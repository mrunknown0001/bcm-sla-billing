      <p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="pe-7s-back"></i> Back</a>
      </p>

      <p>Work Request Order #: {{ $wro->wr_no }}</p>
      <p>Requestor: {{ $wro->user->first_name . ' ' . $wro->user->last_name }}</p>
      <p>Date Requested: {{ date('F j, Y', strtotime($wro->date_of_request)) }}</p>
      <p>Date Needed: {{ date('F j, Y', strtotime($wro->date_needed)) }}</p>
      <p>Project Bldg. #: {{ $wro->project_bldg_no }}</p>

      <p>Project Description/Purpose</p>
      <p><i>{{ $wro->description }}</i></p>
      <br>
      <p>Project Justification</p>
      <p><i>{{ $wro->justification }}</i></p>
      <p>Attachment: @if($wro->attachment != NULL) <a href="{{ route('download.wro.attachement', ['id' => $wro->id]) }}">{{ $wro->wr_no }} @else <i>No Attachment</i> @endif</a>
      </p>