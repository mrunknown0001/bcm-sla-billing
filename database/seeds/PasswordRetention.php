<?php

use Illuminate\Database\Seeder;

class PasswordRetention extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('password_retentions')->insert([
        	'retention_day' => 90,
        ]);
    }
}
