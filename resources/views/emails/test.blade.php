<!doctype html>
<html lang="en">
<head>
	<style type="text/css">
		body, p, h3 {
		  font-family: Arial, Helvetica, sans-serif;
		}
	</style>
</head>
<body>
	<div style="padding-left: 10px; padding-right: 10px; ">
		<p style="text-align: center;">
			<img src="{{ asset('/assets/login/img/logo-mail.png') }}" alt="Brookside Group of Companies">
		</p>
		<h3 style="text-align: center;">Brookside Job and Work Request Order System</h3>

		<p>Hi <b>{{ $approver }}</b>!</p>

		<p>Good day!</p>

		<p>&nbsp;Job Order <b><u><a href="{{ route($route,['id' => $jo_id]) }}">{{ $jo_no }}</a></u></b>  was already created and requested for your approval.
		Please send message if you have any comments or clarifications.</p>

		<p>Thank you and regards,</p>

		<p>
			<b>{{ $requestor }}</b>
			<br>
			<i>{{ $requestor_designation }}</i>
		</p>
	</div>
</body>
</html>