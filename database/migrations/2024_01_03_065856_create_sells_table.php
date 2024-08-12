<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSellsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('sells', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('currency_id')->constrained();
            $table->string('client_name')->nullable();

            $table->string('mobile')->nullable();
            $table->double('total');
            $table->double('total_cbm')->default(0);
            $table->double('paid')->nullable();
            $table->double('balance')->nullable();
            $table->double('discount')->nullable();
            $table->double('rate')->nullable();
            $table->string('operation')->enum(['multiply', 'divide'])->default('multiply');
            $table->string('bill')->nullable();
            $table->string('description')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            $table->integer('branch_id');
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
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
        Schema::dropIfExists('sells');
    }
}
