<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRequestorApproversTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('requestor_approvers', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id')->unsigned(); // requestor
            $table->foreign('user_id')->references('id')->on('users');
            $table->bigInteger('manager')->unsigned()->nullable();
            $table->foreign('manager')->references('id')->on('users');
            $table->bigInteger('div_head')->unsigned()->nullable();
            $table->foreign('div_head')->references('id')->on('users');
            $table->boolean('active')->default(1);
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
        Schema::dropIfExists('requestor_approvers');
    }
}
