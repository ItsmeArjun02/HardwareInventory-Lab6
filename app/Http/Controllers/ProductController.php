<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\InventoryTransaction;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use Illuminate\Support\Facades\DB;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::latest()->get();

        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => 'required|string|max:100|unique:products,sku',
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'quantity' => 'required|integer|min:0',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        DB::transaction(function () use ($validated, $request) {
            $initialQuantity = (int) $validated['quantity'];
            $validated['quantity'] = 0;
            $product = Product::create($validated);
            if ($initialQuantity > 0) {
                InventoryTransaction::create([
                    'product_id' => $product->id, 'user_id' => $request->user()->id,
                    'type' => 'stock_in', 'quantity' => $initialQuantity,
                    'quantity_before' => 0, 'quantity_after' => 0,
                    'reference' => 'INITIAL-'.$product->sku,
                    'notes' => 'Opening stock recorded when product was created.',
                ]);
            }
        });

        return redirect()
            ->route('products.index')
            ->with('success', 'Product added successfully.');
    }

    public function show(Request $request, Product $product)
    {
        $transactionQuery = $product->transactions()->with('user')->latest();
        if ($request->filled('date_from')) $transactionQuery->whereDate('created_at', '>=', $request->input('date_from'));
        if ($request->filled('date_to')) $transactionQuery->whereDate('created_at', '<=', $request->input('date_to'));
        $transactions = $transactionQuery->paginate(10)->withQueryString();
        return view('products.show', compact('product', 'transactions'));
    }

    public function edit(Product $product)
    {
        return view('products.edit', compact('product'));
    }

    public function update(Request $request, Product $product)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'sku' => [
                'required',
                'string',
                'max:100',
                Rule::unique('products', 'sku')->ignore($product->id),
            ],
            'description' => 'nullable|string',
            'category' => 'required|string|max:100',
            'reorder_level' => 'required|integer|min:0',
            'unit_price' => 'required|numeric|min:0',
            'supplier' => 'nullable|string|max:255',
        ]);

        // Quantity changes must go through an inventory transaction so the audit trail stays complete.
        $product->update($validated);

        return redirect()
            ->route('products.index')
            ->with('success', 'Product updated successfully.');
    }

    public function destroy(Product $product)
    {
        $product->delete();

        return redirect()
            ->route('products.index')
            ->with('success', 'Product deleted successfully.');
    }
}