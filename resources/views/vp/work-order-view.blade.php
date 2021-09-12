@extends("layouts.app")

@section("title") SLA View @endsection

@section("header")
  @include("vp.includes.header")
@endsection

@section("page-title") SLA View @endsection

@section("sidebar")
  @include("vp.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.wro-view-details-common')
      <hr>
      {{-- Status --}}
      <p>Status: {!! App\Http\Controllers\GeneralController::viewWroStatus($wro->approval_sequence, $wro->cancelled, $wro->disapproved) !!}</p>
      @include('includes.common.wro-approvals')
      @if($wro->cancelled != 1 && $wro->approval_sequence == 8 && $wro->disapproved != 1)
        <button id="approve" data-id="{{ $wro->id }}" data-text="Do you want to approve SLA {{ $wro->wr_no }}?" class="btn btn-success btn-xl"><i class="pe-7s-close-circle"></i> Approve</button>
        <button id="disapprove" data-id="{{ $wro->id }}" data-text="Do you want to disapprove SLA {{ $wro->wr_no }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
      @else
        @if($wro->cancelled == 1)
          <p>Cancelled By: <strong>{{ App\Http\Controllers\GeneralController::getName($wro->user_id) }}</strong></p>
          <p>Cancelled On: <strong>{{ date('F j, Y H:i:s', strtotime($wro->cancelled_on)) }}</strong></p>
          <p>Reason: <i>{{ $wro->reason }}</i></p>
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
	        title: 'Approve SLA?',
	        text: text,
	        type: 'question',
	        showCancelButton: true,
	        confirmButtonColor: '#3085d6',
	        cancelButtonColor: '#d33',
	        confirmButtonText: 'Continue',
	      }).then((result) => {
	        if (result.value) {
	          $.ajax({
	            url: "/vp/sla/approval/" + id,
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
	        title: 'Disapprove SLA?',
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
	            url: "/vp/sla/disapproval/" + id + "/" + result.value,
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