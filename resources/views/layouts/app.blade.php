<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="author" content="Muhamad Nauval Azhar">
    <meta name="viewport" content="width=device-width,initial-scale=1">
    <meta name="description" content="This is a login page template based on Bootstrap 5">
    <title>WBSAIS</title>

    <link rel="stylesheet" href="{{ asset('assets/bs/css/bootstrap.min.css') }}" />
    <link rel="stylesheet" href="{{ asset('assets/bs/boxicons.min.css') }}" />
    <script src="{{ asset('assets/js/jquery-3.5.1.js')}}"></script>
    <script src="{{ asset('assets/js/popper.min.js') }}"></script>
    <script src="{{ asset('assets/bs/js/bootstrap.bundle.min.js') }}"></script>
    <link rel="stylesheet" href="{{ asset('assets/css/app.css') }}" />
</head>

<body>
    <div id="app">
        <main class="py-4">
            @yield('content')
        </main>
    </div>
</body>

</html>