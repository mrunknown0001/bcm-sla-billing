<?php

use Illuminate\Database\Seeder;

class JoNumberSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('jo_numbers')->insert([
        	'farm' => 'BFC',
        	'year' => '21',
        	'month' => '05',
        	'count' => '0001',
        ]);
    }
}
