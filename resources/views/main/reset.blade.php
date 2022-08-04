<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="author" content="Muhamad Nauval Azhar">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
	<title>Bootstrap 5 Login Page</title>

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
							<h1 class="fs-4 card-title fw-bold mb-4">Reset Password</h1>
							<form method="POST" class="needs-validation" novalidate="" autocomplete="off">
								<div class="mb-3">
									<label for="validationCustom01" class="form-label text-muted">New password</label>
									<div class="input-group has-validation">
										<div class="input-group-text" id="basicAddon"><i class="bx bxs-lock-alt"></i></div>
										<input type="password" placeholder="Your password" id="validationCustom01" class="form-control" required />
										<div class="invalid-feedback">Field is required</div>
									</div>
								</div>

								<div class="mb-3">
									<label for="validationCustom01" class="form-label text-muted">Confirm assword</label>
									<div class="input-group has-validation">
										<div class="input-group-text" id="basicAddon"><i class="bx bxs-lock-alt"></i></div>
										<input type="password" placeholder="Your password" id="validationCustom01" class="form-control" required />
										<div class="invalid-feedback">Field is required</div>
									</div>
								</div>

								<div class="d-flex align-items-center">
									<div class="form-check">
										<input type="checkbox" name="logout_devices" id="logout" class="form-check-input">
										<label for="logout" class="form-check-label">Logout all devices</label>
									</div>
									<button type="submit" class="c-btn ms-auto">
										Reset Password
									</button>
								</div>
							</form>
						</div>
					</div>
					<div class="text-center mt-5 text-muted">
						Copyright &copy; 2017-2021 &mdash; LSPU-SPCC
					</div>
				</div>
			</div>
		</div>
	</section>

	<script src="js/login.js"></script>
</body>

</html>