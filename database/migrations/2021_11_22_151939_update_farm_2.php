<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFarm2 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function($table) {
            $table->tinyInteger('farm_manager_bypass')->nullable();
            $table->tinyInteger('farm_divhead_bypass')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('farms', function($table) {
            $table->dropColumn('farm_manager_bypass')->nullable();
            $table->dropColumn('farm_divhead_bypass')->nullable();
        });
    }
}
