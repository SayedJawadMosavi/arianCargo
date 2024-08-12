<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSubProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sub_products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('product_id')->constrained();
            $table->foreignId('receive_id')->constrained();
            $table->foreignId('purchase_id')->constrained();
            $table->integer('quantity')->unsigned()->default(0);
            $table->integer('available')->unsigned()->default(0);
            $table->double('cost')->unsigned()->default(0);
            $table->double('expense')->nullable();
            $table->double('rent')->nullable();
            $table->double('other')->nullable();
            $table->double('income_price')->unsigned()->default(0);
            $table->double('sell_price')->unsigned()->default(0);
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
        Schema::dropIfExists('sub_products');
    }
}
