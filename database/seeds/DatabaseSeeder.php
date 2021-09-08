<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            [
                'first_name' => 'Adam',
                'last_name' => 'Trinidad',
                'email' => 'm.trinidad@bfcgroup.org',
                'password' => bcrypt('password'),
                'user_type' => 0,
                'active' => 1,
            ],
        ]);

        $this->call([
            FarmSeeder::class,
            DivisionSeeder::class,
            DepartmentSeeder::class,
            UserSeeder::class,
            WroApprovalSeeder::class,
            PasswordRetention::class,
        ]);
    }
}
