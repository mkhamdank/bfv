@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ══ BASE ══ */
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="select2"]):not([class*="ck"]):not([class*="highcharts"]),
    body div:not([class*="ck"]):not([class*="highcharts"]),
    body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ══ LOADING ══ */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.42); backdrop-filter: blur(6px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 52px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 16px 48px rgba(0,0,0,.18);
    }
    .loading-spinner {
        width: 44px; height: 44px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══ PAGE HEADER ══ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 22px;
        border-radius: 18px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .select2-selection__clear{
        display: none !important;
    }
    .select2-container .select2-selection--single {
            height: 38px !important; border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
            background: #fafbff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 36px !important; border-left: 1.5px solid #e2e8f0 !important; border-radius: 0 9px 9px 0 !important;
        background: #fafbff !important; width: 38px !important;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04); pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 {
        color: #fff !important; font-size: 24px !important; font-weight: 700 !important;
        margin: 0 0 4px !important; line-height: 1.2 !important;
    }
    .header-left p { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }
    .header-right { display: flex; gap: 10px; flex-wrap: wrap; }
    .btn-hdr {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; text-decoration: none; transition: all .18s; border: none;
    }
    .btn-hdr-ghost { background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25); color: #fff; }
    .btn-hdr-ghost:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }
    .btn-hdr-amber { background: #f59e0b; color: #fff; box-shadow: 0 3px 10px rgba(245,158,11,.3); }
    .btn-hdr-amber:hover { background: #d97706; color: #fff; text-decoration: none; }

    /* ══ STAT CARDS ══ */
    .stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 22px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        display: flex; align-items: center; gap: 14px;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .si-red    { background: #fee2e2; color: #dc2626; }
    .si-yellow { background: #fef3c7; color: #92400e; }
    .si-blue   { background: #dbeafe; color: #1d4ed8; }
    .si-green  { background: #dcfce7; color: #15803d; }
    .stat-val  { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; margin-bottom: 2px; }
    .stat-lbl  { font-size: 12px; color: #718096; font-weight: 500; }

    /* ══ SECTION CARD ══ */
    .sec-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .sec-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .sec-card-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1a202c; }
    .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .dot-purple { background: #605ca8; }
    .dot-red    { background: #ef4444; }
    .dot-blue   { background: #3b82f6; }
    .dot-green  { background: #22c55e; }
    .sec-card-body { padding: 20px 22px; }

    /* ══ FILTER FORM ══ */
    .filter-grid { display: grid; grid-template-columns: 1fr 1fr 1.5fr auto; gap: 14px; align-items: end; }
    .ff { display: flex; flex-direction: column; gap: 6px; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff input, .ff select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input:focus, .ff select:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }
    .btn-search {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px; border: none;
        background: linear-gradient(135deg,#4a4690,#605ca8); color: #fff;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: opacity .18s;
        box-shadow: 0 3px 10px rgba(96,92,168,.3); white-space: nowrap;
    }
    .btn-search:hover { opacity: .88; }
    .btn-refresh {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px; border: 1.5px solid #e2e8f0;
        background: #fff; color: #605ca8; font-size: 12px; font-weight: 700; cursor: pointer; transition: all .18s;
    }
    .btn-refresh:hover { background: #f5f3ff; border-color: #c4bfef; }

    /* ══ CHART ══ */
    #chart_kategori { width: 100%; height: 320px; }

    /* ══ TABLE ══ */
    .mod-table { width: 100%; border-collapse: collapse; }
    .mod-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
        padding: 10px 14px; border-bottom: 2px solid #edf0f5; white-space: nowrap; text-align: left;
    }
    .mod-table thead th:first-child,
    .mod-table thead th:last-child { text-align: center; }
    .mod-table tbody td {
        padding: 11px 14px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }
    .mod-table tbody td:first-child,
    .mod-table tbody td:last-child { text-align: center; }
    .mod-table tbody tr:hover td { background: #f5f8ff; }
    .mod-table tbody tr:last-child td { border-bottom: none; }
    .no-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px;
        background: #ede9fe; color: #7c3aed; font-size: 11px; font-weight: 700;
    }

    /* ══ STATUS PILLS ══ */
    .pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap;
    }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-open     { background: #fee2e2; color: #dc2626; }
    .pill-open::before     { background: #ef4444; }
    .pill-temp     { background: #fef3c7; color: #92400e; }
    .pill-temp::before     { background: #f59e0b; }
    .pill-progress { background: #dbeafe; color: #1d4ed8; }
    .pill-progress::before { background: #3b82f6; }
    .pill-close    { background: #dcfce7; color: #15803d; }
    .pill-close::before    { background: #22c55e; }

    /* ══ ACTION BUTTONS ══ */
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 5px 12px; border-radius: 7px; border: none;
        font-size: 12px; font-weight: 700; cursor: pointer; transition: all .18s; margin: 2px 0;
    }
    .btn-act:hover { opacity: .85; transform: translateY(-1px); }
    .btn-purple { background: linear-gradient(135deg,#4a4690,#605ca8); color:#fff; box-shadow:0 2px 8px rgba(96,92,168,.25); }
    .btn-green  { background: linear-gradient(135deg,#15803d,#16a34a); color:#fff; box-shadow:0 2px 8px rgba(21,128,61,.25); }
    .btn-danger-soft { background: #fee2e2; color: #dc2626; border: 1px solid #fca5a5; }
    .btn-danger-soft:hover { background: #fecaca; }

    /* ══ MODAL ══ */
    .mh {
        padding: 18px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .mh h4 { margin: 0; font-size: 15px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 9px; }
    .mh-close {
        width: 30px; height: 30px; border-radius: 8px; border: none;
        background: #fee2e2; color: #dc2626; font-size: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center; flex-shrink: 0;
    }
    .mh-close:hover { background: #fecaca; }
    .mb { padding: 22px 24px; }
    .mf { padding: 14px 24px; border-top: 1px solid #f0f2f7; background: #fafbff; display: flex; gap: 10px; justify-content: flex-end; }
    .btn-mf-cancel {
        padding: 9px 18px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #718096; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .18s;
    }
    .btn-mf-cancel:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }
    .btn-mf-save {
        padding: 9px 20px; border-radius: 9px; border: none;
        background: linear-gradient(135deg,#15803d,#16a34a); color: #fff;
        font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 3px 10px rgba(21,128,61,.25);
    }

    /* modal field groups */
    .modal-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; margin-bottom: 14px; }
    .modal-field { display: flex; flex-direction: column; gap: 6px; }
    .modal-field label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .modal-field input, .modal-field select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 12px !important; font-size: 13px !important; color: #1a202c !important;
        background: #fafbff !important; width: 100% !important; outline: none !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .modal-field input:focus, .modal-field select:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .modal-field input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }
    .modal-field textarea {
        border: 1.5px solid #e2e8f0; border-radius: 9px; padding: 9px 12px;
        font-size: 13px; color: #1a202c; background: #fafbff; width: 100%;
        resize: vertical; outline: none; transition: border-color .18s, box-shadow .18s;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .modal-field textarea:focus { border-color: #605ca8; background: #fff; box-shadow: 0 0 0 3px rgba(96,92,168,.1); }
    .modal-field textarea[readonly] { background: #f0eef9; border-color: #c4bfef; color: #4a4690; }

    .section-divider {
        border: none; border-top: 1.5px solid #f0f2f7; margin: 18px 0;
    }

    /* photo upload */
    .photo-upload-wrap {
        border: 1.5px dashed #c4bfef; border-radius: 10px; background: #faf9ff;
        padding: 12px; transition: border-color .18s;
    }
    .photo-upload-wrap:hover { border-color: #605ca8; }
    .photo-upload-wrap input[type="file"] {
        border: none !important; background: transparent !important;
        padding: 0 !important; font-size: 12.5px !important;
        width: 100% !important; cursor: pointer; color: #4a5568 !important; margin-bottom: 6px;
    }
    .photo-upload-label { font-size: 11px; font-weight: 700; color: #605ca8; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; display: block; }

    /* riwayat table */
    .riwayat-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .riwayat-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 9px 12px;
        border-bottom: 1.5px solid #edf0f5; text-align: left;
    }
    .riwayat-table thead th:first-child { text-align: center; }
    .riwayat-table tbody td {
        padding: 9px 12px; color: #2d3748; border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }
    .riwayat-table tbody td:first-child { text-align: center; }
    .riwayat-table tbody tr:last-child td { border-bottom: none; }

    /* schedule table */
    .sched-table { width: 100%; border-collapse: collapse; }
    .sched-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 8px 12px;
        border-bottom: 1.5px solid #edf0f5;
    }
    .sched-table tbody td { padding: 8px 10px; border-bottom: 1px solid #f0f2f7; vertical-align: middle; }
    .sched-table tbody tr:last-child td { border-bottom: none; }
    .sched-table .sched-select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 7px 10px !important; font-size: 13px !important;
        background: #fafbff !important; width: 100% !important;
    }

    /* ══ MISC ══ */
    .page-wrapper { padding-top: 0 !important; }
    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th,
    .datepicker-months > table > thead > tr > th,
    .datepicker-years > table > thead > tr > th {
        background-color: white; color: #696969 !important;
    }
    .btn-header-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
        color: #fff; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: background .18s;
    }
    .btn-header-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

</style>
@stop

@section('header')@stop

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

{{-- Loading Overlay --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memproses data...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-cube"></i> Workshop</div>
            <h1>{{ $title }}</h1>
            <p>Monitoring &amp; penanganan temuan audit molding vendor</p>
        </div>
        <div class="header-right">
            <a href="{{ url('index/workshop/check_molding_vendor/create') }}" class="btn-header-back">
                <i class="fas fa-plus"></i> Cek Molding
            </a>
           
            {{-- <button class="btn-hdr btn-hdr-ghost" onclick="openModalSchedule()">
                <i class="fas fa-calendar-plus"></i> Schedule
            </button> --}}
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon si-red"><i class="fas fa-exclamation-circle"></i></div>
            <div>
                <div class="stat-val" id="sc-open">—</div>
                <div class="stat-lbl">Open</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-yellow"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-val" id="sc-temp">—</div>
                <div class="stat-lbl">Temporary Close</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-spinner"></i></div>
            <div>
                <div class="stat-val" id="sc-prog">—</div>
                <div class="stat-lbl">In-Progress</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-val" id="sc-close">—</div>
                <div class="stat-lbl">Close</div>
            </div>
        </div>
    </div>

    {{-- ── FILTER CARD ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <div class="sec-card-title"><span class="dot dot-purple"></span> Filter Data</div>
        </div>
        <div class="sec-card-body">
            <div class="filter-grid">
                <div class="ff">
                    <label>Tanggal Dari</label>
                    <input type="text" class="datepicker" id="datefrom" name="datefrom"
                           placeholder="yyyy-mm-dd" autocomplete="off">
                </div>
                <div class="ff">
                    <label>Tanggal Sampai</label>
                    <input type="text" class="datepicker" id="dateto" name="dateto"
                           placeholder="yyyy-mm-dd" autocomplete="off">
                </div>
                <div class="ff">
                    <label>Status</label>
                    <select class="select2" style="width:100%;"
                            id="status" data-placeholder="Semua Status">
                        <option value=""></option>
                        <option value="Open">Open</option>
                        <option value="Temporary Close">Temporary Close</option>
                        <option value="In-Progress">In-Progress</option>
                        <option value="Close">Close</option>
                    </select>
                </div>
                <div>
                    <button class="btn-search" onclick="drawChart()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
            </div>
        </div>
    </div>

    {{-- ── CHART CARD ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <div class="sec-card-title">
                <span class="dot dot-purple"></span> Grafik Schedule Maintenance Molding
            </div>
        </div>
        <div class="sec-card-body" style="padding: 16px 20px;">
            <div id="chart_kategori"></div>
        </div>
    </div>

    {{-- ── OUTSTANDING TEMUAN TABLE ── --}}
    <div class="sec-card" style="margin-bottom:32px;">
        <div class="sec-card-header">
            <div class="sec-card-title">
                <span class="dot dot-red"></span> Outstanding Temuan
            </div>
            <button class="btn-refresh" onclick="filter_data('','')">
                <i class="fas fa-sync-alt"></i> Refresh
            </button>
        </div>
        <div style="overflow-x:auto;">
            <table class="mod-table" id="table_penanganan">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th>Tanggal</th>
                        <th>Nama Molding</th>
                        <th>Nama Part</th>
                        <th>Permasalahan</th>
                        <th>PIC</th>
                        <th style="text-align:center;">Status</th>
                        <th style="text-align:center; width:130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="body_penanganan">
                    <tr>
                        <td colspan="8" style="text-align:center;color:#a0aec0;padding:40px 16px;">
                            <i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i> Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>

{{-- ══ MODAL: PENANGANAN TEMUAN ══ --}}
<div class="modal modal-default fade" id="modal_penanganan">
    <div class="modal-dialog modal-lg" style="max-width:1060px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-wrench" style="color:#605ca8;font-size:15px;"></i> Penanganan Temuan</h4>
                <button class="mh-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="mb">
                <input type="hidden" id="ids">

                {{-- Info Row --}}
                <div class="modal-grid-2">
                    <div class="modal-field">
                        <label>Tanggal</label>
                        <input type="text" id="tanggal" readonly>
                    </div>
                    <div class="modal-field">
                        <label>PIC</label>
                        <input type="text" id="pic" readonly>
                    </div>
                    <div class="modal-field">
                        <label>Nama Molding</label>
                        <input type="text" id="molding_name" readonly>
                    </div>
                    <div class="modal-field">
                        <label>Nama Part</label>
                        <input type="text" id="part_name" readonly>
                    </div>
                </div>

                {{-- Permasalahan & Penanganan Sementara --}}
                <div class="modal-grid-2" style="margin-bottom:14px;">
                    <div class="modal-field">
                        <label>Permasalahan</label>
                        <textarea id="permasalahan" rows="4" readonly></textarea>
                    </div>
                    <div class="modal-field">
                        <label>Penanganan Sementara</label>
                        <textarea id="temp_penanganan" rows="4" readonly></textarea>
                    </div>
                </div>

                {{-- Status --}}
                <div style="display:grid;grid-template-columns:1fr 1fr;gap:14px 20px;margin-bottom:14px;">
                    <div class="modal-field">
                        <label>Status <span style="color:#dc2626;">*</span></label>
                        <select class="select3" id="status_problem" style="width:100%;" data-placeholder="Pilih Status">
                            <option value=""></option>
                            <option value="Open">Open</option>
                            <option value="Temporary Close">Temporary Close</option>
                            <option value="Close">Close</option>
                        </select>
                    </div>
                </div>

                {{-- Perbaikan --}}
                <div class="modal-field" style="margin-bottom:14px;">
                    <label>Perbaikan <span style="color:#dc2626;">*</span></label>
                    <textarea id="perbaikan" rows="3"></textarea>
                </div>

                {{-- Foto Perbaikan --}}
                <div class="modal-field" style="margin-bottom:18px;">
                    <label>Foto Perbaikan <span style="color:#dc2626;">*</span></label>
                    <div class="photo-upload-wrap">
                        <span class="photo-upload-label"><i class="fas fa-camera" style="margin-right:5px;"></i>Pilih Foto</span>
                        <input type="file" id="perbaikan1" accept="image/*" style="margin-bottom:8px;">
                        <input type="file" id="perbaikan2" accept="image/*">
                    </div>
                </div>

                <hr class="section-divider">

                {{-- Riwayat Perbaikan --}}
                <div style="font-size:13px;font-weight:700;color:#1a202c;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                    <span class="dot dot-blue" style="width:8px;height:8px;border-radius:50%;background:#3b82f6;flex-shrink:0;"></span>
                    Riwayat Perbaikan
                </div>
                <div style="overflow-x:auto;">
                    <table class="riwayat-table" id="tableRiwayat">
                        <thead>
                            <tr>
                                <th style="width:36px;">#</th>
                                <th>Tanggal Perbaikan</th>
                                <th>Status</th>
                                <th>Perbaikan</th>
                                <th>Foto Perbaikan</th>
                            </tr>
                        </thead>
                        <tbody id="bodyRiwayat">
                            <tr><td colspan="5" style="text-align:center;color:#a0aec0;padding:20px;">Belum ada riwayat.</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="mf">
                <button class="btn-mf-cancel" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-save" onclick="simpanPenanganan()">
                    <i class="fas fa-save"></i> Simpan Penanganan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: SCHEDULE ══ --}}
<div class="modal modal-default fade" id="modal_schedule">
    <div class="modal-dialog" style="max-width:560px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-calendar-plus" style="color:#605ca8;font-size:15px;"></i> Buat Schedule Check</h4>
                <button class="mh-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="mb">
                <div class="modal-field" style="margin-bottom:18px;">
                    <label>Periode</label>
                    <input type="month" id="periode_cek" style="border:1.5px solid #e2e8f0;border-radius:9px;padding:9px 13px;font-size:13px;background:#fafbff;width:100%;">
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <span style="font-size:13px;font-weight:700;color:#1a202c;display:flex;align-items:center;gap:8px;">
                        <span class="dot dot-purple" style="width:8px;height:8px;border-radius:50%;background:#605ca8;flex-shrink:0;"></span>
                        Pilih Molding
                    </span>
                    <button class="btn-act btn-purple" onclick="add_schedule()">
                        <i class="fas fa-plus"></i> Tambah
                    </button>
                </div>
                <div style="overflow-x:auto;">
                    <table class="sched-table">
                        <thead>
                            <tr>
                                <th>Nama Molding</th>
                                <th style="width:1%;">#</th>
                            </tr>
                        </thead>
                        <tbody id="body_schedule"></tbody>
                    </table>
                </div>
            </div>
            <div class="mf">
                <button class="btn-mf-cancel" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-save" onclick="saveSchedule()">
                    <i class="fas fa-check"></i> Simpan Schedule
                </button>
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
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var data_all = [];
    var moldings = <?php echo json_encode($moldings); ?>;

    /* ── CKEditor ── */
    var ckToolbar = [
        ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'],
        { name:'basicstyles', items:['Bold','Italic'] },
        { name:'document',   items:['Source'] },
        { name:'tools',      items:['Maximize'] }
    ];
    var ckUrl = '{{ url('kcfinder_master') }}';
    ['permasalahan','temp_penanganan','perbaikan'].forEach(function(id) {
        CKEDITOR.replace(id, { filebrowserImageBrowseUrl: ckUrl, toolbar: ckToolbar, height: 100 });
    });

    /* ── Ready ── */
    jQuery(document).ready(function () {
        $('body').addClass('sidebar-collapse');
        $('#side_molding').addClass('menu-open');
        drawChart();
        $('.select2').select2({ allowClear: true });
        $('.select3').select2({ dropdownAutoWidth: true, allowClear: true, dropdownParent: $('#modal_penanganan') });
        $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });
    });

    /* ── STATUS PILL helper ── */
    function statusPill(s) {
        if (s === 'Open')            return "<span class='pill pill-open'>Open</span>";
        if (s === 'Temporary Close') return "<span class='pill pill-temp'>Temp. Close</span>";
        if (s === 'In-Progress')     return "<span class='pill pill-progress'>In-Progress</span>";
        return "<span class='pill pill-close'>Close</span>";
    }

    /* ── BUILD ROW ── */
    function buildRow(key, v) {
        var row = '<tr>';
        row += '<td><span class="no-badge">' + (key + 1) + '</span></td>';
        row += '<td>' + v.check_date + '</td>';
        row += '<td>' + v.molding_name + '</td>';
        row += '<td>' + v.part_name + '</td>';
        row += '<td style="max-width:200px;white-space:normal;">' + v.problem + '</td>';
        row += '<td>' + v.pic + '</td>';
        row += '<td style="text-align:center;">' + statusPill(v.status) + '</td>';
        row += '<td style="text-align:center;">';
        row += "<button class='btn-act btn-purple' style='margin-bottom:4px;'><i class='fas fa-info'></i> Detail</button><br>";
        if (v.status === 'Open' || v.status === 'Temporary Close' || v.status === 'In-Progress') {
            row += "<button class='btn-act btn-green' onclick='penangananModal(" + v.id + ")'><i class='fas fa-edit'></i> Penanganan</button>";
        }
        row += '</td></tr>';
        return row;
    }

    /* ── DRAW CHART ── */
    function drawChart() {
        var stat_val = $('#status').val();
        var data = {
            datefrom: $('#datefrom').val(),
            dateto:   $('#dateto').val(),
            status:   stat_val ? [stat_val] : ""
        };
        $.get('{{ url('fetch/workshop/check_molding_vendor/monitoring') }}', data, function (result) {
            if (!result.status) { openErrorGritter('Error', 'Gagal mengambil data'); return; }

            var kategori = [], open = [], close = [], temp_close = [], inprogress = [], temp_datas = [];
            $.each(result.datas, function (k, v) {
                if (kategori.indexOf(v.check_date) === -1) kategori.push(v.check_date);
            });
            $.each(kategori, function (k, v) {
                temp_datas.push({ tanggal: v, temp_close: 0, close: 0, open: 0, inprogress: 0 });
            });
            $.each(result.datas, function (k, v) {
                $.each(temp_datas, function (k2, v2) {
                    if (v.check_date === v2.tanggal) {
                        if      (v.status === 'Close')           v2.close      = v.jml;
                        else if (v.status === 'Temporary Close') v2.temp_close = v.jml;
                        else if (v.status === 'Open')            v2.open       = v.jml;
                        else if (v.status === 'In-Progress')     v2.inprogress = v.jml;
                    }
                });
            });
            $.each(temp_datas, function (k, v) {
                close.push(parseInt(v.close));
                temp_close.push(parseInt(v.temp_close));
                open.push(parseInt(v.open));
                inprogress.push(parseInt(v.inprogress));
            });

            /* update stat cards */
            var totOpen = 0, totTemp = 0, totProg = 0, totClose = 0;
            $.each(result.data_all, function (k, v) {
                if (v.status === 'Open')            totOpen++;
                else if (v.status === 'Temporary Close') totTemp++;
                else if (v.status === 'In-Progress')     totProg++;
                else                                     totClose++;
            });
            $('#sc-open').text(totOpen);
            $('#sc-temp').text(totTemp);
            $('#sc-prog').text(totProg);
            $('#sc-close').text(totClose);

            /* Highcharts modern */
            $('#chart_kategori').highcharts({
                chart: {
                    type: 'column', backgroundColor: '#fff',
                    style: { fontFamily: "'Plus Jakarta Sans', sans-serif" },
                    plotBorderWidth: 0, spacingTop: 10, spacingBottom: 10
                },
                title: { text: null },
                xAxis: {
                    categories: kategori, lineColor: '#e2e8f0', gridLineColor: '#f0f2f7',
                    labels: { style: { color: '#718096', fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" } }
                },
                yAxis: {
                    min: 0, gridLineColor: '#f0f2f7',
                    title: { text: 'Total Temuan', style: { color: '#a0aec0', fontSize: '12px' } },
                    stackLabels: { enabled: true, style: { fontWeight: '700', color: '#4a5568', fontFamily: "'Plus Jakarta Sans', sans-serif" } }
                },
                legend: { itemStyle: { color: '#4a5568', fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" } },
                plotOptions: {
                    series: {
                        cursor: 'pointer', borderRadius: 5, borderWidth: 0,
                        point: { events: { click: function () { filter_data(this.category, this.series.name); } } }
                    },
                    column: { stacking: 'normal', pointPadding: 0.1, groupPadding: 0.15 }
                },
                credits: { enabled: false },
                tooltip: {
                    backgroundColor: '#fff', borderColor: '#e2e8f0', borderRadius: 10,
                    style: { color: '#1a202c', fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '13px' },
                    formatter: function () { return '<b>' + this.series.name + '</b>: ' + this.y; }
                },
                series: [
                    { name: 'Open',            data: open,       color: '#ef4444' },
                    { name: 'Temporary Close', data: temp_close, color: '#f59e0b' },
                    { name: 'In-Progress',     data: inprogress, color: '#3b82f6' },
                    { name: 'Close',           data: close,      color: '#22c55e' }
                ]
            });

            /* build table */
            data_all = result.data_all;
            var body = '';
            var filterStatus = $('#status').val();
            $.each(result.data_all, function (k, v) {
                if (!filterStatus || filterStatus.length === 0) {
                    if (v.status === 'Open' || v.status === 'Temporary Close' || v.status === 'In-Progress') {
                        body += buildRow(k, v);
                    }
                } else {
                    body += buildRow(k, v);
                }
            });
            $('#body_penanganan').html(body || '<tr><td colspan="8" style="text-align:center;color:#a0aec0;padding:40px;">Tidak ada data temuan.</td></tr>');
        });
    }

    /* ── FILTER DATA ── */
    function filter_data(tanggal, stat) {
        var body = '';
        $.each(data_all, function (k, v) {
            if (tanggal !== '' && stat !== '') {
                if (v.status === stat && v.check_date === tanggal) body += buildRow(k, v);
            } else {
                if (v.status === 'Open' || v.status === 'Temporary Close' || v.status === 'In-Progress') body += buildRow(k, v);
            }
        });
        $('#body_penanganan').html(body || '<tr><td colspan="8" style="text-align:center;color:#a0aec0;padding:40px;">Tidak ada data.</td></tr>');
    }

    /* ── SIMPAN PENANGANAN ── */
    function simpanPenanganan() {
        $('#loading').addClass('show');
        var formData = new FormData();
        formData.append('finding_id',    $('#ids').val());
        formData.append('check_date',    $('#tanggal').val());
        formData.append('pic',           $('#pic').val());
        formData.append('molding_name',  $('#molding_name').val());
        formData.append('part_name',     $('#part_name').val());
        formData.append('status',        $('#status_problem').val());
        formData.append('handling_note', CKEDITOR.instances.perbaikan.getData());
        formData.append('perbaikan1',    $('#perbaikan1').prop('files')[0]);
        formData.append('perbaikan2',    $('#perbaikan2').prop('files')[0]);
        $.ajax({
            url: "{{ url('post/workshop/check_molding_vendor/penanganan') }}",
            method: 'POST', data: formData, dataType: 'JSON',
            contentType: false, cache: false, processData: false,
            success: function (response) {
                $('#loading').removeClass('show');
                $('#modal_penanganan').modal('hide');
                openSuccessGritter('Sukses', 'Penanganan berhasil disimpan');
                drawChart();
            },
            error: function () {
                $('#loading').removeClass('show');
                openErrorGritter('Error!', 'Gagal menyimpan penanganan');
            }
        });
    }

    /* ── PENANGANAN MODAL ── */
    function penangananModal(ids) {
        $('#modal_penanganan').modal('show');
        $.each(data_all, function (k, v) {
            if (v.id == ids) {
                $('#ids').val(ids);
                $('#tanggal').val(v.check_date);
                $('#pic').val(v.pic);
                $('#permasalahan').val(v.problem);
                $('#status_problem').val(v.status).trigger('change');
                $('#molding_name').val(v.molding_name);
                $('#part_name').val(v.part_name);
                CKEDITOR.instances['permasalahan'].setData(v.problem);
                CKEDITOR.instances['temp_penanganan'].setData(v.handling_temporary);
            }
        });
        $('#bodyRiwayat').html('<tr><td colspan="5" style="text-align:center;color:#a0aec0;padding:20px;"><i class="fas fa-spinner fa-spin"></i> Memuat...</td></tr>');
        var imgBase = '{{ url('workshop/Audit_Molding/Check_Molding/handling_att') }}/';
        $.get('{{ url('fetch/workshop/check_molding_vendor/penanganan/log') }}', { id: ids }, function (result) {
            var body = '';
            $.each(result.datas, function (k, v) {
                body += '<tr>';
                body += '<td>' + (k+1) + '</td>';
                body += '<td>' + v.handling_date + '</td>';
                body += '<td>' + statusPill(v.status) + '</td>';
                body += '<td style="text-align:left;">' + v.handling_note + '</td>';
                body += '<td><img style="max-width:90px;border-radius:6px;margin:2px;" src="' + imgBase + v.handling_att1 + '">'
                      + '<img style="max-width:90px;border-radius:6px;margin:2px;" src="' + imgBase + v.handling_att2 + '"></td>';
                body += '</tr>';
            });
            $('#bodyRiwayat').html(body || '<tr><td colspan="5" style="text-align:center;color:#a0aec0;padding:20px;">Belum ada riwayat.</td></tr>');
        });
    }

    /* ── SCHEDULE ── */
    function openModalSchedule() { $('#modal_schedule').modal('show'); $('#body_schedule').empty(); }

    function add_schedule() {
        var body = '<tr><td>';
        body += '<select class="select3 sch sched-select" data-placeholder="Pilih Molding">';
        body += '<option value=""></option>';
        $.each(moldings, function (k, v) {
            body += '<option value="' + v.id + '">' + v.molding_name + ' #' + v.mold_number + '</option>';
        });
        body += '</select></td>';
        body += '<td><button class="btn-act btn-danger-soft" onclick="$(this).closest(\'tr\').remove()"><i class="fas fa-trash"></i></button></td></tr>';
        $('#body_schedule').append(body);
        $('.select3').select2({ dropdownParent: $('#modal_schedule') });
    }

    function saveSchedule() {
        $('#loading').addClass('show');
        var formData = new FormData();
        formData.append('mon_cek', $('#periode_cek').val() + '-01');
        var id_molding = [];
        $('.sch').each(function (k, v) { id_molding.push($(v).val()); });
        formData.append('molding', id_molding);
        $.ajax({
            url: "{{ url('post/workshop/check_molding_vendor/schedule') }}",
            method: 'POST', data: formData, dataType: 'JSON',
            contentType: false, cache: false, processData: false,
            success: function (response) {
                $('#loading').removeClass('show');
                $('#modal_schedule').modal('hide');
                openSuccessGritter('Sukses', 'Schedule berhasil disimpan');
            },
            error: function () {
                $('#loading').removeClass('show');
                openErrorGritter('Error!', 'Gagal menyimpan schedule');
            }
        });
    }

    /* ── GRITTER ── */
    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success',
            image: '{{ url('images/image-screen.png') }}', sticky: false, time: '2500' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger',
            image: '{{ url('images/image-stop.png') }}', sticky: false, time: '2500' });
    }
</script>
@endsection