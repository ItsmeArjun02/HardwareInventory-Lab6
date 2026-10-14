@extends('layouts.app')

@section('title', 'Add Product')

@section('content')

<div style="max-width: 850px; margin: 0 auto;">
    <div style="
        background: white;
        padding: 30px;
        border-radius: 12px;
        box-shadow: 0 2px 10px rgba(0,0,0,0.08);
    ">
        <div style="margin-bottom: 25px;">
            <h1 style="margin-bottom: 5px;">Add Product</h1>
            <p style="color: #6b7280; margin: 0;">
                Enter the product information below.
            </p>
        </div>

        <form action="{{ route('products.store') }}" method="POST">
            @csrf

            <div style="display:grid; grid-template-columns:1fr 1fr; gap:20px;">
                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Product Name
                    </label>
                    <input
                        type="text"
                        name="name"
                        value="{{ old('name') }}"
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
                        value="{{ old('sku') }}"
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
                    >{{ old('description') }}</textarea>
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Category
                    </label>
                    <input
                        type="text"
                        name="category"
                        value="{{ old('category') }}"
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
                        value="{{ old('supplier') }}"
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Quantity
                    </label>
                    <input
                        type="number"
                        name="quantity"
                        value="{{ old('quantity', 0) }}"
                        min="0"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>

                <div>
                    <label style="display:block; margin-bottom:6px; font-weight:600;">
                        Reorder Level
                    </label>
                    <input
                        type="number"
                        name="reorder_level"
                        value="{{ old('reorder_level', 5) }}"
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
                        value="{{ old('unit_price') }}"
                        min="0"
                        step="0.01"
                        required
                        style="width:100%; padding:10px; border:1px solid #d1d5db; border-radius:6px;"
                    >
                </div>
            </div>

            <div style="margin-top:25px; display:flex; gap:10px;">
                <button type="submit" class="btn btn-success">
                    Save Product
                </button>

                <a href="{{ route('products.index') }}" class="btn btn-secondary">
                    Cancel
                </a>
            </div>
        </form>
    </div>
</div>

@endsection