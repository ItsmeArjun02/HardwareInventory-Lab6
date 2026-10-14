@extends('layouts.app')
@section('title','Record Inventory Transaction')
@section('content')
<h1>Record Inventory Transaction</h1><p>Use stock-in for received goods, stock-out for sales/issues, return for returned goods, and damaged for write-offs.</p>
<form method="POST" action="{{ route('inventory-transactions.store') }}" style="max-width:760px;background:white;padding:24px;border-radius:10px">@csrf
<div style="margin-bottom:16px"><label for="product_id">Product *</label><select id="product_id" name="product_id" required style="display:block;width:100%"><option value="">Select product</option>@foreach($products as $product)<option value="{{ $product->id }}" data-stock="{{ $product->quantity }}" data-sku="{{ $product->sku }}" @selected(old('product_id')==$product->id)>{{ $product->name }} ({{ $product->sku }})</option>@endforeach</select><small id="stock-help">Choose a product to see its current stock.</small></div>
<div style="margin-bottom:16px"><label for="type">Transaction type *</label><select id="type" name="type" required style="display:block;width:100%">@foreach($types as $type)<option value="{{ $type }}" @selected(old('type','stock_in')===$type)>{{ str_replace('_',' ',ucfirst($type)) }}</option>@endforeach</select></div>
<div style="margin-bottom:16px"><label for="quantity">Quantity *</label><input id="quantity" type="number" name="quantity" min="1" max="1000000" value="{{ old('quantity',1) }}" required style="display:block;width:100%"></div>
<div style="margin-bottom:16px"><label for="reference">Reference number</label><input id="reference" name="reference" maxlength="255" value="{{ old('reference') }}" style="display:block;width:100%" placeholder="PO, sales order, delivery slip"></div>
<div style="margin-bottom:16px"><label for="notes">Notes</label><textarea id="notes" name="notes" maxlength="2000" rows="4" style="display:block;width:100%">{{ old('notes') }}</textarea></div>
<button class="btn btn-primary" type="submit">Save Transaction</button> <a class="btn btn-secondary" href="{{ route('inventory-transactions.index') }}">Cancel</a>
</form>
<script>
(() => { const product=document.getElementById('product_id'), type=document.getElementById('type'), qty=document.getElementById('quantity'), help=document.getElementById('stock-help'); function update(){const opt=product.options[product.selectedIndex];const stock=opt?.dataset?.stock;if(stock!==undefined&&stock!==''){help.textContent='Current stock: '+stock+' unit(s).';qty.max=(type.value==='stock_out'||type.value==='damaged')?stock:'1000000';if((type.value==='stock_out'||type.value==='damaged')&&Number(qty.value)>Number(stock))qty.value=stock;}else{help.textContent='Choose a product to see its current stock.';qty.max='1000000';}} product.addEventListener('change',update);type.addEventListener('change',update);update(); })();
</script>
@endsection
