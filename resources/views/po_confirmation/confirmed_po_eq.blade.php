<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <title>PT. YMPI - CONTROL DELIVERY</title>

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/animate/animate.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/css-hamburgers/hamburgers.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/animsition/css/animsition.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/select2/select2.min.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('vendor/daterangepicker/daterangepicker.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('css/util.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('css/main.css') }}">

    <link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}">

    <link rel="stylesheet" href="{{ url('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">

    <link rel="stylesheet"
        href="{{ url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">

    <style type="text/css">
        :root {
            --page-bg: #eef2f7;
            --surface: #ffffff;
            --surface-soft: #f8fafc;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --border: #dfe7f0;

            --navy: #163a67;

            --purple: #7e568b;
            --purple-dark: #684574;

            --green: #16a34a;
            --green-dark: #15803d;
            --green-soft: #ecfdf5;

            --shadow: 0 14px 34px rgba(15, 23, 42, 0.10);
        }

        * {
            box-sizing: border-box;
        }

        html,
        body {
            width: 100%;
            min-height: 100%;
            margin: 0;
            padding: 0;

            background-color: var(--page-bg);
            color: var(--text-main);
        }

        body {
            overflow-x: hidden;
        }

        .container-contact100 {
            display: flex;
            align-items: flex-start !important;
            justify-content: center;

            width: 100%;
            min-height: 100vh;

            padding: 24px 16px;

            background-color: var(--page-bg) !important;
        }

        .wrap-contact100 {
            overflow: hidden;

            width: 100%;
            max-width: 1000px;

            margin: 0 auto !important;
            padding: 0 !important;

            border: 1px solid var(--border);
            border-radius: 20px;

            background-color: var(--surface) !important;

            box-shadow: var(--shadow);
        }


        /* =========================================================
           HEADER
        ========================================================== */

        .delivery-hero {
            position: relative;
            overflow: hidden;

            display: flex;
            align-items: center;
            justify-content: space-between;

            gap: 20px;

            min-height: 130px;

            padding: 26px 30px;

            background-color: var(--navy);

            color: #ffffff;
        }

        .delivery-hero::before,
        .delivery-hero::after {
            content: "";

            position: absolute;

            border-radius: 50%;

            pointer-events: none;
        }

        .delivery-hero::before {
            width: 220px;
            height: 220px;

            top: -90px;
            right: -60px;

            background-color: rgba(255, 255, 255, 0.06);
        }

        .delivery-hero::after {
            width: 130px;
            height: 130px;

            top: -28px;
            right: 80px;

            background-color: rgba(255, 255, 255, 0.04);
        }

        .delivery-hero-copy,
        .delivery-hero-side {
            position: relative;
            z-index: 2;
        }

        .delivery-kicker {
            display: inline-flex;
            align-items: center;

            gap: 7px;

            margin-bottom: 8px;

            color: rgba(255, 255, 255, 0.88);

            font-size: 11px;
            line-height: 16px;

            font-weight: 800;

            letter-spacing: 1.2px;

            text-transform: uppercase;
        }

        .delivery-kicker i {
            font-size: 13px;
        }

        .delivery-hero h1 {
            margin: 0;

            color: #ffffff;

            font-size: 30px;
            line-height: 38px;

            font-weight: 800;

            letter-spacing: -0.3px;
        }

        .delivery-hero h1 small {
            display: block;

            margin-top: 4px;

            color: #dcecff;

            font-size: 16px;
            line-height: 23px;

            font-weight: 700;
        }

        .delivery-hero p {
            margin: 8px 0 0 0;

            color: rgba(255, 255, 255, 0.80);

            font-size: 13px;
            line-height: 21px;

            font-weight: 600;
        }

        .delivery-badge {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            min-height: 40px;

            padding: 9px 15px;

            border: 1px solid rgba(255, 255, 255, 0.25);
            border-radius: 10px;

            background-color: rgba(255, 255, 255, 0.10);

            color: #ffffff;

            font-size: 12px;
            line-height: 18px;

            font-weight: 800;

            white-space: nowrap;
        }


        /* =========================================================
           CONTENT
        ========================================================== */

        .delivery-body {
            padding: 34px;

            background-color: var(--surface);
        }

        .success-wrapper {
            max-width: 680px;

            margin: 0 auto;

            text-align: center;
        }

        .success-card {
            position: relative;

            overflow: hidden;

            padding: 38px 30px;

            border: 1px solid #bbf7d0;
            border-radius: 18px;

            background-color: var(--green-soft);
        }

        .success-icon-wrapper {
            display: flex;
            align-items: center;
            justify-content: center;

            width: 78px;
            height: 78px;

            margin: 0 auto 20px auto;

            border: 7px solid #dcfce7;
            border-radius: 50%;

            background-color: var(--green);

            color: #ffffff;

            font-size: 31px;

            box-shadow: 0 10px 24px rgba(22, 163, 74, 0.22);
        }

        .success-title {
            margin: 0;

            color: #166534;

            font-size: 25px;
            line-height: 34px;

            font-weight: 800;
        }

        .success-message {
            margin: 10px auto 0 auto;

            max-width: 520px;

            color: #475569;

            font-size: 14px;
            line-height: 23px;

            font-weight: 600;
        }

        .success-divider {
            width: 54px;
            height: 4px;

            margin: 20px auto;

            border-radius: 999px;

            background-color: #86efac;
        }

        .success-information {
            display: inline-flex;
            align-items: center;
            justify-content: center;

            gap: 7px;

            padding: 8px 14px;

            border: 1px solid #bbf7d0;
            border-radius: 999px;

            background-color: #ffffff;

            color: #15803d;

            font-size: 11px;
            line-height: 17px;

            font-weight: 800;
        }

        .success-information i {
            font-size: 13px;
        }


        /* =========================================================
           FOOTER INFORMATION
        ========================================================== */

        .delivery-footer-info {
            margin-top: 18px;

            padding: 14px 16px;

            border: 1px solid var(--border);
            border-radius: 12px;

            background-color: var(--surface-soft);

            color: var(--text-muted);

            font-size: 12px;
            line-height: 20px;

            text-align: center;
        }

        .delivery-footer-info strong {
            color: var(--text-main);
        }


        /* =========================================================
           LOADING
        ========================================================== */

        #loading {
            background-color: rgba(15, 23, 42, 0.60) !important;

            opacity: 1 !important;

            backdrop-filter: blur(2px);
        }

        #loading p {
            top: 50% !important;
            left: 50% !important;

            width: 100%;

            margin: 0;

            transform: translate(-50%, -50%);

            text-align: center;
        }

        #loading p span {
            display: inline-block;

            padding: 18px 24px;

            border-radius: 14px;

            background-color: rgba(15, 23, 42, 0.92);

            color: #ffffff;

            font-size: 20px !important;
            line-height: 29px;

            font-weight: 800;

            box-shadow: 0 14px 32px rgba(15, 23, 42, 0.30);
        }

        #loading p span i {
            margin-left: 8px;
        }


        /* =========================================================
           RESPONSIVE
        ========================================================== */

        @media (max-width: 991px) {
            .wrap-contact100 {
                width: 100% !important;

                margin-left: 0 !important;
            }

            .delivery-hero {
                align-items: flex-start;

                flex-direction: column;
            }
        }

        @media (max-width: 767px) {
            .container-contact100 {
                padding: 10px;
            }

            .wrap-contact100 {
                border-radius: 15px;
            }

            .delivery-hero {
                min-height: 0;

                padding: 20px;

                border-radius: 0;
            }

            .delivery-hero h1 {
                font-size: 24px;
                line-height: 31px;
            }

            .delivery-hero h1 small {
                font-size: 14px;
            }

            .delivery-body {
                padding: 20px 14px;
            }

            .success-card {
                padding: 30px 18px;
            }

            .success-icon-wrapper {
                width: 66px;
                height: 66px;

                font-size: 26px;
            }

            .success-title {
                font-size: 21px;
                line-height: 29px;
            }

            .success-message {
                font-size: 13px;
            }
        }
    </style>
</head>

<body>

    <meta name="csrf-token" content="{{ csrf_token() }}">


    <!-- =========================================================
         LOADING
    ========================================================== -->

    <div id="loading"
        style="
            margin:0;
            padding:0;
            position:fixed;
            right:0;
            top:0;
            width:100%;
            height:100%;
            z-index:30001;
            display:none;
        ">
        <p style="
                position:absolute;
                color:white;
            ">
            <span>
                Loading, please wait . . .

                <i class="fa fa-spin fa-refresh"></i>
            </span>
        </p>
    </div>


    <!-- =========================================================
         MAIN WRAPPER
    ========================================================== -->

    <div class="container-contact100">

        <div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2">

            <!-- =================================================
                 HEADER
            ================================================== -->

            <div class="delivery-hero">

                <div class="delivery-hero-copy">

                    <span class="delivery-kicker">
                        <i class="fa fa-truck"></i>

                        CONTROL DELIVERY
                    </span>

                    <h1>
                        PO Confirmation

                        <small>
                            PT. Yamaha Musical Products Indonesia
                        </small>
                    </h1>

                    <p>
                        Purchase Order delivery confirmation has been successfully submitted.
                    </p>

                </div>


                <div class="delivery-hero-side">

                    <div class="delivery-badge">
                        <i class="fa fa-check-circle"></i>

                        Confirmation Completed
                    </div>

                </div>

            </div>


            <!-- =================================================
                 CONTENT
            ================================================== -->

            <div class="delivery-body">

                <div id="already_filled" style="width:100%;">

                    <div class="success-wrapper">

                        <div class="success-card">

                            <div class="success-icon-wrapper">
                                <i class="fa fa-check"></i>
                            </div>

                            <h2 class="success-title">
                                Thank You for Your Response
                            </h2>

                            <p class="success-message">
                                Your PO confirmation has been successfully received.
                                Our team will review the submitted information as soon as possible.
                            </p>

                            <div class="success-divider"></div>

                            <div class="success-information">
                                <i class="fa fa-info-circle"></i>

                                Confirmation successfully recorded
                            </div>

                        </div>


                        <div class="delivery-footer-info">

                            <i class="fa fa-envelope-o"></i>

                            &nbsp;

                            This confirmation has been recorded by the
                            <strong>
                                PT. YMPI Control Delivery System
                            </strong>.

                        </div>

                    </div>

                </div>

            </div>

        </div>

    </div>


    <!-- =========================================================
         SCRIPT
    ========================================================== -->

    <script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>

    <script src="{{ url('vendor/animsition/js/animsition.min.js') }}"></script>

    <script src="{{ url('vendor/bootstrap/js/popper.js') }}"></script>

    <script src="{{ url('vendor/bootstrap/js/bootstrap.min.js') }}"></script>

    <script src="{{ url('vendor/select2/select2.min.js') }}"></script>

    <script src="{{ url('vendor/daterangepicker/moment.min.js') }}"></script>

    <script src="{{ url('vendor/daterangepicker/daterangepicker.js') }}"></script>

    <script src="{{ url('vendor/countdowntime/countdowntime.js') }}"></script>

    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>

    <script src="{{ url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>

    <script src="{{ url('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>

    <script src="{{ url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });


        jQuery(document).ready(function() {

        });


        function openSuccessGritter(title, message) {

            jQuery.gritter.add({

                title: title,

                text: message,

                class_name: 'growl-success',

                image: '{{ url('images/image-screen.png') }}',

                sticky: false,

                time: '2000'

            });

        }


        function openErrorGritter(title, message) {

            jQuery.gritter.add({

                title: title,

                text: message,

                class_name: 'growl-danger',

                image: '{{ url('images/image-stop.png') }}',

                sticky: false,

                time: '2000'

            });

        }
    </script>

</body>

</html>
