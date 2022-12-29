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
        Schema::dropIfExists('order_duties');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('order_duties', function (Blueprint $table) {
            $table->id();
            $table->integer('order_id')->nullable();
            $table->integer('duty_id')->nullable();
            $table->timestamps();
        });
    }
};
