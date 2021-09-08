<?php

use Illuminate\Database\Seeder;

class DepartmentSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('departments')->insert([
        	[
                'name' => 'Accounting',
                'code' => 'ACC',
                'description' => 'Accounting Department'
            ],
            [
                'name' => 'Purchasing',
                'code' => 'PURCH',
                'description' => 'Purchasing Department'
            ],
        	[
        		'name' => 'Human Capital Development',
        		'code' => 'HRD',
        		'description' => 'HRD Department'
        	],
        	[
        		'name' => 'Information Technology',
        		'code' => 'IT',
        		'description' => 'IT Department'
        	],
            [
                'name' => 'BCM',
                'code' => 'BCM',
                'description' => 'Building and Construction Management Department'
            ],
            [
                'name' => 'Feed Mill',
                'code' => 'FM',
                'description' => 'Feed Mill Department'
            ],
            [
                'name' => 'Swine',
                'code' => 'Swine',
                'description' => 'Swine Department'
            ],
            [
                'name' => 'Poultry',
                'code' => 'Poultry',
                'description' => 'Poultry Department'
            ],
            [
                'name' => 'Sales',
                'code' => 'Sales',
                'description' => 'Sales Department'
            ],
            [
                'name' => 'Admin & Analytics',
                'code' => 'AA',
                'description' => 'Admin and Analytics Department'
            ],
            [
                'name' => 'Treasury Depatment',
                'code' => 'TRSRY',
                'description' => 'Treasury Depatment'
            ],
        ]);
    }
}
