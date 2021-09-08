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

		<p>Hi <b>{{ $name }}</b>!</p>

		<p>Good day!</p>

		<p>Please click the link below to reset password and input the Verification Code after.</p>
		<p>Password Reset Link: {{ $link }}</p>
		<p>Verification Code: <b>{{ $code }}</b></p>
		<br>
		<br>

		<p>Thank you and regards,</p>

		<p><b>System Administrator</b></p>
	</div>
</body>
</html>