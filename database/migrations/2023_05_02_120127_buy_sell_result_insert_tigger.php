<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS insert_update_result');
        DB::unprepared("
        CREATE TRIGGER insert_update_result AFTER INSERT ON buy_sell FOR EACH ROW 
        BEGIN
            IF EXISTS (SELECT * FROM buy_sell WHERE exchange = NEW.exchange AND user_id = NEW.user_id AND date = NEW.date AND is_buy = NEW.is_buy AND status = 1 AND id != NEW.id AND deleted_at IS NULL order by id desc LIMIT 1) THEN

                SET @buy_sell_id = (SELECT id FROM buy_sell WHERE exchange = NEW.exchange AND user_id = NEW.user_id AND date = NEW.date AND is_buy = NEW.is_buy AND status = 1 AND id != NEW.id AND deleted_at IS NULL order by id desc LIMIT 1);

                SET @merged_ids = (SELECT merged_ids FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);
                IF (@merged_ids IS NOT NULL OR @merged_ids = '') THEN 
                    SET @merged_ids = CONCAT(@merged_ids,',',NEW.id);

                    SET @total_qty = (SELECT total_quantity FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);
                    SET @total_quantity = @total_qty + NEW.quantity;

                    SET @total_val = (SELECT total_value FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);
                    SET @total_value = @total_val + NEW.value;

                    SET @avg = (SELECT average FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);
                    SET @average = @avg + NEW.average;

                    SET @total_average = (@total_value / @total_quantity);

                    SET @close = (SELECT close FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);
                    
                    SET @is_close_added = (SELECT is_close_added FROM buy_sell_result where buy_sell_id = @buy_sell_id LIMIT 1);

                    IF (@close IS NOT NULL AND NEW.is_buy = 0 AND @is_close_added = 1) THEN
                        SET @difference = @close - @total_average;
                        SET @total_close_value = @close * @total_quantity;
                        SET @profit_loss = @total_close_value - @total_value;
                        SET @profit_loss_per = (@profit_loss / @total_value) * 100;
                    ELSEIF (@close IS NOT NULL AND NEW.is_buy = 1 AND @is_close_added = 1) THEN
                        SET @difference = @total_average - @close;
                        SET @total_close_value = @close * @total_quantity;
                        SET @profit_loss = @total_value - @total_close_value;
                        SET @profit_loss_per = (@profit_loss / @total_value) * 100;
                    ELSE
                        SET @difference = NULL;
                        SET @profit_loss = NULL;
                        SET @profit_loss_per = NULL;
                    END IF;

                    UPDATE buy_sell_result SET buy_sell_id = NEW.id, merged_ids = @merged_ids, average = @average, total_quantity = @total_quantity, total_value = @total_value, total_average = @total_average, difference = @difference, profit_loss = @profit_loss, profit_loss_per = @profit_loss_per WHERE buy_sell_id = @buy_sell_id;
                ELSE
                    INSERT INTO buy_sell_result VALUES(null, NEW.user_id, NEW.id, NEW.id, NEW.average, NEW.quantity, (NEW.value/NEW.quantity), NEW.value,0,null,null,null,null,NOW(),NOW(),null);
                END IF;
            ELSE
                INSERT INTO buy_sell_result VALUES(null, NEW.user_id, NEW.id, NEW.id, NEW.average, NEW.quantity, (NEW.value/NEW.quantity), NEW.value,0,null,null,null,null,NOW(),NOW(),null);
            END IF; 
        END");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        DB::unprepared('DROP TRIGGER IF EXISTS insert_update_result');
    }
};
