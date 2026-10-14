<?php

namespace App\Providers;

use App\Events\InventoryTransactionRecorded;
use App\Listeners\LogInventoryTransaction;
use App\Models\InventoryTransaction;
use App\Observers\InventoryTransactionObserver;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void {}
    public function boot(): void
    {
        InventoryTransaction::observe(InventoryTransactionObserver::class);
        Event::listen(InventoryTransactionRecorded::class, LogInventoryTransaction::class);
    }
}
