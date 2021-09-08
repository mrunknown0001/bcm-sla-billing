<?php

use Illuminate\Database\Seeder;

class FarmSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('farms')->insert([
        	[
        		'name' => 'Brookside Group of Companies',
        		'code' => 'BGC',
        		'description' => 'BGC',
        	],
        	[
        		'name' => 'Brookside Farms Corporation',
        		'code' => 'BFC',
        		'description' => 'Brookside',
        	],
        	[
        		'name' => 'Brookside Breeding and Genetics Corporation',
        		'code' => 'BBGC',
        		'description' => 'BBGC',
        	],
        	[
        		'name' => 'Brookdale Farms Corporation',
        		'code' => 'BDL',
        		'description' => 'Brookdale',
        	],
        	[
        		'name' => 'Poultrypure Farms Corporation',
        		'code' => 'PFC',
        		'description' => 'PFC',
        	],
        	[
        		'name' => 'RH Farms Corporation',
        		'code' => 'RH',
        		'description' => 'RH',
        	],
        	[
        		'name' => 'Hatchery',
        		'code' => 'HTCHRY',
        		'description' => 'Hatchery',
        	],
        ]);
    }
}
