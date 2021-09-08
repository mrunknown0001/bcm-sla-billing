@extends("layouts.app")

@section("title") Module Management @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Module Management @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6">
    	@include('includes.success')
      <form action="{{ route('mail.setup') }}" method="POST">
      	@csrf
      	<div class="form-group">
      		<label for="mail_switch">Mail On/Off: </label>
      		<input type="checkbox" name="mail_switch" @if(!empty($ms)) @if($ms->status == 1) checked @endif @endif>
      		<button type="submit" class="btn btn-primary btn-sm">Save</button>
      	</div>
      </form>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection