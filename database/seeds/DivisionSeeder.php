<?php

use Illuminate\Database\Seeder;

class DivisionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('divisions')->insert([
        	[
        		'name' => 'Swine',
        		'description' => 'Swine Division',
        	],
        	[
        		'name' => 'Poultry',
        		'description' => 'Poultry Division',
        	],
        	[
        		'name' => 'General Services',
        		'description' => 'General Services Division',
        	],
        	[
        		'name' => 'Sales',
        		'description' => 'Sales and Marketing Division',
        	],
        	[
        		'name' => 'Feed Mill',
        		'description' => 'Feed Mill Division'
        	],
        ]);
    }
}
