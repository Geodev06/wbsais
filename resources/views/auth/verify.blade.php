<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>Web based Sales and Inventory</title>

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
                            <h1 class="fs-4 card-title fw-bold mb-4">
                                Let's verify your email first :)
                            </h1>

                            <div>
                                @if (session('resent'))
                                <div class="alert alert-success" role="alert">
                                    {{ __('A fresh verification link has been sent to your email address.') }}
                                </div>
                                @endif

                                {{ __('Before proceeding, please check your email for a verification link.') }}
                                {{ __('If you did not receive the email') }},
                                <form class="d-inline" method="POST" action="{{ route('verification.resend') }}">
                                    @csrf
                                    <button type="submit" class="btn btn-link p-0 m-0 align-baseline">{{ __('click here to request another') }}</button>.
                                </form>
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
</body>

</html>