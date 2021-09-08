<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Job Order PDF Download</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <p class="text-center"><img src="https://jwo.bfcgroup.ph/assets/login/img/logo.png" alt="Brookside Group of Companies" height="60px"></p>
            <p class="text-center"><strong>Job Order Document</strong></p>
            <br>
            <p>
                Job Order #: <b>{{ $jo->jo_no }}</b>
                <br>
                Requestor: <b>{{ ucfirst($jo->user->first_name) . ' ' . ucfirst($jo->user->last_name) }}</b>
                <br>
                Date Requested: <b>{{ date('F j, Y', strtotime($jo->date_of_request)) }}</b>
                <br>
                Date Needed: <b>{{ date('F j, Y', strtotime($jo->date_needed)) }}</b>
                <br>
                Project Bldg. #: <b>{{ $jo->project_bldg_no }}</b>
            </p>
            <p class="text-center"><strong>ITEMS</strong></p>


            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>Item</th>
                  <th>Unit of Measurement</th>
                  <th>Quantity</th>
                  <th>Stock</th>
                </tr>
              </thead>
              <tbody>
                @foreach($jo->items as $i)
                  <tr>
                    <td>
                      {{ $i->item_name }}
                    </td>
                    <td>
                      {{ $i->um->name }}
                    </td>
                    <td>
                      {{ $i->quantity }}
                    </td>
                    <td>
                      {!! $i->on_stock == 1 ? '<span class="badge badge-success">On Stock</span>' : '<span class="badge badge-warning">To Purchase</span>' !!}
                    </td>
                  </tr>
                @endforeach
              </tbody>
            </table>
            <br>

            <p>Project Description/Purpose:</p>
            <p><i>{{ $jo->description }}</i></p>
            <p>Remarks: <i>{{ $jo->remarks }}</i></p>
            <p>Cost: {{ number_format($jo->cost) }}</p>
            <br>
            <br>
            <br>
            <br>


            <p class="">
              <img src="{{ public_path("uploads/signature/" . $jo->user_id . ".png") }}" alt="" height="100px">
              <br>
              <b>{{ ucfirst($jo->user->first_name) . ' ' . ucfirst($jo->user->last_name) }}</b>
              <br>
              <i>Requestor</i>
            </p>
            <br>
            <br>
            <br>
            <p class="">
              <img src="{{ public_path("uploads/signature/" . $jo->manager_id . ".png") }}" alt="" height="100px">
              <br>
              <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($jo->manager_id)) !!}</b>
              <br>
              <i>Manager</i>
            </p>
            </p>
            <br>
            <br>
            <br>
            @if($jo->cost >= 100000)
              <p class="">
                <img src="{{ public_path("uploads/signature/" . $jo->vp_id . ".png") }}" alt="" height="100px">
                <br>
                <b>{!! ucfirst(App\Http\Controllers\GeneralController::getName($jo->vp_id)) !!}</b>
                <br>
                <i>VP on General Services</i>
              </p>
            @endif
        </div>
    </div>
</body>

</html>