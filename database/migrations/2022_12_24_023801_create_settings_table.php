<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSettingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('settings', function (Blueprint $table) {
            $table->id();
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->string('logo')->nullable();
            $table->string('bill_header')->nullable();
            $table->string('registration_image')->nullable();
            $table->string('name_en')->nullable();
            $table->string('name_pa')->nullable();
            $table->string('name_fa')->nullable();
            $table->string('mobile1')->nullable();
            $table->string('mobile2')->nullable();
            $table->string('email')->nullable();
            $table->text('address_en')->nullable();
            $table->text('address_fa')->nullable();
            $table->text('address_pa')->nullable();
            $table->string('second_address')->nullable();
            $table->string('facebook')->nullable();
            $table->string('twitter')->nullable();
            $table->string('youtube')->nullable();
            $table->string('instagram')->nullable();
            $table->string('linkedin')->nullable();
            $table->string('meta_keyword')->nullable();
            $table->integer('currency_id')->nullable();
            $table->string('meta_description')->nullable();
            $table->integer('check')->default(0);

            $table->integer('en')->default(0)->nullable();
            $table->integer('fa')->default(0)->nullable();
            $table->integer('pa')->default(0)->nullable();
            $table->enum('date_type',['shamsi', 'miladi']);

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
        Schema::dropIfExists('settings');
    }
}
