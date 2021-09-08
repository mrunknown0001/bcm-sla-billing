@extends("layouts.app")

@section("title") Job Order Form @endsection

@section("styles")
<style type="text/css">
	.red {
		color: red;
	}
</style>
@endsection

@section("header")
  @include("user.includes.header")
@endsection

@section("page-title") Job Order Form @endsection

@section("sidebar")
  @include("user.includes.sidebar")
@endsection

@section("page-content")
  <div class="row">
    <div class="col-md-12">
      @include('includes.error')
      @include('includes.success')
      @include('includes.errors')
    	<p>Fields with <span class="red">*</span> is required.</p>
      <form action="{{ route('user.post.job.order') }}" method="POST" autocomplete="off" enctype="multipart/form-data">
        @csrf
      	<div class="form-group">
      		<label for="job_order_number">Job Order #</label>
      		<input type="text" name="job_order_number" id="job_order_number" placeholder="Job Order Number is Automatically Generated" value="{{ $next_jo_series }} (Possible Next Job Order # Series)" class="form-control" disabled="true">
      	</div>
      	<div class="form-group">
      		<label for="full_name">Name of Requestor</label>
      		<input type="text" name="full_name" id="full_name" placeholder="Name of Requestor" value="{{ Auth::user()->first_name . ' ' . Auth::user()->last_name }}" class="form-control" disabled="true">
      	</div>
      	<div class="form-group">
      		<label for="date_of_request">Date of Request <span class="red">*</span></label>
      		<input type="date" name="date_of_request" id="date_of_request" placeholder="mm/dd/yyyy" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="date_needed">Date Needed <span class="red">*</span></label>
      		<input type="date" name="date_needed" id="date_needed" placeholder="mm/dd/yyyy" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<label for="project">Project Bldg. # <span class="red">*</span></label>
      		<input type="text" name="project" id="project" placeholder="Project Bldg. #" value="" class="form-control" required="">
      	</div>
      	<div class="form-group">
      		<p class="text-center">Existing Inventory Level of Item Requested <span class="red">*</span></p>
      	</div>
      	<div id="items">
	      	{{-- <div class="form-group">
	      		<label for="item1">Item # 1</label>
	      		<input type="text" name="items[]" id="item1" placeholder="Item # 1" value="" class="form-control" required="">
	      	</div> --}}
	      	<div class="row form-group">
	      		<div class="col-md-4">
	      			<input type="text" name="items[]" id="item1" placeholder="Item" value="" class="form-control" required="">
	      		</div>
	      		<div class="col-md-3">
	      			<select name="uom[]" id="uom1" class="form-control" required>
	      				<option value="">Unit of Measurement</option>
                @foreach($uom as $u)
                <option value="{{ $u->id }}">{{ $u->code . ' - ' . $u->name }}</option>
                @endforeach
	      			</select>
	      		</div>
	      		<div class="col-md-2">
	      			<input type="number" name="qty[]" id="qty1" placeholder="Qty" value="" class="form-control" required=""  min="0.25" step="0.25">
	      		</div>
            <div class="col-md-2">
              <select name="stock[]" id="stock" class="form-control" required>
                <option value="" disabled selected>Select Option</option>
                <option value="on_stock">On Stock</option>
                <option value="to_purchase">To Purchase</option>
              </select>
            </div>
	      		<div class="col-md-1">
	      			{{-- <button type="button" class="btn btn-danger btn-xs">x</button> --}}
	      		</div>
	      	</div>
	      </div>
	      <div class="form-group">
	      	<button id="addItem" type="button" class="btn btn-success btn-sm">Add Item</button>
	      </div>
      	<div class="form-group">
      		<label for="description">Project Description/Purpose <span class="red">*</span></label>
      		<textarea type="text" name="description" id="description" placeholder="Project Description/Purpose" class="form-control" required="" rows="5"></textarea>
      	</div>
        <div class="form-group">
          <label for="remarks">Remarks </label>
          <input type="text" name="remarks" id="remarks" placeholder="Remarks" value="" class="form-control">
        </div>
        <div class="form-group">
          <label for="cost">Rough Cost Estimate </label>
          <input type="number" name="cost" id="cost" min="1" placeholder="Rough Cost Estimate" value="" class="form-control">
        </div>
        <div class="form-group">
          <label for="attachment">Attachment</label>
          <div class="input-group">
            <div class="input-group-prepend">
              <span class="input-group-text">Upload</span>
            </div>
            <div class="custom-file">
              <input type="file" class="custom-file-input" id="attachment" name="attachment" accept="application/pdf">
              <label class="custom-file-label" for="attachment">Choose PDF File</label>
            </div>
          </div>
        </div>
      	<div class="form-group">
      		<button type="submit" class="btn btn-primary btn-lg">Submit</button>
      	</div>
      </form>
      <br>
    </div>
  </div>
@endsection

@section('scripts')
<script>
	var id=1;
	$("#addItem").click(function (e) {
		id += 1;
	  $("#items").append('<div id="itemrow' + id + '" class="row form-group"><div class="col-md-4">	      			<input type="text" name="items[]" id="item' + id + '" placeholder="Item" value="" class="form-control" required="">	      		</div>	      		<div class="col-md-3">	      			<select name="uom[]" id="uom' + id + '" class="form-control">	      				<option value="">Unit of Measurement</option>' + '@foreach($uom as $u)' + '<option value="' + '{{ $u->id }}' +'">' + '{{ $u->code . ' - ' . $u->name }}' + '</option>' +  '@endforeach'  + '      			</select>	      		</div>	      		<div class="col-md-2">	      			<input type="number" name="qty[]" id="qty' + id + '" placeholder="Qty" value="" class="form-control" required="" min="0.25" step="0.25">	      		</div>	                <div class="col-md-2">              <select name="stock[]" id="stock" class="form-control" required>                <option value="" disabled selected>Select Option</option>                <option value="on_stock">On Stock</option>                <option value="to_purchase">To Purchase</option>              </select>            </div>  		<div class="col-md-1">	      			<button type="button" class="btn btn-danger btn-xs" onclick="remove(' + id + ')">x</button></div>    	</div>');
	});

	function remove(id){
		did = '#itemrow' + id
		// console.log(did)
		$(did).remove();
	}


    $('#attachment').on('change',function(){
      //get the file name
      var fileName = $(this).val();
      var cleanFileName = fileName.replace('C:\\fakepath\\', " ")
      //replace the "Choose a file" label
      if(fileName == '') {
        $(this).next('.custom-file-label').html('Choose PDF File');
      }
      else {
        $(this).next('.custom-file-label').html(cleanFileName);
      }
    });
</script>
@endsection