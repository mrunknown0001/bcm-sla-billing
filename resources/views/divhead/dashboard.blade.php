@extends("layouts.app")

@section("title") Division Head Dashboard @endsection

@section("header")
  @include("divhead.includes.header")
@endsection

@section("page-title") Division Head Dashboard @endsection

@section("sidebar")
  @include("divhead.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.notice')
      @include('includes.success')
      @include('includes.error')

	    <h4>SLA</h4>

       <table id="wro" class="table cell-border compact stripe hover" width="99%">
          <thead>
            <tr>
              <th scope="col">SLA #</th>
              <th scope="col">Status</th>
              <th scope="col">Date of Request</th>
              <th scope="col">Actual Date Filed</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
        </table>
        <hr>
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

      $('#wro').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
        ],
        ajax: { 
          url: "{{ route('divhead.all.work.order') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'wro' },
          { data: 'status'},
          { data: 'date_of_request' },
          { data: 'actual_date_filed'},
          { data: 'action' },
      ]
      });

    });


    $(document).on('click', '#view1', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'View SLA Details?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            // view here
            window.location.replace("/div-head/sla/view/" + id);

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

    $(document).on('click', '#approve1', function (e) {
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
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/div-head/sla/approval/" + id,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Approval Successfull!',
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
              title: 'Approval Action Cancelled',
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


    $(document).on('click', '#disapprove1', function (e) {
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
          confirmButtonText: 'Disapprove'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/div-head/sla/disapproval/" + id + "/" + result.value,
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


    $(document).on('click', '#approve_gs_div_head', function (e) {
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
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/div-head/gs/sla/approval/" + id,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Approval Successfull!',
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
              title: 'Approval Action Cancelled',
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
          confirmButtonText: 'Disapprove'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/div-head/gs/sla/disapproval/" + id + "/" + result.value,
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