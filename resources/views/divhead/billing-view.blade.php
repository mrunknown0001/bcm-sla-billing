@extends("layouts.app")

@section("title") Billing @endsection

@section("header")
  @include("divhead.includes.header")
@endsection

@section("page-title") Billing @endsection

@section("sidebar")
  @include("divhead.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.billing-view-details-common')
      <hr>
      {{-- Status --}}
      <p>Status: {!! App\Http\Controllers\GeneralController::viewWroStatus($billing->approval_sequence, $billing->cancelled, $billing->disapproved) !!}</p>
      @include('includes.common.billing-approvals')
      @if($billing->cancelled != 1 && $billing->approval_sequence == 6 && $billing->disapproved != 1)
        <button id="approve" data-id="{{ $billing->id }}" data-text="Do you want to approve Billing {{ $billing->wr_no }}?" class="btn btn-success btn-xl"><i class="pe-7s-check"></i> Approve</button>
        <button id="disapprove" data-id="{{ $billing->id }}" data-text="Do you want to disapprove Billing {{ $billing->wr_no }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
      @elseif($billing->cancelled != 1 && $billing->approval_sequence == 4 && $billing->disapproved != 1)
	      @if(App\Http\Controllers\GeneralController::checkGsDivHead(Auth::user()->id))
	        <button id="approve_gs_div_head" data-id="{{ $billing->id }}" data-text="Do you want to approve Billing {{ $billing->reference_number }}?" class="btn btn-success btn-xl"><i class="pe-7s-check"></i> Approve</button>
	        <button id="disapprove_gs_div_head" data-id="{{ $billing->id }}" data-text="Do you want to disapprove Billing {{ $billing->reference_number }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
	      @endif
      @else
        @if($billing->cancelled == 1)
          <p>Cancelled By: <strong>{{ App\Http\Controllers\GeneralController::getName($billing->user_id) }}</strong></p>
          <p>Cancelled On: <strong>{{ date('F j, Y H:i:s', strtotime($billing->cancelled_on)) }}</strong></p>
          <p>Reason: <i>{{ $billing->reason }}</i></p>
          <hr>
        @endif
      @endif
      <br><br>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')
	<script src="{{ asset('js/sweetalert.js') }}"></script>
	<script>
	  $(document).on('click', '#approve', function (e) {
	      e.preventDefault();
	      var id = $(this).data('id');
	      var text = $(this).data('text');
	      Swal.fire({
	        title: 'Approve Billing?',
	        text: text,
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Continue',
	      }).then((result) => {
	        if (result.value) {
	          $.ajax({
	            url: "/div-head/billing/approval/" + id,
	            type: "GET",
	            success: function(value) {
	              window.location.reload(); 
	            },
	            error: function(error) {
	              Swal.fire({
	                title: 'Error Occured! Tray Again Later.',
	                text: "",
	                type: 'error',
	                showCancelButton: false,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Close'
	              });
	            }
	          });
	        }
	        else {
	          Swal.fire({
	            title: 'Action Cancelled',
	            text: "",
	            type: 'info',
	            showCancelButton: false,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Close'
	          });
	        }
	      });
	  });

	  $(document).on('click', '#disapprove', function (e) {
	      e.preventDefault();
	      var id = $(this).data('id');
	      var text = $(this).data('text');
	      Swal.fire({
	        title: 'Disapprove Billing?',
	        text: text,
	        type: 'question',
	        input: 'text',
	        inputPlaceholder: 'Comment Here...',
	        inputValidator: (value) => {
	          return !value && 'Please leave a comment!'
	        },
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Continue',
	      }).then((result) => {
	        if (result.value) {
	          $.ajax({
	            url: "/div-head/billing/disapproval/" + id + "/" + result.value,
	            type: "GET",
	            success: function() {
	              window.location.reload(); 
	            },
	            error: function(error) {
	              Swal.fire({
	                title: 'Error Occured! Tray Again Later.',
	                text: "",
	                type: 'error',
	                showCancelButton: false,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Close'
	              });
	            }
	          });
	        }
	        else {
	          Swal.fire({
	            title: 'Action Cancelled',
	            text: "",
	            type: 'info',
	            showCancelButton: false,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Close'
	          });
	        }
	      });
	  });

	  $(document).on('click', '#approve_gs_div_head', function (e) {
	      e.preventDefault();
	      var id = $(this).data('id');
	      var text = $(this).data('text');
	      Swal.fire({
	        title: 'Approve Billing?',
	        text: text,
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Continue',
	      }).then((result) => {
	        if (result.value) {
	          $.ajax({
	            url: "/div-head/gs/billing/approval/" + id,
	            type: "GET",
	            success: function(value) {
	              window.location.reload(); 
	            },
	            error: function(error) {
	              Swal.fire({
	                title: 'Error Occured! Tray Again Later.',
	                text: "",
	                type: 'error',
	                showCancelButton: false,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Close'
	              });
	            }
	          });
	        }
	        else {
	          Swal.fire({
	            title: 'Action Cancelled',
	            text: "",
	            type: 'info',
	            showCancelButton: false,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Close'
	          });
	        }
	      });
	  });

	  $(document).on('click', '#disapprove_gs_div_head', function (e) {
	      e.preventDefault();
	      var id = $(this).data('id');
	      var text = $(this).data('text');
	      Swal.fire({
	        title: 'Disapprove Billing?',
	        text: text,
	        type: 'question',
	        input: 'text',
	        inputPlaceholder: 'Comment Here...',
	        inputValidator: (value) => {
	          return !value && 'Please leave a comment!'
	        },
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Continue',
	      }).then((result) => {
	        if (result.value) {
	          $.ajax({
	            url: "/div-head/gs/billing/disapproval/" + id + "/" + result.value,
	            type: "GET",
	            success: function() {
	              window.location.reload(); 
	            },
	            error: function(error) {
	              Swal.fire({
	                title: 'Error Occured! Tray Again Later.',
	                text: "",
	                type: 'error',
	                showCancelButton: false,
	                confirmButtonColor: '#3085d6',
	                cancelButtonColor: '#d33',
	                confirmButtonText: 'Close'
	              });
	            }
	          });
	        }
	        else {
	          Swal.fire({
	            title: 'Action Cancelled',
	            text: "",
	            type: 'info',
	            showCancelButton: false,
	            confirmButtonColor: '#3085d6',
	            cancelButtonColor: '#d33',
	            confirmButtonText: 'Close'
	          });
	        }
	      });
	  });
	</script>
@endsection