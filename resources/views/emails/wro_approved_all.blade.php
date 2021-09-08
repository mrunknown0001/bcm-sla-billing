@extends('layouts.mail')

@section('content')

	<p>Dear All!</p>

	<p>Good day!</p>

	<p style="text-align: justify-all;">Work Request Order <b><u>{{ $wro_no }}</u></b>  was approved. You may proceed on the next step.</p>

	<p>Thank you and regards,</p>

	<p>
		<b>{{ $approver }}</b>
		<br>
		<i>{{ $approver_designation }}</i>
	</p>
@endsection