@extends("layouts.app")

@section("title") Job Order View @endsection

@section("styles")

@endsection

@section("header")
  @include("vp.includes.header")
@endsection

@section("page-title") Job Order Details @endsection

@section("sidebar")
  @include("vp.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.jo-view-details-common')
      <hr>
      @include('includes.common.jo_status')
      @if($jo->status == 5)
        <hr>
        <button id="approve" data-id="{{ $jo->id }}" data-text="Do you want to approve Job Order {{ $jo->jo_no }}?" class="btn btn-success btn-xl"><i class="pe-7s-check"></i> Approve</button>
        <button id="disapprove" data-id="{{ $jo->id }}" data-text="Do you want to disapprove Job Order {{ $jo->jo_no }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Disapprove</button>
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
        title: 'Approve Job Order?',
        text: text,
        type: 'question',
        showCancelButton: true,
        confirmButtonColor: '#3085d6',
        cancelButtonColor: '#d33',
        confirmButtonText: 'Continue',
      }).then((result) => {
        if (result.value) {
          $.ajax({
            url: "/vp/jo/approval/" + id,
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

  $(document).on('click', '#disapprove', function (e) {
      e.preventDefault();
      var id = $(this).data('id');
      var text = $(this).data('text');
      Swal.fire({
        title: 'Disapprove Job Order?',
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
            url: "/vp/jo/disapprove/" + id + "/" + result.value,
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