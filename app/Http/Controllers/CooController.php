<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;
use App\WroApproval;
use App\WorkOrder as Wo;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

class CooController extends Controller
{
	public function dashboard()
	{
		return view('coo.dashboard');
	}    

    public function account()
    {
        return view('coo.account');
    }


	public function viewWro($id)
	{
		$wro = Wo::findorfail($id);

		return view('coo.work-order-view', ['wro' => $wro]);
	}


	public function wroApproval($id)
	{
        $wro = Wo::findorfail($id);

        if($wro->cancelled == 1 || $wro->approval_sequence != 6 || $wro->disapproved == 1) {
            return abort(404);
        }

        $wro->approval_sequence = 7;
        $wro->coo_id = Auth::user()->id;
        $wro->coo_approval = 1;
        $wro->coo_approved = date('Y-m-d H:i:s', strtotime(now()));
        $wro->save();

        # Send email to next Approver (COO)
        $approvals  = WroApproval::find(1);
        $next_approver = GC::getName($approvals->vp_gen_serv);
        $next_approver_email = GC::getEmail($approvals->vp_gen_serv);
        $prev_approver = GC::getName($approvals->coo);
        $prev_approver_designation = 'Cheif Operations Officer';
        $wro_view_route = 'vp.view.work.order';
        $wro_id = $wro->id;
        $wro_no = $wro->wr_no;

        MC::wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no);

        return true;


	}



	public function wroDisapproval($id, $comment)
	{
        $wro = Wo::findorfail($id);


        if($wro->cancelled == 1 || $wro->approval_sequence != 6 || $wro->disapproved == 1) {
            return abort(404);
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
        $approver = GC::getName($approvals->coo);
        $approver_designation = 'Chief Operations Officer';
        $wro_view_route = 'user.view.work.order';

        MC::wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro->id, $wro->wr_no);

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

		if($approvals->coo == Auth::user()->id) {
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
						'action' => GC::wroCooAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
					];
				}
			}
		}

		return $data;
	}


    public function archivedWro()
    {
        return view('coo.wro-archived');
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

        if($approvals->coo == Auth::user()->id) {
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
                        'action' => GC::wroCooAction($w->approval_sequence, $w->id, $w->wr_no, $w->cancelled, $w->disapproved, $w->archived),
                    ];
                }
            }
        }

        return $data;
    }
}
