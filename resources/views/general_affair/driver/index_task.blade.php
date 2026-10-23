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
    /* ── SECTION CARD ── */
    .sec-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .sec-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .sec-card-header .dot { width: 8px; height: 8px; border-radius: 50%; flex-shrink: 0; }
    .dot-purple { background: #605ca8; }
    .dot-yellow { background: #f59e0b; }
    .dot-blue   { background: #3b82f6; }
    .dot-green  { background: #22c55e; }
    .sec-card-body { padding: 20px 22px; }
    /* ── FORM FIELDS (ff) ── */
    .ff { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .ff:last-child { margin-bottom: 0; }
    .ff label {
        font-size: 11px; font-weight: 700; color: #4a5568;
        letter-spacing: .06em; text-transform: uppercase; margin: 0;
    }
    .ff label .req { color: #dc2626; margin-left: 2px; }
    .ff input, .ff select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
        -webkit-appearance: none; appearance: none;
    }
    .ff input:focus, .ff select:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] {
        background: #f0eef9 !important; color: #605ca8 !important;
        font-weight: 600 !important; border-color: #c4bfef !important; cursor: default;
    }
    .ff .hint { font-size: 11px; color: #a0aec0; margin-top: 1px; }
    /* ── GRID HELPERS ── */
    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
    .g3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 0 16px; }
    /* ── SELECT ARROW ── */
    .select-wrap { position: relative; }
    .select-wrap::after {
        content: '\f078'; font-family: 'Font Awesome 5 Free'; font-weight: 900;
        position: absolute; right: 14px; top: 50%; transform: translateY(-50%);
        color: #a0aec0; font-size: 11px; pointer-events: none;
    }
    .select-wrap select { padding-right: 36px !important; }
    /* ── PHOTO CARD ── */
    .photo-card {
        border: 1.5px dashed #c4bfef; border-radius: 12px;
        padding: 16px; background: #faf9ff; margin-bottom: 14px;
        transition: border-color .18s, background .18s;
    }
    .photo-card:last-child { margin-bottom: 0; }
    .photo-card:hover { border-color: #605ca8; background: #f5f3ff; }
    .photo-label {
        font-size: 11px; font-weight: 700; color: #605ca8;
        letter-spacing: .06em; text-transform: uppercase; margin-bottom: 10px; display: block;
    }
    .photo-label .req { color: #dc2626; }
    .photo-card input[type="file"] {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 7px 10px !important; font-size: 12.5px !important;
        background: #fff !important; color: #4a5568 !important;
        width: 100% !important; cursor: pointer;
    }
    .photo-preview { display: none; margin-top: 12px; }
    .photo-preview img {
        width: 100%; max-height: 220px; object-fit: cover;
        border-radius: 10px; border: 2px solid #ede9fe; display: block;
    }
/* ── BUTTONS ── */
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
        background: #dc2626; border: 1.5px solid #ef4444;
        color: #fff; font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: all .18s;
    }
    .btn-back-bottom:hover { background: #b91c1c; border-color: #991b1b; color: #fff; text-decoration: none; }
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
    /* ── COMPACT SINGLE-SCREEN LAYOUT ── */
    .content-header { padding: 0 12px !important; }
    .page-header-modern { padding: 12px 18px; margin: 8px 0 10px; border-radius: 12px; gap: 8px; }
    .header-left h1 { font-size: 19px !important; }
    .header-left p { font-size: 11px; }
    .header-left .badge-tag { margin-bottom: 4px; padding: 3px 9px; font-size: 9px; }
    .btn-header-back { padding: 7px 12px; font-size: 11px; }
    .sec-card { margin-bottom: 8px; border-radius: 10px; }
    .sec-card-header { padding: 8px 12px; font-size: 12px; }
    .sec-card-body { padding: 10px 12px; }
    .ff { gap: 3px; margin-bottom: 7px; min-width: 0; }
    .ff label { font-size: 9px; }
    .ff input, .ff select { padding: 6px 9px !important; font-size: 12px !important; min-height: 32px; }
    .g2 { grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0 10px; }
    .g3 { grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 0 10px; }
    .compact-grid { display: grid; grid-template-columns: repeat(4, minmax(0, 1fr)); gap: 0 10px; }
    .required-field input, .required-field select, .required-field input[type="file"] { border: 2px solid #f59e0b !important; background: #fffbeb !important; }
    .required-field input:focus, .required-field select:focus { border-color: #d97706 !important; box-shadow: 0 0 0 2px rgba(245,158,11,.16) !important; }
    .required-field .photo-label { color: #92400e; }
    .photo-grid { display: grid; grid-template-columns: repeat(3, minmax(0, 1fr)); gap: 8px; }
    .photo-card { margin: 0; padding: 8px; border-radius: 9px; min-width: 0; }
    .photo-label { font-size: 9px; margin-bottom: 5px; line-height: 1.35; }
    .photo-card input[type="file"] { padding: 5px !important; font-size: 10px !important; }
    .photo-preview img { max-height: 160px; object-fit: contain; }
    .preview-accordion { margin-top: 5px; font-size: 11px; }
    .preview-accordion summary, .info-accordion summary { cursor: pointer; color: #605ca8; font-weight: 700; padding: 4px 0; }
    .info-accordion { background: #fff; border: 1px solid #e5e7eb; border-radius: 10px; margin-bottom: 8px; }
    .info-accordion summary { padding: 8px 12px; font-size: 12px; list-style-position: inside; }
    .info-accordion .sec-card-body { padding-top: 4px; }
    .location-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 8px; align-items: end; }
    .location-status { margin: 0 0 7px; font-size: 10px; }
    .btn-submit { padding: 10px; font-size: 13px; border-radius: 9px; }
    .action-wrap { margin: 8px 0 12px !important; }
    @media (max-width: 900px) { .g2, .compact-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); } }
    @media (max-width: 600px) {
        .content-header { padding: 0 7px !important; }
        .page-header-modern { padding: 10px 12px; margin: 6px 0 8px; }
        .header-left h1 { font-size: 16px !important; }
        .g2, .g3, .compact-grid { grid-template-columns: repeat(2, minmax(0, 1fr)); gap: 0 8px; }
        .photo-grid { grid-template-columns: 1fr; }
        .photo-card { padding: 7px; }
        .photo-preview img { max-height: 130px; }
        .sec-card-body { padding: 8px; }
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
{{-- Hidden Fields --}}
<input type="hidden" id="id"        value="{{ $id }}">
<input type="hidden" id="latitude"  name="latitude">
<input type="hidden" id="longitude" name="longitude">
<div class="content-header" style="padding: 0 20px;">
    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-gas-pump"></i> Pengisian BBM</div>
            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }}</p>
        </div>
        <div>
            <a class="btn-header-back" href="{{ url('index/driver/job') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>
    {{-- ── INFORMASI KENDARAAN ── --}}
    <details class="info-accordion">
        <summary><i class="fas fa-car"></i> Informasi Kendaraan (buka jika diperlukan)</summary>
        <div class="sec-card-body">
            <div class="g2">
                <div class="ff">
                    <label>Driver</label>
                    <input type="text" id="driver" name="driver" readonly
                           value="{{ $driver_task->driver_id }} - {{ $driver_task->driver_name }}">
                </div>
                <div class="ff">
                    <label>Kendaraan</label>
                    <input type="text" id="vehicle" name="vehicle" readonly
                           value="{{ $driver_task->car }} - {{ $driver_task->plat_no }}">
                </div>
                <div class="ff">
                    <label>Destinasi</label>
                    <input type="text" id="location" name="location" readonly
                           value="{{ $driver_task->destination }}">
                </div>
                <div class="ff">
                    <label>Tanggal Pengisian</label>
                    <input type="text" id="date" name="date" readonly value="{{ date('Y-m-d') }}">
                </div>
            </div>
            <div class="ff" style="margin-bottom:0;">
                <label>Jam Pengisian</label>
                <div style="display:flex; gap:12px;">
                    <input type="number" id="hour"   name="hour"
                           placeholder="Jam" value="{{ date('H') }}" style="flex:1;">
                    <input type="number" id="minute" name="minute"
                           placeholder="Menit" value="{{ str_pad(date('i'), 2, '0', STR_PAD_LEFT) }}" style="flex:1;">
                </div>
            </div>
        </div>
    </details>
    {{-- ── LOKASI GPS ── --}}
    <div class="sec-card" style="display: none;">
        <div class="sec-card-header"><span class="dot dot-blue"></span> Lokasi GPS</div>
        <div class="sec-card-body">
            <div class="location-status"><span id="locText">Mendapatkan lokasi...</span></div>
            <div class="location-grid">
                <div class="ff" style="margin-bottom:0;"><label>Latitude</label><input type="text" id="latitudeDisplay" readonly placeholder="Menunggu GPS"></div>
                <div class="ff" style="margin-bottom:0;"><label>Longitude</label><input type="text" id="longitudeDisplay" readonly placeholder="Menunggu GPS"></div>
            </div>
        </div>
    </div>
    {{-- ── ODOMETER & BBM ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <span class="dot dot-yellow"></span> Odometer &amp; Pengisian BBM
        </div>
        <div class="sec-card-body">
            <div class="compact-grid">
            <div class="ff">
                <label>Odometer Akhir (KM) <span class="req">*</span></label>
                    <input type="number" id="odometer" name="odometer"
                           value="{{$driver_task->odometer}}" placeholder="Odometer (KM)">
            </div>
            <input type="hidden" id="fuel_actual" name="fuel_actual" value="0">
            <div class="ff">
                <label>Jenis BBM <span class="req">*</span></label>
                <div class="select-wrap">
                    <select id="fuel_type" name="fuel_type" onchange="changeFuelType(this.value)">
                        <option value="-">Pilih Jenis BBM</option>
                        @foreach($bbm as $bbms)
                        @if($driver_task->fuel_type == explode('_',$bbms)[0])
                            <option value="{{ explode('_',$bbms)[0] }}" selected>{{ explode('_',$bbms)[0] }}</option>
                        @else
                            <option value="{{ explode('_',$bbms)[0] }}">{{ explode('_',$bbms)[0] }}</option>
                        @endif
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="ff required-field">
                <label>Harga pada Nota <span class="req">*</span></label>
                <input type="number" id="receipt" name="receipt" class="numpad"
                           placeholder="Harga pada Nota" style="border: 2px solid orange !important; background: #fffbeb !important;" value="" onchange="changeReceipt(this.value)">
            </div>
            <div class="ff" style="">
                <label>Harga Per Liter <span class="req">*</span></label>
                <input type="number" id="fuel_amount_liter" name="fuel_amount_liter"
                        placeholder="Harga per liter" value="">
                <input type="hidden" id="fuel_amount" name="fuel_amount" value="">
            </div>
            <div class="ff">
                <label>Pengisian BBM (Liter) <span class="req">*</span></label>
                <input type="text" id="fuel" name="fuel" placeholder="Jumlah liter" value="" inputmode="decimal" pattern="[0-9.]*">
            </div>
            </div>
        </div>
    </div>
    {{-- ── FOTO DOKUMENTASI ── --}}
    <div class="sec-card">
        <div class="sec-card-header">
            <span class="dot dot-green"></span> Foto Dokumentasi
        </div>
        <div class="sec-card-body">
            <div class="photo-grid">
            {{-- Nota --}}
            <div class="photo-card required-field">
                <span class="photo-label">
                    <i class="fas fa-receipt" style="margin-right:5px;"></i>
                    Foto Nota Pengisian <span class="req">*</span>
                </span>
                <input type="file" id="fileData" name="fileData"  onchange="readURL(this);">
                <details class="preview-accordion">
                    <summary>Preview foto nota</summary>
                    <div class="photo-preview" id="previewNota"><img id="blah" src="" alt="Preview Nota"></div>
                </details>
            </div>
            {{-- Odometer Before --}}
            <div class="photo-card required-field">
                <span class="photo-label">
                    <i class="fas fa-tachometer-alt" style="margin-right:5px;"></i>
                    Foto Odometer &amp; Indikator <strong>Sebelum</strong> Pengisian <span class="req">*</span>
                </span>
                <input type="file" id="fileDataOdoBefore" name="fileDataOdoBefore"
                        onchange="readURLOdoBefore(this);">
                <details class="preview-accordion">
                    <summary>Preview foto sebelum</summary>
                    <div class="photo-preview" id="previewOdoBefore"><img id="blahOdoBefore" src="" alt="Preview Odo Before"></div>
                </details>
            </div>
            {{-- Odometer After --}}
            <div class="photo-card required-field">
                <span class="photo-label">
                    <i class="fas fa-tachometer-alt" style="margin-right:5px;"></i>
                    Foto Odometer &amp; Indikator <strong>Setelah</strong> Pengisian <span class="req">*</span>
                </span>
                <input type="file" id="fileDataOdoAfter" name="fileDataOdoAfter"
                        onchange="readURLOdoAfter(this);">
                <details class="preview-accordion">
                    <summary>Preview foto setelah</summary>
                    <div class="photo-preview" id="previewOdoAfter"><img id="blahOdoAfter" src="" alt="Preview Odo After"></div>
                </details>
            </div>
            </div>
        </div>
    </div>
    {{-- ── ACTION BUTTONS ── --}}
    <div class="action-wrap">
        <button class="btn-submit" onclick="submitDriver();">
            <i class="fas fa-paper-plane"></i> Submit Pengisian BBM
        </button>
    </div>
</div>
@endsection
@section('scripts')
<script src="{{ url("js/jquery.numpad.js") }}"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });
    /* ── Numpad config ── */
    var isSmall = screen.width < 400;
    var btnW  = isSmall ? '62px'  : '76px';
    var btnH  = isSmall ? '54px'  : '66px';
    var btnFS = isSmall ? '20px'  : '24px';
    var dispH = isSmall ? '70px'  : '86px';
    var dispFS= isSmall ? '30px'  : '38px';
    var padW  = isSmall ? '220px' : '270px';
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
        var $table = $(this);
        if ($table.find('.numpad-title').length === 0) {
            $table.prepend('<tr class="numpad-title"><td colspan="3" style="padding:0; border:none;"></td></tr>');
        }
        $table.find('.done').css({
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
        $table.find('.cancel').css({
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
        $table.find('.clear').css({
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
        $table.find('.del').css({
            'background':    '#f1f5f9',
            'color':         '#475569',
            'border':        '1.5px solid #e2e8f0',
            'width':         btnW,
            'height':        btnH,
            'border-radius': '12px',
            'font-size':     '18px',
            'font-family':   'Plus Jakarta Sans,sans-serif'
        });
        $table.find('.sep').css({
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
        $table.find('button').not('.done,.cancel,.clear,.del,.sep').on('mouseenter', function(){
            $(this).css({'background':'#ede9fe','border-color':'#605ca8','color':'#4a4690'});
        }).on('mouseleave', function(){
            $(this).css({'background':'#fff','border-color':'#e2e8f0','color':'#2d3748'});
        });
    };
    /* ── Geolocation ── */
    function getLocation() {
        if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition, function() {
                $('#locText').text('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.');
            });
        } else {
            $('#locText').text('Browser tidak mendukung geolokasi.');
        }
    }
    function showPosition(position) {
        var lat = position.coords.latitude;
        var lng = position.coords.longitude;
        $('#latitude').val(lat);
        $('#longitude').val(lng);
        $('#latitudeDisplay').val(lat.toFixed(6));
        $('#longitudeDisplay').val(lng.toFixed(6));
        $('#locText').text('Lokasi GPS berhasil diperoleh.');
    }
    /* ── Image compress helper ── */
    function compressAndPreview(input, imgId, previewId) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = new Image();
                img.onload = function () {
                    var canvas = document.createElement('canvas');
                    canvas.width  = img.width;
                    canvas.height = img.height;
                    canvas.getContext('2d').drawImage(img, 0, 0);
                    var compressed = canvas.toDataURL('image/jpeg', 0.5);
                    $('#' + imgId).attr('src', compressed);
                    $('#' + previewId).show();
                };
                img.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
    function readURL(input)          { compressAndPreview(input, 'blah',         'previewNota'); }
    function readURLOdoBefore(input) { compressAndPreview(input, 'blahOdoBefore','previewOdoBefore'); }
    function readURLOdoAfter(input)  { compressAndPreview(input, 'blahOdoAfter', 'previewOdoAfter'); }
    /* ── BBM helpers ── */
    var bbm = <?php echo json_encode($bbm2); ?>;
    function changeFuelType(fuel_type) {
        var harga = 0;
        for (var i = 0; i < bbm.length; i++) {
            if (fuel_type == bbm[i].split('_')[0]) { harga = bbm[i].split('_')[1]; }
        }
        $('#fuel_amount_liter').val(harga);
        // $('#fuel_amount').val(parseFloat(harga) * parseFloat($('#fuel').val()));
        $('#receipt').val('');
        $('#fuel_amount').val('');
        $('#fuel').val('');
    }
    // function changeLiter(amountLiter) {
    //     if ($('#fuel').val() == '') {
    //         openErrorGritter('Error!', 'Isikan Pengisian BBM (Liter) terlebih dahulu');
    //         $('#fuel_amount_liter').val('');
    //         $('#fuel_amount').val('');
    //         return false;
    //     }
    //     $('#fuel_amount').val(parseFloat($('#fuel').val()) * parseInt(amountLiter));
    //     $('#receipt').val(parseFloat($('#fuel').val()) * parseInt(amountLiter));
    // }
    function changeReceipt(receipt) {
        if ($('#fuel_amount_liter').val() == '') {
            openErrorGritter('Error!', 'Isikan Harga per Liter terlebih dahulu / pilih Jenis BBM');
            $('#receipt').val('');
            return false;
        }
        $('#fuel_amount').val(parseFloat(receipt));
        $('#fuel').val((parseFloat(receipt) / parseFloat($('#fuel_amount_liter').val())).toFixed(2));
    }
    /* ── Submit ── */
    function submitDriver() {
        if (!$('#latitude').val()) {
            openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
            getLocation(); return false;
        }
        if (!$('#longitude').val()) {
            openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
            getLocation(); return false;
        }
        if ($('#fuel').val() == '' || $('#fuel_type').val() == '-' ||
            $('#odometer').val() == '' || $('#fuel_amount').val() == '' ||
            $('#fuel_amount_liter').val() == '' || $('#receipt').val() == '') {
            openErrorGritter('Error!', 'Isikan BBM dan Odometer');
            return false;
        }
        if ($('#fileData').prop('files')[0] == undefined) {
            openErrorGritter('Error!', 'Isikan Foto Nota Pengisian');
            return false;
        }
        if ($('#fileDataOdoBefore').prop('files')[0] == undefined) {
            openErrorGritter('Error!', 'Isikan Foto Odometer Sebelum Pengisian');
            return false;
        }
        if ($('#fileDataOdoAfter').prop('files')[0] == undefined) {
            openErrorGritter('Error!', 'Isikan Foto Odometer Setelah Pengisian');
            return false;
        }
        $('#loading').addClass('show');
        var data = {
            latitude          : $('#latitude').val(),
            longitude         : $('#longitude').val(),
            fuel              : $('#fuel').val(),
            fuel_actual       : $('#fuel_actual').val(),
            fuel_type         : $('#fuel_type').val(),
            times             : $('#hour').val() + ':' + $('#minute').val(),
            fuel_amount       : $('#fuel_amount').val(),
            fuel_amount_liter : $('#fuel_amount_liter').val(),
            receipt           : $('#receipt').val(),
            location          : $('#location').val(),
            odometer          : $('#odometer').val(),
            id                : '{{ $id }}'
        };
        $.post('{{ url("input/driver/job_new") }}', data, function (result) {
            if (result.status) {
                openSuccessGritter('Sukses', 'Berhasil kerjakan tugas');
                saveImage1();
                saveImage2();
                saveImage3();
            } else {
                openErrorGritter('Error!', result.message);
                $('#loading').removeClass('show');
            }
        });
    }
    function saveImage1() {
        $('#loading').addClass('show');
        $.post('{{ url("input/driver/job_image1") }}',
            { fileData: $('#blah').attr('src'), id: '{{ $id }}' },
            function (result) {
                $('#loading').removeClass('show');
                if (result.status) {
                    openSuccessGritter('Sukses', 'Foto nota tersimpan');
                    $('#div_vehicle').hide();
                } else {
                    openErrorGritter('Error!', result.message);
                }
            }
        );
    }
    function saveImage2() {
        $('#loading').addClass('show');
        $.post('{{ url("input/driver/job_image2") }}',
            { fileDataOdoBefore: $('#blahOdoBefore').attr('src'), id: '{{ $id }}' },
            function (result) {
                $('#loading').removeClass('show');
                if (result.status) {
                    openSuccessGritter('Sukses', 'Foto odometer (sebelum) tersimpan');
                    $('#div_vehicle').hide();
                } else {
                    openErrorGritter('Error!', result.message);
                }
            }
        );
    }
    function saveImage3() {
        $('#loading').addClass('show');
        $.post('{{ url("input/driver/job_image3") }}',
            { fileDataOdoAfter: $('#blahOdoAfter').attr('src'), id: '{{ $id }}' },
            function (result) {
                $('#loading').removeClass('show');
                if (result.status) {
                    openSuccessGritter('Sukses', 'Foto odometer (setelah) tersimpan');
                    $('#div_vehicle').hide();
                } else {
                    openErrorGritter('Error!', result.message);
                }
            }
        );
    }
    /* ── Ready ── */
    $(document).ready(function () {
        $('body').toggleClass('sidebar-collapse');
        $('#side_vfi').addClass('menu-open');
        $('.numpad').numpad({ hidePlusMinusButton: true, decimalSeparator: '.' });
        $('.select2').select2({ allowClear: true });
        getLocation();

        setTimeout(function () {
            changeFuelType($('#fuel_type').val());
        }, 2000);
    });
    /* ── Utilities ── */
    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    function addZero(i) { return i < 10 ? '0' + i : i; }
    function getActualFullDate() {
        var d = new Date(), z = addZero;
        return z(d.getFullYear()) + '-' + z(d.getMonth()+1) + '-' + z(d.getDate())
             + ' ' + z(d.getHours()) + ':' + z(d.getMinutes()) + ':' + z(d.getSeconds());
    }
    function openSuccessGritter(title, message) {
        jQuery.gritter.add({
            title: title, text: message, class_name: 'growl-success',
            image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000'
        });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({
            title: title, text: message, class_name: 'growl-danger',
            image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000'
        });
    }
</script>
@endsection
