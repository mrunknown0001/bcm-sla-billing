@extends("layouts.app")

@section("title") Update User @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Update User @endsection

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
      <form action="{{ route('admin.post.upate.user') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
        <input type="hidden" name="id" value="{{ $user->id }}">
      	<div class="form-group">
      		<label for="first_name">First Name</label>
      		<input type="text" name="first_name" id="first_name" value="{{ $user->first_name }}" placeholder="First Name" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="last_name">Last Name</label>
      		<input type="text" name="last_name" id="last_name" value="{{ $user->last_name }}" placeholder="Last Name" class="form-control" required>
      	</div>
      	<div class="form-group">
      		<label for="email">Email</label>
      		<input type="text" name="email" id="email" value="{{ $user->email }}" placeholder="Email" class="form-control">
      	</div>
      	<div class="form-group">
      		<label for="farm">Farm</label>
      		<select name="farm" id="farm" class="form-control" required>
      			<option value="" disabled="">Select Farm</option>
      			@foreach($farms as $f)
      				<option value="{{ $f->id }}" {{ $f->id == $user->farm_id ? 'selected' : '' }}>{{ $f->code }}</option>
      			@endforeach
      		</select>
      	</div>
      	<div class="form-group">
      		<label for="department">Department</label>
      		<select name="department" id="department" class="form-control" required>
      			<option value="" disabled="">Select Department</option>
      			@foreach($departments as $d)
      				<option value="{{ $d->id }}" {{ $d->id == $user->dept_id ? 'selected' : '' }}>{{ $d->name }}</option>
      			@endforeach
      		</select>
      	</div>
      	<div class="form-group">
      		<label for="position">Position</label>
      		<select name="position" id="position" class="form-control" required>
      			<option value="">Select Position</option>
      			@foreach($positions as $p)
      				<option value="{{ $p['id'] }}" {{ $p['id'] == $user->user_type ? 'selected' : '' }}>{{ $p['position'] }}</option>
      			@endforeach
      		</select>
      	</div>

        <div class="form-group">
          <label for="password">Password</label>
          <input type="text" name="password" id="password" placeholder="New Password For This User" class="form-control">
        </div>
        <div class="form-group">
          <label for="signature">Signature</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" name="signature" id="signature" class="custom-file-input" accept="image/png">
              <label class="custom-file-label" for="attachment">Choose PNG File</label>
            </div>
          </div>
        </div>
        @if($user->user_type != 0)
        <div class="form-group">
          <label for="active">Active?</label>
          <input type="checkbox" name="active" id="active" class="" {{ $user->active == 1 ? 'checked' : '' }}>
        </div>
        @endif
      	<div class="form-group">
      		<button type="submit" class="btn btn-primary btn-lg">Update</button>
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