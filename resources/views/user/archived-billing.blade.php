@extends("layouts.app")

@section("title") Archived Billing @endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Archived Billing @endsection

@section("sidebar")
  @include("user.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      {{-- <h4>Archived Work Request Order Lists</h4> --}}
        @include('includes.error')
        @include('includes.success')
      <h4>Billing Lists</h4>
        <table id="billing" class="display table cell-border compact stripe hover compact" width="99%">
          <thead>
            <tr>
              <th scope="col">Ref. #</th>
              <th scope="col">Project Name</th>
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
      $('#wro').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3, 4 ] }
        ],
        ajax: { 
          url: "{{ route('user.all.archived.wro') }}",
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
            window.location.replace("/u/sla/view/" + id);
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