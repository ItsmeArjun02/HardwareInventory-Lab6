<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class InventoryTransaction extends Model
{
    public const TYPES = ['stock_in', 'stock_out', 'return', 'damaged'];
    protected $fillable = ['product_id', 'user_id', 'type', 'quantity', 'quantity_before', 'quantity_after', 'reference', 'notes'];
    protected function casts(): array { return ['quantity' => 'integer', 'quantity_before' => 'integer', 'quantity_after' => 'integer']; }
    public function product(): BelongsTo { return $this->belongsTo(Product::class); }
    public function user(): BelongsTo { return $this->belongsTo(User::class); }
    public function scopeStockIn(Builder $query): Builder { return $query->whereIn('type', ['stock_in', 'return']); }
    public function scopeStockOut(Builder $query): Builder { return $query->whereIn('type', ['stock_out', 'damaged']); }
    public function scopeRecent(Builder $query): Builder { return $query->latest(); }
    public function getSignedQuantityAttribute(): string
    { return in_array($this->type, ['stock_in', 'return'], true) ? '+'.$this->quantity : '-'.$this->quantity; }
}
