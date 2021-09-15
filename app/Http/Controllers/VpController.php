<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Auth;

use App\WroApproval;

use App\WorkOrder as Wo;
use App\JobOrder as Jo;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

use App\Billing;
use DataTables;

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



    // Archived Billing
    public function archivedBilling ()
    {
        return view('vp.archived-billing');
    }


    // All Billing
    public function allBilling(Request $request)
    {
        if($request->ajax()) {
            
            $approvals = WroApproval::where('active', 1)->first();

            $data = collect();

            if($approvals->vp_gen_serv == Auth::user()->id) {
                $billing = Billing::where('archived', 0)
                        ->get();

                foreach($billing as $w) {
                    $data->push([
                        'ref' => $w->reference_number,
                        'project_name' => $w->project_name,
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => 'action', 
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['action'])
                    ->make(true);

        }
    }



    public function allArchivedBilling(Request $request)
    {
        if($request->ajax()) {
            
            $approvals = WroApproval::where('active', 1)->first();

            $data = collect();

            if($approvals->vp_gen_serv == Auth::user()->id) {
                $billing = Billing::where('archived', 1)
                        ->get();

                foreach($billing as $w) {
                    $data->push([
                        'ref' => $w->reference_number,
                        'project_name' => $w->project_name,
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => 'action', 
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['action'])
                    ->make(true);

        }
    }
}
