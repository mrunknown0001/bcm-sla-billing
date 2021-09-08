      <p>
        <a href="{{ url()->previous() }}" class="btn btn-primary"><i class="pe-7s-back"></i> Back</a>
      </p>

      <p>Job Order #: {{ $jo->jo_no }}</p>
      <p>Requestor: {{ $jo->user->first_name . ' ' . $jo->user->last_name }}</p>
      <p>Date Requested: {{ date('F j, Y', strtotime($jo->date_of_request)) }}</p>
      <p>Date Needed: {{ date('F j, Y', strtotime($jo->date_needed)) }}</p>
      <p>Project Bldg. #: {{ $jo->project_bldg_no }}</p>
      <p>Remarks: {{ $jo->remarks }}</p>
      @if($jo->cost != 0)
        <p>Estimated Cost: {{ number_format($jo->cost) }}</p>
      @endif
      <p class="text-center"><strong>Items</strong></p>
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
      <p>Project Description/Purpose</p>
      <p><i>{{ $jo->description }}</i></p>
      <p>Attachment: @if($jo->attachment != NULL)<a href="{{ route('download.jo.attachement', ['id' => $jo->id]) }}">{{ $jo->jo_no }}</a>@else <i>No Attachment</i> @endif</p>