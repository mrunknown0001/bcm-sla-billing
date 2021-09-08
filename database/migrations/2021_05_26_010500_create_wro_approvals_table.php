<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWroApprovalsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('wro_approvals', function (Blueprint $table) {
            $table->id();
            # First Level Approver
            $table->bigInteger('bcm_manager')->unsigned()->nullable();
            $table->foreign('bcm_manager')->references('id')->on('users');
            # Second Level Approver
            $table->bigInteger('gen_serv_div_head')->unsigned()->nullable();
            $table->foreign('gen_serv_div_head')->references('id')->on('users');
            
            # Third Level Approver is Dept/Farm Manager
            # Fourth Level Approver is Dept/Farm Division Head
            
            # Fifth Level Approver
            $table->bigInteger('treasury_manager')->unsigned()->nullable();
            $table->foreign('treasury_manager')->references('id')->on('users');
            # Final Approver
            $table->bigInteger('vp_gen_serv')->unsigned()->nullable();
            $table->foreign('vp_gen_serv')->references('id')->on('users');

            # Below is not in use
            $table->bigInteger('coo')->unsigned()->nullable();
            $table->foreign('coo')->references('id')->on('users');

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
        Schema::dropIfExists('wro_approvals');
    }
}
