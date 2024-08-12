<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateStaffTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('staff', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('fathername')->nullable();
            $table->string('mobile')->nullable();
            $table->text('address')->nullable();
            $table->enum('education', ['Twelve degree', 'bachelor degree', 'master degree', 'Ph.D'])->nullable();
            $table->string('joining_date_shamsi')->nullable();
            $table->date('joining_date_miladi')->nullable();
            $table->string('shamsi_date_dob')->nullable();
            $table->date('miladi_date_dob')->nullable();
            $table->text('documents')->nullable();
            $table->string('tazkira_number')->nullable();
            $table->string('salary')->nullable();
            $table->double('loan')->default(0);
            $table->string('position')->nullable();
            $table->integer('user_id');
            $table->integer('branch_id');
            $table->integer('active')->default(1);

            $table->text('description')->nullable();
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
        Schema::dropIfExists('staff');
    }
}
