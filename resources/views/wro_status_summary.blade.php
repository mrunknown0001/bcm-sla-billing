<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Work Request Order Status Summary</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
</head>

<body>
    <div class="row">
        <div class="col-md-12">
            <p class="text-center"><img src="https://jwo.bfcgroup.ph/assets/login/img/logo.png" alt="Brookside Group of Companies" height="60px"></p>
            <p class="text-center"><strong>Work Request Order Summary Report</strong></p>
            <br>
            <h4>{{ $month }} {{ $year }}</h4>
            <br>
            <table class="table table-bordered table-sm">
              <thead>
                <tr>
                  <th>Status</th>
                  <th>Count</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Pending</td>
                  <td>{{ $pending }}</td>
                </tr>
                <tr>
                  <td>Cancelled</td>
                  <td>{{ $cancelled }}</td>
                </tr>
                <tr>
                  <td>Disapproved</td>
                  <td>{{ $disapproved }}</td>
                </tr>
                <tr>
                  <td>Approved by Manager</td>
                  <td>{{ $manager_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by Division Head</td>
                  <td>{{ $divhead_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by BCM Manager</td>
                  <td>{{ $bcm_manager_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by General Services Div Head</td>
                  <td>{{ $gen_serv_div_head_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by Treasury Manager</td>
                  <td>{{ $treasury_manager_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by COO</td>
                  <td>{{ $coo_approved }}</td>
                </tr>
                <tr>
                  <td>Approved by VP on General Services</td>
                  <td>{{ $vp_gen_serv_approved }}</td>
                </tr>
              </tbody>
              <tfoot>
                <tr>
                  <th>Total</th>
                  <th>{{ $wro_total }}</th>
                </tr>
              </tfoot>
            </table>
        </div>
    </div>
</body>

</html>