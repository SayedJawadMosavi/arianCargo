<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargosTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargos', function (Blueprint $table) {
            $table->id();

            $table->foreignId('currency_id')->constrained();
            $table->string('client_name')->nullable();
            $table->unsignedBigInteger('client_id');
            $table->unsignedBigInteger('receiver_id');
            $table->foreignId('account_id')->constrained();
            $table->unsignedBigInteger('equalent_per_weight')->nullable();
            $table->unsignedBigInteger('equalent_total')->nullable();
            $table->unsignedBigInteger('equalent_balance')->nullable();
            $table->unsignedBigInteger('country_id')->nullable();

            $table->double('total');
            $table->double('new_total');
            $table->double('total_weight')->default(0);
            $table->double('per_weight')->default(0);
            $table->double('new_per_weight')->default(0);
            $table->double('paid')->nullable();
            $table->double('paid_equalent')->nullable();
            $table->double('balance')->nullable();
            $table->double('new_balance')->nullable();
            $table->double('rate')->nullable();
            // $table->string('operation')->enum(['multiply', 'divide'])->default('multiply');
            $table->string('operation')->nullable();
            $table->string('bill')->nullable();
            $table->string('number')->nullable();
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
        Schema::dropIfExists('cargos');
    }
}
