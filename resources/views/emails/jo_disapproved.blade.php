@extends('layouts.mail')

@section('content')
	<p>Hi <b>{{ $requestor }}</b>!</p>

	<p>Good day!</p>

	<p style="text-align: justify-all;">
		Job Order <b><u><a href="{{ route($route,['id' => $jo_id]) }}">{{ $jo_no }}</a></u></b> was disapproved, please see reason(s) on the Job Order for reference.
	</p>

	<p>Thank you and regards,</p>

	<p>
		<b>{{ $approver }}</b>
		<br>
		<i>{{ $approver_designation }}</i>
	</p>


@endsection