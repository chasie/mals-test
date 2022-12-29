<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUserTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_timings', function (Blueprint $table) {
            $table->id();
            $table->timestamp('time_fix')->nullable();
            $table->integer('user_id')->nullable();
            $table->integer('work_status')->nullable();
            $table->integer('type_order')->nullable();
            $table->integer('status_order')->nullable();
            $table->integer('order_id')->nullable();
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
        Schema::dropIfExists('user_timings');
    }
}
