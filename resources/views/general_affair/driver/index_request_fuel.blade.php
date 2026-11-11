@extends('layouts.master')
@section('title', 'VFI')
@section('styles')
    <link href="{{ url('css/jquery.numpad.css') }}" rel="stylesheet">
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap"
        rel="stylesheet">
    <style>
        body {
            background: #f0f2f7 !important;
        }
        body,
        body input,
        body button,
        body label,
        body p,
        body span,
        body div,
        body a,
        body td,
        body th {
            font-family: 'Plus Jakarta Sans', sans-serif;
        }
        .page-wrapper {
            padding-top: 0 !important;
        }
        .content-header {
            padding: 0 20px;
        }
        .page-header-modern {
            background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
            padding: 28px 36px 24px;
            margin: 24px 0;
            border-radius: 18px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            flex-wrap: wrap;
            gap: 16px;
            color: #fff;
        }
        .badge-tag {
            display: inline-flex;
            gap: 6px;
            align-items: center;
            padding: 5px 14px;
            border-radius: 20px;
            background: rgba(255, 255, 255, .14);
            color: #e0ddff;
            font-size: 11px;
            font-weight: 700;
            letter-spacing: 1px;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .header-left h1 {
            color: #fff !important;
            font-size: 24px !important;
            margin: 0 0 4px !important;
        }
        .header-left p {
            color: rgba(255, 255, 255, .7);
            font-size: 13px;
            margin: 0;
        }
        .btn-header-back {
            display: inline-flex;
            gap: 7px;
            align-items: center;
            padding: 10px 20px;
            border-radius: 10px;
            background: #dc2626;
            color: #fff;
            text-decoration: none;
            font-size: 13px;
            font-weight: 600;
        }
        .sec-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
            border: 1px solid rgba(0, 0, 0, .05);
            overflow: hidden;
            margin-bottom: 20px;
        }
        .sec-card-header {
            padding: 14px 22px;
            border-bottom: 1px solid #f0f2f7;
            background: #fafbff;
            display: flex;
            align-items: center;
            gap: 10px;
            font-size: 14px;
            font-weight: 700;
            color: #1a202c;
        }
        .dot {
            width: 8px;
            height: 8px;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .dot-purple {
            background: #605ca8;
        }
        .dot-yellow {
            background: #f59e0b;
        }
        .dot-blue {
            background: #3b82f6;
        }
        .dot-green {
            background: #22c55e;
        }
        .sec-card-body {
            padding: 20px 22px;
        }
        .g2 {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 20px;
        }
        .vehicle-info-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 16px 20px;
        }
        .vehicle-info-grid .ff {
            margin-bottom: 0;
        }
        .g3 {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 0 16px;
        }
        .ff {
            display: flex;
            flex-direction: column;
            gap: 6px;
            margin-bottom: 16px;
            min-width: 0;
        }
        .ff:last-child {
            margin-bottom: 0;
        }
        .ff label {
            font-size: 11px;
            font-weight: 700;
            color: #4a5568;
            letter-spacing: .06em;
            text-transform: uppercase;
            margin: 0;
        }
        .req {
            color: #dc2626;
        }
        .ff input,
        .ff select {
            border: 1.5px solid #e2e8f0 !important;
            border-radius: 9px !important;
            padding: 10px 13px !important;
            font-size: 13.5px !important;
            color: #1a202c !important;
            background: #fafbff !important;
            outline: none !important;
            width: 100% !important;
            box-sizing: border-box;
            min-height: 40px;
        }
        .ff input:focus {
            border-color: #605ca8 !important;
            background: #fff !important;
        }
        .ff input[readonly] {
            background: #f0eef9 !important;
            color: #514b9b !important;
            font-weight: 700 !important;
        }
        .hint {
            font-size: 11px;
            color: #718096;
            line-height: 1.5;
        }
        .result-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 12px;
        }
        .voucher-related.is-hidden {
            display: none !important;
        }
        .result-item {
            padding: 14px;
            border: 1px solid #e8e7f5;
            background: #faf9ff;
            border-radius: 12px;
            min-width: 0;
        }
        .result-item .result-label {
            display: block;
            font-size: 10px;
            color: #718096;
            text-transform: uppercase;
            font-weight: 700;
            letter-spacing: .04em;
            margin-bottom: 7px;
        }
        .result-item .result-value {
            display: block;
            font-size: 18px;
            color: #342f70;
            font-weight: 800;
            overflow-wrap: anywhere;
        }
        .result-item small {
            display: block;
            color: #718096;
            font-size: 10px;
            margin-top: 5px;
        }
        .calculation-note {
            background: #f8fafc;
            border-left: 3px solid #605ca8;
            border-radius: 7px;
            padding: 12px 14px;
            color: #4a5568;
            font-size: 12px;
            line-height: 1.7;
            margin-top: 14px;
        }
        .photo-card {
            border: 1.5px dashed #c4bfef;
            border-radius: 12px;
            padding: 16px;
            background: #faf9ff;
            margin-bottom: 14px;
        }
        .photo-label {
            display: block;
            font-size: 11px;
            font-weight: 700;
            color: #605ca8;
            letter-spacing: .04em;
            text-transform: uppercase;
            margin-bottom: 10px;
        }
        .photo-card input[type=file] {
            width: 100%;
            box-sizing: border-box;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px;
            background: #fff;
        }
        .photo-preview {
            display: none;
            margin-top: 12px;
        }
        .photo-preview img {
            width: 100%;
            max-height: 220px;
            object-fit: contain;
            border-radius: 10px;
            border: 2px solid #ede9fe;
        }
        .btn-submit {
            display: flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            width: 100%;
            padding: 14px;
            border: 0;
            border-radius: 12px;
            background: linear-gradient(135deg, #15803d, #16a34a);
            color: #fff;
            font-size: 15px;
            font-weight: 700;
            cursor: pointer;
        }
        .btn-submit:disabled {
            opacity: .6;
            cursor: not-allowed;
        }
        #loading {
            display: none;
            position: fixed;
            inset: 0;
            background: rgba(30, 31, 58, .42);
            z-index: 30001;
            align-items: center;
            justify-content: center;
        }
        #loading.show {
            display: flex;
        }
        .loading-box {
            background: #fff;
            border-radius: 20px;
            padding: 30px 40px;
            display: flex;
            flex-direction: column;
            align-items: center;
            gap: 14px;
        }
        .loading-spinner {
            width: 42px;
            height: 42px;
            border: 3px solid #ede9fe;
            border-top-color: #605ca8;
            border-radius: 50%;
            animation: spin .75s linear infinite;
        }
        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }
        @media(max-width:767px) {
            .content-header {
                padding: 0 10px;
            }
            .page-header-modern {
                padding: 22px 20px;
            }
            .g2,
            .g3,
            .result-grid {
                grid-template-columns: 1fr;
            }
            .vehicle-info-grid {
                grid-template-columns: 1fr;
                gap: 14px;
            }
            .sec-card-body {
                padding: 16px;
            }
            .result-item .result-value {
                font-size: 17px;
            }
        }
        .ui-accordion-toggle{width:100%;display:flex;align-items:center;justify-content:space-between;gap:10px;padding:10px 12px;margin:8px 0;border:1px solid #cbd5e1;border-radius:10px;background:#f8fafc;color:#1e293b;font-weight:700;font-size:13px;text-align:left;cursor:pointer}
        .ui-accordion-toggle .accordion-chevron{transition:transform .2s ease;flex-shrink:0}
        .ui-accordion-toggle[aria-expanded="true"] .accordion-chevron{transform:rotate(180deg)}
        .ui-accordion-panel{display:none;padding-top:8px}
        .ui-accordion-panel.is-open{display:block}
        .photo-preview{display:none}
        .photo-preview\.is-open{display:block}
        .required-field input:not([type=file]),.required-field select,#fileDataOdoBefore{border:2px solid #2563eb!important;background:#eff6ff!important;border-radius:8px!important}
        .readonly-field input{background:#e5e7eb!important;border:1px solid #9ca3af!important;color:#374151!important}
        @media(max-width:767px){
            .page-header-modern{padding:10px 12px!important;margin-bottom:10px!important}
            .page-header-modern h1{font-size:18px!important;margin:0!important}
            .sec-card{margin-bottom:10px!important;border-radius:10px!important}
            .sec-card-header{padding:9px 12px!important;font-size:13px!important}
            .sec-card-body{padding:10px!important}
            .g2{gap:8px!important}
            .ff{margin-bottom:9px!important}
            .ff label{font-size:12px!important;margin-bottom:4px!important}
            .hint{font-size:10px!important;line-height:1.25!important}
            .ui-accordion-toggle{padding:8px 10px;margin:5px 0;font-size:12px}
            .result-grid{gap:7px!important;grid-template-columns:repeat(2,minmax(0,1fr))!important}
            .result-item{padding:8px!important}
            .result-item .result-value{font-size:14px!important}
            .btn-submit{padding:11px!important;margin-top:4px!important}
        }
        /* Tampilan ringkas khusus mobile */
        .photo-preview\.is-open { display:block !important; }
        .photo-preview:not(.is-open) { display:none !important; }
        @media (max-width:767px) {
            .content-header { padding:0 8px!important; }
            .page-header-modern { padding:9px 11px!important; margin:7px 0 9px!important; border-radius:9px!important; gap:7px!important; }
            .badge-tag { font-size:9px!important; padding:3px 7px!important; margin-bottom:4px!important; }
            .header-left h1 { font-size:16px!important; line-height:1.2!important; }
            .header-left p { font-size:10px!important; }
            .btn-header-back { padding:7px 9px!important; font-size:11px!important; }
            .sec-card { margin-bottom:8px!important; border-radius:9px!important; }
            .sec-card-header { padding:7px 10px!important; font-size:12px!important; }
            .sec-card-body { padding:8px!important; }
            .vehicle-info-grid,.g2 { grid-template-columns:repeat(2,minmax(0,1fr))!important; gap:6px 8px!important; }
            .ff { gap:3px!important; margin-bottom:5px!important; }
            .ff label { font-size:10px!important; letter-spacing:0!important; }
            .ff input,.ff select { min-height:34px!important; padding:6px 8px!important; font-size:12px!important; }
            .hint { font-size:9px!important; line-height:1.2!important; }
            .photo-card { padding:8px!important; margin-bottom:7px!important; }
            .photo-label { font-size:10px!important; margin-bottom:5px!important; }
            .photo-card input[type=file] { padding:5px!important; font-size:11px!important; }
            .ui-accordion-toggle { padding:7px 8px!important; margin:4px 0!important; font-size:11px!important; }
            .result-grid { grid-template-columns:repeat(2,minmax(0,1fr))!important; gap:6px!important; }
            .result-item { padding:7px!important; }
            .result-item .result-label { font-size:9px!important; margin-bottom:3px!important; }
            .result-item .result-value { font-size:13px!important; }
            .result-item small { font-size:9px!important; }
            .calculation-note { padding:7px!important; font-size:10px!important; margin-top:7px!important; }
            .btn-submit { padding:10px!important; font-size:13px!important; }
            .photo-preview img { max-height:160px!important; }
        }

        /* Ringkasan tangki dan efisiensi 3 bulan */
        .fuel-visual-card{display:none;margin-top:10px;padding:12px;border:1px solid #dbe4ee;border-radius:12px;background:linear-gradient(135deg,#f8fbff,#fff)}
        .fuel-visual-card.is-visible{display:block}
        .fuel-visual-layout{display:grid;grid-template-columns:112px minmax(0,1fr);gap:14px;align-items:center}
        .tank-summary{text-align:center}
        .tank-total{font-size:11px;font-weight:800;color:#1e293b;margin-bottom:5px}
        .tank-shell{width:76px;height:142px;margin:0 auto;border:3px solid #38536b;border-radius:5px;padding:3px;background:#fff;display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden}
        .tank-fill{width:100%;display:flex;flex-direction:column;justify-content:flex-end;overflow:hidden}
        .tank-part{display:flex;align-items:center;justify-content:center;color:#fff;font-size:10px;font-weight:800;min-height:22px;transition:height .25s ease}
        .tank-to-buy{background:#f36c2c}
        .tank-remaining{background:#1f6f8b}
        .tank-legend{margin-top:6px;font-size:10px;font-weight:800;line-height:1.45}
        .tank-legend .fill-text{color:#e84f18}
        .tank-legend .remain-text{color:#006ba6}
        .tank-odo{margin-top:6px;font-size:10px;font-weight:800;color:#334155}
        .efficiency-box{min-width:0}
        .efficiency-title{font-size:12px;font-weight:800;color:#1e293b;margin-bottom:7px}
        .efficiency-main{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:7px}
        .efficiency-item{padding:8px;border-radius:9px;background:#fff;border:1px solid #e2e8f0}
        .efficiency-item span{display:block;font-size:9px;text-transform:uppercase;font-weight:700;color:#64748b;margin-bottom:3px}
        .efficiency-item strong{display:block;font-size:13px;color:#312e70;overflow-wrap:anywhere}
        .efficiency-status{display:inline-flex;margin-top:7px;padding:4px 8px;border-radius:999px;font-size:10px;font-weight:800;background:#e2e8f0;color:#475569}
        .efficiency-status.good{background:#dcfce7;color:#166534}.efficiency-status.watch{background:#fef3c7;color:#92400e}.efficiency-status.bad{background:#fee2e2;color:#991b1b}
        .history-list{display:grid;gap:6px;margin-top:7px}.history-row{display:grid;grid-template-columns:1fr auto auto auto;gap:7px;align-items:center;padding:7px 8px;background:#fff;border:1px solid #e2e8f0;border-radius:8px;font-size:10px}.history-row strong{color:#312e70}
        .main-accordion>.sec-card-body{padding-top:8px}.compact-page .sec-card{margin-bottom:8px}.compact-page .page-header-modern{margin:8px 0 10px;padding:12px 18px}.compact-page .sec-card-header{padding:9px 14px}.compact-page .sec-card-body{padding:9px 14px}
        @media(max-width:767px){.fuel-visual-layout{grid-template-columns:100px minmax(0,1fr);gap:8px}.tank-shell{width:66px;height:126px}.efficiency-main{gap:5px}.efficiency-item{padding:6px}.efficiency-item strong{font-size:11px}.history-row{grid-template-columns:1fr 1fr;gap:4px}.compact-page .page-header-modern{padding:8px 10px}.compact-page .sec-card-body{padding:7px 9px}}

        /* Layout terbaru: input dan simulasi selalu tampil */
        .always-visible-section .sec-card-body{display:block!important}
        .input-divider{height:1px;background:#e2e8f0;margin:8px 0 10px}
        .simulation-empty{min-height:118px;display:flex;flex-direction:column;align-items:center;justify-content:center;gap:5px;text-align:center;border:1px dashed #cbd5e1;border-radius:11px;background:#f8fafc;color:#64748b;padding:12px}
        .simulation-empty i{font-size:22px;color:#94a3b8}.simulation-empty strong{font-size:13px;color:#334155}.simulation-empty span{font-size:10px}
        .compact-empty{min-height:86px}
        .simulation-card{display:none!important;margin:0!important;padding:0!important;border:0!important;background:transparent!important}
        .simulation-card.is-visible{display:block!important}
        .simulation-layout{display:grid;grid-template-columns:118px minmax(0,1fr);gap:10px;align-items:stretch}
        .simulation-results{min-width:0;display:flex;flex-direction:column;justify-content:center}
        .simulation-title{font-size:12px;font-weight:800;color:#1e293b;margin-bottom:6px}
        .simulation-result-grid{display:grid;grid-template-columns:repeat(2,minmax(0,1fr));gap:6px}
        .simulation-result-item{padding:8px;border:1px solid #e1e6f0;border-radius:10px;background:#f8f7ff;min-height:58px;display:flex;flex-direction:column;justify-content:center}
        .simulation-result-item span{font-size:9px;font-weight:700;text-transform:uppercase;color:#64748b;margin-bottom:4px}
        .simulation-result-item strong{font-size:13px;color:#312e70;overflow-wrap:anywhere}
        .simulation-result-item.summary-wide{grid-column:1/-1;min-height:52px}
        #efficiencyPanel{padding-top:7px}
        #efficiencyContent .efficiency-main{margin-top:0}
        @media(max-width:767px){
            .simulation-layout{grid-template-columns:105px minmax(0,1fr);gap:7px}
            .simulation-result-grid{gap:5px}.simulation-result-item{padding:6px;min-height:52px}.simulation-result-item strong{font-size:11px}
            .simulation-title{font-size:11px}.simulation-empty{min-height:95px}
        }

        /* Penegas batas antara Input Pengisian BBM dan Simulasi Pengisian BBM */
        .always-visible-section:first-of-type{position:relative;margin-bottom:18px!important}
        .always-visible-section:first-of-type:after{content:"";position:absolute;left:0;right:0;bottom:-11px;height:5px;background:#cbd5e1;border-top:1px solid #94a3b8;border-bottom:1px solid #e2e8f0;border-radius:3px}
        .simulation-submit-wrap{margin:8px 0 10px!important}
        @media(max-width:767px){.always-visible-section:first-of-type{margin-bottom:16px!important}.always-visible-section:first-of-type:after{bottom:-10px;height:4px}.simulation-submit-wrap{margin:7px 0 9px!important}}

        /* Perbaikan proporsi simulasi tangki pada file terbaru */
        .simulation-layout{grid-template-columns:96px minmax(0,1fr)!important;gap:8px!important;align-items:center!important}
        .tank-summary{display:flex!important;flex-direction:column!important;align-items:center!important;justify-content:center!important;min-width:0!important;padding:0!important}
        .tank-total{margin:0 0 4px!important;line-height:1.2!important}
        .simulation-card .tank-shell{width:68px!important;height:98px!important;margin:0 auto!important;padding:3px!important;box-sizing:border-box!important;display:block!important;overflow:hidden!important;border-width:3px!important}
        .simulation-card .tank-fill{width:100%!important;height:100%!important;display:flex!important;flex-direction:column!important;justify-content:flex-start!important;overflow:hidden!important}
        .simulation-card .tank-part{width:100%!important;min-height:0!important;flex:none!important;flex-shrink:0!important;box-sizing:border-box!important;display:flex!important;align-items:center!important;justify-content:center!important;overflow:hidden!important;font-size:10px!important;line-height:1!important}
        .simulation-card .tank-to-buy{order:1!important}
        .simulation-card .tank-remaining{order:2!important}
        .tank-legend{margin-top:4px!important;line-height:1.25!important}
        .tank-odo{margin-top:4px!important;line-height:1.2!important;white-space:nowrap!important}
        .simulation-results{justify-content:center!important}
        .simulation-result-grid{gap:5px!important}
        .simulation-result-item{min-height:50px!important;padding:6px!important}
        .simulation-result-item.summary-wide{min-height:46px!important}
        @media(max-width:767px){
            .simulation-layout{grid-template-columns:92px minmax(0,1fr)!important;gap:6px!important}
            .simulation-card .tank-shell{width:64px!important;height:92px!important}
            .simulation-result-item{min-height:48px!important;padding:6px!important}
        }
    </style>
@endsection
@section('content')
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Menyimpan data...</p>
        </div>
    </div>
    <div class="content-header compact-page">
        {{-- <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude"> --}}
        <div class="page-header-modern">
            <div class="header-left">
                <div class="badge-tag"><i class="fas fa-gas-pump"></i> Permintaan Pengisian BBM</div>
                <h1>{{ $title }}</h1>
                <p>{{ $title_jp }}</p>
            </div>
            <a class="btn-header-back" href="{{ url('index/driver/job') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        {{-- 1. Input selalu tampil --}}
        <div class="sec-card always-visible-section">
            <div class="sec-card-header"><span class="dot dot-blue"></span> Input Pengisian BBM</div>
            <div class="sec-card-body">
                <div class="ff required-field">
                    <label for="carSelect">Kendaraan <span class="req">*</span></label>
                    <select id="carSelect" name="car_select" required>
                        <option value="" disabled selected>Pilih kendaraan</option>
                        @foreach ($car as $carOption)
                            <option value="{{ $loop->index }}" data-car="{{ $carOption->car }}" data-plat-no="{{ $carOption->plat_no }}" data-fuel-capacity="{{ $carOption->fuel_capacity ?? 0 }}" data-fuel-consumption="{{ $carOption->fuel_consumption ?? 0 }}">{{ $carOption->car }} - {{ $carOption->plat_no }}</option>
                        @endforeach
                    </select>
                    <span class="hint">Pilih kendaraan untuk mengisi informasi dan perhitungan BBM secara otomatis.</span>
                </div>
                <div class="input-divider"></div>
                <div class="photo-card">
                    <span class="photo-label"><i class="fas fa-tachometer-alt"></i> Foto Odometer &amp; Indikator Sebelum Pengisian <span class="req">*</span></span>
                    <input type="file" id="fileDataOdoBefore" name="fileDataOdoBefore" accept="image/*" capture="environment" required>
                    <button type="button" class="ui-accordion-toggle" id="photoPreviewToggle" aria-expanded="false" aria-controls="previewOdoBefore" style="display:none" onclick="toggleFuelAccordion(this, 'previewOdoBefore')">
                        <span><i class="fas fa-image"></i> Lihat Preview Foto</span><i class="fas fa-chevron-down accordion-chevron"></i>
                    </button>
                    <div class="photo-preview" id="previewOdoBefore"><img id="blahOdoBefore" src="" alt="Preview odometer sebelum pengisian"></div>
                </div>
                <div class="g2">
                    <div class="ff required-field"><label for="odometer">Odometer Saat Ini (KM) <span class="req">*</span></label><input type="text" readonly style="background:white;" id="odometer" name="odometer" class="numpad" placeholder="Contoh: 125000" required><span class="hint">Masukkan angka odometer yang terlihat pada dashboard.</span></div>
                    <div class="ff required-field">
                        <label for="remainingRange">Sisa Jarak Tempuh (KM) <span class="req">*</span></label><input type="text" readonly style="background:white;" class="numpad" id="remainingRange" name="remaining_range" placeholder="Contoh: 140" required><span class="hint">Jarak yang masih dapat ditempuh berdasarkan indikator kendaraan.</span>
                        <div id="hiaceGuidanceWrapper" style="display:none;"><button type="button" class="ui-accordion-toggle" aria-expanded="false" aria-controls="remainingRangeGuidancePanel" onclick="toggleFuelAccordion(this, 'remainingRangeGuidancePanel')"><span><i class="fas fa-book-open"></i> Lihat Panduan HiAce</span><i class="fas fa-chevron-down accordion-chevron"></i></button><div id="remainingRangeGuidancePanel" class="ui-accordion-panel"><span class="hint">Untuk <b>Hiace</b>, gunakan indikator jarak tersisa dari odometer.</span><img src="{{ asset('images/Hiace.png') }}" alt="Panduan Jarak Tersisa Hiace" style="max-width:100%;height:auto;"></div></div>
                    </div>
                </div>
                <div class="ff required-field"><label for="fuelLocation">Lokasi Pengisian BBM</label><select id="fuelLocation" name="fuel_location"><option value="" disabled selected>Pilih lokasi pengisian BBM</option><option value="POM Bensin HR Muhammad Surabaya">POM Bensin HR Muhammad Surabaya</option><option value="POM Bensin Kebon Agung Pasuruan">POM Bensin Kebon Agung Pasuruan</option></select><span class="hint">Pilih lokasi pengisian BBM.</span></div>
            </div>
        </div>

        {{-- 2. Simulasi selalu tampil --}}
        <div class="sec-card always-visible-section">
            <div class="sec-card-header"><span class="dot dot-orange"></span> Simulasi Pengisian BBM</div>
            <div class="sec-card-body">
                <div id="simulationEmpty" class="simulation-empty"><i class="fas fa-gas-pump"></i><strong>Belum ada data simulasi</strong><span>Lengkapi kendaraan, odometer, sisa jarak tempuh, dan lokasi pengisian.</span></div>
                <div id="fuelVisualCard" class="fuel-visual-card simulation-card" aria-live="polite">
                    <div class="simulation-layout">
                        <div class="tank-summary">
                            <div class="tank-total">Total <span id="tankTotalText">0 L</span></div>
                            <div class="tank-shell"><div class="tank-fill" id="tankFill"><div class="tank-part tank-to-buy" id="tankBuyPart"><span id="tankBuyInside">+0</span></div><div class="tank-part tank-remaining" id="tankRemainingPart"><span id="tankRemainingInside">0</span></div></div></div>
                            <div class="tank-legend"><div class="fill-text">Isi +<span id="tankBuyText">0</span> L</div><div class="remain-text">Sisa <span id="tankRemainingText">0</span> L</div></div>
                            <div class="tank-odo">ODO <span id="tankOdometerText">0</span> KM</div>
                        </div>
                        <div class="simulation-results">
                            <div class="simulation-title"><i class="fas fa-calculator"></i> Ringkasan Hasil Perhitungan</div>
                            <div class="simulation-result-grid">
                                <div class="simulation-result-item"><span>Estimasi Sisa BBM</span><strong id="summaryRemainingFuel">-</strong></div>
                                <div class="simulation-result-item"><span>Kebutuhan Beli BBM</span><strong id="summaryFuelToBuy">-</strong></div>
                                <div class="simulation-result-item voucher-summary"><span>Nominal Voucher</span><strong id="summaryVoucherDenomination">-</strong></div>
                                <div class="simulation-result-item voucher-summary"><span>Jumlah Voucher</span><strong id="summaryVoucherCount">-</strong></div>
                                <div class="simulation-result-item voucher-summary summary-wide"><span>Total Nilai Voucher</span><strong id="summaryVoucherTotal">-</strong></div>
                                <div class="simulation-result-item no-voucher-summary summary-wide" style="display:none"><span>Total Biaya BBM</span><strong id="summaryTotalCost">-</strong></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="simulation-submit-wrap">
            <button type="button" class="btn-submit" id="submitButton" onclick="submitDriver()">
                <i class="fas fa-paper-plane"></i> Submit Request Fuel
            </button>
        </div>

        {{-- 3. Efisiensi default tertutup --}}
        <div class="sec-card main-accordion">
            <div class="sec-card-header"><span class="dot dot-blue"></span> Efisiensi 3 Bulan Terakhir</div>
            <div class="sec-card-body">
                <button type="button" class="ui-accordion-toggle" id="efficiencyToggle" aria-expanded="false" aria-controls="efficiencyPanel" onclick="toggleMainAccordion(this, 'efficiencyPanel')"><span><i class="fas fa-chart-line"></i> Tampilkan Efisiensi 3 Bulan Terakhir</span><i class="fas fa-chevron-down accordion-chevron"></i></button>
                <div id="efficiencyPanel" class="ui-accordion-panel">
                    <div id="efficiencyEmpty" class="simulation-empty compact-empty"><i class="fas fa-chart-line"></i><strong>Data efisiensi belum tersedia</strong><span>Pilih kendaraan untuk menampilkan histori tiga bulan terakhir.</span></div>
                    <div id="efficiencyContent" style="display:none">
                        <div class="efficiency-main">
                            <div class="efficiency-item"><span>Efisiensi Aktual</span><strong id="efficiencyActual">-</strong></div>
                            <div class="efficiency-item"><span>Standar Kendaraan</span><strong id="efficiencyStandard">-</strong></div>
                            <div class="efficiency-item"><span>Total Jarak</span><strong id="efficiencyDistance">-</strong></div>
                            <div class="efficiency-item"><span>Total BBM</span><strong id="efficiencyFuel">-</strong></div>
                        </div>
                        <div class="efficiency-status" id="efficiencyStatus">Data 3 bulan belum tersedia</div>
                        <button type="button" class="ui-accordion-toggle" id="efficiencyHistoryToggle" aria-expanded="false" aria-controls="efficiencyHistoryPanel" onclick="toggleFuelAccordion(this, 'efficiencyHistoryPanel')"><span><i class="fas fa-history"></i> Lihat Riwayat 3 Bulan</span><i class="fas fa-chevron-down accordion-chevron"></i></button>
                        <div id="efficiencyHistoryPanel" class="ui-accordion-panel"><div id="efficiencyHistoryList" class="history-list"><div class="hint">Data riwayat belum tersedia.</div></div></div>
                    </div>
                </div>
            </div>
        </div>

        {{-- 4. Informasi kendaraan default tertutup --}}
        <div class="sec-card main-accordion">
            <div class="sec-card-header"><span class="dot dot-purple"></span> Informasi Kendaraan</div>
            <div class="sec-card-body">
                <button type="button" class="ui-accordion-toggle" id="vehicleInfoToggle" aria-expanded="false" aria-controls="vehicleInfoPanel" onclick="toggleMainAccordion(this, 'vehicleInfoPanel')"><span><i class="fas fa-car"></i> Tampilkan Informasi Kendaraan</span><i class="fas fa-chevron-down accordion-chevron"></i></button>
                <div id="vehicleInfoPanel" class="ui-accordion-panel"><div class="vehicle-info-grid">
                    <div class="ff"><label>Driver</label><input type="text" id="driver" readonly value="{{ $driver->driver_id }} - {{ $driver->driver_name }}"></div>
                    <div class="ff"><label>Tanggal Pengisian</label><input type="text" id="date" readonly value="{{ date('Y-m-d') }}"></div>
                    <div class="ff"><label>Kendaraan</label><input type="text" id="vehicle" readonly value=""></div>
                    <div class="ff"><label>Plat Nomor</label><input type="text" id="plat_no" readonly value=""></div>
                    <div class="ff"><label>Konsumsi BBM</label><input type="text" id="fuelConsumption" readonly value=""><span class="hint">Diambil dari data kendaraan.</span></div>
                    <div class="ff"><label>Kapasitas Maks Tangki</label><input type="text" id="fuelCapacity" readonly value=""><span class="hint">Diambil dari data kendaraan.</span></div>
                    <div class="ff"><label>Jenis BBM</label><input type="text" id="fuelTypeHidden" readonly value=""><span class="hint">Diambil dari data kendaraan.</span></div>
                    <div class="ff"><label>Harga per Liter</label><input type="text" id="fuelPriceHidden" readonly value=""><span class="hint">Diambil dari data kendaraan.</span></div>
                </div></div>
            </div>
        </div>

        {{-- 5. Detail hasil default tertutup --}}
        <div class="sec-card main-accordion">
            <div class="sec-card-header"><span class="dot dot-green"></span> Hasil Perhitungan BBM</div>
            <div class="sec-card-body">
                <button type="button" class="ui-accordion-toggle" id="fuelResultToggle" aria-expanded="false" aria-controls="fuelCalculationPanel" onclick="toggleMainAccordion(this, 'fuelCalculationPanel')"><span><i class="fas fa-calculator"></i> Tampilkan Hasil Perhitungan BBM</span><i class="fas fa-chevron-down accordion-chevron"></i></button>
                <div id="fuelCalculationPanel" class="ui-accordion-panel">
                    <div class="ff voucher-related" id="div_voucher"><label for="voucherDenomination">Nominal per Voucher (Rp)</label><input type="text" id="voucherDenomination" readonly value="100000"><span class="hint">Default Rp100.000 per voucher.</span></div>
                    <div class="result-grid" aria-live="polite">
                        <div class="result-item"><span class="result-label">Estimasi Sisa BBM</span><span class="result-value" id="remainingFuelResult">-</span><small>Range ÷ konsumsi BBM</small></div>
                        <div class="result-item"><span class="result-label">Kebutuhan Beli BBM</span><span class="result-value" id="fuelToBuyResult">-</span><small>Kapasitas maks tangki − estimasi sisa BBM</small></div>
                        <div class="result-item"><span class="result-label">Total Biaya BBM</span><span class="result-value" id="totalCostResult">-</span><small>Liter yang dibeli × harga per liter</small></div>
                        <div class="result-item voucher-related"><span class="result-label">Jumlah Voucher</span><span class="result-value" id="voucherCountResult">-</span><small>Dibulatkan ke bawah</small></div>
                        <div class="result-item voucher-related"><span class="result-label">Total Nilai Voucher</span><span class="result-value" id="voucherTotalResult">-</span><small>Jumlah voucher × nominal voucher</small></div>
                        <div class="result-item voucher-related"><span class="result-label">Sisa Nilai Voucher</span><span class="result-value" id="voucherDifferenceResult">-</span><small>Total voucher − estimasi biaya</small></div>
                    </div>
                    <div class="calculation-note" id="calculationNote">Masukkan odometer dan range tersisa untuk menghitung kebutuhan BBM secara otomatis.</div>
                </div>
                <input type="hidden" id="remaining_fuel_liter" name="remaining_fuel_liter"><input type="hidden" id="fuel_to_buy_liter" name="fuel_to_buy_liter"><input type="hidden" id="fuel_total_cost" name="fuel_total_cost"><input type="hidden" id="voucher_count" name="voucher_count"><input type="hidden" id="voucher_total" name="voucher_total">
            </div>
        </div>

    </div>
@endsection
@section('scripts')
    <script src="{{ url('js/jquery.numpad.js') }}"></script>
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script>
    var bbm = <?php echo json_encode($bbm); ?>;
    var car = <?php echo json_encode($car); ?>;
    var fuelEfficiency3Months = @json($fuelEfficiency3Months ?? []);
        function toggleMainAccordion(button, panelId) {
            document.querySelectorAll('.main-accordion .ui-accordion-panel.is-open').forEach(function(panel) {
                if (panel.id !== panelId) {
                    panel.classList.remove('is-open');
                    panel.style.display = 'none';
                    var other = document.querySelector('[aria-controls="' + panel.id + '"]');
                    if (other) other.setAttribute('aria-expanded', 'false');
                }
            });
            toggleFuelAccordion(button, panelId);
        }
        function toggleFuelAccordion(button, panelId) {
            var panel = document.getElementById(panelId);
            if (!panel) return;
            var isOpen = button.getAttribute('aria-expanded') === 'true';
            var shouldOpen = !isOpen;
            button.setAttribute('aria-expanded', String(shouldOpen));
            if (panelId === 'previewOdoBefore') {
                panel.style.display = shouldOpen ? 'block' : 'none';
                panel.classList.toggle('is-open', shouldOpen);
            } else {
                panel.classList.toggle('is-open', shouldOpen);
                panel.style.display = shouldOpen ? 'block' : 'none';
            }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        /* ── Numpad config ── */
        var isSmall = screen.width < 400;
        var btnW = isSmall ? '62px' : '76px';
        var btnH = isSmall ? '54px' : '66px';
        var btnFS = isSmall ? '20px' : '24px';
        var dispH = isSmall ? '70px' : '86px';
        var dispFS = isSmall ? '30px' : '38px';
        var padW = isSmall ? '220px' : '270px';
        var numBtnStyle = [
            'background:#fff',
            'color:#2d3748',
            'border:1.5px solid #e2e8f0',
            'border-radius:12px',
            'font-size:' + btnFS,
            'font-weight:700',
            'width:' + btnW,
            'height:' + btnH,
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
            'height:' + btnH,
            'line-height:1',
            'width:' + btnW,
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
            'width:' + padW + ';',
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
        $.fn.numpad.defaults.backgroundTpl =
            '<div class="modal-backdrop in" style="opacity:.55;background:#2d2b4e;z-index:30099;"></div>';
        $.fn.numpad.defaults.displayTpl = [
            '<input type="text" style="',
            'background:linear-gradient(135deg,#2d2b4e,#605ca8);',
            'color:#fff;',
            'font-size:' + dispFS + ';',
            'font-weight:800;',
            'text-align:center;',
            'border:none;',
            'border-radius:0;',
            'height:' + dispH + ';',
            'letter-spacing:3px;',
            'padding:0 20px;',
            'box-shadow:none;',
            'width:100%;',
            'font-family:Plus Jakarta Sans,sans-serif;',
            '"/>'
        ].join('');
        $.fn.numpad.defaults.buttonNumberTpl = '<button type="button" style="' + numBtnStyle + '"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" style="' + funcBtnStyle + '"></button>';
        $.fn.numpad.defaults.onKeypadCreate = function() {
            var $table = $(this);
            if ($table.find('.numpad-title').length === 0) {
                $table.prepend('<tr class="numpad-title"><td colspan="3" style="padding:0; border:none;"></td></tr>');
            }
            $table.find('.done').css({
                'background': 'linear-gradient(135deg,#15803d,#16a34a)',
                'color': '#fff',
                'border': 'none',
                'width': btnW,
                'height': btnH,
                'border-radius': '12px',
                'font-size': '13px',
                'font-weight': '700',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });
            $table.find('.cancel').css({
                'background': '#fee2e2',
                'color': '#dc2626',
                'border': '1.5px solid #fecaca',
                'width': btnW,
                'height': btnH,
                'border-radius': '12px',
                'font-size': '13px',
                'font-weight': '700',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });
            $table.find('.clear').css({
                'background': '#fef3c7',
                'color': '#b45309',
                'border': '1.5px solid #fde68a',
                'width': btnW,
                'height': btnH,
                'border-radius': '12px',
                'font-size': '13px',
                'font-weight': '700',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });
            $table.find('.del').css({
                'background': '#f1f5f9',
                'color': '#475569',
                'border': '1.5px solid #e2e8f0',
                'width': btnW,
                'height': btnH,
                'border-radius': '12px',
                'font-size': '18px',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });
            $table.find('.sep').css({
                'background': '#ebf2ff',
                'color': '#2d6bc4',
                'border': '1.5px solid #c3d9f8',
                'width': btnW,
                'height': btnH,
                'border-radius': '12px',
                'font-size': btnFS,
                'font-weight': '700',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });
            $table.find('button').not('.done,.cancel,.clear,.del,.sep').on('mouseenter', function() {
                $(this).css({
                    'background': '#ede9fe',
                    'border-color': '#605ca8',
                    'color': '#4a4690'
                });
            }).on('mouseleave', function() {
                $(this).css({
                    'background': '#fff',
                    'border-color': '#e2e8f0',
                    'color': '#2d3748'
                });
            });
        };
        // Konfigurasi dari data kendaraan.
        let fuelConsumption = 0;
        let fuelCapacity = 0;
        const submitUrl = @json($submitUrl ?? '');
        let map = null;
        let marker = null;
        let lastCalculation = null;
        function updateSelectedVehicle() {
            const select = document.getElementById('carSelect');
            const option = select.options[select.selectedIndex];
            if (!option || option.value === '') return;
            const carName = option.dataset.car || '';
            const platNo = option.dataset.platNo || '';
            fuelConsumption = Number(option.dataset.fuelConsumption || 0);
            fuelCapacity = Number(option.dataset.fuelCapacity || 0);
            document.getElementById('vehicle').value = carName;
            document.getElementById('plat_no').value = platNo;
            document.getElementById('fuelConsumption').value = fuelConsumption + ' km/liter';
            document.getElementById('fuelCapacity').value = fuelCapacity + ' liter';
            var type = '';
            for (let i = 0; i < car.length; i++) {
                const carOption = car[i];
                if (carOption.plat_no === platNo) {
                    type = carOption.fuel_type || '';
                    break;
                }
            }
            var price = 0;
            for (let i = 0; i < bbm.length; i++) {
                const bbmOption = bbm[i];
                const parts = bbmOption.split('_');
                if (parts[0] === type) {
                    price = parts[1] || 0;
                    break;
                }
            }
            const isHiace = /hi\s*-?\s*ace/i.test(carName);
            document.getElementById('hiaceGuidanceWrapper').style.display = isHiace ? 'block' : 'none';
            document.getElementById('fuelPriceHidden').value = price || '';
            document.getElementById('fuelTypeHidden').value = type || '';
            calculateFuel();
            renderEfficiency();
        }
        function formatNumber(value, maximumFractionDigits = 2) {
            return Number(value).toLocaleString('id-ID', {
                minimumFractionDigits: 0,
                maximumFractionDigits: maximumFractionDigits
            });
        }
        function formatRupiah(value) {
            return 'Rp ' + Math.round(Number(value) || 0).toLocaleString('id-ID');
        }
        function setResult(id, value) {
            document.getElementById(id).textContent = value;
        }
        function isNoVoucherLocation() {
            return $('#fuelLocation').val() === 'POM Bensin HR Muhammad Surabaya';
        }
        function updateFuelLocationUI() {
            const noVoucher = isNoVoucherLocation();
            $('.voucher-related').toggleClass('is-hidden', noVoucher);
            calculateFuel();
        }
        function normalizePlate(value) {
            return String(value || '')
                .toUpperCase()
                .replace(/\s+/g, '')
                .trim();
        }

        function getEfficiencyForVehicle(platNo, carName) {
            if (!fuelEfficiency3Months) {
                return null;
            }

            var data = Array.isArray(fuelEfficiency3Months)
                ? fuelEfficiency3Months
                : Object.values(fuelEfficiency3Months);

            var targetPlate = normalizePlate(platNo);

            var found = data.find(function (item) {
                return normalizePlate(item.plat_no) === targetPlate;
            });

            if (found) {
                return found;
            }

            var targetCar = String(carName || '').trim().toUpperCase();

            return data.find(function (item) {
                return String(item.vehicle || '')
                    .trim()
                    .toUpperCase() === targetCar;
            }) || null;
        }
        function renderEfficiency() {
            var platNo = $('#plat_no').val();
            var carName = $('#vehicle').val();
            var data = getEfficiencyForVehicle(platNo, carName);
            $('#efficiencyStandard').text(fuelConsumption > 0 ? formatNumber(fuelConsumption) + ' KM/L' : '-');
            $('#efficiencyActual').text('-');
            $('#efficiencyDistance, #efficiencyFuel').text('-');
            $('#efficiencyStatus').removeClass('good watch bad').text('Data 3 bulan belum tersedia');
            $('#efficiencyHistoryList').html('<div class="hint">Data riwayat belum tersedia.</div>');
            var hasData = !!(data && (Number(data.total_fuel || 0) > 0 || Number(data.total_distance || 0) > 0 || Number(data.efficiency || 0) > 0));
            $('#efficiencyEmpty').toggle(!hasData);
            $('#efficiencyContent').toggle(hasData);
            if (!hasData) return;
            var actual = Number(data.efficiency || data.efficiency_actual || 0);
            var distance = Number(data.total_distance || 0);
            var totalFuel = Number(data.total_fuel || 0);
            if (!actual && distance > 0 && totalFuel > 0) actual = distance / totalFuel;
            $('#efficiencyActual').text(actual > 0 ? formatNumber(actual) + ' KM/L' : '-');
            $('#efficiencyDistance').text(distance > 0 ? formatNumber(distance, 0) + ' KM' : '-');
            $('#efficiencyFuel').text(totalFuel > 0 ? formatNumber(totalFuel) + ' L' : '-');
            var ratio = fuelConsumption > 0 && actual > 0 ? actual / fuelConsumption * 100 : 0;
            var status = $('#efficiencyStatus').removeClass('good watch bad');
            if (ratio > 130) status.addClass('watch').text('Periksa data • hasil melebihi standar');
            else if (ratio >= 95) status.addClass('good').text('Efisien • ' + formatNumber(ratio, 1) + '% dari standar');
            else if (ratio >= 85) status.addClass('watch').text('Perlu dipantau • ' + formatNumber(ratio, 1) + '% dari standar');
            else if (ratio > 0) status.addClass('bad').text('Tidak efisien • ' + formatNumber(ratio, 1) + '% dari standar');
            var monthly = Array.isArray(data.monthly) ? data.monthly : (Array.isArray(data.months) ? data.months : []);
            if (monthly.length) {
                $('#efficiencyHistoryList').html(monthly.map(function(row) {
                    var eff = Number(row.efficiency || 0), d = Number(row.distance || 0), f = Number(row.fuel || 0);
                    if (!eff && d > 0 && f > 0) eff = d / f;
                    return '<div class="history-row"><strong>' + (row.month || row.period || '-') + '</strong><span>' + formatNumber(d, 0) + ' KM</span><span>' + formatNumber(f) + ' L</span><span>' + formatNumber(eff) + ' KM/L</span></div>';
                }).join(''));
            } else {
                $('#efficiencyHistoryList').html('<div class="hint">Ringkasan tersedia, tetapi rincian bulanan belum tersedia.</div>');
            }
        }
        function updateFuelVisual() {
            var odometerValue = $('#odometer').val();
            var rangeValue = $('#remainingRange').val();
            var ready = $('#carSelect').val() !== null && odometerValue !== '' && rangeValue !== '' && $('#fuelLocation').val() && lastCalculation;
            $('#simulationEmpty').toggle(!ready);
            $('#fuelVisualCard').toggleClass('is-visible', !!ready);
            renderEfficiency();
            if (!ready) return;
            var remain = Math.max(0, Number(lastCalculation.remaining_fuel_liter || 0));
            var buy = Math.max(0, Number(lastCalculation.fuel_to_buy_liter || 0));
            var total = Math.max(0, remain + buy);
            var remainPct = total > 0 ? Math.max(0, Math.min(100, remain / total * 100)) : 0;
            var buyPct = total > 0 ? Math.max(0, 100 - remainPct) : 0;
            $('#tankTotalText').text(formatNumber(total) + ' L');
            $('#tankBuyText').text(formatNumber(buy));
            $('#tankRemainingText').text(formatNumber(remain));
            $('#tankOdometerText').text(Number(odometerValue).toLocaleString('id-ID'));
            $('#tankBuyInside').text('+' + formatNumber(buy));
            $('#tankRemainingInside').text(formatNumber(remain));
            $('#tankBuyPart').css({
                height: buyPct + '%',
                flexBasis: buyPct + '%'
            });
            $('#tankRemainingPart').css({
                height: remainPct + '%',
                flexBasis: remainPct + '%'
            });
            $('#summaryRemainingFuel').text(formatNumber(remain) + ' liter');
            $('#summaryFuelToBuy').text(formatNumber(buy) + ' liter');
            var noVoucher = isNoVoucherLocation();
            $('.voucher-summary').toggle(!noVoucher);
            $('.no-voucher-summary').toggle(noVoucher);
            if (noVoucher) {
                $('#summaryTotalCost').text(formatRupiah(Number(lastCalculation.fuel_total_cost || 0)));
            } else {
                $('#summaryVoucherDenomination').text(formatRupiah(Number(lastCalculation.voucher_denomination || 0)));
                $('#summaryVoucherCount').text(formatNumber(Number(lastCalculation.voucher_count || 0), 0) + ' voucher');
                $('#summaryVoucherTotal').text(formatRupiah(Number(lastCalculation.voucher_total || 0)));
            }
        }
        function calculateFuel() {
            const rangeValue = $('#remainingRange').val();
            const range = Number(rangeValue);
            const fuelPrice = Number($('#fuelPriceHidden').val());
            const voucherDenomination = Number($('#voucherDenomination').val());
            const noVoucher = isNoVoucherLocation();
            if (rangeValue === '' || !Number.isFinite(range) || range < 0 ||
                !Number.isFinite(fuelConsumption) || fuelConsumption <= 0 ||
                !Number.isFinite(fuelCapacity) || fuelCapacity <= 0 ||
                !$('#fuelPriceHidden').val() || !Number.isFinite(fuelPrice) || fuelPrice < 0 ||
                (!noVoucher && (!Number.isFinite(voucherDenomination) || voucherDenomination <= 0))) {
                ['remainingFuelResult', 'fuelToBuyResult', 'totalCostResult',
                    'voucherCountResult', 'voucherTotalResult', 'voucherDifferenceResult'
                ]
                .forEach(id => setResult(id, '-'));
                $('#calculationNote').text(
                    'Periksa range, konsumsi BBM, kapasitas maks tangki, dan jenis BBM. Untuk lokasi yang menggunakan voucher, pastikan nominal voucher valid.'
                );
                lastCalculation = null;
                clearHiddenCalculation();
                updateFuelVisual();
                return;
            }
            const remainingFuelRaw = range / fuelConsumption;
            if (remainingFuelRaw > fuelCapacity) {
                $('#calculationNote').text('Range tersisa menghasilkan estimasi ' + formatNumber(remainingFuelRaw) +
                    ' liter, melebihi kapasitas maks tangki ' + formatNumber(fuelCapacity) +
                    ' liter. Periksa kembali data range atau konsumsi kendaraan.');
                lastCalculation = null;
                clearHiddenCalculation();
                setResult('remainingFuelResult', formatNumber(remainingFuelRaw) + ' liter');
                setResult('fuelToBuyResult', '-');
                setResult('totalCostResult', '-');
                setResult('voucherCountResult', '-');
                setResult('voucherTotalResult', '-');
                setResult('voucherDifferenceResult', '-');
                return;
            }
            const remainingFuel = remainingFuelRaw;
            const fuelToBuy = Math.max(0, fuelCapacity - remainingFuel);
            const totalCost = fuelToBuy * fuelPrice;
            const voucherCount = noVoucher ? 0 : (totalCost > 0 ? Math.floor(totalCost / voucherDenomination) : 0);
            const voucherTotal = noVoucher ? 0 : voucherCount * voucherDenomination;
            const voucherDifference = noVoucher ? 0 : voucherTotal - totalCost;
            lastCalculation = {
                odometer: $('#odometer').val() === '' ? null : Number($('#odometer').val()),
                remaining_range: range,
                fuel_location: $('#fuelLocation').val(),
                fuel_consumption: fuelConsumption,
                fuel_capacity: fuelCapacity,
                remaining_fuel_liter: remainingFuel,
                fuel_to_buy_liter: fuelToBuy,
                fuel_price_per_liter: fuelPrice,
                fuel_total_cost: totalCost,
                voucher_denomination: noVoucher ? 0 : voucherDenomination,
                voucher_count: voucherCount,
                voucher_total: voucherTotal,
                voucher_difference: voucherDifference
            };
            setResult('remainingFuelResult', formatNumber(remainingFuel) + ' liter');
            setResult('fuelToBuyResult', formatNumber(fuelToBuy) + ' liter');
            setResult('totalCostResult', formatRupiah(totalCost));
            setResult('voucherCountResult', noVoucher ? '-' : formatNumber(voucherCount, 0) + ' voucher');
            setResult('voucherTotalResult', noVoucher ? '-' : formatRupiah(voucherTotal));
            setResult('voucherDifferenceResult', noVoucher ? '-' : formatRupiah(voucherDifference));
            let note = 'Perhitungan: ' + formatNumber(range) + ' km ÷ ' + formatNumber(fuelConsumption) +
                ' km/liter = ' + formatNumber(remainingFuel) + ' liter tersisa. ' +
                formatNumber(fuelCapacity) + ' liter − ' + formatNumber(remainingFuel) +
                ' liter = ' + formatNumber(fuelToBuy) + ' liter yang perlu dibeli. ' +
                formatNumber(fuelToBuy) + ' × ' + formatRupiah(fuelPrice) + ' = ' + formatRupiah(totalCost) + '.';
            note += noVoucher
                ?
                ' Lokasi HR Muhammad menggunakan nominal aktual tanpa voucher.'
                :
                ' Voucher dibulatkan ke bawah menjadi ' + formatNumber(voucherCount, 0) +
                ' voucher (' + formatRupiah(voucherTotal) + ').';
            $('#calculationNote').text(note);
            $('#remaining_fuel_liter').val(remainingFuel);
            $('#fuel_to_buy_liter').val(fuelToBuy);
            $('#fuel_total_cost').val(totalCost);
            $('#voucher_count').val(voucherCount);
            $('#voucher_total').val(voucherTotal);
            updateFuelVisual();
        }
        function clearHiddenCalculation() {
            $('#remaining_fuel_liter, #fuel_to_buy_liter, #fuel_total_cost, #voucher_count, #voucher_total').val('');
        }
        // function getLocation() {
        //     if (!navigator.geolocation) {
        //         return;
        //     }
        //     navigator.geolocation.getCurrentPosition(function(position) {
        //         const lat = position.coords.latitude;
        //         const lng = position.coords.longitude;
        //         $('#latitude').val(lat);
        //         $('#longitude').val(lng);
        //         $('#locDot').addClass('got');
        //     }, function() {
        //     }, {
        //         enableHighAccuracy: true,
        //         timeout: 15000,
        //         maximumAge: 0
        //     });
        // }
        function previewImage(input, imgId, previewId) {
            if (!input.files || !input.files[0]) {
                $('#' + previewId).hide();
                $('#' + imgId).attr('src', '');
                return;
            }
            const file = input.files[0];
            if (!file.type.match(/^image\//)) {
                alert('File dokumentasi harus berupa gambar.');
                input.value = '';
                return;
            }
            const reader = new FileReader();
            reader.onload = function(event) {
                const image = new Image();
                image.onload = function() {
                    const maxDimension = 1600;
                    const scale = Math.min(1, maxDimension / Math.max(image.width, image.height));
                    const canvas = document.createElement('canvas');
                    canvas.width = Math.round(image.width * scale);
                    canvas.height = Math.round(image.height * scale);
                    canvas.getContext('2d').drawImage(image, 0, 0, canvas.width, canvas.height);
                    $('#' + imgId).attr('src', canvas.toDataURL('image/jpeg', 0.75));
                    $('#' + previewId).removeClass('is-open').hide();
                };
                image.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }
        function submitDriver() {
            const odometerValue = $('#odometer').val();
            const fuelLocation = $('#fuelLocation').val();
            if (!$('#carSelect').val()) { alert('Kendaraan wajib dipilih.'); $('#carSelect').focus(); return; }
            const isNoVoucher = fuelLocation === 'POM Bensin HR Muhammad Surabaya';
            const photoInput = document.getElementById('fileDataOdoBefore');
            if (odometerValue === '' || !Number.isFinite(Number(odometerValue)) || Number(odometerValue) < 0) {
                alert('Odometer saat ini wajib diisi dengan angka yang valid.');
                $('#odometer').focus();
                return;
            }
            if ($('#remainingRange').val() === '' || !Number.isFinite(Number($('#remainingRange').val())) || Number($(
                    '#remainingRange').val()) < 0) {
                alert('Range tersisa wajib diisi dengan angka yang valid.');
                $('#remainingRange').focus();
                return;
            }
            if (!fuelLocation) {
                alert('Lokasi pengisian BBM wajib dipilih.');
                $('#fuelLocation').focus();
                return;
            }
            // if (!$('#fuelPrice').val()) {
            //     alert('Jenis BBM wajib dipilih.');
            //     $('#fuelPrice').focus();
            //     return;
            // }
            // Pastikan kalkulasi terbaru sesuai lokasi yang dipilih.
            calculateFuel();
            if (!lastCalculation) {
                alert('Perhitungan BBM belum valid. Periksa kembali input dan data kendaraan.');
                return;
            }
            if (!photoInput.files || !photoInput.files[0]) {
                alert('Foto odometer dan indikator sebelum pengisian wajib dilampirkan.');
                return;
            }
const formData = new FormData();
            formData.append('odometer', odometerValue);
            formData.append('remaining_range', $('#remainingRange').val());
            formData.append('fuel_location', fuelLocation);
            formData.append('fuel_consumption', fuelConsumption);
            formData.append('fuel_capacity', fuelCapacity);
            formData.append('fuel_price_per_liter', $('#fuelPriceHidden').val());
            formData.append('fuel_type', $('#fuelTypeHidden').val());
            formData.append('voucher_denomination', isNoVoucher ? 0 : lastCalculation.voucher_denomination);
            formData.append('voucher_count', isNoVoucher ? 0 : lastCalculation.voucher_count);
            formData.append('voucher_total', isNoVoucher ? 0 : lastCalculation.voucher_total);
            formData.append('voucher_difference', isNoVoucher ? 0 : lastCalculation.voucher_difference);
            formData.append('remaining_fuel_liter', lastCalculation.remaining_fuel_liter);
            formData.append('fuel_to_buy_liter', lastCalculation.fuel_to_buy_liter);
            formData.append('fuel_total_cost', lastCalculation.fuel_total_cost);
            formData.append('latitude', $('#latitude').val());
            formData.append('longitude', $('#longitude').val());
            formData.append('fileDataOdoBefore', photoInput.files[0]);
            formData.append('driver_id', $('#driver').val().split(' - ')[0]);
            formData.append('driver_name', $('#driver').val().split(' - ').slice(1).join(' - '));
            formData.append('date', $('#date').val());
            formData.append('vehicle', $('#vehicle').val());
            formData.append('plat_no', $('#plat_no').val());
            $('#loading').addClass('show');
            $('#submitButton').prop('disabled', true);
            $.ajax({
                url: '{{ url('input/driver/request/fuel') }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(response) {
                    $('#loading').removeClass('show');
                    $('#submitButton').prop('disabled', false);
                    if (response && (response.status === true || response.success === true)) {
                        alert(response.message || 'Permintaan BBM berhasil disimpan.');
                        window.location.href = '{{ url('index/driver') }}';
                    } else {
                        alert((response && response.message) ||
                            'Data belum dapat disimpan. Periksa respons server.');
                    }
                },
                error: function(xhr) {
                    $('#loading').removeClass('show');
                    $('#submitButton').prop('disabled', false);
                    const message = xhr.responseJSON && xhr.responseJSON.message ?
                        xhr.responseJSON.message :
                        'Terjadi kesalahan saat menyimpan permintaan BBM.';
                    alert(message);
                }
            });
        }
        $(document).ready(function() {
            $('body').toggleClass('sidebar-collapse');
            $('#side_vfi').addClass('menu-open');
            $('#remainingRange, #fuelPrice, #voucherDenomination').on('input change', calculateFuel);
            $('#carSelect').on('change', updateSelectedVehicle);
            $('#fuelLocation').on('change', function(){ updateFuelLocationUI(); calculateFuel(); });
            $('#odometer').on('input', calculateFuel);
            $('#fileDataOdoBefore').on('change', function() {
                previewImage(this, 'blahOdoBefore', 'previewOdoBefore');
                $('#previewOdoBefore').removeClass('is-open');
                $('#photoPreviewToggle').attr('aria-expanded', 'false').toggle(!!(this.files && this.files.length));
            });
            $('.numpad').numpad({
                hidePlusMinusButton: true,
                decimalSeparator: '.'
            });
            $('.select2').select2({
                allowClear: true
            });
            calculateFuel();
            // getLocation();
        });
    </script>
@endsection
