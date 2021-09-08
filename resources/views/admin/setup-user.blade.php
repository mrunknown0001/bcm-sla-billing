@extends("layouts.app")

@section("title") Setup User @endsection

@section("header")
  @include("admin.includes.header")
@endsection

@section("page-title") Setup User @endsection

@section("sidebar")
  @include("admin.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-6 offset-3">
    	<p><a href="{{ route('admin.users') }}" class="btn btn-primary btn-xs"><i class="pe-7s-left-arrow"></i> Back to Users</a></p>
      <h4>Setup User: <u>{{ $user->first_name . ' ' . $user->last_name }}</u></h4>

      <form action="{{ route('admin.post.setup.user') }}" method="POST" autocomplete="off">
      	@csrf
      	<input type="hidden" name="user_id" value="{{ $user->id }}">
				<div class="form-group">
	    		<label for="manager">Select Manager</label>
	    		<select class="form-control" name="manager">
	    			<option value="" disabled="" selected="">Select Manager</option>
	    			@if(count($managers) > 0)
	    				@foreach($managers as $m) 
	    					<option value="{{ $m->id }}" @if(!empty($ra)) {{ $ra->manager == $m->id ? 'selected' : '' }} @endif>{{ $m->first_name . ' ' . $m->last_name }}</option>
	    				@endforeach
	    			@else
		    			<option value="">No Managers Available</option>
	    			@endif
	    		</select>
      	</div>

      	<div class="form-group">
	    		<label for="div_head">Select Division Head</label>
	    		<select class="form-control" name="div_head">
	    			<option value="" disabled selected>Select Division Head</option>
	    			@if(count($div_heads) > 0)
	    				@foreach($div_heads as $d) 
	    					<option value="{{ $d->id }}" @if(!empty($ra)) {{ $ra->div_head == $d->id ? 'selected' : '' }} @endif>{{ $d->first_name . ' ' . $d->last_name }}</option>
	    				@endforeach
	    			@else
		    			<option value="">No Division Head Available</option>
	    			@endif
	    		</select>
      	</div>
      	
      	<div class="form-grup">
      		<button class="btn btn-primary">Submit</button>
      	</div>
      </form>
    </div>
  </div>
@endsection

@section('styles')

@endsection

@section('scripts')

@endsection