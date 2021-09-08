@extends("layouts.app")

@section("title") Unit of Measurement @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Unit of Measurement <a href="{{ route('admin.add.uom') }}">+</a>@endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.success')
      @include('includes.error')
      <table id="uom" class="table cell-border compact stripe hover" width="99%">
        <thead>
          <tr>
            <th scope="col">Name</th>
            <th scope="col">Short Code</th>
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

  <script type="text/javascript">
    $(document).ready(function() {
      $('#uom').DataTable({
        // serverSide: true,
        ajax: { 
          url: "{{ route('admin.all.uom') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'name' },
          { data: 'code' },
          { data: 'action' },
      ]
      });

    });
  </script>
@endsection