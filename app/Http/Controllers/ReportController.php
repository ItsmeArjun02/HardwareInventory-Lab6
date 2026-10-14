<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class ReportController extends Controller
{
    public function inventory(Request $request)
    {
        $products = Product::orderBy('category')->orderBy('name')->get();
        $totalProducts = Product::count();
        $lowStockItems = Product::lowStock()->where('quantity', '>', 0)->count();
        $outOfStockItems = Product::outOfStock()->count();
        $inventoryValue = (float) Product::query()->selectRaw('COALESCE(SUM(quantity * unit_price), 0) AS total')->value('total');
        $lowStockProducts = Product::query()->whereColumn('quantity', '<=', 'reorder_level')->orderBy('quantity')->get();
        $transactionQuery = InventoryTransaction::with(['product', 'user'])->latest();
        if ($request->filled('date_from')) $transactionQuery->whereDate('created_at', '>=', $request->input('date_from'));
        if ($request->filled('date_to')) $transactionQuery->whereDate('created_at', '<=', $request->input('date_to'));
        $transactions = $transactionQuery->paginate(20)->withQueryString();
        return view('reports.inventory', compact('products', 'totalProducts', 'lowStockItems', 'outOfStockItems', 'inventoryValue', 'lowStockProducts', 'transactions'));
    }

    public function exportTransactions(Request $request): StreamedResponse
    {
        $query = InventoryTransaction::with(['product', 'user'])->latest();
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->input('date_from'));
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->input('date_to'));
        return response()->streamDownload(function () use ($query) {
            $out = fopen('php://output', 'w');
            fputcsv($out, ['Date/Time', 'Product', 'SKU', 'Type', 'Quantity', 'Before', 'After', 'Reference', 'User', 'Notes']);
            $query->chunk(500, function ($transactions) use ($out) {
                foreach ($transactions as $t) fputcsv($out, [$t->created_at, $t->product?->name, $t->product?->sku, $t->type, $t->signed_quantity, $t->quantity_before, $t->quantity_after, $t->reference, $t->user?->name, $t->notes]);
            });
            fclose($out);
        }, 'inventory-transactions.csv', ['Content-Type' => 'text/csv; charset=UTF-8']);
    }
}
