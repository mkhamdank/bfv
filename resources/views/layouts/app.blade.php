<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- CSRF Token -->
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <!-- Icon -->
    <link rel="icon" href="{{ url('img/bridgesmall.png') }}">

    <!-- Title -->
    <title>
        @if (isset($title))
            {{ $title }}
        @else
            Bridge for Vendor
        @endif
    </title>

    <!-- Fonts -->
    <link rel="dns-prefetch" href="//fonts.gstatic.com">
    <link href="https://fonts.bunny.net/css?family=Nunito" rel="stylesheet">

    <!-- Styles -->
    <link href="{{ url('adminlte/plugins/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/dist/css/adminlte.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/overlayScrollbars/css/OverlayScrollbars.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/daterangepicker/daterangepicker.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/summernote/summernote-bs4.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/toastr/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ url('adminlte/plugins/select2/css/select2.min.css') }}" rel="stylesheet">

    @yield('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('adminlte/dist/js/adminlte.min.js') }}"></script>

</head>
<body>
    <div id="app">        
        @yield('content')
    </div>

    <!-- Scripts -->
    <script>
    
    
    </script>

    @yield('scripts')
</body>
</html>
