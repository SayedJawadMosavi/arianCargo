<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMainTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('main_transfers', function (Blueprint $table) {
            $table->id();
            $table->string('bill')->nullable();
            $table->foreignId('stock_id')->constrained();
            $table->string('description')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();
            $table->integer('updated_by')->nullable();
            $table->integer('branch_id');
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
        Schema::dropIfExists('main_transfers');
    }
}
