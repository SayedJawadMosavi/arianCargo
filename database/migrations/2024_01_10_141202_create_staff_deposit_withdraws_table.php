<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffDepositWithdrawsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff_deposit_withdraws', function (Blueprint $table) {
            $table->id();
            $table->foreignId('staff_id')->constrained();
            $table->foreignId('account_id')->constrained();
            $table->enum('type',['withdraw', 'deposit']);
            $table->double('amount', 16, 2, true);
            $table->tinyText('description')->nullable();
            $table->enum('action', ['direct', 'staff', 'payment', 'sell', 'purchase','staff_transaction','staff_salary'])->default('payment');
            $table->integer('action_id')->nullable();
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
        Schema::dropIfExists('staff_deposit_withdraws');
    }
}
