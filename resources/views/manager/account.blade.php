@extends("layouts.app")

@section("title") User Account @endsection

@section("header")
  @include("manager.includes.header")
@endsection

@section("page-title") User Account @endsection

@section("sidebar")
  @include("manager.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
  <div class="col-md-12">
  		@include('includes.common.change-pass');
    </div>
  </div>
@endsection

@section('styles')
  {{-- <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}"> --}}
@endsection

@section('scripts')
  {{-- <script src="{{ asset('js/datatables.js') }}"></script> --}}
  {{-- <script src="{{ asset('js/sweetalert.js') }}"></script> --}}
@endsection