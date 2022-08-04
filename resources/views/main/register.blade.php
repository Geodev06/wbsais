<!DOCTYPE html>
<html lang="en">

<head>
	<meta charset="utf-8">
	<meta name="author" content="Muhamad Nauval Azhar">
	<meta name="viewport" content="width=device-width,initial-scale=1">
	<meta name="description" content="This is a login page template based on Bootstrap 5">
	<title>Registration Page</title>

	<link rel="stylesheet" href="{{ asset('assets/bs/css/bootstrap.min.css') }}" />
	<link rel="stylesheet" href="{{ asset('assets/bs/boxicons.min.css') }}" />
	<script src="{{ asset('assets/js/jquery-3.5.1.js')}}"></script>
	<script src="{{ asset('assets/js/popper.min.js') }}"></script>
	<script src="{{ asset('assets/bs/js/bootstrap.bundle.min.js') }}"></script>
	<link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
	<style>
		.msg-container {
			height: auto;
			width: auto;
			padding: 1.5em;
			background: linear-gradient(to top left, #660066 0%, #cc0099 100%);
			border-radius: 12px;
			display: grid;
			justify-content: center;
			align-items: center;
			color: white;
		}

		.c-p {
			text-align: left;
			font-size: 13px;
		}
	</style>

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
						<div class="card-body p-5" id="content">
							<h1 class="fs-4 card-title fw-bold mb-4">Register</h1>
							<hr>
							<h1 class="fs-6 card-title fw-bold mb-4">Basic information</h1>
							<form class="" autocomplete="off" id="reg-form">
								@csrf
								<div class=" mb-3">
									<label for="validationCustom00" class="form-label text-muted">Name</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon1"><i class="bx bxs-user-detail"></i></div>
										<input type="text" name="name" placeholder="Your name" id="validationCustom00" class="form-control" required />
									</div>
									<span class="error-text error_name"></span>
								</div>

								<div class="mb-3">
									<label for="validationCustom01" class="form-label text-muted">Store name</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon2"><i class="bx bxs-store"></i></div>
										<input type="text" name="store_name" placeholder="Your store name" id="validationCustom01" class="form-control" required />
									</div>
									<span class="error-text error_store_name"></span>
								</div>
								<hr>
								<h1 class="fs-6 card-title fw-bold mb-4">Contact</h1>
								<div class="mb-3">
									<label for="validationCustom02" class="form-label text-muted">Email address</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon3"><i class="bx bxl-gmail"></i></div>
										<input type="email" name="email" placeholder="Your email address" id="validationCustom02" class="form-control" required />

									</div>
									<span class="error-text error_email"></span>
								</div>

								<div class="mb-3">
									<label for="validationCustom03" class="form-label text-muted">Password</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon4"><i class="bx bxs-lock-alt"></i></div>
										<input type="password" name="password" placeholder="Your password" id="validationCustom03" class="form-control" required />
										<div class="input-group-text" id="basicAddon4"><i class="bx bx-hide" id="pwdhide"></i></div>

									</div>
									<span class="error-text error_password"></span>
								</div>

								<div class="mb-3">
									<label for="validationCustom04" class="form-label text-muted">Confirm assword</label>
									<div class="input-group">
										<div class="input-group-text" id="basicAddon5"><i class="bx bxs-lock-alt"></i></div>
										<input type="password" name="password_confirmation" placeholder="Your password" id="validationCustom04" class="form-control" required />

									</div>
									<span class="error-text error_password"></span>
								</div>

								<div class="align-items-center d-flex">
									<button type="submit" class="c-btn w-25 ms-auto">
										Register
									</button>
								</div>
							</form>
						</div>
						<div class="card-footer py-2 mb-3 border-0">
							<div class="text-center">
								Already have an account? <a href="{{route('wbsais.login')}}" class="text-dark">Login</a>
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
	<script type="text/javascript">
		$(function() {
			$('#reg-form').on('submit', function(e) {
				e.preventDefault()
				$.ajax({
					url: "{{ route('register.store') }}",
					type: 'post',
					data: new FormData(this),
					dataType: 'json',
					processData: false,
					contentType: false,
					beforeSend: function() {
						$('.error-text').text('');
						$('#reg-form :input').prop("disabled", true);
					},
					success: function(data) {
						$('#reg-form :input').prop("disabled", false);
						if (data.status == 0) {
							$.each(data.error, function(prefix, val) {
								$('.error_' + prefix).text(val[0]);
							})
						} else {
							$('#reg-form')[0].reset();
							let alert = "<div class='msg-container'><strong class='fs-3 fw-bold'> Account has been successfully created!</strong> <p class='c-p'>" +
								data.msg + "</p>";
							$('#content').html(alert);
						}
					}

				});
			});

			$('#pwdhide').on('click', function() {
				const pwd = document.getElementById('validationCustom03');
				const confirm_pwd = document.getElementById('validationCustom04');
				if ($('#pwdhide').hasClass('bx-hide')) {
					$('#pwdhide').removeClass('bx-hide');
					$('#pwdhide').addClass('bx-show');
					pwd.type = "text";
					confirm_pwd.type = "text";

				} else {
					$('#pwdhide').removeClass('bx-show');
					$('#pwdhide').addClass('bx-hide');
					pwd.type = "password";
					confirm_pwd.type = "password";

				}
			})
		})
	</script>
</body>

</html>