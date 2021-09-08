@extends("layouts.app")

@section("title") Job Order View @endsection

@section("styles")

@endsection

@section("header")
  @include("divhead.includes.header")
@endsection

@section("page-title") Job Order Details @endsection

@section("sidebar")
  @include("divhead.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      
      <hr>
      @include('includes.common.jo-view-details-common')
      <hr>
      @if($jo->status == 1)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>

      @elseif($jo->status == 2)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Approved By: {!! App\Http\Controllers\GeneralController::getName($jo->manager_id) !!}</p>
        <p>Date Approved: {{ date('F j, Y H:m:i', strtotime($jo->manager_approved)) }}</p>
        <hr>
        {{-- <a href="{{ route('user.jo.pdf.download', ['id' => $jo->id]) }}" class="btn btn-primary"><i class="pe-7s-download"></i> Download PDF</a>  --}}
      @elseif($jo->status == 3)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Cancelled by: {{ $jo->cancelledBy->first_name . ' ' . $jo->cancelledBy->last_name }}</p>
        <p>Cancelled on: {{ date('F j, Y H:m:i', strtotime($jo->cancelled_on)) }}</p>
        <p>Reason: <i>{{ $jo->reason }}</i></p>
      @elseif($jo->status == 4)
        <p>Status: {!! App\Http\Controllers\GeneralController::viewJoStatus($jo->status) !!}</p>
        <hr>
        <p>Disapproved by: {{ App\Http\Controllers\GeneralController::getName($jo->manager_id) }}</p>
        <p>Disapproved on: {{ date('F j, Y H:m:i', strtotime($jo->disapproved_on)) }}</p>
        <p>Reason: <i>{{ $jo->reason }}</i></p>
      @endif
      <br><br>
    </div>
  </div>
@endsection

@section('scripts')
<script src="{{ asset('js/sweetalert.js') }}"></script>
<script>

</script>
@endsection