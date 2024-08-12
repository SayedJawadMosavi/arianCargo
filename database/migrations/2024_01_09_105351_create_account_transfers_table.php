<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAccountTransfersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('account_transfers', function (Blueprint $table) {
            $table->id();

            $table->foreignId('sender_account_id')->references('id')->on('accounts');
            $table->float('amount', 16, 2);
            $table->foreignId('receiver_account_id')->references('id')->on('accounts');
            $table->double('rate')->nullable();
            $table->double('total')->nullable();
            $table->string('operation')->enum(['multiply', 'divide']);
            $table->tinyText('description')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            $table->integer('updated_by')->nullable();
            $table->foreignId('currency_id')->constrained();
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
        Schema::dropIfExists('account_transfers');
    }
}
