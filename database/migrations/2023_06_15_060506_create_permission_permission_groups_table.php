<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if(!Schema::hasTable('permission_permission_groups'))
        Schema::create('permission_permission_groups', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('permission_id')->unsigned();
            $table->foreign('permission_id')->references('id')->on('permissions');
            $table->bigInteger('permission_group_id')->unsigned();
            $table->foreign('permission_group_id')->references('id')->on('permission_groups');
           
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
        Schema::dropIfExists('permission_permission_groups');
    }
};
