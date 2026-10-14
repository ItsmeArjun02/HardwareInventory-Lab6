<?php

namespace App\Observers;

use App\Events\InventoryTransactionRecorded;
use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use App\Notifications\LowStockNotification;
use Illuminate\Support\Facades\Notification;
use Illuminate\Validation\ValidationException;

class InventoryTransactionObserver
{
    public function created(InventoryTransaction $transaction): void
    {
        // The controller creates transactions inside DB::transaction(). The lock also protects
        // stock updates if transactions are submitted concurrently.
        $product = Product::query()->lockForUpdate()->find($transaction->product_id);
        if (!$product) throw ValidationException::withMessages(['product_id' => 'The selected product no longer exists.']);

        $before = (int) $product->quantity;
        $increase = in_array($transaction->type, ['stock_in', 'return'], true);
        $after = $increase ? $before + $transaction->quantity : $before - $transaction->quantity;
        if ($after < 0) throw ValidationException::withMessages(['quantity' => 'Stock-out quantity cannot exceed the available quantity.']);

        $product->quantity = $after;
        $product->saveQuietly();

        $transaction->quantity_before = $before;
        $transaction->quantity_after = $after;
        $transaction->saveQuietly();

        $wasAtOrAboveReorder = $before > $product->reorder_level;
        $isNowLow = $after <= $product->reorder_level;
        if ($wasAtOrAboveReorder && $isNowLow) {
            $users = User::query()->get();
            if ($users->isNotEmpty()) Notification::send($users, new LowStockNotification($product));
        }

        InventoryTransactionRecorded::dispatch($transaction->fresh(['product', 'user']));
    }
}
