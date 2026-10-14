@extends('layouts.app')

@section('title', 'Products')

@section('content')

<div style="display:flex; justify-content:space-between; align-items:center; margin-bottom:20px;">
    <h1>Products</h1>

    <a href="{{ route('products.create') }}" class="btn btn-primary">
        Add Product
    </a>
</div>

<table>
    <thead>
        <tr>
            <th>Name</th>
            <th>SKU</th>
            <th>Category</th>
            <th>Quantity</th>
            <th>Unit Price</th>
            <th>Supplier</th>
            <th>Status</th>
            <th>Actions</th>
        </tr>
    </thead>

    <tbody>
        @forelse($products as $product)
            <tr>
                <td>{{ $product->name }}</td>
                <td>{{ $product->sku }}</td>
                <td>{{ $product->category }}</td>
                <td>{{ $product->quantity }}</td>
                <td>₱{{ number_format($product->unit_price, 2) }}</td>
                <td>{{ $product->supplier ?? 'N/A' }}</td>

                <td>
                    @if($product->quantity == 0)
                        <span class="low-stock">Out of Stock</span>
                    @elseif($product->isLowStock())
                        <span class="low-stock">Low Stock</span>
                    @else
                        In Stock
                    @endif
                </td>

                <td>
                    <a href="{{ route('products.show', $product) }}"
                       class="btn btn-secondary">
                        View
                    </a>

                    <a href="{{ route('products.edit', $product) }}"
                       class="btn btn-warning">
                        Edit
                    </a>

                    <form action="{{ route('products.destroy', $product) }}"
                          method="POST"
                          style="display:inline;"
                          onsubmit="return confirm('Are you sure you want to delete this product?');">

                        @csrf
                        @method('DELETE')

                        <button type="submit" class="btn btn-danger">
                            Delete
                        </button>
                    </form>
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="8" style="text-align:center;">
                    No products found.
                </td>
            </tr>
        @endforelse
    </tbody>
</table>

@endsection