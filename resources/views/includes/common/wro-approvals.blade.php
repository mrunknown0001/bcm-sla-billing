
@if($wro->approval_sequence >= 3 && $wro->bcm_manager_approval == 1)
	<p>BCM Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->bcm_manager_id) }}</strong></p>
	<p>BCM Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->bcm_manager_approved )) }}</strong></p>
	<hr>
	@if($wro->approval_sequence >= 4 && $wro->gen_serv_div_head_approval == 1)
		<p>General Services Division Head: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->gen_serv_div_head_id) }}</strong></p>
		<p>Gen. Services Div Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->gen_serv_div_head_approved)) }}</strong></p>
		<hr>
		@if($wro->approval_sequence >= 5 && $wro->farm_manager_approval == 1)
			<p>Farm Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->farm_manager_id) }}</strong></p>
			<p>Farm Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->farm_manager_approved)) }}</strong></p>
			<hr>
			@if($wro->approval_sequence >= 6 && $wro->farm_divhead_approval == 1)
				<p>Farm Div Head: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->farm_divhead_id) }}</strong></p>
				<p>Farm Div Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->farm_divhead_approved)) }}</strong></p>
				<hr>

				@if($wro->approval_sequence >= 7 && $wro->treasury_manager_approval == 1)
					<p>Treasury Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->treasury_manager_id) }}</strong></p>
					<p>Treasury Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->treasury_manager_approved)) }}</strong></p>
					<hr>

					@if($wro->approval_sequence >= 8 && $wro->vp_gen_serv_approval == 1)
						<p>VP on Gen Services: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->vp_gen_serv_id) }}</strong></p>
						<p>VP on Gen Services Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->vp_gen_serv_approved)) }}</strong></p>
						<hr>
					@endif
				@endif
			@endif
		@endif
	@endif
@endif

@if($wro->disapproved ==1)
	@if($wro->bcm_manager_approval == 0)
		<p>BCM Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->disapproved_by) }}</strong></p>
		<p>BCM Manager Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->disapproved_on )) }}</strong></p>
		<p>Reason: <i>{{ $wro->reason }}</i></p>
		<hr>
	@elseif($wro->gen_serv_div_head_approval == 0)
		<p>Gen. Serv. - Div Head: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->disapproved_by) }}</strong></p>
		<p>Gen. Serv. - Div Head Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $wro->reason }}</i></p>
		<hr>
	@elseif($wro->treasury_manager_approval == 0)
		<p>Treasury Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->disapproved_by) }}</strong></p>
		<p>Treasury Manager Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $wro->reason }}</i></p>
		<hr>
	@elseif($wro->coo_approval == 0)
		<p>COO: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->disapproved_by) }}</strong></p>
		<p>COO Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->disapproved_on )) }}</strong></p>
		<p>Reason: <i>{{ $wro->reason }}</i></p>
		<hr>
	@elseif($wro->vp_gen_serv_approval == 0)
		<p>VP on Gen. Serv.: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->disapproved_by) }}</strong></p>
		<p>VP on Gen. Serv. Disapproval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->disapproved_on)) }}</strong></p>
		<p>Reason: <i>{{ $wro->reason }}</i></p>
		<hr>
	@endif
@endif