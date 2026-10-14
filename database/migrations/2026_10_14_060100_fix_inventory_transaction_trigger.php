<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') return;
        DB::unprepared('DROP TRIGGER IF EXISTS inventory_transactions_after_insert');
        DB::unprepared('DROP TRIGGER IF EXISTS inventory_transactions_before_insert');
        DB::unprepared("
            CREATE TRIGGER inventory_transactions_before_insert
            BEFORE INSERT ON inventory_transactions
            FOR EACH ROW
            BEGIN
                DECLARE current_stock INT DEFAULT 0;
                IF NEW.product_id IS NOT NULL AND NEW.type IN ('stock_out', 'damaged') THEN
                    SELECT quantity INTO current_stock FROM products WHERE id = NEW.product_id;
                    IF NEW.quantity > current_stock THEN
                        SIGNAL SQLSTATE '45000' SET MESSAGE_TEXT = 'Insufficient stock for this transaction';
                    END IF;
                END IF;
            END
        ");
    }

    public function down(): void
    {
        if (DB::getDriverName() === 'mysql') {
            DB::unprepared('DROP TRIGGER IF EXISTS inventory_transactions_before_insert');
        }
    }
};
