<!DOCTYPE html>
<html lang="en">
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<title>Forgot Password - Job Order & Work Request Order</title>
	<link rel="stylesheet" href="{{ asset('assets/login/css/bootstrap4.3.1.css') }}">
	<link rel="stylesheet" type="text/css" href="{{ asset('assets/login/css/my-login.css') }}">
</head>
<body class="my-login-page">
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-md-center h-100">
				<div class="card-wrapper">
					<div class="text-center">
						<p></p>
						<img src="{{ asset('assets/login/img/logo.png') }}" alt="logo" class="img" height="60px">
						<p></p>
					</div>
					<div class="card fat">
						<div class="card-body">
							<h5 class="card-title text-center">Job and Work Order Request - Forgot Password</h5>
							@include('includes.error')
							@include('includes.success')
							<form action="" method="POST" class="my-login-validation" autocomplete="off">
								@csrf
								<div class="form-group">
									<label for="email">E-Mail Address</label>
									<input id="email" type="email" class="form-control" name="email" required autofocus>
									<div class="invalid-feedback">
										Email is invalid
									</div>
								</div>

								<div class="form-group">
									<div class="custom-checkbox custom-control">
										<input type="checkbox" name="remember" id="remember" class="custom-control-input">
									</div>
								</div>
								<div class="form-group m-0">
									<button type="submit" class="btn btn-primary btn-block">
										Submit
									</button>
								</div>

							</form>
						</div>
					</div>
					<div class="footer">
						Copyright &copy; {{ date('Y') }} &mdash; Brookside Group of Companies 
					</div>
				</div>
			</div>
		</div>
	</section>
	<script src="{{ asset('assets/login/js/jquery3.3.1.js') }}"></script>
	<script src="{{ asset('assets/login/js/popper.js') }}"></script>
	<script src="{{ asset('assets/login/js/bootstrap4.3.1.js') }}"></script>
	<script src="{{ asset('assets/login/js/my-login.js') }}"></script>
</body>
</html>
