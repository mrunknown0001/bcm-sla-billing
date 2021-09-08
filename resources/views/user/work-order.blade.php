@extends("layouts.app")

@section("title") Work Order Form @endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Work Order Form @endsection

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
      <form action="{{ route('user.post.work.order') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        @if(\App\Http\Controllers\GeneralController::getDeptCode(Auth::user()->dept_id) == 'BCM')
        <div class="form-group">
          <label for="farm">Farm</label>
          <select class="form-control" name="farm" id="farm" required>
            <option value="">Select Farm</option>
            @foreach($farms as $f)
              <option value="{{ $f->id }}">{{ $f->name }}</option>
            @endforeach
          </select>
        </div>
        @endif
      	<div class="form-group">
      		<label for="work_order_number">Work Order #</label>
      		<input type="text" name="work_order_number" id="work_order_number" placeholder="Work Request Order Number is Automatically Generated" value="{{ $next_wro_number }} (Possible Next Work Request Order # Series)" class="form-control" disabled="true">
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
      		<label for="project">Project Bldg. # <span class="red">*</span></label>
      		<input type="text" name="project" id="project" placeholder="Project Bldg. #" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="description">Project Description/Purpose <span class="red">*</span></label>
      		<textarea type="text" name="description" id="description" placeholder="Project Description/Purpose" class="form-control" required="" rows="5"></textarea>
      	</div>

      	<div class="form-group">
      		<label for="justification">Project Justification <span class="red">*</span></label>
      		<textarea type="text" name="justification" id="justification" placeholder="Project Justification" class="form-control" required="" rows="5"></textarea>
      	</div>
        <div class="form-group">
          <label for="attachment">Attachment</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="attachment" name="attachment" accept="application/pdf">
              <label class="custom-file-label" for="attachment">Choose PDF File</label>
            </div>
          </div>
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
  <script type="text/javascript">
    $('#attachment').on('change',function(){
      //get the file name
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ")
      //replace the "Choose a file" label
      if(fileName == '') {
        $(this).next('.custom-file-label').html('Choose PDF File');
      }
      else {
        $(this).next('.custom-file-label').html(cleanFileName);
      }
    });


    $('#farm').on('change', function () {
      $.ajax({
        url: "/u/wro/number/preview/" + $(this).val(),
        type: "GET",
        success: function(data) {
          $('#work_order_number').val(data);
        }
      });
    });
  </script>
@endsection