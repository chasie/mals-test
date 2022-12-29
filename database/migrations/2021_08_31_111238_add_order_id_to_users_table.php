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
        Schema::table('users', function (Blueprint $table) {
            $table->integer('order_id')->nullable();
            $table->integer('status_work')->nullable()->default(0);
            $table->integer('type_order')->nullable();
            $table->integer('status_order')->nullable()->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn('order_id');
            $table->dropColumn('status_work');
            $table->dropColumn('type_order');
            $table->dropColumn('status_order');
        });
    }
};
