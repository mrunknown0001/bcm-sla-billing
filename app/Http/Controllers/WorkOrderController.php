<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Response;
use App\WorkOrder as Wo;
use App\User;
use App\WroApproval;

class WorkOrderController extends Controller
{
    public function downloadWro($id)
    {
    	$wo = Wo::findorfail($id);

        $file = public_path()."/uploads/wro/" . $wo->attachment;
        $headers = array('Content-Type: application/pdf',);

        $filename = 'Attachment-' . $wo->attachment;

        return Response::download($file, $filename, $headers);
    }




    public function wroSetupApprover($code)
    {
    	$managers = User::where('user_type', 4)->where('active', 1)->get();
    	$divhead = User::where('user_type', 3)->where('active', 1)->get();
    	$vp = User::where('user_type', 2)->where('active', 1)->get();
    	$co = User::where('user_type', 1)->where('active', 1)->get();

    	return view('admin.wro-setup-form', [
	    		'managers' => $managers,
	    		'divhead' => $divhead,
	    		'vp' => $vp,
	    		'co' => $co, 
	    		'code' => $code
	    	]);
    }


    public function postWroSetupApprover(Request $request)
    {
    	$w = WroApproval::find(1);

    	if($request->bcm_manager != NULL) {
	    	$w->bcm_manager = $request->bcm_manager;
    	}
    	if($request->gsdivhead != NULL) {
	    	$w->gen_serv_div_head = $request->gsdivhead;
    	}
    	if($request->treasury_manager != NULL) {
	    	$w->treasury_manager = $request->treasury_manager;
    	}
    	if($request->coo != NULL) {
	    	$w->coo = $request->coo;
    	}
    	if($request->vp != NULL) {
	    	$w->vp_gen_serv = $request->vp;
    	}

    	if($w->save()) {
    		return redirect()->route('admin.wro.setup')->with('success', 'Approver Updated!');
    	}

    	return redirect()->route('admin.wro.setup')->with('error', 'Error! Please Try Again');
    }
}
