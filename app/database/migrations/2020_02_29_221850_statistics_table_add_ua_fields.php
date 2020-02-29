<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class StatisticsTableAddUaFields extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {

        Schema::table('statistics', function(Blueprint $table) {
            $table->string('browser');
            $table->string('engine');
            $table->string('os');
            $table->string('device');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('statistics', function (Blueprint $table) {
            Schema::table('users', function($table) {
                $table->dropColumn('browser');
                $table->dropColumn('engine');
                $table->dropColumn('os');
                $table->dropColumn('device');
            });
        });
    }
}
