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
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">

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
        /* ── Global typography ── */
        :root {
            --brand:        #605ca8;
            --brand-dark:   #4e4a90;
            --brand-light:  #f0eef9;
            --sidebar-w:    260px;
        }

        body,
        .nav-sidebar .nav-link p,
        .brand-text,
        .dropdown-item,
        .navbar-nav .nav-link {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        /* ── Page background ── */
        body.sidebar-mini {
            background-color: #f5f4fb;
        }

        /* ── Content wrapper ── */
        .content-wrapper {
            background-color: #f5f4fb;
            min-height: calc(100vh - 57px);
        }

        /* ── Sidebar height fix ── */
        .main-sidebar {
            height: 100% !important;
        }

        /* ── Cards inside content ── */
        .card {
            border: none;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(96,92,168,.08), 0 4px 16px rgba(96,92,168,.07);
        }

        .card-header {
            border-bottom: 1px solid rgba(96,92,168,.1);
            background: #fff;
            border-radius: 12px 12px 0 0 !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            font-size: 14px;
            color: #1e1b3a;
        }

        /* ── Buttons global ── */
        .btn-primary {
            background-color: var(--brand) !important;
            border-color: var(--brand) !important;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-weight: 600;
            border-radius: 8px;
        }
        .btn-primary:hover {
            background-color: var(--brand-dark) !important;
            border-color: var(--brand-dark) !important;
        }

        /* ── Tables ── */
        .table thead th {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12px;
            font-weight: 700;
            letter-spacing: .04em;
            text-transform: uppercase;
            color: #7874b0;
            border-bottom: 2px solid #e8e6f8;
            background: #faf9ff;
        }

        .table td {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 13.5px;
            color: #2d2b4e;
            vertical-align: middle;
        }

        /* ── Breadcrumb ── */
        .content-header h1 {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 18px;
            font-weight: 700;
            color: #1e1b3a;
        }

        .breadcrumb-item,
        .breadcrumb-item a {
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 12.5px;
            color: #8b87b5;
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
    <script></script>
    
    @yield('scripts')
</body>
</html>