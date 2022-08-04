<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="author" content="Muhamad Nauval Azhar">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
	<title>Login Page</title>

	<link rel="stylesheet" href="{{ asset('assets/bs/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/bs/boxicons.min.css') }}" />
	<script src="{{ asset('assets/js/jquery-3.5.1.js')}}"></script>
	<script src="{{ asset('assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('assets/bs/js/bootstrap.bundle.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
</head>

<body>
	<section class="h-100">
		<div class="container h-100">
			<div class="row justify-content-sm-center h-100">
				<div class="col-xxl-4 col-xl-5 col-lg-5 col-md-7 col-sm-9">
					<div class="text-center my-5">
						<h1 class="fs-4 card-title fw-bold mb-3">Drugstores</h1>
						<p class="fs-6 card-text mt-0">Sales and Inventory Management</p>
					</div>
					<div class="card shadow-lg">
						<div class="card-body p-5">
							@if(Session::has('reset-msg'))
							<div class="alert alert-success alert-dismissible fade show" role="alert">
								<strong>Notice</strong>
								<p style="font-size: 13px;">{{ Session::get('reset-msg')}}</p>
								<button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
							</div>
							@endif
							<h1 class="fs-4 card-title fw-bold mb-4">Login</h1>
							<form method="POST" autocomplete="off" id="login-form">
								@csrf
								<div class="mb-3">
									<label for="validationCustom01" class="form-label text-muted">Email address</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon"><i class="bx bx-user"></i></div>
										<input type="email" name="email" placeholder="Email address" id="validationCustom01" class="form-control" required />
									</div>
									<span class="error-text error_email"></span>
								</div>

								<div class="mb-3">
									<div class="mb-2 w-100">
										<label class="text-muted" for="password">Password</label>
										<a href="{{ route('password.request')}}" style="font-size: 13px;" class="float-end">
											Forgot Password?
										</a>
									</div>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon1"><i class="bx bx-lock"></i></div>
										<input type="password" name="password" placeholder="Password" id="validationCustom02" class="form-control" required />
									</div>
									<span class="error-text error_password"></span>
								</div>

								<div class="d-flex align-items-center">
									<div class="form-check">
										<input type="checkbox" name="remember" id="remember" class="form-check-input">
										<label for="remember" class="form-check-label">Remember Me</label>
									</div>
									<input type="submit" class="c-btn ms-auto w-25" value="Login">
									</input>
								</div>
							</form>
						</div>
						<div class="card-footer py-3 border-0">
							<div class="text-center">
								Don't have an account? <a href="{{ route('register.view') }}" class="text-dark">Create One</a>
							</div>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						<p style="font-size: 12px">Copyright &copy; 2021-2022 &mdash; LSPU-SPCC </p>
					</div>
				</div>
			</div>
		</div>
	</section>
	<!-- Error modal -->
	<div class="modal fade" id="alert-modal" tabindex="-1" aria-labelledby="modal-label" aria-hidden="true">
		<div class="modal-dialog modal-dialog-centered">
			<div class="modal-content border-0">
				<div class=" flex-alert-container">
					<div class="flex-alert-header p-5 rounded-left">
						<i class="bx bx-x-circle mx-1 text-danger" style="font-size: 5em;"></i>
					</div>
					<div class="flex-alert-body bg-white p-5">
						<h1 class="fs-3 card-title">Login Error</h1>
						<span id="msg-error" style="font-size: 13px;" class="text-muted">Error</span>
					</div>
				</div>
			</div>
		</div>
	</div>
	<!-- End -->
	<script src="./assets/js/login.js"></script>
	<script type="text/javascript">
		$(function() {
			$('#login-form').on('submit', function(e) {
				e.preventDefault()
				$.ajax({
					url: "{{ route('login.auth') }} ",
					type: 'post',
					data: new FormData(this),
					dataType: 'json',
					processData: false,
					contentType: false,
					beforeSend: function() {
						$('.error-text').text('');
						$('#login-form :input').prop("disabled", true);
					},
					success: function(data) {
						$('#login-form :input').prop("disabled", false);
						if (data.status == -1) {
							window.location.replace("{{ route('login') }}");
						}
						if (data.status == 0) {
							$('#msg-error').text(data.msg);
							$('#alert-modal').modal('toggle');

						} else {
							$('#login-form')[0].reset();
							window.location.replace("{{ route('user.dash') }}");
						}
					}
				});
			});
		});
	</script>
</body>

</html>