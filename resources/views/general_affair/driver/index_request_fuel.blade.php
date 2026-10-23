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
    </style>
@endsection
@section('content')
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Menyimpan data...</p>
        </div>
    </div>
    <div class="content-header">
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">
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
        <div class="sec-card">
            <div class="sec-card-header"><span class="dot dot-blue"></span> Pilih Kendaraan</div>
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
            </div>
        </div>
        <div class="sec-card">
            <div class="sec-card-header"><span class="dot dot-purple"></span> Informasi Kendaraan</div>
            <div class="sec-card-body">
                <button type="button" class="ui-accordion-toggle" id="vehicleInfoToggle" aria-expanded="false" aria-controls="vehicleInfoPanel" onclick="toggleFuelAccordion(this, 'vehicleInfoPanel')">
                    <span><i class="fas fa-car"></i> Lihat Informasi Kendaraan</span><i class="fas fa-chevron-down accordion-chevron"></i>
                </button>
                <div id="vehicleInfoPanel" class="ui-accordion-panel">
                <div class="vehicle-info-grid">
                    <div class="ff">
                        <label>Driver</label>
                        <input type="text" id="driver" readonly
                            value="{{ $driver->driver_id }} - {{ $driver->driver_name }}">
                    </div>
                    <div class="ff">
                        <label>Tanggal Pengisian</label>
                        <input type="text" id="date" readonly value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="ff">
                        <label>Kendaraan</label>
                        <input type="text" id="vehicle" readonly value="">
                    </div>
                    <div class="ff">
                        <label>Plat Nomor</label>
                        <input type="text" id="plat_no" readonly value="">
                    </div>
                    <div class="ff">
                        <label>Konsumsi BBM</label>
                        <input type="text" id="fuelConsumption" readonly
                            value="">
                        <span class="hint">Diambil dari data kendaraan.</span>
                    </div>
                    <div class="ff">
                        <label>Kapasitas Maks Tangki</label>
                        <input type="text" id="fuelCapacity" readonly value="">
                        <span class="hint">Diambil dari data kendaraan.</span>
                    </div>
                    <div class="ff">
                        <label>Jenis BBM</label>
                        <input type="text" id="fuelTypeHidden" readonly value="">
                        <span class="hint">Diambil dari data kendaraan.</span>
                    </div>
                    <div class="ff">
                        <label>Harga per Liter</label>
                        <input type="text" id="fuelPriceHidden" readonly value="">
                        <span class="hint">Diambil dari data kendaraan.</span>
                    </div>
                </div>
                </div>
            </div>
        </div>
        <div class="sec-card">
            <div class="sec-card-header"><span class="dot dot-yellow"></span> Parameter &amp; Perhitungan BBM</div>
            <div class="sec-card-body">
                <div class="photo-card">
                    <span class="photo-label">
                        <i class="fas fa-tachometer-alt"></i>
                        Foto Odometer &amp; Indikator Sebelum Pengisian <span class="req">*</span>
                    </span>
                    <input type="file" id="fileDataOdoBefore" name="fileDataOdoBefore" accept="image/*"
                        capture="environment" required>
                    <button type="button" class="ui-accordion-toggle" id="photoPreviewToggle" aria-expanded="false" aria-controls="previewOdoBefore" style="display:none" onclick="toggleFuelAccordion(this, 'previewOdoBefore')">
                        <span><i class="fas fa-image"></i> Lihat Preview Foto</span><i class="fas fa-chevron-down accordion-chevron"></i>
                    </button>
                    <div class="photo-preview" id="previewOdoBefore">
                        <img id="blahOdoBefore" src="" alt="Preview odometer sebelum pengisian">
                    </div>
                </div>
                <div class="g2">
                    <div class="ff required-field">
                        <label for="odometer">Odometer Saat Ini (KM) <span class="req">*</span></label>
                        <input type="text" readonly style="background: white;" id="odometer" name="odometer"
                            class="numpad" placeholder="Contoh: 125000" required>
                        <span class="hint">Masukkan angka odometer yang terlihat pada dashboard.</span>
                    </div>
                    <div class="ff required-field">
                        <label for="remainingRange">Sisa Jarak Tempuh (KM) <span class="req">*</span></label>
                        <input type="text" readonly style="background: white;" class="numpad" id="remainingRange"
                            name="remaining_range" placeholder="Contoh: 140" required>
                        <span class="hint">Jarak yang masih dapat ditempuh berdasarkan indikator kendaraan.</span>
                        <div id="hiaceGuidanceWrapper" style="display:none;">
                        <button type="button" class="ui-accordion-toggle" aria-expanded="false" aria-controls="remainingRangeGuidancePanel" onclick="toggleFuelAccordion(this, 'remainingRangeGuidancePanel')">
                            <span><i class="fas fa-book-open"></i> Lihat Panduan HiAce</span><i class="fas fa-chevron-down accordion-chevron"></i>
                        </button>
                        <div id="remainingRangeGuidancePanel" class="ui-accordion-panel">
                            <div id="remainingRangeGuidance" style="margin-bottom:10px;">
                                <span class="hint">Untuk <b>Hiace</b>, gunakan indikator jarak tersisa dari odometer dengan panduan seperti di bawah ini.</span>
                                <img src="{{ asset('images/Hiace.png') }}" alt="Panduan Jarak Tersisa Hiace" style="max-width:100%;height:auto;">
                            </div>
                        </div>
                        </div>
                    </div>
                </div>
                <div class="ff required-field">
                    <label for="fuelLocation">Lokasi Pengisian BBM</label>
                    <select id="fuelLocation" name="fuel_location">
                        <option value="" disabled selected>Pilih lokasi pengisian BBM</option>
                        <option value="POM Bensin HR Muhammad Surabaya">POM Bensin HR Muhammad Surabaya</option>
                        <option value="POM Bensin Kebon Agung Pasuruan">POM Bensin Kebon Agung Pasuruan</option>
                    </select>
                    <span class="hint">Pilih lokasi pengisian BBM.</span>
                </div>
                <div class="ff voucher-related" id="div_voucher">
                    <label for="voucherDenomination">Nominal per Voucher (Rp)</label>
                    <input type="text" id="voucherDenomination" readonly value="100000">
                    <span class="hint">Default Rp100.000 per voucher.</span>
                </div>
                <button type="button" class="ui-accordion-toggle" aria-expanded="false" aria-controls="fuelCalculationPanel" onclick="toggleFuelAccordion(this, 'fuelCalculationPanel')">
                    <span><i class="fas fa-calculator"></i> Lihat Hasil Perhitungan BBM</span><i class="fas fa-chevron-down accordion-chevron"></i>
                </button>
                <div id="fuelCalculationPanel" class="ui-accordion-panel">
                <div class="result-grid" aria-live="polite">
                    <div class="result-item">
                        <span class="result-label">Estimasi Sisa BBM</span>
                        <span class="result-value" id="remainingFuelResult">-</span>
                        <small>Range ÷ konsumsi BBM</small>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Kebutuhan Beli BBM</span>
                        <span class="result-value" id="fuelToBuyResult">-</span>
                        <small>Kapasitas maks tangki − estimasi sisa BBM</small>
                    </div>
                    <div class="result-item">
                        <span class="result-label">Total Biaya BBM</span>
                        <span class="result-value" id="totalCostResult">-</span>
                        <small>Liter yang dibeli × harga per liter</small>
                    </div>
                    <div class="result-item voucher-related">
                        <span class="result-label">Jumlah Voucher</span>
                        <span class="result-value" id="voucherCountResult">-</span>
                        <small>Dibulatkan ke bawah</small>
                    </div>
                    <div class="result-item voucher-related">
                        <span class="result-label">Total Nilai Voucher</span>
                        <span class="result-value" id="voucherTotalResult">-</span>
                        <small>Jumlah voucher × nominal voucher</small>
                    </div>
                    <div class="result-item voucher-related">
                        <span class="result-label">Sisa Nilai Voucher</span>
                        <span class="result-value" id="voucherDifferenceResult">-</span>
                        <small>Total voucher − estimasi biaya</small>
                    </div>
                </div>
                <div class="calculation-note" id="calculationNote">
                    Masukkan odometer dan range tersisa untuk menghitung kebutuhan BBM secara otomatis.
                </div>
                </div>
                {{-- Nilai kalkulasi untuk dikirim ke backend --}}
                <input type="hidden" id="remaining_fuel_liter" name="remaining_fuel_liter">
                <input type="hidden" id="fuel_to_buy_liter" name="fuel_to_buy_liter">
                <input type="hidden" id="fuel_total_cost" name="fuel_total_cost">
                <input type="hidden" id="voucher_count" name="voucher_count">
                <input type="hidden" id="voucher_total" name="voucher_total">
            </div>
        </div>
<div style="margin-bottom:40px;">
            <button type="button" class="btn-submit" id="submitButton" onclick="submitDriver()">
                <i class="fas fa-paper-plane"></i> Submit Request Fuel
            </button>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('js/jquery.numpad.js') }}"></script>
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script>
    var bbm = <?php echo json_encode($bbm); ?>;
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
            var price = 0;
            for (let i = 0; i < bbm.length; i++) {
                const bbmOption = bbm[i];
                const parts = bbmOption.split('_');
                type = parts[0] || '';
                price = parts[1] || 0;
            }
            const isHiace = /hi\s*-?\s*ace/i.test(carName);
            document.getElementById('hiaceGuidanceWrapper').style.display = isHiace ? 'block' : 'none';
            document.getElementById('fuelPriceHidden').value = price || '';
            document.getElementById('fuelTypeHidden').value = type || '';
            calculateFuel();
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
        }
        function clearHiddenCalculation() {
            $('#remaining_fuel_liter, #fuel_to_buy_liter, #fuel_total_cost, #voucher_count, #voucher_total').val('');
        }
        function getLocation() {
            if (!navigator.geolocation) {
                return;
            }
            navigator.geolocation.getCurrentPosition(function(position) {
                const lat = position.coords.latitude;
                const lng = position.coords.longitude;
                $('#latitude').val(lat);
                $('#longitude').val(lng);
                $('#locDot').addClass('got');
            }, function() {
            }, {
                enableHighAccuracy: true,
                timeout: 15000,
                maximumAge: 0
            });
        }
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
            if (!$('#fuelPrice').val()) {
                alert('Jenis BBM wajib dipilih.');
                $('#fuelPrice').focus();
                return;
            }
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
            formData.append('fuel_price_per_liter', $('#fuelPrice').val());
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
                        if (response.redirect) {
                            window.location.href = response.redirect;
                        }
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
            $('#fuelLocation').on('change', updateFuelLocationUI);
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
            getLocation();
        });
    </script>
@endsection
