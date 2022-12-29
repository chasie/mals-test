<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddTypeToUserTimingsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('user_timings', function (Blueprint $table) {
            $table->integer('type')->nullable();
            $table->timestamp('start')->nullable();
            $table->timestamp('finish')->nullable();
            $table->integer('diff')->nullable();
            $table->dropColumn('time_fix');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('user_timings', function (Blueprint $table) {
            $table->dropColumn('type');
            $table->dropColumn('start');
            $table->dropColumn('finish');
            $table->dropColumn('diff');
            $table->timestamp('time_fix')->nullable();
        });
    }
}
