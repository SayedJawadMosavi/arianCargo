<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_id')->constrained();
            $table->unsignedBigInteger('stock_product_id');
            $table->foreignId('product_id')->constrained();
            $table->double('quantity')->unsigned();
            // $table->double('income_price')->unsigned();
            $table->double('cost')->unsigned();
            $table->double('total')->unsigned();
            $table->double('profit')->default(0)->nullable();
            $table->integer('rate')->default(1);
            $table->integer('cbm')->default(0);
            $table->integer('branch_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->index('product_id');
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
        Schema::dropIfExists('sell_details');
    }
}
