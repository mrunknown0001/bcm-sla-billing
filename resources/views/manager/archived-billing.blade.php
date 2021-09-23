@extends("layouts.app")

@section("title") Archived Billing @endsection

@section("header")
  @include("manager.includes.header")
@endsection

@section("page-title") Archived Billing @endsection

@section("sidebar")
  @include("manager.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      {{-- <h4>Archived Work Request Order Lists</h4> --}}

        @include('includes.error')
        @include('includes.success')
       <table id="billing" class="table cell-border compact stripe hover" width="99%">
          <thead>
            <tr>
              <th scope="col">Ref. #</th>
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
      let billingtable = $('#billing').DataTable({
        processing: true,
        serverSide: true,
        columnDefs: [
          { className: "dt-center", targets: [ 1, 2, 3, 4 ] }
        ],
        ajax: "{{ route('manager.all.archived.billing') }}",
        columns: [
            {data: 'ref', name: 'ref' },
            {data: 'status', name: 'status'},
            {data: 'date_of_request', name: 'date_of_request'},
            {data: 'actual_date_filed', name: 'actual_date_filed'},
            {data: 'action', name: 'action', orderable: false, searchable: false},
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
            window.location.replace("/manager/sla/view/" + id);

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