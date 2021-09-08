@extends("layouts.app")

@section("title") View Job Order @endsection

@section("header")
	@if(Auth::user()->user_type == 6)
	  @include("user.includes.header")
	@elseif(Auth::user()->user_type == 4)
		@include("manager.includes.header")
	@endif
@endsection

@section("page-title") View Job Order @endsection

@section("sidebar")
	@if(Auth::user()->user_type == 6)
	  @include("user.includes.sidebar")
	@elseif(Auth::user()->user_type == 4)
		@include("manager.includes.sidebar")
	@endif
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.jo-view-details-common')
      <hr>
      @include('includes.common.jo_status')
      <br><br>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection