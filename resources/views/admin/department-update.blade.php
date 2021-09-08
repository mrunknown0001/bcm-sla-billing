@extends("layouts.app")

@section("title") Update Department @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Update Department @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6 offset-3">
      <p><a href="{{ route('admin.departments') }}" class="btn btn-primary btn-xs"><i class="pe-7s-left-arrow"></i> Back to Departments</a></p>
      @include('includes.success')
      @include('includes.error')
      @include('includes.errors')
      <form class="form" action="{{ route('admin.post.update.department') }}" method="POST" autocomplete="off">
      	@csrf
        <input type="hidden" name="id" value="{{ $dept->id }}">
      	<div class="form-group">
      		<label for="name">Name</label>
      		<input type="text" class="form-control" id="name" name="name" value="{{ $dept->name }}" placeholder="Name" required="">
      	</div>
      	<div class="form-group">
      		<label for="code">Code</label>
      		<input type="text" class="form-control" id="code" name="code" value="{{ $dept->code }}" placeholder="Code" required="">
      	</div>
      	<div class="form-group">
      		<label for="description">Description</label>
      		<input type="text" class="form-control" id="description" name="description" value="{{ $dept->description }}" placeholder="Description">
      	</div>
      	<div class="form-group">
      		<button type="submit" class="btn btn-warning btn-lg">Update</button>
      	</div>
      </form>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection