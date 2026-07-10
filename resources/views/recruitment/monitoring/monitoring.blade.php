@extends('layouts.master')

@section('styles')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/buttons.dataTables.min.css') }}">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">

    <style>
        body {
            background: #f0f2f7 !important;
        }

        body p,
        body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="dtr"]),
        body div,
        body label,
        body input,
        body select,
        body textarea,
        body button,
        body a,
        body td,
        body th,
        body h1,
        body h2,
        body h3,
        body h4,
        body h5,
        body h6,
        body li {
            font-family: 'Plus Jakarta Sans', sans-serif !important;
        }

        /* ══════════════════════════════════════
           PAGE HEADER
        ══════════════════════════════════════ */
        .page-header-modern {
            background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
            padding: 26px 36px 22px;
            margin: 24px 0 24px;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 14px;
            position: relative;
            overflow: hidden;
        }

        .page-header-modern::before {
            content: '';
            position: absolute;
            right: -50px;
            top: -50px;
            width: 220px;
            height: 220px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .05);
            pointer-events: none;
        }

        .page-header-modern::after {
            content: '';
            position: absolute;
            left: 30%;
            bottom: -70px;
            width: 160px;
            height: 160px;
            border-radius: 50%;
            background: rgba(255, 255, 255, .03);
            pointer-events: none;
        }

        .ph-badge {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: rgba(255, 255, 255, .14);
            border: 1px solid rgba(255, 255, 255, .22);
            color: #c9c6f0;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1.2px;
            text-transform: uppercase;
            padding: 5px 14px;
            border-radius: 20px;
            margin-bottom: 10px;
        }

        .ph-title {
            color: #fff !important;
            font-size: 22px !important;
            font-weight: 700 !important;
            margin: 0 0 3px !important;
            line-height: 1.2 !important;
        }

        .ph-sub {
            color: rgba(255, 255, 255, .5);
            font-size: 13px;
            margin: 0;
        }

        /* ══════════════════════════════════════
           STAT CARDS
        ══════════════════════════════════════ */
        .stat-row {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 14px;
            margin-bottom: 20px;
        }

        .stat-card {
            background: #fff;
            border-radius: 14px;
            padding: 18px 20px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            border: 1px solid rgba(0, 0, 0, .05);
            display: flex;
            align-items: center;
            gap: 14px;
            transition: box-shadow .2s, transform .2s;
        }

        .stat-card:hover {
            box-shadow: 0 4px 16px rgba(0, 0, 0, .09);
            transform: translateY(-1px);
        }

        .stat-icon {
            width: 44px;
            height: 44px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            flex-shrink: 0;
        }

        .si-purple {
            background: #ede9fe;
            color: #7c3aed;
        }

        .si-blue {
            background: #dbeafe;
            color: #1d4ed8;
        }

        .si-green {
            background: #dcfce7;
            color: #15803d;
        }

        .si-amber {
            background: #fef3c7;
            color: #b45309;
        }

        .stat-val {
            font-size: 22px;
            font-weight: 800;
            color: #1a202c;
            line-height: 1;
            margin-bottom: 2px;
        }

        .stat-lbl {
            font-size: 12px;
            color: #718096;
            font-weight: 500;
        }

        /* ══════════════════════════════════════
           SETTING CARD
        ══════════════════════════════════════ */
        .setting-card {
            background: #fff;
            border-radius: 14px;
            padding: 20px 24px;
            box-shadow: 0 2px 8px rgba(0, 0, 0, .05);
            border: 1px solid rgba(0, 0, 0, .05);
            margin-bottom: 16px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
        }

        .setting-left {
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .setting-icon {
            width: 42px;
            height: 42px;
            border-radius: 11px;
            background: linear-gradient(135deg, #4a4690, #605ca8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 17px;
            color: #fff;
            flex-shrink: 0;
        }

        .setting-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
            margin-bottom: 2px;
        }

        .setting-sub {
            font-size: 12px;
            color: #718096;
        }

        .setting-right {
            display: flex;
            align-items: center;
            gap: 14px;
            flex-wrap: wrap;
        }

        /* Status badge */
        .status-pill {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            padding: 6px 14px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: 700;
        }

        .status-pill::before {
            content: '';
            width: 7px;
            height: 7px;
            border-radius: 50%;
            animation: blink 1.5s ease-in-out infinite;
        }

        @keyframes blink {

            0%,
            100% {
                opacity: 1
            }

            50% {
                opacity: .3
            }
        }

        .s-on {
            background: #dcfce7;
            color: #15803d;
        }

        .s-on::before {
            background: #22c55e;
        }

        .s-off {
            background: #fee2e2;
            color: #991b1b;
        }

        .s-off::before {
            background: #ef4444;
        }

        /* Toggle button */
        .btn-toggle {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 9px 20px;
            border: none;
            border-radius: 10px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all .2s;
        }

        .btn-toggle-open {
            background: #15803d;
            color: #fff;
            box-shadow: 0 4px 12px rgba(21, 128, 61, .3);
        }

        .btn-toggle-close {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .3);
        }
        
        .btn-scan-kamera {
            background: #2563eb;
            color: #fff;
            box-shadow: 0 4px 12px rgba(37, 99, 235, .3);
        }

        .btn-toggle:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        /* ══════════════════════════════════════
           TABLE CARD
        ══════════════════════════════════════ */
        .table-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border: 1px solid rgba(0, 0, 0, .05);
            overflow: hidden;
            margin-bottom: 32px;
        }

        .table-card-header {
            padding: 16px 24px;
            border-bottom: 1px solid #f0f2f7;
            background: #fafbff;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 12px;
        }

        .table-card-title {
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .table-card-title .dot {
            width: 8px;
            height: 8px;
            background: #605ca8;
            border-radius: 50%;
        }

        /* Filter bar */
        .filter-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            flex-wrap: wrap;
        }

        .filter-bar .fi {
            display: flex;
            flex-direction: column;
            gap: 4px;
        }

        .filter-bar label {
            font-size: 10.5px;
            font-weight: 700;
            color: #a0aec0;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin: 0;
        }

        .filter-bar .iw {
            position: relative;
        }

        .filter-bar .iw i {
            position: absolute;
            left: 10px;
            top: 50%;
            transform: translateY(-50%);
            color: #a0aec0;
            font-size: 12px;
            pointer-events: none;
        }

        .filter-bar input[type=text] {
            border: 1.5px solid #e2e8f0;
            border-radius: 8px;
            padding: 7px 12px 7px 30px;
            font-size: 13px;
            color: #1a202c;
            background: #fff;
            outline: none;
            width: 220px;
            transition: border-color .18s, box-shadow .18s;
        }

        .filter-bar input[type=text]:focus {
            border-color: #605ca8;
            box-shadow: 0 0 0 3px rgba(96, 92, 168, .1);
        }

        .btn-search {
            display: inline-flex;
            align-items: center;
            gap: 7px;
            padding: 8px 18px;
            border: none;
            border-radius: 8px;
            background: linear-gradient(135deg, #4a4690, #605ca8);
            color: #fff;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
            box-shadow: 0 4px 12px rgba(96, 92, 168, .3);
            margin-top: 18px;
        }

        .btn-search:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        /* ══════════════════════════════════════
           DATATABLE
        ══════════════════════════════════════ */
        .dataTables_wrapper {
            padding: 16px 20px 20px !important;
        }

        .dataTables_length label,
        .dataTables_filter label {
            font-size: 13px !important;
            color: #4a5568 !important;
            font-weight: 500 !important;
        }

        .dataTables_length select,
        .dataTables_filter input {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 8px !important;
            padding: 6px 10px !important;
            font-size: 13px !important;
            outline: none !important;
        }

        table.dataTable {
            border-collapse: collapse !important;
            width: 100% !important;
        }

        table.dataTable thead th {
            background: #f7f8fc !important;
            color: #718096 !important;
            font-size: 11px !important;
            font-weight: 700 !important;
            letter-spacing: .7px !important;
            text-transform: uppercase !important;
            padding: 12px 14px !important;
            border-bottom: 2px solid #edf0f5 !important;
            border-top: none !important;
            white-space: nowrap;
        }

        table.dataTable tbody td {
            padding: 11px 14px !important;
            font-size: 13px !important;
            color: #2d3748 !important;
            border-bottom: 1px solid #f0f2f7 !important;
            border-top: none !important;
            vertical-align: middle !important;
        }

        table.dataTable tbody tr:last-child td {
            border-bottom: none !important;
        }

        /* Column search input */
        table.dataTable thead .col-search-input {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 6px !important;
            padding: 4px 8px !important;
            font-size: 11.5px !important;
            margin-top: 5px;
            outline: none !important;
            background: #fff !important;
        }

        table.dataTable thead .col-search-input:focus {
            border-color: #605ca8 !important;
        }

        /* Pagination */
        .dataTables_paginate .paginate_button {
            border-radius: 7px !important;
            font-size: 13px !important;
            font-weight: 600 !important;
            color: #4a5568 !important;
            border: 1px solid transparent !important;
            padding: 5px 10px !important;
            margin: 0 2px !important;
        }

        .dataTables_paginate .paginate_button:hover {
            background: #ede9fe !important;
            color: #605ca8 !important;
        }

        .dataTables_paginate .paginate_button.current {
            background: linear-gradient(135deg, #4a4690, #605ca8) !important;
            color: #fff !important;
        }

        .dataTables_info {
            font-size: 12px !important;
            color: #718096 !important;
        }

        /* ══════════════════════════════════════
           TABLE CELL ELEMENTS
        ══════════════════════════════════════ */
        .participant-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .p-avatar {
            width: 32px;
            height: 32px;
            border-radius: 9px;
            background: linear-gradient(135deg, #4a4690, #605ca8);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 11px;
            font-weight: 700;
            color: #fff;
            flex-shrink: 0;
        }

        .p-name {
            font-weight: 600;
            color: #1a202c;
            font-size: 13px;
        }

        .p-ktp {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 1px;
        }

        .date-chip {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            background: #f0f5ff;
            color: #2d6bc4;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
        }

        .edu-chip {
            display: inline-flex;
            align-items: center;
            background: #f7f8fc;
            color: #4a5568;
            padding: 3px 9px;
            border-radius: 6px;
            font-size: 11.5px;
            font-weight: 600;
            border: 1px solid #e2e8f0;
        }

        .test-done {
            background: #dcfce7;
            color: #15803d;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .test-done::before {
            content: '';
            width: 5px;
            height: 5px;
            background: #22c55e;
            border-radius: 50%;
        }

        .test-pending {
            background: #fee2e2;
            color: #991b1b;
            padding: 3px 10px;
            border-radius: 20px;
            font-size: 11.5px;
            font-weight: 700;
            display: inline-flex;
            align-items: center;
            gap: 5px;
        }

        .test-pending::before {
            content: '';
            width: 5px;
            height: 5px;
            background: #ef4444;
            border-radius: 50%;
        }

        .btn-act {
            display: inline-flex;
            align-items: center;
            gap: 5px;
            padding: 5px 11px;
            border-radius: 7px;
            font-size: 12px;
            font-weight: 700;
            cursor: pointer;
            border: none;
            text-decoration: none;
            transition: all .18s;
            white-space: nowrap;
            margin: 2px;
        }

        .btn-act:hover {
            opacity: .82;
            transform: translateY(-1px);
            text-decoration: none;
        }

        .btn-show {
            background: #ede9fe;
            color: #7c3aed;
        }

        .btn-dl {
            background: #dcfce7;
            color: #15803d;
        }

        /* ══════════════════════════════════════
           LOADING OVERLAY
        ══════════════════════════════════════ */
        #loading {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30, 31, 58, .35);
            backdrop-filter: blur(4px);
            z-index: 30001;
            align-items: center;
            justify-content: center;
        }

        #loading.show-flex {
            display: flex !important;
        }

        .loading-box {
            background: #fff;
            border-radius: 20px;
            padding: 36px 48px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
            box-shadow: 0 12px 40px rgba(0, 0, 0, .15);
        }

        .loading-spinner {
            width: 42px;
            height: 42px;
            border: 3px solid #e2e8f0;
            border-top-color: #605ca8;
            border-radius: 50%;
            animation: spin .75s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        .loading-box p {
            font-size: 13px;
            color: #718096;
            margin: 0;
            font-weight: 600;
        }

        /* ══════════════════════════════════════
           MODAL DETAIL HASIL TES
        ══════════════════════════════════════ */
        .modal-content {
            border: none;
            border-radius: 16px;
            box-shadow: 0 12px 48px rgba(0, 0, 0, .18);
            overflow: hidden;
        }

        .mh-brand {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 24px;
            background: linear-gradient(135deg, #2d2b4e, #605ca8);
        }

        .mh-brand h4 {
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            margin: 0;
        }

        .mh-close {
            background: rgba(255, 255, 255, .18);
            border: none;
            color: #fff;
            width: 30px;
            height: 30px;
            border-radius: 8px;
            cursor: pointer;
            font-size: 17px;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: background .18s;
            line-height: 1;
            text-decoration: none;
        }

        .mh-close:hover {
            background: rgba(255, 255, 255, .3);
            color: #fff;
        }

        .modal-body {
            padding: 24px;
        }

        /* ══════════════════════════════════════
           MODAL CONFIRM SETTING
        ══════════════════════════════════════ */
        .confirm-body {
            padding: 24px;
            display: flex;
            gap: 16px;
            align-items: flex-start;
        }

        .confirm-icon {
            width: 46px;
            height: 46px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 20px;
            flex-shrink: 0;
        }

        .confirm-icon.open {
            background: #dcfce7;
            color: #15803d;
        }

        .confirm-icon.close {
            background: #fee2e2;
            color: #dc2626;
        }

        .confirm-title {
            font-size: 15px;
            font-weight: 700;
            color: #1a202c;
            margin: 0 0 5px;
        }

        .confirm-desc {
            font-size: 13px;
            color: #718096;
            margin: 0;
            line-height: 1.6;
        }

        .mf {
            display: flex;
            gap: 10px;
            padding: 16px 24px;
            border-top: 1px solid #f0f2f7;
            background: #fafbff;
            justify-content: flex-end;
        }

        .btn-mf {
            padding: 10px 22px;
            border: none;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: all .18s;
        }

        .btn-mf:hover {
            opacity: .88;
            transform: translateY(-1px);
        }

        .btn-cancel {
            background: #f0f2f7;
            color: #4a5568;
            border: 1.5px solid #e2e8f0;
        }

        .btn-confirm-open {
            background: #15803d;
            color: #fff;
            box-shadow: 0 4px 12px rgba(21, 128, 61, .25);
        }

        .btn-confirm-close {
            background: #dc2626;
            color: #fff;
            box-shadow: 0 4px 12px rgba(220, 38, 38, .25);
        }
    </style>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

    {{-- Loading --}}
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Memuat data...</p>
        </div>
    </div>

    <div class="content-header" style="padding: 0 20px;">

        {{-- ── PAGE HEADER ── --}}
        <div class="page-header-modern">
            <div>
                <div class="ph-badge"><i class="fas fa-users"></i> Recruitment</div>
                <h1 class="ph-title">Monitoring Kraepelin Test</h1>
                <p class="ph-sub">Pantau status & hasil tes peserta rekrutmen</p>
            </div>
        </div>

        {{-- ── STAT CARDS ── --}}
        <div class="stat-row">
            <div class="stat-card">
                <div class="stat-icon si-purple"><i class="fas fa-users"></i></div>
                <div>
                    <div class="stat-val" id="stat-total">—</div>
                    <div class="stat-lbl">Total Peserta</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
                <div>
                    <div class="stat-val" id="stat-done">—</div>
                    <div class="stat-lbl">Selesai Tes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-amber"><i class="fas fa-clock"></i></div>
                <div>
                    <div class="stat-val" id="stat-pending">—</div>
                    <div class="stat-lbl">Belum Tes</div>
                </div>
            </div>
            <div class="stat-card">
                <div class="stat-icon si-blue"><i class="fas fa-percentage"></i></div>
                <div>
                    <div class="stat-val" id="stat-pct">—</div>
                    <div class="stat-lbl">Completion Rate</div>
                </div>
            </div>
        </div>

        {{-- ── SETTING CARD ── --}}
        <div class="setting-card">
            <div class="setting-left">
                <div class="setting-icon"><i class="fas fa-cog"></i></div>
                <div>
                    <div class="setting-title">Setting Kraepelin Test</div>
                    <div class="setting-sub">Buka atau tutup sesi tes untuk peserta</div>
                </div>
            </div>
            <div class="setting-right">
                <button class="btn-toggle btn-scan-kamera" data-bs-toggle="modal" data-bs-target="#modalBarcode">
                    <i class="fas fa-qrcode"></i> <span>QR CODE TES</span>
                </button>
                <div>
                    <div
                        style="font-size:11px;font-weight:700;color:#a0aec0;letter-spacing:.06em;text-transform:uppercase;margin-bottom:5px;">
                        Status Sesi</div>
                    <span class="status-pill s-off status_opening">OFF</span>
                </div>
                <input type="hidden" name="opening_kraepelin_test" id="opening_kraepelin_test">
                <button class="btn-toggle btn-toggle-open status_button">
                    <i class="fas fa-door-open"></i> <span>OPEN TEST</span>
                </button>
            </div>
        </div>

        {{-- ── TABLE CARD ── --}}
        <div class="table-card">
            <div class="table-card-header">
                <div class="table-card-title">
                    <span class="dot"></span> Hasil Tes Kraepelin
                </div>
                <div class="filter-bar">
                    <div class="fi">
                        <label>Rentang Tanggal</label>
                        <div class="iw">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="text" class="daterangepicker2" name="date" id="date"
                                placeholder="Pilih rentang tanggal">
                        </div>
                    </div>
                    <button class="btn-search" id="search">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>

            <table id="tableKraepelin" style="width:100%;"></table>
        </div>

    </div>


    {{-- ══════════════════════════════════════
    MODAL: Hasil Tes
    ══════════════════════════════════════ --}}
    <div class="modal modal-default fade" id="modalTes" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog modal-lg" style="max-width:1200px !important;">
            <div class="modal-content">
                <div class="mh-brand">
                    <h4>Detail Hasil Rekrutmen</h4>
                    <a href="javascript:;" data-bs-dismiss="modal" class="mh-close">&times;</a>
                </div>
                <div class="modal-body">
                    <div class="hasil_tes"></div>
                </div>
            </div>
        </div>
    </div>


    {{-- ══════════════════════════════════════
    MODAL: Konfirmasi Setting
    ══════════════════════════════════════ --}}
    <div class="modal fade" id="confirmSetting" tabindex="-1">
        <div class="modal-dialog" style="max-width:420px;">
            <div class="modal-content">
                <div class="mh-brand">
                    <h4>Konfirmasi Setting</h4>
                    <button type="button" class="mh-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="confirm-body" id="confirmBody">
                    <div class="confirm-icon open" id="confirmIcon"><i class="fas fa-door-open"></i></div>
                    <div>
                        <p class="confirm-title" id="confirmTitle">Buka Sesi Tes?</p>
                        <p class="confirm-desc" id="confirmDesc">Peserta akan dapat mengakses dan mengikuti Kraepelin Test
                            setelah sesi dibuka.</p>
                    </div>
                </div>
                <div class="mf">
                    <button type="button" class="btn-mf btn-cancel" data-bs-dismiss="modal">Batal</button>
                    <button id="submitSetting" type="button" class="btn-mf btn-confirm-open" id="confirmBtn">Ya,
                        Lanjutkan</button>
                </div>
            </div>
        </div>
    </div>

    {{-- ══════════════════════════════════════
    MODAL: QR Code Tes
    ══════════════════════════════════════ --}}
    <div class="modal fade" id="modalBarcode" tabindex="-1">
        <div class="modal-dialog modal-sm">
            <div class="modal-content">
                <div class="mh-brand">
                    <h4>QR Code Tes Kraepelin</h4>
                    <button type="button" class="mh-close" data-bs-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body text-center">
                    <img src="{{ asset('images/qrcode kraepelin.png') }}" alt="QR Code Kraepelin" style="max-width: 100%; height: auto; border-radius: 8px;">
                    <p class="text-muted" style="font-size: 13px; margin-top: 15px; margin-bottom: 0;">Scan QR Code di atas untuk masuk ke halaman tes.</p>
                    {{-- <p class="text-muted" style="font-size: 11px; margin-top: 15px; margin-bottom: 0;">https://10.109.33.34/bfv/public/index/ympi_recruitment</p> --}}
                    <p class="text-muted" style="font-size: 11px; margin-top: 15px; margin-bottom: 0;">https://ympi.co.id/bfv/public/index/ympi_recruitment</p>
                    <a href="https://ympi.co.id/bfv/public/index/ympi_recruitment" target="_blank" class="btn btn-primary mt-3">Buka Halaman Tes</a>
                </div>
                <div class="mf">
                    <button type="button" class="btn-mf btn-cancel" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
    <script src="{{ url('js/highcharts.js') }}"></script>
    <script src="{{ url('ckeditor/ckeditor.js') }}"></script>

    <script>
        const openSuccessGritter = (title, message) => {
            jQuery.gritter.add({ title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
        };
        const openErrorGritter = (title, message) => {
            jQuery.gritter.add({ title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
        };
        const errorAjax = (message) => {
            if ($.isArray(message)) {
                openErrorGritter('Error!', message.join(', '));
            } else if (typeof message === 'object') {
                Object.keys(message).forEach(key => {
                    let nk = key.replace('.', '_');
                    $(`.${nk}`).html(message[key]);
                });
                let first = Object.entries(message)[0][0];
                if (document.getElementById(first)) $('html,body').animate({ scrollTop: $(`#${first}`).offset().top }, 1500);
            } else {
                openErrorGritter('Error!', message);
            }
        };

        $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

        /* ── Daterangepicker ── */
        $('input.daterangepicker2').on('apply.daterangepicker', function (ev, picker) {
            $(this).val(picker.startDate.format('YYYY-MM-DD') + ' - ' + picker.endDate.format('YYYY-MM-DD'));
            $(this).data('daterangepicker').hide();
        });
        $('.daterangepicker2').daterangepicker({
            locale: { format: 'YYYY-MM-DD' },
            todayHighlight: true, autoUpdateInput: true, autoclose: true,
            startDate: moment().subtract(30, 'days').format('YYYY-MM-DD'),
            endDate: moment().format('YYYY-MM-DD'),
        });

        /* ── DataTable ── */
        const tableKraepelin = (step = 1) => {
            let date = $('#date').val();
            let myTableName = 'tableKraepelin';
            let myTable = $(`#${myTableName}`).DataTable({
                processing: true, serverSide: true, ordering: true, destroy: true, searching: true,
                footer: true,
                dom: "<'dataTables_wrapper'<'dt-top'f>rt<'dt-bottom'ip>>",
                lengthMenu: [[10, 25, 50, -1], ['10', '25', '50', 'Semua']],
                ajax: {
                    headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') },
                    url: "{{ url('index/ympi_recruitment_monitoring') }}",
                    type: 'GET', data: { date }, async: true,
                    error: function (xhr, error, code) {
                        if (step < 5) { step += 1; tableKraepelin(step); }
                    }
                },
                columns: [
                    { data: 'DT_RowIndex', title: 'No', orderable: false, searchable: false, width: '42px' },
                    {
                        data: 'name', title: 'Peserta', orderable: true, className: 'myFilter',
                        render: (data, type, row) =>
                            `<div class="participant-cell">
                                <div class="p-avatar">${(data || '?').substring(0, 2).toUpperCase()}</div>
                                <div>
                                    <div class="p-name">${data || '-'}</div>
                                    <div class="p-ktp">${row.card_id || '-'}</div>
                                </div>
                            </div>`
                    },
                    {
                        data: 'age', title: 'Usia', orderable: true, className: 'myFilter', width: '60px',
                        render: d => d ? `<span style="font-weight:600;">${d} <small style="color:#a0aec0;font-weight:400;">thn</small></span>` : '-'
                    },
                    {
                        data: 'test_date', title: 'Tgl Tes', orderable: true, className: 'myFilter',
                        render: d => d ? `<span class="date-chip"><i class="fas fa-calendar-alt"></i>${d}</span>` : '-'
                    },
                    { data: 'school', title: 'Sekolah / Universitas', orderable: true, className: 'myFilter' },
                    {
                        data: 'education', title: 'Pendidikan', orderable: true, className: 'myFilter', width: '90px',
                        render: d => d ? `<span class="edu-chip">${d}</span>` : '-'
                    },
                    {
                        data: 'test_done', title: 'Status', orderable: true,
                        render: (data, type, row) =>
                            row.test_done
                                ? `<span class="test-done">Selesai</span>`
                                : `<span class="test-pending">Belum</span>`
                    },
                    {
                        data: 'action', title: 'Aksi', orderable: false, searchable: false,
                        render: (data, type, row) => {
                            if (!row.test_done) return '<span style="color:#a0aec0;font-size:12px;">—</span>';
                            return `<button class="btn-act btn-show result"
                                        participant_id="${row.participant_id}"
                                        test_date="${row.test_date}"
                                        test_type="${row.test_type}"
                                        title="Lihat Hasil">
                                        <i class="fas fa-eye"></i> Lihat
                                    </button>
                                    <button class="btn-act btn-dl result_download"
                                        participant_id="${row.participant_id}"
                                        test_date="${row.test_date}"
                                        test_type="${row.test_type}"
                                        title="Download">
                                        <i class="fas fa-download"></i>
                                    </button>`;
                        }
                    },
                ],
                tfoot: $(`#${myTableName} tfoot`),
                scrollX: true,
                paging: true, lengthChange: true, ordering: false, order: [],
                info: true, autoWidth: false, processing: true, destroy: true,
                sPaginationType: 'full_numbers',
                drawCallback: function (settings) {
                    /* Update stat cards from table data */
                    let api = this.api();
                    let rows = api.rows({ search: 'applied' }).data().toArray();
                    let total = rows.length;
                    let done = rows.filter(r => r.test_done).length;
                    let pending = total - done;
                    let pct = total > 0 ? Math.round((done / total) * 100) : 0;
                    $('#stat-total').text(total || '—');
                    $('#stat-done').text(done || '—');
                    $('#stat-pending').text(pending || '—');
                    $('#stat-pct').text(total > 0 ? pct + '%' : '—');
                }
            });

            $(`#${myTableName}_wrapper thead th`).each(function () {
                let title = $(this).text();
                let w = $(this).width();
                if ($(this).hasClass('myFilter')) {
                    if (w < 60) w = 60;
                    $(this).html(title + `<br><input type="text" style="width:${w}px;" class="col-search-input form-control form-control-sm" placeholder="Cari">`);
                }
            });
            myTable.columns().every(function () {
                let tbl = this;
                $('input', this.header()).on('keyup change', function () {
                    if (tbl.search() !== this.value) tbl.search(this.value).draw();
                });
            });
        };

        /* ── Sidebar active state ── */
        $('#side_hr').addClass('menu-open');
        $('body').addClass('sidebar-collapse');
        $('#side_recruitment > a').addClass('active');

        /* ── Setting status toggle ── */
        const changeSettingStatus = (from = '') => {
            let from_ = (from == undefined || from == null) ? '' : from;
            let statusMap = { '': 'open', 'close': 'open', 'open': 'close' };
            let next = statusMap[from_];

            if (next == 'open') {
                // saat ini OFF → next action = buka
                $('.status_opening')
                    .removeClass('s-on').addClass('s-off')
                    .html('<i class="fas fa-circle" style="font-size:8px;"></i> OFF');
                $('#opening_kraepelin_test').val('open');
                $('.status_button')
                    .removeClass('btn-toggle-close').addClass('btn-toggle-open')
                    .html('<i class="fas fa-door-open"></i> <span>OPEN TEST</span>');
            } else {
                // saat ini ON → next action = tutup
                $('.status_opening')
                    .removeClass('s-off').addClass('s-on')
                    .html('<i class="fas fa-circle" style="font-size:8px;"></i> ON');
                $('#opening_kraepelin_test').val('close');
                $('.status_button')
                    .removeClass('btn-toggle-open').addClass('btn-toggle-close')
                    .html('<i class="fas fa-door-closed"></i> <span>CLOSE TEST</span>');
            }
        };

        const changeOpeningKraepelinTest = () => {
            let val = $('#opening_kraepelin_test').val();
            $('#loading').addClass('show-flex');
            $.ajax({
                headers: { 'X-CSRF-TOKEN': '{{ csrf_token() }}', Accept: 'application/json' },
                method: 'POST',
                url: "{{ url('input/ympi_recruitment_monitoring/change_setting') }}",
                data: { status: val, type: 'opening_kraepelin_test' },
                success: function (result) {
                    $('#confirmSetting').modal('hide');
                    $('#loading').removeClass('show-flex');
                    if (result.status) {
                        changeSettingStatus(result.data.status);
                        openSuccessGritter('Berhasil', result.data.message);
                    } else { errorAjax(result.message); }
                },
                error: function (xhr) {
                    $('#loading').removeClass('show-flex');
                    $('#confirmSetting').modal('hide');
                    errorAjax(xhr.responseJSON.message);
                }
            });
        };

        /* ── Show result modal ── */
        const showResult = (test_date, participant_id, test_type) => {
            $('#loading').addClass('show-flex');
            $('.hasil_tes').html('');
            let url = '';
            if (test_type == 'kraepelin') url = "{{ url('fetch/ympi_recruitment_monitoring/kraepelin_result') }}";
            if (!url) return;
            $.ajax({
                method: 'GET', url,
                data: { test_date, participant_id, test_type, type: 'view' },
                success: function (res) {
                    $('#loading').removeClass('show-flex');
                    if (res.status) { $('#modalTes').modal('show'); $('.hasil_tes').html(res.data); }
                    else errorAjax(res.message);
                },
                error: function (xhr) {
                    $('#loading').removeClass('show-flex');
                    errorAjax(xhr.responseJSON.message);
                }
            });
        };

        /* ── Download result ── */
        const downloadResult = (test_date, participant_id, test_type) => {
            let form = document.createElement('form');
            form.setAttribute('method', 'post');
            form.setAttribute('action', "{{ url('download/ympi_recruitment_monitoring/kraepelin_result') }}");
            let params = { _token: '{{ csrf_token() }}', test_date, participant_id, test_type, type: 'download' };
            for (let i in params) {
                let inp = document.createElement('input');
                inp.type = 'hidden'; inp.name = i; inp.value = params[i];
                form.appendChild(inp);
            }
            document.body.appendChild(form);
            form.submit();
            document.body.removeChild(form);
        };

        /* ── Ready ── */
        jQuery(document).ready(function () {
            let statusOpeningTest = "{{ @$statusOpeningTest->status }}";
            tableKraepelin();
            changeSettingStatus(statusOpeningTest);
        });

        $(document).on('click', '#search', () => tableKraepelin());

        $(document).on('click', '.status_button', function () {
            let to = $('#opening_kraepelin_test').val();
            if (to == 'open') {
                $('#confirmIcon').attr('class', 'confirm-icon open').html('<i class="fas fa-door-open"></i>');
                $('#confirmTitle').text('Buka Sesi Tes?');
                $('#confirmDesc').text('Peserta akan dapat mengakses dan mengikuti Kraepelin Test setelah sesi dibuka.');
                $('#submitSetting').attr('class', 'btn-mf btn-confirm-open');
            } else {
                $('#confirmIcon').attr('class', 'confirm-icon close').html('<i class="fas fa-door-closed"></i>');
                $('#confirmTitle').text('Tutup Sesi Tes?');
                $('#confirmDesc').text('Peserta tidak akan dapat mengakses tes setelah sesi ditutup.');
                $('#submitSetting').attr('class', 'btn-mf btn-confirm-close');
            }
            $('#confirmSetting').modal('show');
        });

        $(document).on('click', '#submitSetting', () => changeOpeningKraepelinTest());

        $(document).on('click', '.result', function () {
            showResult($(this).attr('test_date'), $(this).attr('participant_id'), $(this).attr('test_type'));
        });

        $(document).on('click', '.result_download', function () {
            downloadResult($(this).attr('test_date'), $(this).attr('participant_id'), $(this).attr('test_type'));
        });
    </script>
@endsection