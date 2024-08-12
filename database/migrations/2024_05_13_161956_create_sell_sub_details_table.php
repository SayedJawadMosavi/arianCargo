<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellSubDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_sub_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_detail_id')->constrained();
            $table->foreignId('stock_sub_product_id')->constrained();
            $table->integer('quantity')->unsigned();
            $table->double('income_price')->unsigned()->default(0);
            $table->double('cost')->unsigned()->default(0);
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
        Schema::dropIfExists('sell_sub_details');
    }
}
