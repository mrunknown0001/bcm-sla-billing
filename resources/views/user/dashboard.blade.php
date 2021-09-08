@extends("layouts.app")

@section("title") User Dashboard @endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") User Dashboard @endsection

@section("sidebar")
  @include("user.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
  <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      @include('includes.notice')

      <h4>Job Order Lists</h4>
      <table id="jo" class="table cell-border compact stripe hover" width="99%">
	      <thead>
          <tr>
            <th scope="col">JO #</th>
            <th scope="col">Status</th>
            <th scope="col">Date of Request</th>
            <th scope="col">Actual Date Filed</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
      </table>
      <hr>
      @if(\App\Http\Controllers\GeneralController::getDeptCode(Auth::user()->dept_id) == 'BCM')
      <h4>Work Request Order Lists</h4>
        <table id="wro" class="display table cell-border compact stripe hover compact" width="99%">
          <thead>
            <tr>
              <th scope="col">WRO #</th>
              <th scope="col">Status</th>
              <th scope="col">Date of Request</th>
              <th scope="col">Actual Date Filed</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
        </table>
        <hr>
      @endif
    </div>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
@endsection

@section('scripts')
  <script src="{{ asset('js/datatables.js') }}"></script>
  <script src="{{ asset('js/sweetalert.js') }}"></script>

  <script>
    $(document).ready(function() {
      // $('#jo').DataTable({
      //   pageLength: 5,
      //   columnDefs: [
      //      { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
      //   ],
      //   ajax: { 
      //     url: "{{ route('user.all.jo') }}",
      //     dataSrc: ""
      //   },
      //   columns: [
      //     { data: 'jo' },
      //     { data: 'status'},
      //     { data: 'date_of_request' },
      //     { data: 'actual_date_filed' },
      //     { data: 'action' },
      // ]
      // });

      let jotable = $('#jo').DataTable({
        processing: true,
        serverSide: true,
        columnDefs: [
          { className: "dt-center", targets: [ 1, 2, 3, 4 ] }
        ],
        ajax: "{{ route('user.all.jo') }}",
        columns: [
            {data: 'jo', name: 'jo' },
            {data: 'status', name: 'status', searchable: false},
            {data: 'date_of_request', name: 'date_of_request'},
            {data: 'actual_date_filed', name: 'actual_date_filed'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });

      // $('#wro').DataTable({
      //   pageLength: 5,
      //   columnDefs: [
      //      { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
      //   ],
      //   scrollX: true,
      //   ajax: { 
      //     url: "{{ route('user.all.wro') }}",
      //     dataSrc: ""
      //   },
      //   columns: [
      //     { data: 'wro' },
      //     { data: 'status'},
      //     { data: 'date_of_request' },
      //     { data: 'actual_date_filed' },
      //     { data: 'action' },
      // ]
      // });

      let wrotable = $('#wro').DataTable({
        processing: true,
        serverSide: true,
        columnDefs: [
          { className: "dt-center", targets: [ 1, 2, 3, 4 ] }
        ],
        ajax: "{{ route('user.all.wro') }}",
        columns: [
            {data: 'wro', name: 'wro' },
            {data: 'status', name: 'status', searchable: false},
            {data: 'date_of_request', name: 'date_of_request'},
            {data: 'actual_date_filed', name: 'actual_date_filed'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
        ]
      });
    });



    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'View Job Order Details?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            // view here
            window.location.replace("/u/job-order/view/" + id);

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
            // update here
            // window.location.replace("/u/job-order/cancel/" + id + "/" + result.value);
            // alert(result.value)
            $.ajax({
              url: "/u/job-order/cancel/" + id + "/" + result.value,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Job Order Cancelled!',
                  text: "",
                  type: 'success',
                  showCancelButton: false,
                  confirmButtonColor: '#3085d6',
                  cancelButtonColor: '#d33',
                  confirmButtonText: 'Close'
                });

                var table = $('#jo').DataTable();
                table.ajax.reload();
              },
              error: function() {
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


    $(document).on('click', '#view1', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'View Work Request Order Details?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            // view here
            window.location.replace("/u/work-order/view/" + id);

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

    $(document).on('click', '#cancel1', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Cancel Work Request Order?',
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
              url: "/u/work-order/cancel/" + id + "/" + result.value,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Work Request Order Cancelled!',
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