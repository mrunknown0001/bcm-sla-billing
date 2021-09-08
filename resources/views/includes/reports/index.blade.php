@extends("layouts.app")

@section("title") Reports @endsection

@section("header")
	@if(Auth::user()->user_type == 6)
	  @include("user.includes.header")
	@elseif(Auth::user()->user_type == 4)
		@include("manager.includes.header")
	@endif
@endsection

@section("page-title") Reports @endsection

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
      <h4>Job Order</h4>
      <div class="row">
      	<div class="col-md-12">
      		<strong>Filter</strong>
      		<div class="row">
      			<div class="col-md-3">
      				<div class="form-group row">
      					<label for="from" class="col-sm-2 col-form-label">From</label>
      					<div class="col-sm-10">
		      				<input type="date" id="from" name="from" class="form-control">
		      			</div>
	      			</div>
      			</div>
      			<div class="col-md-3">
      				<div class="form-group row">
      					<label for="to" class="col-sm-2 col-form-label">To</label>
      					<div class="col-sm-10">
		      				<input type="date" id="to" name="to" class="form-control">
		      			</div>
	      			</div>
      			</div>
            <div class="col-md-3">
              <select class="form-control" id="status">
                <option value="">All Status</option>
                <option value="1">Pending</option>
                <option value="2">Approved by Manager</option>
                <option value="3">Cancelled</option>
                <option value="4">Disapproved by Manager</option>
                <option value="5">Pending on VP on Gen. Services</option>
                <option value="6">Approved by VP on Gen. Services</option>
                <option value="7">Disapproved by VP on Gen. Services</option>                
              </select>
            </div>
            <div class="col-md-3">
              <button id="clear1" class="btn btn-warning">Clear Filter</button>
            </div>
      		</div>
      	</div>
      </div>
       <table id="jo" class="table cell-border compact stripe hover" width="99%">
          <thead>
            <tr>
              <th scope="col">JO #</th>
              <th scope="col">Status</th>
              <th scope="col">Date of Request</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
        </table>
        <hr>

      <h4>Work Request Order</h4>
      <div class="row">
      	<div class="col-md-12">
      		<strong>Filter</strong>
      		<div class="row">
      			<div class="col-md-3">
      				<div class="form-group row">
      					<label for="from" class="col-sm-2 col-form-label">From</label>
      					<div class="col-sm-10">
		      				<input type="date" id="from2" name="from2" class="form-control">
		      			</div>
	      			</div>
      			</div>
      			<div class="col-md-3">
      				<div class="form-group row">
      					<label for="to" class="col-sm-2 col-form-label">To</label>
      					<div class="col-sm-10">
		      				<input type="date" id="to2" name="to2" class="form-control">
		      			</div>
	      			</div>
      			</div>
            <div class="col-md-3">
              <select class="form-control" id="status2">
                <option value="">All Status</option>
                <option value="1">Pending Approval on Manager</option>
                <option value="2">Pending Approval on Division Head</option>
                <option value="3">Pending Approval on BCM Manager</option>
                <option value="4">Pending Approval on Gen. Services Division Head</option>
                <option value="5">Pending Approval on Treasury Manager</option>
                <option value="6">Pending Approval on COO</option>
                <option value="7">Final Approval</option>
                <option value="8">Approved</option>
                <option value="cancelled">Cancelled</option>
                <option value="disapproved">Disapproved</option>           
              </select>
            </div>
            <div class="col-md-3">
              <button id="clear2" class="btn btn-warning">Clear Filter</button>
            </div>
      		</div>
      	</div>
      </div>
       <table id="wro" class="table cell-border compact stripe hover" width="99%">
          <thead>
            <tr>
              <th scope="col">WRO #</th>
              <th scope="col">Status</th>
              <th scope="col">Date of Request</th>
              <th scope="col">Action</th>
            </tr>
          </thead>
        </table>
        <hr>
        <h4>Summary Export</h4>
        <form class="form" action="{{ route('reports.generate.download') }}" method="POST">
          @csrf
          <div class="row">
            <div class="col-md-12">
              <strong>Filter</strong>
              <div class="row">
                <div class="col-md-">
                  <div class="form-group row">
                    <label for="document_type" class="col-md-5 col-form-label">Document Type:</label>
                    <div class="col-md-6">
                      <select class="form-control" name="document_type" id="document_type" required>
                        <option value="">Document Type</option>
                        <option value="jo">Job Order</option>
                        <option value="wro">Work Request Order</option>
                      </select>
                    </div>
                </div>
              </div>
                <div class="col-md-2">
                  <div class="form-group row">
                    <label for="year" class="col-sm-4 col-form-label">Year:</label>
                    <div class="col-sm-8">
                      <select class="form-control" name="year" id="year" required>
                        <option value="">Year</option>
                        @if(count($years) > 0)
                          @foreach($years as $y)
                            <option value="{{ $y }}">{{ $y }}</option>
                          @endforeach
                        @else
                          <option value="">No JO/WRO Record Found</option>
                        @endif
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-3">
                  <div class="form-group row">
                    <label for="month" class="col-sm-4 col-form-label">Month:</label>
                    <div class="col-sm-6">
                      <select class="form-control" name="month" id="month" required>
                        <option value="">Month</option>
                        <option value="1">January</option>
                        <option value="2">February</option>
                        <option value="3">March</option>
                        <option value="4">April</option>
                        <option value="5">May</option>
                        <option value="6">June</option>
                        <option value="7">July</option>
                        <option value="8">August</option>
                        <option value="9">September</option>
                        <option value="10">October</option>
                        <option value="11">November</option>
                        <option value="12">December</option>
                      </select>
                    </div>
                  </div>
                </div>
                <div class="col-md-2">
                  <button type="submit" class="btn btn-primary">Generate</button>
                </div>
              </div>
            </div>
          </div>
        </form>
        <hr>
    </div>
  </div>
@endsection

@section('styles')
  <link rel="stylesheet" href="{{ asset('css/datatables.min.css') }}">
  <style>
		input[type="date"]{
		  border: none;
		  clip-path: inset(0 0 0 0);
		  outline:none;
		  outline:0;
		}
		input[type="date"]::-webkit-inner-spin-button,    
		input[type="date"]::-webkit-clear-button { display: none; }
  </style>
@endsection

@section('scripts')
  <script src="{{ asset('js/datatables.js') }}"></script>
  <script src="{{ asset('js/sweetalert.js') }}"></script>

  <script>

  	let jotable = null;
  	let wrotable = null;

  	let date_from = null;
  	let date_to = null;
    let status = null;

  	let date_from2 = null;
  	let date_to2 = null;
    let status2 = null;

    $(document).ready(function() {
      jotable = $('#jo').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3] }
        ],
        ajax: { 
          url: "{{ route('reports.all.jo') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'jo' },
          { data: 'status'},
          { data: 'date_of_request' },
          { data: 'action' },
      ],
      });

      wrotable = $('#wro').DataTable({
        // serverSide: true,
        columnDefs: [
           { className: "dt-center", targets: [ 0, 1, 2, 3 ] }
        ],
        ajax: { 
          url: "{{ route('reports.all.wro') }}",
          dataSrc: ""
        },
        columns: [
          { data: 'wro' },
          { data: 'status'},
          { data: 'date_of_request' },
          { data: 'action' },
      ]
      });
    });

    // JO

    $('#from').change(function() {
	    date_from = $(this).val();

	    if(date_from == null || date_from == '') {
        $('#to').val('');
        $('#status').val('');
        date_from = '';
        date_to = '';
        status = '';
	    	var nurl = "/reports/all/jo";
	    	jotable.ajax.url( nurl ).load();
        console.log(1)
	    }
	    else if(date_to != null && date_from != null && date_from <= date_to) {
        if(status != null || status != '') {
  		    newurl = "/reports/all/jo/" + date_from + "/" + date_to + "/" + status;
  		    jotable.ajax.url( newurl ).load();	
          console.log(2)
        }
	    }
	    else if(date_to != '' && date_from > date_to) {
        Swal.fire({
          title: 'Error in Time Range1',
          text: "Please check from and to date",
          type: 'error',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Close'
        });

        date_from = '';
        $(this).val('');
	    }

		});

    $('#to').change(function() {
	    date_to = $(this).val();
	    if(date_to == null || date_to == '') {
        $('#from').val('');
        $('#status').val('');
        date_from = '';
        date_to = '';
        status = '';
        var nurl = "/reports/all/jo";
        jotable.ajax.url( nurl ).load();
	    }
	    else if(date_from != null && date_from > date_to) {
	    	
        Swal.fire({
          title: 'Error in Time Range',
          text: "Please check from and to date",
          type: 'error',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Close'
        });

        date_to = '';
        $(this).val('');
	    }
	    else if(date_from == null) {
	    	return false;	
	    }
	    else {
        if(status == null) {
          newurl = "/reports/all/jo/" + date_from + "/" + date_to;
          jotable.ajax.url( newurl ).load();
        }
        else {
          newurl = "/reports/all/jo/" + date_from + "/" + date_to + "/" + status;
          jotable.ajax.url( newurl ).load();
        }

	    }

		});

    $('#status').change(function() {
      status = $(this).val();


      if((date_from == null || date_from == '') && (date_to == null || date_to == '')) {
        newurl = "/reports/job-order/" + status;
        jotable.ajax.url( newurl ).load();
        console.log(newurl)
      }
      else if(date_from != null && date_to != null) {
        newurl = "/reports/all/jo/" + date_from + "/" + date_to + "/" + status;
        jotable.ajax.url( newurl ).load();
        console.log(4)
      }
    });


    $('#clear1').click(function () {
    	$('#from').val('');
    	$('#to').val('');
      $('#status').val('');
      date_from = '';
      date_to = '';
      status = '';
    	var nurl = "/reports/all/jo";
    	jotable.ajax.url( nurl ).load();
    });



    // WRO

    $('#from2').change(function() {
	    date_from2 = $(this).val();
	    if(date_from2 == null || date_from2 == '') {
        $('#to2').val('');
        $('#status2').val('');
        date_from2 = '';
        date_to2 = '';
        status2 = '';
	    	var nurl = "/reports/all/wro";
	    	wrotable.ajax.url( nurl ).load();
	    }
	    else if(date_to2 != null && date_from2 != null && date_from2 <= date_to2) {
		    newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2;

		    wrotable.ajax.url( newurl ).load();	
	    }
	    else if(date_to2 != '' && date_from2 > date_to2) {
        Swal.fire({
          title: 'Error in Time Range',
          text: "Please check from and to date",
          type: 'error',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Close'
        });

        date_from2 = '';
        $(this).val('');
	    }

		});

    $('#to2').change(function() {
	    date_to2 = $(this).val();
	    if(date_to2 == null || date_to2 == '') {
        $('#from2').val('');
        $('#status2').val('');
        date_from2 = '';
        date_to2 = '';
        status2 = '';
	    	var nurl = "/reports/all/wro";
	    	wrotable.ajax.url( nurl ).load();
        console.log(1)
	    }
	    else if(date_from2 != null && date_from2 > date_to2) {
        Swal.fire({
          title: 'Error in Time Range',
          text: "Please check from and to date",
          type: 'error',
          showCancelButton: false,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Close'
        });
        date_to2 = '';
        $(this).val('');
	    }
	    else if(date_from2 == null) {
	    	return false;	
        console.log(2)
	    }
      else if(date_from2 != null || date_from2 != '' && date_to2 != '') {
        if(status2 != null) {
          if(status2 != '') {
            newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2 + "/" + status2;
            wrotable.ajax.url( newurl ).load();
            console.log(3)
          }
        }
        else {
          newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2;
          wrotable.ajax.url( newurl ).load();
          console.log(4)
        }
      }
	    else {
        if(status2 != null || status2 != '') {
  		    newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2 + "/" + status2;
          wrotable.ajax.url( newurl ).load();
          console.log(5)
        }
        else {
          newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2;
          wrotable.ajax.url( newurl ).load();
          console.log(6)
        }

	    }

		});

    $('#status2').change(function () {
      status2 = $(this).val();

      if((date_from2 == null || date_from2 == '') && (date_to2 == null || date_to2 == '')) {
        newurl = "/reports/work-order/" + status2;
        wrotable.ajax.url( newurl ).load();
        console.log(1)
      }
      else if(date_from2 != null || date_to != null) {
        if(date_from2 != '' && date_to2 != '') {
          newurl = "/reports/all/wro/" + date_from2 + "/" + date_to2 + "/" + status2;
          wrotable.ajax.url( newurl ).load();
          console.log(2)
        }
        else if(date_to2 == '') {
          return false;
        }

      }
      else if(date_from2 != null || date_from2 != '' && date_to2 == '') {
        return false;
        console.log(3)
      }
      else if(date_from2 == null || date_from2 == '' &&  date_to2 != '') {
        return false;
        console.log(4)
      }
      else if(date_from2 != '' && date_to2 == '' || date_to2 == null) {
        return false;
        console.log(5)
      }
      else {
        return false;
        console.log(6)
      }
    });

    $('#clear2').click(function () {
    	$('#from2').val('');
    	$('#to2').val('');
      $('#status2').val('');
      date_from2 = '';
      date_to2 = '';
      status2 = '';
    	var nurl = "/reports/all/wro";
    	wrotable.ajax.url( nurl ).load();
    });




    $(document).on('click', '#view', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'View Job Order Details?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            // view here
            window.location.replace("/reports/view/jo/" + id);

          }
          else {
            Swal.fire({
              title: 'Action Cancelled',
              text: "",
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Close'
            });
          }
        });
    });

    $(document).on('click', '#view2', function (e) {
        e.preventDefault();
        var id = $(this).data('id');
        var text = $(this).data('text');
        Swal.fire({
          title: 'View Work Request Order Details?',
          text: text,
          type: 'question',
          showCancelButton: true,
          confirmButtonColor: '#3085d6',
          cancelButtonColor: '#d33',
          confirmButtonText: 'Continue'
        }).then((result) => {
          if (result.value) {
            // view here
            window.location.replace("/reports/view/wro/" + id);

          }
          else {
            Swal.fire({
              title: 'Action Cancelled',
              text: "",
              type: 'info',
              showCancelButton: false,
              confirmButtonColor: '#3085d6',
              cancelButtonColor: '#d33',
              confirmButtonText: 'Close'
            });
          }
        });
    });
  </script>
@endsection