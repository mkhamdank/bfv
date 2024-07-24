<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta http-equiv="Content-Security-Policy" 
    content="
      worker-src blob:; 
      child-src blob: gap:;
      img-src 'self' blob: data:;
      default-src * 'self' 'unsafe-inline' 'unsafe-eval' data: gap: content:">

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
    <link href="{{ url('adminlte/plugins/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-bs4/css/dataTables.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-responsive/css/responsive.bootstrap4.min.css') }}">
    <link rel="stylesheet" href="{{ url('adminlte/plugins/datatables-buttons/css/buttons.bootstrap4.min.css') }}">

    @yield('styles')

    <!-- Scripts -->
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])

    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('adminlte/dist/js/adminlte.min.js') }}"></script>    
    <script src="{{ url('adminlte/plugins/jszip/jszip.js') }}"></script>
    <script src="{{ url('adminlte/plugins/select2/js/select2.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/moment/moment.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/daterangepicker/daterangepicker.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-bs4/js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-responsive/js/dataTables.responsive.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-responsive/js/responsive.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.bootstrap4.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/jszip/jszip.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/pdfmake/pdfmake.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/pdfmake/vfs_fonts.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.print.min.js') }}"></script>
    <script src="{{ url('adminlte/plugins/datatables-buttons/js/buttons.colVis.min.js') }}"></script>

    <style>
    .main-sidebar {
        height: 100% !important;
    }
    </style>
    

</head>
<body class="sidebar-mini">
    <div id="app">

        @include('../components/navbar')
        @include('../components/sidebar')


        <div class="content-wrapper">
            @yield('content')
        </div>

    </div>

    <!-- Scripts -->
    <script>
        $("#toggle-sidebar").ControlSidebar('toggle');
    </script>

    <script src="{{ url('js/notifications.js') }}" defer></script>    
    <script>        


    </script>
    
    @yield('scripts')
</body>
</html>
