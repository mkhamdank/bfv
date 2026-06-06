@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="{{ url('css/icheck-bootstrap.min.css') }}" rel="stylesheet">
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

    /* ══════════════════════════════════════
       LOADING OVERLAY
    ══════════════════════════════════════ */
    #loading {
        position: fixed; inset: 0;
        background: rgba(30,20,60,.45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex !important;
        align-items: center; justify-content: center;
    }
    .loading-box {
        background: #fff; border-radius: 20px;
        padding: 36px 48px;
        display: flex; flex-direction: column;
        align-items: center; gap: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px;
        border: 3px solid #e2e8f0; border-top-color: #605ca8;
        border-radius: 50%; animation: spin .75s linear infinite;
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
    .btn-standar {
        background: rgba(255,255,255,.15); color: #fff;
        border: 1.5px solid rgba(255,255,255,.3); border-radius: 10px;
        padding: 10px 18px; font-size: 13px; font-weight: 600;
        cursor: pointer; display: inline-flex; align-items: center; gap: 7px;
        transition: background .18s; position: relative; z-index: 1;
    }
    .btn-standar:hover { background: rgba(255,255,255,.25); }

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
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7;
        background: #fafbff; display: flex; align-items: center; gap: 10px;
    }
    .section-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; flex-shrink: 0; }
    .section-card-header h4 { font-size: 13px; font-weight: 700; color: #1a202c; margin: 0; }
    .section-card-body { padding: 20px 24px; }

    /* ══════════════════════════════════════
       INFO MOLDING GRID
    ══════════════════════════════════════ */
    .info-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px;
        margin-bottom: 4px;
    }
    @media (max-width: 768px) { .info-grid { grid-template-columns: 1fr 1fr; } }

    .info-item .il { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: #718096; margin-bottom: 5px; display: block; }
    .info-item .iv {
        display: flex; align-items: center; gap: 8px;
        background: #fafbff; border: 1.5px solid #e2e8f0;
        border-radius: 10px; padding: 10px 14px;
        font-size: 13px; font-weight: 700; color: #1a202c;
    }
    .info-item .iv i { color: #605ca8; }

    /* Large point display */
    .point-card {
        border-radius: 14px; padding: 16px 20px;
        display: flex; flex-direction: column; align-items: center; gap: 4px;
        text-align: center;
    }
    .pc-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; }
    .pc-val { font-size: 36px; font-weight: 800; line-height: 1; }
    .pc-molding { background: #ede9fe; border: 1.5px solid #ddd6fe; }
    .pc-molding .pc-lbl { color: #5b21b6; } .pc-molding .pc-val { color: #5b21b6; }
    .pc-produk  { background: #ebf2ff; border: 1.5px solid #c3d9f8; }
    .pc-produk  .pc-lbl { color: #2d6bc4; } .pc-produk  .pc-val { color: #2d6bc4; }
    .pc-rank    { border: 1.5px solid; }
    .pc-rank-AA { background: #dcfce7; border-color: #bbf7d0; } .pc-rank-AA .pc-lbl,.pc-rank-AA .pc-val { color: #15803d; }
    .pc-rank-A  { background: #ebf2ff; border-color: #c3d9f8; } .pc-rank-A  .pc-lbl,.pc-rank-A  .pc-val { color: #2d6bc4; }
    .pc-rank-B  { background: #fef3c7; border-color: #fde68a; } .pc-rank-B  .pc-lbl,.pc-rank-B  .pc-val { color: #b45309; }
    .pc-rank-C  { background: #fee2e2; border-color: #fecaca; } .pc-rank-C  .pc-lbl,.pc-rank-C  .pc-val { color: #dc2626; }
    .pc-keputusan {
        background: #f7f8fc; border: 1.5px solid #edf0f5; border-radius: 14px;
        padding: 14px 20px; font-size: 13px; color: #4a5568; font-weight: 500; line-height: 1.5;
    }
    .pc-keputusan strong { display: block; font-size: 11px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: .7px; margin-bottom: 4px; }

    /* ══════════════════════════════════════
       STANDAR PENILAIAN TABLE
    ══════════════════════════════════════ */
    .std-table { width: 100%; border-collapse: collapse; }
    .std-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .7px; text-transform: uppercase;
        padding: 10px 14px; border-bottom: 2px solid #edf0f5; text-align: center;
    }
    .std-table tbody td { padding: 10px 14px; font-size: 13px; color: #2d3748; border-bottom: 1px solid #f0f2f7; vertical-align: middle; }
    .std-table tbody tr:last-child td { border-bottom: none; }
    .std-cat { display: inline-flex; align-items: center; justify-content: center; padding: 4px 14px; border-radius: 20px; font-size: 12px; font-weight: 800; white-space: nowrap; }
    .sc-ok   { background: #dcfce7; color: #15803d; }
    .sc-ng   { background: #fee2e2; color: #dc2626; }
    .sc-oks  { background: #fef3c7; color: #b45309; }

    .poin-chip {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 8px;
        font-size: 11px; font-weight: 700;
    }
    .chip-red  { background: #fee2e2; color: #dc2626; border: 1.5px solid #fecaca; }
    .chip-blue { background: #ebf2ff; color: #2d6bc4; border: 1.5px solid #c3d9f8; }

    /* ══════════════════════════════════════
       DIAGNOSA TABLE
    ══════════════════════════════════════ */
    .diag-table { width: 100%; border-collapse: collapse; }
    .diag-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .7px; text-transform: uppercase;
        padding: 10px 12px; border-bottom: 2px solid #edf0f5;
        text-align: center; white-space: nowrap;
    }
    .diag-table tbody tr { border-bottom: 1px solid #f0f2f7; }
    .diag-table tbody tr:last-child { border-bottom: none; }
    .diag-table tbody td { padding: 10px 12px; font-size: 13px; color: #2d3748; vertical-align: middle; }

    /* Row color by grouping */
    .row-main { background: #fff8f8; }
    .row-main:hover { background: #fee2e2 !important; }
    .row-sub  { background: #f0f8ff; }
    .row-sub:hover  { background: #dbeafe !important; }

    /* Grouping badge */
    .grp-main { display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; color: #dc2626; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; margin-left: 6px; }
    .grp-sub  { display: inline-flex; align-items: center; gap: 4px; background: #ebf2ff; color: #2d6bc4; padding: 2px 8px; border-radius: 6px; font-size: 10px; font-weight: 700; margin-left: 6px; }

    /* Radio pills */
    .radio-group { display: flex; flex-direction: column; gap: 5px; }
    .radio-pill {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 5px 12px; border-radius: 8px; cursor: pointer;
        font-size: 12px; font-weight: 600; border: 1.5px solid #e2e8f0;
        background: #fafbff; transition: all .15s; user-select: none;
    }
    .radio-pill input[type=radio] { accent-color: #605ca8; cursor: pointer; }
    .radio-pill:has(input:checked) { background: #ede9fe; border-color: #605ca8; color: #4a4690; }
    .radio-pill.ng:has(input:checked) { background: #fee2e2; border-color: #dc2626; color: #dc2626; }
    .radio-pill.oks:has(input:checked) { background: #fef3c7; border-color: #b45309; color: #b45309; }

    /* Checkbox chips */
    .ck-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 4px 10px; border-radius: 8px; cursor: pointer;
        font-size: 12px; font-weight: 600; border: 1.5px solid #e2e8f0;
        background: #fafbff; margin: 2px; transition: all .15s;
    }
    .ck-chip input[type=checkbox] { accent-color: #605ca8; cursor: pointer; }
    .ck-chip:has(input:checked) { background: #ebf2ff; border-color: #2d6bc4; color: #2d6bc4; }

    /* Item check */
    .item-check-row {
        display: flex; align-items: center; gap: 8px;
        padding: 5px 0; border-bottom: 1px dashed #f0f2f7;
    }
    .item-check-row:last-child { border-bottom: none; }
    .item-lbl { flex: 1; display: flex; align-items: center; gap: 6px; font-size: 12px; font-weight: 500; cursor: pointer; }
    .item-lbl input[type=checkbox] { accent-color: #605ca8; cursor: pointer; }
    .item-lbl:has(input:checked) { color: #dc2626; font-weight: 700; }
    .btn-cam {
        display: none; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px;
        background: #ebf2ff; color: #2d6bc4; border: none; cursor: pointer;
        font-size: 14px; transition: background .15s; flex-shrink: 0;
    }
    .btn-cam:hover { background: #c3d9f8; }
    .img-link { display: none; font-size: 11px; font-weight: 600; color: #2d6bc4; cursor: pointer; white-space: nowrap; }

    /* Pengurangan cell */
    .poin-cell {
        font-size: 22px; font-weight: 800; text-align: center;
        min-width: 48px;
    }
    .poin-cell.has-ng { color: #dc2626; }

    /* Status check icon */
    .cek-icon { font-size: 20px; color: #d1d5db; text-align: center; display: block; }
    .cek-icon.done { color: #15803d; }

    /* Rincian textarea */
    .rincian-input {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 8px;
        padding: 7px 10px; font-size: 12px; resize: vertical;
        min-height: 56px; outline: none; background: #fafbff;
        transition: border-color .15s;
    }
    .rincian-input:focus { border-color: #605ca8; box-shadow: 0 0 0 2px rgba(96,92,168,.1); }

    /* Details button */
    .btn-details {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ebf2ff; color: #2d6bc4; border: none;
        border-radius: 7px; padding: 5px 12px; font-size: 11px; font-weight: 600;
        cursor: pointer; margin-top: 6px; transition: background .15s;
    }
    .btn-details:hover { background: #c3d9f8; }

    /* ══════════════════════════════════════
       FOOTER BUTTONS
    ══════════════════════════════════════ */
    .footer-actions {
        display: flex; gap: 12px; justify-content: space-between;
        padding: 20px 24px; border-top: 1px solid #f0f2f7; background: #fafbff;
    }
    .btn-temp {
        display: inline-flex; align-items: center; gap: 8px;
        background: #fef3c7; color: #b45309;
        border: 1.5px solid #fde68a; border-radius: 12px;
        padding: 13px 28px; font-size: 14px; font-weight: 700;
        cursor: pointer; transition: all .18s;
    }
    .btn-temp:hover { background: #fde68a; }
    .btn-save-full {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff; border: none; border-radius: 12px;
        padding: 13px 32px; font-size: 14px; font-weight: 700;
        cursor: pointer; box-shadow: 0 4px 14px rgba(21,128,61,.28);
        transition: opacity .18s, transform .18s;
    }
    .btn-save-full:hover { opacity: .88; transform: translateY(-1px); }

    /* ══════════════════════════════════════
       MODALS
    ══════════════════════════════════════ */
    .modal-content { border-radius: 18px !important; overflow: hidden !important; border: none !important; }
    .modal-hdr {
        background: linear-gradient(135deg, #2d2b4e, #605ca8);
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-hdr h5 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-hdr .close { color: rgba(255,255,255,.7) !important; font-size: 20px; opacity: 1 !important; padding: 0; margin: 0; }
    .modal-hdr .close:hover { color: #fff !important; }

    .modal-hdr-warn {
        background: linear-gradient(135deg, #b45309, #d97706);
        padding: 18px 24px;
        display: flex; align-items: center; justify-content: center;
    }
    .modal-hdr-warn h5 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }

    .modal-body-pad { padding: 20px 24px; }
    .modal-ftr { padding: 14px 24px; border-top: 1px solid #f0f2f7; display: flex; gap: 10px; justify-content: flex-end; }

    .btn-modal-ok {
        background: linear-gradient(135deg, #15803d, #16a34a); color: #fff;
        border: none; border-radius: 10px; padding: 10px 24px;
        font-size: 13px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px;
    }
    .btn-modal-ok:hover { opacity: .88; }

    /* Standar modal */
    .standar-item {
        display: flex; gap: 14px; align-items: flex-start;
        padding: 12px 16px; border-radius: 10px; margin-bottom: 8px;
    }
    .standar-item:last-child { margin-bottom: 0; }
    .si-ok  { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
    .si-ng  { background: #fff5f5; border: 1.5px solid #fecaca; }
    .si-oks { background: #fffbeb; border: 1.5px solid #fde68a; }
    .si-lbl { font-weight: 800; font-size: 13px; min-width: 90px; }
    .si-ok  .si-lbl { color: #15803d; }
    .si-ng  .si-lbl { color: #dc2626; }
    .si-oks .si-lbl { color: #b45309; }
    .si-desc { font-size: 13px; color: #4a5568; line-height: 1.5; }

    .blinking { animation: blinkingIcon 1s infinite; }
    @keyframes blinkingIcon { 0%,100%{opacity:1;} 50%{opacity:0;} }
</style>
@stop

@section('header')
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid" style="padding: 0 20px; max-width: 1400px; margin: 0 auto;">

    {{-- LOADING --}}
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Memuat data...</p>
        </div>
    </div>

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-tasks"></i>&nbsp; Diagnosa Molding</div>
            <h1>Form Diagnosa Molding</h1>
            <p>{{ $molding_name->fixed_asset_name }} &mdash; {{ $form_number }}</p>
        </div>
        <button class="btn-standar" onclick="$('#modal_info').modal('show')">
            <i class="fas fa-info-circle"></i> Standar Diagnosa
        </button>
    </div>

    {{-- 1. INFORMASI MOLDING --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-cube" style="color:#605ca8;margin-right:6px;"></i> Informasi Molding</h4>
        </div>
        <div class="section-card-body">
            <div class="info-grid" style="grid-template-columns:1fr 1fr; margin-bottom:16px;">
                <div class="info-item">
                    <span class="il">Form Number</span>
                    <div class="iv"><i class="fas fa-file-alt"></i> <span id="form_number">{{ $form_number }}</span></div>
                </div>
                <div class="info-item">
                    <span class="il">Nama Molding</span>
                    <div class="iv"><i class="fas fa-cube"></i> {{ $molding_name->fixed_asset_name }}</div>
                </div>
            </div>

            <div style="display:grid; grid-template-columns:1fr 1fr 1fr 1fr; gap:14px;">
                <div class="point-card pc-molding">
                    <span class="pc-lbl">Poin Molding</span>
                    <span class="pc-val" id="total_point">100</span>
                </div>
                <div class="point-card pc-produk">
                    <span class="pc-lbl">Poin Produk</span>
                    <span class="pc-val" id="total_produk_point">
                        @if(isset($product_point)) {{ $product_point->points }} @else — @endif
                    </span>
                </div>
                <div class="point-card pc-rank" id="ranking_card">
                    <span class="pc-lbl">Ranking</span>
                    <span class="pc-val" id="ranking">C</span>
                </div>
                <div class="pc-keputusan">
                    <strong>Keputusan</strong>
                    <span id="keputusan">Sulit melanjutkan proses produksi. Perlu peremajaan.</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 2. STANDAR PENILAIAN --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-star" style="color:#605ca8;margin-right:6px;"></i> Standar Penilaian</h4>
        </div>
        <div class="section-card-body" style="padding:0;">
            <table class="std-table">
                <thead>
                    <tr>
                        <th style="width:120px;">Kategori</th>
                        <th>Keterangan</th>
                        <th style="width:180px;">Poin Pengurangan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td rowspan="2"><span class="std-cat sc-ok">OK</span></td>
                        <td>Yang tidak ada masalah.</td>
                        <td rowspan="2" style="text-align:center; font-weight:700; color:#15803d;">0 Poin</td>
                    </tr>
                    <tr><td>Kondisi awal dapat dipelihara konsistensinya.</td></tr>
                    <tr>
                        <td rowspan="3"><span class="std-cat sc-ng">NG</span></td>
                        <td>Yang ada masalah.</td>
                        <td rowspan="3" style="text-align:center;">
                            <span class="poin-chip chip-red"><i style="width:10px;height:10px;background:#ffa3a3;border-radius:2px;display:inline-block;"></i> −10 Poin (Main)</span><br><br>
                            <span class="poin-chip chip-blue"><i style="width:10px;height:10px;background:#6dcdf0;border-radius:2px;display:inline-block;"></i> −5 Poin (Sub)</span>
                        </td>
                    </tr>
                    <tr><td>Yang harus direpair setelah menyesuaikan schedule lagi, atau yang harus diganti part-nya.</td></tr>
                    <tr><td>Yang tidak dapat direpair.</td></tr>
                    <tr>
                        <td rowspan="2"><span class="std-cat sc-oks">OK Sementara</span></td>
                        <td>Yang masalah nya dapat diselesaikan dengan repair. (Sedang diobservasi prosesnya)</td>
                        <td rowspan="2" style="text-align:center;">
                            <span class="poin-chip chip-red"><i style="width:10px;height:10px;background:#ffa3a3;border-radius:2px;display:inline-block;"></i> −3 Poin (Main)</span><br><br>
                            <span class="poin-chip chip-blue"><i style="width:10px;height:10px;background:#6dcdf0;border-radius:2px;display:inline-block;"></i> −1 Poin (Sub)</span>
                        </td>
                    </tr>
                    <tr><td>Ada resiko mempengaruhi stabilitas produksi kedepannya.</td></tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 3. LEMBAR DIAGNOSA --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-clipboard-check" style="color:#605ca8;margin-right:6px;"></i> Lembar Diagnosa Molding</h4>
        </div>
        <div style="overflow-x:auto;">
            <table class="diag-table" id="tableMaster">
                <thead>
                    <tr>
                        <th style="width:16%;">Jenis NG</th>
                        <th style="width:14%;">Hasil Diagnosa</th>
                        <th style="width:9%;">Part</th>
                        <th>Nama Item</th>
                        <th style="width:14%;">Rincian Lain</th>
                        <th style="width:60px;">Poin</th>
                        <th style="width:80px;">Status</th>
                    </tr>
                </thead>
                <tbody id="bodyTableMaster"></tbody>
            </table>
        </div>
        <div class="footer-actions">
            <button type="button" class="btn-temp" id="btn_save_temp" onclick="saveProductCheckTemp()">
                <i class="fas fa-save"></i> Simpan Sementara
            </button>
            <button type="button" class="btn-save-full" id="btn_save" onclick="saveProductCheck()">
                <i class="fas fa-check-double"></i> Simpan Sepenuhnya
            </button>
        </div>
    </div>

</div>

{{-- MODAL STANDAR DIAGNOSA --}}
<div class="modal fade" id="modal_info" tabindex="-1" role="dialog" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-hdr-warn">
                <h5><i class="fas fa-bullhorn blinking"></i> Standar Diagnosa</h5>
            </div>
            <div class="modal-body-pad">
                <div class="standar-item si-ok">
                    <div class="si-lbl"><i class="fas fa-check-circle"></i> OK</div>
                    <div class="si-desc">Tidak ada masalah → kondisi awal terjaga konsistensinya.</div>
                </div>
                <div class="standar-item si-ng">
                    <div class="si-lbl"><i class="fas fa-times-circle"></i> NG</div>
                    <div class="si-desc">Ada masalah → reschedule untuk repair, atau ganti parts. Tidak bisa direpair.</div>
                </div>
                <div class="standar-item si-oks">
                    <div class="si-lbl"><i class="fas fa-exclamation-circle"></i> OK Sementara</div>
                    <div class="si-desc">Bisa diselesaikan dengan repair (perlu dipantau hasilnya). Diperkirakan beresiko memberi dampak ke kestabilan produksi selanjutnya.</div>
                </div>
            </div>
            <div class="modal-ftr">
                <button type="button" class="btn-modal-ok" data-bs-dismiss="modal" data-dismiss="modal">
                    <i class="fas fa-check"></i> Mengerti
                </button>
            </div>
        </div>
    </div>
</div>

{{-- MODAL DETAIL NG --}}
<div class="modal fade" id="modal_detail" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-hdr">
                <h5><i class="fas fa-info-circle"></i> Detail NG &mdash; <span id="nama_ng_detail"></span></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
            </div>
            <div class="modal-body-pad" style="overflow-x:auto;">
                <table class="diag-table" id="table_detail" width="100%">
                    <thead>
                        <tr>
                            <th style="width:40px;">No</th>
                            <th>Tanggal</th>
                            <th>Foto 1</th>
                            <th>Foto 2</th>
                            <th style="width:60px;">Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbody_detail"></tbody>
                </table>
            </div>
        </div>
    </div>
</div>

{{-- MODAL IMAGE --}}
<div class="modal fade" id="modal_image" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg" role="document" style="max-width:90vw;">
        <div class="modal-content">
            <div class="modal-hdr">
                <h5><i class="fas fa-image"></i> Detail Foto</h5>
                <button type="button" class="close" data-dismiss="modal" data-bs-dismiss="modal"><span>&times;</span></button>
            </div>
            <div class="modal-body-pad">
                <img src="" alt="" id="image" style="width:100%; border-radius:10px;">
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
<script src="{{ url('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<script src="{{ url('adminlte/plugins/bs-custom-file-input/bs-custom-file-input.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var master_check_list = {!! json_encode($master_check_list) !!};
    var actual_check_list = {!! json_encode($actual_check_list) !!};
    var ranks             = {!! json_encode($ranks) !!};

    var audio_error   = new Audio('{{ url("sounds/error_2.mp3") }}');
    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');

    jQuery(document).ready(function () {
        $("#wrapper").toggleClass("toggled");
        $('body').toggleClass("sidebar-collapse");
        $('#side_diagnosa_molding').addClass('menu-open');
        $("#modal_info").modal('show');
        if (window.bsCustomFileInput && typeof bsCustomFileInput.init === 'function') {
            bsCustomFileInput.init();
        }
        getData();
        var loading = document.getElementById('loading');
        if (loading) {
            loading.style.setProperty('display', 'none', 'important');
        }
    });

    /* ── Rank helper ── */
    function getRankClass(rank) {
        var map = { 'AA':'pc-rank-AA', 'A':'pc-rank-A', 'B':'pc-rank-B', 'C':'pc-rank-C' };
        return map[rank] || 'pc-rank-C';
    }

    /* ── Update rank display ── */
    function updateRankDisplay(totalPoin) {
        var rank = 'C';
        var keputusan = 'Sulit melanjutkan proses produksi. Perlu peremajaan.';
        $.each(ranks, function(k, r) {
            if (totalPoin >= r.min_score && totalPoin <= r.max_score) {
                rank = r.rank; keputusan = r.keputusan;
            }
        });
        $('#ranking').text(rank);
        $('#keputusan').text(keputusan);
        $('#total_point').text(totalPoin);
        var $card = $('#ranking_card');
        $card.removeClass('pc-rank-AA pc-rank-A pc-rank-B pc-rank-C').addClass(getRankClass(rank));
    }

    /* ── Build table ── */
    function getData() {
        $('#bodyTableMaster').html('');
        var tableData = '';

        $.each(master_check_list, function(key, value) {
            var isMain = value.grouping === 'Main';
            var ng  = isMain ? 10 : 5;
            var oks = isMain ? 3  : 1;
            var rowClass = isMain ? 'row-main' : 'row-sub';
            var grpBadge = isMain
                ? '<span class="grp-main">Main −'+ng+'</span>'
                : '<span class="grp-sub">Sub −'+ng+'</span>';

            tableData += '<tr class="'+rowClass+'">';

            /* Jenis NG */
            tableData += '<td style="font-weight:600;">'+value.item_ng+grpBadge+'</td>';

            /* Hasil Diagnosa */
            tableData += '<td><div class="radio-group">'
                + '<label class="radio-pill"><input type="radio" name="r3_'+value.id+'" tag="OK" id="'+value.id+'_OK" value="0" onclick="changePengurangan('+value.id+', \''+value.grouping+'\')"> OK</label>'
                + '<label class="radio-pill ng"><input type="radio" name="r3_'+value.id+'" tag="NG" id="'+value.id+'_NG" value="'+ng+'" onclick="changePengurangan('+value.id+', \''+value.grouping+'\')"> NG</label>'
                + '<label class="radio-pill oks"><input type="radio" name="r3_'+value.id+'" tag="OK Sementara" id="'+value.id+'_OK_Sementara" value="'+oks+'" onclick="changePengurangan('+value.id+', \''+value.grouping+'\')"> OK Sementara</label>'
                + '</div></td>';

            /* Part (daerah_ng) */
            tableData += '<td>';
            if (value.daerah_ng) {
                var daerah = value.daerah_ng.split(', ');
                $.each(daerah, function(k2, v2) {
                    tableData += '<label class="ck-chip"><input type="checkbox" name="ck_'+value.id+'" value="'+v2+'" id="'+value.id+'_'+v2+'" onchange="changePengurangan('+value.id+', \''+value.grouping+'\')"> '+v2+'</label>';
                });
            }
            tableData += '</td>';

            /* Nama Item */
            tableData += '<td>';
            if (value.item_check) {
                var items = value.item_check.split(', ');
                $.each(items, function(k2, v2) {
                    tableData += '<div class="item-check-row">'
                        + '<label class="item-lbl"><input type="checkbox" id="ckitem_'+value.id+'_'+k2+'" name="ckitem_'+value.id+'" value="'+v2+'" onchange="changeItemCheck('+value.id+','+k2+')"> '+v2+'</label>'
                        + '<input type="file" id="photo_file1_'+value.id+'_'+k2+'" style="display:none;" accept="image/*" onchange="showImage(this, \'img_photo1_'+value.id+'_'+k2+'\')">'
                        + '<button type="button" class="btn-cam" id="btn_photo1_'+value.id+'_'+k2+'" onclick="document.getElementById(\'photo_file1_'+value.id+'_'+k2+'\').click()"><i class="fas fa-camera"></i></button>'
                        + '<span class="img-link" id="img_photo1_'+value.id+'_'+k2+'" onclick="openImage(this)">📷1</span>'
                        + '<input type="file" id="photo_file2_'+value.id+'_'+k2+'" style="display:none;" accept="image/*" onchange="showImage(this, \'img_photo2_'+value.id+'_'+k2+'\')">'
                        + '<button type="button" class="btn-cam" id="btn_photo2_'+value.id+'_'+k2+'" onclick="document.getElementById(\'photo_file2_'+value.id+'_'+k2+'\').click()"><i class="fas fa-camera"></i></button>'
                        + '<span class="img-link" id="img_photo2_'+value.id+'_'+k2+'" onclick="openImage(this)">📷2</span>'
                        + '</div>';
                });
            }
            tableData += '</td>';

            /* Rincian Lain */
            tableData += '<td><textarea class="rincian-input" placeholder="Rincian..." id="rincian_lain_'+value.id+'"></textarea></td>';

            /* Pengurangan */
            tableData += '<td class="poin-cell" id="pengurangan_'+value.id+'">0</td>';

            /* Status + Details */
            tableData += '<td style="text-align:center;">'
                + '<i class="fas fa-check cek-icon" id="cek_'+value.id+'"></i>'
                + '<button type="button" class="btn-details" onclick="openDetail('+value.id+', \''+value.item_ng+'\')"><i class="fas fa-info"></i> Detail</button>'
                + '</td>';

            tableData += '</tr>';
        });

        $('#bodyTableMaster').html(tableData);

        /* Restore actual data */
        if (actual_check_list.length > 0) {
            $.each(actual_check_list, function(key, value) {
                $("input[name='r3_"+value.ng_id+"'][value='"+value.deduction+"']").prop("checked", true);
                $("#cek_"+value.ng_id).addClass("done");

                if (value.parts) {
                    $.each(value.parts.split(', '), function(k2, v2) {
                        $("input[name='ck_"+value.ng_id+"'][value='"+v2+"']").prop("checked", true);
                    });
                }

                if (value.item_number !== null && value.item_number !== undefined) {
                    $("#ckitem_"+value.ng_id+"_"+value.item_number).prop("checked", true);
                    changeItemCheck(value.ng_id, value.item_number);
                }

                if (value.photo1) {
                    $("#img_photo1_"+value.ng_id+"_"+value.item_number).show().attr('data-src', '{{ url("diagnose_molding/molding_check_photo") }}/'+value.photo1);
                    $("#btn_photo1_"+value.ng_id+"_"+value.item_number).css('display','inline-flex');
                }
                if (value.photo2) {
                    $("#img_photo2_"+value.ng_id+"_"+value.item_number).show().attr('data-src', '{{ url("diagnose_molding/molding_check_photo") }}/'+value.photo2);
                    $("#btn_photo2_"+value.ng_id+"_"+value.item_number).css('display','inline-flex');
                }
                if (value.rincian_lain) { $("#rincian_lain_"+value.ng_id).val(value.rincian_lain); }
            });

            recalcTotal();
        }
    }

    /* ── Radio change ── */
    function changePengurangan(id, grouping) {
        var checkedVal = parseInt($("input[name='r3_"+id+"']:checked").val()) || 0;
        $('#pengurangan_'+id).text(checkedVal).toggleClass('has-ng', checkedVal > 0);
        $("#cek_"+id).addClass("done");
        recalcTotal();
    }

    /* ── Checkbox item change ── */
    function changeItemCheck(id, key2) {
        var checked = $("#ckitem_"+id+"_"+key2).is(':checked');
        $("#btn_photo1_"+id+"_"+key2).css('display', checked ? 'inline-flex' : 'none');
        $("#btn_photo2_"+id+"_"+key2).css('display', checked ? 'inline-flex' : 'none');
    }

    /* ── Recalculate total ── */
    function recalcTotal() {
        var total = 100;
        $('.pengurangan').each(function() { total -= parseInt($(this).text()) || 0; });
        if (total < 0) total = 0;
        updateRankDisplay(total);
    }

    /* ── Image helpers ── */
    function showImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function(e) {
                var $el = $('#'+imgId);
                $el.show().attr('data-src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function openImage(el) {
        var src = $(el).attr('data-src');
        $('#image').attr('src', src);
        $('#modal_image').modal('show');
    }

    /* ── Open detail modal ── */
    function openDetail(id, nama) {
        $('#nama_ng_detail').text(nama);
        $('#tbody_detail').html('<tr><td colspan="5" style="text-align:center;padding:20px;color:#718096;">Memuat detail...</td></tr>');
        $('#modal_detail').modal('show');
        $.get('{{ url("fetch/diagnose_molding/mold_molding_check/detail") }}', { ng_id: id }, function(result) {
            if (result.status) {
                var rows = '';
                $.each(result.data, function(k, v) {
                    rows += '<tr>'
                        + '<td>'+(k+1)+'</td>'
                        + '<td>'+v.check_date+'</td>'
                        + '<td><img src="{{ url("diagnose_molding/molding_check_photo") }}/'+v.photo1+'" style="max-width:90px;border-radius:6px;" onclick="openImage(this)"></td>'
                        + '<td><img src="{{ url("diagnose_molding/molding_check_photo") }}/'+v.photo2+'" style="max-width:90px;border-radius:6px;" onclick="openImage(this)"></td>'
                        + '<td><button class="btn-details" onclick="deleteDetail('+v.id+')"><i class="fas fa-trash"></i></button></td>'
                        + '</tr>';
                });
                $('#tbody_detail').html(rows || '<tr><td colspan="5" style="text-align:center;color:#a0aec0;">Tidak ada data</td></tr>');
            }
        });
    }

    /* ── Collect form data ── */
    function collectData(useFullPoin) {
        var datas = [];
        var total_poin = useFullPoin ? parseInt($('#total_point').text()) : 100;

        $.each(master_check_list, function(key, value) {
            var hasil_diagnosa = '';
            var pengurangan = 0;
            $('input[name="r3_'+value.id+'"]:checked').each(function() {
                hasil_diagnosa = $(this).attr('tag');
                pengurangan = $(this).attr('value');
            });

            var part_checked = [];
            $('input[name="ck_'+value.id+'"]:checked').each(function() { part_checked.push($(this).val()); });
            var part_checked_name = part_checked.join(', ');
            var status_ng = false;

            $.each(value.item_check ? value.item_check.split(', ') : [], function(key2, value2) {
                if ($('#ckitem_'+value.id+'_'+key2)[0] && $('#ckitem_'+value.id+'_'+key2)[0].checked) {
                    status_ng = true;
                    var photo1 = null, photo2 = null;
                    if ($('#photo_file1_'+value.id+'_'+key2)[0].files.length > 0) photo1 = $('#photo_file1_'+value.id+'_'+key2)[0].files[0];
                    if ($('#photo_file2_'+value.id+'_'+key2)[0].files.length > 0) photo2 = $('#photo_file2_'+value.id+'_'+key2)[0].files[0];
                    if (!useFullPoin) total_poin -= parseInt($('#pengurangan_'+value.id).text());
                    datas.push({ ng_id: value.id, item_ng: value.item_ng, hasil_diagnosa, part_checked: part_checked_name, id_items: key2, item_name: value2, rincian_lain: $('#rincian_lain_'+value.id).val(), pengurangan, photo1, photo2 });
                }
            });

            if (!status_ng && hasil_diagnosa === 'OK') {
                datas.push({ ng_id: value.id, item_ng: value.item_ng, hasil_diagnosa, part_checked: part_checked_name, id_items: null, item_name: null, rincian_lain: $('#rincian_lain_'+value.id).val(), pengurangan: 0, photo1: null, photo2: null });
            }
        });

        var formData = new FormData();
        formData.append('total_poin', useFullPoin ? $('#total_point').text() : total_poin);
        formData.append('form_number', $('#form_number').text());
        datas.forEach(function(item, index) {
            Object.keys(item).forEach(function(k) { formData.append('data['+index+']['+k+']', item[k]); });
        });
        return formData;
    }

    function submitData(formData) {
        $.ajax({
            url: '{{ url("save/diagnose_molding/mold_molding_check") }}',
            type: 'POST', contentType: false, processData: false, data: formData,
            success: function(result) {
                if (result.status) { toastr.success(result.message); audio_success.play(); }
                else               { toastr.error(result.message);   audio_error.play(); }
            },
            error: function() { toastr.error('Terjadi kesalahan.'); audio_error.play(); }
        });
    }

    function saveProductCheckTemp() {
        Swal.fire({ title: 'Simpan Sementara?', icon: 'question', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save"></i> Ya', cancelButtonText: 'Batal',
            confirmButtonColor: '#b45309', cancelButtonColor: '#718096'
        }).then(function(r) { if (r.isConfirmed) submitData(collectData(true)); });
    }

    function saveProductCheck() {
        Swal.fire({ title: 'Simpan Sepenuhnya?', icon: 'question', showCancelButton: true,
            confirmButtonText: '<i class="fas fa-check-double"></i> Ya, Simpan', cancelButtonText: 'Batal',
            confirmButtonColor: '#15803d', cancelButtonColor: '#718096'
        }).then(function(r) { if (r.isConfirmed) submitData(collectData(false)); });
    }
</script>
@endsection