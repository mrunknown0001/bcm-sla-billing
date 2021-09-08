<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateUsersTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->string('first_name', 50)->nullable();
            $table->string('middle_name', 50)->nullable();
            $table->string('last_name', 50)->nullable();
            $table->string('email', 80)->unique();
            $table->tinyInteger('user_type')->default(6); // position ==> static assignment
                            // 0 =>  Admin/IT
                            // 1 => Chief Officers
                            // 2 => VPs
                            // 3 => Division Head
                            // 4 => Managers
                            // 5 => Blank user type for Supervisors
                            // 6 => Employee | Requestor
                            // ... 
            $table->string('mobile_number')->nullable()->unique();
            $table->bigInteger('farm_id')->nullable()->unsigned();
            $table->foreign('farm_id')->references('id')->on('farms');
            $table->bigInteger('dept_id')->nullable()->unsigned();
            $table->foreign('dept_id')->references('id')->on('departments');
            $table->string('password', 120)->nullable();
            $table->boolean('active')->default(0);
            $table->rememberToken();
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
        Schema::dropIfExists('users');
    }
}
