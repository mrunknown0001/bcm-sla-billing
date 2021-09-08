<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\UnitOfMeasurement as Uom;
use App\RequestorApprover as Ra;
use App\JobOrder as Jo;
use App\WorkOrder as Wo;
use App\JoNumber;
use App\Farm;
use App\User;

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


    public function allJobOrder(Request $request)
    {
        /*
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];

        $jos = Jo::where('user_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();

        if(count($jos) > 0) {
            $data = [];
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'status' => GC::viewJoStatus($j->status),
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                    'action' => GC::joRequestorAction($j->status, $j->id, $j->jo_no), 
                ];
            }
        }

        return $data;
        */
       
        if($request->ajax()) {
            $jo = Jo::where('user_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();
            $data = collect();
            if(count($jo) > 0) {
                foreach($jo as $j) {
                    $data->push([
                        'jo' => $j->jo_no,
                        'status' => GC::viewJoStatus($j->status),
                        'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                        'action' => GC::joRequestorAction($j->status, $j->id, $j->jo_no)
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
       
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

        if($wro->cancelled == 1 || $wro->approval_sequence != 1) {
            return false;
        }

        $wro->cancelled = 1;
        $wro->cancelled_on = now();
        $wro->reason = $comment;
        $wro->save();

        return true;
        
    }



    public function viewJobOrder($id)
    {
        $jo = Jo::findorfail($id);

        if($jo->user_id != Auth::user()->id) {
            return abort(404);
        }

        return view('user.job-order-view', ['jo' => $jo]);
    }



    public function cancelJobOrder($id, $comment)
    {
        $jo = Jo::find($id);

        if($jo->user_id != Auth::user()->id) {
            return abort(404);
        }

        if($jo->status == 3 || $jo->status == 2 || $jo->archived == 1) {
            return redirect()->route('user.dashboard')->with('error', 'Please check JO Status.');
        }

        $jo->reason = $comment;
        $jo->cancelled_on = now();
        $jo->status = 3;
        $jo->save();

    }



    public function jobOrder()
    {
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();

        if(empty($ra)) {
            return redirect()->back()->with('error', 'Please request to setup your Approvers!');
        }


        $next_jo_series = GC::nextJoSeries(Auth::user()->id);


        $uom = Uom::where('active', 1)->get();


    	return view('user.job-order', ['uom' => $uom, 'next_jo_series' => $next_jo_series]);
    }


    public function postJobOrder(Request $request)
    {
        $request->validate([
            'date_of_request' => 'required',
            'date_needed' => 'required',
            'project' => 'required',
            'description' => 'required',
            'attachment' => 'nullable|mimes:pdf|max:10000',
            'cost' => 'numeric|nullable'
        ]);

        // check if setup is ok
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();

        if(empty($ra)) {
            return redirect()->back()->with('error', 'Please request to setup your Approvers!');
        }

        // validate request data

        // generate jo number per farm with checking

        $number = GeneralController::generateJoNo(Auth::user()->id);

        if($number == '0') {
            return redirect()->back()->with('error', 'Requestor has no designated farm.');
        }


        $jo = new Jo();
        $jo->jo_no = $number;
        $jo->user_id = Auth::user()->id;
        $jo->requestor = Auth::user()->first_name . ' ' . Auth::user()->last_name;
        $jo->manager_id = $ra->manager;
        $jo->date_of_request = date('Y-m-d', strtotime($request->date_of_request));
        $jo->date_needed = date('Y-m-d', strtotime($request->date_needed));
        $jo->project_bldg_no = $request->project;
        $jo->description = $request->description;
        $jo->remarks = $request->remarks;
        if($request->cost != NULL) {
            $jo->cost = $request->cost;
        }

        if($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = $jo->jo_no . '.pdf';
            $attachment->move(public_path('/uploads/jo/'), $filename);
            $jo->attachment = $filename;
        }

        // once saved
        // create list of items on the JO

        if($jo->save()) {

            $insert = [];

            for($i = 0; $i < count($request->items); $i++) {

                $insert[] = [
                    'jo_id' => $jo->id,
                    'item_name' => $request->items[$i],
                    'uom' => $request->uom[$i],
                    'quantity' => $request->qty[$i],
                    'on_stock' => $request->stock[$i] == 'on_stock' ? 1 : 0,
                    'to_purchase' => $request->stock[$i] == 'to_purchase' ? 1 : 0,
                ];
            }

            DB::table('job_order_items')->insert($insert);

        }


        // Send mail Notification to Manager
        $approver = GC::getName($jo->manager_id); # name of manager - important
        $approver_email = GC::getEmail($jo->manager_id); # email of receiver - important
        $requestor = $jo->user->first_name . ' ' . $jo->user->last_name; # name of requestor - important
        $requestor_designation = 'Requestor';

        $jo_view_route = "manager.view.job.order"; # manager route for jo details
        $jo_id = $jo->id;
        $jo_no = $jo->jo_no;
        MC::joManagerApproval($approver, $approver_email, $requestor, $requestor_designation, $jo_view_route, $jo_id, $jo_no);

        return redirect()->route('user.job.order')->with('success', 'Job Order ' . $jo->jo_no . ' Submitted Successfully!');
        

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

    	return view('user.work-order', ['next_wro_number' => $next_wro_number, 'farms' => $farms]);
    }


    public function postWorkOrder(Request $request)
    {
        $request->validate([
            'date_of_request' => 'required',
            'date_needed' => 'required',
            'project' => 'required',
            'description' => 'required',
            'justification' => 'required'
        ]);

        if($request->hasFile('attachment')) {
            $request->validate([
                'attachment' => 'max:50000'
            ]);
        }

        // check if setup is ok
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();


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
        $wro->project_bldg_no = $request->project;
        $wro->description = $request->description;
        $wro->justification = $request->justification;

        $wro->approval_sequence = 3;

        $wro->farm_manager_id = $farm_manager->id; # new
        $wro->farm_divhead_id = $farm_div_head->id; # new

        $wro->manager_id = $ra->manager;
        $wro->div_head_id = $ra->div_head;

        if($request->hasFile('attachment')) {
            $attachment = $request->file('attachment');
            $filename = $wro->wr_no . '.pdf';
            $attachment->move(public_path('/uploads/wro/'), $filename);
            $wro->attachment = $filename;
        }

        if($wro->save()) {

            $approver = GC::getName($wro->manager_id); # name of manager - important
            $approver_email = GC::getEmail($wro->manager_id); # email of receiver - important
            $requestor = $wro->user->first_name . ' ' . $wro->user->last_name; # name of requestor - important
            $requestor_designation = GC::getUserPosition($wro->user_id);
            $wro_view_route = "manager.view.work.order";
            $wro_id = $wro->id;
            $wro_no = $wro->wr_no;
            MC::wroManagerApproval($approver, $approver_email, $requestor, $requestor_designation, $wro_view_route, $wro_id, $wro_no);

            return redirect()->back()->with('success', 'Work Request Order ' . $wro->wr_no . ' Submitted Successfully!');
        }

        return redirect()->back()->with('error', 'Please Try Again Later.');


    }



    public function archivedJO()
    {
        return view('user.archived-jo');
    }


    public function allArchivedJO(Request $request)
    {
        /*
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];

        $jos = Jo::where('user_id', Auth::user()->id)
                    ->where('archived', 1)
                    ->get();

        if(count($jos) > 0) {
            $data = [];
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'status' => GC::viewJoStatus($j->status),
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                    'action' => GC::joRequestorAction($j->status, $j->id, $j->jo_no), 
                ];
            }
        }

        return $data;
        */

        if($request->ajax()) {
            $jo = Jo::where('user_id', Auth::user()->id)
                    ->where('archived', 1)
                    ->get();
            $data = collect();
            if(count($jo) > 0) {
                foreach($jo as $j) {
                    $data->push([
                        'jo' => $j->jo_no,
                        'status' => GC::viewJoStatus($j->status),
                        'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                        'action' => GC::joRequestorAction($j->status, $j->id, $j->jo_no)
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
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
