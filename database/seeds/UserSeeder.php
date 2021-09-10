<?php

use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Adam',
                'last_name' => 'Trinidad',
                'email' => 'maet.bgc@gmail.com',
                'password' => bcrypt('password'),
                'user_type' => 6, // user / requestor
                'active' => 1,
                'farm_id' => 2,
            ],  
            [
                'first_name' => 'Jeff',
                'last_name' => 'Montiano',
                'email' => 'jmontiano@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 6, // user / requestor
                'active' => 1,
                'farm_id' => 2,
            ],           
            [
                'first_name' => 'Dave',
                'last_name' => 'Div Head',
                'email' => 'd.toribio@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 3, // div head
                'active' => 1,
                'farm_id' => 2,
            ],
            [
                'first_name' => 'Dang',
                'last_name' => 'Baniaga',
                'email' => 'd.baniaga@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 6, // user / requestor
                'active' => 1,
                'farm_id' => 2,
            ],
            [
                'first_name' => 'Kim',
                'last_name' => 'Bacani',
                'email' => 'k.bacani@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 4, // manager
                'active' => 1,
                'farm_id' => 2,
            ],
        ]);

        DB::table('users')->insert([
            [
                'first_name' => 'RR',
                'last_name' => 'Romano',
                'email' => 'r.romano@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 4, // manager
                'active' => 1,
            ],
            [
                'first_name' => 'Leo',
                'last_name' => 'Derpo',
                'email' => 'l.derpo@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 4, // manager
                'active' => 1,
            ],
            [
                'first_name' => 'Tony',
                'last_name' => 'Acibar',
                'email' => 'a.acibar@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 3, // manager
                'active' => 1,
            ],
            [
                'first_name' => 'Ancel',
                'last_name' => 'Roque',
                'email' => 'a.roque@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 3, // manager
                'active' => 1,
            ],
            [
                'first_name' => 'Tetta',
                'last_name' => 'Dizon',
                'email' => 'tettadizon@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 2, // vp
                'active' => 1,
            ],
            [
                'first_name' => 'Kiel',
                'last_name' => 'Carreon',
                'email' => 'e.carreon@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 4, // manager
                'active' => 1,
            ],
        ]);
    }
}
