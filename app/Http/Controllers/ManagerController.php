<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\JobOrder as Jo;
use App\WorkOrder as Wo;
use Auth;
use App\Billing;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

use App\WroApproval;
use DataTables;

class ManagerController extends Controller
{
    /**
     * [dashboard Manager Dashboard]
     * @return [type] [description]
     */
    public function dashboard()
    {
    	return view('manager.dashboard');
    }

    public function account()
    {
        return view('manager.account');
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
        $wro3 = [];


        # start of First Approver Manager
        $wro1 = Wo::where('farm_manager_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();

        if(count($wro1) > 0) {
            $data = [];
            foreach($wro1 as $w) {
                if($w->approval_sequence >= 5) {
                    $data[] = [
                        'wro' => $w->wr_no,
                        'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::wroManagerAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }
        # end of First Approver Manger


        // check BCM Manager and Treasury Manager
        $wro_approval = WroApproval::where('active', 1)->first();

        # start of BCM Manager
        if($wro_approval->bcm_manager == Auth::user()->id) {
            $wro2 = Wo::where('archived', 0)
                    ->get();


            if(count($wro2) > 0) {


                foreach($wro2 as $w) {
                    if($w->approval_sequence >= 3) {
                        $data[] = [
                            'wro' => $w->wr_no,
                            'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                            'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                            'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                            'action' => GC::wroBCMManagerAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                        ];
                    }
                }
            }
        }
        # end of BCM Manager

        # start of treasury manager
        if($wro_approval->treasury_manager == Auth::user()->id) {
            $wro3 = Wo::where('archived', 0)
                    ->get();


            if(count($wro3) > 0) {


                foreach($wro3 as $w) {
                    // if($w->approval_sequence >= 5) { # old code changed on 9/1/21
                    if($w->approval_sequence >= 7) {
                        $data[] = [
                            'wro' => $w->wr_no,
                            'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                            'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                            'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                            'action' => GC::wroTreasuryMgrAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                        ];
                    }
                }
            }
        }
        # end of treasury manager
       
        if(count($wro1) < 1 && count($wro2) < 1 && count($wro3) < 1) {
            $data = [
                'wro' => NULL,
                'status' => NULL,
                'date_of_request' => NULL,
                'actual_date_filed' => NULL,
                'action' => NULL
            ];
        } 
        

        return $data;
    }


    public function viewWorkOrder($id)
    {
        $wro = Wo::findorfail($id);

        return view('manager.work-order-view', ['wro' => $wro]);
    }


    public function wroApproval($id)
    {
        // check owner ship
        $wro = Wo::findorfail($id);

        if($wro->farm_manager_id != Auth::user()->id) {
            return abort(404);
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 5) {
            return false;
        }

        $wro->approval_sequence = 6;
        $wro->farm_manager_approval = 1;
        $wro->farm_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        $next_approver = GC::getName($wro->farm_divhead_id);
        $next_approver_email = GC::getEmail($wro->farm_divhead_id);
        $prev_approver = GC::getName($wro->farm_manager_id);
        $prev_approver_designation = 'Manager';
        $wro_view_route = 'divhead.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;

        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);

        return true;
    }



    public function wroDisapproval($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->farm_manager_id != Auth::user()->id) {
            return abort(500);
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 5) {
            return abort(500);
        }

        $wro->disapproved_by = Auth::user()->id;
        $wro->disapproved = 1;
        $wro->disapproved_on = now();
        $wro->reason = $comment;
        $wro->save();

        # Send Disapproval Email Notification to Requestor

        // $approvals = WroApproval::find(1);
        $requestor = GC::getName($wro->user_id);
        $requestor_email = GC::getEmail($wro->user_id);
        $approver = GC::getName($wro->farm_manager_id);
        $approver_designation = 'Manager';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;

    }



    public function wroArchive($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->bcm_manager_id != Auth::user()->id) {
            // return false; # needs to be uncomment
            return abort(500);
        }

        $wro->archived = 1;
        $wro->archived_on = date('Y-m-d H:i:s', strtotime(now()));
        $wro->archived_by = Auth::user()->id;
        $wro->save();

        return true;
    }




    public function archivedWRO()
    {
        return view('manager.archived-wro');
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

        $approvers = WroApproval::find(1);

        if($approvers->bcm_manager == Auth::user()->id) {
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
                        'action' => GC::wroBCMManagerAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }
        elseif( $approvers->treasury_manager == Auth::user()->id) {
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
                        'action' => GC::wroTreasuryMgrAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }
        else {
            $wro = Wo::where('archived', 1)
                ->where('farm_manager_id', Auth::user()->id)
                ->get();

            if(count($wro) > 0) {
                $data = [];
                foreach($wro as $w) {
                    $data[] = [
                        'wro' => $w->wr_no,
                        'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::wroManagerAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }


        return $data;
    }






    public function wroBCMManagerApproval($id)
    {
        // validate
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 3 || $wro->disapproved == 1) {
            return false;
        }

        // update
        $wro->approval_sequence = 4;
        $wro->bcm_manager_id = Auth::user()->id;
        $wro->bcm_manager_approval = 1;
        $wro->bcm_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        // save

        $approvals =  WroApproval::find(1);


        # Send Email to Next Approver (GS Div Head)

        $next_approver = GC::getName($approvals->gen_serv_div_head);
        $next_approver_email = GC::getEmail($approvals->gen_serv_div_head);
        $prev_approver = GC::getName($approvals->bcm_manager);
        $prev_approver_designation = 'BCM Manager';
        $wro_view_route = 'divhead.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;

        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
 
        
        return true;
    }



    public function wroBCMManagerDisapproval($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 3 || $wro->disapproved == 1) {
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
        $approver = GC::getName($approvals->bcm_manager);
        $approver_designation = 'BCM Manager';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;


    }


    public function wroTrsryManagerApproval($id)
    {
        // validate
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 7 || $wro->disapproved == 1) {
            return false;
        }

        // update
        $wro->approval_sequence = 8;
        $wro->treasury_manager_id = Auth::user()->id;
        $wro->treasury_manager_approval = 1;
        $wro->treasury_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        // save

        $approvals =  WroApproval::find(1);

        $next_approver = GC::getName($approvals->vp_gen_serv);
        $next_approver_email = GC::getEmail($approvals->vp_gen_serv);
        $prev_approver = GC::getName($approvals->treasury_manager);
        $prev_approver_designation = 'Treasury Manager';
        $wro_view_route = 'vp.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;

        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
        return true;
    }


    public function wroTrsryManagerDisapproval($id, $comment)
    {
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 7 || $wro->disapproved == 1) {
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
        $approver = GC::getName($approvals->treasury_manager);
        $approver_designation = 'Treasury Manager';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);
        
        return true;
    }




    // Billing
    public function archivedBilling()
    {
        return view('manager.archived-billing');
    }


    // All Billing
    public function allBilling(Request $request)
    {
        if($request->ajax()) {

            $wro_approval = WroApproval::where('active', 1)->first();

            $data = collect();

            # start of BCM Manager
            if($wro_approval->bcm_manager == Auth::user()->id) {
                $billing = Billing::where('archived', 0)
                        ->get();

                if(count($billing) > 0) {

                    foreach($billing as $w) {
                        if($w->approval_sequence >= 3) {
                            $data->push([
                                'ref' => $w->reference_number,
                                'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                                'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                                'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                                'action' => GC::billingBCMManagerAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                            ]);
                        }
                    }
                }
            }
            # end of BCM Manager

            # start of treasury manager
            if($wro_approval->treasury_manager == Auth::user()->id) {
                $billing3 = Billing::where('archived', 0)
                        ->get();


                if(count($billing3) > 0) {


                    foreach($billing3 as $w) {
                        // if($w->approval_sequence >= 5) { # old code changed on 9/1/21
                        if($w->approval_sequence >= 7) {
                            $data->push([
                                'ref' => $w->reference_number,
                                'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                                'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                                'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                                'action' => GC::billingTreasuryMgrAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                            ]);
                        }
                    }
                }
            }
            # end of treasury manager
            
            # start of First Approver Manager
            $billing1 = Billing::where('farm_manager_id', Auth::user()->id)
                        ->where('archived', 0)
                        ->get();

            if(count($billing1) > 0) {
                $data = [];
                foreach($billing1 as $w) {
                    if($w->approval_sequence >= 5) {
                        $data[] = [
                            'ref' => $w->reference_number,
                            'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                            'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                            'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                            'action' => GC::billingManagerAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                        ];
                    }
                }
            }
            # end of First Approver Manger

            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
    }



    public function allArchivedBilling(Request $request)
    {
        if($request->ajax()) {

            $wro_approval = WroApproval::where('active', 1)->first();

            $data = collect();

            # start of BCM Manager
            if($wro_approval->bcm_manager == Auth::user()->id) {
                $billing = Billing::where('archived', 1)
                        ->get();

                if(count($billing) > 0) {

                    foreach($billing as $w) {
                        if($w->approval_sequence >= 3) {
                            $data->push([
                                'ref' => $w->reference_number,
                                'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                                'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                                'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                                'action' => GC::billingBCMManagerAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                            ]);
                        }
                    }
                }
            }
            # end of BCM Manager

            # start of treasury manager
            if($wro_approval->treasury_manager == Auth::user()->id) {
                $billing3 = Billing::where('archived', 1)
                        ->get();


                if(count($billing3) > 0) {


                    foreach($billing3 as $w) {
                        // if($w->approval_sequence >= 5) { # old code changed on 9/1/21
                        if($w->approval_sequence >= 7) {
                            $data->push([
                                'ref' => $w->reference_number,
                                'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                                'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                                'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                                'action' => GC::billingTreasuryMgrAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                            ]);
                        }
                    }
                }
            }
            # end of treasury manager
            
            # start of First Approver Manager
            $billing1 = Billing::where('farm_manager_id', Auth::user()->id)
                        ->where('archived', 1)
                        ->get();

            if(count($billing1) > 0) {
                $data = [];
                foreach($billing1 as $w) {
                    if($w->approval_sequence >= 5) {
                        $data[] = [
                            'ref' => $w->reference_number,
                            'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                            'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                            'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                            'action' => GC::billingManagerAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved, $w->archived),
                        ];
                    }
                }
            }
            # end of First Approver Manger

            return DataTables::of($data)
                    ->rawColumns(['status', 'action'])
                    ->make(true);

        }
    }



    public function viewBilling($id)
    {
        $billing = Billing::findorfail($id);

        return view('manager.billing-view', ['billing' => $billing]);
    }



    public function billingBCMManagerApproval($id)
    {
        // validate
        $billing = Billing::findorfail($id);

        if($billing->cancelled == 1 || $billing->approval_sequence != 3 || $billing->disapproved == 1) {
            return false;
        }

        // update
        $billing->approval_sequence = 4;
        $billing->bcm_manager_id = Auth::user()->id;
        $billing->bcm_manager_approval = 1;
        $billing->bcm_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $billing->save();

        // save

        $approvals =  WroApproval::find(1);


        # Send Email to Next Approver (GS Div Head)

        $next_approver = GC::getName($approvals->gen_serv_div_head);
        $next_approver_email = GC::getEmail($approvals->gen_serv_div_head);
        $prev_approver = GC::getName($approvals->bcm_manager);
        $prev_approver_designation = 'BCM Manager';
        $wro_view_route = 'divhead.view.work.order';
        $wro_id = $billing->id;
        $wro_no = $billing->reference_number;

        MC::billingNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
 
        
        return true;
    }



    public function billingBCMManagerDisapproval($id, $comment)
    {
        $wro = Billing::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 3 || $wro->disapproved == 1) {
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
        $approver = GC::getName($approvals->bcm_manager);
        $approver_designation = 'BCM Manager';
        $wro_view_route = 'user.view.work.order';

        MC::billingDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->reference_number);

        return true;


    }



    public function billingApproval($id)
    {
        // check owner ship
        $wro = Billing::findorfail($id);

        if($wro->farm_manager_id != Auth::user()->id) {
            return abort(404);
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 5) {
            return false;
        }

        $wro->approval_sequence = 6;
        $wro->farm_manager_approval = 1;
        $wro->farm_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        $next_approver = GC::getName($wro->farm_divhead_id);
        $next_approver_email = GC::getEmail($wro->farm_divhead_id);
        $prev_approver = GC::getName($wro->farm_manager_id);
        $prev_approver_designation = 'Manager';
        $wro_view_route = 'divhead.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->reference_number;

        MC::billingNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);

        return true;
    }



    public function billingTrsryManagerApproval($id)
    {
        // validate
        $wro = Billing::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 7 || $wro->disapproved == 1) {
            return false;
        }

        // update
        $wro->approval_sequence = 8;
        $wro->treasury_manager_id = Auth::user()->id;
        $wro->treasury_manager_approval = 1;
        $wro->treasury_manager_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        // save

        $approvals =  WroApproval::find(1);

        $next_approver = GC::getName($approvals->vp_gen_serv);
        $next_approver_email = GC::getEmail($approvals->vp_gen_serv);
        $prev_approver = GC::getName($approvals->treasury_manager);
        $prev_approver_designation = 'Treasury Manager';
        $wro_view_route = 'vp.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->reference_number;

        MC::billingNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);
        return true;
    }



    public function billingTrsryManagerDisapproval($id)
    {
        $wro = Billing::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 7 || $wro->disapproved == 1) {
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
        $approver = GC::getName($approvals->treasury_manager);
        $approver_designation = 'Treasury Manager';
        $wro_view_route = 'user.view.work.order';

        MC::billingDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->reference_number);
        
        return true;
    }


    public function archiveBilling($id)
    {
        $wro = Billing::findorfail($id);

        if($wro->bcm_manager_id != Auth::user()->id) {
            return 'error';
        }

        $wro->archived = 1;
        $wro->archived_on = date('Y-m-d H:i:s', strtotime(now()));
        $wro->archived_by = Auth::user()->id;
        $wro->save();

        return 'ok';
    }



    public function billingDisapproval($id, $comment)
    {
        $wro = Billing::findorfail($id);

        if($wro->farm_manager_id != Auth::user()->id) {
            return abort(500);
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 5) {
            return abort(500);
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
        $approver = GC::getName($wro->farm_manager_id);
        $approver_designation = 'Manager';
        $wro_view_route = 'user.view.work.order';

        MC::billingDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;
    }
}
