@extends("layouts.app")

@section("title") View Work Request Order @endsection

@section("header")
	@if(Auth::user()->user_type == 6)
	  @include("user.includes.header")
	@elseif(Auth::user()->user_type == 4)
		@include("manager.includes.header")
	@endif
@endsection

@section("page-title") View Work Request Order @endsection

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
      <hr>
      @include('includes.common.wro-view-details-common')
      <hr>
      <p>Status: {!! App\Http\Controllers\GeneralController::viewWroStatus($wro->approval_sequence, $wro->cancelled, $wro->disapproved) !!}</p>
      @include('includes.common.wro-approvals')

    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection