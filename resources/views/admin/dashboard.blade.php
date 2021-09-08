@extends("layouts.app")

@section("title") Admin Dashboard @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Admin Dashboard @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6">
      @include('includes.notice')
      @include('includes.success')
      @include('includes.error')
      Dashboard
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection