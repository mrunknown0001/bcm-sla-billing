      <p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="pe-7s-back"></i> Back</a>
      </p>

      <p>SLA #: {{ $wro->wr_no }}</p>
      <p>Requestor: {{ $wro->user->first_name . ' ' . $wro->user->last_name }}</p>
      <p>Date Requested: {{ date('F j, Y', strtotime($wro->date_of_request)) }}</p>
      <p>Date Needed: {{ date('F j, Y', strtotime($wro->date_needed)) }}</p>
      <p>Project Name: {{ $wro->project_name }}</p>

      <p>Project Description/Purpose</p>
      <p><i>{{ $wro->description }}</i></p>
      <br>
      <p>Project Justification</p>
      <p><i>{{ $wro->justification }}</i></p>
      <p>URL: <a href="{{ $wro->url }}" target="_blank">{{ $wro->url }}</a>
      </p>