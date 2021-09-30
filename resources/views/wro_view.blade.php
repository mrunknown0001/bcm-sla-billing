<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>SLA PDF Download</title>
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
            <p class="text-center"><strong>Server Level Agreement Document</strong></p>
            <br>
            <p>
                SLA #: <b>{{ $wro->wr_no }}</b>
                <br>
                Requestor: <b>{{ ucfirst($wro->user->first_name) . ' ' . ucfirst($wro->user->last_name) }}</b>
                <br>
                Date Requested: <b>{{ date('F j, Y', strtotime($wro->date_of_request)) }}</b>
                <br>
                Date Needed: <b>{{ date('F j, Y', strtotime($wro->date_needed)) }}</b>
                <br>
                Project Name: <b>{{ $wro->project_name }}</b>
            </p>

            <p>Project Description/Purpose:</p>
            <p><i>{{ $wro->description }}</i></p>


            <p>Project Justification:</p>
            <p><i>{{ $wro->justification }}</i></p>

            <p>URL: <b><a href="{{ $wro->url }}">{{ $wro->url }}</a></b></p>

            <div class="page-break"></div>

            <div class="column-left">
              <p class="">
                Requested By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->user_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->user_id)) !!}</b>
                <br>
                <i>Requestor</i>
              </p>
              <br>
              <p class="">
                Verified By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->gen_serv_div_head_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->gen_serv_div_head_id)) !!}</b>
                <br>
                <i>General Services - Division Head</i>
              </p>
              <br>
              <p class="">
                Recommending Approval By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->farm_divhead_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->farm_divhead_id )) !!}</b>
                <br>
                <i>Farm Division Head</i>
              </p>
              <br>
              <p class="">
                Approved By: <br>
                <img src="{{  public_path("uploads/signature/" . $wro->vp_gen_serv_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->vp_gen_serv_id  )) !!}</b>
                <br>
                <i>VP - General Services</i>
              </p>
            </div>

            <div class="column-right">
              <p class="">
                Checked By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->bcm_manager_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->bcm_manager_id)) !!}</b>
                <br>
                <i>BCM Manager</i>
              </p>
              <br>
              <p class="">
                Noted By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->farm_manager_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->farm_manager_id )) !!}</b>
                <br>
                <i>Farm Manager</i>
              </p>
              <br>
              <p class="">
                Approved By: <br>
                <img src="{{ public_path("uploads/signature/" . $wro->treasury_manager_id . ".png") }}" alt="" height="120px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($wro->treasury_manager_id )) !!}</b>
                <br>
                <i>Treasury Manager</i>
              </p>
            </div>

        </div>
    </div>
</body>

</html>