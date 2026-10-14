@extends('layouts.app')
@section('title','Transaction Details')
@section('content')
<h1>Transaction Details #{{ $inventoryTransaction->id }}</h1>
<div style="display:grid;grid-template-columns:repeat(auto-fit,minmax(260px,1fr));gap:16px">
<section style="background:white;padding:20px;border-radius:8px"><h2>Movement</h2><p><b>Type:</b> {{ str_replace('_',' ',ucfirst($inventoryTransaction->type)) }}</p><p><b>Quantity:</b> {{ $inventoryTransaction->signed_quantity }}</p><p><b>Before:</b> {{ $inventoryTransaction->quantity_before }}</p><p><b>After:</b> {{ $inventoryTransaction->quantity_after }}</p><p><b>Date:</b> {{ $inventoryTransaction->created_at->format('M d, Y H:i:s') }}</p><p><b>Reference:</b> {{ $inventoryTransaction->reference ?: '—' }}</p><p><b>Notes:</b> {{ $inventoryTransaction->notes ?: '—' }}</p></section>
<section style="background:white;padding:20px;border-radius:8px"><h2>Product</h2>@if($inventoryTransaction->product)<p><b>Name:</b> <a href="{{ route('products.show',$inventoryTransaction->product) }}">{{ $inventoryTransaction->product->name }}</a></p><p><b>SKU:</b> {{ $inventoryTransaction->product->sku }}</p><p><b>Current stock:</b> {{ $inventoryTransaction->product->quantity }}</p>@else<p>Product was deleted.</p>@endif</section>
<section style="background:white;padding:20px;border-radius:8px"><h2>Accountability</h2><p><b>User:</b> {{ $inventoryTransaction->user?->name ?? 'Unknown user' }}</p><p><b>Email:</b> {{ $inventoryTransaction->user?->email ?? '—' }}</p></section>
</div><p style="margin-top:20px"><a class="btn btn-secondary" href="{{ route('inventory-transactions.index') }}">Back to Transactions</a></p>
@endsection
