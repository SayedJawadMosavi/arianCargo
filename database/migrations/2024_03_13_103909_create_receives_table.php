<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateReceivesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('receives', function (Blueprint $table) {
            $table->id();

            $table->foreignId('purchase_detail_id')->constrained();
            $table->unsignedBigInteger('purchase_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('quantity')->unsigned();
            $table->double('rent')->unsigned();
            $table->double('expense')->unsigned();
            $table->double('sell_price')->unsigned()->nullable();
            $table->integer('no')->unsigned();
            $table->integer('branch_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->text('description')->nullable();

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
        Schema::dropIfExists('receives');
    }
}
