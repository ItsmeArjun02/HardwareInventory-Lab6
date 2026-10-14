<?php

namespace App\Events;

use App\Models\InventoryTransaction;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InventoryTransactionRecorded
{
    use Dispatchable, InteractsWithSockets, SerializesModels;
    public function __construct(public InventoryTransaction $transaction) {}
}
