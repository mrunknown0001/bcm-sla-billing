<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;

use Auth;
use App\User;
use DB;
use App\WroApproval;
use App\RequestorApprover;

use App\Farm;
use App\Department;
use App\Position;

use Config;

use App\MailSetting;

use App\Http\Controllers\GeneralController as GC;

class AdminController extends Controller
{

	/**
	 *  Admin Login
	 */
	public function adminLogin()
	{
		return view('admin-login');
	}


	public function postAdminLogin(Request $request)
	{
		// return $request;

		if(Auth::attempt(['email' => $request->email, 'password' => $request->password])) {
			if(Auth::user()->user_type != 0) {
				Auth::logout();
                return redirect()->route('login')->with('error', 'Please try again later.');
			}

			return redirect()->route('admin.dashboard');
		}

		return redirect()->route('login')->with('error', 'Invalid Credentials');
	}


    /**
     *  Admin Dashboard
     */
    public function dashboard()
    {
    	return view('admin.dashboard');
    }



    /**
     * Admin Settings
     */
    public function settings()
    {
    	return view('admin.settings');
    }



    public function users()
    {
        return view('admin.users');
    }



    public function addUser()
    {
        // farm
        // department
        $farms = Farm::where('active', 1)->get();
        $departments = Department::where('active', 1)->get();
        $positions = GC::getPosition();

        return view('admin.user-add', ['farms' => $farms, 'departments' => $departments, 'positions' => $positions]);
    }



    public function postAddUser(Request $request)
    {
        if(!$request->hasFile('signature')) {
            return redirect()->back()->with('error', 'Signature is required!');
        }

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'farm' => 'required',
            'department' => 'required',
            'position' => 'required',
            'signature' => 'required|mimes:png|max:10000'
        ]);

        // create new user
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->farm_id = $request->farm;
        $user->dept_id = $request->department;
        $user->password = bcrypt('password');
        $user->active = 1;
        $user->user_type = $request->position;


        if($user->save()) {

            // store image eg. id.png
            $signature = $request->file('signature');
            $filename = $user->id . '.png';
            $signature->move(public_path('/uploads/signature/'), $filename);

            return redirect()->back()->with('success', 'User Created!');
        }    

        return redirect()->back()->with('error', 'Error Occured Please Try Again!');
    }


    public function updateUser($id)
    {
        $user = User::findorfail($id);
        $farms = Farm::where('active', 1)->get();
        $departments = Department::where('active', 1)->get();
        $positions = GC::getPosition();

        return view('admin.user-update', ['user' => $user, 'farms' => $farms, 'departments' => $departments, 'positions' => $positions]);
    }


    public function postUpdateUser(Request $request)
    {
        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'farm' => 'required',
            'department' => 'required',
            'position' => 'required',
            'signature' => 'mimes:png|max:10000'
        ]);

        $user = User::findorfail($request->id);

        if(!empty($request->email) && $request->email != $user->email) {
            $request->validate([
                'email' => 'required|unique:users'
            ]);
        }

        if(!empty($request->password)) {
            $user->password = bcrypt($request->password);
        }

        if($request->active != NULL) {
            $active = 1;
        }
        else {
            $active = 0;
        }

        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->farm_id = $request->farm;
        $user->dept_id = $request->department;
        $user->user_type = $request->position;
        $user->active = $active;

        if($user->save()) {

            if($request->hasFile('signature')) {
                // store image eg. id.png
                $signature = $request->file('signature');
                $filename = $user->id . '.png';
                $signature->move(public_path('/uploads/signature/'), $filename);
            }

            return redirect()->back()->with('success', 'User Updated!');
        }    

        return redirect()->back()->with('error', 'Error Occured Please Try Again!');

    }


    public function allUsers()
    {
        $data = [
            'name' => NULL,
            'email' => NULL,
            'setup' => NULL,
            'status' => NULL,
            'action' => NULL,
        ];

        // get all requestor/users user_type = 6
        $users = User::get();

        if(count($users) > 0) {
            $data = [];
            foreach($users as $u) {
                $data[] = [
                    'name' => strtoupper($u->first_name . ' ' . $u->last_name),
                    'email' => $u->email,
                    'setup' => $this->checkSetup($u->id),
                    'status' => GC::userStatus($u->active),
                    'action' => GC::adminUserSetup($u->id, $u->user_type, strtoupper($u->first_name . ' ' . $u->last_name))
                ];
            }
        }

        return $data;
    }



    public function setupUser($id)
    {
        $user = User::findorfail($id);

        if($user->user_type != 6) {
            return abort(404);
        }

        $managers = User::where('user_type', 4)->get();
        $div_heads = User::where('user_type', 3)->get();

        $ra = RequestorApprover::where('user_id', $user->id)->where('active', 1)->first();

        return view('admin.setup-user', ['user' => $user, 'managers' => $managers, 'div_heads' => $div_heads, 'ra' => $ra]);
    }



    public function postSetupUser(Request $request)
    {
        $request->validate([
            'manager' => 'required',
            'div_head' => 'required',
        ]);

        $user_id = $request['user_id'];

        $ra = RequestorApprover::where('user_id', $user_id)->where('active', 1)->first();

        if(empty($ra)) {
            $ra = new RequestorApprover();
            $ra->user_id = $user_id;
        }

        $ra->manager = $request->manager;
        $ra->div_head = $request->div_head;

        if($ra->save()) {
            return redirect()->route('admin.users')->with('success', 'User Updated!');
        }

        return redirect()->route('admin.users')->with('error', 'Error Occured! Please Try Again.');
    }



    public function wroSetup()
    {
        // get all set approver
        $app = WroApproval::where('active',1)->first();


        # First Approver
        $bcm_manager = $this->getName($app->bcm_manager);
        # First Approver Position and Department
        $u_bcm_manager = User::find($app->bcm_manager);
        $fa_pos_dept = GC::getUserPosition($u_bcm_manager->user_type) . ' - ' . GC::getDeptCode($u_bcm_manager->dept_id);
        # Second Approver
        $gen_serv_div_head = $this->getName($app->gen_serv_div_head);
        # Second Approver Position and Department
        $u_gen_serv_div_head = User::find($app->gen_serv_div_head);
        $sa_pos_dept = GC::getUserPosition($u_gen_serv_div_head->user_type) . ' - ' . GC::getDeptCode($u_gen_serv_div_head->dept_id);
        # Third Approver - Dept/Farm Manager
        # Fourth Approver - Dept/Farm Division Head
        
        # Fifth Approver
        $treasury_manager = $this->getName($app->treasury_manager);
        # Fifth Approver Position and Department
        $u_treasury_manager = User::find($app->treasury_manager);
        $fiftha_pos_dept = GC::getUserPosition($u_treasury_manager->user_type). ' - ' . GC::getDeptCode($u_treasury_manager->dept_id);

        # Final Approver
        $vp_gen_serv = $this->getName($app->vp_gen_serv);
        # Final Approver Position and Department
        $u_vp_gen_serv = User::find($app->vp_gen_serv);
        $fin_app_pos_dept = GC::getUserPosition($u_vp_gen_serv->user_type). ' - ' . GC::getDeptCode($u_vp_gen_serv->dept_id); 

        // $coo = $this->getName($app->coo);

        return view('admin.wro-setup', [
            'bcm_manager' => $bcm_manager,
            'gen_serv_div_head' => $gen_serv_div_head,
            'treasury_manager' => $treasury_manager,
            'vp_gen_serv' => $vp_gen_serv,
            // 'coo' => $coo
            'fa_pos_dept' => $fa_pos_dept,
            'sa_pos_dept' => $sa_pos_dept,
            'fiftha_pos_dept' => $fiftha_pos_dept,
            'fin_app_pos_dept' => $fin_app_pos_dept
        ]);
    }


    public function passwordRetention()
    {
        $pr = \App\PasswordRetention::find(1);

        return view('admin.password-retention', ['days' => $pr->retention_day]);
    }



    public function postPasswordRetention(Request $request)
    {
        $pr = \App\PasswordRetention::find(1);

        $request->validate([
            'days' => 'required'
        ]);

        $pr->retention_day = $request->days;

        if($pr->save()) {
            return redirect()->route('admin.password.retention')->with('success', 'Password Retention Duration Updated!');
        }

        return redirect()->route('admin.password.retention')->with('error', 'Error Occured! Please Trya Again Later');
    }











    public function truncateJo()
    {
        Schema::disableForeignKeyConstraints();

        \App\JobOrderItems::truncate();
        \App\JobOrder::truncate();
        \App\JoNumber::truncate();

        Schema::enableForeignKeyConstraints();

        return redirect()->route('admin.dashboard')->with('success', 'Job Order and JoNumber Tables Truncated.');
    }




    public function truncateWro()
    {
        \App\WorkOrder::truncate();
        \App\WroNumber::truncate();

        return redirect()->route('admin.dashboard')->with('success', 'Work Order and WroNumber Tables Truncated.');
    }















    /**
     * Admin Database Backup
     */
    public function backup()
    {
        $filename = 'bak.sql';

        $dbhost = Config::get('values.dbhost');
        $dbname = Config::get('values.dbname');
        $dbuser = Config::get('values.dbuser');
        $dbpass = Config::get('values.dbpass');

        // $command = "mysqldump -u " . $dbuser ." -p " . $dbpass . " -h " . $dbhost . " " . $dbname . " > " . "\app\backup\\" . $filename;

        // mysqldump -u [user name] –p [password] [options] [database_name] [tablename] > [dumpfilename.sql]

        // $returnVar = NULL;
        // $output  = NULL;

        // exec($command, $output, $returnVar);

    	return view('admin.db-backup');
    }


    public function restore()
    {
        return view('admin.db-restore');
    }



    /**
     * Super Admin URL Management
     */
    public function moduleManagement()
    {
        $ms = MailSetting::find(1);
    	return view('admin.module', ['ms' => $ms]);
    }



















    private function getName($id)
    {
        $user = User::find($id);

        if(empty($user)) {
            return 'Empty or Not Set';
        }

        return $user->first_name . ' ' . $user->last_name;
    }


    /**
     * check setup for requestor approvers [manager and div head]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    private function checkSetup($id)
    {
        $ra = RequestorApprover::where('user_id', $id)->where('active', 1)->first();

        if(!empty($ra)) {
            return 'OK';
        }

        return 'No Setup';
    }

}
