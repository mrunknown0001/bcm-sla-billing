<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateFarm1 extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('farms', function($table) {
            $table->bigInteger('farm_manager_id')->nullable();
            $table->bigInteger('farm_divhead_id')->nullable();
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
            $table->dropColumn('farm_manager_id')->nullable();
            $table->dropColumn('farm_divhead_id')->nullable();
        });
    }
}
