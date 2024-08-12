<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellReturnsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sell_returns', function (Blueprint $table) {
            $table->id();
            $table->foreignId('sell_id')->constrained();
            $table->foreignId('sell_detail_id')->constrained();
            $table->unsignedBigInteger('stock_product_id');
            $table->integer('quantity')->unsigned();
            $table->double('cost')->unsigned();
            $table->double('total')->unsigned();
            $table->string('description')->nullable();
            $table->integer('branch_id');
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('updated_by')->nullable();
            $table->integer('deleted_by')->nullable();
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
        Schema::dropIfExists('sell_returns');
    }
}
