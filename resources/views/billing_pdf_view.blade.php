<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Billing PDF Download</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style type="text/css">
      .page-break {
        page-break-after: always;
      }
      .column-left {
        float: left !important;
        width: 50% !important;
        /*padding: 10px !important;*/
      }

      .column-right {
        float: right !important;
        width: 50% !important;
        /*padding: 10px !important;*/
      }
    </style>
</head>

<body>
  <div class="row">
    <div class="col-md-12">
      <p class="text-center"><img src="https://jwo.bfcgroup.ph/assets/login/img/logo.png" alt="Brookside Group of Companies" height="60px"></p>
      <p class="text-center"><strong>Billing Document</strong></p>
      <br>
      <p>
        Reference Number: <b>{{ $billing->reference_number }}</b>
        <br>
        Project Name: <b>{{ $billing->project_name }}</b>
        <br>
        Requestor: <b>{{ ucfirst($billing->user->first_name) . ' ' . ucfirst($billing->user->last_name) }}</b>
        <br>
        Date Requested: <b>{{ date('F j, Y', strtotime($billing->date_of_request)) }}</b>
        <br>
        Date Needed: <b>{{ date('F j, Y', strtotime($billing->date_needed)) }}</b>
      </p>

      <p>URL: <b>{{ $billing->url }}</b></p>

      <div class="page-break"></div>

      <div class="column-left">
        <p class="">
          Requested By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->user_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Date Requested: {{ $billing->created_at }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->user_id)) !!}</b>
          <br>
          <i>Requestor</i>
        </p>
        <br>
        <p class="">
          Verified By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->gen_serv_div_head_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->gen_serv_div_head_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->gen_serv_div_head_id)) !!}</b>
          <br>
          <i>General Services - Division Head</i>
        </p>
        <br>
        @if($billing->farm_divhead_id != 0)
        <p class="">
          Recommending Approval By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->farm_divhead_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->farm_divhead_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->farm_divhead_id )) !!}</b>
          <br>
          <i>Farm Division Head</i>
        </p>
        <br>
        @endif
        <p class="">
          Approved By: <br>
          <img src="{{  public_path("uploads/signature/" . $billing->vp_gen_serv_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->vp_gen_serv_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->vp_gen_serv_id  )) !!}</b>
          <br>
          <i>VP - General Services</i>
        </p>
      </div>
      <div class="column-right">
        <p class="">
          Checked By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->bcm_manager_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->bcm_manager_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->bcm_manager_id)) !!}</b>
          <br>
          <i>BCM Manager</i>
        </p>
        <br>
        @if($billing->farm_manager_id != 0)
        <p class="">
          Noted By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->farm_manager_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->farm_manager_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->farm_manager_id )) !!}</b>
          <br>
          <i>Farm Manager</i>
        </p>
        <br>
        @endif
        <p class="">
          Approved By: <br>
          <img src="{{ public_path("uploads/signature/" . $billing->treasury_manager_id . ".png") }}" alt="" height="100px">
          <br>
          <small>Digitally Signed on: {{ $billing->treasury_manager_approved }}</small>
          <br>
          <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($billing->treasury_manager_id )) !!}</b>
          <br>
          <i>Treasury Manager</i>
        </p>
      </div>
    </div>
  </div>
</body>
</html>