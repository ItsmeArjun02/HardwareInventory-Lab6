<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class AdjustmentController extends Controller
{
    public function create()
    {
        $products = Product::orderBy('name')->get();
        return view('adjustments.create', compact('products'));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'physical_count' => ['required', 'integer', 'min:0', 'max:1000000'],
            'reason' => ['required', 'in:physical_count,damage,loss,system_error,other'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);
        DB::transaction(function () use ($data, $request) {
            $product = Product::whereKey($data['product_id'])->lockForUpdate()->firstOrFail();
            $difference = (int) $data['physical_count'] - (int) $product->quantity;
            if ($difference === 0) throw ValidationException::withMessages(['physical_count' => 'The physical count matches system stock. No adjustment is needed.']);
            InventoryTransaction::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => $difference > 0 ? 'stock_in' : 'stock_out',
                'quantity' => abs($difference),
                'quantity_before' => $product->quantity,
                'quantity_after' => $product->quantity,
                'reference' => 'ADJ-'.now()->format('YmdHis'),
                'notes' => 'Stock adjustment ('.$data['reason'].'). '.($data['notes'] ?? ''),
            ]);
        });
        return redirect()->route('inventory-transactions.index')->with('success', 'Stock adjustment recorded and audit trail updated.');
    }
}
