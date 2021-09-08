      @if($jo->status == 1)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
      @elseif($jo->status == 2)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Approved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</strong></p>
        <p>Approved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->manager_approved)) }}</strong></p>
	    @elseif($jo->status == 3)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Cancelled by: <strong>{{ ucfirst($jo->user->first_name) . ' ' . ucfirst($jo->user->last_name) }}</strong></p>
        <p>Cancelled on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->cancelled_on)) }}</strong></p>
        <p>Reason: <i>{{ $jo->reason }}</i></p>
      @elseif($jo->status == 4)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Disapproved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</strong></p>
        <p>Disapproved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->disapproved_on)) }}</strong></p>
        <p>Reason: <i>{{ $jo->reason }}</i></p>
      @elseif($jo->status == 5)
      	<p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
      	<hr>
        <p>Approved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</strong></p>
        <p>Approved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->manager_approved)) }}</strong></p>
      @elseif($jo->status == 6)
      	<p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
      	<hr>
        <p>Approved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</strong></p>
        <p>Approved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->manager_approved)) }}</strong></p>

        <hr>

        <p>Approved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->vp_id) }}</strong></p>
        <p>Approved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->vp_approved)) }}</strong></p>
      @elseif($jo->status == 7)
      	<p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
      	<hr>
        <p>Approved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</strong></p>
        <p>Approved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->manager_approved)) }}</strong></p>
        <hr>
        <p>Disapproved by: <strong>{{ App\Http\Controllers\GeneralController::getName($jo->vp_id) }}</strong></p>
        <p>Disapproved on: <strong>{{ date('F j, Y H:m:i', strtotime($jo->disapproved_on)) }}</strong></p>
        <p>Reason: <i>{{ $jo->reason }}</i></p>
      @endif