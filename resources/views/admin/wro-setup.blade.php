@extends("layouts.app")

@section("title") Work Request Approval Setup @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Work Request Approval Setup @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.success')
      @include('includes.error')
      <table class="table table-bordered table-alternate table-sm table-hover">
        <thead>
          <tr>
            <th scope="col">#</th>
            <th scope="col">Desc</th>
            <th scope="col">Name</th>
            <th scope="col">Position</th>
            <th scope="col">Action</th>
          </tr>
        </thead>
        <tbody>
          <tr>
            <th scope="row">1</th>
            <th>First Approver</th>
            <td>{{ $bcm_manager }}</td>
            <td>{{ $fa_pos_dept }}</td>
            <td><a href="{{ route('admin.wro.setup.form', ['code' => 1]) }}" class="btn btn-primary btn-sm" ><i class="pe-7s-settings"></i> Setup</a></td>
          </tr>
          <tr>
            <th scope="row">2</th>
            <th>Second Approver</th>
            <td>{{ $gen_serv_div_head }}</td>
            <td>{{ $sa_pos_dept }}</td>
            <td><a href="{{ route('admin.wro.setup.form', ['code' => 2]) }}" class="btn btn-primary btn-sm" ><i class="pe-7s-settings"></i> Setup</a></td>
          </tr>
          <tr>
            <th scope="row">3</th>
            <th>Third Approver</th>
            <td>Dept/Farm Manager</td>
            <td>Dept/Farm Manager</td>
            <td></td>
          </tr>
          <tr>
            <th scope="row">4</th>
            <th>Fourth Approver</th>
            <td>Dept/Farm Division Head</td>
            <td>Dept/Farm Division Head</td>
            <td></td>
          </tr>
          <tr>
            <th scope="row">5</th>
            <th>Fifth Approver</th>
            <td>{{ $treasury_manager }}</td>
            <td>{{ $fiftha_pos_dept }}</td>
            <td><a href="{{ route('admin.wro.setup.form', ['code' => 3]) }}" class="btn btn-primary btn-sm" ><i class="pe-7s-settings"></i> Setup</a></td>
          </tr>
          {{-- <tr>
            <th scope="row">4</th>
            <td>COO</td>
            <td>{{ $coo }}</td>
            <td><a href="{{ route('admin.wro.setup.form', ['code' => 4]) }}" class="btn btn-primary btn-sm" ><i class="pe-7s-settings"></i> Setup</a></td>
          </tr> --}}
          <tr>
            <th scope="row">6</th>
            <th>Final Approver</th>
            <td>{{ $vp_gen_serv }}</td>
            <td>{{ $fin_app_pos_dept }}</td>
            <td><a href="{{ route('admin.wro.setup.form', ['code' => 5]) }}" class="btn btn-primary btn-sm" ><i class="pe-7s-settings"></i> Setup</a></td>
          </tr>
        </tbody>
      </table>
    </div>
  </div>


@endsection

@section('styles')
<style>
.swal2-select {
  height: 10vh;
}
</style>
@endsection

@section('scripts')
  <script src="{{ asset('js/sweetalert.js') }}"></script>
  <script>
    $(document).ready(function() {

      var options = $.getJSON( "{{ route('admin.get.managers') }}");
      


      $(document).on('click', '#updatebcmmanager1', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Update BCM Manager',
            text: 'BCM Manager Approver on Work Request Orders',
            type: 'question',
            input: 'select',
            inputOptions: { options },
            inputPlaceholder: 'Select BCM Manager',
            inputClass: 'form-control',
            inputValidator: (value) => {
              return !value && 'Select A Manager!'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
          }).then((result) => {
            if (result.value) {
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


      $(document).on('click', '#updategenservdivhead1', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Update General Services Division Head',
            text: 'General Services Division Head Approver on Work Request Orders',
            type: 'question',
            input: 'select',
            inputOptions: { options },
            inputPlaceholder: 'Select Gen Services Div Head',
            inputClass: 'form-control',
            inputValidator: (value) => {
              return !value && 'Select General Services Division Head!'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
          }).then((result) => {
            if (result.value) {
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


      $(document).on('click', '#updatetrsrymanager1', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Update Treasury Manager',
            text: 'Treasury Manager Approver on Work Request Orders',
            type: 'question',
            input: 'select',
            inputOptions: { options },
            inputPlaceholder: 'Select Treasury Manager',
            inputClass: 'form-control',
            inputValidator: (value) => {
              return !value && 'Select Treasury Manager!'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
          }).then((result) => {
            if (result.value) {
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


      $(document).on('click', '#updatevpgenserv1', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Update Vice President - General Services',
            text: 'Vice President on General Services Approver on Work Request Orders',
            type: 'question',
            input: 'select',
            inputOptions: { options },
            inputPlaceholder: 'Select Vice President on General Services',
            inputClass: 'form-control',
            inputValidator: (value) => {
              return !value && 'Select Vice President on General Services!'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
          }).then((result) => {
            if (result.value) {
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


      $(document).on('click', '#updatecoo1', function (e) {
          e.preventDefault();
          Swal.fire({
            title: 'Update COO',
            text: 'COO Approver on Work Request Orders',
            type: 'question',
            input: 'select',
            inputOptions: { options },
            inputPlaceholder: 'Select COO',
            inputClass: 'form-control',
            inputValidator: (value) => {
              return !value && 'Select COO!'
            },
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Submit',
          }).then((result) => {
            if (result.value) {
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

    });


  </script>
@endsection