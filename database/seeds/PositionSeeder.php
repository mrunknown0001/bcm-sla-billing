<?php

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('positions')->insert([
        	[
        		'name' => 'Chief Executive Officer',
        		'code' => 'CEO',
        	],
        	[
        		'name' => 'Chief Operations Officer',
        		'code' => 'COO',
        	],
        	[
        		'name' => 'Chief Financial Officer',
        		'code' => 'CFO',
        	],
        	[
        		'name' => 'Vice President',
        		'code' => 'VP',
        	],
        	[
        		'name' => 'Division Head',
        		'code' => 'Div. Head',
        	],
        	[
        		'name' => 'Manager',
        		'code' => 'Mngr',
        	],
        	[
        		'name' => 'Supervisor',
        		'code' => 'Sup',
        	],
        	[
        		'name' => 'Farm Secretary',
        		'code' => 'Farm Sec',
        	],
        	[
        		'name' => 'Encoder',
        		'code' => 'Encoder',
        	],
        ]);
    }
}
