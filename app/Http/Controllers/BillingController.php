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
}
