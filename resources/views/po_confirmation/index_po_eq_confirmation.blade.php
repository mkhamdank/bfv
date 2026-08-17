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
            --surface: #fff;
            --surface-soft: #f8fafc;
            --text: #1e293b;
            --text-main: #1e293b;
            --text-muted: #64748b;
            --muted: #64748b;
            --border: #dfe7f0;
            --navy: #163a67;
            --purple: #7e568b;
            --purple-dark: #684574;
            --purple-soft: #f4eaf6;
            --green: #16a34a;
            --green-dark: #15803d;
            --green-soft: #ecfdf5;
            --orange: #f59e0b;
            --shadow: 0 14px 34px rgba(15, 23, 42, .10)
        }

        * {
            box-sizing: border-box
        }

        html,
        body {
            min-height: 100%;
            margin: 0;
            background: var(--page-bg);
            color: var(--text)
        }

        body {
            overflow-x: hidden
        }

        .container-contact100 {
            min-height: 100vh;
            padding: 22px 16px 30px;
            align-items: flex-start !important;
            background: var(--page-bg) !important
        }

        .wrap-contact100 {
            overflow: hidden;
            padding: 0 !important;
            border: 1px solid var(--border);
            border-radius: 20px;
            background: #fff !important;
            box-shadow: var(--shadow)
        }

        .delivery-hero {
            position: relative;
            overflow: hidden;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 18px;
            min-height: 122px;
            padding: 24px 28px;
            background: var(--navy);
            color: #fff
        }

        .delivery-hero:before,
        .delivery-hero:after {
            content: "";
            position: absolute;
            border-radius: 50%;
            background: rgba(255, 255, 255, .06);
            pointer-events: none
        }

        .delivery-hero:before {
            width: 210px;
            height: 210px;
            top: -88px;
            right: -58px
        }

        .delivery-hero:after {
            width: 125px;
            height: 125px;
            top: -22px;
            right: 75px;
            background: rgba(255, 255, 255, .04)
        }

        .delivery-hero-copy,
        .delivery-hero-side {
            position: relative;
            z-index: 1
        }

        .delivery-kicker {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            margin-bottom: 7px;
            color: rgba(255, 255, 255, .88);
            font-size: 11px;
            font-weight: 800;
            letter-spacing: 1.2px;
            text-transform: uppercase
        }

        .delivery-hero h1 {
            margin: 0;
            color: #fff;
            font-size: 30px;
            line-height: 38px;
            font-weight: 800
        }

        .delivery-hero h1 small {
            display: block;
            margin-top: 3px;
            color: #dcecff;
            font-size: 16px;
            font-weight: 700
        }

        .delivery-hero p {
            margin: 8px 0 0;
            color: rgba(255, 255, 255, .82);
            font-size: 13px;
            line-height: 20px;
            font-weight: 600
        }

        .delivery-badge {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 9px 14px;
            border: 1px solid rgba(255, 255, 255, .24);
            border-radius: 10px;
            background: rgba(255, 255, 255, .10);
            color: #fff;
            font-size: 12px;
            font-weight: 800
        }

        .delivery-body {
            padding: 22px;
            background: #fff
        }

        .search-card {
            margin-bottom: 18px;
            padding: 18px;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #f8fafc
        }

        .section-title {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 12px
        }

        .section-icon {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            width: 38px;
            height: 38px;
            border-radius: 10px;
            background: var(--purple);
            color: #fff
        }

        .section-title h3 {
            margin: 0;
            font-size: 16px;
            font-weight: 800
        }

        .section-title p {
            margin: 2px 0 0;
            color: var(--muted);
            font-size: 12px
        }

        .label-input1002 {
            display: block;
            margin-bottom: 7px;
            color: var(--text);
            font-size: 13px;
            font-weight: 800
        }

        .po-search-row {
            display: flex;
            gap: 10px
        }

        .po-search-row .form-control {
            flex: 1;
            height: 42px;
            border: 1px solid #cfd9e5;
            border-radius: 9px;
            box-shadow: none
        }

        .po-search-row .form-control:focus {
            border-color: var(--purple);
            box-shadow: 0 0 0 3px rgba(126, 86, 139, .12)
        }

        #searchPo {
            min-width: 115px;
            height: 42px;
            padding: 9px 18px !important;
            border: 0;
            border-radius: 9px;
            background: #2563eb;
            color: #fff;
            font-weight: 800
        }

        .instruction-box {
            margin-bottom: 12px;
            padding: 10px 13px;
            border-left: 4px solid var(--orange);
            border-radius: 8px;
            background: #fff7ed;
            color: #9a3412;
            font-size: 12px;
            line-height: 19px;
            font-weight: 700
        }

        .table-responsive-custom {
            overflow-x: auto;
            border: 1px solid var(--border);
            border-radius: 14px;
            background: #fff;
            box-shadow: 0 7px 18px rgba(15, 23, 42, .05)
        }

        #tableDetail {
            width: 100% !important;
            min-width: 900px;
            margin: 0 !important;
            border-collapse: collapse !important;
            border: 0 !important;
            background: #fff
        }

        #tableDetail thead th {
            padding: 11px 8px !important;
            border: 1px solid var(--purple-dark) !important;
            background: var(--purple) !important;
            color: #fff !important;
            font-size: 11px;
            line-height: 17px;
            font-weight: 800;
            text-align: center;
            vertical-align: middle;
            text-transform: uppercase;
            white-space: nowrap
        }

        #tableDetail tbody td {
            padding: 9px 8px !important;
            border: 1px solid #e2e8f0 !important;
            background: #fff;
            color: var(--text);
            font-size: 12px;
            line-height: 18px;
            vertical-align: middle
        }

        #tableDetail tbody tr:nth-child(even) td {
            background: #fafbff
        }

        #tableDetail tbody tr:hover td {
            background: var(--purple-soft)
        }

        #tableDetail .form-control {
            height: 34px;
            border: 1px solid #cfd9e5;
            border-radius: 7px;
            box-shadow: none
        }

        #tableDetail input[type=checkbox] {
            width: 20px;
            height: 20px;
            cursor: pointer
        }

        .action-wrapper {
            display: flex;
            justify-content: center;
            gap: 10px;
            margin-top: 18px
        }

        .btn-action {
            min-width: 130px;
            min-height: 42px;
            padding: 10px 18px;
            border: 0;
            border-radius: 9px;
            color: #fff;
            font-size: 13px;
            font-weight: 800
        }

        .btn-cancel {
            background: #64748b
        }

        .btn-submit {
            background: var(--green)
        }
        
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

        #loading {
            background: rgba(15, 23, 42, .58) !important;
            opacity: 1 !important
        }

        #loading p {
            top: 50% !important;
            left: 50% !important;
            width: 100%;
            margin: 0;
            transform: translate(-50%, -50%);
            text-align: center
        }

        #loading span {
            display: inline-block;
            padding: 18px 24px;
            border-radius: 14px;
            background: rgba(15, 23, 42, .92);
            color: #fff;
            font-size: 20px !important;
            font-weight: 800
        }

        .dataTables_wrapper .dataTables_filter,
        .dataTables_wrapper .dataTables_info,
        .dataTables_wrapper .dataTables_paginate,
        .dataTables_wrapper .dataTables_length {
            display: none !important
        }

        @media(max-width:991px) {
            .wrap-contact100 {
                width: 100% !important;
                margin-left: 0 !important
            }

            .delivery-hero {
                align-items: flex-start;
                flex-direction: column
            }
        }

        @media(max-width:767px) {
            .container-contact100 {
                padding: 8px
            }

            .wrap-contact100 {
                border-radius: 12px
            }

            .delivery-hero {
                padding: 16px;
                min-height: auto;
                gap: 12px
            }

            .delivery-hero:before {
                width: 150px;
                height: 150px;
                top: -70px;
                right: -40px
            }

            .delivery-hero:after {
                width: 90px;
                height: 90px;
                top: -15px;
                right: 50px
            }

            .delivery-kicker {
                font-size: 10px;
                margin-bottom: 5px
            }

            .delivery-hero h1 {
                font-size: 18px;
                line-height: 24px;
                font-weight: 700
            }

            .delivery-hero h1 small {
                font-size: 13px;
                margin-top: 2px
            }

            .delivery-hero p {
                font-size: 11px;
                line-height: 16px;
                margin-top: 6px
            }

            .delivery-badge {
                padding: 7px 11px;
                font-size: 10px;
                gap: 5px
            }

            .delivery-body {
                padding: 12px
            }

            .search-card {
                margin-bottom: 14px;
                padding: 14px
            }

            .section-icon {
                width: 32px;
                height: 32px;
                font-size: 16px
            }

            .section-title h3 {
                font-size: 14px
            }

            .section-title p {
                font-size: 11px
            }

            .label-input1002 {
                font-size: 12px;
                margin-bottom: 6px
            }

            .po-search-row,
            .action-wrapper {
                flex-direction: column
            }

            .po-search-row .form-control {
                height: 38px;
                font-size: 13px
            }

            #searchPo,
            .btn-action {
                width: 100%;
                height: 38px;
                font-size: 12px;
                padding: 8px 12px !important;
                min-width: auto
            }

            .instruction-box {
                font-size: 11px;
                line-height: 16px;
                padding: 8px 10px
            }

            .table-responsive-custom {
                border-radius: 10px;
                box-shadow: 0 4px 12px rgba(15, 23, 42, .05)
            }

            #tableDetail {
                min-width: 100%;
                font-size: 11px
            }

            #tableDetail thead th {
                padding: 8px 4px !important;
                font-size: 9px;
                line-height: 14px
            }

            #tableDetail tbody td {
                padding: 7px 4px !important;
                font-size: 10px;
                line-height: 14px
            }

            #tableDetail .form-control {
                height: 30px;
                font-size: 10px
            }

            #tableDetail input[type=checkbox] {
                width: 16px;
                height: 16px
            }

            .action-wrapper {
                gap: 8px;
                margin-top: 14px
            }

            .btn-action {
                min-height: 36px;
                min-width: auto
            }

            .success-state {
                padding: 28px 16px
            }

            .success-icon {
                width: 56px;
                height: 56px;
                font-size: 24px;
                margin-bottom: 12px
            }

            .success-state h3 {
                font-size: 18px
            }

            .success-state p {
                font-size: 12px;
                margin-top: 5px
            }
        }

        @media(max-width:480px) {
            .container-contact100 {
                padding: 6px;
                min-height: 100vh
            }

            .delivery-hero {
                padding: 12px;
                gap: 8px
            }

            .delivery-hero:before {
                width: 120px;
                height: 120px;
                top: -60px;
                right: -30px
            }

            .delivery-hero:after {
                width: 70px;
                height: 70px;
                top: -10px;
                right: 40px
            }

            .delivery-kicker {
                font-size: 9px;
                margin-bottom: 4px
            }

            .delivery-hero h1 {
                font-size: 16px;
                line-height: 22px
            }

            .delivery-hero h1 small {
                font-size: 11px;
                margin-top: 1px
            }

            .delivery-hero p {
                font-size: 10px;
                line-height: 14px;
                margin-top: 4px
            }

            .delivery-badge {
                padding: 6px 10px;
                font-size: 9px
            }

            .delivery-body {
                padding: 10px
            }

            .search-card {
                margin-bottom: 12px;
                padding: 12px
            }

            .section-icon {
                width: 28px;
                height: 28px;
                font-size: 14px
            }

            .section-title h3 {
                font-size: 13px
            }

            .label-input1002 {
                font-size: 11px;
                margin-bottom: 5px
            }

            .po-search-row .form-control,
            #searchPo,
            .btn-action {
                height: 36px;
                font-size: 11px
            }

            .instruction-box {
                font-size: 10px;
                line-height: 14px;
                padding: 7px 9px
            }

            #tableDetail thead th {
                padding: 6px 3px !important;
                font-size: 8px;
                line-height: 12px
            }

            #tableDetail tbody td {
                padding: 5px 3px !important;
                font-size: 9px;
                line-height: 12px
            }

            #tableDetail .form-control {
                height: 28px;
                font-size: 9px
            }

            #tableDetail input[type=checkbox] {
                width: 14px;
                height: 14px
            }

            .success-state {
                padding: 24px 12px
            }

            .success-icon {
                width: 48px;
                height: 48px;
                font-size: 20px;
                margin-bottom: 10px
            }

            .success-state h3 {
                font-size: 16px
            }

            .success-state p {
                font-size: 11px
            }
        }
    </style>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="loading"
        style="margin:0;padding:0;position:fixed;right:0;top:0;width:100%;height:100%;z-index:30001;display:none;">
        <p style="position:absolute;color:white;"><span>Loading, please wait . . . <i
                    class="fa fa-spin fa-refresh"></i></span></p>
    </div>
    <div class="container-contact100">
        <div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2">
            <div class="delivery-hero">
                <div class="delivery-hero-copy">
                    <span class="delivery-kicker"><i class="fa fa-truck"></i> CONTROL DELIVERY</span>
                    <h1>PO Confirmation<small>PT. Yamaha Musical Products Indonesia</small></h1>
                    <p>Konfirmasi penerimaan Purchase Order dan detail item pengiriman.</p>
                </div>
                <div class="delivery-hero-side">
                    <div class="delivery-badge"><i class="fa fa-check-circle"></i> Delivery Confirmation</div>
                </div>
            </div>
            <div class="delivery-body">
                <div id="not_filled" style="width:100%;">
                    <div class="search-card" id="po_field">
                        <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                        <input type="hidden" id="check_po" value="{{ $check_po }}">
                        <div class="section-title">
                            <div class="section-icon"><i class="fa fa-search"></i></div>
                            <div>
                                <h3>Search Purchase Order</h3>
                                <p>Masukkan nomor PO sesuai link konfirmasi yang diterima.</p>
                            </div>
                        </div>
                        <label class="label-input1002" id="labeldept">PO Number</label>
                        <div class="po-search-row">
                            <input type="text" class="form-control" id="po_number" name="po_number"
                                placeholder="Input PO Number (e.g. : EQ2401001-IT)">
                            <button type="button" id="searchPo" class="btn btn-primary" onclick="searhPo()"><i
                                    class="fa fa-search"></i> Search</button>
                        </div>
                    </div>
                    <div class="col-xs-12 col-md-12" id="main"
                        style="display:none;vertical-align:middle;margin-top:3%;">
                        <div class="instruction-box"><i class="fa fa-info-circle"></i> Please tick the check column to
                            confirm that the PO has been received.</div>
                        <div class="table-responsive-custom">
                            <table id="tableDetail">
                                <thead>
                                    <tr>
                                        <th style="width:30%;">Item Name</th>
                                        <th style="width:10%;">Delivery Date</th>
                                        <th style="width:5%;">Quantity</th>
                                        <th style="width:5%;">Price</th>
                                        <th style="width:5%;">Amount</th>
                                        <th style="width:5%;">Check</th>
                                        <th style="width:5%;" id="th_driver">Driver</th>
                                        <th style="width:15%;">Note</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyDetail">
                                </tbody>
                            </table>
                        </div>
                        <div class="action-wrapper">
                            <button class="btn-action btn-cancel" type="button" onclick="clearAll()"><i
                                    class="fa fa-times"></i> Cancel</button>
                            <button class="btn-action btn-submit" type="button" onclick="save()"><i
                                    class="fa fa-save"></i> Submit</button>
                        </div>
                    </div>
                </div>
                <div id="already_filled" style="width:100%;display:none;">
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
</body>
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
        clearAll();

    });

    function clearAll() {
        $("#main").hide();
        $("#not_filled").show();
        $("#already_filled").hide();
        $("#po_number").prop('disabled', false);

        $("#po_number").val('');
        // $("#po_number").focus();
        $('#po_number').val('{{ $check_po ?? '' }}');

        $('#searchPo').click();


        no_item = [];
    }

    var no_item = [];
    var drivers = [];

    function changeList(no_item, type) {
        var ids = '';
        if (type == 'driver_name') {
            var contents = $("#driver_" + no_item).val();
            ids = 'driver';
        } else if (type == 'plat_no') {
            var contents = $("#plat_no_" + no_item).val();
            ids = 'plat_no';
        } else if (type == 'car') {
            var contents = $("#car_" + no_item).val();
            ids = 'car';
        } else if (type == 'driver_phone') {
            var contents = $("#driver_phone_" + no_item).val();
            ids = 'driver_phone';
        }

        if (contents != '') {
            var list_id = "#list_" + ids + "_" + no_item;
            $(list_id).html("");
            $(list_id).show();

            var filteredDrivers = [];

            if (type == 'driver_name') {
                filteredDrivers = file_txt_driver_name.filter(function(driver) {
                    return driver.toLowerCase().includes(contents.toLowerCase());
                });
            } else if (type == 'plat_no') {
                filteredDrivers = file_txt_driver_plat_no.filter(function(driver) {
                    return driver.toLowerCase().includes(contents.toLowerCase());
                });
            } else if (type == 'car') {
                filteredDrivers = file_txt_driver_car.filter(function(driver) {
                    return driver.toLowerCase().includes(contents.toLowerCase());
                });
            } else if (type == 'driver_phone') {
                filteredDrivers = file_txt_driver_phone.filter(function(driver) {
                    return driver.toLowerCase().includes(contents.toLowerCase());
                });
            }

            for (var i = 0; i < filteredDrivers.length; i++) {
                $(list_id).append('<li style="padding:8px 10px;cursor:pointer;border-bottom:1px solid #f0f0f0;transition:background-color 0.2s;" onmouseover="$(this).css(\'background-color\',\'#f0f0f0\')" onmouseout="$(this).css(\'background-color\',\'#fff\')" onclick="selectItem(\'' + no_item + '\', \'' + filteredDrivers[i].replace(/'/g, "\\'") + '\', \'' + ids + '\')">' + filteredDrivers[i] + '</li>');
            }
        } else {
            var list_id = "#list_" + ids + "_" + no_item;
            $(list_id).hide();
        }
    }

    function selectItem(no_item, value, ids) {
        $("#" + ids + "_" + no_item).val(value);
        $("#list_" + ids + "_" + no_item).hide();
    }

    $(document).on('click', function(e) {
        if (!$(e.target).closest('.driver-input, .plat-no-input, .car-input, .driver-phone-input, .autocomplete-list').length) {
            $('.autocomplete-list').hide();
        }
    });

    var file_txt_driver_name = null;
    var file_txt_driver_plat_no = null;
    var file_txt_driver_car = null;
    var file_txt_driver_phone = null;

    function searhPo() {
        var po_number = $("#po_number").val();
        var check_po = $("#check_po").val();

        if (po_number.length >= 12) {

            if (check_po != po_number) {
                openErrorGritter('Error!', "PO number doesn't match the link");
                return false;
            }

            var data = {
                po_number: po_number
            }

            $("#loading").show();
            $.get('{{ url('fetch/po_eq_confirmation') }}', data, function(result, status, xhr) {
                if (result.status) {

                    $('#loading').show();

                    // $('#tableDetail').DataTable().clear();
                    // $('#tableDetail').DataTable().destroy();
                    $('#bodyDetail').html("");
                    no_item = [];
                    file_txt_driver_name = result.file_txt_driver_name;
                    file_txt_driver_plat_no = result.file_txt_driver_plat_no;
                    file_txt_driver_car = result.file_txt_driver_car;
                    file_txt_driver_phone = result.file_txt_driver_phone;

                    $("#po_number").prop('disabled', true);

                    $('#th_driver').hide();

                    if(result.drivers.length > 0){
                        $('#th_driver').show();
                    }
                    drivers = result.drivers;

                    var tableData = "";
                    for (var i = 0; i < result.data.length; i++) {
                        tableData += '<tr>';
                        tableData += '<td style="width:30%; padding:0px 5px 0px 5px; text-align:left;">';
                        if(result.data[i].driver){
                            tableData += '<span id="driver_detail_' + result.data[i].no_item + '" style="font-weight:bold;">' + (result.data[i].driver || '') + '</span><br>';
                        }
                        tableData += (result.data[i].item_name || '') + '</td>';
                        tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">' +
                            result.data[i].delivery_date + '</td>';
                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            result.data[i].quantity + ' ' + result.data[i].uom + '</td>';
                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            parseInt(result.data[i].price).toLocaleString('de-DE') + '</td>';

                        var amount = result.data[i].price * result.data[i].quantity;

                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            amount.toLocaleString('de-DE') + '</td>';
                        tableData += '<td style="width:5%; padding:18px 5px 0px 5px; text-align:center;">';
                        tableData += '<label><input type="checkbox" class="minimal" id="check_' + result.data[i]
                            .no_item + '"></label><br>'
                        tableData += '</td>';

                        if(result.drivers.length > 0){
                            tableData += '<td style="width:20%; padding:0px 5px 0px 5px; text-align:left;">' +
                                '<label style="font-weight:bold; font-size:11px;">Driver Name</label><div style="position:relative;"><input type="text" class="form-control driver-input" style="font-size: 12px;" onkeyup="changeList(\'' + result.data[i].no_item + '\', \'driver_name\')" id="driver_' + result.data[i].no_item + '" placeholder="Nama Driver ..." data-no-item="' + result.data[i].no_item + '"><ul class="autocomplete-list" id="list_driver_' + result.data[i].no_item + '" style="position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;list-style:none;padding:0;margin:5px 0 0 0;display:none;z-index:1000;"></ul></div>' +
                                '<label style="font-weight:bold; font-size:11px; display:block;">Plat No</label><div style="position:relative;"><input type="text" class="form-control plat-no-input" style="font-size: 12px;" onkeyup="changeList(\'' + result.data[i].no_item + '\', \'plat_no\')" id="plat_no_' + result.data[i].no_item + '" placeholder="Plat No ..." data-no-item="' + result.data[i].no_item + '"><ul class="autocomplete-list" id="list_plat_no_' + result.data[i].no_item + '" style="position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;list-style:none;padding:0;margin:5px 0 0 0;display:none;z-index:1000;"></ul></div>' +
                                '<label style="font-weight:bold; font-size:11px; display:block;">Jenis Mobil</label><div style="position:relative;"><input type="text" class="form-control car-input" style="font-size: 12px;" onkeyup="changeList(\'' + result.data[i].no_item + '\', \'car\')" id="car_' + result.data[i].no_item + '" placeholder="Jenis Mobil ..." data-no-item="' + result.data[i].no_item + '"><ul class="autocomplete-list" id="list_car_' + result.data[i].no_item + '" style="position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;list-style:none;padding:0;margin:5px 0 0 0;display:none;z-index:1000;"></ul></div>' +
                                '<label style="font-weight:bold; font-size:11px; display:block;">No. HP</label><div style="position:relative;"><input type="text" class="form-control driver-phone-input" style="font-size: 12px;" onkeyup="changeList(\'' + result.data[i].no_item + '\', \'driver_phone\')" id="driver_phone_' + result.data[i].no_item + '" placeholder="No. HP ..." data-no-item="' + result.data[i].no_item + '"><ul class="autocomplete-list" id="list_driver_phone_' + result.data[i].no_item + '" style="position:absolute;top:100%;left:0;right:0;background:#fff;border:1px solid #ddd;list-style:none;padding:0;margin:5px 0 0 0;display:none;z-index:1000;"></ul></div>' +
                                '<label style="font-weight:bold; font-size:11px; display:block;">Upload Photo</label><div style="position:relative;"><input type="file" class="form-control" style="font-size: 12px;" id="file_' + result.data[i].no_item + '" accept="image/*" placeholder="Foto Driver ..."></div>' +
                                '</td>';
                        }

                        tableData += '<td style="width:15%; padding:0px 5px 0px 5px; text-align:center;">';
                        tableData +=
                            '<input type="text" class="form-control" style="font-size: 12px;" id="note_' +
                            result.data[i].no_item + '" placeholder="Reason ...">';
                        tableData += '</td>';

                        tableData += '</tr>';

                        no_item.push(result.data[i].no_item);

                    }

                    $('#bodyDetail').append(tableData);
                    // $('#tableDetail').DataTable({
                    //     'dom': 'Bfrtip',
                    //     'responsive': true,
                    //     'lengthMenu': [
                    //         [-1],
                    //         ['Show all']
                    //     ],
                    //     'buttons': {
                    //         buttons: []
                    //     },
                    //     'paging': false,
                    //     'lengthChange': false,
                    //     'searching': false,
                    //     'ordering': false,
                    //     'info': false,
                    //     'autoWidth': true,
                    //     'sPaginationType': 'full_numbers',
                    //     'bJQueryUI': true,
                    //     'bAutoWidth': false,
                    //     'processing': true
                    // });


                    $('#main').show();
                    $('#loading').hide();

                }
            });

        } else {
            // $('#tableDetail').DataTable().clear();
            // $('#tableDetail').DataTable().destroy();
            $('#bodyDetail').html("");
            // var tableData = "";

            // $('#bodyDetail').append(tableData);
            // $('#tableDetail').DataTable({
            //     'dom': 'Bfrtip',
            //     'responsive': true,
            //     'lengthMenu': [
            //         [-1],
            //         ['Show all']
            //     ],
            //     'buttons': {
            //         buttons: []
            //     },
            //     'paging': false,
            //     'lengthChange': false,
            //     'searching': false,
            //     'ordering': false,
            //     'info': false,
            //     'autoWidth': true,
            //     'sPaginationType': 'full_numbers',
            //     'bJQueryUI': true,
            //     'bAutoWidth': false,
            //     'processing': true
            // });
        }

    }

    function save() {

        if(drivers.length > 0){

            var formData = new FormData($('#formData')[0]);

            var po_number = $("#po_number").val();
            var ada_driver = false;

            var salah = 0;

            for (var i = 0; i < no_item.length; i++) {
                if ($('#check_' + no_item[i]).is(":checked")) {
                    var driver = [];
                    formData.append('no_item_'+no_item[i], no_item[i]);
                    formData.append('note_'+no_item[i], $('#note_' + no_item[i]).val());
                    if(drivers.length > 0){
                        if($('#driver_' + no_item[i]).val() == '' || $('#plat_no_' + no_item[i]).val() == '' || $('#car_' + no_item[i]).val() == '' || $('#driver_phone_' + no_item[i]).val() == ''){
                            salah++;
                        }
                        formData.append('driver_detail_'+no_item[i], $('#driver_detail_' + no_item[i]).text());
                        formData.append('driver_name_'+no_item[i], $('#driver_' + no_item[i]).val());
                        formData.append('driver_plat_no_'+no_item[i], $('#plat_no_' + no_item[i]).val());
                        formData.append('driver_car_'+no_item[i], $('#car_' + no_item[i]).val());
                        formData.append('driver_phone_'+no_item[i], $('#driver_phone_' + no_item[i]).val());
                        formData.append('driver_photo_'+no_item[i], $('#file_' + no_item[i])[0].files[0] ? $('#file_' + no_item[i])[0].files[0] : null);
                        ada_driver = true;
                    }
                } else {
                    openErrorGritter('Error!', 'Tick all column before submit');
                    return false;
                }
            }

            if(salah > 0){
                openErrorGritter('Error!', 'Fill all driver column before submit');
                return false;
            }

            formData.append('po_number', po_number);
            formData.append('no_item', no_item);
            formData.append('ada_driver', ada_driver);

            $.ajax({
                url: "{{ url('input/po_eq_confirmation') }}",
                type: "POST",
                data: formData,
                contentType: false,
                processData: false,
                beforeSend: function() {
                    $("#loading").show();
                },
                success: function(result) {
                    if (result.status) {
                        clearAll();
                        $("#not_filled").hide();
                        $("#already_filled").show();
                        openSuccessGritter('Success', 'PO successfully confirmed');
                        $("#loading").hide();
                    } else {
                        openErrorGritter('Error!', result.message);
                        $("#loading").hide();
                    }
                },
                error: function(xhr, status, error) {
                    openErrorGritter('Error!', 'An error occurred while submitting the form.');
                    console.log('Error : ' + error);
                    $("#loading").hide();
                }
            });
        }else{
            var po_number = $("#po_number").val();
            var data = [];
            var ada_driver = false;

            var salah = 0;

            for (var i = 0; i < no_item.length; i++) {
                if ($('#check_' + no_item[i]).is(":checked")) {
                    var driver = [];
                    data.push({
                        'no_item': no_item[i],
                        'note': $('#note_' + no_item[i]).val(),
                        'driver' : null
                    });
                    if(drivers.length > 0){
                        if($('#driver_' + no_item[i]).val() == '' || $('#plat_no_' + no_item[i]).val() == '' || $('#car_' + no_item[i]).val() == '' || $('#driver_phone_' + no_item[i]).val() == ''){
                            salah++;
                        }
                    }
                } else {
                    openErrorGritter('Error!', 'Tick all column before submit');
                    return false;
                }
            }

            if(salah > 0){
                openErrorGritter('Error!', 'Fill all driver column before submit');
                return false;
            }

            var x = {
                po_number: po_number,
                data: data,
                ada_driver: ada_driver
            }
            if (confirm("Are you sure to confirm this PO?")) {
                $("#loading").show();

                $.post("{{ url('input/po_eq_confirmation') }}", x, function(result, status, xhr) {
                    if (result.status) {
                        clearAll();
                        $("#not_filled").hide();
                        $("#already_filled").show();
                        openSuccessGritter('Success', 'PO successfully confirmed');
                        $("#loading").hide();

                    } else {
                        openErrorGritter('Error!', result.message);
                        console.log('Error : ' + result.message);
                        $("#loading").hide();
                    }
                });
            }
        }
    }



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

</html>
