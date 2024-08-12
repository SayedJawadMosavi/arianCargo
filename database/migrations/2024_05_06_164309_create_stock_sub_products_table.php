<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStockSubProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('stock_sub_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('stock_product_id')->constrained();
            $table->foreignId('sub_product_id')->constrained();
            $table->integer('quantity')->default(0)->unsigned();
            $table->integer('available')->default(0)->unsigned();
            $table->double('cost')->unsigned()->default(0);
            $table->double('expense')->nullable();
            $table->double('rent')->nullable();
            $table->double('other')->nullable();
            $table->double('income_price')->unsigned()->default(0);
            $table->double('sell_price')->unsigned()->default(0);
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreignId('branch_id')->constrained();
            $table->softDeletes();
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
        Schema::dropIfExists('stock_sub_products');
    }
}
