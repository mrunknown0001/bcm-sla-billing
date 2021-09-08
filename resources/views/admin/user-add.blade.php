@extends("layouts.app")

@section("title") Add User @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Add User @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6 offset-3">
      <p><a href="{{ route('admin.users') }}" class="btn btn-primary btn-xs"><i class="pe-7s-left-arrow"></i> Back to Users</a></p>
 			@include('includes.success')
 			@include('includes.error')
 			@include('includes.errors')
      <form action="" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
      	<div class="form-group">
      		<label for="first_name">First Name</label>
      		<input type="text" name="first_name" id="first_name" placeholder="First Name" class="form-control">
      	</div>
      	<div class="form-group">
      		<label for="last_name">Last Name</label>
      		<input type="text" name="last_name" id="last_name" placeholder="Last Name" class="form-control">
      	</div>
      	<div class="form-group">
      		<label for="email">Email</label>
      		<input type="text" name="email" id="email" placeholder="Email" class="form-control">
      	</div>
      	<div class="form-group">
      		<label for="farm">Farm</label>
      		<select name="farm" id="farm" class="form-control" required="">
      			<option value="">Select Farm</option>
      			@foreach($farms as $f)
      				<option value="{{ $f->id }}">{{ $f->code }}</option>
      			@endforeach
      		</select>
      	</div>
      	<div class="form-group">
      		<label for="department">Department</label>
      		<select name="department" id="department" class="form-control">
      			<option value="">Select Department</option>
      			@foreach($departments as $d)
      				<option value="{{ $d->id }}">{{ $d->name }}</option>
      			@endforeach
      		</select>
      	</div>
      	<div class="form-group">
      		<label for="position">Position</label>
      		<select name="position" id="position" class="form-control" required="">
      			<option value="">Select Position</option>
      			@foreach($positions as $p)
      				<option value="{{ $p['id'] }}">{{ $p['position'] }}</option>
      			@endforeach
      		</select>
      	</div>
      	<div class="form-group">
      		<label for="signature">Signature</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" name="signature" id="signature" class="custom-file-input" accept="image/png" required="">
              <label class="custom-file-label" for="attachment">Choose PNG File</label>
            </div>
          </div>
      	</div>
      	<div class="form-group">
      		<button type="submit" class="btn btn-primary btn-lg">Submit</button>
      	</div>
      </form>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')
  <script>
    $('#signature').on('change',function(){
      //get the file name
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ")
      //replace the "Choose a file" label
      if(fileName == '') {
        $(this).next('.custom-file-label').html('Choose PNG File');
      }
      else {
        $(this).next('.custom-file-label').html(cleanFileName);
      }
    });
  </script>
@endsection