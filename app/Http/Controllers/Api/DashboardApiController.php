<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\InventoryTransaction;
use App\Models\Product;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Resources\Json\AnonymousResourceCollection;

class DashboardApiController extends Controller
{
    public function stats(): JsonResponse
    {
        $value = Product::query()->selectRaw('COALESCE(SUM(quantity * unit_price), 0) AS total')->value('total');
        return response()->json(['data' => [
            'total_products' => Product::count(),
            'low_stock_items' => Product::lowStock()->where('quantity', '>', 0)->count(),
            'out_of_stock_items' => Product::outOfStock()->count(),
            'inventory_value' => (float) $value,
            'last_updated' => now()->toISOString(),
        ]]);
    }
    public function productStock(Product $product): JsonResponse
    {
        return response()->json(['data' => ['id' => $product->id, 'name' => $product->name, 'sku' => $product->sku, 'quantity' => $product->quantity, 'reorder_level' => $product->reorder_level]]);
    }

    public function lowStock(): AnonymousResourceCollection
    {
        return ProductResource::collection(Product::query()->whereColumn('quantity', '<=', 'reorder_level')->orderBy('quantity')->get());
    }
    public function recentTransactions(): JsonResponse
    {
        $items = InventoryTransaction::with(['product:id,name,sku', 'user:id,name'])->latest()->limit(10)->get();
        return response()->json(['data' => $items->map(fn ($t) => [
            'id' => $t->id, 'date' => $t->created_at?->toISOString(), 'product' => $t->product?->name ?? 'Deleted product',
            'sku' => $t->product?->sku, 'type' => $t->type, 'quantity' => $t->signed_quantity,
            'quantity_before' => $t->quantity_before, 'quantity_after' => $t->quantity_after,
            'user' => $t->user?->name ?? 'Unknown user', 'reference' => $t->reference,
        ])]);
    }
}
