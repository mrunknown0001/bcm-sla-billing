<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\User;
use App\JoNumber;
use App\WroNumber;
use App\Farm;
use App\JobOrder as Jo;
use App\WorkOrder as Wo;
use App\WroApproval;
use App\Department;

use PDF;

use App\PasswordHistory;

use Auth;

class GeneralController extends Controller
{

    public function postChangePassword(Request $request)
    {
        $request->validate([
            'old_password' => 'required',
            'new_password' => 'required|confirmed|min:8|regex:/^.*(?=.{3,})(?=.*[a-zA-Z])(?=.*[0-9])(?=.*[\d\x])(?=.*[!@$#%]).*$/'
        ]);

        $user = Auth::user();

        if(!password_verify($request->old_password, $user->password)) {
            return redirect()->back()->with('error', 'Incorrect Old Password!');
        }

        $user->password = bcrypt($request->new_password);
        $user->save();

        $history = new PasswordHistory();
        $history->user_id = $user->id;
        $history->password = $user->password;
        $history->date_change = date('Y-m-d', strtotime(now()));
        $history->save();

        return redirect()->back()->with('success', 'Password Successfully Changed!');
    }


    /**
     * [getName get name of user using id primary key]
     * @param  [type] $id [primary key of users table]
     * @return [type]     [big Integer]
     */
    public static function getName($id)
    {
    	$user = User::findorfail($id);

        return ucfirst($user->first_name) . ' ' . ucfirst($user->last_name);
    }



    /**
     * [getEmail get email of users using id - primary key]
     * @param  [type] $id [primary key of users table]
     * @return [type]     [big integer]
     */
    public static function getEmail($id)
    {
        $user = User::findorfail($id);

        return $user->email;
    }


    /**
     * [generateJoNo description]
     * @param  [type] $id [user id]
     * @return [type]     [job order number if no error occured, 0 if error occured]
     */
    public static function generateJoNo($id)
    {
        // get user and what farm under
        $user = User::find($id);

        // check month and year and farm
        if(empty($user) || $user->farm_id == NULL) {
            return '0';
        }

        $farm = Farm::find($user->farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate jo number
        // // if not, create new record for month year and farm jo number
        $jo_no = JoNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($jo_no)) {
            // create jo no per farm per month year
            $jo_no = new JoNumber();
            $jo_no->farm = $farm->code;
            $jo_no->year = date('y');
            $jo_no->month = date('m');
            $jo_no->count = 1;
            $jo_no->save();
        }
        
        // generate jo number based on jo number on specific farm
        $jo_number = 'JO-' . $farm->code . '-' . $jo_no->month . $jo_no->year . '-' . str_pad($jo_no->count, 3, '0', STR_PAD_LEFT);

        $jo_no->count += 1;
        $jo_no->save();

        return $jo_number;
    }



    public static function nextJoSeries($id)
    {
        // get user and what farm under
        $user = User::find($id);

        // check month and year and farm
        if(empty($user) || $user->farm_id == NULL) {
            return '0';
        }

        $farm = Farm::find($user->farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate jo number
        // // if not, create new record for month year and farm jo number
        $jo_no = JoNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($jo_no)) {
            return $jo_number = 'JO-' . $farm->code . '-' . date('m') . date('y') . '-' . str_pad(1, 3, '0', STR_PAD_LEFT);
        }
        
        // generate jo number based on jo number on specific farm
        return $jo_number = 'JO-' . $farm->code . '-' . $jo_no->month . $jo_no->year . '-' . str_pad($jo_no->count, 3, '0', STR_PAD_LEFT);
    }



    public static function nextWroSeries($id)
    {
        // get user and what farm under
        $user = User::find($id);

        // check month and year and farm
        if(empty($user) || $user->farm_id == NULL) {
            return '0';
        }

        $farm = Farm::find($user->farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate jo number
        // // if not, create new record for month year and farm jo number
        $wro_no = WroNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($wro_no)) {
            return $wro_no = 'SLA-' . $farm->code . '-' . date('m') . date('y') . '-' . str_pad(1, 3, '0', STR_PAD_LEFT);
        }
        
        // generate jo number based on jo number on specific farm
        return $wro_number = 'SLA-' . $farm->code . '-' . $wro_no->month . $wro_no->year . '-' . str_pad($wro_no->count, 3, '0', STR_PAD_LEFT);
    }





    public static function generateWroNo($id)
    {
        // get user and what farm under
        $user = User::find($id);

        // check month and year and farm
        if(empty($user) || $user->farm_id == NULL) {
            return '0';
        }

        $farm = Farm::find($user->farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate wro number
        // // if not, create new record for month year and farm wro number
        $wro = WroNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($wro)) {
            // create wro no per farm per month year
            $wro = new WroNumber();
            $wro->farm = $farm->code;
            $wro->year = date('y');
            $wro->month = date('m');
            $wro->count = 1;
            $wro->save();
        }
        
        // generate wro number based on wro number on specific farm
        $wro_number = 'SLA-' . $farm->code . '-' . $wro->month . $wro->year . '-' . str_pad($wro->count, 3, '0', STR_PAD_LEFT);

        $wro->count += 1;
        $wro->save();

        return $wro_number;
    }


    public static function generateWroNo2($id, $farm_id)
    {
        $farm = Farm::find($farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate wro number
        // // if not, create new record for month year and farm wro number
        $wro = WroNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($wro)) {
            // create wro no per farm per month year
            $wro = new WroNumber();
            $wro->farm = $farm->code;
            $wro->year = date('y');
            $wro->month = date('m');
            $wro->count = 1;
            $wro->save();
        }
        
        // generate wro number based on wro number on specific farm
        $wro_number = 'SLA-' . $farm->code . '-' . $wro->month . $wro->year . '-' . str_pad($wro->count, 3, '0', STR_PAD_LEFT);

        $wro->count += 1;
        $wro->save();

        return $wro_number;
    }



    public function previewWroNo($farm_id)
    {
        $farm = Farm::find($farm_id);

        if(empty($farm)) {
            return '0';
        }

        // if existing check on count number then from there generate wro number
        // // if not, create new record for month year and farm wro number
        $wro = WroNumber::where('farm', $farm->code)
                    ->where('year', date('y'))
                    ->where('month', date('m'))
                    ->first();

        if(empty($wro)) {
            // create wro no per farm per month year
            $wro = new WroNumber();
            $wro->farm = $farm->code;
            $wro->year = date('y');
            $wro->month = date('m');
            $wro->count = 1;
            $wro->save();
        }
        
        // generate wro number based on wro number on specific farm
        $wro_number = 'SLA-' . $farm->code . '-' . $wro->month . $wro->year . '-' . str_pad($wro->count, 3, '0', STR_PAD_LEFT);

        return $wro_number . ' (Possible Next Service Level Agreement # Series)';
    }



    public function previewProjectName($id)
    {
        $wro = Wo::where('wr_no', $id)->first();

        if(empty($wro)) {
            return '0';
        }

        $project_name = $wro->project_name;

        return $project_name;
    }


    public static function generateVerificationCode()
    {
        return random_int(100000, 999999);
    }



    public static function getDeptCode($id)
    {
        $dept = Department::find($id);

        if(empty($dept->code)) {
            return "Department Not Set";
        }
        
        return $dept->code;
    }



    public static function trsryMgr($id)
    {
        $ap = WroApproval::whereActive(1)->first();

        if($id == $ap->treasury_manager) {
            return true;
        }

        return false;
    }




    public static function viewJoStatus($status)
    {
        if($status == 1) {
            return "<span class='badge badge-warning'>Pending</span>";
        }
        else if ($status == 2) {
            return "<span class='badge badge-success'>Approved by Manager</span>";
        }  
        else if($status == 3) {
            return '<span class="badge badge-danger">Cancelled by Requestor</span>';  
        }
        else if($status == 4) {
            return '<span class="badge badge-danger">Disapproved by Manager</span>';  
        }
        else if($status == 5) {
            return '<span class="badge badge-warning">Pending Approval on VP on General Services</span>';
        }
        else if($status == 6) {
            return '<span class="badge badge-success">Approved by VP on General Services</span>';
        }
        else { // 7
            return '<span class="badge badge-danger">Disapproved by VP on General Services</span>';  
        }
         
    }


    public static function archiveStatus($i)
    {
        if($i == 1) {
            return '<span class="badge badge-primary">ARCHIVED</span>';
        }
        else {
            return '<span class="badge badge-default">NOT ARCHIVED</span>';
        }
    }


    public static function viewWroStatus($status, $cancelled, $disapprove)
    {
        if($cancelled != 1) {
            if($disapprove != 1) {
                switch ($status) {
                    case 1:
                        return "<span class='badge badge-warning'>Pending Approval on Manager</span>";
                        break;

                    case 2:
                        return "<span class='badge badge-warning'>Pending Approval on Division Head</span>";
                        break;

                    case 3:
                        return "<span class='badge badge-warning'>Pending Approval on BCM Manager</span>";
                        break;

                    case 4:
                        return "<span class='badge badge-warning'>Pending Approval on Gen. Services Division Head</span>";
                        break;

                    case 5:
                        return "<span class='badge badge-warning'>Pending Approval on Farm Manager</span>";
                        break;

                    case 6:
                        return "<span class='badge badge-warning'>Pending Approval on Farm Division Head</span>";
                        break;

                    case 7:
                        return "<span class='badge badge-warning'>Pending Approval on Treasury Manager</span>";
                        break;

                    case 8:
                        return "<span class='badge badge-warning'>Pending Approval on VP on General Services</span>";
                        break;

                    case 9:
                        return "<span class='badge badge-success'>Approved</span>";
                        break;

                    default: 
                        return "<span class='badge badge-default'>Unknown Status</span>";

                }
            }
            else {
                return "<span class='badge badge-danger'>Disapproved</span>";
            }
        }
        else {
            return "<span class='badge badge-danger'>Cancelled</span>";
        }
    }







    public function downloadWro($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->approval_sequence != 9) {
            return false;
        }

        view()->share('wro',$wro);
        $pdf = PDF::loadView('wro_view', $wro);

        $filename = $wro->wr_no . '.pdf';

        return $pdf->download($filename);
    }


    public static function getMonth($id)
    {
        switch ($id) {
            case 1:
                return 'January';
                break;
            case 2:
                return 'February';
                break;
            case 3:
                return 'March';
                break;
            case 4:
                return 'April';
                break;
            case 5:
                return 'May';
                break;
            case 6:
                return 'June';
                break;
            case 7:
                return 'July';
                break;
            case 8:
                return 'August';
                break;
            case 9:
                return 'September';
                break;
            case 10:
                return 'October';
                break;
            case 11:
                return 'November';
                break;
            case 12:
                return 'December';
                break;
            
            default:
                return 'Error';
                break;
        }
    }



    public static function wroManagerAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($archived == 0) {
            if($cancelled == 0 && $disapproved == 0) {
                if($status == 5) {
                    return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                    <button id='approve1' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                    <button id='disapprove1' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
                }
                elseif($status == 9) {
                    return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='" . route('manager.wro.pdf.download', ['id' => $id]) . "' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
                }
                else {
                    return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
                }
            }
            else
             {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
            }
        }
        else {
            if($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='" . route('manager.wro.pdf.download', ['id' => $id]) . "' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
    }


    public static function wroRequestorAction($status, $id, $wro, $cancelled, $disapproved)
    {
        if($cancelled == 0 && $status == 3 && $disapproved == 0) {
            return '<button id="view1" data-id="' . $id . '" data-text="Do you want to view SLA ' . $wro . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <button id="cancel1" class="btn btn-danger btn-xs" data-id="' . $id . '" data-text="Do you want to cancel SLA ' . $wro . '?"><i class="pe-7s-close-circle"></i> Cancel</button>';
        }
        elseif ($status == 9) {
            return '<button id="view1" data-id="' . $id . '" data-text="Do you want to view SLA ' . $wro . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <a href="' . route('user.wro.pdf.download', ['id' => $id]) . '" class="btn btn-primary btn-xs"><i class="pe-7s-download"></i> Download</a>';
        }
        else {
            return '<button id="view1" data-id="' . $id . '" data-text="Do you want to view SLA ' . $wro . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button>';
        }
    }



    public static function wroDivHeadAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1 && $archived == 0) {
            if($status == 6) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve1' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove1' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='" . route('divhead.wro.pdf.download', ['id' => $id]) . "' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }


    public static function wroBCMManagerAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 3) {
                return "<button id='view_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_bcm_mgr' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_bcm_mgr' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive SLA " . $wro . "?' class='btn btn-primary btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive SLA " . $wro . "?' class='btn btn-primary btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
        }
        else {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }


    public static function wroGenServDivHeadAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 4) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_gs_div_head' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_gs_div_head' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }


    public static function wroTreasuryMgrAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 7) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_trsry_mgr' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_trsry_mgr' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }


    public static function wroGsVpAction($status, $id, $wro, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 8) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_gs_vp' data-id='" . $id . "' data-text='Do you want to approve SLA " . $wro . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_gs_vp' data-id='" . $id . "' data-text='Do you want to disapprove SLA " . $wro . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
            else {
                return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='view1' data-id='" . $id . "' data-text='Do you want to view SLA " . $wro . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }



    public static function checkBcmManager($id)
    {
        $approvals = WroApproval::where('active', 1)->first();

        if($approvals->bcm_manager == $id) {
            return true;
        }

        return false;
    }

    public static function checkTreasuryManager($id)
    {
        $approvals = WroApproval::where('active', 1)->first();

        if($approvals->treasury_manager == $id) {
            return true;
        }

        return false;
    }


    public static function checkGsDivHead($id)
    {
        $approvals = WroApproval::where('active', 1)->first();

        if($approvals->gen_serv_div_head  == $id) {
            return true;
        }

        return false;
    }



    public static function adminUserSetup($id, $user_type, $name)
    {
        if($user_type == 6) {
            $button = '<a href="' . route('admin.setup.user', $id) . ' " class="btn btn-primary btn-xs"><i class="pe-7s-settings"></i> Setup</a> <a href="' . route('admin.update.user', ['id' => $id]) . '" class="btn btn-warning btn-xs"><i class="pe-7s-pen"></i> Update</a>';
        }
        else {
            $button = '<a href="' . route('admin.update.user', ['id' => $id]) . '" class="btn btn-warning btn-xs"><i class="pe-7s-pen"></i> Update</a>';
        }

        return $button . ' <button id="remove" data-id=' . $id . ' data-text="Remove User: ' . $name . '" class="btn btn-danger btn-xs"><i class="pe-7s-delete-user"></i> Remove</button>';
    }




    public static function userStatus($status)
    {
        if($status == 1) {
            return "<span class='badge badge-success'>ACTIVE</span>";
        }
        else {
            return "<span class='badge badge-danger'>INACTIVE</span>";
        }
    }




    public static function getPosition()
    {
        $pos = [
            [
                'id' => 0,
                'position' => 'Administrator',
                'description' => 'IT  Administrator'
            ],
            [
                'id' => 1,
                'position' => 'Chief Officers',
                'description' => 'Chief Officers'
            ],
            [
                'id' => 2,
                'position' => 'VP',
                'description' => 'Vice President'
            ],
            [
                'id' => 3,
                'position' => 'DivHead',
                'description' => 'Division Head'
            ],
            [
                'id' => 4,
                'position' => 'Manager',
                'description' => 'Manager'
            ],
            [
                'id' => 5,
                'position' => 'Supervisor',
                'description' => 'Supervisor'
            ],
            [
                'id' => 6,
                'position' => 'Requestor',
                'description' => 'Encoder Requestor'
            ],
        ];

        return $pos;
    }



    public static function getUserPosition($position)
    {
        switch ($position) {
            case 0:
                return 'Administrator';
                break;
            case 1:
                return 'Chief officer';
                break;
            case 2: 
                return 'Vice President';
                break;
            case 3: 
                return 'Division Head';
                break;
            case 4:
                return 'Manager';
                break;
            case 5:
                return 'Supervisor';
                break;
            case 6:
                return 'Requestor/Encoder';
                break;
            default:
                return 'Unknown Position';
                break;
        }
    }




    public function getManagers()
    {
        $data = NULL;

        $managers = User::where('user_type', 4)->get();

        if(count($managers) > 0) {
            $data = "[";
            foreach($managers as $m) {
                // $data[] = [
                //     'id' => $m->id,
                //     'name' => $this->getName($m->id)
                // ];

                $data .= "'" . $m->id . "'" . ":" . "'" . $this->getName($m->id) . "',";
            }
        }
        $data .= "]";
        return $data;
    }






    // actions on billing per user
    public static function billingRequestorAction($status, $id, $sla_number, $cancelled, $disapproved)
    {
        if($cancelled == 0 && $status == 3 && $disapproved == 0) {
            return '<button id="viewbilling" data-id="' . $id . '" data-text="Do you want to view Billing ' . $sla_number . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <button id="cancelbilling" class="btn btn-danger btn-xs" data-id="' . $id . '" data-text="Do you want to cancel Billing ' . $sla_number . '?"><i class="pe-7s-close-circle"></i> Cancel</button>';
        }
        elseif ($status == 9) {
            return '<button id="viewbilling" data-id="' . $id . '" data-text="Do you want to view Billing ' . $sla_number . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <a href="' . route('user.wro.pdf.download', ['id' => $id]) . '" class="btn btn-primary btn-xs"><i class="pe-7s-download"></i> Download</a>';
        }
        else {
            return '<button id="viewbilling" data-id="' . $id . '" data-text="Do you want to view Billing ' . $sla_number . '?"class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button>';
        }
    }


    // action for billing manager
    public static function billingManagerAction($status, $id, $sla_number, $cancelled, $disapproved, $archived)
    {
        if($archived == 0) {
            if($cancelled == 0 && $disapproved == 0) {
                if($status == 5) {
                    return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                    <button id='approvebilling' data-id='" . $id . "' data-text='Do you want to approve Billing " . $sla_number . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                    <button id='disapprovebilling' data-id='" . $id . "' data-text='Do you want to disapprove Billing " . $sla_number . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
                }
                elseif($status == 9) {
                    return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
                }
                else {
                    return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
                }
            }
            else
             {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive1' data-id='" . $id . "' data-text='Do you want to archive Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
            }
        }
        else {
            if($status == 9) {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a>";
            }
            else {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
    }


    // bmc manager billing action
    public static function billingBCMManagerAction($status, $id, $sla_number, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 3) {
                return "<button id='view_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to approve Billing " . $sla_number . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to disapprove Billing " . $sla_number . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='view_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to archive Billing " . $sla_number . "?' class='btn btn-primary btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
            }
            else {
                return "<button id='view_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='view_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <button id='archive_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to archive Billing " . $sla_number . "?' class='btn btn-primary btn-xs'><i class='pe-7s-portfolio'></i> Archive</button>";
        }
        else {
            return "<button id='view_billing_bcm_mgr' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }
 


    // div head gs billing actions
   public static function billingGenServDivHeadAction($status, $id, $sla_number, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1) {
            if($status == 4) {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approve_billing_gs_div_head' data-id='" . $id . "' data-text='Do you want to approve Billing " . $sla_number . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprove_billing_gs_div_head' data-id='" . $id . "' data-text='Do you want to disapprove Billing " . $sla_number . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
            else {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }


    public static function billingDivHeadAction($status, $id, $sla_number, $cancelled, $disapproved, $archived)
    {
        if($cancelled == 0 && $disapproved != 1 && $archived == 0) {
            if($status == 6) {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>
                <button id='approvebilling' data-id='" . $id . "' data-text='Do you want to approve Billing " . $sla_number . "?' class='btn btn-success btn-xs'><i class='pe-7s-check'></i> Approve</button>
                <button id='disapprovebilling' data-id='" . $id . "' data-text='Do you want to disapprove Billing " . $sla_number . "?' class='btn btn-danger btn-xs'><i class='pe-7s-close-circle'></i> Disapprove</button>";
            }
            elseif($status == 9) {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button> <a href='' class='btn btn-primary btn-xs'><i class='pe-7s-download'></i> Download</a>";
            }
            else {
                return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
            }
        }
        elseif($disapproved == 1) {
            return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
        else {
            return "<button id='viewbilling' data-id='" . $id . "' data-text='Do you want to view Billing " . $sla_number . "?' class='btn btn-info btn-xs'><i class='pe-7s-look'></i> View</button>";
        }
    }
}
