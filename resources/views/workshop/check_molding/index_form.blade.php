@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ══ BASE ══ */
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="select2"]):not([class*="ck"]),
    body div:not([class*="ck"]), body label, body input, body select, body textarea,
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
        padding: 26px 32px 22px; margin: 20px 0 22px;
        border-radius: 18px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 14px;
        position: relative; overflow: hidden;
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
        padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
        cursor: pointer; text-decoration: none; transition: all .18s; border: none;
    }
    .btn-hdr-ghost { background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25); color: #fff; }
    .btn-hdr-ghost:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }
    .btn-hdr-amber { background: #f59e0b; color: #fff; box-shadow: 0 3px 10px rgba(245,158,11,.3); }
    .btn-hdr-amber:hover { background: #d97706; color: #fff; }

    /* ══ INFO CARDS (General Info) ══ */
    .info-grid {
        display: grid; grid-template-columns: repeat(4,1fr); gap: 12px; margin-bottom: 20px;
    }
    .info-item {
        background: #fff; border: 1.5px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;
        box-shadow: 0 1px 4px rgba(0,0,0,.04);
    }
    .info-item.accent { background: linear-gradient(135deg,#f0eef9,#e9e6f5); border-color: #c4bfef; }
    .info-lbl {
        font-size: 10.5px; font-weight: 700; color: #a0aec0;
        letter-spacing: .08em; text-transform: uppercase; margin-bottom: 5px;
    }
    .info-val { font-size: 15px; font-weight: 700; color: #1a202c; }
    .info-item.accent .info-val { color: #4a4690; }

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
    .dot-blue   { background: #3b82f6; }
    .dot-red    { background: #ef4444; }
    .dot-green  { background: #22c55e; }
    .sec-card-body { padding: 18px 20px; }

    /* ══ PART BUTTONS ══ */
    .part-grid { display: flex; flex-wrap: wrap; gap: 8px; }
    .part-btn {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 9px; border: none;
        font-size: 12.5px; font-weight: 600; cursor: pointer; transition: all .18s;
    }
    .part-btn-pending { background: #fee2e2; color: #dc2626; border: 1.5px solid #fca5a5; }
    .part-btn-pending:hover { background: #fecaca; }
    .part-btn-done    { background: #dcfce7; color: #15803d; border: 1.5px solid #86efac; }
    .part-btn-done:hover    { background: #bbf7d0; }

    /* ══ CHECKLIST TABLE ══ */
    .cek-wrap { overflow-x: auto; }
    .cek-table { width: 100%; border-collapse: collapse; }
    .cek-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700; letter-spacing: .06em; text-transform: uppercase;
        padding: 10px 12px; border-bottom: 2px solid #edf0f5;
        white-space: nowrap; text-align: center;
    }
    .cek-table tbody td {
        padding: 10px 12px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: top; text-align: center;
    }
    .cek-table tbody td.left { text-align: left; }
    .cek-table tbody tr:hover td { background: #f5f8ff; }
    .cek-table tbody tr:last-child td { border-bottom: none; }

    /* ══ PHOTO SECTION ══ */
    .photo-group { text-align: left; }
    .photo-group-lbl {
        font-size: 11px; font-weight: 700; color: #605ca8;
        text-transform: uppercase; letter-spacing: .06em;
        margin-bottom: 7px; display: flex; align-items: center; gap: 5px;
    }
    .photo-group-lbl .req { color: #dc2626; }
    .photo-btn-row { display: flex; gap: 6px; margin-bottom: 7px; }
    .btn-photo {
        flex: 1; display: inline-flex; align-items: center; justify-content: center; gap: 5px;
        padding: 6px 10px; border-radius: 8px; border: 1.5px solid #c4bfef;
        background: #f0eef9; color: #605ca8; font-size: 11.5px; font-weight: 700;
        cursor: pointer; transition: all .18s;
    }
    .btn-photo:hover { background: #e2dff5; border-color: #8b87d4; }
    .photo-previews { display: flex; gap: 6px; flex-wrap: wrap; }
    .photo-previews img {
        width: 80px; height: 80px; object-fit: cover; border-radius: 8px;
        border: 2px solid #ede9fe; display: none;
    }
    .photo-sep { height: 1px; background: #f0f2f7; margin: 10px 0; }

    /* ══ FORM FIELDS ══ */
    .ff { display: flex; flex-direction: column; gap: 5px; margin-bottom: 14px; }
    .ff:last-child { margin-bottom: 0; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff label .req { color: #dc2626; }
    .ff input, .ff select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 8px 12px !important; font-size: 13px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input:focus, .ff select:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }

    /* ══ SELECT2 MODERN ══ */
    .select2-container--default .select2-selection--single,
    .select2-container--default .select2-selection--multiple {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        height: auto !important;
        min-height: 36px !important;
        padding: 4px 10px !important;
        font-size: 13px !important;
        color: #1a202c !important;
        background: #fafbff !important;
        outline: none !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .select2-container--default.select2-container--open .select2-selection--single,
    .select2-container--default.select2-container--open .select2-selection--multiple {
        border-color: #605ca8 !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        color: #1a202c !important;
        padding-left: 0 !important;
        padding-right: 0 !important;
        /* line-height: 24px !important; */
        margin-top: 0px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #a0aec0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 100% !important;
        right: 8px !important;
    }

    /* ══ STATUS PILLS ══ */
    .pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap;
    }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-ok     { background: #dcfce7; color: #15803d; }
    .pill-ok::before   { background: #22c55e; }
    .pill-ng     { background: #fee2e2; color: #dc2626; }
    .pill-ng::before   { background: #ef4444; }
    .pill-open   { background: #fee2e2; color: #dc2626; }
    .pill-open::before { background: #ef4444; }
    .pill-temp   { background: #fef3c7; color: #92400e; }
    .pill-temp::before { background: #f59e0b; }
    .pill-close  { background: #dcfce7; color: #15803d; }
    .pill-close::before{ background: #22c55e; }

    /* ══ BUTTONS ══ */
    .btn-modern {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 9px 18px; border-radius: 9px; border: none;
        font-size: 13px; font-weight: 700; cursor: pointer; transition: all .18s;
    }
    .btn-modern:hover { opacity: .87; transform: translateY(-1px); }
    .btn-purple { background: linear-gradient(135deg,#4a4690,#605ca8); color:#fff; box-shadow:0 3px 10px rgba(96,92,168,.3); }
    .btn-green  { background: linear-gradient(135deg,#15803d,#16a34a); color:#fff; box-shadow:0 3px 10px rgba(21,128,61,.3); }
    .btn-red    { background: linear-gradient(135deg,#b91c1c,#dc2626); color:#fff; }
    .btn-yellow { background: linear-gradient(135deg,#b45309,#f59e0b); color:#fff; }
    .btn-act-sm {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px; border: none;
        font-size: 13px; cursor: pointer; transition: all .18s; margin: 2px;
    }
    .btn-act-sm:hover { opacity: .85; transform: scale(1.08); }
    .btn-act-sm-red    { background: #fee2e2; color: #dc2626; }
    .btn-act-sm-yellow { background: #fef3c7; color: #92400e; }

    .btn-submit-full {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 14px; border: none; border-radius: 12px;
        background: linear-gradient(135deg,#15803d,#16a34a);
        color: #fff; font-size: 15px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 16px rgba(21,128,61,.3); transition: all .18s;
    }
    .btn-submit-full:hover { opacity: .9; transform: translateY(-1px); }

    /* ══ MODAL ══ */
    .mh {
        padding: 18px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .mh h4 { margin: 0; font-size: 15px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 9px; }
    .mh-close {
        width: 30px; height: 30px; border-radius: 8px; border: none;
        background: #fee2e2; color: #dc2626; font-size: 16px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
    }
    .mh-close:hover { background: #fecaca; }
    .mb { padding: 20px 24px; }
    .mf { padding: 14px 24px; border-top: 1px solid #f0f2f7; background: #fafbff; display: flex; gap: 10px; justify-content: flex-end; }
    .btn-mf-cancel {
        padding: 9px 18px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #718096; font-size: 13px; font-weight: 600; cursor: pointer;
    }
    .btn-mf-cancel:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }
    .btn-mf-save {
        padding: 9px 20px; border-radius: 9px; border: none;
        background: linear-gradient(135deg,#15803d,#16a34a); color: #fff;
        font-size: 13px; font-weight: 700; cursor: pointer; box-shadow: 0 3px 10px rgba(21,128,61,.25);
    }

    /* modal table */
    .modal-table { width: 100%; border-collapse: collapse; font-size: 13px; }
    .modal-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 9px 12px;
        border-bottom: 1.5px solid #edf0f5; text-align: center;
    }
    .modal-table tbody td {
        padding: 9px 12px; color: #2d3748; border-bottom: 1px solid #f0f2f7; vertical-align: middle; text-align: center;
    }
    .modal-table tbody tr:last-child td { border-bottom: none; }

    /* photo upload inside modal */
    .photo-upload-wrap {
        border: 1.5px dashed #c4bfef; border-radius: 10px;
        background: #faf9ff; padding: 12px; margin-top: 6px; transition: border-color .18s;
    }
    .photo-upload-wrap:hover { border-color: #605ca8; }
    .photo-upload-wrap input[type="file"] {
        border: none !important; background: transparent !important;
        padding: 0 !important; font-size: 12.5px !important; width: 100% !important; cursor: pointer; color: #4a5568 !important; margin-bottom: 6px;
    }
    .photo-upload-label { font-size: 11px; font-weight: 700; color: #605ca8; text-transform: uppercase; letter-spacing: .06em; margin-bottom: 8px; display: block; }
    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 20px; }

    /* ══ MISC ══ */
    .page-wrapper { padding-top: 0 !important; }
    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th,
    .datepicker-months > table > thead > tr > th,
    .datepicker-years > table > thead > tr > th {
        background-color: white; color: #696969 !important;
    }
</style>
@stop

@section('header')@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Loading --}}
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
            <div class="badge-tag"><i class="fas fa-cube"></i> Audit Molding</div>
            <h1>Audit Molding</h1>
            <p>Pengecekan kondisi &amp; kelayakan molding vendor</p>
        </div>
        <div class="header-right">
            <button class="btn-hdr btn-hdr-amber" onclick="openModal('modal_problem_log')">
                <i class="fas fa-exclamation-triangle"></i> Riwayat Temuan
            </button>
            <button class="btn-hdr btn-hdr-ghost" onclick="openModal('modal_history')">
                <i class="fas fa-history"></i> Riwayat Pengecekan
            </button>
        </div>
    </div>

    {{-- ── GENERAL INFORMATION ── --}}
    <input type="hidden" id="molding_category">
    <input type="hidden" id="employee_id">
    <input type="hidden" name="type_molding" id="type_molding">
    <input type="hidden" name="molding_number" id="molding_number">
    <input type="hidden" id="molding_id">

    <div class="info-grid">
        <div class="info-item">
            <div class="info-lbl"><i class="fas fa-calendar-alt" style="margin-right:4px;"></i>Tanggal Audit</div>
            <div class="info-val"><?= date('d F Y') ?></div>
        </div>
        <div class="info-item accent">
            <div class="info-lbl"><i class="fas fa-cube" style="margin-right:4px;"></i>Nama Molding</div>
            <div class="info-val" id="molding_name">—</div>
        </div>
        <div class="info-item accent">
            <div class="info-lbl"><i class="fas fa-user" style="margin-right:4px;"></i>PIC</div>
            <div class="info-val" id="employee_name">—</div>
        </div>
        <div class="info-item">
            <div class="info-lbl"><i class="fas fa-map-marker-alt" style="margin-right:4px;"></i>Lokasi</div>
            <div class="info-val" id="location">ARISA</div>
        </div>
    </div>

    {{-- ── PART LIST ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <div class="sec-card-title"><span class="dot dot-blue"></span> Part List Molding</div>
        </div>
        <div class="sec-card-body">
            <div class="part-grid" id="div_part">
                <span style="color:#a0aec0;font-size:13px;"><i class="fas fa-info-circle" style="margin-right:5px;"></i>Pilih Molding untuk menampilkan part list.</span>
            </div>
        </div>
    </div>

    {{-- ── PENGECEKAN MOLDING ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <div class="sec-card-title"><span class="dot dot-red"></span> Pengecekan Molding</div>
            <button class="btn-modern btn-yellow" style="padding:7px 14px;font-size:12px;" onclick="openModal('modal_problem_log')">
                <i class="fas fa-book"></i> Riwayat Temuan
            </button>
        </div>
        <div class="cek-wrap">
            <table class="cek-table" id="tableResult">
                <thead>
                    <tr>
                        <th style="width:36px;">No</th>
                        <th style="text-align:left;min-width:120px;">Nama Part</th>
                        <th style="min-width:140px;">Poin Cek</th>
                        <th style="min-width:130px;">Standar</th>
                        <th style="min-width:130px;">Cara Pengecekan</th>
                        <th style="min-width:130px;">Cara Penanganan</th>
                        <th style="min-width:260px;">Eviden</th>
                        <th style="min-width:100px;">Judgement</th>
                        <th style="width:80px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="body_cek">
                    <tr>
                        <td colspan="9" style="text-align:center;color:#a0aec0;padding:32px 16px;font-size:13px;">
                            <i class="fas fa-mouse-pointer" style="margin-right:6px;"></i>Klik salah satu part untuk memulai pengecekan.
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
        <div style="padding:16px 20px;border-top:1px solid #f0f2f7;background:#fafbff;">
            <button class="btn-submit-full" onclick="cek()">
                <i class="fas fa-paper-plane"></i> Submit Pengecekan
            </button>
        </div>
    </div>

</div>

{{-- ══ MODAL: Pilih Periode, PIC & Molding ══ --}}
<div class="modal modal-default fade" id="molding_select">
    <div class="modal-dialog" style="max-width:480px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-cube" style="color:#605ca8;"></i> Pilih Periode, PIC &amp; Molding</h4>
                <button type="button" class="mh-close" data-dismiss="modal" onclick="$('#molding_select').modal('hide');">&times;</button>
            </div>
            <div class="mb" style="display:flex;flex-direction:column;gap:16px;">
                <div class="ff" style="margin-bottom:0;">
                    <label>Periode Cek <span class="req">*</span></label>
                    <select class="select2" id="prd" style="width:100%;" data-placeholder="Pilih Periode" onchange="loadMolding(this)">
                        <option value=""></option>
                        @foreach ($period as $pr)
                            <option value="{{ $pr }}">{{ $pr }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>PIC <span class="req">*</span></label>
                    <select class="select2" id="pic" style="width:100%;" data-placeholder="Pilih PIC">
                        <option value=""></option>
                        @foreach ($pics as $pic)
                            <option value="{{ $pic->employee_id }}">{{ $pic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Molding <span class="req">*</span></label>
                    <select class="select2" id="moldings" style="width:100%;" data-placeholder="Pilih Molding">
                        <option value=""></option>
                    </select>
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" onclick="$('#molding_select').modal('hide');"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-save" onclick="selectMolding()">
                    <i class="fas fa-check"></i> OK, Mulai Audit
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: Riwayat Pengecekan ══ --}}
<div class="modal fade" id="modal_history" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:1200px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-history" style="color:#605ca8;"></i> Riwayat Pengecekan</h4>
                <button type="button" class="mh-close" data-dismiss="modal" onclick="$('#modal_history').modal('hide');">&times;</button>
            </div>
            <div class="mb">
                <div class="g2" style="margin-bottom:14px;">
                    <div class="ff" style="margin-bottom:0;">
                        <label>Tanggal Dari</label>
                        <input type="text" class="datepicker2" id="date_from" name="date_from" placeholder="yyyy-mm-dd" autocomplete="off">
                    </div>
                    <div class="ff" style="margin-bottom:0;">
                        <label>Tanggal Sampai</label>
                        <input type="text" class="datepicker2" id="date_to" name="date_to" placeholder="yyyy-mm-dd" autocomplete="off">
                    </div>
                </div>
                <div class="ff" style="margin-bottom:14px;">
                    <label>Molding</label>
                    <select class="form-control select4" multiple="multiple" id="molding_select" data-placeholder="Pilih Molding" style="width:100%;">
                        @foreach ($moldings as $molding)
                            <option value="{{ $molding->molding_name }}">{{ $molding->molding_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="ff" style="margin-bottom:16px;">
                    <label>PIC</label>
                    <select class="select4" id="pic_select" multiple="multiple" style="width:100%;" data-placeholder="Pilih PIC">
                        @foreach ($pics as $pic)
                            <option value="{{ $pic->employee_id }}">{{ $pic->name }}</option>
                        @endforeach
                    </select>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
                    <button class="btn-modern btn-purple" onclick="fetchDetailRecord()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <div style="overflow-x:auto;">
                    <table class="modal-table" id="tableDetail">
                        <thead>
                            <tr>
                                <th rowspan="2">Id</th>
                                <th rowspan="2">Tanggal</th>
                                <th rowspan="2">Molding</th>
                                <th rowspan="2">PIC</th>
                                <th rowspan="2">Poin Cek</th>
                                <th colspan="3">Eviden</th>
                                <th rowspan="2">Judgement</th>
                                <th rowspan="2">Status</th>
                            </tr>
                            <tr>
                                <th>Before</th>
                                <th>After</th>
                                <th>Aktifitas</th>
                            </tr>
                        </thead>
                        <tbody id="bodyTableDetail"></tbody>
                    </table>
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" onclick="$('#modal_history').modal('hide');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: Tuliskan Permasalahan ══ --}}
<div class="modal modal-default fade" id="modal_problem">
    <div class="modal-dialog modal-lg" style="max-width:740px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i> Tuliskan Permasalahan</h4>
                <button type="button" class="mh-close" data-dismiss="modal" onclick="$('#modal_problem').modal('hide');">&times;</button>
            </div>
            <div class="mb" style="display:flex;flex-direction:column;gap:14px;">
                <div class="g2">
                    <div class="ff" style="margin-bottom:0;">
                        <label>Nama Molding</label>
                        <input type="text" id="nama_molding" readonly>
                    </div>
                    <div class="ff" style="margin-bottom:0;">
                        <label>Nama Part</label>
                        <input type="text" id="nama_part" readonly>
                    </div>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Permasalahan <span class="req">*</span></label>
                    <textarea id="permasalahan" style="border:1.5px solid #e2e8f0;border-radius:9px;padding:9px 12px;font-size:13px;background:#fafbff;width:100%;"></textarea>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Foto Permasalahan</label>
                    <div class="photo-upload-wrap">
                        <span class="photo-upload-label"><i class="fas fa-camera" style="margin-right:4px;"></i>Pilih Foto</span>
                        <input type="file" id="permasalahan1" class="permasalahan1" accept="image/*" onchange="readURL2(this,'img_permasalahan1')" style="margin-bottom:6px;">
                        <input type="file" id="permasalahan2" class="permasalahan2" accept="image/*" onchange="readURL2(this,'img_permasalahan2')">
                        <div class="photo-previews" style="margin-top:8px;">
                            <img src="" class="img_permasalahan1" alt="">
                            <img src="" class="img_permasalahan2" alt="">
                        </div>
                    </div>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Perbaikan Sementara <span class="req">*</span></label>
                    <textarea id="perbaikan_sementara" style="border:1.5px solid #e2e8f0;border-radius:9px;padding:9px 12px;font-size:13px;background:#fafbff;width:100%;"></textarea>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Foto Perbaikan</label>
                    <div class="photo-upload-wrap">
                        <span class="photo-upload-label"><i class="fas fa-camera" style="margin-right:4px;"></i>Pilih Foto</span>
                        <input type="file" id="perbaikan1" class="perbaikan1" accept="image/*" onchange="readURL2(this,'img_perbaikan1')" style="margin-bottom:6px;">
                        <input type="file" id="perbaikan2" class="perbaikan2" accept="image/*" onchange="readURL2(this,'img_perbaikan2')">
                        <div class="photo-previews" style="margin-top:8px;">
                            <img src="" class="img_perbaikan1" alt="">
                            <img src="" class="img_perbaikan2" alt="">
                        </div>
                    </div>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Catatan</label>
                    <textarea id="note" style="border:1.5px solid #e2e8f0;border-radius:9px;padding:9px 12px;font-size:13px;background:#fafbff;width:100%;"></textarea>
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Status <span class="req">*</span></label>
                    <div id="status_div" style="width:220px;">
                        <select class="select5" id="status" style="width:100%;" data-placeholder="Pilih Status">
                            <option value=""></option>
                            <option value="Open">Open</option>
                            <option value="Temporary Close">Temporary Close</option>
                            <option value="Close">Close</option>
                        </select>
                    </div>
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" onclick="$('#modal_problem').modal('hide');"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-save" onclick="simpanTemuan()">
                    <i class="fas fa-save"></i> Simpan Temuan
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══ MODAL: Riwayat Temuan ══ --}}
<div class="modal modal-default fade" id="modal_problem_log">
    <div class="modal-dialog modal-lg" style="max-width:1200px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 20px 60px rgba(0,0,0,.2);">
            <div class="mh">
                <h4><i class="fas fa-exclamation-triangle" style="color:#f59e0b;"></i> Riwayat Temuan</h4>
                <button type="button" class="mh-close" data-dismiss="modal" onclick="$('#modal_problem_log').modal('hide');">&times;</button>
            </div>
            <div class="mb">
                <div class="g2" style="margin-bottom:14px;">
                    <div class="ff" style="margin-bottom:0;">
                        <label>Tanggal Dari</label>
                        <input type="text" class="datepicker2" id="riwayat_dari" placeholder="yyyy-mm-dd">
                    </div>
                    <div class="ff" style="margin-bottom:0;">
                        <label>Tanggal Sampai</label>
                        <input type="text" class="datepicker2" id="riwayat_sampai" placeholder="yyyy-mm-dd">
                    </div>
                </div>
                <div style="display:flex;justify-content:flex-end;margin-bottom:16px;">
                    <button class="btn-modern btn-purple" onclick="cariTemuan()">
                        <i class="fas fa-search"></i> Cari
                    </button>
                </div>
                <div style="overflow-x:auto;">
                    <table class="modal-table" id="tableMasalah">
                        <thead>
                            <tr>
                                <th style="width:36px;">No.</th>
                                <th>Tanggal</th>
                                <th>Nama Molding</th>
                                <th>Nama Part</th>
                                <th>Permasalahan</th>
                                <th>Perbaikan Sementara</th>
                                <th>Note</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody id="bodyMasalah"></tbody>
                    </table>
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" onclick="$('#modal_problem_log').modal('hide');"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ url('js/bootstrap-toggle.min.js') }}"></script>
<script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/vfs_fonts.js') }}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('ckeditor/ckeditor.js') }}"></script>
<script src="{{ url('js/compressImage.js') }}"></script>

<!-- <script src="{{ asset('js/jquery-3.5.1.js') }}"></script> -->

<script src="{{ asset('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ asset('js/dataTables.bootstrap4.min.js') }}"></script>

<script src="{{ asset('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ asset('js/buttons.html5.min.js') }}"></script>
<script src="{{ asset('js/buttons.print.min.js') }}"></script>

<script src="{{ asset('js/jszip.min.js') }}"></script>

<script src="{{ asset('js/exceljs.min.js') }}"></script>
<script src="{{ asset('js/FileSaver.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var check_point  = <?php echo json_encode($check_points); ?>;
    var moldings     = <?php echo json_encode($moldings); ?>;
    var part_status  = [];
    var part_err     = [];
    var num          = 1;
    const compressedFiles = [];

    /* ── CKEditor ── */
    var ckCfg = {
        filebrowserImageBrowseUrl: '{{ url('kcfinder_master') }}',
        toolbar: [
            ['Cut','Copy','Paste','PasteText','PasteFromWord','-','Undo','Redo'],
            { name:'basicstyles', items:['Bold','Italic'] },
            { name:'document',   items:['Source'] },
            { name:'tools',      items:['Maximize'] }
        ],
        height: 100
    };
    CKEDITOR.replace('permasalahan',      ckCfg);
    CKEDITOR.replace('perbaikan_sementara', ckCfg);
    CKEDITOR.replace('note',              ckCfg);

    /* ── Ready ── */
    jQuery(document).ready(function () {
        $('#wrapper').toggleClass('toggled');
        $('#molding_select').modal('show');
        $('body').toggleClass('sidebar-collapse');
        $('#side_molding').addClass('menu-open');

        $('.select2').select2({ dropdownAutoWidth: true, allowClear: true, dropdownParent: $('#molding_select') });
        $('.select3').select2({ minimumResultsForSearch: -1, dropdownAutoWidth: true, allowClear: true });
        $('.select4').select2({ dropdownAutoWidth: true, dropdownParent: $('#modal_history') });
        $('.select5').select2({ dropdownAutoWidth: true, dropdownParent: $('#status_div') });
        $('.datepicker2').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });
    });

    /* ── SELECT MOLDING ── */
    function selectMolding() {
        if ($('#moldings').val() == '' || $('#pic').val() == '') {
            openErrorGritter('Gagal', 'Pilih PIC dan Molding terlebih dahulu');
            return false;
        }
        $('#molding_id').val($('#moldings').val());
        $('#molding_select').modal('hide');
        $('#molding_name').text($('#moldings option:selected').text());
        $('#employee_name').text($('#pic').val() + ' - ' + $('#pic option:selected').text());

        var type = '', mold_num = '';
        $.each(moldings, function (i, v) {
            if (v.molding_type + '_' + v.molding_category == $('#moldings').val()) {
                type     = v.molding_type;
                mold_num = v.mold_number;
                $('#molding_category').val(v.molding_category);
            }
        });
        $('#type_molding').val(type);
        $('#molding_number').val(mold_num);

        num = 1;
        var body = '';
        var param = { molding: $('#moldings').val(), period: $('#prd').val() };
        $.get('{{ url('fetch/workshop/check_molding_vendor/part') }}', param, function (data) {
            $('#div_part').empty();
            $.each(data.molding_part, function (i, v) {
                if (v.molding_type + '_' + v.molding_category == $('#molding_id').val()) {
                    var cls = v.sudah ? 'part-btn-done' : 'part-btn-pending';
                    var ico = v.sudah ? 'fa-check' : 'fa-wrench';
                    body += '<button class="part-btn ' + cls + '" onclick="add_point(\'' + v.part_name + '\')">'
                          + '<i class="fas ' + ico + '"></i> ' + num + ') ' + v.part_name + '</button>';
                    num++;
                }
            });
            $('#div_part').html(body || '<span style="color:#a0aec0;font-size:13px;">Tidak ada part ditemukan.</span>');
        });
    }

    /* ── ADD POINT ── */
    function add_point(nama_part) {
        $('#body_cek').empty();
        var body = '<tr>';
        body += '<td>1</td>';
        body += '<td class="part left" style="font-weight:600;">' + nama_part;
        if (UrlExists('{{ url('workshop/Audit_Molding/Part_Image') }}/' + $('#molding_id').val() + '/' + nama_part + '.jpg')) {
            body += '<br><img src="{{ url('workshop/Audit_Molding/Part_Image') }}/' + $('#molding_id').val() + '/' + nama_part + '.jpg" style="max-width:120px;margin-top:8px;border-radius:8px;">';
        }
        body += '</td>';

        /* Poin Cek */
        body += '<td><select class="form-control select3 cek_poin" data-placeholder="Pilih" style="width:100%;" onchange="changeCek(this)">';
        body += '<option></option>';
        $.each(check_point, function (i, v) {
            body += '<option value="' + v.check_point + '">' + v.poin_cek + '</option>';
        });
        body += '</select></td>';
        body += '<td class="standar left" style="font-size:12px;"></td>';
        body += '<td class="cara_cek left" style="font-size:12px;"></td>';
        body += '<td class="penanganan left" style="font-size:12px;"></td>';

        /* Eviden */
        body += '<td><div class="photo-group">';

        /* Before */
        body += '<div class="photo-group-lbl"><span class="req">*</span>&nbsp;Foto Before</div>';
        body += '<input type="file" class="before1" id="img_before1" accept="image/*" onchange="readURL(this,\'img_before1\');" style="display:none;">';
        body += '<input type="file" class="before2" id="img_before2" accept="image/*" onchange="readURL(this,\'img_before2\');" style="display:none;">';
        body += '<div class="photo-btn-row"><button class="btn-photo" onclick="buttonImage(this,\'before1\')"><i class="fas fa-camera"></i> Photo 1</button><button class="btn-photo" onclick="buttonImage(this,\'before2\')"><i class="fas fa-camera"></i> Photo 2</button></div>';
        body += '<div class="photo-previews"><img src="" class="img_before1" alt=""><img src="" class="img_before2" alt=""></div>';
        body += '<div class="photo-sep"></div>';

        /* After */
        body += '<div class="photo-group-lbl"><span class="req">*</span>&nbsp;Foto After</div>';
        body += '<input type="file" class="after1" accept="image/*" onchange="readURL(this,\'img_after1\');" style="display:none;">';
        body += '<input type="file" class="after2" accept="image/*" onchange="readURL(this,\'img_after2\');" style="display:none;">';
        body += '<div class="photo-btn-row"><button class="btn-photo" onclick="buttonImage(this,\'after1\')"><i class="fas fa-camera"></i> Photo 1</button><button class="btn-photo" onclick="buttonImage(this,\'after2\')"><i class="fas fa-camera"></i> Photo 2</button></div>';
        body += '<div class="photo-previews"><img src="" class="img_after1" alt=""><img src="" class="img_after2" alt=""></div>';
        body += '<div class="photo-sep"></div>';

        /* Aktifitas */
        body += '<div class="photo-group-lbl">Aktifitas Pekerjaan</div>';
        body += '<input type="file" class="aktifitas1" accept="image/*" onchange="readURL(this,\'img_aktifitas1\');" style="display:none;">';
        body += '<input type="file" class="aktifitas2" accept="image/*" onchange="readURL(this,\'img_aktifitas2\');" style="display:none;">';
        body += '<div class="photo-btn-row"><button class="btn-photo" onclick="buttonImage(this,\'aktifitas1\')"><i class="fas fa-camera"></i> Photo 1</button><button class="btn-photo" onclick="buttonImage(this,\'aktifitas2\')"><i class="fas fa-camera"></i> Photo 2</button></div>';
        body += '<div class="photo-previews"><img src="" class="img_aktifitas1" alt=""><img src="" class="img_aktifitas2" alt=""></div>';
        body += '</div></td>';

        /* Judgement */
        body += '<td><select class="form-control select3 judgement" data-placeholder="Judgement" style="width:100%;"><option value=""></option><option value="OK">OK</option><option value="NG">NG</option></select></td>';

        /* Action */
        body += '<td>';
        body += '<button class="btn-act-sm btn-act-sm-red" style="display:block;margin-bottom:4px;width:32px;" onclick="delete_cek(this)"><i class="fas fa-trash"></i></button>';
        body += '<button class="btn-act-sm btn-act-sm-yellow" style="display:block;width:32px;" onclick="modal_problem(this)"><i class="fas fa-exclamation-triangle"></i></button>';
        body += '</td></tr>';

        $('#body_cek').append(body);
        $('.select3').select2({ minimumResultsForSearch: -1, dropdownAutoWidth: true, allowClear: true });
    }

    /* ── HELPERS ── */
    function buttonImage(elem, cls) { $(elem).parent().find('.' + cls).click(); }

    function changeCek(elem) {
        $.each(check_point, function (i, v) {
            if ($(elem).val() == v.check_point) {
                $(elem).closest('tr').find('.standar').text(v.std);
                $(elem).closest('tr').find('.cara_cek').text(v.how);
                $(elem).closest('tr').find('.penanganan').text(v.handle2);
            }
        });
    }

    function delete_cek(elem) { $(elem).closest('tr').remove(); }

    function modal_problem(elem) {
        $('#modal_problem').modal('show');
        $('#nama_molding').val($('#molding_name').text());
        $('#nama_part').val($(elem).closest('tr').find('.part').text());
    }

    /* ── CEK / SUBMIT ── */
    function cek() {
        $('#loading').addClass('show');
        var formData = new FormData();
        var part = [], cek_poin = [], judgement = [];
        var status = true, status2 = true;
        part_err = [];

        $('.part').each(function (i, obj) { if (!$(obj).text()) status = false; part.push($(obj).text()); });
        if (!status) { openErrorGritter('Error', 'Lengkapi kolom Part'); $('#loading').removeClass('show'); return false; }

        $('.cek_poin').each(function (i, obj) { if (!$(obj).val()) status = false; cek_poin.push($(obj).val()); });
        if (!status) { openErrorGritter('Error', 'Lengkapi kolom Poin Cek'); $('#loading').removeClass('show'); return false; }

        $('.before1').each(function (i, obj) { if (typeof $(obj).prop('files')[0] === 'undefined') status = false; formData.append('before1_' + i, compressedFiles[i]); });
        $('.before2').each(function (i, obj) { if (typeof $(obj).prop('files')[0] === 'undefined') status = false; formData.append('before2_' + i, $(obj).prop('files')[0]); });
        if (!status) { openErrorGritter('Error', 'Lengkapi Foto Before'); $('#loading').removeClass('show'); return false; }

        $('.after1').each(function (i, obj) { if (typeof $(obj).prop('files')[0] === 'undefined') status = false; formData.append('after1_' + i, $(obj).prop('files')[0]); });
        $('.after2').each(function (i, obj) { if (typeof $(obj).prop('files')[0] === 'undefined') status = false; formData.append('after2_' + i, $(obj).prop('files')[0]); });
        if (!status) { openErrorGritter('Error', 'Lengkapi Foto After'); $('#loading').removeClass('show'); return false; }

        $('.aktifitas1').each(function (i, obj) { formData.append('aktifitas1_' + i, $(obj).prop('files')[0]); });
        $('.aktifitas2').each(function (i, obj) { compressedFiles.push($(obj).prop('files')[0]); formData.append('aktifitas2_' + i, compressedFiles[i]); });

        $('.judgement').each(function (i, obj) {
            if (!$(obj).val()) status = false;
            judgement.push($(obj).val());
            if ($(obj).val() == 'NG') {
                var prt = $('.part').eq(i).text();
                if (!findItem(part_status, prt)) { part_err.push(prt); status2 = false; }
            }
        });
        if (!status2) { openErrorGritter('Error', 'Lengkapi Form Temuan: ' + part_err.join(', ')); $('#loading').removeClass('show'); return false; }
        if (!status)  { openErrorGritter('Error', 'Lengkapi kolom Judgement'); $('#loading').removeClass('show'); return false; }

        formData.append('date', '{{ date('Y-m-d') }}');
        formData.append('molding_name', $('#molding_name').text());
        formData.append('pic', $('#employee_name').text());
        formData.append('location', $('#location').text());
        formData.append('molding_category', $('#molding_category').val());
        formData.append('part', part);
        formData.append('cek_poin', cek_poin);
        formData.append('judgement', judgement);
        formData.append('molding_type', $('#type_molding').val());
        formData.append('molding_number', $('#molding_number').val());

        $.ajax({
            url: "{{ url('post/workshop/check_molding_vendor') }}",
            method: 'POST', data: formData, dataType: 'JSON',
            contentType: false, cache: false, processData: false,
            success: function (response) {
                $('#loading').removeClass('show');
                openSuccessGritter('Sukses', 'Pengecekan berhasil tersimpan');
                selectMolding();
                $('#body_cek').empty();
            },
            error: function (resp) {
                $('#loading').removeClass('show');
                openErrorGritter('Error!', resp.responseJSON ? resp.responseJSON.message : 'Terjadi kesalahan');
            }
        });
    }

    /* ── CARI TEMUAN ── */
    function cariTemuan() {
        $('#bodyMasalah').empty();
        var data = { date_from: $('#riwayat_dari').val(), date_to: $('#riwayat_sampai').val() };
        $.get('{{ url('fetch/workshop/check_molding_vendor/temuan') }}', data, function (result) {
            if (result.status) {
                var body = '';
                $.each(result.datas, function (i, v) {
                    var sClass = v.status == 'Open' ? 'pill-open' : v.status == 'Temporary Close' ? 'pill-temp' : 'pill-close';
                    var imgUrl = '{{ url('workshop/Audit_Molding/Check_Molding/problem_att') }}/';
                    body += '<tr>';
                    body += '<td>' + (i+1) + '</td><td>' + v.check_date + '</td><td>' + v.molding_name + '</td><td>' + v.part_name + '</td>';
                    body += '<td style="text-align:left;">' + v.problem;
                    if (v.problem_att) {
                        v.problem_att.split(',').forEach(function (f) { body += '<br><img style="max-width:90px;border-radius:6px;margin:2px;" src="' + imgUrl + f + '">'; });
                    }
                    body += '</td>';
                    body += '<td style="text-align:left;">' + v.handling_temporary;
                    if (v.handling_att) {
                        v.handling_att.split(',').forEach(function (f) { body += '<br><img style="max-width:90px;border-radius:6px;margin:2px;" src="' + imgUrl + f + '">'; });
                    }
                    body += '</td>';
                    body += '<td>' + (v.note_problem || '—') + '</td>';
                    body += '<td><span class="pill ' + sClass + '">' + v.status + '</span></td>';
                    body += '</tr>';
                });
                $('#bodyMasalah').html(body || '<tr><td colspan="8" style="color:#a0aec0;text-align:center;padding:24px;">Tidak ada data.</td></tr>');
            } else {
                openErrorGritter('Error', result.message);
            }
        });
    }

    /* ── FETCH DETAIL RECORD ── */
    function fetchDetailRecord() {
        $('#loading').addClass('show');
        var data = { date_from: $('#date_from').val(), date_to: $('#date_to').val(), moldings: $('#molding_select').val() };
        $.get('{{ url('fetch/workshop/check_molding_vendor/record') }}', data, function (result) {
            if (result.status) {
                var html = '';
                var imgUrl = '{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/';
                $.each(result.datas, function (k, v) {
                    var nama = v.pic;
                    $.each(result.employees, function (k2, v2) { if (v.pic == v2.employee_id) nama = v2.name; });
                    var jPill = v.judgement == 'OK' ? "<span class='pill pill-ok'>OK</span>" : "<span class='pill pill-ng'>NG</span>";
                    html += '<tr>';
                    html += '<td>' + v.id + '</td><td>' + v.check_date + '</td><td>' + v.molding_name + '</td><td>' + nama + '</td><td>' + v.point_check + '</td>';
                    html += '<td><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_before1+'"><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_before2+'"></td>';
                    html += '<td><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_after1+'"><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_after2+'"></td>';
                    html += '<td><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_activity1+'"><img style="max-width:80px;border-radius:6px;margin:2px;" src="'+imgUrl+v.photo_activity2+'"></td>';
                    html += '<td>' + jPill + '</td><td>' + (v.status || '—') + '</td></tr>';
                });
                $('#bodyTableDetail').html(html || '<tr><td colspan="10" style="color:#a0aec0;text-align:center;padding:24px;">Tidak ada data.</td></tr>');

                var tableDetail = $('#tableDetail').DataTable({
                    pageLength: 10,

                    dom: 'Bfrtip',

                    buttons: [
                        {
                            text: '<i class="fas fa-file-excel"></i> Export Excel',
                            className: 'btn btn-success',
                            action: function () {
                                exportDetailExcel();
                            }
                        }
                    ],

                    columnDefs: [
                        {
                            targets: [5, 6, 7],
                            orderable: false,
                            searchable: false
                        }
                    ]
                });

                $('#loading').removeClass('show');
            } else {
                $('#loading').removeClass('show');
                openErrorGritter('Error', result.message);
            }
        });
    }

    /* ── SIMPAN TEMUAN ── */
    function simpanTemuan() {
        if (CKEDITOR.instances.permasalahan.getData() == '' || CKEDITOR.instances.perbaikan_sementara.getData() == '' || $('#status').val() == '') {
            openErrorGritter('Gagal', 'Lengkapi semua kolom');
            return false;
        }
        $('#loading').addClass('show');
        var formData = new FormData();
        formData.append('date', '{{ date('Y-m-d H:i:s') }}');
        formData.append('pic', $('#employee_name').text());
        formData.append('molding_name', $('#nama_molding').val());
        formData.append('part_name', $('#nama_part').val());
        formData.append('problem', CKEDITOR.instances.permasalahan.getData());
        formData.append('handling_temporary', CKEDITOR.instances.perbaikan_sementara.getData());
        formData.append('notes', CKEDITOR.instances.note.getData());
        formData.append('status', $('#status').val());
        ['permasalahan1','permasalahan2','perbaikan1','perbaikan2'].forEach(function (id) {
            var f = $('#' + id).prop('files')[0];
            if (f) formData.append(id, f);
        });
        $.ajax({
            url: "{{ url('post/workshop/check_molding_vendor/temuan') }}",
            method: 'POST', data: formData, dataType: 'JSON',
            contentType: false, cache: false, processData: false,
            success: function (data) {
                if (data.status) {
                    part_status.push({ part: $('#nama_part').val(), status: $('#status').val() });
                    openSuccessGritter('Success', data.message);
                    $('#loading').removeClass('show');
                    $('#modal_problem').modal('hide');
                } else {
                    openErrorGritter('Error!', data.message);
                    $('#loading').removeClass('show');
                }
            }
        });
    }

    /* ── IMAGE UTILS ── */
    function readURL(input, idfile) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var tmp = new Image();
                tmp.onload = function () {
                    var c = document.createElement('canvas');
                    c.width = tmp.width; c.height = tmp.height;
                    c.getContext('2d').drawImage(tmp, 0, 0);
                    var compressed = c.toDataURL('image/jpeg', 0.6);
                    $(input).parent().find('.' + idfile).attr('src', compressed).show();
                };
                tmp.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL2(input, idfile) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) { $(input).parent().find('.' + idfile).attr('src', e.target.result).show(); };
            reader.readAsDataURL(input.files[0]);
        }
    }

    /* ── MISC ── */
    function openModal(id)   { $('#' + id).modal('show'); }
    function loadMolding(elem) {
        var molds = <?php echo json_encode($period_cek); ?>;
        $('#moldings').empty();
        var isi = '<option value=""></option>';
        $.each(molds, function (i, v) {
            if (v.period == $(elem).val())
                isi += '<option value="' + v.molding_type + '_' + v.molding_category + '">' + v.molding_name + '</option>';
        });
        $('#moldings').html(isi);
    }

    async function exportDetailExcel() {

        const dt = $('#tableDetail').DataTable();

        const workbook = new ExcelJS.Workbook();
        const worksheet = workbook.addWorksheet('Check Molding');

        worksheet.mergeCells('A1:A2');
        worksheet.mergeCells('B1:B2');
        worksheet.mergeCells('C1:C2');
        worksheet.mergeCells('D1:D2');
        worksheet.mergeCells('E1:E2');
        worksheet.mergeCells('F1:H1');
        worksheet.mergeCells('I1:I2');
        worksheet.mergeCells('J1:J2');

        worksheet.getCell('A1').value = 'Id';
        worksheet.getCell('B1').value = 'Tanggal';
        worksheet.getCell('C1').value = 'Molding';
        worksheet.getCell('D1').value = 'PIC';
        worksheet.getCell('E1').value = 'Poin Cek';

        worksheet.getCell('F1').value = 'Eviden';
        worksheet.getCell('F2').value = 'Before';
        worksheet.getCell('G2').value = 'After';
        worksheet.getCell('H2').value = 'Aktifitas';

        worksheet.getCell('I1').value = 'Judgement';
        worksheet.getCell('J1').value = 'Status';

        // INI YANG PENTING
        const rows = dt
            .rows({
                search: 'applied'
            })
            .nodes()
            .toArray();

        for (let i = 0; i < rows.length; i++) {

            const tr = rows[i];
            const cells = $(tr).find('td');

            const excelRow = worksheet.addRow([
                $(cells[0]).text().trim(),
                $(cells[1]).text().trim(),
                $(cells[2]).text().trim(),
                $(cells[3]).text().trim(),
                $(cells[4]).text().trim(),
                '',
                '',
                '',
                $(cells[8]).text().trim(),
                $(cells[9]).text().trim()
            ]);

            excelRow.height = 75;

            await insertImagesToExcel(
                workbook,
                worksheet,
                $(cells[5]).find('img'),
                excelRow.number,
                5
            );

            await insertImagesToExcel(
                workbook,
                worksheet,
                $(cells[6]).find('img'),
                excelRow.number,
                6
            );

            await insertImagesToExcel(
                workbook,
                worksheet,
                $(cells[7]).find('img'),
                excelRow.number,
                7
            );
        }

        const buffer = await workbook.xlsx.writeBuffer();

        saveAs(
            new Blob([buffer], {
                type: 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet'
            }),
            'Check_Molding.xlsx'
        );
    }

    async function insertImagesToExcel(
        workbook,
        worksheet,
        images,
        rowNumber,
        columnIndex
    ) {

        for (
            let i = 0;
            i < images.length;
            i++
        ) {

            const imageUrl =
                $(images[i]).attr('src');

            if (!imageUrl) {
                continue;
            }

            try {

                const response =
                    await fetch(imageUrl);

                if (!response.ok) {
                    throw new Error(
                        'Image tidak ditemukan'
                    );
                }

                const blob =
                    await response.blob();

                const buffer =
                    await blob.arrayBuffer();


                /*
                ===================================
                DETEKSI FORMAT
                ===================================
                */

                let extension = 'jpeg';

                if (
                    blob.type.includes('png')
                ) {
                    extension = 'png';
                }


                /*
                ===================================
                TAMBAHKAN IMAGE
                ===================================
                */

                const imageId =
                    workbook.addImage({
                        buffer: buffer,
                        extension: extension
                    });


                /*
                ===================================
                POSISI IMAGE
                ===================================

                columnIndex ExcelJS dimulai dari 0
                A = 0
                B = 1
                ...
                F = 5

                */

                const imageWidth = 65;

                const imageHeight = 65;

                const imageOffset =
                    i * 0.55;


                worksheet.addImage(
                    imageId,
                    {
                        tl: {
                            col:
                                columnIndex +
                                imageOffset,
                            row:
                                rowNumber -
                                1 +
                                0.1
                        },

                        ext: {
                            width: imageWidth,
                            height: imageHeight
                        }
                    }
                );

            } catch (error) {

                console.error(
                    'Gagal export gambar:',
                    imageUrl,
                    error
                );

            }

        }
    }

    function findItem(arr, val) {
        for (var i = 0; i < arr.length; i++) { if (arr[i].part == val) return true; }
        return false;
    }
    function UrlExists(url) {
        var http = new XMLHttpRequest(); http.open('HEAD', url, false); http.send();
        return http.status != 404;
    }
    var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url('images/image-screen.png') }}', sticky: false, time: '2000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url('images/image-stop.png') }}', sticky: false, time: '2000' });
        audio_error.play();
    }
</script>
@endsection