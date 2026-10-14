@extends('layouts.app')

@section('title', 'Edit Product')

@section('content')

<div style="max-width: 850px; margin: 0 auto;">
    <div style="
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    ">
        <div style="margin-bottom: 25px;">
            <h1 style="margin-bottom: 5px;">Edit Product</h1>
            <p style="color: #6b7280; margin: 0;">
                Update the product information below.
            </p>
        </div>

        <form action="{{ route('products.update', $product) }}" method="POST">
            @csrf
            @method('PUT')

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Product Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name', $product->name) }}"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        SKU
                    </label>
                    <input
                        type="text"
                        name="sku"
                        value="{{ old('sku', $product->sku) }}"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Description
                    </label>
                    <textarea
                        name="description"
                        rows="4"
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >{{ old('description', $product->description) }}</textarea>
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Category
                    </label>
                    <input
                        type="text"
                        name="category"
                        value="{{ old('category', $product->category) }}"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Supplier
                    </label>
                    <input
                        type="text"
                        name="supplier"
                        value="{{ old('supplier', $product->supplier) }}"
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Current Quantity (managed by transactions)
                    </label>
                    <input type="number" value="{{ $product->quantity }}" readonly style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px; background:#f3f4f6;">
                    <small>Use Record Transaction or Stock Adjustment to change quantity and preserve the audit trail.</small>
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Reorder Level
                    </label>
                    <input
                        type="number"
                        name="reorder_level"
                        value="{{ old('reorder_level', $product->reorder_level) }}"
                        min="0"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div style="grid-column:1 / -1;">
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Unit Price
                    </label>
                    <input
                        type="number"
                        name="unit_price"
                        value="{{ old('unit_price', $product->unit_price) }}"
                        min="0"
                        step="0.01"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>
            </div>

            <div style="margin-top:25px; display:flex; gap:10px;">
                <button type="submit" class="btn btn-success">
                    Update Product
                </button>

                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection