<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateShareholderLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('shareholder_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('share_holder_id')->constrained();
            $table->foreignId('shareholder_currency_id')->constrained();
            $table->enum('type',['withdraw', 'deposit']);
            $table->double('amount', 16, 2, true);
            $table->double('available', 16, 2);
            $table->tinyText('description')->nullable();
            $table->enum('action', ['direct', 'share_holder', 'payment', 'sell', 'purchase','share_holder_transaction'])->default('payment');
            $table->integer('action_id')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
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
        Schema::dropIfExists('shareholder_logs');
    }
}
