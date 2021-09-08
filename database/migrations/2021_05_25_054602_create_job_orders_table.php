<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateJobOrdersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('job_orders', function (Blueprint $table) {
            $table->id();
            $table->string('jo_no')->nullable();
            $table->bigInteger('user_id')->unsigned();
            $table->foreign('user_id')->references('id')->on('users');
            $table->string('requestor')->nullable();
            $table->date('date_of_request')->nullable();
            $table->date('date_needed')->nullable();
            $table->string('project_bldg_no')->nullable();
            $table->text('description')->nullable();
            $table->text('remarks')->nullable();
            $table->string('attachment')->nullable();
            $table->integer('cost')->default(0);

            $table->tinyInteger('status')->default(1); // 1 - pending, 2 - approved, 3 - cancelled, 4 - disapproved, 5 - vp pending approval, 6 - vp approved, 7 - vp disapproved

            $table->bigInteger('manager_id')->unsigned()->nullable();
            $table->foreign('manager_id')->references('id')->on('users');
            $table->boolean('manager_approval')->default(0);
            $table->string('manager')->nullable();
            $table->timestamp('manager_approved')->nullable();


            $table->bigInteger('vp_id')->unsigned()->nullable();
            $table->foreign('vp_id')->references('id')->on('users');
            $table->boolean('vp_approval')->default(0);
            $table->timestamp('vp_approved')->nullable();

            $table->timestamp('cancelled_on')->nullable();

            $table->timestamp('disapproved_on')->nullable();

            $table->string('reason')->nullable();

            $table->boolean('archived')->default(0);
            $table->timestamp('archived_on')->nullable();
            $table->bigInteger('archived_by')->nullable();
    
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
        Schema::dropIfExists('job_orders');
    }
}
