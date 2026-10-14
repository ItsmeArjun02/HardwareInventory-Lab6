<?php

use App\Http\Controllers\AdjustmentController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\InventoryTransactionController;
use App\Http\Controllers\NotificationController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ReportController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () { return auth()->check() ? redirect()->route('dashboard') : redirect()->route('login'); });
Route::get('/dashboard', [DashboardController::class, 'index'])->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::resource('products', ProductController::class);
    Route::get('/inventory-transactions', [InventoryTransactionController::class, 'index'])->name('inventory-transactions.index');
    Route::get('/inventory-transactions/create', [InventoryTransactionController::class, 'create'])->name('inventory-transactions.create');
    Route::post('/inventory-transactions', [InventoryTransactionController::class, 'store'])->name('inventory-transactions.store');
    Route::get('/inventory-transactions/{inventoryTransaction}', [InventoryTransactionController::class, 'show'])->name('inventory-transactions.show');
    Route::delete('/inventory-transactions/{inventoryTransaction}', [InventoryTransactionController::class, 'destroy'])->name('inventory-transactions.destroy');
    Route::get('/adjustments/create', [AdjustmentController::class, 'create'])->name('adjustments.create');
    Route::post('/adjustments', [AdjustmentController::class, 'store'])->name('adjustments.store');
    Route::get('/reports/inventory', [ReportController::class, 'inventory'])->name('reports.inventory');
    Route::get('/reports/inventory/export', [ReportController::class, 'exportTransactions'])->name('reports.inventory.export');
    Route::get('/notifications', [NotificationController::class, 'index'])->name('notifications.index');
    Route::patch('/notifications/{notification}/read', [NotificationController::class, 'markRead'])->name('notifications.read');
    Route::patch('/notifications/read-all', [NotificationController::class, 'markAllRead'])->name('notifications.read-all');

    // Authenticated JSON endpoints used by dashboard polling.
    Route::prefix('api')->name('api.')->group(function () {
        Route::get('/dashboard/stats', [\App\Http\Controllers\Api\DashboardApiController::class, 'stats'])->name('dashboard.stats');
        Route::get('/products/low-stock', [\App\Http\Controllers\Api\DashboardApiController::class, 'lowStock'])->name('products.low-stock');
        Route::get('/products/{product}/stock', [\App\Http\Controllers\Api\DashboardApiController::class, 'productStock'])->name('products.stock');
        Route::get('/transactions/recent', [\App\Http\Controllers\Api\DashboardApiController::class, 'recentTransactions'])->name('transactions.recent');
    });
});

require __DIR__.'/auth.php';
