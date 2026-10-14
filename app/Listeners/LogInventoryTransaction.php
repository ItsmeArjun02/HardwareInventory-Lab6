<?php

namespace App\Listeners;

use App\Events\InventoryTransactionRecorded;
use Illuminate\Support\Facades\Log;

class LogInventoryTransaction
{
    public function handle(InventoryTransactionRecorded $event): void
    {
        Log::info('Inventory transaction recorded', [
            'transaction_id' => $event->transaction->id,
            'product_id' => $event->transaction->product_id,
            'type' => $event->transaction->type,
            'quantity' => $event->transaction->quantity,
            'user_id' => $event->transaction->user_id,
        ]);
    }
}
