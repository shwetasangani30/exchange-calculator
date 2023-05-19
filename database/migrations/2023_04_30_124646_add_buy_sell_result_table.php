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
        Schema::create('buy_sell_result', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedBigInteger('user_id');
            $table->foreign('user_id')->references('id')->on('users');
            $table->unsignedBigInteger('buy_sell_id');
            $table->foreign('buy_sell_id')->references('id')->on('buy_sell');
            $table->string('merged_ids')->nullable();
            $table->double('average', 20, 2)->nullable();
            $table->double('total_quantity', 20, 2)->nullable();
            $table->double('total_average', 20, 2)->nullable();
            $table->double('total_value', 20, 2)->nullable();
            $table->integer('is_close_added')->default(0);
            $table->double('close', 20, 2)->nullable();
            $table->double('difference', 20, 2)->nullable();
            $table->double('profit_loss', 20, 2)->nullable();
            $table->double('profit_loss_per', 20, 2)->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        //
    }
};
