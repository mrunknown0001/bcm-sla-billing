@extends("layouts.app")

@section("title") Billing View @endsection

@section("styles")

@endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Billing Details @endsection

@section("sidebar")
  @include("user.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.billing-view-details-common')
      <hr>
      <p>Status: {!! App\Http\Controllers\GeneralController::viewWroStatus($billing->approval_sequence, $billing->cancelled, $billing->disapproved) !!}</p>
      @include('includes.common.billing-approvals')
      @if($billing->cancelled != 1 && $billing->approval_sequence == 3 && $billing->disapproved != 1)
        <button id="cancel" data-id="{{ $billing->id }}" data-text="Do you want to cancel Billing {{ $billing->reference_number }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Cancel</button>
      @elseif($billing->approved == 1 || $billing->disapproved == 1)
        @if($billing->archived == 0)
          <button id="archive" data-id="{{ $billing->id }}" data-text="Do you want to archive Billing {{ $billing->reference_number }}?" class="btn btn-primary btn-xl"><i class="pe-7s-close-portfolio"></i> Archive</button>
        @endif
      @else
        @if($billing->archived == 0)
          <button id="archive" data-id="{{ $billing->id }}" data-text="Do you want to archive Billing {{ $billing->reference_number }}?" class="btn btn-primary btn-xl"><i class="pe-7s-close-portfolio"></i> Archive</button>
        @endif
        @if($billing->cancelled == 1)
          <p>Cancelled By: {{ App\Http\Controllers\GeneralController::getName($billing->user_id) }}</p>
          <p>Cancelled On: {{ date('F j, Y H:i:s', strtotime($billing->cancelled_on)) }}</p>
          <p>Reason: <i>{{ $billing->reason }}</i></p>
          <hr>
        @endif
      @endif
      <br><br>
    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script>
  $(document).on('click', '#cancel', function (e) {
      e.preventDefault();
      var id = $(this).data('id');
      var text = $(this).data('text');
      Swal.fire({
        title: 'Cancel Billing?',
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
            url: "/u/billing/cancel/" + id + "/" + result.value,
            type: "GET",
            success: function() {
              window.location.reload(); 
            },
            error: function(error) {
              console.log(error)
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

  $(document).on('click', '#archive', function (e) {
      e.preventDefault();
      var id = $(this).data('id');
      var text = $(this).data('text');
      Swal.fire({
        title: 'Archive Billing?',
        text: text,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continue',
      }).then((result) => {
        if (result.value) {

          $.ajax({
            url: "/u/billing/archive/" + id,
            type: "GET",
            success: function() {
              Swal.fire({
                title: 'Billing Archived.',
                text: "",
                type: 'success',
                showCancelButton: false,
                confirmButtonColor: '#3085d6',
                cancelButtonColor: '#d33',
                confirmButtonText: 'Close'
              }).then((result) => {
                window.location.reload(); 
              });
            },
            error: function(error) {
              console.log(error)
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