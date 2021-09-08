<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Mail;
use App\MailSetting;

class MailController extends Controller
{
	private static $status = 0;

	public function __construct()
	{
		$s = MailSetting::find(1);
		if(!empty($s)) {
			static::$status = $s->status;
		}
	}


	public function setup(Request $request)
	{
		$ms = MailSetting::find(1);

		if(empty($ms)) {
			$ms = new MailSetting();
		}

		if($request->mail_switch == 'on') {
			$ms->status = 1;
			$ms->save();
		}
		else {
			$ms->status = 0;
			$ms->save();
		}

		return redirect()->route('admin.module')->with('success', 'Mail Setting Updated!');
	}
    
    public static function sendVerificationCode($code, $email, $name, $link)
    {
    	$data = array(
    		"code" => $code,
    		"name" => $name,
    		"link" => $link
    	);

    	$subject = "Brookside Job and Work Request Order - Account Password Reset";

    	if(static::$status == 1) {
	    	Mail::send("emails.password-reset", $data, function($message) use ($name, $email, $subject) {
				$message->to($email, $name)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
	    }

    }

    public static function joManagerApproval($approver, $approver_email, $requestor, $requestor_designation, $jo_view_route, $jo_id, $jo_no)
    {

		// $approver = "Jeff Montiano"; # name of manager - important
		// $approver_email = "jmontiano@bfcgroup.org"; # email of receiver - important
		// $requestor = "Adam Trinidad"; # name of requestor - important
		// $requestor_designation = 'IT Supervisor';

		// $jo_view_route = "manager.view.job.order"; # manager 
		// $jo_id = 1;
		// $jo_no = "JO-BFC-0621-0001"

		$data = array(
			"approver" => $approver,
			"route" => $jo_view_route,
			"jo_id" => $jo_id,
			"jo_no" => $jo_no,
			"requestor" => $requestor,
			"requestor_designation" => $requestor_designation,
		);

		$subject = "[FOR APPROVAL] " . $jo_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.jo_manager_approval", $data, function($message) use ($approver, $approver_email, $subject) {
				$message->to($approver_email, $approver)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }



    public static function joApproved($approver, $approver_designation, $requestor, $requestor_email, $jo_view_route, $jo_id, $jo_no)
    {
    	// $approver 
    	// $approver_designation
    	// $requestor
    	// $requestor_email
    	// $jo_view_route
    	// $jo_id
    	// $jo_no

		$data = array(
			"approver" => $approver,
			"approver_designation" => $approver_designation,
			"route" => $jo_view_route,
			"jo_id" => $jo_id,
			"jo_no" => $jo_no,
			"requestor" => $requestor,
			"requestor_email" => $requestor_email,
		);

		$subject = "[APPROVED] " . $jo_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.jo_approved", $data, function($message) use ($requestor, $requestor_email, $subject) {
				$message->to($requestor_email, $requestor)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }



    public static function joDisapproved($approver, $approver_designation, $requestor, $requestor_email, $jo_view_route, $jo_id, $jo_no)
    {
    	// $approver 
    	// $approver_designation
    	// $requestor
    	// $requestor_email
    	// $jo_view_route
    	// $jo_id
    	// $jo_no

		$data = array(
			"approver" => $approver,
			"approver_designation" => $approver_designation,
			"route" => $jo_view_route,
			"jo_id" => $jo_id,
			"jo_no" => $jo_no,
			"requestor" => $requestor,
			"requestor_email" => $requestor_email,
		);

		$subject = "[DISAPPROVED] " . $jo_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.jo_disapproved", $data, function($message) use ($requestor, $requestor_email, $subject) {
				$message->to($requestor_email, $requestor)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }



    public static function wroManagerApproval($approver, $approver_email, $requestor, $requestor_designation, $wro_view_route, $wro_id, $wro_no)
    {

		$data = array(
			"approver" => $approver,
			"route" => $wro_view_route,
			"wro_id" => $wro_id,
			"wro_no" => $wro_no,
			"requestor" => $requestor,
			"requestor_designation" => $requestor_designation,
		);

		// $approver_email = 'm.trinidad@bfcgroup.org';

		$subject = "[FOR APPROVAL] " . $wro_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.wro_manager_approval", $data, function($message) use ($approver, $approver_email, $subject) {
				$message->to($approver_email, $approver)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }


    public static function wrNextApproval($next_approver, $next_approver_email, $prev_approver, $prev_approver_designation, $wro_view_route, $wro_id, $wro_no)
    {

		$data = array(
			"next_approver" => $next_approver,
			"route" => $wro_view_route,
			"wro_id" => $wro_id,
			"wro_no" => $wro_no,
			"prev_approver" => $prev_approver,
			"prev_approver_designation" => $prev_approver_designation,
		);

		// $next_approver_email = 'm.trinidad@bfcgroup.org';

		$subject = "[FOR APPROVAL] " . $wro_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.wro_next_approval", $data, function($message) use ($next_approver, $next_approver_email, $subject) {
				$message->to($next_approver_email, $next_approver)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }




     public static function wroDisapproved($approver, $approver_designation, $requestor, $requestor_email, $wro_view_route, $wro_id, $wro_no)
    {

		$data = array(
			"approver" => $approver,
			"approver_designation" => $approver_designation,
			"route" => $wro_view_route,
			"wro_id" => $wro_id,
			"wro_no" => $wro_no,
			"requestor" => $requestor,
			"requestor_email" => $requestor_email,
		);

		// $requestor_email = 'm.trinidad@bfcgroup.org';

		$subject = "[DISAPPROVED] " . $wro_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.wro_disapproved", $data, function($message) use ($requestor, $requestor_email, $subject, $wro_no) {
				$message->to($requestor_email, $requestor)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }



    public static function wroApproved($approver, $approver_designation, $receivers, $wro_no)
    {

		$data = array(
			"approver" => $approver,
			"approver_designation" => $approver_designation,
			"wro_no" => $wro_no

		);

		$subject = "[APPROVED] " . $wro_no; // subject of email Static

    	if(static::$status == 1) {
			Mail::send("emails.wro_approved_all", $data, function($message) use ($receivers, $subject) {
				$message->to($receivers)->subject($subject);
				$message->from("no-reply@bfcgroup.ph", "Brookside JWO System");
			});
		}
    }


}
