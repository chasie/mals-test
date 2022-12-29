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
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('phone')->nullable();
            $table->string('name')->nullable();
            $table->string('email')->nullable();
            $table->timestamp('birthday')->nullable();
            $table->integer('group_id')->nullable();
            $table->integer('activation')->nullable()->default(1);
            $table->string('password')->nullable();
            $table->string('login')->nullable();
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
};
