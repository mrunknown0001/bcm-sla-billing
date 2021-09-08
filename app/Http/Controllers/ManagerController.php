<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\JobOrder as Jo;
use App\WorkOrder as Wo;
use Auth;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

use App\WroApproval;

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


    /**
     * [viewJobOrder Viewing method for Job Order in Manager Level]
     * @param  [type] $id [description]
     * @return [type]     [description]
     */
    public function viewJobOrder($id)
    {
        $jo = Jo::findorfail($id);

        if($jo->manager_id != Auth::user()->id) {
            return abort(404);
        }

        return view('manager.job-order-view', ['jo' => $jo]);
    }



    public function joDisapproved ($id, $comment)
    {
        $jo = Jo::findorfail($id);
        
        if($jo->manager_id != Auth::user()->id) {
            return false;
        }

        if($jo->status == 3 || $jo->status == 2 || $jo->archived == 1) {
            return false;
        }

        $jo->reason = $comment;
        $jo->disapproved_on = now();
        $jo->status = 4;
        $jo->save();

        $approver = GC::getName($jo->manager_id); 
        $approver_designation = "Manager";
        $requestor = GC::getName($jo->user_id);
        $requestor_email =$jo->user->email;
        $jo_view_route = "user.view.job.order";
        $jo_id = $jo->id;
        $jo_no = $jo->jo_no;
        
        MC::joDisapproved($approver, $approver_designation, $requestor, $requestor_email, $jo_view_route, $jo_id, $jo_no);

    }


    public function joApproval($id)
    {
        $jo = Jo::findorfail($id);
        
        if($jo->manager_id != Auth::user()->id) {
            return abort(404);
        }

        if($jo->status == 3 || $jo->status == 2 || $jo->archived == 1) {
            return redirect()->route('manager.dashboard')->with('error', 'Please check JO Status.');
        }


        // condition on cost if vp will going to approve it
        if($jo->cost >= 50000) {
            $jo->status = 5;
        }
        else {
            $jo->status = 2;
        }
    	$jo->manager_approval = 1;
    	$jo->manager = Auth::user()->first_name . ' ' . Auth::user()->last_name;
    	$jo->manager_approved = now();

    	if($jo->save()) {
            $approver = GC::getName($jo->manager_id); 
            $approver_designation = "Manager";
            $requestor = GC::getName($jo->user_id);
            $requestor_email =$jo->user->email;
            $jo_view_route = "user.view.job.order";
            $jo_id = $jo->id;
            $jo_no = $jo->jo_no;
            
            MC::joApproved($approver, $approver_designation, $requestor, $requestor_email, $jo_view_route, $jo_id, $jo_no);

    		return 'ok';
    	}
		
		return 'error';

    }



    public function archiveJO($id)
    {
        $jo = Jo::findorfail($id);
        
        if($jo->manager_id != Auth::user()->id) {
            return false;
        }

        if($jo->status == 1 || $jo->archived == 1) {
            return false;
        }

        $jo->archived = 1;
        $jo->archived_on = now();
        $jo->save();
    }



    public function allJobOrder()
    {
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];

        $jos = Jo::where('manager_id', Auth::user()->id)
                    ->where('archived', 0)
                    ->get();

        if(count($jos) > 0) {
            $data = [];
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'approver' => GC::getName($j->manager_id),
                    'status' => GC::viewJoStatus($j->status),
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                    'action' => GC::joManagerAction($j->status, $j->id, $j->jo_no),
                ];
            }
        }

        return $data;
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

        if($wro->manager_id != Auth::user()->id) {
            return false;
        }

        if($wro->cancelled == 1 || $wro->approval_sequence != 5) {
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
        $approver = GC::getName($wro->manager_id);
        $approver_designation = 'Manager';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;

    }



    public function wroArchive($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->manager_id != Auth::user()->id) {
            return false;
        }

        $wro->archived = 1;
        $wro->archived_on = date('Y-m-d H:i:s', strtotime(now()));
        $wro->archived_by = Auth::user()->id;
        $wro->save();

        return true;
    }


    public function archivedJO()
    {
        return view('manager.archived-jo');
    }

    public function allArchivedJO()
    {
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL,
        ];


        $jos = Jo::where('manager_id', Auth::user()->id)
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
                    'action' => $j->status == 2 ? '<button id="view" data-id="' . $j->id . '" data-text="Do you want to view Job Order ' . $j->jo_no . '?" class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button> <a href="' . route("manager.jo.pdf.download", ['id' => $j->id]) . '" class="btn btn-primary btn-xs"><i class="pe-7s-download"></i> Download</a>'
                    :
                    '<button id="view" data-id="' . $j->id . '" data-text="Do you want to view Job Order ' . $j->jo_no . '?" class="btn btn-info btn-xs"><i class="pe-7s-look"></i> View</button>', 
                ];
            }
        }

        return $data;

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

        $wro = Wo::where('manager_id', Auth::user()->id)
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
                    'action' => GC::wroManagerAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                ];
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

}
