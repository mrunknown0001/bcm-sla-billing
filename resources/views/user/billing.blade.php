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
      <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <div class="form-group">
          <label for="reference_number">Reference Number (SLA)</label>
          <input type="text" name="reference_number" id="reference_number" placeholder="Reference Number (SLA)" class="form-control" >
        </div>
        <div class="form-group">
          <label for="project_name">Project Name</label>
          <input type="text" name="project_name" id="project_name" placeholder="Project Name" class="form-control" disabled="true">
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
          <input type="text" name="url" id="url" placeholder="URL" value="" class="form-control" required="">
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
<style type="text/css">
	.red {
		color: red;
	}
</style>
@endsection

@section('scripts')

@endsection