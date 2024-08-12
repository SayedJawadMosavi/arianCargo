<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateProductsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->foreignId('category_id')->constrained();
            $table->unsignedBigInteger('currency_id')->nullable();
            $table->unsignedBigInteger('unit_id');
            $table->string('name')->unique();
            $table->string('code')->nullable();
            $table->string('model')->nullable();
            $table->string('size')->nullable();
            $table->double('height')->nullable();
            $table->double('width')->nullable();
            $table->double('length')->nullable();
            $table->double('weight')->nullable();
            // $table->integer('initial_quantity')->nullable()->unsigned();
            // $table->integer('quantity')->unsigned()->default(0);
            // $table->integer('waste')->nullable()->unsigned()->default(0);
            // $table->double('cost')->unsigned()->nullable();
            // $table->double('expense')->unsigned()->nullable();
            // $table->double('sell')->nullable();
            $table->double('sell_price')->unsigned()->default(0)->nullable();
            $table->double('income_price')->unsigned()->default(0)->nullable();
            $table->integer('min_stock')->default(0)->nullable();
            $table->integer('active')->default(1);
            $table->string('image')->nullable();
            $table->date('expiry')->nullable();
            $table->integer('branch_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('products');
    }
}
