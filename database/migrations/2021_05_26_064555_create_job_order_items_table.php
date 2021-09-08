<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrderItemsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_order_items', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('jo_id')->unsigned();
            $table->foreign('jo_id')->references('id')->on('job_orders');
            $table->string('item_name')->nullable();
            $table->string('uom')->nullable();
            $table->string('quantity')->nullable();
            // $table->boolean('on_stock')->default(0);
            // $table->boolean('to_purchase')->default(0);
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
        Schema::dropIfExists('job_order_items');
    }
}
