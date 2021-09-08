@extends('layouts.mail')

@section('content')

	<p>Hi <b>{{ $next_approver }}</b>!</p>

	<p>Good day!</p>

	<p style="text-align: justify-all;">Work Request Order <b><u><a href="{{ route($route,['id' => $wro_id]) }}">{{ $wro_no }}</a></u></b>  was already created and requested for your approval.
	<br>
	Please send message if you have any comments or clarifications.</p>

	<p>Thank you and regards,</p>

	<p>
		<b>{{ $prev_approver }}</b>
		<br>
		<i>{{ $prev_approver_designation }}</i>
	</p>
@endsection