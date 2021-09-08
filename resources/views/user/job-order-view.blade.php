@extends("layouts.app")

@section("title") Job Order View @endsection

@section("styles")

@endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Job Order Details @endsection

@section("sidebar")
  @include("user.includes.sidebar")
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
      @if($jo->status == 1)
        <hr>
        <button id="cancel" data-id="{{ $jo->id }}" data-text="Do you want to cancel Job Order {{ $jo->jo_no }}?" class="btn btn-danger btn-xl"><i class="pe-7s-close-circle"></i> Cancel</button>
      @elseif($jo->status == 2 || $jo->status == 6)
        <hr>
        <a href="{{ route('user.jo.pdf.download', ['id' => $jo->id]) }}" class="btn btn-primary"><i class="pe-7s-download"></i> Download PDF</a> 
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
        title: 'Cancel Job Order?',
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
            url: "/u/job-order/cancel/" + id + "/" + result.value,
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
</script>
@endsection