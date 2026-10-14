@extends('layouts.app')
@section('title', 'Inventory Transactions')
@section('content')
<div style="display:flex;justify-content:space-between;align-items:center;gap:12px;flex-wrap:wrap">
    <div><h1>Inventory Transactions</h1><p>Auditable record of every stock movement.</p></div>
    <div><a class="btn btn-primary" href="{{ route('inventory-transactions.create') }}">+ Record Transaction</a> <a class="btn btn-warning" href="{{ route('adjustments.create') }}">Stock Adjustment</a></div>
</div>
<form method="GET" class="filter-panel" style="display:flex;gap:10px;flex-wrap:wrap;margin:18px 0;padding:15px;background:white;border-radius:8px">
    <select name="product_id"><option value="">All products</option>@foreach($products as $product)<option value="{{ $product->id }}" @selected(request('product_id') == $product->id)>{{ $product->name }}</option>@endforeach</select>
    <select name="type"><option value="">All types</option>@foreach(\App\Models\InventoryTransaction::TYPES as $type)<option value="{{ $type }}" @selected(request('type') === $type)>{{ str_replace('_',' ',ucfirst($type)) }}</option>@endforeach</select>
    <label>From <input type="date" name="date_from" value="{{ request('date_from') }}"></label>
    <label>To <input type="date" name="date_to" value="{{ request('date_to') }}"></label>
    <button class="btn btn-primary" type="submit">Filter</button><a class="btn btn-secondary" href="{{ route('inventory-transactions.index') }}">Reset</a>
</form>
<div style="overflow-x:auto;background:white;border-radius:8px;padding:12px"><table><thead><tr><th>Date/Time</th><th>Product</th><th>Type</th><th>Qty</th><th>Balance Before → After</th><th>Reference</th><th>User</th><th>Actions</th></tr></thead><tbody>
@forelse($transactions as $transaction)
<tr><td>{{ $transaction->created_at->format('M d, Y H:i') }}</td><td>@if($transaction->product)<a href="{{ route('products.show',$transaction->product) }}">{{ $transaction->product->name }}</a>@else Deleted product @endif</td><td><span class="badge {{ in_array($transaction->type,['stock_in','return'])?'success':($transaction->type==='damaged'?'danger':'warning') }}">{{ str_replace('_',' ',ucfirst($transaction->type)) }}</span></td><td>{{ $transaction->signed_quantity }}</td><td>{{ $transaction->quantity_before }} → {{ $transaction->quantity_after }}</td><td>{{ $transaction->reference ?: '—' }}</td><td>{{ $transaction->user?->name ?? 'Unknown' }}</td><td><a href="{{ route('inventory-transactions.show',$transaction) }}">View</a></td></tr>
@empty<tr><td colspan="8" style="text-align:center;padding:24px">No transactions recorded yet.</td></tr>@endforelse
</tbody></table></div>
<div style="margin-top:18px">{{ $transactions->links() }}</div>
@endsection
