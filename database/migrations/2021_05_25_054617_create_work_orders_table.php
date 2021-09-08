<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateWorkOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('work_orders', function (Blueprint $table) {
            $table->id();
            $table->string('wr_no')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->date('date_of_request')->nullable();
            $table->date('date_needed')->nullable();
            $table->string('project_bldg_no')->nullable();
            $table->text('description')->nullable();
            $table->text('justification')->nullable();
            $table->string('attachment')->nullable();

            $table->boolean('cancelled')->default(0);
            $table->timestamp('cancelled_on')->nullable();


            $table->bigInteger('disapproved_by')->unsigned()->nullable();
            $table->foreign('disapproved_by')->references('id')->on('users');
            $table->boolean('disapproved')->default(0);
            $table->timestamp('disapproved_on')->nullable();

            $table->text('reason')->nullable();

            $table->tinyInteger('approval_sequence')->default(1);
            // old approval sequence
            // 1 manager
            // 2 div head
            // 3 bcm manager
            // 4 gen services div head
            // 5 treasury manager
            // 6 vp gen services
            // 7 coo
            // 8 approved - ok

            // new approval sequence
            // 3 bcm manager
            // 4 gs div head
            // 5 farm manager
            // 6 farm div head
            // 7 treasury manager
            // 8 vp
            $table->bigInteger('next_approver')->nullable(); // for excempted case

            $table->bigInteger('manager_id')->unsigned()->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->boolean('manager_approval')->default(0);
            $table->timestamp('manager_approved')->nullable();


            $table->bigInteger('div_head_id')->unsigned()->nullable();
            $table->foreign('div_head_id')->references('id')->on('users');
            $table->boolean('div_head_approval')->default(0);
            $table->timestamp('div_head_approved')->nullable();

            $table->bigInteger('bcm_manager_id')->nullable()->unsigned();
            $table->foreign('bcm_manager_id')->references('id')->on('users');
            $table->boolean('bcm_manager_approval')->default(0);
            $table->timestamp('bcm_manager_approved')->nullable();


            $table->bigInteger('gen_serv_div_head_id')->unsigned()->nullable();
            $table->foreign('gen_serv_div_head_id')->references('id')->on('users');
            $table->boolean('gen_serv_div_head_approval')->default(0);
            $table->timestamp('gen_serv_div_head_approved')->nullable();


            $table->bigInteger('treasury_manager_id')->unsigned()->nullable();
            $table->foreign('treasury_manager_id')->references('id')->on('users');
            $table->boolean('treasury_manager_approval')->default(0);
            $table->timestamp('treasury_manager_approved')->nullable();


            $table->bigInteger('vp_gen_serv_id')->unsigned()->nullable();
            $table->foreign('vp_gen_serv_id')->references('id')->on('users');
            $table->boolean('vp_gen_serv_approval')->default(0);
            $table->timestamp('vp_gen_serv_approved')->nullable();


            $table->bigInteger('coo_id')->unsigned()->nullable();
            $table->foreign('coo_id')->references('id')->on('users');
            $table->boolean('coo_approval')->default(0);
            $table->timestamp('coo_approved')->nullable();
    

            $table->boolean('archived')->default(0);
            $table->timestamp('archived_on')->nullable();
            $table->bigInteger('archived_by')->nullable(); // manager only can archive
    

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
        Schema::dropIfExists('work_orders');
    }
}
