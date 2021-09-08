<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Update2WroTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('work_orders', function($table) {
            $table->string('farm_manager_approval')->after('farm_manager_id')->nullable();
            $table->string('farm_manager_approved')->after('farm_manager_approval')->nullable();
            $table->string('farm_divhead_approval')->after('farm_divhead_id')->nullable();
            $table->string('farm_divhead_approved')->after('farm_divhead_approval')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('work_orders', function($table) {
            $table->dropColumn('farm_manager_approval');
            $table->dropColumn('farm_manager_approved');
            $table->dropColumn('farm_divhead_approval');
            $table->dropColumn('farm_divhead_approved');
        });
    }
}
