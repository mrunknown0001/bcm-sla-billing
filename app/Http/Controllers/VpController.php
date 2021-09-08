<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\WroApproval;

use App\WorkOrder as Wo;
use App\JobOrder as Jo;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

class VpController extends Controller
{
    public function dashboard()
    {
    	return view('vp.dashboard');
    }

    public function account()
    {
        return view('vp.account');
    }


    public function allJobOrder()
    {
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL
        ];

        // Manual setting of cost throttle
        $jos = Jo::where('cost', '>=', 50000)
                    ->where('archived', 0)
                    ->get();

        if(count($jos) > 0) {
            $data = NULL;
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'status' => GC::viewJoStatus($j->status),
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                    'action' => GC::joVPAction($j->status, $j->id, $j->jo_no)
                ];
            }
        }

        return $data;
    }



    public function viewJO($id)
    {
        $jo = Jo::findorfail($id);

        return view('vp.job-order-view', ['jo' => $jo]);
    }


    public function joApproval($id)
    {
       $jo = Jo::findorfail($id);
        
        if($jo->cost < 50000 || $jo->manager_approval == 0) {
            return false;
        }

        $jo->vp_approval = 1;
        $jo->vp_id = Auth::user()->id;
        $jo->vp_approved = now();
        $jo->status = 6;

        if($jo->save()) {

            # Send Approval Notice to Requestor
            $approver = GC::getName($jo->vp_id); 
            $approver_designation = "VP on General Services";
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



    public function joDisapproval($id, $comment)
    {
        $jo = Jo::findorfail($id);
        
        if($jo->cost < 50000 || $jo->manager_approval == 0) {
            return false;
        }

        $jo->vp_id = Auth::user()->id;

        $jo->reason = $comment;
        $jo->disapproved_on = now();
        $jo->status = 7;
        $jo->save();

        # Send Disapproval Notice to Requestor

        $approver = GC::getName($jo->vp_id); 
        $approver_designation = "VP on General Service";
        $requestor = GC::getName($jo->user_id);
        $requestor_email =$jo->user->email;
        $jo_view_route = "user.view.job.order";
        $jo_id = $jo->id;
        $jo_no = $jo->jo_no;
        
        MC::joDisapproved($approver, $approver_designation, $requestor, $requestor_email, $jo_view_route, $jo_id, $jo_no);

        return true;
    }



    public function allWorkOrder()
    {
		$data = [
			'wro' => NULL,
			'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
			'action' => NULL
		];

		$approvals = WroApproval::where('active', 1)->first();

		if($approvals->vp_gen_serv == Auth::user()->id) {
			$wro = Wo::where('archived', 0)
					->get();

			if(count($wro) > 0) {
				$data = NULL;
				foreach($wro as $w) {
					$data[] = [
						'wro' => $w->wr_no,
						'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
						'action' => GC::wroGsVpAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
					];
				}
			}
		}

		return $data;

    }



    public function viewWorkOrder($id)
    {
    	$wro = Wo::findorfail($id);

    	return view('vp.work-order-view', ['wro' => $wro]);
    }


    public function wroGsVPApproval($id)
    {
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 8 || $wro->disapproved == 1) {
            return false;
        }

        $wro->approval_sequence = 9;
        $wro->vp_gen_serv_id = Auth::user()->id;
        $wro->vp_gen_serv_approval = 1;
        $wro->vp_gen_serv_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        # Send email to Approver
        $approver = GC::getName(Auth::user()->id);
        $approver_designation = 'VP on General Services';
        $receivers = [
            GC::getEmail($wro->user_id),
            GC::getEmail($wro->bcm_manager_id),
            GC::getEmail($wro->gen_serv_div_head_id),
            GC::getEmail($wro->treasury_manager_id),
            GC::getEmail($wro->farm_manager_id),
            GC::getEmail($wro->farm_divhead_id),
            // GC::getEmail($wro->coo_id),
        ];
        // $receivers = ['m.trinidad@bfcgroup.org', 'maet.bgc@gmail.com'];
        $wro_no = $wro->wr_no;
        MC::wroApproved($approver, $approver_designation, $receivers, $wro_no);

        return true;
    }


    public function wroGsVpDisapproval($id, $comment)
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
        $approver = GC::getName($approvals->vp_gen_serv);
        $approver_designation = 'VP - General Services';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

        return true;

    }



    public function archivedWro()
    {
        return view('vp.wro-archived');
    }


    public function allArchivedWro()
    {
        $data = [
            'wro' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL
        ];

        $approvals = WroApproval::where('active', 1)->first();

        if($approvals->vp_gen_serv == Auth::user()->id) {
            $wro = Wo::where('archived', 1)
                    ->get();

            if(count($wro) > 0) {
                $data = NULL;
                foreach($wro as $w) {
                    $data[] = [
                        'wro' => $w->wr_no,
                        'status' => GC::viewWroStatus($w->approval_sequence, $w->cancelled, $w->disapproved),
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::wroGsVpAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }

        return $data;
    }



    public function archivedJo()
    {
        return view('vp.jo-archived');
    }


    public function allArchivedJo()
    {
        $data = [
            'jo' => NULL,
            'status' => NULL,
            'date_of_request' => NULL,
            'actual_date_filed' => NULL,
            'action' => NULL
        ];

        $jos = Jo::where('archived', 1)
                    ->get();

        if(count($jos) > 0) {
            $data = NULL;
            foreach($jos as $j) {
                $data[] = [
                    'jo' => $j->jo_no,
                    'status' => GC::viewJoStatus($j->status),                        
                    'date_of_request' => date('F j, Y', strtotime($j->date_of_request)),
                    'actual_date_filed' => date('F j, Y', strtotime($j->created_at)),
                    'action' => GC::joVPAction($j->status, $j->id, $j->jo_no)
                ];
            }
        }

        return $data;
    }
}
