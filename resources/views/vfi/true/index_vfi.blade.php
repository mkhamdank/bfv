@extends('layouts.master')

@section('title', 'VFI - Input Inspeksi')

@section('styles')
<link href="{{ url('css/jquery.numpad.css') }}" rel="stylesheet">
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    #loading, #error { display: none; }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th,
    .datepicker-months > table > thead > tr > th,
    .datepicker-years > table > thead > tr > th {
        background-color: white; color: #696969 !important;
    }
    .unselectable { user-select: none; -webkit-user-select: none; }

    /* ══════════════════════════════════════
       SELECT2 OVERRIDE
    ══════════════════════════════════════ */
    .select2-container .select2-selection--single {
        height: 42px !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        background: #fafbff !important;
        display: flex !important;
        align-items: center !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .select2-container--default .select2-selection--single:focus,
    .select2-container--default.select2-container--open .select2-selection--single {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.12) !important;
        outline: none !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 13px !important;
        font-weight: 500 !important;
        color: #2d3748 !important;
        line-height: 42px !important;
        margin-top:0px !important;
        /* padding-left: 14px !important; */
        /* padding-right: 32px !important; */
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        color: #a0aec0 !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 42px !important;
        right: 10px !important;
    }
    .select2-dropdown {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important;
        box-shadow: 0 8px 24px rgba(0,0,0,.1) !important;
        overflow: hidden !important;
    }
    .select2-search--dropdown .select2-search__field {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 8px 12px !important;
        font-size: 13px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        outline: none !important;
    }
    .select2-search--dropdown .select2-search__field:focus {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .select2-results__option {
        font-size: 13px !important;
        padding: 9px 14px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        color: #2d3748 !important;
    }
    .select2-results__option--highlighted {
        background: #ede9fe !important;
        color: #4a4690 !important;
    }
    .select2-results__option[aria-selected=true] {
        background: #f5f3ff !important;
        color: #4a4690 !important;
        font-weight: 600 !important;
    }

    /* ══════════════════════════════════════
       NG LIST HEIGHT — pas dengan kolom kiri
    ══════════════════════════════════════ */
    #ngList2 {
        max-height: none !important;
        height: calc(100vh - 320px) !important;
        min-height: 300px !important;
        overflow-y: auto !important;
        height: 580px !important;
    }
    @media (max-width: 767px) {
        #ngList2 { height: 360px !important; }
    }
    /* Backdrop */
    .modal-backdrop.in { opacity: .5 !important; background: #2d2b4e !important; }

    /* Container tabel numpad */
    .numpad.modal-content {
        border: none !important;
        border-radius: 20px !important;
        overflow: hidden !important;
        box-shadow: 0 20px 60px rgba(45,43,78,.35) !important;
        background: #fff !important;
        width: 340px !important;
        min-width: 300px !important;
        max-width: 95vw !important;
        position: fixed !important;
        top: 50% !important;
        left: 50% !important;
        transform: translate(-50%, -50%) !important;
        margin: 0 !important;
    }

    /* Display (input angka) */
    .numpad .display input,
    .numpad input[type=text].form-control {
        background: linear-gradient(135deg, #2d2b4e, #605ca8) !important;
        color: #fff !important;
        font-size: 36px !important;
        font-weight: 800 !important;
        text-align: center !important;
        border: none !important;
        border-radius: 0 !important;
        height: 80px !important;
        letter-spacing: 2px !important;
        padding: 0 20px !important;
        box-shadow: none !important;
        width: 100% !important;
    }

    /* Label di atas display */
    .numpad .label {
        background: linear-gradient(135deg, #2d2b4e, #605ca8) !important;
        color: rgba(255,255,255,.6) !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        text-transform: uppercase !important;
        letter-spacing: 1.2px !important;
        text-align: center !important;
        padding: 14px 0 0 !important;
    }

    /* Grid tombol */
    .numpad table { border-collapse: separate !important; border-spacing: 6px !important; padding: 10px !important; background: #f7f8fc !important; }
    .numpad td { padding: 0 !important; }

    /* Semua tombol angka */
    .numpad .btn.btn-info {
        background: #fff !important;
        color: #2d3748 !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
        font-size: 22px !important;
        font-weight: 700 !important;
        width: 80px !important;
        height: 64px !important;
        line-height: 1 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,.06) !important;
        transition: all .15s !important;
    }
    .numpad .btn.btn-info:hover,
    .numpad .btn.btn-info:active {
        background: #ede9fe !important;
        border-color: #605ca8 !important;
        color: #4a4690 !important;
        transform: scale(.97) !important;
    }

    /* Tombol fungsi (Done, Cancel, Clear, Del, Sep) */
    .numpad .btn.btn-success {
        border-radius: 12px !important;
        font-size: 13px !important;
        font-weight: 700 !important;
        height: 64px !important;
        line-height: 1 !important;
        box-shadow: 0 2px 4px rgba(0,0,0,.06) !important;
        transition: all .15s !important;
        width: 100% !important;
    }

    /* Done button */
    .numpad .done {
        background: linear-gradient(135deg, #15803d, #16a34a) !important;
        color: #fff !important;
        border: none !important;
        border-radius: 12px !important;
    }
    .numpad .done:hover { opacity: .88 !important; }

    /* Cancel button */
    .numpad .cancel {
        background: #fee2e2 !important;
        color: #dc2626 !important;
        border: 1.5px solid #fecaca !important;
        border-radius: 12px !important;
    }
    .numpad .cancel:hover { background: #fecaca !important; }

    /* Clear button */
    .numpad .clear {
        background: #fef3c7 !important;
        color: #b45309 !important;
        border: 1.5px solid #fde68a !important;
        border-radius: 12px !important;
    }
    .numpad .clear:hover { background: #fde68a !important; }

    /* Del (backspace) button */
    .numpad .del {
        background: #f1f5f9 !important;
        color: #475569 !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 12px !important;
    }
    .numpad .del:hover { background: #e2e8f0 !important; }

    /* Sep (titik desimal) */
    .numpad .sep {
        background: #ebf2ff !important;
        color: #2d6bc4 !important;
        border: 1.5px solid #c3d9f8 !important;
        border-radius: 12px !important;
        width: 80px !important;
    }

    /* ══════════════════════════════════════
       MOBILE RESPONSIVE
    ══════════════════════════════════════ */
    @media (max-width: 767px) {
        .vfi-layout { grid-template-columns: 1fr !important; }

        .page-header-modern { padding: 20px 18px 18px; border-radius: 14px; }
        .header-left h1 { font-size: 20px !important; }

        .section-card-body { padding: 14px !important; }

        /* Info dasar — stack tanggal & inspector */
        .info-dasar-grid { grid-template-columns: 1fr !important; }

        /* Material desc & SN — stack */
        .mat-sn-grid { grid-template-columns: 1fr !important; }

        /* QTY input lebih kecil di HP */
        .qty-input { height: 54px !important; font-size: 22px !important; }

        /* Result cards OK/NG */
        .result-grid { grid-template-columns: 1fr 1fr; gap: 8px; }
        .result-card .r-val input { font-size: 22px !important; }
        .rc-ratio .r-val input { font-size: 22px !important; }
        .rc-ratio { padding: 10px 14px; }
        .rc-ratio > i { font-size: 28px !important; }

        /* NG list table — tombol lebih kecil */
        .ng-btn { width: 38px !important; height: 38px !important; font-size: 20px !important; }
        .ng-btn-cell { width: 44px !important; min-width: 44px !important; }
        .ng-count-cell { width: 50px !important; min-width: 50px !important; font-size: 20px !important; }
        .ng-name-cell { font-size: 12px !important; padding: 8px 10px !important; }
        #ngList2 { max-height: 340px !important; }

        /* Action buttons */
        .btn-cancel-action,
        .btn-confirm-action { font-size: 14px !important; padding: 13px !important; }

        /* Numpad lebih kecil di HP */
        .numpad.modal-content { width: 300px !important; }
        .numpad .btn.btn-info { width: 68px !important; height: 56px !important; font-size: 18px !important; }
        .numpad .btn.btn-success { height: 56px !important; }
        .numpad input[type=text].form-control { font-size: 28px !important; height: 68px !important; }
    }

    @media (max-width: 400px) {
        .numpad.modal-content { width: 280px !important; }
        .numpad .btn.btn-info { width: 60px !important; height: 50px !important; font-size: 16px !important; }
        .numpad table { border-spacing: 4px !important; padding: 8px !important; }
    }

    /* ══════════════════════════════════════
       LOADING OVERLAY
    ══════════════════════════════════════ */
    #loading {
        display: none;
        position: fixed; inset: 0;
        background: rgba(30,31,58,.35);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    #loading.show-flex { display: flex; }
    .loading-box {
        background: #fff; border-radius: 20px;
        padding: 36px 48px;
        display: flex; flex-direction: column;
        align-items: center; gap: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px;
        border: 3px solid #e2e8f0;
        border-top-color: #605ca8;
        border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .page-header-modern::after {
        content: ''; position: absolute; left: 30%; bottom: -60px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.03);
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ══════════════════════════════════════
       SECTION CARDS
    ══════════════════════════════════════ */
    .section-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .section-card-header {
        padding: 14px 20px; border-bottom: 1px solid #f0f2f7;
        background: #fafbff; display: flex; align-items: center; gap: 10px;
    }
    .section-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }
    .section-card-header h4 { font-size: 13px; font-weight: 700; color: #1a202c; margin: 0; }
    .section-card-body { padding: 18px 20px; }

    /* ══════════════════════════════════════
       FORM ELEMENTS
    ══════════════════════════════════════ */
    .form-lbl {
        display: block; font-size: 11px; font-weight: 700;
        color: #718096; text-transform: uppercase;
        letter-spacing: .6px; margin-bottom: 6px;
    }
    .form-inp {
        width: 100%; border: 1.5px solid #e2e8f0;
        border-radius: 10px; padding: 10px 14px;
        font-size: 14px; color: #2d3748; outline: none;
        background: #fafbff; transition: border-color .18s, box-shadow .18s;
        box-sizing: border-box;
    }
    .form-inp:focus { border-color: #605ca8; box-shadow: 0 0 0 3px rgba(96,92,168,.12); }
    .form-inp[readonly] { background: #f7f8fc; cursor: default; }

    /* ══════════════════════════════════════
       INFO DISPLAY BOXES
    ══════════════════════════════════════ */
    .info-box {
        border-radius: 10px; padding: 12px 16px;
        display: flex; align-items: center; gap: 10px;
        font-weight: 700; font-size: 14px; min-height: 48px;
    }
    .info-box-green  { background: #dcfce7; color: #15803d; border: 1.5px solid #bbf7d0; }
    .info-box-purple { background: #ede9fe; color: #5b21b6; border: 1.5px solid #ddd6fe; }
    .info-box-gray   { background: #f7f8fc; color: #4a5568; border: 1.5px solid #e2e8f0; font-style: italic; }
    .info-box .info-icon { font-size: 16px; flex-shrink: 0; }

    /* ══════════════════════════════════════
       RESULT SUMMARY ROW
    ══════════════════════════════════════ */
    .result-grid {
        display: grid; grid-template-columns: 1fr 1fr; gap: 12px;
        margin-bottom: 12px;
    }
    .result-card {
        border-radius: 12px; padding: 14px 16px;
        display: flex; flex-direction: column; align-items: center;
        gap: 4px;
    }
    .result-card .r-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; }
    .result-card .r-val { font-size: 28px; font-weight: 800; line-height: 1; }
    .rc-ok  { background: #dcfce7; border: 1.5px solid #bbf7d0; }
    .rc-ok .r-lbl { color: #15803d; }
    .rc-ok .r-val { color: #15803d; }
    .rc-ng  { background: #fee2e2; border: 1.5px solid #fecaca; }
    .rc-ng .r-lbl { color: #dc2626; }
    .rc-ng .r-val { color: #dc2626; }
    .rc-ratio {
        background: #fff8e1; border: 1.5px solid #fde68a;
        border-radius: 12px; padding: 12px 16px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .rc-ratio .r-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .6px; color: #b45309; }
    .rc-ratio .r-val { font-size: 28px; font-weight: 800; color: #b45309; line-height: 1; }

    /* ══════════════════════════════════════
       QTY INPUT (large)
    ══════════════════════════════════════ */
    .qty-input {
        width: 100%; height: 64px;
        border: 2px solid #e2e8f0; border-radius: 12px;
        font-size: 28px; font-weight: 800;
        text-align: center; color: #1a202c;
        background: #fafbff; outline: none;
        transition: border-color .18s, box-shadow .18s;
        box-sizing: border-box;
    }
    .qty-input:focus { border-color: #605ca8; box-shadow: 0 0 0 3px rgba(96,92,168,.12); }

    /* ══════════════════════════════════════
       NG LIST TABLE
    ══════════════════════════════════════ */
    #ngList2 { max-height: 460px; overflow-y: auto; border-radius: 12px; }
    #ngList2::-webkit-scrollbar { width: 4px; }
    #ngList2::-webkit-scrollbar-thumb { background: #e2dff5; border-radius: 4px; }

    .ng-table { width: 100%; border-collapse: collapse; }
    .ng-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700;
        letter-spacing: .8px; text-transform: uppercase;
        padding: 10px 12px; border-bottom: 2px solid #edf0f5;
        text-align: center;
    }
    .ng-table tbody tr { border-bottom: 1px solid #f0f2f7; transition: background .15s; }
    .ng-table tbody tr:last-child { border-bottom: none; }
    .ng-table tbody tr:hover { background: #f5f3ff; }
    .ng-table tbody td { padding: 0; vertical-align: middle; text-align: center; }
    .ng-name-cell { padding: 10px 14px; font-size: 13px; font-weight: 600; color: #2d3748; text-align: left !important; }
    .ng-count-cell {
        font-size: 26px; font-weight: 800; color: #fff;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        width: 64px; min-width: 64px; text-align: center;
        padding: 8px 0;
    }

    /* Plus / Minus buttons */
    .ng-btn {
        display: flex; align-items: center; justify-content: center;
        width: 48px; height: 48px; border-radius: 10px;
        font-size: 26px; font-weight: 700;
        cursor: pointer; border: none;
        transition: all .15s; flex-shrink: 0;
        margin: 4px;
    }
    .ng-btn-minus { background: #fee2e2; color: #dc2626; }
    .ng-btn-minus:hover { background: #fecaca; transform: scale(1.06); }
    .ng-btn-plus  { background: #dcfce7; color: #15803d; }
    .ng-btn-plus:hover  { background: #bbf7d0; transform: scale(1.06); }
    .ng-btn-cell { padding: 4px; width: 56px; min-width: 56px; }

    /* Row zebra */
    .ng-table tbody tr:nth-child(even) td.ng-name-cell { background: #fafbff; }

    /* ══════════════════════════════════════
       ACTION BUTTONS
    ══════════════════════════════════════ */
    .btn-cancel-action {
        width: 100%; padding: 16px; border: none;
        border-radius: 12px; font-size: 16px; font-weight: 800;
        cursor: pointer; letter-spacing: .5px;
        background: #fee2e2; color: #dc2626;
        border: 2px solid #fecaca;
        transition: all .18s;
    }
    .btn-cancel-action:hover { background: #fecaca; transform: translateY(-1px); }

    .btn-confirm-action {
        width: 100%; padding: 16px; border: none;
        border-radius: 12px; font-size: 16px; font-weight: 800;
        cursor: pointer; letter-spacing: .5px;
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff;
        box-shadow: 0 4px 14px rgba(21,128,61,.28);
        transition: all .18s;
    }
    .btn-confirm-action:hover { opacity: .88; transform: translateY(-1px); }
    .btn-confirm-action:disabled { opacity: .5; cursor: not-allowed; transform: none; }

    /* two column layout */
    .vfi-layout { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) { .vfi-layout { grid-template-columns: 1fr; } }
</style>
@stop

@section('content')
<div class="container-fluid" style="padding: 0 20px; max-width: 1300px; margin: 0 auto;">

    {{-- LOADING OVERLAY --}}
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Memproses data...</p>
        </div>
    </div>

    <input type="hidden" id="start_time" value="">
    <input type="hidden" id="incoming_check_code" value="">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-clipboard-check"></i>&nbsp; VFI</div>
            <h1>Input Vendor Final Inspection</h1>
            <p>{{ Auth::user()->name }} &mdash; Catat hasil inspeksi akhir produksi</p>
        </div>
    </div>

    <div class="vfi-layout">

        {{-- ════════════════════════════════
             KOLOM KIRI — Form Input
        ════════════════════════════════ --}}
        <div>

            {{-- Tanggal & Inspector --}}
            <div class="section-card">
                <div class="section-card-header">
                    <span class="dot"></span>
                    <h4><i class="fas fa-calendar-alt" style="color:#605ca8;margin-right:6px;"></i> Informasi Dasar</h4>
                </div>
                <div class="section-card-body">
                    <div class="info-dasar-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;margin-bottom:14px;">
                        <div>
                            <label class="form-lbl">Tanggal Produksi</label>
                            <input type="date" class="form-inp" name="date" id="date"
                                value="{{ date('Y-m-d') }}" pattern="\d{4}-\d{2}-\d{2}">
                        </div>
                        <div>
                            <label class="form-lbl">Inspector</label>
                            <div class="info-box info-box-green">
                                <i class="fas fa-user-check info-icon"></i>
                                <span id="op">{{ $inspector }}</span>
                            </div>
                        </div>
                    </div>

                    <label class="form-lbl">Material</label>
                    <select class="form-control select2" name="material_number" id="material_number"
                        data-placeholder="Cari & pilih Material Number..."
                        style="width:100%;margin-bottom:12px;"
                        onchange="selectMaterial(this.value);">
                        <option value="">Pilih Material</option>
                        @foreach($materials as $material)
                        <option value="{{ $material->material_number }} - {{ $material->material_description }}">
                            {{ $material->material_number }} - {{ $material->material_description }}
                        </option>
                        @endforeach
                    </select>

                    <div class="mat-sn-grid" style="display:grid;grid-template-columns:1fr 1fr;gap:14px;">
                        <div>
                            <label class="form-lbl">Material Description</label>
                            <div class="info-box info-box-gray" id="material_description" style="min-height:48px;">
                                <i class="fas fa-box info-icon"></i>
                                <span>—</span>
                            </div>
                        </div>
                        <div>
                            <label class="form-lbl">Serial Number</label>
                            <div class="info-box info-box-purple" id="serial_number" style="min-height:48px;">
                                <i class="fas fa-barcode info-icon"></i>
                                <span>—</span>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            {{-- QTY & Result --}}
            <div class="section-card">
                <div class="section-card-header">
                    <span class="dot"></span>
                    <h4><i class="fas fa-calculator" style="color:#605ca8;margin-right:6px;"></i> Quantity & Result</h4>
                </div>
                <div class="section-card-body">
                    <label class="form-lbl">QTY Check <span style="color:#dc2626;">*</span></label>
                    <input type="number" class="qty-input numpad" name="qty_check"
                        id="qty_check" placeholder="0"
                        onchange="checkQty(this.value);"
                        style="margin-bottom:16px;">

                    <div class="result-grid">
                        <div class="result-card rc-ok">
                            <span class="r-lbl">QTY OK</span>
                            <span class="r-val"><input type="text" name="total_ok" id="total_ok"
                                readonly value="0"
                                style="background:transparent;border:none;outline:none;font-size:28px;font-weight:800;color:#15803d;width:100%;text-align:center;"></span>
                        </div>
                        <div class="result-card rc-ng">
                            <span class="r-lbl">QTY NG</span>
                            <span class="r-val"><input type="text" name="total_ng" id="total_ng"
                                readonly value="0"
                                style="background:transparent;border:none;outline:none;font-size:28px;font-weight:800;color:#dc2626;width:100%;text-align:center;"></span>
                        </div>
                    </div>

                    <div class="rc-ratio">
                        <div>
                            <div class="r-lbl">NG Ratio</div>
                            <div class="r-val">
                                <input type="text" name="ng_ratio" id="ng_ratio"
                                    readonly value="0"
                                    style="background:transparent;border:none;outline:none;font-size:28px;font-weight:800;color:#b45309;width:80px;text-align:center;">
                                <span style="font-size:18px;font-weight:700;color:#b45309;">%</span>
                            </div>
                        </div>
                        <i class="fas fa-percentage" style="font-size:36px;color:#fde68a;"></i>
                    </div>
                </div>
            </div>

        </div>

        {{-- ════════════════════════════════
             KOLOM KANAN — NG List
        ════════════════════════════════ --}}
        <div>
            <div class="section-card" style="height:calc(100% - 20px);">
                <div class="section-card-header">
                    <span class="dot"></span>
                    <h4><i class="fas fa-list-ul" style="color:#605ca8;margin-right:6px;"></i> Daftar NG</h4>
                </div>
                <div class="section-card-body" style="padding:0;">

                    <div id="ngList2">
                        <input type="hidden" name="ng_list_count" id="ng_list_count" value="{{ count($ng_lists) }}">
                        <table class="ng-table" id="tableNgList">
                            <thead>
                                <tr>
                                    <th style="width:56px;">−</th>
                                    <th style="text-align:left;">NG Name</th>
                                    <th style="width:56px;">+</th>
                                    <th style="width:64px;">Qty</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTableNgList">
                                @foreach($ng_lists as $nomor => $ng_list)
                                <input type="hidden" id="loop" value="{{ $loop->count }}">
                                <tr>
                                    <td class="ng-btn-cell">
                                        <button class="ng-btn ng-btn-minus unselectable"
                                            onclick="minus({{ $nomor+1 }})">−</button>
                                    </td>
                                    <td class="ng-name-cell" id="ng{{ $nomor+1 }}">{{ $ng_list->ng_name }}</td>
                                    <td class="ng-btn-cell">
                                        <button class="ng-btn ng-btn-plus unselectable"
                                            onclick="plus({{ $nomor+1 }})">+</button>
                                    </td>
                                    <td class="ng-count-cell"><span id="count{{ $nomor+1 }}">0</span></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>

                    {{-- Action Buttons --}}
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;padding:16px 20px;">
                        <button class="btn-cancel-action" id="btn_cancel" onclick="cancelAll()">
                            <i class="fas fa-times"></i> CANCEL
                        </button>
                        <button class="btn-confirm-action" id="btn_confirm" onclick="confirmNgLog()">
                            <i class="fas fa-check"></i> CONFIRM
                        </button>
                    </div>

                </div>
            </div>
        </div>

    </div>{{-- end vfi-layout --}}

</div>
@endsection

@section('scripts')
<script src="{{ url('js/jquery.numpad.js') }}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    /* ══ NUMPAD CUSTOM TEMPLATE ══ */
    var isMobile = window.innerWidth <= 767;
    var btnW  = isMobile ? '62px'  : '76px';
    var btnH  = isMobile ? '54px'  : '66px';
    var btnFS = isMobile ? '20px'  : '24px';
    var dispH = isMobile ? '70px'  : '86px';
    var dispFS= isMobile ? '30px'  : '38px';
    var padW  = isMobile ? '220px' : '270px';

    var numBtnStyle = [
        'background:#fff',
        'color:#2d3748',
        'border:1.5px solid #e2e8f0',
        'border-radius:12px',
        'font-size:'+btnFS,
        'font-weight:700',
        'width:'+btnW,
        'height:'+btnH,
        'line-height:1',
        'box-shadow:0 2px 6px rgba(0,0,0,.06)',
        'transition:background .15s',
        'cursor:pointer',
        'font-family:Plus Jakarta Sans,sans-serif'
    ].join(';');

    var funcBtnStyle = [
        'border-radius:12px',
        'font-size:13px',
        'font-weight:700',
        'height:'+btnH,
        'line-height:1',
        'width:'+btnW,
        'cursor:pointer',
        'transition:all .15s',
        'font-family:Plus Jakarta Sans,sans-serif'
    ].join(';');

    $.fn.numpad.defaults.gridTpl = [
        '<table style="',
            'background:#f7f8fc;',
            'border-radius:0 0 20px 20px;',
            'border-collapse:separate;',
            'border-spacing:6px;',
            'padding:10px;',
            'width:'+padW+';',
            'position:fixed;',
            'top:50%;',
            'left:50%;',
            'transform:translate(-50%,-50%);',
            'z-index:30100;',
            'box-shadow:0 24px 64px rgba(45,43,78,.4);',
            'border-radius:20px;',
            'overflow:hidden;',
        '"></table>'
    ].join('');

    $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.55;background:#2d2b4e;z-index:30099;"></div>';

    $.fn.numpad.defaults.displayTpl = [
        '<input type="text" style="',
            'background:linear-gradient(135deg,#2d2b4e,#605ca8);',
            'color:#fff;',
            'font-size:'+dispFS+';',
            'font-weight:800;',
            'text-align:center;',
            'border:none;',
            'border-radius:0;',
            'height:'+dispH+';',
            'letter-spacing:3px;',
            'padding:0 20px;',
            'box-shadow:none;',
            'width:100%;',
            'font-family:Plus Jakarta Sans,sans-serif;',
        '"/>'
    ].join('');

    $.fn.numpad.defaults.buttonNumberTpl  = '<button type="button" style="'+numBtnStyle+'"></button>';
    $.fn.numpad.defaults.buttonFunctionTpl= '<button type="button" style="'+funcBtnStyle+'"></button>';

    $.fn.numpad.defaults.onKeypadCreate = function() {
        /* Header label di atas display */
        var $table = $(this);
        if ($table.find('.numpad-title').length === 0) {
            $table.prepend(
                '<tr class="numpad-title"><td colspan="3" style="'+
                'background:linear-gradient(135deg,#2d2b4e,#605ca8);'+
                'color:rgba(255,255,255,.95);font-size:12px;font-weight:700;'+
                'text-transform:uppercase;letter-spacing:1.4px;'+
                'text-align:center;display:block;width:100%;margin:0 auto;'+
                'padding:14px 0;line-height:1.2;box-sizing:border-box;'+
                'font-family:Plus Jakarta Sans,sans-serif;'+
                '">QTY Check</td></tr>'
            );
        }

        /* Tombol Done — hijau */
        $(this).find('.done').css({
            'background': 'linear-gradient(135deg,#15803d,#16a34a)',
            'color':       '#fff',
            'border':      'none',
            'width':       btnW,
            'height':      btnH,
            'border-radius': '12px',
            'font-size':   '13px',
            'font-weight': '700',
            'font-family': 'Plus Jakarta Sans,sans-serif'
        });

        /* Tombol Cancel — merah flat */
        $(this).find('.cancel').css({
            'background':    '#fee2e2',
            'color':         '#dc2626',
            'border':        '1.5px solid #fecaca',
            'width':         btnW,
            'height':        btnH,
            'border-radius': '12px',
            'font-size':     '13px',
            'font-weight':   '700',
            'font-family':   'Plus Jakarta Sans,sans-serif'
        });

        /* Tombol Clear — kuning */
        $(this).find('.clear').css({
            'background':    '#fef3c7',
            'color':         '#b45309',
            'border':        '1.5px solid #fde68a',
            'width':         btnW,
            'height':        btnH,
            'border-radius': '12px',
            'font-size':     '13px',
            'font-weight':   '700',
            'font-family':   'Plus Jakarta Sans,sans-serif'
        });

        /* Tombol Del — abu */
        $(this).find('.del').css({
            'background':    '#f1f5f9',
            'color':         '#475569',
            'border':        '1.5px solid #e2e8f0',
            'width':         btnW,
            'height':        btnH,
            'border-radius': '12px',
            'font-size':     '18px',
            'font-family':   'Plus Jakarta Sans,sans-serif'
        });

        /* Tombol Sep (titik) — biru */
        $(this).find('.sep').css({
            'background':    '#ebf2ff',
            'color':         '#2d6bc4',
            'border':        '1.5px solid #c3d9f8',
            'width':         btnW,
            'height':        btnH,
            'border-radius': '12px',
            'font-size':     btnFS,
            'font-weight':   '700',
            'font-family':   'Plus Jakarta Sans,sans-serif'
        });

        /* Hover angka */
        $(this).find('button').not('.done,.cancel,.clear,.del,.sep').on('mouseenter', function(){
            $(this).css({'background':'#ede9fe','border-color':'#605ca8','color':'#4a4690'});
        }).on('mouseleave', function(){
            $(this).css({'background':'#fff','border-color':'#e2e8f0','color':'#2d3748'});
        });
    };

    var hour, minute, second, intervalTime, intervalUpdate;

    $(document).ready(function() {
        $('body').toggleClass('sidebar-collapse');
        $('#side_vfi').addClass('menu-open');
        cancelAll();
        updateKensaCode();
        $('.numpad').numpad({ hidePlusMinusButton: true, decimalSeparator: '.' });
        $('.select2').select2({ placeholder: 'Cari & pilih Material Number...', allowClear: false });
    });

    function updateKensaCode() {
        $.get('{{ url("fetch/kensa/serial_number/true") }}', function(result) {
            if (result.status) {
                $('#serial_number').html('<i class="fas fa-barcode" style="font-size:16px;flex-shrink:0;"></i><span>' + result.serial_number + '</span>');
            } else {
                audio_error.play();
                openErrorGritter('Error', result.message);
            }
        });
    }

    function cancelAll() {
        $('#material_number').val('').trigger('change.select2');
        $('#material_description').html('<i class="fas fa-box info-icon"></i><span>—</span>');
        $('#qty_check').val('');
        $('#total_ok').val('0');
        $('#total_ng').val('0');
        $('#ng_ratio').val('0');
        var jumlah_ng = '{{ $nomor+1 }}';
        for (var i = 1; i <= jumlah_ng; i++) {
            $('#count'+i).html(0);
        }
    }

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

    function selectMaterial(material) {
        var material_number      = material.split(' - ')[0];
        var material_description = material.split(' - ')[1];
        $('#material_description').html('<i class="fas fa-box info-icon"></i><span>' + material_description + '</span>');
        $('#material_description').removeClass('info-box-gray').addClass('info-box-green');

        var data = { material_number: material_number, periode: $('#date').val() };
        $.get('{{ route("admin.vfi.fetch_true") }}', data, function(result) {
            if (result.status) {
                $('#target').html(result.target.qty);
                $('#sisa_target').html(parseInt(result.target.qty) - parseInt(result.target.qty_actual));
            }
        });
    }

    function checkQty(value) {
        var qty_ng   = 0;
        var jumlah_ng = '{{ $nomor+1 }}';
        for (var i = 1; i <= jumlah_ng; i++) {
            if ($('#count'+i).text() != 0) {
                qty_ng = qty_ng + parseInt($('#count'+i).text());
            }
        }
        var total_ok = value - qty_ng;
        $('#total_ok').val(total_ok);
        $('#total_ng').val(qty_ng);
        $('#ng_ratio').val(value > 0 ? ((qty_ng / value) * 100).toFixed(1) : 0);
    }

    function plus(id) {
        if ($('#material_description span').text() === '—' || $('#qty_check').val() === '') {
            openErrorGritter('Error!', 'Isi Semua Data.'); return;
        }
        var count = parseInt($('#count'+id).text());
        $('#total_ok').val(parseInt($('#total_ok').val()) - 1);
        $('#total_ng').val(parseInt($('#total_ng').val()) + 1);
        $('#ng_ratio').val(((parseInt($('#total_ng').val()) / parseInt($('#qty_check').val())) * 100).toFixed(1));
        $('#count'+id).text(count + 1);
    }

    function minus(id) {
        if ($('#material_description span').text() === '—' || $('#qty_check').val() === '') {
            openErrorGritter('Error!', 'Isi Semua Data.'); return;
        }
        var count = parseInt($('#count'+id).text());
        if (count > 0) {
            $('#total_ok').val(parseInt($('#total_ok').val()) + 1);
            $('#total_ng').val(parseInt($('#total_ng').val()) - 1);
            $('#ng_ratio').val(((parseInt($('#total_ng').val()) / parseInt($('#qty_check').val())) * 100).toFixed(1));
            $('#count'+id).text(count - 1);
        }
    }

    function addZero(i) { return i < 10 ? '0' + i : i; }

    function getActualFullDate() {
        var d = new Date();
        return addZero(d.getFullYear()) + '-' + addZero(d.getMonth()+1) + '-' + addZero(d.getDate())
             + ' ' + addZero(d.getHours()) + ':' + addZero(d.getMinutes()) + ':' + addZero(d.getSeconds());
    }

    function confirmNgLog() {
        if ($('#material_description span').text() === '—' || $('#qty_check').val() === '') {
            openErrorGritter('Error!', 'Isi Semua Data.'); return;
        }
        $('#loading').addClass('show-flex');
        $('#btn_confirm').prop('disabled', true);

        var material_number      = $('#material_number').val().split(' - ')[0];
        var material_description = $('#material_number').val().split(' - ')[1];
        var ng_name = [], ng_qty = [];
        var jumlah_ng = '{{ $nomor+1 }}';
        for (var i = 1; i <= jumlah_ng; i++) {
            if ($('#count'+i).text() != 0) {
                ng_name.push($('#ng'+i).text());
                ng_qty.push($('#count'+i).text());
            }
        }

        var data = {
            material_number:      material_number,
            material_description: material_description,
            qty_check:            $('#qty_check').val(),
            total_ok:             $('#total_ok').val(),
            total_ng:             $('#total_ng').val(),
            serial_number:        $('#serial_number span').text(),
            ng_ratio:             $('#ng_ratio').val(),
            inspector:            $('#op').text(),
            ng_name:              ng_name,
            ng_qty:               ng_qty,
            jumlah_ng:            jumlah_ng,
            check_date:           $('#date').val(),
        };

        $.post('{{ route("admin.vfi.input_true") }}', data, function(result) {
            if (result.status) {
                cancelAll();
                updateKensaCode();
                openSuccessGritter('Success!', result.message);
            } else {
                openErrorGritter('Error!', result.message);
            }
            $('#loading').removeClass('show-flex');
            $('#btn_confirm').prop('disabled', false);
        });
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection