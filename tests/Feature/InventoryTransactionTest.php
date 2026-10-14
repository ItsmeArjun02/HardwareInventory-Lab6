<?php

namespace Tests\Feature;

use App\Models\InventoryTransaction;
use App\Models\Product;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Queue;
use Tests\TestCase;

class InventoryTransactionTest extends TestCase
{
    use RefreshDatabase;

    protected function setUp(): void
    {
        parent::setUp();
        Queue::fake();
    }

    public function test_stock_in_transaction_increases_quantity_and_records_balance(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['name'=>'Test Product','sku'=>'TEST-001','category'=>'Test','quantity'=>5,'reorder_level'=>2,'unit_price'=>10]);
        $this->actingAs($user)->post(route('inventory-transactions.store'), ['product_id'=>$product->id,'type'=>'stock_in','quantity'=>3,'reference'=>'PO-1','notes'=>'Delivery'])->assertRedirect(route('inventory-transactions.index'));
        $this->assertSame(8, $product->fresh()->quantity);
        $this->assertDatabaseHas('inventory_transactions', ['product_id'=>$product->id,'quantity'=>3,'quantity_before'=>5,'quantity_after'=>8,'user_id'=>$user->id]);
    }

    public function test_stock_out_decreases_quantity_and_excess_stock_out_is_rejected(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['name'=>'Test Product','sku'=>'TEST-002','category'=>'Test','quantity'=>5,'reorder_level'=>2,'unit_price'=>10]);
        $this->actingAs($user)->post(route('inventory-transactions.store'), ['product_id'=>$product->id,'type'=>'stock_out','quantity'=>2])->assertRedirect(route('inventory-transactions.index'));
        $this->assertSame(3, $product->fresh()->quantity);
        $this->actingAs($user)->from(route('inventory-transactions.create'))->post(route('inventory-transactions.store'), ['product_id'=>$product->id,'type'=>'stock_out','quantity'=>99])->assertSessionHasErrors('quantity');
        $this->assertSame(3, $product->fresh()->quantity);
        $this->assertSame(1, InventoryTransaction::where('product_id',$product->id)->count());
    }

    public function test_stock_adjustment_uses_a_transaction_and_updates_quantity(): void
    {
        $user = User::factory()->create();
        $product = Product::create(['name'=>'Test Product','sku'=>'TEST-003','category'=>'Test','quantity'=>5,'reorder_level'=>2,'unit_price'=>10]);
        $this->actingAs($user)->post(route('adjustments.store'), ['product_id'=>$product->id,'physical_count'=>8,'reason'=>'physical_count','notes'=>'Counted on shelf'])->assertRedirect(route('inventory-transactions.index'));
        $this->assertSame(8, $product->fresh()->quantity);
        $this->assertDatabaseHas('inventory_transactions', ['product_id'=>$product->id,'type'=>'stock_in','quantity'=>3,'quantity_before'=>5,'quantity_after'=>8]);
    }
}
