<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateCargoDetailsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cargo_details', function (Blueprint $table) {
            $table->id();
            $table->foreignId('cargo_id')->constrained();

            $table->string('item_name');
            $table->double('quantity')->unsigned();
            // $table->double('income_price')->unsigned();
            $table->string('cbm')->nullable();
            $table->string('type')->nullable();
            $table->double('item_value')->default(0);
            $table->integer('rate')->default(1);
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
        Schema::dropIfExists('cargo_details');
    }
}
