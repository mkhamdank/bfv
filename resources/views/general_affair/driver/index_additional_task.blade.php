@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
<link href="{{ url("css/jquery.numpad.css") }}" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ── BASE ── */
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="select2"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── LOADING ── */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.42); backdrop-filter: blur(6px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    #loading_ocr {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.42); backdrop-filter: blur(6px);
        z-index: 30002; align-items: center; justify-content: center;
    }
    #loading_ocr.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 52px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 16px 48px rgba(0,0,0,.18);
    }
    .loading-box-ocr {
        background: #fff; border-radius: 20px; padding: 36px 52px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 16px 48px rgba(0,0,0,.18);
    }
    .loading-spinner {
        width: 44px; height: 44px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    .loading-spinner-ocr {
        width: 44px; height: 44px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }
    .loading-box-ocr p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    

    /* ── PAGE HEADER ── */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 16px;
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
    .btn-header-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
        color: #fff; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: background .18s;
    }
    .btn-header-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

    /* ── ERROR CARD ── */
    .error-card {
        background: #fff; border-radius: 16px; padding: 32px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        border-left: 4px solid #dc2626; margin-bottom: 24px;
        display: flex; align-items: flex-start; gap: 16px;
    }
    .error-icon { font-size: 28px; color: #dc2626; flex-shrink: 0; margin-top: 2px; }
    .error-card h4 { color: #dc2626; font-weight: 700; font-size: 16px; margin: 0 0 6px; }
    .error-card p  { color: #718096; font-size: 14px; margin: 0; }

    /* ── SUCCESS CARD ── */
    .success-card {
        background: #fff; border-radius: 18px;
        box-shadow: 0 4px 24px rgba(0,0,0,.08); border: 1px solid rgba(0,0,0,.05);
        padding: 48px 32px; text-align: center; margin-bottom: 24px;
    }
    .success-icon-ring {
        width: 80px; height: 80px; border-radius: 50%;
        background: linear-gradient(135deg, #dcfce7, #bbf7d0);
        border: 3px solid #86efac;
        display: flex; align-items: center; justify-content: center;
        margin: 0 auto 20px; font-size: 32px;
    }
    .success-card h3 { color: #15803d; font-weight: 800; font-size: 22px; margin: 0 0 8px; }
    .success-card p  { color: #4a5568; font-size: 14px; margin: 0 0 4px; }
    .success-card .jp { color: #a0aec0; font-size: 13px; }

    /* ── INFO CARD ── */
    .info-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .info-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .dot-purple { background: #605ca8; }
    .dot-blue   { background: #3b82f6; }
    .dot-rose   { background: #f43f5e; }
    .info-card-body { padding: 20px 22px; }

    /* ── FORM FIELDS ── */
    .ff { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .ff:last-child { margin-bottom: 0; }
    .ff label {
        font-size: 11px; font-weight: 700; color: #4a5568;
        letter-spacing: .06em; text-transform: uppercase; margin: 0;
    }
    .ff input[type="text"], .ff input[type="number"] {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input[type="text"]:focus, .ff input[type="number"]:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] {
        background: #f0eef9 !important; color: #605ca8 !important;
        font-weight: 600 !important; border-color: #c4bfef !important; cursor: default;
    }
    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 16px; }

    /* ── SECTION TABLE CARD (E-Toll / Parkir) ── */
    .section-table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .stc-header {
        padding: 14px 20px; display: flex; align-items: center; justify-content: space-between;
        border-bottom: 1px solid #f0f2f7;
    }
    .stc-title { display: flex; align-items: center; gap: 10px; font-size: 14px; font-weight: 700; color: #1a202c; }
    .stc-body { padding: 0; }

    /* ── ROW ITEM ── */
    .entry-row {
        padding: 16px 20px; border-bottom: 1px solid #f0f2f7;
        display: flex; flex-direction: column; gap: 10px;
        animation: fadeIn .25s ease;
    }
    .entry-row:last-child { border-bottom: none; }
    @keyframes fadeIn { from { opacity:0; transform:translateY(6px); } to { opacity:1; transform:translateY(0); } }
    .entry-row-top { display: grid; grid-template-columns: 1fr 1fr auto; gap: 10px; align-items: end; }
    .entry-row-bottom { display: grid; grid-template-columns: 1fr; gap: 0; }

    /* cost field accent */
    .cost-input {
        border: 1.5px solid #c4bfef !important; background: #f0eef9 !important;
        color: #4a4690 !important; font-weight: 700 !important;
    }
    .cost-input:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }

    /* ── PHOTO UPLOAD ── */
    .photo-upload-wrap {
        border: 1.5px dashed #c4bfef; border-radius: 10px;
        background: #faf9ff; overflow: hidden;
        transition: border-color .18s;
    }
    .photo-upload-wrap:hover { border-color: #605ca8; }
    .photo-upload-label {
        display: flex; align-items: center; gap: 7px;
        padding: 10px 13px; font-size: 11.5px; font-weight: 700;
        color: #605ca8; letter-spacing: .04em; text-transform: uppercase;
    }
    .photo-upload-wrap input[type="file"] {
        border: none !important; background: transparent !important;
        padding: 0 13px 10px !important; font-size: 12.5px !important;
        color: #4a5568 !important; width: 100% !important; cursor: pointer;
    }
    .photo-preview-img {
        display: none; width: 100%; max-height: 200px; object-fit: cover;
        border-top: 1.5px solid #ede9fe;
    }

    /* ── BUTTONS ── */
    .btn-add {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 9px; font-size: 12px; font-weight: 700;
        cursor: pointer; border: none; transition: all .18s;
    }
    .btn-add-blue { background: #ede9fe; color: #605ca8; border: 1.5px solid #c4bfef; }
    .btn-add-blue:hover { background: #ddd6fe; border-color: #8b87d4; }
    .btn-add-rose { background: #fff1f2; color: #f43f5e; border: 1.5px solid #fecdd3; }
    .btn-add-rose:hover { background: #ffe4e6; border-color: #fb7185; }

    .btn-remove {
        display: inline-flex; align-items: center; justify-content: center;
        width: 32px; height: 32px; border-radius: 8px; border: none;
        background: #fee2e2; color: #dc2626; font-size: 13px;
        cursor: pointer; transition: background .18s; flex-shrink: 0;
    }
    .btn-remove:hover { background: #fecaca; }

    .btn-submit {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 14px; border: none; border-radius: 12px;
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff; font-size: 15px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 16px rgba(21,128,61,.3);
        transition: opacity .18s, transform .15s;
    }
    .btn-submit:hover { opacity: .9; transform: translateY(-1px); }
    .btn-back-bottom {
        display: flex; align-items: center; justify-content: center; gap: 8px;
        width: 100%; padding: 12px; border-radius: 12px; margin-top: 12px;
        background: #fff; border: 1.5px solid #e2e8f0;
        color: #4a5568; font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: all .18s;
    }
    .btn-back-bottom:hover { background: #f5f3ff; border-color: #c4bfef; color: #605ca8; text-decoration: none; }
    .btn-success-back {
        display: inline-flex; align-items: center; gap: 8px;
        padding: 12px 28px; border-radius: 12px; margin-top: 24px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff; font-size: 14px; font-weight: 700;
        text-decoration: none; transition: opacity .18s;
        box-shadow: 0 4px 14px rgba(96,92,168,.3);
    }
    .btn-success-back:hover { opacity: .88; color: #fff; text-decoration: none; }

    /* ── MISC ── */
    .page-wrapper { padding-top: 0 !important; }
    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }
    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th,
    .datepicker-months > table > thead > tr > th,
    .datepicker-years > table > thead > tr > th,
    .datepicker-decades > table > thead > tr > th,
    .datepicker-centuries > table > thead > tr > th {
        background-color: white; color: #696969 !important;
    }
</style>
@stop

@section('content')

{{-- Loading Overlay --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Menyimpan data...</p>
    </div>
</div>

<div id="loading_ocr">
    <div class="loading-box-ocr">
        <div class="loading-spinner-ocr"></div>
        <p>Membaca data dengan OCR...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-receipt"></i> Biaya Tambahan</div>
            <h1>{{ $title }}</h1>
            <p>Input E-Toll &amp; Biaya Parkir Driver</p>
        </div>
        <div>
            <a class="btn-header-back" href="{{ url('index/driver/toll_parking') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- ── ERROR STATE ── --}}
    @if($status == 'error')
    <div class="error-card">
        <div class="error-icon"><i class="fas fa-exclamation-circle"></i></div>
        <div>
            <h4>Terjadi Kesalahan</h4>
            <p>{{ $message }}</p>
        </div>
    </div>
    @endif

    {{-- ── SUCCESS STATE ── --}}
    <div id="div_driver_3" style="display:none;">
        <div class="success-card">
            <div class="success-icon-ring">
                <i class="fas fa-check" style="color:#15803d;"></i>
            </div>
            <h3>Sukses!</h3>
            <p>Data berhasil dikirim</p>
            <p class="jp">データの入力に成功しました</p>
            <a href="{{ url('index/driver/toll_parking') }}" class="btn-success-back">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>
    </div>

    @if($status == 'success')
    <input type="hidden" id="id"      value="{{ $driver_task->id }}">
    <input type="hidden" id="task_id" value="{{ $driver_task->task_id }}">

    {{-- ── SECTION 1: INFO DRIVER ── --}}
    <div id="div_driver_1">
        <div class="info-card">
            <div class="info-card-header">
                <span class="dot dot-purple"></span> Informasi Driver
            </div>
            <div class="info-card-body">
                <div class="g2">
                    <div class="ff">
                        <label>Driver</label>
                        <input type="text" id="driver" readonly value="{{ $driver_task->driver_name }}">
                    </div>
                    <div class="ff">
                        <label>Tanggal</label>
                        <input type="text" id="date" readonly value="{{ date('d-m-Y', strtotime($driver_task->date_from)) }}">
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- ── SECTION 2: FORM INPUT ── --}}
    <div id="div_driver_2">

        {{-- E-Toll Card --}}
        <div class="section-table-card">
            <div class="stc-header" style="background:#dbeafe;border-bottom:1px solid #3b82f6;">
                <div class="stc-title">
                    <span class="dot dot-blue"></span>
                    <i class="fas fa-credit-card" style="color:#3b82f6;font-size:14px;"></i>
                    E-Toll (E-Money)
                </div>
                <button type="button" class="btn-add btn-add-blue" onclick="addEtoll()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div class="stc-body" id="bodyEtoll">
                {{-- Row 0 (default) --}}
                <div class="entry-row" id="tr_etoll_0">
                    <div class="photo-upload-wrap">
                        <div class="photo-upload-label">
                            <i class="fas fa-camera"></i> Foto Bukti E-Toll
                            <span style="color:#dc2626;">*</span>
                        </div>
                        <input type="file" name="file_etoll_0" id="file_etoll_0"
                               accept="image/*" onchange="readURLEtoll(this, 0);">
                        <img id="blah_etoll_0" class="photo-preview-img" src="" alt="">
                        <input type="hidden" name="json_etoll_0" id="json_etoll_0" value="">
                    </div>
                    <div id="div_etoll_0" style="display: none;">
                        <div class="entry-row-top">
                            <div class="ff" style="margin:0;">
                                <label>Dari</label>
                                <input type="text" name="from_0" id="from_0" placeholder="Titik awal">
                            </div>
                            <div class="ff" style="margin:0;">
                                <label>Ke</label>
                                <input type="text" name="to_0" id="to_0" placeholder="Titik tujuan">
                            </div>
                            <div><!-- spacer for delete btn --></div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">
                            <div class="ff" style="margin:0;">
                                <label>Biaya (Rp) <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="etoll_0" id="etoll_0"
                                    class="cost-input" placeholder="Nominal (min. 100)"
                                    inputmode="numeric" pattern="[0-9]*">
                            </div>
                            <div style="padding-bottom:0;">&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Parkir Card --}}
        <div class="section-table-card">
            <div class="stc-header" style="background:#fef3f2;border-bottom:1px solid #fecaca;">
                <div class="stc-title">
                    <span class="dot dot-amber"></span>
                    <i class="fas fa-parking" style="color:#d97706;font-size:14px;"></i>
                    Parkir (E-Money)
                </div>
                <button type="button" class="btn-add btn-add-amber" onclick="addParkingEmoney()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div class="stc-body" id="bodyParkingEmoney">
                {{-- Row 0 (default) --}}
                <div class="entry-row" id="tr_parking_emoney_0">
                    <div class="photo-upload-wrap">
                        <div class="photo-upload-label">
                            <i class="fas fa-camera"></i> Foto Bukti Parkir (E-Money)
                            <span style="color:#dc2626;">*</span>
                        </div>
                        <input type="file" name="file_parking_emoney_0" id="file_parking_emoney_0"
                               accept="image/*" onchange="readURLParkingEmoney(this, 0);">
                        <img id="blah_parking_emoney_0" class="photo-preview-img" src="" alt="">
                        <input type="hidden" name="json_parking_emoney_0" id="json_parking_emoney_0" value="">
                    </div>
                    <div id="div_parking_emoney_0" style="display: none;">
                        <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">
                            <div class="ff" style="margin:0;">
                                <label>Lokasi Parkir (E-Money)</label>
                                <input type="text" name="parking_emoney_at_0" id="parking_emoney_at_0" placeholder="Nama lokasi">
                            </div>
                            <div><!-- spacer --></div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">
                            <div class="ff" style="margin:0;">
                                <label>Biaya (E-Money) (Rp) <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="parking_emoney_0" id="parking_emoney_0"
                                    class="cost-input" placeholder="Nominal (min. 100)"
                                    inputmode="numeric" pattern="[0-9]*">
                            </div>
                            <div>&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Parkir Card --}}
        <div class="section-table-card">
            <div class="stc-header" style="background:#fff5f7;border-bottom:1px solid #fce7f3;">
                <div class="stc-title">
                    <span class="dot dot-rose"></span>
                    <i class="fas fa-parking" style="color:#f43f5e;font-size:14px;"></i>
                    Parkir (Tunai)
                </div>
                <button type="button" class="btn-add btn-add-rose" onclick="addParking()">
                    <i class="fas fa-plus"></i> Tambah
                </button>
            </div>
            <div class="stc-body" id="bodyParking">
                {{-- Row 0 (default) --}}
                <div class="entry-row" id="tr_parking_0">
                    <div class="photo-upload-wrap">
                        <div class="photo-upload-label">
                            <i class="fas fa-camera"></i> Foto Bukti Parkir (Tunai)
                            <span style="color:#dc2626;">*</span>
                        </div>
                        <input type="file" name="file_parking_0" id="file_parking_0"
                               accept="image/*" onchange="readURLParking(this, 0);">
                        <img id="blah_parking_0" class="photo-preview-img" src="" alt="">
                        <input type="hidden" name="json_parking_0" id="json_parking_0" value="">
                    </div>
                    <div id="div_parking_0" style="display: none;">
                        <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">
                            <div class="ff" style="margin:0;">
                                <label>Lokasi Parkir (Tunai)</label>
                                <input type="text" name="parking_at_0" id="parking_at_0" placeholder="Nama lokasi">
                            </div>
                            <div><!-- spacer --></div>
                        </div>
                        <div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">
                            <div class="ff" style="margin:0;">
                                <label>Biaya (Tunai) (Rp) <span style="color:#dc2626;">*</span></label>
                                <input type="text" name="parking_0" id="parking_0"
                                    class="cost-input" placeholder="Nominal (min. 100)"
                                    inputmode="numeric" pattern="[0-9]*">
                            </div>
                            <div>&nbsp;</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Submit --}}
        <div style="margin-bottom:40px;">
            <button class="btn-submit" onclick="submitDriver()">
                <i class="fas fa-paper-plane"></i> Submit Data
            </button>
            <a class="btn-back-bottom" href="{{ url('index/driver/toll_parking') }}">
                <i class="fas fa-arrow-left"></i> Kembali ke Daftar
            </a>
        </div>

    </div>{{-- /#div_driver_2 --}}
    @endif

</div>
@endsection

@section('scripts')
<script src="{{ url("js/jquery.numpad.js") }}"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    /* ── Numpad ── */
    var isSmall = screen.width < 400;
    $.fn.numpad.defaults.gridTpl           = '<table class="table modal-content" style="width:' + (isSmall ? '80%' : '25%') + ';background-color:white;"></table>';
    $.fn.numpad.defaults.backgroundTpl     = '<div class="modal-backdrop in" style="opacity:.4"></div>';
    $.fn.numpad.defaults.displayTpl        = '<input type="text" class="form-control" style="font-size:' + (isSmall ? '6vw' : '2vw') + ';height:50px;"/>';
    $.fn.numpad.defaults.buttonNumberTpl   = '<button type="button" class="btn btn-info" style="font-size:' + (isSmall ? '5vw' : '2vw') + ';width:50px;"></button>';
    $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn btn-success" style="font-size:' + (isSmall ? '5vw' : '2vw') + ';width:100%;color:white;background-color:green;border-color:green"></button>';
    $.fn.numpad.defaults.onKeypadCreate = function () {
        $(this).find('.done').css({ 'background-color':'white','color':'rgb(72,156,78)','border-color':'rgb(72,156,78)' });
        if (isSmall) $(this).find('.done').css('width','100px');
        $(this).find('.sep').css({ 'background-color':'white','color':'black','border-color':'#0dcaf0','width':'50px' });
        $(this).find('.cancel').css({ 'background-color':'white','color':'rgb(219,103,115)','border-color':'rgb(219,103,115)' }).html('<i class="fas fa-times"></i>');
        if (isSmall) $(this).find('.cancel').css('width','50px');
        $(this).find('.clear').css({ 'background-color':'white','color':'black','border-color':'black' }).html('<i class="fas fa-trash"></i>');
        if (isSmall) $(this).find('.clear').css('width','50px');
        $(this).find('.del').css({ 'background-color':'white','color':'black','border-color':'black' }).html('<i class="fas fa-backspace"></i>');
        if (isSmall) $(this).find('.del').css({ 'width':'50px','font-size':'4vw' });
    };

    /* ── Ready ── */
    $(document).ready(function () {
        $('body').toggleClass('sidebar-collapse');
        $('#side_vfi').addClass('menu-open');
        $('.select2').select2({ allowClear: true });
    });

    /* ── Image compress helper ── */
    function compressImage(input, imgId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    var canvas = document.createElement('canvas');
                    canvas.width  = img.width  * 0.5;
                    canvas.height = img.height * 0.5;
                    canvas.getContext('2d').drawImage(img, 0, 0, canvas.width, canvas.height);
                    var compressed = canvas.toDataURL('image/jpeg', 0.7);
                    $('#' + imgId).attr('src', compressed).show();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURLEtoll(input, id)   { compressImage(input, 'blah_etoll_'   + id); ocrEtoll(input, id); }
    function readURLParking(input, id) { compressImage(input, 'blah_parking_' + id); ocrParking(input, id); }
    function readURLParkingEmoney(input, id) { compressImage(input, 'blah_parking_emoney_' + id); ocrParkingEmoney(input, id); }

    function toggleOcrLoading(show) {
        $('#loading_ocr').toggleClass('show', show);
    }
    
    function ocrEtoll(input, id) {
        var formData = new FormData();
        formData.append('image', input.files[0]);
        toggleOcrLoading(true);

        $.ajax({
            url: '{{ url("/api/ocr/etoll") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                renderOcrResult(response, id, 'etoll');
            },
            error: function() {
                openErrorGritter('Error!', 'Gagal memproses OCR E-Toll');
            },
            complete: function() {
                toggleOcrLoading(false);
            }
        });
    }

    function ocrParking(input, id) {
        var formData = new FormData();
        formData.append('image', input.files[0]);
        toggleOcrLoading(true);

        $.ajax({
            url: '{{ url("/api/ocr/parking") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                renderOcrResult(response, id, 'parking');
            },
            error: function() {
                openErrorGritter('Error!', 'Gagal memproses OCR Parkir');
            },
            complete: function() {
                toggleOcrLoading(false);
            }
        });
    }

    function ocrParkingEmoney(input, id) {
        var formData = new FormData();
        formData.append('image', input.files[0]);
        toggleOcrLoading(true);

        $.ajax({
            url: '{{ url("/api/ocr/parking") }}',
            type: 'POST',
            data: formData,
            processData: false,
            contentType: false,
            success: function(response) {
                renderOcrResult(response, id, 'parking_emoney');
            },
            error: function() {
                openErrorGritter('Error!', 'Gagal memproses OCR Parkir E-Money');
            },
            complete: function() {
                toggleOcrLoading(false);
            }
        });
    }

    function renderOcrResult(response, id, type) {
        if (type === 'etoll') {
            var salah = 0;
            var selectedDate = ($('#date').val() || '').toString().replace(/\//g, '-');
            if (selectedDate) {
                var dateParts = selectedDate.split('-');
                if (dateParts.length === 3 && dateParts[0].length !== 4) {
                    selectedDate = dateParts[2] + '-' + dateParts[1].padStart(2, '0') + '-' + dateParts[0].padStart(2, '0');
                }
            }
            var transactions = Array.isArray(response.transactions) ? response.transactions : [];
            var json_etoll = '';
            if (transactions.length > 0) {
                var firstTransaction = transactions[0] || {};
                var lastTransaction = transactions[transactions.length - 1] || {};
                var totalAmount = 0;

                $.each(transactions, function(_, transaction) {
                    var transactionDate = (transaction.tanggal_jam || '').toString().split(' ')[0];
                    totalAmount += parseInt(transaction.amount || 0, 10) || 0;
                    if (selectedDate && transactionDate != selectedDate) {
                        salah++;
                    }
                    json_etoll += JSON.stringify(transaction);
                });

                if(salah > 0) {
                    openErrorGritter('Error!', 'Tanggal pada transaksi tidak sesuai dengan tanggal ' + selectedDate);
                    return false;
                }

                if (firstTransaction.lokasi) {
                    $('#from_' + id).val(firstTransaction.asal);
                    $('#from_' + id).prop('readonly', true);
                }

                if (lastTransaction.lokasi) {
                    $('#to_' + id).val(lastTransaction.lokasi);
                    $('#to_' + id).prop('readonly', true);
                }

                if (totalAmount > 0) {
                    $('#etoll_' + id).val(totalAmount);
                    $('#etoll_' + id).prop('readonly', true);
                }
            }
            $('#json_etoll_' + id).val(json_etoll);

            $('#div_etoll_' + id).show();
        } else if (type === 'parking') {
            var json_parking = '';
            if (response.location) {
                $('#parking_at_' + id).val(response.location);
            }

            if (response.amount) {
                $('#parking_' + id).val(parseInt(response.amount, 10) || 0);
                $('#parking_' + id).prop('readonly', true);
                json_parking += JSON.stringify(response);
            }
            $('#json_parking_' + id).val(json_parking);
            $('#div_parking_' + id).show();
        } else if (type === 'parking_emoney') {
            var json_parking_emoney = '';
            if (response.location) {
                $('#parking_emoney_at_' + id).val(response.location);
            }

            if (response.amount) {
                $('#parking_emoney_' + id).val(parseInt(response.amount, 10) || 0);
                $('#parking_emoney_' + id).prop('readonly', true);
                json_parking_emoney += JSON.stringify(response);
            }
            $('#json_parking_emoney_' + id).val(json_parking_emoney);
            $('#div_parking_emoney_' + id).show();
        }
    }

    /* ── Counters ── */
    var count_etoll   = 1;
    var count_parking = 1;
    var count_parking_emoney = 1;

    /* ── Add / Remove E-Toll ── */
    function addEtoll() {
        var i = count_etoll;
        var html = '<div class="entry-row" id="tr_etoll_' + i + '">';
        html += '<div class="photo-upload-wrap">';
        html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">';
        html += '<div class="photo-upload-label" style="margin-bottom:0;"><i class="fas fa-camera"></i> Foto Bukti E-Toll <span style="color:#dc2626;">*</span></div>';
        html += '<button class="btn-remove" style="margin-right: 3px;" type="button" onclick="removeEtoll(' + i + ')"><i class="fas fa-trash"></i></button>';
        html += '</div>';
        html += '<input type="file" name="file_etoll_' + i + '" id="file_etoll_' + i + '" accept="image/*" onchange="readURLEtoll(this,' + i + ');">';
        html += '<img id="blah_etoll_' + i + '" class="photo-preview-img" src="" alt="">';
        html += '<input type="hidden" name="json_etoll_' + i + '" id="json_etoll_' + i + '" value="">';
        html += '</div>';
        html += '<div id="div_etoll_' + i + '" style="display: none;">';
        html += '<div class="entry-row-top">';
        html += '<div class="ff" style="margin:0;"><label>Dari</label><input type="text" name="from_' + i + '" id="from_' + i + '" placeholder="Titik awal"></div>';
        html += '<div class="ff" style="margin:0;"><label>Ke</label><input type="text" name="to_' + i + '" id="to_' + i + '" placeholder="Titik tujuan"></div>';
        html += '</div>';
        html += '<div class="ff" style="margin:0;"><label>Biaya (Rp) <span style="color:#dc2626;">*</span></label><input type="text" name="etoll_' + i + '" id="etoll_' + i + '" class="cost-input" placeholder="Nominal (min. 100)" inputmode="numeric" pattern="[0-9]*"></div>';
        html += '</div>';
        html += '</div>';
        
        $('#bodyEtoll').append(html);
        count_etoll++;
    }

    function removeEtoll(id) { $('#tr_etoll_' + id).remove(); }

    /* ── Add / Remove Parkir ── */
    function addParking() {
        var i = count_parking;
        var html = '<div class="entry-row" id="tr_parking_' + i + '">';
        html += '<div class="photo-upload-wrap">';
        html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">';
        html += '<div class="photo-upload-label" style="margin-bottom:0;"><i class="fas fa-camera"></i> Foto Bukti Parkir <span style="color:#dc2626;">*</span></div>';
        html += '<button class="btn-remove" style="margin-right: 3px;" type="button" onclick="removeParking(' + i + ')"><i class="fas fa-trash"></i></button>';
        html += '</div>';
        html += '<input type="file" name="file_parking_' + i + '" id="file_parking_' + i + '" accept="image/*" onchange="readURLParking(this,' + i + ');">';
        html += '<img id="blah_parking_' + i + '" class="photo-preview-img" src="" alt="">';
        html += '<input type="hidden" name="json_parking_' + i + '" id="json_parking_' + i + '" value="">';
        html += '</div>';
        html += '<div id="div_parking_' + i + '" style="display: none;">';
        html += '<div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">';
        html += '<div class="ff" style="margin:0;"><label>Lokasi Parkir</label><input type="text" name="parking_at_' + i + '" id="parking_at_' + i + '" placeholder="Nama lokasi"></div>';
        html += '</div>';
        html += '<div class="ff" style="margin:0;"><label>Biaya (Rp) <span style="color:#dc2626;">*</span></label><input type="text" name="parking_' + i + '" id="parking_' + i + '" class="cost-input" placeholder="Nominal (min. 100)" inputmode="numeric" pattern="[0-9]*"></div>';
        html += '</div>';
        html += '</div>';
        $('#bodyParking').append(html);
        count_parking++;
    }

    function removeParking(id) { $('#tr_parking_' + id).remove(); }

    /* ── Add / Remove Parkir ── */
    function addParkingEmoney() {
        var i = count_parking_emoney;
        var html = '<div class="entry-row" id="tr_parking_emoney_' + i + '">';
        html += '<div class="photo-upload-wrap">';
        html += '<div style="display:flex;align-items:center;justify-content:space-between;gap:10px;margin-bottom:10px;">';
        html += '<div class="photo-upload-label" style="margin-bottom:0;"><i class="fas fa-camera"></i> Foto Bukti Parkir <span style="color:#dc2626;">*</span></div>';
        html += '<button class="btn-remove" style="margin-right: 3px;" type="button" onclick="removeParkingEmoney(' + i + ')"><i class="fas fa-trash"></i></button>';
        html += '</div>';
        html += '<input type="file" name="file_parking_emoney_' + i + '" id="file_parking_emoney_' + i + '" accept="image/*" onchange="readURLParkingEmoney(this,' + i + ');">';
        html += '<img id="blah_parking_emoney_' + i + '" class="photo-preview-img" src="" alt="">';
        html += '<input type="hidden" name="json_parking_emoney_' + i + '" id="json_parking_emoney_' + i + '" value="">';
        html += '</div>';
        html += '<div id="div_parking_emoney_' + i + '" style="display: none;">';
        html += '<div style="display:grid;grid-template-columns:1fr auto;gap:10px;align-items:end;">';
        html += '<div class="ff" style="margin:0;"><label>Lokasi Parkir</label><input type="text" name="parking_emoney_at_' + i + '" id="parking_emoney_at_' + i + '" placeholder="Nama lokasi"></div>';
        html += '</div>';
        html += '<div class="ff" style="margin:0;"><label>Biaya (Rp) <span style="color:#dc2626;">*</span></label><input type="text" name="parking_emoney_' + i + '" id="parking_emoney_' + i + '" class="cost-input" placeholder="Nominal (min. 100)" inputmode="numeric" pattern="[0-9]*"></div>';
        html += '</div>';
        html += '</div>';
        $('#bodyParkingEmoney').append(html);
        count_parking_emoney++;
    }

    function removeParkingEmoney(id) { $('#tr_parking_emoney_' + id).remove(); }

    /* ── Submit ── */
    var etoll = [], etoll_from = [], etoll_to = [];
    var parking_arr = [], parking_at = [];
    var file_etoll = [], file_parking = [];
    var parking_emoney_arr = [], parking_emoney_at = [];
    var json_etoll = [];
    var json_parking = [];
    var json_parking_emoney = [];
    var file_parking_emoney = [];


    async function submitDriver() {

        $('#loading').addClass('show');

        etoll = [];
        etoll_from = [];
        etoll_to = [];

        parking_arr = [];
        parking_at = [];

        parking_emoney_arr = [];
        parking_emoney_at = [];

        file_etoll = [];
        file_parking = [];
        file_parking_emoney = [];

        json_etoll = [];
        json_parking = [];
        json_parking_emoney = [];

        /* ==========================
        VALIDASI ETOLL
        ========================== */
        for (var i = 0; i < count_etoll; i++) {

            var val = $('#etoll_' + i).val();
            var hasFile = $('#file_etoll_' + i).prop('files')[0] != undefined;

            if (val != '' && val != undefined) {

                var v = parseInt(val);

                if (isNaN(v) || v < 100) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'E-Toll harus berupa nominal minimal 100');
                    return false;
                }

                if (!hasFile) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Foto Bukti E-Toll');
                    return false;
                }

                etoll.push(v);
                etoll_from.push($('#from_' + i).val());
                etoll_to.push($('#to_' + i).val());
                file_etoll.push($('#blah_etoll_' + i).attr('src'));
                json_etoll.push($('#json_etoll_' + i).val());

                if($('#from_' + i).val() == '' || $('#to_' + i).val() == ''){
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Lokasi Awal dan Akhir E-Toll');
                    return false;
                }
            }
            else if (hasFile) {
                $('#loading').removeClass('show');
                openErrorGritter('Error!', 'Isikan Nominal E-Toll');
                return false;
            }
        }

        /* ==========================
        VALIDASI PARKING TUNAI
        ========================== */
        for (var i = 0; i < count_parking; i++) {

            var val = $('#parking_' + i).val();

            if (val != '' && val != undefined) {

                var v = parseInt(val);

                if (isNaN(v) || v < 100) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Parkir harus berupa nominal minimal 100');
                    return false;
                }

                if ($('#file_parking_' + i).prop('files')[0] == undefined) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Foto Bukti Parkir');
                    return false;
                }

                parking_arr.push(v);
                parking_at.push($('#parking_at_' + i).val());
                file_parking.push($('#blah_parking_' + i).attr('src'));
                json_parking.push($('#json_parking_' + i).val());

                if($('#parking_at_' + i).val() == ''){
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Lokasi Parkir');
                    return false;
                }
            }
        }

        /* ==========================
        VALIDASI PARKING EMONEY
        ========================== */
        for (var i = 0; i < count_parking_emoney; i++) {

            var val = $('#parking_emoney_' + i).val();

            if (val != '' && val != undefined) {

                var v = parseInt(val);

                if (isNaN(v) || v < 100) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Parkir E-Money harus berupa nominal minimal 100');
                    return false;
                }

                if ($('#file_parking_emoney_' + i).prop('files')[0] == undefined) {
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Foto Bukti Parkir E-Money');
                    return false;
                }

                parking_emoney_arr.push(v);
                parking_emoney_at.push($('#parking_emoney_at_' + i).val());
                file_parking_emoney.push($('#blah_parking_emoney_' + i).attr('src'));
                json_parking_emoney.push($('#json_parking_emoney_' + i).val());

                if($('#parking_emoney_at_' + i).val() == ''){
                    $('#loading').removeClass('show');
                    openErrorGritter('Error!', 'Isikan Lokasi Parkir E-Money');
                    return false;
                }
            }
        }

        try {

            /* =====================================
            SAVE ETOLL SATU PERSATU (SEQUENTIAL)
            ===================================== */
            for (let idx = 0; idx < etoll.length; idx++) {

                let result = await $.ajax({
                    type: 'POST',
                    url: '{{ url("input/additional/driver/job/etoll") }}',
                    data: {
                        id: $('#id').val(),
                        task_id: $('#task_id').val(),
                        etoll: etoll[idx],
                        etoll_from: etoll_from[idx],
                        etoll_to: etoll_to[idx],
                        file_etoll: file_etoll[idx],
                        json_etoll: json_etoll[idx],
                        index: idx
                    }
                });

                if (!result.status) {
                    throw new Error(result.message);
                }
            }

            /* =====================================
            SAVE PARKING TUNAI SATU PERSATU
            ===================================== */
            for (let idx = 0; idx < parking_arr.length; idx++) {

                let result = await $.ajax({
                    type: 'POST',
                    url: '{{ url("input/additional/driver/job/parking") }}',
                    data: {
                        id: $('#id').val(),
                        task_id: $('#task_id').val(),
                        parking: parking_arr[idx],
                        parking_at: parking_at[idx],
                        file_parking: file_parking[idx],
                        json_parking: json_parking[idx],
                        index: idx
                    }
                });

                if (!result.status) {
                    throw new Error(result.message);
                }
            }

            /* =====================================
            SAVE PARKING EMONEY SATU PERSATU
            ===================================== */
            for (let idx = 0; idx < parking_emoney_arr.length; idx++) {

                let result = await $.ajax({
                    type: 'POST',
                    url: '{{ url("input/additional/driver/job/parking_emoney") }}',
                    data: {
                        id: $('#id').val(),
                        task_id: $('#task_id').val(),
                        parking_emoney: parking_emoney_arr[idx],
                        parking_at_emoney: parking_emoney_at[idx],
                        file_parking_emoney: file_parking_emoney[idx],
                        json_parking_emoney: json_parking_emoney[idx],
                        index: idx
                    }
                });

                if (!result.status) {
                    throw new Error(result.message);
                }
            }

            /* =====================================
            CLOSE TASK DI AKHIR
            ===================================== */
            let finish = await $.ajax({
                type: 'POST',
                url: '{{ url("input/additional/driver/job") }}',
                data: {
                    id: $('#id').val(),
                    task_id: $('#task_id').val()
                }
            });

            if (!finish.status) {
                throw new Error(finish.message);
            }

            $('#loading').removeClass('show');

            $('#div_driver_1').hide();
            $('#div_driver_2').hide();
            $('#div_driver_3').show();

            openSuccessGritter(
                'Sukses',
                'Data berhasil dikirim (データの入力に成功しました)'
            );

        }
        catch (err) {

            $('#loading').removeClass('show');

            openErrorGritter(
                'Error!',
                err.message || 'Terjadi kesalahan saat menyimpan data'
            );
        }
    }

    /* ── Gritter ── */
    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success',
            image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger',
            image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection