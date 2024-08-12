<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePurchaseDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('purchase_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('purchase_id')->constrained();
            $table->foreignId('product_id')->constrained();
            $table->double('quantity')->unsigned();
            $table->double('received')->unsigned()->nullable();
            $table->double('cost')->unsigned();
            // $table->double('tax')->nullable();
            // $table->double('rent')->nullable();
            // $table->double('other')->nullable();
            // $table->double('sell_price')->nullable();
            $table->integer('branch_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->unsignedBigInteger('received_id')->nullable();
            $table->dateTime('received_date')->nullable();
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
        Schema::dropIfExists('purchase_details');
    }
}
