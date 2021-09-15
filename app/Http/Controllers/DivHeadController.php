<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\WorkOrder as Wo;
use App\JobOrder as Jo;

use App\Department;

use App\RequestorApprover as Ra;

use App\WroApproval;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

use DataTables;
use App\Billing;


class DivHeadController extends Controller
{
    public function dashboard()
    {
    	return view('divhead.dashboard');
    }

    public function account()
    {
        return view('divhead.account');
    }




    public function allWorkOrder()
    {
        $data = [
            [
                'wro' => NULL,
                'status' => NULL,
                'date_of_request' => NULL,
                'actual_date_filed' => NULL,
                'action' => NULL,
            ]
        ];


        $wro1 = [];
        $wro2 = [];

        # Start of Div Head
    	$wro1 = Wo::where('farm_divhead_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();

        if(count($wro1) > 0) {
        	$data = [];
        	foreach($wro1 as $w) {

                $data[] = [
                    'wro' => $w->wr_no,
                    'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                    'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                    'action' => GC::wroDivHeadAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                ];
            }
        }
        # End of Div Head
        
        // Check for Gen Services Div Head
        $wro_approval = WroApproval::where('active', 1)->first();

        # Start of Gen Serv Div Head
        if($wro_approval->gen_serv_div_head == Auth::user()->id) {
            $wro2 = Wo::where('archived', 0)
                    ->get();


            if(count($wro2) > 0) {


                foreach($wro2 as $w) {
                    $data[] = [
                        'wro' => $w->wr_no,
                        'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::wroGenServDivHeadAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }
        # End of Gen Serv Div Head

        if(count($wro1) < 1 && count($wro2) < 1) {
            $data = [
                'wro' => NULL,
                'status' => NULL,
                'action' => NULL
            ];
        } 

        return $data;

    }


    public function viewWorkOrder($id)
    {
    	$wro = Wo::findorfail($id);

        // if($wro->div_head_id != Auth::user()->id) {
        //     return false;
        // }


        return view('divhead.work-order-view', ['wro' => $wro]);
    }



    public function wroApproval($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->farm_divhead_id != Auth::user()->id) {
            return false;
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 6 || $wro->disapproved == 1) {
            return false;
        }

        $wro->approval_sequence = 7;
        $wro->farm_divhead_approval = 1;
        $wro->farm_divhead_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        # Send email to next Approver (Treasury Manager)

        $approvals =  WroApproval::find(1);


        $next_approver = GC::getName($approvals->treasury_manager);
        $next_approver_email = GC::getEmail($approvals->treasury_manager);
        $prev_approver = GC::getName($wro->farm_divhead_id);
        $prev_approver_designation = 'Division Head';
        $wro_view_route = 'manager.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;

        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
         
        return true;
    }



    public function wroDisapproval($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->farm_divhead_id != Auth::user()->id) {
            return false;
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 6 || $wro->disapproved == 1) {
            return false;
        }

        $wro->disapproved_by = Auth::user()->id;
        $wro->disapproved = 1;
        $wro->disapproved_on = now();
        $wro->reason = $comment;
        $wro->save();

        # Send Disapproval Email Notification to Requestor
         $approvals = WroApproval::find(1);
        $requestor = GC::getName($wro->user_id);
        $requestor_email = GC::getEmail($wro->user_id);
        $approver = GC::getName($approvals->farm_divhead_id);
        $approver_designation = 'Division Head';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);


        return true;
    }


    public function archivedWRO()
    {
        return view('divhead.archived-wro');
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

        $wro = Wo::where('archived', 1)
                    ->get();

        if(count($wro) > 0) {
            $data = [];
            foreach($wro as $w) {
                $data[] = [
                    'wro' => $w->wr_no,
                    'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                    'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                    'action' => GC::wroDivHeadAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                ];
            }
        }

        return $data;
    }


    public function wroGsDivHeadApproval($id)
    {
        $wro = Wo::findorfail($id);


        if($wro->cancelled == 1 || $wro->approval_sequence != 4 || $wro->disapproved == 1) {
            return false;
        }

        if($wro->farm_manager_id == NULL || $wro->farm_manager_id == '') {
            return redirect()->back()->with('error', 'Invalid Entry! Cancel this WRO and create new.');
        }

        if($wro->farm_divhead_id == NULL || $wro->farm_divhead_id == '') {
            return redirect()->back()->with('error', 'Invalid Entry! Cancel this WRO and create new.');
        }


        $wro->approval_sequence = 5;
        $wro->gen_serv_div_head_id = Auth::user()->id;
        $wro->gen_serv_div_head_approval = 1;
        $wro->gen_serv_div_head_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        


        $approvals =  WroApproval::find(1);


        $next_approver = GC::getName($wro->farm_manager_id); 
        $next_approver_email = GC::getEmail($wro->farm_manager_id); 
        $prev_approver = GC::getName($approvals->gen_serv_div_head);
        $prev_approver_designation = 'General Services - Division Head';
        $wro_view_route = 'manager.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;
        # Send email to next Approver (Farm Manager)
        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
        return true;
    }



    public function wroGsDivHeadDisapproval($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 4 || $wro->disapproved == 1) {
            return false;
        }

        $wro->disapproved_by = Auth::user()->id;
        $wro->disapproved = 1;
        $wro->disapproved_on = now();
        $wro->reason = $comment;
        $wro->save();

        # Send Disapproval Email Notification to Requestor

         $approvals = WroApproval::find(1);
        $requestor = GC::getName($wro->user_id);
        $requestor_email = GC::getEmail($wro->user_id);
        $approver = GC::getName($approvals->gen_serv_div_head);
        $approver_designation = 'General Services - Division Head';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;
    }



    // archived billing
    public function archivedBilling()
    {
        return view('divhead.archived-billing');
    }



    // all billing
    public function allBilling(Request $request)
    {
        if($request->ajax()) {

            $wro_approval = WroApproval::where('active', 1)->first();

            $data = collect();

            # start of GS Div head
            if($wro_approval->gen_serv_div_head == Auth::user()->id) {
                $billing = Billing::where('archived', 0)
                        ->get();

                if(count($billing) > 0) {

                    foreach($billing as $w) {
                        if($w->approval_sequence >= 4) {
                            $data->push([
                                'ref' => $w->reference_number,
                                'project_name' => $w->project_name,
                                'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                                'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                                'action' => 'action',
                            ]);
                        }
                    }
                }
            }
            # end of GS Div head
            
            # start of farm/dept div head
            $billing1 = Billing::where('farm_divhead_id', Auth::user()->id)
                        ->where('archived', 0)
                        ->get();

            if(count($billing1) > 0) {
                $data = [];
                foreach($billing1 as $w) {
                    if($w->approval_sequence >= 5) {
                        $data->push([
                            'ref' => $w->wr_no,
                            'project_name' => $w->project_name,
                            'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                            'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                            'action' => 'action',
                        ]);
                    }
                }
            }
            # end o

            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
    }

}
