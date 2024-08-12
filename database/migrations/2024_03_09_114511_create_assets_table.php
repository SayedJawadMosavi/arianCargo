<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateAssetsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('assets', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->integer('assets_value');
            $table->integer('quantity');
            $table->text('description')->nullable();
            $table->foreignId('currency_id')->constrained();
            $table->integer('category_id');
            $table->integer('account_id');
            $table->integer('branch_id');
            $table->integer('user_id');
            $table->string('shamsi_date')->nullable();
            $table->date('miladi_date')->nullable();
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
        Schema::dropIfExists('assets');
    }
}
