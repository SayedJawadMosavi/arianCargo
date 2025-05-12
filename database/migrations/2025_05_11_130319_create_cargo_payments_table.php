<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoPaymentsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_payments', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('client_currency_id')->constrained();
            $table->float('amount', 16, 2);
            $table->tinyText('description')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            $table->enum('type',['withdraw', 'deposit']);
            $table->integer('updated_by')->nullable();
            $table->integer('user_id');
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
        Schema::dropIfExists('cargo_payments');
    }
}
