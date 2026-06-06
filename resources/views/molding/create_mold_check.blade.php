@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
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

    /* ══ LOADING ══ */
    #loading {
        position: fixed; inset: 0; background: rgba(30,20,60,.45);
        backdrop-filter: blur(4px); z-index: 9999;
        display: flex !important; align-items: center; justify-content: center;
    }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 48px;
        display: flex; flex-direction: column; align-items: center; gap: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner { width: 42px; height: 42px; border: 3px solid #e2e8f0; border-top-color: #605ca8; border-radius: 50%; animation: spin .75s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══ PAGE HEADER ══ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px; border-radius: 18px;
        display: flex; align-items: center; justify-content: space-between; flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before { content: ''; position: absolute; right: -40px; top: -40px; width: 200px; height: 200px; border-radius: 50%; background: rgba(255,255,255,.04); }
    .page-header-modern::after  { content: ''; position: absolute; left: 30%; bottom: -60px; width: 160px; height: 160px; border-radius: 50%; background: rgba(255,255,255,.03); }
    .header-left .badge-tag { display: inline-flex; align-items: center; gap: 6px; background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22); color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px; text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px; }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }
    .btn-standar { background: rgba(255,255,255,.15); color: #fff; border: 1.5px solid rgba(255,255,255,.3); border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 600; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; transition: background .18s; position: relative; z-index: 1; }
    .btn-standar:hover { background: rgba(255,255,255,.25); }

    /* ══ SECTION CARD ══ */
    .section-card { background: #fff; border-radius: 16px; box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05); overflow: hidden; margin-bottom: 20px; }
    .section-card-header { padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff; display: flex; align-items: center; gap: 10px; }
    .section-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; flex-shrink: 0; }
    .section-card-header h4 { font-size: 13px; font-weight: 700; color: #1a202c; margin: 0; }
    .section-card-body { padding: 20px 24px; }

    /* ══ INFO GRID ══ */
    .info-top-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; }
    @media (max-width: 768px) { .info-top-grid { grid-template-columns: 1fr; } }
    .il { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; color: #718096; margin-bottom: 5px; display: block; }
    .iv { display: flex; align-items: center; gap: 8px; background: #fafbff; border: 1.5px solid #e2e8f0; border-radius: 10px; padding: 10px 14px; font-size: 13px; font-weight: 700; color: #1a202c; }
    .iv i { color: #605ca8; }

    /* ══ POINT CARDS ══ */
    .point-grid { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-top: 16px; }
    @media (max-width: 900px) { .point-grid { grid-template-columns: 1fr 1fr; } }
    .point-card { border-radius: 14px; padding: 16px 18px; display: flex; flex-direction: column; align-items: center; gap: 4px; text-align: center; }
    .pc-lbl { font-size: 11px; font-weight: 700; text-transform: uppercase; letter-spacing: .7px; }
    .pc-val { font-size: 34px; font-weight: 800; line-height: 1; }
    .pc-produk  { background: #ede9fe; border: 1.5px solid #ddd6fe; } .pc-produk  .pc-lbl, .pc-produk  .pc-val { color: #5b21b6; }
    .pc-molding { background: #ebf2ff; border: 1.5px solid #c3d9f8; } .pc-molding .pc-lbl, .pc-molding .pc-val { color: #2d6bc4; }
    .pc-rank { border: 1.5px solid; }
    .pc-rank-AA { background: #dcfce7; border-color: #bbf7d0; } .pc-rank-AA .pc-lbl, .pc-rank-AA .pc-val { color: #15803d; }
    .pc-rank-A  { background: #ebf2ff; border-color: #c3d9f8; } .pc-rank-A  .pc-lbl, .pc-rank-A  .pc-val { color: #2d6bc4; }
    .pc-rank-B  { background: #fef3c7; border-color: #fde68a; } .pc-rank-B  .pc-lbl, .pc-rank-B  .pc-val { color: #b45309; }
    .pc-rank-C  { background: #fee2e2; border-color: #fecaca; } .pc-rank-C  .pc-lbl, .pc-rank-C  .pc-val { color: #dc2626; }
    .pc-keputusan { background: #f7f8fc; border: 1.5px solid #edf0f5; border-radius: 14px; padding: 14px 18px; display: flex; flex-direction: column; align-items: flex-start; gap: 4px; }
    .pc-keputusan .pc-lbl { color: #718096; }
    .pc-keputusan .pc-val { font-size: 13px; font-weight: 600; color: #4a5568; line-height: 1.4; }

    /* ══ UPLOAD AREA ══ */
    .upload-area { border: 2px dashed #c4bfef; border-radius: 12px; padding: 20px; text-align: center; cursor: pointer; background: #faf9ff; transition: border-color .18s; }
    .upload-area:hover { border-color: #605ca8; }
    .upload-area .fa { font-size: 28px; color: #c4bfef; display: block; margin-bottom: 8px; }
    .upload-area p { font-size: 12.5px; color: #718096; margin: 0 0 4px; }
    .upload-area small { font-size: 11px; color: #a0aec0; }
    .btn-upload { display: inline-flex; align-items: center; gap: 7px; background: linear-gradient(135deg,#4a4690,#605ca8); color: #fff; border: none; border-radius: 10px; padding: 10px 20px; font-size: 13px; font-weight: 700; cursor: pointer; transition: opacity .18s; margin-top: 10px; }
    .btn-upload:hover { opacity: .88; }

    /* ══ LEGENDA ══ */
    .legenda-wrap { display: flex; gap: 8px; flex-wrap: wrap; }
    .legenda-item { display: inline-flex; align-items: center; gap: 6px; padding: 4px 10px; border-radius: 7px; font-size: 11.5px; font-weight: 600; }
    .leg-dot { width: 11px; height: 11px; border-radius: 3px; flex-shrink: 0; }
    .lg-ok   { background: #f0fdf4; color: #15803d; border: 1px solid #bbf7d0; }
    .lg-ng-m { background: #fff5f5; color: #dc2626; border: 1px solid #fecaca; }
    .lg-ng-s { background: #eff6ff; color: #2563eb; border: 1px solid #bfdbfe; }
    .lg-ok-s { background: #fffbeb; color: #b45309; border: 1px solid #fde68a; }
    .lg-isi  { background: #fdf4ff; color: #9333ea; border: 1px solid #e9d5ff; }

    /* ══ DIAGNOSA TABLE ══ */
    .diag-table { width: 100%; border-collapse: collapse; }
    .diag-table thead th { background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; padding: 10px 14px; border-bottom: 2px solid #edf0f5; text-align: center; white-space: nowrap; }
    .diag-table tbody tr { border-bottom: 1px solid #f0f2f7; }
    .diag-table tbody td { padding: 10px 12px; font-size: 13px; color: #2d3748; vertical-align: middle; }
    .row-ng-main { background: #fff8f8; } .row-ng-main:hover { background: #fee2e2 !important; }
    .row-ng-sub  { background: #f0f8ff; } .row-ng-sub:hover  { background: #dbeafe !important; }
    .row-ok      { background: #f0fdf4; } .row-ok:hover      { background: #dcfce7 !important; }
    .row-isi     { background: #fdf4ff; } .row-isi:hover     { background: #f3e8ff !important; }

    /* Radio pills */
    .radio-grp { display: flex; flex-direction: column; gap: 5px; }
    .r-pill { display: inline-flex; align-items: center; gap: 7px; padding: 5px 11px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 600; border: 1.5px solid #e2e8f0; background: #fafbff; transition: all .15s; user-select: none; }
    .r-pill input[type=radio] { accent-color: #605ca8; cursor: pointer; }
    .r-pill.ok:has(input:checked)  { background: #f0fdf4; border-color: #15803d; color: #15803d; }
    .r-pill.ng:has(input:checked)  { background: #fee2e2; border-color: #dc2626; color: #dc2626; }
    .r-pill.oks:has(input:checked) { background: #fef3c7; border-color: #b45309; color: #b45309; }

    /* Isi pills */
    .isi-grp { display: grid; grid-template-columns: 1fr 1fr; gap: 5px; }
    .isi-pill { display: inline-flex; align-items: center; gap: 6px; padding: 5px 10px; border-radius: 8px; cursor: pointer; font-size: 12px; font-weight: 600; border: 1.5px solid #e2e8f0; background: #fafbff; transition: all .15s; user-select: none; }
    .isi-pill input[type=radio] { accent-color: #9333ea; cursor: pointer; }
    .isi-pill:has(input:checked) { background: #fdf4ff; border-color: #9333ea; color: #9333ea; }

    /* Poin & cek cells */
    .poin-cell { font-size: 22px; font-weight: 800; text-align: center; color: #1a202c; }
    .poin-cell.neg { color: #dc2626; }
    .cek { font-size: 20px; color: #ddd; display: block; text-align: center; transition: color .18s; }
    .cek.done { color: #15803d; }

    /* Action buttons */
    .act-btn { display: inline-flex; align-items: center; gap: 5px; padding: 5px 11px; border-radius: 8px; font-size: 11px; font-weight: 700; border: none; cursor: pointer; transition: opacity .15s; white-space: nowrap; margin: 2px; }
    .act-btn:hover { opacity: .82; }
    .ab-photo  { background: #dcfce7; color: #15803d; }
    .ab-detail { background: #ebf2ff; color: #2d6bc4; }

    /* Total row */
    .total-row td { background: #f7f8fc !important; font-weight: 700; border-top: 2px solid #edf0f5 !important; }
    .total-val { font-size: 22px; font-weight: 800; color: #dc2626; text-align: center; }
    .point-val { font-size: 14px; font-weight: 700; color: #5b21b6; text-align: center; }

    /* ══ FOOTER ACTIONS ══ */
    .footer-actions { display: flex; gap: 12px; justify-content: space-between; padding: 18px 24px; border-top: 1px solid #f0f2f7; background: #fafbff; }
    .btn-temp { display: inline-flex; align-items: center; gap: 8px; background: #fef3c7; color: #b45309; border: 1.5px solid #fde68a; border-radius: 12px; padding: 12px 28px; font-size: 14px; font-weight: 700; cursor: pointer; transition: all .18s; }
    .btn-temp:hover { background: #fde68a; }
    .btn-save-full { display: inline-flex; align-items: center; gap: 8px; background: linear-gradient(135deg,#15803d,#16a34a); color: #fff; border: none; border-radius: 12px; padding: 12px 32px; font-size: 14px; font-weight: 700; cursor: pointer; box-shadow: 0 4px 14px rgba(21,128,61,.28); transition: opacity .18s, transform .18s; }
    .btn-save-full:hover { opacity: .88; transform: translateY(-1px); }

    /* ══ MODALS ══ */
    .modal-content { border-radius: 18px !important; overflow: hidden !important; border: none !important; }
    .modal-hdr { background: linear-gradient(135deg,#2d2b4e,#605ca8); padding: 18px 24px; display: flex; align-items: center; justify-content: space-between; }
    .modal-hdr-warn { background: linear-gradient(135deg,#b45309,#d97706); padding: 18px 24px; display: flex; align-items: center; justify-content: center; }
    .modal-hdr h5, .modal-hdr-warn h5 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-hdr .close { color: rgba(255,255,255,.7) !important; font-size: 20px; opacity: 1 !important; }
    .modal-body-pad { padding: 20px 24px; }
    .modal-ftr { padding: 14px 24px; border-top: 1px solid #f0f2f7; display: flex; gap: 10px; justify-content: flex-end; background: #fafbff; }
    .btn-modal-ok   { background: linear-gradient(135deg,#15803d,#16a34a); color: #fff; border: none; border-radius: 10px; padding: 10px 22px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; }
    .btn-modal-save { background: linear-gradient(135deg,#4a4690,#605ca8);  color: #fff; border: none; border-radius: 10px; padding: 10px 22px; font-size: 13px; font-weight: 700; cursor: pointer; display: inline-flex; align-items: center; gap: 7px; }
    .btn-modal-cancel { background: #f0f2f7; color: #718096; border: none; border-radius: 10px; padding: 10px 18px; font-size: 13px; font-weight: 600; cursor: pointer; }
    .btn-modal-cancel:hover { background: #e2e8f0; }

    .standar-item { display: flex; gap: 12px; align-items: flex-start; padding: 12px 14px; border-radius: 10px; margin-bottom: 8px; }
    .si-ok  { background: #f0fdf4; border: 1.5px solid #bbf7d0; } .si-ok  .si-lbl { color: #15803d; }
    .si-ng  { background: #fff5f5; border: 1.5px solid #fecaca; } .si-ng  .si-lbl { color: #dc2626; }
    .si-oks { background: #fffbeb; border: 1.5px solid #fde68a; } .si-oks .si-lbl { color: #b45309; }
    .si-lbl { font-weight: 800; font-size: 13px; min-width: 80px; }
    .si-desc { font-size: 13px; color: #4a5568; line-height: 1.5; }

    .photo-upload-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px; margin-top: 14px; }
    .photo-slot { border: 2px dashed #c4bfef; border-radius: 12px; padding: 16px; text-align: center; background: #faf9ff; }
    .btn-choose { display: inline-flex; align-items: center; gap: 6px; background: #ebf2ff; color: #2d6bc4; border: none; border-radius: 8px; padding: 7px 14px; font-size: 12px; font-weight: 600; cursor: pointer; margin-top: 8px; }
    .btn-choose:hover { background: #c3d9f8; }

    .detail-table { width: 100%; border-collapse: collapse; }
    .detail-table thead th { background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase; padding: 10px 12px; border-bottom: 2px solid #edf0f5; text-align: center; }
    .detail-table tbody td { padding: 10px 12px; font-size: 13px; border-bottom: 1px solid #f0f2f7; vertical-align: middle; text-align: center; }
    .detail-table tbody tr:last-child td { border-bottom: none; }
    .btn-del-sm { display: inline-flex; align-items: center; gap: 4px; background: #fee2e2; color: #dc2626; border: none; border-radius: 7px; padding: 5px 10px; font-size: 11px; font-weight: 700; cursor: pointer; }
    .btn-del-sm:hover { background: #fecaca; }

    .blinking { animation: blinkingIcon 1s infinite; }
    @keyframes blinkingIcon { 0%,100%{opacity:1;} 50%{opacity:0;} }
</style>
@stop

@section('header')
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid" style="padding: 0 24px;">

    <div id="loading"><div class="loading-box"><div class="loading-spinner"></div><p>Memproses...</p></div></div>

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-tasks"></i>&nbsp; Diagnosa Molding</div>
            <h1>Form Diagnosa Produk</h1>
            <p>{{ $molding_name->fixed_asset_name }} &mdash; {{ $form_number }}</p>
        </div>
        <button class="btn-standar" onclick="$('#modal_info').modal('show')">
            <i class="fas fa-info-circle"></i> Standar Diagnosa
        </button>
    </div>

    {{-- INFORMASI MOLDING --}}
    <div class="section-card">
        <div class="section-card-header"><span class="dot"></span><h4><i class="fas fa-cube" style="color:#605ca8;margin-right:6px;"></i> Informasi Molding</h4></div>
        <div class="section-card-body">
            <div class="info-top-grid">
                <div>
                    <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:16px;">
                        <div><span class="il">Form Number</span><div class="iv"><i class="fas fa-file-alt"></i><span id="form_number">{{ $form_number }}</span></div></div>
                        <div><span class="il">Nama Molding</span><div class="iv"><i class="fas fa-cube"></i> {{ $molding_name->fixed_asset_name }}</div></div>
                    </div>
                    <div class="point-grid">
                        <div class="point-card pc-produk">
                            <span class="pc-lbl">Poin Produk</span>
                            <span class="pc-val" id="total_point">100</span>
                        </div>
                        <div class="point-card pc-molding">
                            <span class="pc-lbl">Poin Molding</span>
                            <span class="pc-val" id="molding_point">@if(isset($penilaian_molding)){{ $penilaian_molding->points }}@else —@endif</span>
                        </div>
                        <div class="point-card pc-rank pc-rank-C" id="ranking_card">
                            <span class="pc-lbl">Ranking</span>
                            <span class="pc-val" id="ranking">C</span>
                        </div>
                        <div class="pc-keputusan">
                            <span class="pc-lbl">Keputusan</span>
                            <span class="pc-val" id="keputusan">Sulit melanjutkan proses produksi. Perlu peremajaan.</span>
                        </div>
                    </div>
                </div>
                <div>
                    <span class="il">Foto Produk</span>
                    <div class="upload-area" onclick="document.getElementById('customFile').click()">
                        <i class="fas fa-cloud-upload-alt fa" ></i>
                        <p>Klik untuk pilih foto produk</p>
                        <small>Format: JPG, PNG, JPEG</small>
                        <input type="file" id="customFile" accept="image/*" style="display:none;" onchange="updateFileLabel(this)">
                    </div>
                    <div id="file-label" style="font-size:12px;color:#605ca8;font-weight:600;margin-top:6px;display:none;"></div>
                    <button type="button" class="btn-upload" onclick="uploadImage()"><i class="fas fa-upload"></i> Upload Foto</button>
                    @if($molding_name->photo_product)
                        <img src="{{ url('workshop/molding/photo_product/main/' . $molding_name->photo_product) }}" alt="Foto Produk" style="border-radius:10px;width:100%;height:auto;display:block;margin-top:12px;" id="photo_product">
                    @else
                        <div style="text-align:center;padding:24px;color:#ddd;"><i class="fas fa-image" style="font-size:60px;display:block;margin-bottom:8px;"></i><span style="font-size:13px;color:#a0aec0;">Belum ada foto produk</span></div>
                    @endif
                </div>
            </div>
        </div>
    </div>

    {{-- LEMBAR DIAGNOSA --}}
    <div class="section-card" id="form_diagnose_container" style="display:none;">
        <div class="section-card-header"><span class="dot"></span><h4><i class="fas fa-clipboard-check" style="color:#605ca8;margin-right:6px;"></i> Lembar Diagnosa Produk</h4></div>
        <div style="padding:14px 24px;border-bottom:1px solid #f0f2f7;background:#fafbff;">
            <div style="font-size:11px;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:.7px;margin-bottom:8px;">Standar Penilaian</div>
            <div class="legenda-wrap">
                <span class="legenda-item lg-ok"><span class="leg-dot" style="background:#bbf7d0;"></span> OK — 0 Poin</span>
                <span class="legenda-item lg-ng-m"><span class="leg-dot" style="background:#ffa3a3;"></span> NG Main — −10 Poin</span>
                <span class="legenda-item lg-ng-s"><span class="leg-dot" style="background:#6dcdf0;"></span> NG Sub — −5 Poin</span>
                <span class="legenda-item lg-ok-s"><span class="leg-dot" style="background:#fde68a;"></span> OK Sementara Main −3 / Sub −1</span>
                <span class="legenda-item lg-isi"><span class="leg-dot" style="background:#e9a7fb;"></span> Isi — Mengikuti tabel</span>
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="diag-table" id="tableMaster">
                <thead>
                    <tr>
                        <th style="width:40px;">#</th>
                        <th style="text-align:left;min-width:160px;">Jenis NG</th>
                        <th style="min-width:240px;">Hasil Diagnosa</th>
                        <th style="width:80px;">Poin</th>
                        <th style="width:56px;">Cek</th>
                        <th style="min-width:190px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="bodyTableMaster"></tbody>
            </table>
        </div>
        <div class="footer-actions">
            <button type="button" class="btn-temp" id="btn_save_temp" onclick="saveProductCheckTemp()"><i class="fas fa-save"></i> Simpan Sementara</button>
            <button type="button" class="btn-save-full" id="btn_save" onclick="saveProductCheck()"><i class="fas fa-check-double"></i> Simpan Sepenuhnya</button>
        </div>
    </div>

</div>

{{-- MODAL: STANDAR DIAGNOSA --}}
<div class="modal fade" id="modal_info" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-hdr-warn"><h5><i class="fas fa-bullhorn blinking"></i> Standar Diagnosa</h5></div>
        <div class="modal-body-pad">
            <div class="standar-item si-ok"><div class="si-lbl"><i class="fas fa-check-circle"></i> OK</div><div class="si-desc">Yang tidak ada masalah. → Tidak ada masalah berdasarkan hasil keputusan QA.</div></div>
            <div class="standar-item si-ng"><div class="si-lbl"><i class="fas fa-times-circle"></i> NG</div><div class="si-desc">Ada masalah.</div></div>
            <div class="standar-item si-oks"><div class="si-lbl"><i class="fas fa-exclamation-circle"></i> OK Sementara</div><div class="si-desc">Berdasarkan poin saat ini QA memutuskan tidak ada masalah, tetapi kedepannya akan menjadi masalah. Ada penanganan lanjutan setelah injection.</div></div>
        </div>
        <div class="modal-ftr"><button type="button" class="btn-modal-ok" data-bs-dismiss="modal" data-dismiss="modal"><i class="fas fa-check"></i> Mengerti</button></div>
    </div></div>
</div>

{{-- MODAL: TAMBAH FOTO NG --}}
<div class="modal fade" id="modal_ng" tabindex="-1" data-bs-backdrop="static" data-bs-keyboard="false">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-hdr"><h5><i class="fas fa-camera"></i> Tambah Foto NG — <span id="nama_ng"></span></h5></div>
        <div class="modal-body-pad">
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:14px;">
                <div><span class="il">Nama NG</span><div style="font-size:14px;font-weight:700;color:#1a202c;" id="nama_ng_disp"></div></div>
                <div><span class="il">Nomor</span><div style="font-size:14px;font-weight:700;color:#605ca8;" id="nomor"></div></div>
            </div>
            <div class="photo-upload-grid">
                <div class="photo-slot">
                    <i class="fas fa-image" style="font-size:48px;color:#ddd;display:block;margin-bottom:8px;" id="dummy_photo_1"></i>
                    <img id="photo_ng_1" src="" style="display:none;width:100%;border-radius:8px;">
                    <input type="file" id="photo_file_1" accept="image/*" style="display:none;">
                    <button type="button" class="btn-choose" onclick="document.getElementById('photo_file_1').click()"><i class="fas fa-image"></i> Foto 1 <span style="color:#dc2626;">*</span></button>
                </div>
                <div class="photo-slot">
                    <i class="fas fa-image" style="font-size:48px;color:#ddd;display:block;margin-bottom:8px;" id="dummy_photo_2"></i>
                    <img id="photo_ng_2" src="" style="display:none;width:100%;border-radius:8px;">
                    <input type="file" id="photo_file_2" accept="image/*" style="display:none;">
                    <button type="button" class="btn-choose" onclick="document.getElementById('photo_file_2').click()"><i class="fas fa-image"></i> Foto 2</button>
                </div>
            </div>
        </div>
        <div class="modal-ftr">
            <button type="button" class="btn-modal-cancel" data-dismiss="modal">Batal</button>
            <button type="button" class="btn-modal-save" onclick="savePhotoNg()"><i class="fas fa-save"></i> Simpan</button>
        </div>
    </div></div>
</div>

{{-- MODAL: DETAIL NG --}}
<div class="modal fade" id="modal_detail" tabindex="-1">
    <div class="modal-dialog modal-lg"><div class="modal-content">
        <div class="modal-hdr">
            <h5><i class="fas fa-search"></i> Detail NG — <span id="nama_ng_detail"></span></h5>
            <button type="button" class="close" data-dismiss="modal"><span>&times;</span></button>
        </div>
        <div class="modal-body-pad" style="overflow-x:auto;">
            <table class="detail-table">
                <thead><tr><th style="width:40px;">No</th><th>Tanggal</th><th>Foto 1</th><th>Foto 2</th><th style="width:80px;">Aksi</th></tr></thead>
                <tbody id="tbody_detail"></tbody>
            </table>
        </div>
    </div></div>
</div>

@endsection

@section('scripts')
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var master_check_list = {!! json_encode($master_check_list) !!};
    var ranks             = {!! json_encode($ranks) !!};
    var molding_penilaian = {!! isset($penilaian_molding) ? json_encode($penilaian_molding->points) : '0' !!};
    var audio_error   = new Audio('{{ url("sounds/error.mp3") }}');
    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');

    jQuery(document).ready(function () {
        $("#wrapper").toggleClass("toggled");
        $('#side_diagnosa_molding').addClass('menu-open');
        $('body').addClass("sidebar-collapse");
        @if($molding_name->photo_product) $('#form_diagnose_container').show(); getData(); @endif
        $('#modal_info').modal('show');
        $('#photo_file_1').on('change', function() { selectPhoto(1); });
        $('#photo_file_2').on('change', function() { selectPhoto(2); });
        var loading = document.getElementById('loading');
        if (loading) {
            loading.style.setProperty('display', 'none', 'important');
        }
    });

    function updateFileLabel(input) {
        if (input.files && input.files[0]) { $('#file-label').show().text('📎 ' + input.files[0].name); }
    }

    function updateRankCard() {
        var molding = parseInt($('#molding_point').text()) || 0;
        var produk  = parseInt($('#total_point').text())   || 100;
        var smallest = (molding_penilaian && molding > 0) ? Math.min(produk, molding) : produk;
        var rankClass = 'pc-rank-C';
        $.each(ranks, function(k, r) {
            if (smallest >= r.point) {
                $('#ranking').text(r.rank); $('#keputusan').text(r.keputusan);
                rankClass = 'pc-rank-' + r.rank.replace(' ',''); return false;
            }
        });
        $('#ranking_card').removeClass('pc-rank-AA pc-rank-A pc-rank-B pc-rank-C').addClass(rankClass);
    }

    function getData() {
        $.get('{{ url("fetch/diagnose_molding/product/check_list") }}', { form_number: $('#form_number').text() }, function(result) {
            if (!result.status) return;
            var tData = ''; var ctr = 1;
            $.each(result.master_check_list, function(key, value) {
                var rc = '', ng = 0, tmp = 0;
                if (value.grouping == 'Main' && value.category_check == 'OK/NG')  { rc='row-ng-main'; ng=10; tmp=3; }
                else if (value.grouping == 'Main' && value.category_check == 'Isi') { rc='row-isi'; }
                else if (value.grouping == 'Secondary') { rc='row-ng-sub'; ng=5; tmp=1; }
                else if (!value.grouping) { rc='row-ok'; }

                tData += '<tr class="'+rc+'">';
                tData += '<td style="text-align:center;color:#a0aec0;font-weight:700;font-size:12px;">'+(ctr++)+'<input type="hidden" class="ng_id_val" value="'+value.id+'"></td>';
                tData += '<td style="font-weight:600;" class="ng_name">'+value.item_ng+'</td>';

                if (value.category_check == 'OK/NG') {
                    tData += '<td><div class="radio-grp">';
                    tData += '<label class="r-pill ok"><input type="radio" name="r3_'+value.id+'" id="OK_'+value.id+'" value="0" onclick="changePengurangan('+value.id+')" tag="OK"> OK</label>';
                    tData += '<label class="r-pill ng"><input type="radio" name="r3_'+value.id+'" id="NG_'+value.id+'" value="'+ng+'" onclick="changePengurangan('+value.id+')" tag="NG"> NG</label>';
                    tData += '<label class="r-pill oks"><input type="radio" name="r3_'+value.id+'" id="OK_Sementara_'+value.id+'" value="'+tmp+'" onclick="changePengurangan('+value.id+')" tag="OK Sementara"> OK Sementara</label>';
                    tData += '</div></td>';
                } else {
                    tData += '<td><div class="isi-grp">';
                    tData += '<label class="isi-pill"><input type="radio" name="r3_'+value.id+'" value="10" onclick="changePengurangan('+value.id+')" tag="1 ~ 10"> 1~10 : −10</label>';
                    tData += '<label class="isi-pill"><input type="radio" name="r3_'+value.id+'" value="20" onclick="changePengurangan('+value.id+')" tag="11 ~ 30"> 11~30 : −20</label>';
                    tData += '<label class="isi-pill"><input type="radio" name="r3_'+value.id+'" value="30" onclick="changePengurangan('+value.id+')" tag="31 ~ 50"> 31~50 : −30</label>';
                    tData += '<label class="isi-pill"><input type="radio" name="r3_'+value.id+'" value="50" onclick="changePengurangan('+value.id+')" tag="> 51"> >51 : −50</label>';
                    tData += '</div></td>';
                }

                tData += '<td class="poin-cell" id="pengurangan_'+value.id+'">0</td>';
                tData += '<td><i class="fas fa-check cek" id="cek_'+value.id+'"></i></td>';
                tData += '<td style="text-align:center;">';
                tData += '<button class="act-btn ab-photo btn_ng" onclick="buatNG('+value.id+',\''+value.item_ng+'\')"><i class="fas fa-camera"></i> Foto NG</button>';
                tData += '<button class="act-btn ab-detail" onclick="modalDetail('+value.id+',\''+value.item_ng+'\')"><i class="fas fa-info"></i> Detail</button>';
                tData += '</td></tr>';
            });

            tData += '<tr class="total-row"><td colspan="3" style="text-align:right;padding:12px 14px;">Total Pengurangan</td><td class="total-val" id="total_pengurangan">0</td><td colspan="2" class="point-val">Poin : <span id="total_points">100</span></td></tr>';
            $('#bodyTableMaster').html(tData);

            var status_form = true;
            if (result.actual_check_list.length > 0) {
                if (result.actual_check_list[0].status == 'Closed') status_form = false;
                $.each(result.actual_check_list, function(k, v) {
                    $('input[name="r3_'+v.ng_id+'"][value="'+v.actual_deduction+'"]').prop('checked', true);
                    changePengurangan(v.ng_id);
                });
            }
            if (!status_form) {
                $.each(result.master_check_list, function(k, v) { $('input[name="r3_'+v.id+'"]').prop('disabled', true); });
                $('.btn_ng, #btn_save_temp, #btn_save').prop('disabled', true);
            }
        });
    }

    function changePengurangan(id) {
        var val = parseInt($('input[name="r3_'+id+'"]:checked').val()) || 0;
        $('#pengurangan_'+id).text(val > 0 ? '-'+val : '0').toggleClass('neg', val > 0);
        $('#cek_'+id).addClass('done');
        var total = 0;
        $('.poin-cell').each(function() { total += Math.abs(parseInt($(this).text().replace('-','')) || 0); });
        $('#total_pengurangan').text(total > 0 ? '-'+total : '0');
        $('#total_point, #total_points').text(100 - total);
        updateRankCard();
    }

    function uploadImage() {
        if ($('#customFile')[0].files.length == 0) { toastr.error('Mohon pilih file terlebih dahulu'); audio_error.play(); return; }
        $('#loading').show();
        var fd = new FormData();
        fd.append('image', $('#customFile')[0].files[0]);
        fd.append('form_number', $('#form_number').text());
        $.ajax({ url:'{{ url("upload/diagnose_molding/product_image") }}', type:'POST', data:fd, processData:false, contentType:false,
            success: function(r) { $('#loading').hide(); if (r.status) { toastr.success(r.message); location.reload(); } else { toastr.error(r.message); audio_error.play(); } },
            error: function() { $('#loading').hide(); toastr.error('Terjadi kesalahan'); audio_error.play(); }
        });
    }

    function buatNG(id, nama_ng) { $('#modal_ng').modal('show'); $('#nomor').text(id); $('#nama_ng, #nama_ng_disp').text(nama_ng); }

    function savePhotoNg() {
        if (!$('#photo_file_1')[0].files[0]) { toastr.error('Foto 1 wajib diisi'); return; }
        $('#loading').show();
        var fd = new FormData();
        fd.append('nomor', $('#nomor').text()); fd.append('nama_ng', $('#nama_ng').text());
        fd.append('photo_file_1', $('#photo_file_1')[0].files[0]);
        if ($('#photo_file_2')[0].files[0]) fd.append('photo_file_2', $('#photo_file_2')[0].files[0]);
        fd.append('form_number', $('#form_number').text());
        $.ajax({ url:'{{ url("upload/diagnose_molding/photo_ng") }}', type:'POST', data:fd, processData:false, contentType:false,
            success: function(r) {
                $('#loading').hide();
                if (r.status) { toastr.success(r.message); audio_success.play(); $('#modal_ng').modal('hide'); $('#photo_file_1,#photo_file_2').val(''); $('#photo_ng_1,#photo_ng_2').hide().attr('src',''); $('#dummy_photo_1,#dummy_photo_2').show(); }
                else { toastr.error(r.message); audio_error.play(); }
            }, error: function() { $('#loading').hide(); toastr.error('Terjadi kesalahan'); audio_error.play(); }
        });
    }

    function selectPhoto(id) {
        var file = $('#photo_file_'+id)[0].files[0];
        if (file) { var r = new FileReader(); r.onloadend = function() { $('#photo_ng_'+id).attr('src',r.result).show(); $('#dummy_photo_'+id).hide(); }; r.readAsDataURL(file); }
    }

    function modalDetail(id, nama_ng) {
        $('#tbody_detail').html('<tr><td colspan="5" style="text-align:center;padding:20px;color:#718096;">Memuat...</td></tr>');
        $('#modal_detail').modal('show'); $('#nama_ng_detail').text(nama_ng);
        $.get('{{ url("fetch/diagnose_molding/product_details") }}', { id:id, form_number:$('#form_number').text() }, function(result) {
            if (!result.status) { toastr.error(result.message); return; }
            var rows = result.product_ng.length > 0 ? '' : '<tr><td colspan="5" style="text-align:center;color:#a0aec0;padding:16px;">Tidak ada data</td></tr>';
            $.each(result.product_ng, function(k, v) {
                rows += '<tr><td>'+(k+1)+'</td><td>'+v.check_at.replace(' ','<br>')+'</td>';
                rows += '<td><img src="{{ asset("workshop/molding/photo_product/ng/") }}/'+v.photo1+'" style="max-width:110px;border-radius:6px;"></td>';
                rows += '<td>'+(v.photo2?'<img src="{{ asset("workshop/molding/photo_product/ng/") }}/'+v.photo2+'" style="max-width:110px;border-radius:6px;">':'—')+'</td>';
                rows += '<td><button class="btn-del-sm" onclick="deleteProductNg('+v.id+')"><i class="fas fa-trash"></i></button></td></tr>';
            });
            $('#tbody_detail').html(rows);
        });
    }

    function deleteProductNg(id) {
        Swal.fire({ title:'Hapus data ini?', icon:'warning', showCancelButton:true, confirmButtonText:'Hapus', cancelButtonText:'Batal', confirmButtonColor:'#dc2626', cancelButtonColor:'#718096' })
        .then(function(r) { if (r.isConfirmed) { $.post('{{ url("delete/diagnose_molding/product_ng") }}', {id:id, _token:'{{ csrf_token() }}'}, function(res) { if (res.status) { toastr.success(res.message); audio_success.play(); $('#modal_detail').modal('hide'); } else { toastr.error(res.message); audio_error.play(); } }); } });
    }

    function collectNgData() {
        var ng_id=[],ng_name=[],ng_value_name=[],ng_value=[];
        $('.ng_id_val').each(function() { var ids=$(this).val(); $('input[name="r3_'+ids+'"]:checked').each(function() { ng_id.push(ids); ng_value.push($(this).val()); ng_value_name.push($(this).attr('tag')); }); });
        $('.ng_name').each(function() { ng_name.push($(this).text()); });
        return { form_number:$('#form_number').text(), total_point:$('#total_points').text(), ng_id, ng_name, ng_value, ng_value_name };
    }

    function saveProductCheckTemp() {
        Swal.fire({ title:'Simpan Sementara?', icon:'question', showCancelButton:true, confirmButtonText:'<i class="fas fa-save"></i> Ya', cancelButtonText:'Batal', confirmButtonColor:'#b45309', cancelButtonColor:'#718096' })
        .then(function(r) { if (r.isConfirmed) { $.ajax({ url:'{{ url("save/diagnose_molding/product_check") }}', type:'POST', data:collectNgData(), success:function(res){ if(res.status){toastr.success(res.message);audio_success.play();}else{toastr.error(res.message);audio_error.play();} } }); } });
    }

    function saveProductCheck() {
        if ($('.cek:not(.done)').length > 0) { toastr.error('Harap cek semua item terlebih dahulu'); audio_error.play(); return; }
        Swal.fire({ title:'Simpan Sepenuhnya?', icon:'question', showCancelButton:true, confirmButtonText:'<i class="fas fa-check-double"></i> Ya, Simpan', cancelButtonText:'Batal', confirmButtonColor:'#15803d', cancelButtonColor:'#718096' })
        .then(function(r) { if (r.isConfirmed) { $.ajax({ url:'{{ url("save/diagnose_molding/product_check_real") }}', type:'POST', data:collectNgData(), success:function(res){ if(res.status){toastr.success(res.message);audio_success.play();}else{toastr.error(res.message);audio_error.play();} } }); } });
    }
</script>
@endsection