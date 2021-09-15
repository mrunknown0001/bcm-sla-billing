<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Billing;
use App\WorkOrder;
use Auth;
use App\User;

use App\RequestorApprover as Ra;
use App\Farm;

use DataTables;

use App\Http\Controllers\GeneralController as GC;
use App\Http\Controllers\MailController as MC;

class BillingController extends Controller
{
    // Create Billing
    public function billing()
    {
    	$sla = WorkOrder::select('wr_no','id')->get();
    	// chekc sla if already created for approval or approved or cancelled
    	// sla (wro) need to be approved prior to creation of billing or not for flexibility
    	return view('user.billing', ['sla' => $sla]);
    }


    // Post Create Billing
    public function postBilling(Request $request)
    {
    	$request->validate([
    		'reference_number' => 'required',
    		'date_of_request' => 'required',
    		'date_needed' => 'required',
    		'mobilization' => 'required',
    		'url' => 'required'
    	]);

    	// check if ref num is exist in sla (wro)
    	$ref = WorkOrder::where('wr_no', $request->reference_number)->first();
    	if(empty($ref)) {
    		return redirect()->route('user.billing')->with('error', 'SLA Reference Number is not existing!');
    	}

    	// if reference number is already in billing
    	$check = Billing::where('reference_number', $request->reference_number)->first();
    	if(!empty($check)) {
    		return redirect()->route('user.billing')->with('error', 'Reference Number is already used!');
    	}


    	// checking from sla
        $ra = Ra::where('user_id', Auth::user()->id)
                ->where('active', 1)
                ->first();

        $farm_code = substr($request->reference_number, 4, 3);
        $farm = Farm::where('code', $farm_code)->first();

        $farm_manager = User::where('farm_id', $farm->id)
                            ->where('user_type', 4)
                            // ->where([['dept_id', '=', 7],['dept_id', '=', 8]]) // poultry & swine
                            ->where(function ($query) {
                                $query->where('dept_id', 7)
                                    ->orWhere('dept_id', 8);
                            })
                            ->first();

        $farm_div_head = User::where('farm_id', $farm->id)
                            ->where('user_type', 3)
                            // ->where([['dept_id', '=', 7],['dept_id', '=', 8]]) // poultry & swine
                            ->where(function ($query) {
                                $query->where('dept_id', 7)
                                    ->orWhere('dept_id', 8);
                            })
                            ->first();

    	$billing = new Billing();
    	$billing->reference_number = $request->reference_number;
    	$billing->project_name = $ref->project_name;
    	$billing->user_id = Auth::user()->id;
    	$billing->date_of_request = date('Y-m-d', strtotime($request->date_of_request));
    	$billing->date_needed = date('Y-m-d', strtotime($request->date_needed));
    	$billing->mobilization = $request->mobilization;
    	$billing->url = $request->url;


        $billing->approval_sequence = 3;

        $billing->farm_manager_id = $farm_manager->id; # new
        $billing->farm_divhead_id = $farm_div_head->id; # new

    	if($billing->save()) {
    		return redirect()->route('user.billing')->with('success', 'Billing Successfully Created!');
    	}

    	return redirect()->route('user.billing')->with('error', 'Error Occured. Please Try Again!');
    }




    // Archived Billing
    public function archivedBilling()
    {
    	return view('user.archived-billing');
    }



    // all billing
    public function all(Request $request)
    {
        if($request->ajax()) {
            $billing = Billing::where('user_id', Auth::user()->id)
                        ->where('archived', 0)
                        ->get();

            $data = collect();
            if(count($billing) > 0) {
                foreach($billing as $w) {
                    $data->push([
                        'ref' => $w->reference_number,
                        'project_name' => $w->project_name,
                        'date_of_request' => date('F j, Y', strtotime($w->date_of_request)),
                        'actual_date_filed' => date('F j, Y', strtotime($w->created_at)),
                        'action' => GC::billingRequestorAction($w->approval_sequence, $w->id, $w->reference_number, $w->cancelled, $w->disapproved), 
                    ]);
                }
            }
            return DataTables::of($data)
                    ->rawColumns(['action'])
                    ->make(true);

        }
    }


}
