<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareholderTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shareholder_transactions', function (Blueprint $table) {
            $table->id();
            $table->foreignId('account_id')->constrained();
            $table->foreignId('share_holder_id')->constrained();
            $table->foreignId('shareholder_currency_id')->constrained();
            $table->float('amount', 16, 2);
            $table->tinyText('description')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
            // $table->enum('same', ['yes', 'no'])->default('yes');
            // $table->string('rate')->nullable();
            // $table->string('operation')->nullable();
            // $table->float('total', 16, 2)->nullable();
            $table->enum('type',['withdraw', 'deposit']);
            $table->integer('updated_by')->nullable();
            $table->foreignId('user_id')->constrained();
            $table->integer('deleted_by')->nullable();;
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
        Schema::dropIfExists('shareholder_transactions');
    }
}
