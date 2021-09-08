<?php

use Illuminate\Database\Seeder;

use App\User;

class WroApprovalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $coo = User::where('email', 'gil.austria@bfcgroup.org')->first();
        $vp_gen_serv = User::where('email', 'tettadizon@bfcgroup.org')->first();
        $treasury_manager = User::where('email', 'e.carreon@bfcgroup.org')->first();
        $gen_serv_div_head = User::where('email', 'a.roque@bfcgroup.org')->first();
        $bcm_manager = User::where('email', 'r.romano@bfcgroup.org')->first();

        DB::table('wro_approvals')->insert([
        	'bcm_manager' => $bcm_manager->id,
        	'gen_serv_div_head' => $gen_serv_div_head->id,
        	'treasury_manager' => $treasury_manager->id,
        	'vp_gen_serv' => $vp_gen_serv->id,
        	'coo' => $coo->id,
        ]);
    }
}
