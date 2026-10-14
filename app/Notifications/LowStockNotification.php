<?php

namespace App\Notifications;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class LowStockNotification extends Notification implements ShouldQueue
{
    use Queueable;
    public function __construct(public Product $product) {}
    public function via(object $notifiable): array { return ['database', 'mail']; }
    public function toDatabase(object $notifiable): array
    {
        return [
            'title' => 'Low stock alert',
            'message' => "{$this->product->name} (SKU: {$this->product->sku}) has {$this->product->quantity} unit(s) remaining.",
            'product_id' => $this->product->id,
            'product_name' => $this->product->name,
            'sku' => $this->product->sku,
            'quantity' => $this->product->quantity,
            'reorder_level' => $this->product->reorder_level,
            'action' => 'Review stock and reorder if needed.',
        ];
    }
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Low stock alert: '.$this->product->name)
            ->greeting('Inventory low-stock alert')
            ->line("{$this->product->name} (SKU: {$this->product->sku}) has {$this->product->quantity} unit(s) remaining.")
            ->line('Reorder level: '.$this->product->reorder_level)
            ->line('Please review this item and reorder if needed.');
    }
}
