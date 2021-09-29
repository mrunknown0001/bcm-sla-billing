@extends("layouts.app")

@section("title") Billing Form @endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Billing Form @endsection

@section("sidebar")
  @include("user.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-10 offset-1">
      @include('includes.success')
      @include('includes.error')
      @include('includes.errors')
    	<p>Fields with <span class="red">*</span> is required.</p>
      <form action="{{ route('user.post.billing') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="reference_number">Reference Number (SLA)</label>
          {{-- <input type="text"  name="reference_number" id="reference_number" placeholder="Reference Number (SLA)" class="form-control" > --}}
          <select class="form-control " name="reference_number" id="reference_number" style="" required="">
            <option value="">Select SLA</option>
            @foreach($sla as $key => $s)
              <option value="{{ $s['wr_no'] }}">{{ $s['wr_no'] }}</option>
            @endforeach 
          </select>
        </div>
        <div class="form-group">
          <label for="project_name">Project Name</label>
          <input type="text" name="project_name" id="project_name" placeholder="Project Name" class="form-control" readonly>
        </div>
      	<div class="form-group">
      		<label for="full_name">Name of Requestor</label>
      		<input type="text" name="full_name" id="full_name" placeholder="Name of Requestor" value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" class="form-control" disabled="true">
      	</div>
      	<div class="form-group">
      		<label for="date_of_request">Date of Request <span class="red">*</span></label>
      		<input type="date" name="date_of_request" id="date_of_request" placeholder="mm/dd/yyyy" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="date_needed">Date Needed <span class="red">*</span></label>
      		<input type="date" name="date_needed" id="date_needed" placeholder="mm/dd/yyyy" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="mobilization">Mobilization/Building No.<span class="red">*</span></label>
      		<input type="text" name="mobilization" id="mobilization" placeholder="Mobilization/Building No." value="" class="form-control" required="">
      	</div>
        <div class="form-group">
          <label for="url">URL <span class="red">*</span></label>
          <input type="text" name="url" id="url" placeholder="URL" value="" class="form-control" disabled="">
        </div>

      	<div class="form-group">
      		<button type="submit" class="btn btn-primary btn-lg">Submit</button>
      	</div>
      </form>
      <br>
    </div>
  </div>
@endsection

@section('styles')
<link href="{{ asset('css/select2.css') }}" rel="stylesheet">
{{-- <link href="{{ asset('css/bootstrap-select.css') }}" rel="stylesheet"> --}}
<style type="text/css">
	.red {
		color: red;
	}

</style>
@endsection

@section('scripts')
  <script src="{{ asset('js/select2.js') }}"></script>
  {{-- <script src="{{ asset('assets/login/js/popper.js') }}"></script> --}}
  {{-- <script src="{{ asset('js/bootstrap-select.js') }}"></script> --}}

  <script>
    $(document).ready(function() {
        $('#reference_number').select2();
        // project name
        $('#reference_number').change(function () {
          if($(this).val() == '') {
            $('#project_name').val('');
            $('#url').val('');
          }
          $.ajax({
            url: "/u/preview/project/" + $(this).val(),
            type: "GET",
            success: function(data) {
              console.log(data);
              $('#project_name').val(data);
            }
          });
          $.ajax({
            url: "/u/preview/url/" + $(this).val(),
            type: "GET",
            success: function(data) {
              console.log(data);
              $('#url').val(data);
            }
          });
        });
    });

      $("#submit").click(function(e){
        var request_date = new Date($('#date_of_request').val());
        var date_needed = new Date($('#date_needed').val());

        var d = new Date();
        var today = d.getDate();
        
        if (request_date > today){
          e.preventDefault();
           Swal.fire({
              title: 'Check Request Date',
              text: "Request Date must not greater than today",
              type: 'error',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Close'
            });
         }

        if (date_needed < request_date){
          e.preventDefault();
           Swal.fire({
              title: 'Check Dates',
              text: "Check Date of Request and Date Needed",
              type: 'error',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Close'
            });
         }
      });
  </script>
@endsection