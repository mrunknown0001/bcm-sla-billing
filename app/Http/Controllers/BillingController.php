<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class BillingController extends Controller
{
    // Create Billing
    public function billing()
    {
    	return view('user.billing');
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
