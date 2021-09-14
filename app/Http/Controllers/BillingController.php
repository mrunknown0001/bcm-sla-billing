<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\WorkOrder;

class BillingController extends Controller
{
    // Create Billing
    public function billing()
    {
    	$sla = WorkOrder::select('wr_no','id')->get();
    	// chekc sla if already created for approval or approved or cancelled
    	return view('user.billing', ['sla' => $sla]);
    }


    // Post Create Billing
    public function postBilling(Request $request)
    {
    	return $request;
    }




    // Archived Billing
    public function archivedBilling()
    {
    	return view('user.archived-billing');
    }
}
