<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateClientLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('client_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('client_id')->constrained();
            $table->foreignId('client_currency_id')->constrained();
            $table->enum('type',['withdraw', 'deposit']);
            $table->double('amount', 16, 2, true);
            $table->double('available', 16, 2);
            $table->tinyText('description')->nullable();
            $table->enum('action', ['direct', 'client', 'payment', 'sell', 'purchase','client_transaction'])->default('payment');
            $table->integer('action_id')->nullable();
            $table->string('shamsi_date')->nullable();
            $table->string('clearance_date_shamsi')->nullable();
            $table->date('miladi_date')->nullable();
            $table->date('clearance_date_miladi')->nullable();
            $table->tinyText('clearance_description')->nullable();
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
        Schema::dropIfExists('client_logs');
    }
}
