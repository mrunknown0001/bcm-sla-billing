      <p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="pe-7s-back"></i> Back</a>
      </p>

      <p>Reference Number: <b>{{ $billing->reference_number }}</b></p>
      <p>Requestor: <b>{{ $billing->user->first_name . ' ' . $billing->user->last_name }}</b></p>
      <p>Date Requested: <b>{{ date('F j, Y', strtotime($billing->date_of_request)) }}</b></p>
      <p>Date Needed: <b>{{ date('F j, Y', strtotime($billing->date_needed)) }}</b></p>
      <p>Project Name: <b>{{ $billing->project_name }}</b></p>

      <p>URL: <b><a href="{{ $billing->url }}" target="_blank">{{ $billing->url }}</b></a>
      </p>