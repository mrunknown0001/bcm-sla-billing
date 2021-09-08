@if($wro->approval_sequence >= 1 && $wro->manager_approval == 1)
	<hr>
	<p>Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->manager_id) }}</strong></p>
	<p>Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->manager_approved)) }}</strong></p>
	<hr>
	@if($wro->approval_sequence >= 2 && $wro->div_head_approval == 1)
		<p>Division Head: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->div_head_id) }}</strong></p>
		<p>Division Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->div_head_approved)) }}</strong></p>
		<hr>
		@if($wro->approval_sequence >= 3 && $wro->bcm_manager_approval == 1)
			<p>BCM Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->bcm_manager_id) }}</strong></p>
			<p>BCM Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->bcm_manager_approved )) }}</strong></p>
			<hr>
			@if($wro->approval_sequence >= 4 && $wro->gen_serv_div_head_approval == 1)
				<p>General Services Division Head: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->gen_serv_div_head_id) }}</strong></p>
				<p>Gen. Services Div Head Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->gen_serv_div_head_approved)) }}</strong></p>
				<hr>
				@if($wro->approval_sequence >= 5 && $wro->treasury_manager_approval == 1)
					<p>Treasury Manager: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->treasury_manager_id) }}</strong></p>
					<p>Treasury Manager Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->treasury_manager_approved)) }}</strong></p>
					<hr>
					@if($wro->approval_sequence >= 6 && $wro->coo_approval == 1)
						<p>COO: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->coo_id) }}</strong></p>
						<p>COO Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->coo_approved)) }}</strong></p>
						<hr>

						@if($wro->approval_sequence >= 7 && $wro->vp_gen_serv_approval == 1)
							<p>VP - General Services: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->vp_gen_serv_id) }}</strong></p>
							<p>VP - General Services Approval Timestamp: <strong>{{ date('F j, Y h:i A', strtotime($wro->vp_gen_serv_approved)) }}</strong></p>
							<hr>
						@endif
					@endif
				@endif
			@endif
		@endif
	@endif
@endif