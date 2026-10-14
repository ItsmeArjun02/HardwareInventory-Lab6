<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Product extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'sku', 'description', 'category', 'quantity', 'reorder_level', 'unit_price', 'supplier'];

    protected function casts(): array
    {
        return ['quantity' => 'integer', 'reorder_level' => 'integer', 'unit_price' => 'decimal:2'];
    }

    public function transactions(): HasMany { return $this->hasMany(InventoryTransaction::class); }
    public function inventoryTransactions(): HasMany { return $this->transactions(); }
    public function latestTransaction(): HasOne { return $this->hasOne(InventoryTransaction::class)->latestOfMany(); }

    public function isLowStock(): bool { return $this->quantity <= $this->reorder_level && $this->quantity > 0; }
    public function isOutOfStock(): bool { return $this->quantity <= 0; }
    public function getStockStatusAttribute(): string
    {
        if ($this->isOutOfStock()) return 'Out of Stock';
        if ($this->isLowStock()) return 'Low Stock';
        return 'In Stock';
    }
    public function getStockStatusColorAttribute(): string
    {
        if ($this->isOutOfStock()) return 'danger';
        if ($this->isLowStock()) return 'warning';
        return 'success';
    }
    public function scopeLowStock(Builder $query): Builder
    { return $query->whereColumn('quantity', '<=', 'reorder_level'); }
    public function scopeOutOfStock(Builder $query): Builder { return $query->where('quantity', '<=', 0); }
    public function scopeInCategory(Builder $query, string $category): Builder { return $query->where('category', $category); }
}
