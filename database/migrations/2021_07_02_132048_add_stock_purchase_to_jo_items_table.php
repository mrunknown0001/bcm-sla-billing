<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddStockPurchaseToJoItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('job_order_items', function (Blueprint $table) {
            $table->boolean('on_stock')->default(0);
            $table->boolean('to_purchase')->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('job_order_items', function (Blueprint $table) {
            $table->dropColumn('on_stock')->default(0);
            $table->dropColumn('to_purchase');
        });
    }
}
