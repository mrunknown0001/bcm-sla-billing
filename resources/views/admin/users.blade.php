@extends("layouts.app")

@section("title") Users @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Users <a href="{{ route('admin.add.user') }}">+</a> @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
    	@include('includes.success')
    	@include('includes.error')
		  <div class="table-responsive">
		    <table id="users" class="table table-striped table-bordered compact hover" style="width: 99%">
		      <thead>
		        <tr>
		          <th>Name</th>
		          <th>Email</th>
		          <th>Approval Setup</th>
		          <th>Status</th>
		          <th>Action</th>
		        </tr>
		      </thead>
		    </table>
		  </div>
    </div>
  </div>
@endsection

@section('styles')
	<link rel="stylesheet" type="text/css" href="{{ asset('css/datatables.min.css') }}">
@endsection

@section('scripts')
	<script src="{{ asset('js/datatables.js') }}"></script>
	<script src="{{ asset('js/sweetalert.js') }}"></script>
	<script>
	  $(document).ready(function() {
    $('#users').DataTable({
      ajax: { 
        url: "{{ route('admin.all.users') }}",
        dataSrc: ""
      },
      columns: [
        { data: 'name' },
        { data: 'email' },
        { data: 'setup' },
        { data: 'status' },
        { data: 'action'},
	    ]
	    });
	  });

    $(document).on('click', '#remove', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Remove User?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#d33',
          cancelButtonColor: '#808080',
          confirmButtonText: 'Remove'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/chiefofficer/wro/disapproval/" + id + "/" + result.value,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Disapprove Successfull!',
                  text: "",
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Close'
                });
                var table = $('#wro').DataTable();
                table.ajax.reload();
              },
              error: function(err) {
                console.log(err)
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