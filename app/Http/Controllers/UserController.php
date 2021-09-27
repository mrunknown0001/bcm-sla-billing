<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\RequestorApprover as Ra;
use App\WorkOrder as Wo;
use App\Farm;
use App\User;
use App\WroApproval;

use Auth;
use DB;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

use DataTables;

class UserController extends Controller
{
    /**
     * User Dashboard
     */
    public function dashboard()
    {
        // getll all jo and wro
        // $jos = Jo::where('user_id', Auth::user()->id)->get();

    	return view('user.dashboard');
    }



    public function account()
    {
        return view('user.account');
    }





    public function allworkOrder(Request $request)
    {
        /*
        $data = [
            'wro' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];

        $wro = Wo::where('user_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();

        if(count($wro) > 0) {
            $data = [];
            foreach($wro as $w) {
                $data[] = [
                    'wro' => $w->wr_no,
                    'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                    'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                    'action' => GC::wroRequestorAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved), 
                ];
            }
        }

        return $data;
        */
       
        if($request->ajax()) {
            $wro = Wo::where('user_id', Auth::user()->id)
                        ->where('archived', 0)
                        ->get();
            $data = collect();
            if(count($wro) > 0) {
                foreach($wro as $w) {
                    $data->push([
                        'wro' => $w->wr_no,
                        'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::wroRequestorAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved), 
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
       
    }


    public function viewWorkOrder($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->user_id != Auth::user()->id) {
            return abort(404);
        }

        return view('user.work-order-view', ['wro' => $wro]);
    }


    public function cancelWorkOrder($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->user_id != Auth::user()->id) {
            return abort(404);
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 3) {
            return false;
        }

        $wro->cancelled = 1;
        $wro->cancelled_on = now();
        $wro->reason = $comment;
        $wro->save();

        return true;
        
    }






    public function workOrder()
    {
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();
                
        if(empty($ra)) {
            return redirect()->back()->with('error', 'Please request to setup your Approvers!');
        }


        $farms = Farm::whereActive(1)->get();

        $next_wro_number = GC::nextWroSeries(Auth::user()->id);

        // SLA
        

    	return view('user.work-order', ['next_wro_number' => $next_wro_number, 'farms' => $farms]);
    }


    public function postWorkOrder(Request $request)
    {
        $request->validate([
            'date_of_request' => 'required',
            'date_needed' => 'required',
            'project_name' => 'required',
            'description' => 'required',
            'justification' => 'required',
            'url' => 'required|url'
        ]);


        // check if setup is ok
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();

        $approvers = WroApproval::find(1);


        if(empty($ra)) {
            return redirect()->back()->with('error', 'Please request to setup your Approvers!');
        }

        if($request->farm == null) {
            $wro_no = GC::generateWroNo(Auth::user()->id);
        }
        else {
            $wro_no = GC::generateWroNo2(Auth::user()->id, $request->farm);
        }
        
        # check and get farm manager and division head for continuation of WRO Request
        $farm = Farm::findorfail($request->farm);
        $farm_manager = User::where('farm_id', $request->farm)
                            ->where('user_type', 4)
                            // ->where([['dept_id', '=', 7],['dept_id', '=', 8]]) // poultry & swine
                            ->where(function ($query) {
                                $query->where('dept_id', 7)
                                    ->orWhere('dept_id', 8);
                            })
                            ->first();

        $farm_div_head = User::where('farm_id', $request->farm)
                            ->where('user_type', 3)
                            // ->where([['dept_id', '=', 7],['dept_id', '=', 8]]) // poultry & swine
                            ->where(function ($query) {
                                $query->where('dept_id', 7)
                                    ->orWhere('dept_id', 8);
                            })
                            ->first();

        if(empty($farm_manager)) {
            return redirect()->route('user.work.order')->with('error', 'No Assigned Farm Manager on ' . $farm->name . '. Please contact IT Administrator for proper setup.');
        }

        if(empty($farm_div_head)) {
            return redirect()->route('user.work.order')->with('error', 'No Assigned Farm Division on ' . $farm->name . '. Please contact IT Administrator for proper setup.');
        }

        if($wro_no == '0') {
            return redirect()->back()->with('error', 'Requestor has no designated farm.');
        }

        $wro = new Wo();
        $wro->wr_no = $wro_no;
        $wro->user_id = Auth::user()->id;
        $wro->date_of_request = date('Y-m-d', strtotime($request->date_of_request));
        $wro->date_needed = date('Y-m-d', strtotime($request->date_needed));
        $wro->project_name = $request->project_name;
        $wro->description = $request->description;
        $wro->justification = $request->justification;
        $wro->url = $request->url;

        $wro->approval_sequence = 3;

        $wro->farm_manager_id = $farm_manager->id; # new
        $wro->farm_divhead_id = $farm_div_head->id; # new

        // $wro->manager_id = $ra->manager;
        // $wro->div_head_id = $ra->div_head;

        $wro->bcm_manager_id = $approvers->bcm_manager;
        $wro->gen_serv_div_head_id = $approvers->gen_serv_div_head;
        $wro->treasury_manager_id = $approvers->treasury_manager;
        $wro->vp_gen_serv_id = $approvers->vp_gen_serv;


        if($wro->save()) {

            $approver = GC::getName($wro->bcm_manager_id); # name of manager - important
            $approver_email = GC::getEmail($wro->bcm_manager_id); # email of receiver - important
            $requestor = $wro->user->first_name . ' ' . $wro->user->last_name; # name of requestor - important
            $requestor_designation = GC::getUserPosition($wro->user_id);
            $wro_view_route = "manager.view.work.order";
            $wro_id = $wro->id;
            $wro_no = $wro->wr_no;
            MC::wroManagerApproval($approver, $approver_email, $requestor, $requestor_designation, $wro_view_route, $wro_id, $wro_no);

            return redirect()->back()->with('success', 'SLA ' . $wro->wr_no . ' Submitted Successfully!');
        }

        return redirect()->back()->with('error', 'Please Try Again Later.');


    }




    public function archivedWRO()
    {
        return view('user.archived-wro');
    }



    public function allArchivedWRO()
    {
        $data = [
            'wro' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];

        $wro = Wo::where('user_id', Auth::user()->id)
                    ->where('archived', 1)
                    ->get();

        if(count($wro) > 0) {
            $data = [];
            foreach($wro as $w) {
                $data[] = [
                    'wro' => $w->wr_no,
                    'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                    'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                    'action' => GC::wroRequestorAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                ];
            }
        }

        return $data;
    }


}
