@extends("layouts.app")

@section("title") Billing View @endsection

@section("styles")

@endsection

@section("header")
  @include("manager.includes.header")
@endsection

@section("page-title") Billing Details @endsection

@section("sidebar")
  @include("manager.includes.sidebar")
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
      @if($billing->cancelled != 1 && $billing->approval_sequence == 3 && $billing->disapproved != 1)
        <button id="approve" data-id="{{ $billing->id }}" data-text="Do you want to approve Billing {{ $billing->reference_number }}?" class="btn btn-success btn-xl"><i class="pe-7s-close-circle"></i> Approve</button>
        <button id="disapprove" data-id="{{ $billing->id }}" data-text="Do you want to disapprove Billing {{ $billing->reference_number }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
      @elseif($billing->cancelled != 1 && $billing->approval_sequence == 3 && $billing->disapproved != 1)
        @if(App\Http\Controllers\GeneralController::checkBcmManager(Auth::user()->id))
          <button id="approve_bcm_mgr" data-id="{{ $billing->id }}" data-text="Do you want to approve Billing {{ $billing->reference_number }}?" class="btn btn-success btn-xl"><i class="pe-7s-close-circle"></i> Approve</button>
          <button id="disapprove_bcm_mgr" data-id="{{ $billing->id }}" data-text="Do you want to disapprove Billing {{ $billing->reference_number }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
        @endif
      @elseif($billing->cancelled != 1 && $billing->approval_sequence == 7 && $billing->disapproved != 1)
        @if(App\Http\Controllers\GeneralController::checkTreasuryManager(Auth::user()->id))
          <button id="approve_trsry_mgr" data-id="{{ $billing->id }}" data-text="Do you want to approve Billing {{ $billing->reference_number }}?" class="btn btn-success btn-xl"><i class="pe-7s-close-circle"></i> Approve</button>
          <button id="disapprove_trsry_mgr" data-id="{{ $billing->id }}" data-text="Do you want to disapprove Billing {{ $billing->reference_number }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
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
            url: "/manager/billing/approval/" + id,
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
            url: "/manager/billing/disapproval/" + id + "/" + result.value,
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

  $(document).on('click', '#approve_bcm_mgr', function (e) {
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
            url: "/manager/billing/bcm/manager/approval/" + id,
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

  $(document).on('click', '#disapprove_bcm_mgr', function (e) {
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
            url: "/manager/billing/bcm/manager/disapproval/" + id + "/" + result.value,
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


  $(document).on('click', '#approve_trsry_mgr', function (e) {
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
            url: "/manager/billing/trsry/manager/approval/" + id,
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

  $(document).on('click', '#disapprove_trsry_mgr', function (e) {
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
            url: "/manager/billing/trsry/manager/disapproval/" + id + "/" + result.value,
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