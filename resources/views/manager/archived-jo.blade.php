@extends("layouts.app")

@section("title") Archived Job Order @endsection

@section("header")
  @include("manager.includes.header")
@endsection

@section("page-title") Archived Job Order @endsection

@section("sidebar")
  @include("manager.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      {{-- <h4>Archived Job Order Lists</h4> --}}

        @include('includes.error')
        @include('includes.success')
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
          url: "{{ route('manager.all.archived.jo') }}",
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

  </script>
@endsection