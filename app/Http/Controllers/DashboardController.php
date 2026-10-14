<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;

class DashboardController extends Controller
{
    public function index()
    {
        $totalProducts = Product::count();
        $lowStockItems = Product::lowStock()->where('quantity', '>', 0)->count();
        $outOfStockItems = Product::outOfStock()->count();
        $inventoryValue = (float) Product::query()->selectRaw('COALESCE(SUM(quantity * unit_price), 0) AS total')->value('total');
        $recentProducts = Product::latest()->take(5)->get();
        $lowStockProducts = Product::query()->whereColumn('quantity', '<=', 'reorder_level')->orderBy('quantity')->limit(8)->get();
        $recentTransactions = InventoryTransaction::with(['product', 'user'])->latest()->limit(8)->get();
        return view('dashboard', compact('totalProducts', 'lowStockItems', 'outOfStockItems', 'inventoryValue', 'recentProducts', 'lowStockProducts', 'recentTransactions'));
    }
}
