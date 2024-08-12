<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateVendorLogsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('vendor_logs', function (Blueprint $table) {
            $table->id();
            $table->foreignId('vendor_id')->constrained();
            $table->foreignId('vendor_currency_id')->constrained();
            $table->enum('type',['paid', 'received']);
            $table->double('amount', 16, 2, true);
            $table->double('available', 16, 2);
            $table->tinyText('description')->nullable();
            $table->enum('action', ['direct', 'vendor', 'payment', 'sell', 'purchase','vendor_transaction'])->default('payment');
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
        Schema::dropIfExists('vendor_logs');
    }
}
