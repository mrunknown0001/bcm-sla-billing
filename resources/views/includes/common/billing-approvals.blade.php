
@if($billing->approval_sequence >= 3 && $billing->bcm_manager_approval == 1)
	<p>Checked By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->bcm_manager_id) }}</strong></p>
	<p>BCM Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->bcm_manager_approved )) }}</strong></p>
	<hr>
	@if($billing->approval_sequence >= 4 && $billing->gen_serv_div_head_approval == 1)
		<p>Verified By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->gen_serv_div_head_id) }}</strong></p>
		<p>Gen. Services Div Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->gen_serv_div_head_approved)) }}</strong></p>
		<hr>
	@endif
	@if($billing->approval_sequence >= 5 && $billing->farm_manager_approval == 1)
		<p>Noted By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->farm_manager_id) }}</strong></p>
		<p>Farm Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->farm_manager_approved)) }}</strong></p>
		<hr>
	@endif
		@if($billing->approval_sequence >= 6 && $billing->farm_divhead_approval == 1)
			<p>Recommending Approval: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->farm_divhead_id) }}</strong></p>
			<p>Farm Div Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->farm_divhead_approved)) }}</strong></p>
			<hr>

		@endif
		@if($billing->approval_sequence >= 7 && $billing->treasury_manager_approval == 1)
			<p>Approved By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->treasury_manager_id) }}</strong></p>
			<p>Treasury Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->treasury_manager_approved)) }}</strong></p>
			<hr>

		@endif
		@if($billing->approval_sequence >= 8 && $billing->vp_gen_serv_approval == 1)
			<p>Approved By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->vp_gen_serv_id) }}</strong></p>
			<p>VP on Gen Services Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->vp_gen_serv_approved)) }}</strong></p>
			<hr>
		@endif
@endif

@if($billing->disapproved ==1)
	@if($billing->bcm_manager_approval == 0)
		<p>BCM Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->disapproved_by) }}</strong></p>
		<p>BCM Manager Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->disapproved_on )) }}</strong></p>
		<p>Reason: <i>{{ $billing->reason }}</i></p>
		<hr>
	@elseif($billing->gen_serv_div_head_approval == 0)
		<p>Gen. Serv. - Div Head: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->disapproved_by) }}</strong></p>
		<p>Gen. Serv. - Div Head Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $billing->reason }}</i></p>
		<hr>
	@elseif($billing->treasury_manager_approval == 0)
		<p>Treasury Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->disapproved_by) }}</strong></p>
		<p>Treasury Manager Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $billing->reason }}</i></p>
		<hr>
	@elseif($billing->coo_approval == 0)
		<p>COO: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->disapproved_by) }}</strong></p>
		<p>COO Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->disapproved_on )) }}</strong></p>
		<p>Reason: <i>{{ $billing->reason }}</i></p>
		<hr>
	@elseif($billing->vp_gen_serv_approval == 0)
		<p>VP on Gen. Serv.: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->disapproved_by) }}</strong></p>
		<p>VP on Gen. Serv. Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($billing->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $billing->reason }}</i></p>
		<hr>
	@endif
@endif