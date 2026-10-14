<?php

namespace App\Http\Controllers;

use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Validation\ValidationException;

class InventoryTransactionController extends Controller
{
    public function index(Request $request)
    {
        $query = InventoryTransaction::with(['product', 'user'])->latest();
        if ($request->filled('product_id')) $query->where('product_id', $request->integer('product_id'));
        if ($request->filled('type')) $query->where('type', $request->string('type')->toString());
        if ($request->filled('date_from')) $query->whereDate('created_at', '>=', $request->input('date_from'));
        if ($request->filled('date_to')) $query->whereDate('created_at', '<=', $request->input('date_to'));
        $transactions = $query->paginate(20)->withQueryString();
        $products = Product::orderBy('name')->get(['id', 'name']);
        return view('inventory-transactions.index', compact('transactions', 'products'));
    }

    public function create()
    {
        $products = Product::orderBy('name')->get();
        $types = InventoryTransaction::TYPES;
        return view('inventory-transactions.create', compact('products', 'types'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'product_id' => ['required', 'integer', 'exists:products,id'],
            'type' => ['required', 'in:'.implode(',', InventoryTransaction::TYPES)],
            'quantity' => ['required', 'integer', 'min:1', 'max:1000000'],
            'reference' => ['nullable', 'string', 'max:255'],
            'notes' => ['nullable', 'string', 'max:2000'],
        ]);

        DB::transaction(function () use ($validated, $request) {
            $product = Product::whereKey($validated['product_id'])->lockForUpdate()->firstOrFail();
            if (in_array($validated['type'], ['stock_out', 'damaged'], true) && $validated['quantity'] > $product->quantity) {
                throw ValidationException::withMessages(['quantity' => "Only {$product->quantity} unit(s) are currently available."]);
            }
            InventoryTransaction::create([
                'product_id' => $product->id,
                'user_id' => $request->user()->id,
                'type' => $validated['type'],
                'quantity' => $validated['quantity'],
                'quantity_before' => $product->quantity,
                'quantity_after' => $product->quantity,
                'reference' => $validated['reference'] ?? null,
                'notes' => $validated['notes'] ?? null,
            ]);
        });

        return redirect()->route('inventory-transactions.index')->with('success', 'Inventory transaction recorded successfully.');
    }

    public function show(InventoryTransaction $inventoryTransaction)
    {
        $inventoryTransaction->load(['product', 'user']);
        return view('inventory-transactions.show', compact('inventoryTransaction'));
    }

    public function destroy(InventoryTransaction $inventoryTransaction)
    {
        // Audit records are immutable: deleting a transaction would make stock history unreliable.
        abort(403, 'Inventory transactions are audit records and cannot be deleted.');
    }
}
