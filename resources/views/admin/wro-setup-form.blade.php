@extends("layouts.app")

@section("title") Work Request Approval Setup @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Work Request Approval Setup @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6 offset-3">
      <p><a href="{{ route('admin.wro.setup') }}" class="btn btn-primary btn-xs"><i class="pe-7s-left-arrow"></i> Back to WRO Setup</a></p>
      <form action="{{ route('admin.post.wro.setup') }}" method="POST">
        @csrf
        @if($code == 1)
          <div class="form-group">
            <label for="bcm_manager">Select First Approver</label>
            <select name="bcm_manager" id="bcm_manager" class="form-control">
              <option value="">Select First Approver</option>
              @foreach($managers as $m)
                <option value="{{ $m->id }}">{{ \App\Http\Controllers\GeneralController::getName($m->id) }}</option>
              @endforeach
            </select>
          </div>
        @elseif($code == 2)
          <div class="form-group">
            <label for="gsdivhead">Select Second Approver</label>
            <select name="gsdivhead" id="gsdivhead" class="form-control">
              <option value="">Select Second Approver</option>
              @foreach($divhead as $m)
                <option value="{{ $m->id }}">{{ \App\Http\Controllers\GeneralController::getName($m->id) }}</option>
              @endforeach
            </select>
          </div>
        @elseif($code == 3)
          <div class="form-group">
            <label for="treasury_manager">Select Fifth Approver</label>
            <select name="treasury_manager" id="treasury_manager" class="form-control">
              <option value="">Select Fifth Approver</option>
              @foreach($managers as $m)
                <option value="{{ $m->id }}">{{ \App\Http\Controllers\GeneralController::getName($m->id) }}</option>
              @endforeach
            </select>
          </div>
        {{-- @elseif($code == 4)
          <div class="form-group">
            <label for="coo">Select COO</label>
            <select name="coo" id="coo" class="form-control">
              <option value="">Select COO</option>
              @foreach($co as $m)
                <option value="{{ $m->id }}">{{ \App\Http\Controllers\GeneralController::getName($m->id) }}</option>
              @endforeach
            </select>
          </div> --}}
        @elseif($code == 5)
          <div class="form-group">
            <label for="vp">Select Final Approver </label>
            <select name="vp" id="vp" class="form-control">
              <option value="">Select Final Approver</option>
              @foreach($vp as $m)
                <option value="{{ $m->id }}">{{ \App\Http\Controllers\GeneralController::getName($m->id) }}</option>
              @endforeach
            </select>
          </div>
        @endif
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

@endsection