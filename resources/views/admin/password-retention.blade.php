@extends("layouts.app")

@section("title") Password Retention @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Password Retention @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      <p>Password Retention is set to <b>{{ $days }}</b> days.</p>
      <div class="row">
      	<div class="col-md-6 offset-3">
      		@include('includes.error')
      		@include('includes.success')
      		<form class="form" action="{{ route('admin.post.password.retention') }}" method="POST" autocomplete="off">
      			@csrf
      			<div class="form-group">
      				<label for="days">Days of Rentetion</label>
      				<input type="number" name="days" id="days" placeholder="Days of Rentention" class="form-control" required="">
      			</div>
      			<div class="form-group">
      				<button type="submit" class="btn btn-primary">Submit</button>
      			</div>
      		</form>
      	</div>
      </div>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection