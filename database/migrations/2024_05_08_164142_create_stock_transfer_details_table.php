<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockTransferDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_transfer_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_transfer_id')->constrained();
            $table->foreignId('sender_stock_sub_product_id')->references('id')->on('stock_sub_products')->constrained();
            $table->foreignId('receiver_stock_sub_product_id')->references('id')->on('stock_sub_products')->constrained();
            $table->integer('quantity')->unsigned();
            // $table->integer('available')->unsigned();
            $table->foreignId('user_id')->constrained();
            $table->foreignId('branch_id')->constrained();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('stock_transfer_details');
    }
}
