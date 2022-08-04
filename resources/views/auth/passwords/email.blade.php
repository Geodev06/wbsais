@extends('layouts.app')

@section('content')
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
                        <h1 class="fs-4 card-title fw-bold mb-4">Forgot Password</h1>
                        @if (session('status'))
                        <div class="alert alert-success" role="alert">
                            {{ session('status') }}
                        </div>
                        @endif

                        <form method="POST" action="{{ route('password.email') }}">
                            @csrf
                            <div class="mb-3">
                                <label for="validationCustom01" class="form-label text-muted">{{ __('Email Address') }}</label>
                                <div class="input-group">
                                    <div class="input-group-text" id="basicAddon"><i class="bx bxl-gmail"></i></div>
                                    <input type="email" name="email" placeholder="Email address" id="validationCustom01" class="form-control @error('email') is-invalid @enderror" value="{{ old('email') }}" required autocomplete="email" autofocus />
                                    @error('email')
                                    <span class="invalid-feedback" role="alert">
                                        <p><i class="bx bx-error"></i> {{ $message }}</p>
                                    </span>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex align-items-center float-left">
                                <button type="submit" class="c-btn ms-auto w-100">
                                    {{ __('Send Password Reset Link') }}
                                </button>
                            </div>
                        </form>
                    </div>
                    <div class="card-footer py-3 border-0">
                        <div class="text-center">
                            Remember your password? <a href="{{ route('wbsais.login')}}" class="text-dark">Login</a>
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
@endsection