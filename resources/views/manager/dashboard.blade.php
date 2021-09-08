@extends("layouts.app")

@section("title") Manager Dashboard @endsection

@section("header")
  @include("manager.includes.header")
@endsection

@section("page-title") Manager Dashboard  @endsection

@section("sidebar")
  @include("manager.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">

      @include('includes.error')
      @include('includes.success')
      @include('includes.notice')
      <h4>Job Order Lists</h4>
      <div class="table-wrapper">
         <table id="jo" class="table cell-border compact stripe hover " width="99%">
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
        </div>
        <hr>
      {{-- @if(\App\Http\Controllers\GeneralController::getDeptCode(Auth::user()->dept_id) == 'BCM' || \App\Http\Controllers\GeneralController::trsryMgr(Auth::user()->id)) --}}
      <h4>Work Request Order Lists</h4>

      <div class="table-wrapper">
       <table id="wro" class="table cell-border compact stripe hover" width="99%">
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
      </div>
        <hr>
      {{-- @endif --}}
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
      $('#jo').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
        ],
        ajax: { 
          url: "{{ route('manager.all.jo') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'jo' },
          { data: 'status'},
          { data: 'date_of_request' },
          { data: 'actual_date_filed' },
          { data: 'action' },
      ]
      });

      $('#wro').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
        ],
        ajax: { 
          url: "{{ route('manager.all.wro') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'wro' },
          { data: 'status'},
          { data: 'date_of_request' },
          { data: 'actual_date_filed' },
          { data: 'action' },
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
            window.location.replace("/manager/job-order/view/" + id);

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
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/jo/approval/" + id,
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
              url: "/manager/jo/disapprove/" + id + "/" + result.value,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Job Order Disapproved!',
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



    $(document).on('click', '#archive', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Archive Job Order?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/jo/archive/" + id,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Job Order Archived Successfull!',
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


    // wro
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
            window.location.replace("/manager/work-order/view/" + id);

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
          title: 'Approve Work Request Order?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/wro/approval/" + id,
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
          title: 'Disapprove Work Request Order?',
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
              url: "/manager/wro/disapproval/" + id + "/" + result.value,
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


    $(document).on('click', '#archive1', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Archive Work Request Order?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/wro/archive/" + id,
              type: "GET",
              success: function() {
                Swal.fire({
                  title: 'Work Request Order Archived Successfull!',
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

    $(document).on('click', '#view_bcm_mgr', function (e) {
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
            window.location.replace("/manager/work-order/view/" + id);

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
          title: 'Approve Work Request Order?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/wro/bcm/manager/approval/" + id,
              type: "GET",
              success: function(succ) {
                console.log(succ)
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


    $(document).on('click', '#disapprove_bcm_mgr', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Disapprove Work Request Order?',
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
              url: "/manager/wro/bcm/manager/disapproval/" + id + "/" + result.value,
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

    $(document).on('click', '#approve_trsry_mgr', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Approve Work Request Order?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Approve'
        }).then((result) => {
          if (result.value) {
            $.ajax({
              url: "/manager/wro/trsry/manager/approval/" + id,
              type: "GET",
              success: function(succ) {
                console.log(succ)
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

    $(document).on('click', '#disapprove_trsry_mgr', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'Disapprove Work Request Order?',
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
              url: "/manager/wro/trsry/manager/disapproval/" + id + "/" + result.value,
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