      <p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="pe-7s-back"></i> Back</a>
      </p>

      <p>SLA #: <b>{{ $wro->wr_no }}</b></p>
      <p>Requestor: <b>{{ $wro->user->first_name . ' ' . $wro->user->last_name }}</b></p>
      <p>Date Requested: <b>{{ date('F j, Y', strtotime($wro->date_of_request)) }}</b></p>
      <p>Date Needed: <b>{{ date('F j, Y', strtotime($wro->date_needed)) }}</b></p>
      <p>Project Name: <b>{{ $wro->project_name }}</b></p>

      <p>Project Description/Purpose:</p>
      <p><i>{{ $wro->description }}</i></p>
      <br>
      <p>Project Justification:</p>
      <p><i>{{ $wro->justification }}</i></p>
      <p>URL: <b><a href="{{ $wro->url }}" target="_blank">{{ $wro->url }}</b></a>
      </p>