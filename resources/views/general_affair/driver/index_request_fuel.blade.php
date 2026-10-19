@extends('layouts.master')

@section('title', 'VFI')

@section('styles')

    <link href="{{ url('css/jquery.numpad.css') }}" rel="stylesheet">

    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">

    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"

        integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin="">

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

        #map {

            width: 100%;

            height: 280px;

            border-radius: 12px;

            border: 1.5px solid #e2e8f0;

            z-index: 1;

        }

        .location-status {

            display: flex;

            align-items: center;

            gap: 8px;

            margin-top: 10px;

            font-size: 12px;

            color: #718096;

        }

        .loc-dot {

            width: 8px;

            height: 8px;

            border-radius: 50%;

            background: #a0aec0;

        }

        .loc-dot.got {

            background: #22c55e;

            box-shadow: 0 0 6px rgba(34, 197, 94, .5);

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

            <div class="sec-card-header"><span class="dot dot-purple"></span> Informasi Kendaraan</div>

            <div class="sec-card-body">

                <div class="vehicle-info-grid">

                    <div class="ff">

                        <label>Driver</label>

                        <input type="text" id="driver" readonly

                            value="{{ $car->driver_id }} - {{ $car->driver_name }}">

                    </div>

                    <div class="ff">

                        <label>Tanggal Pengisian</label>

                        <input type="text" id="date" readonly value="{{ date('Y-m-d') }}">

                    </div>

                    <div class="ff">

                        <label>Kendaraan</label>

                        <input type="text" id="vehicle" readonly value="{{ $car->car }}">

                    </div>

                    <div class="ff">

                        <label>Plat Nomor</label>

                        <input type="text" id="plat_no" readonly value="{{ $car->plat_no }}">

                    </div>

                    <div class="ff">

                        <label>Konsumsi BBM</label>

                        <input type="text" id="fuelConsumption" readonly

                            value="{{ $car->fuel_consumption ?? 0 }} km/liter">

                        <span class="hint">Diambil dari data kendaraan.</span>

                    </div>

                    <div class="ff">

                        <label>Kapasitas Maks Tangki</label>

                        <input type="text" id="fuelCapacity" readonly value="{{ $car->fuel_capacity ?? 0 }} liter">

                        <span class="hint">Diambil dari data kendaraan.</span>

                    </div>

                </div>

            </div>

        </div>

        <div class="sec-card">

            <div class="sec-card-header"><span class="dot dot-yellow"></span> Parameter &amp; Perhitungan BBM</div>

            <div class="sec-card-body">

                <div class="g2">

                    <div class="ff">

                        <label for="odometer">Odometer Saat Ini (KM) <span class="req">*</span></label>

                        <input type="text" readonly style="background: white;" id="odometer" name="odometer"

                            class="numpad" placeholder="Contoh: 125000" required>

                        <span class="hint">Masukkan angka odometer yang terlihat pada dashboard.</span>

                    </div>

                    <div class="ff">

                        <label for="remainingRange">Range Tersisa di Odometer (KM) <span class="req">*</span></label>

                        <input type="text" readonly style="background: white;" class="numpad" id="remainingRange"

                            name="remaining_range" placeholder="Contoh: 140" required>

                        <span class="hint">Jarak yang masih dapat ditempuh berdasarkan indikator kendaraan.</span>

                        @if(str_contains(strtolower($car->car), 'hiace') || str_contains(strtolower($car->car), 'hi-ace') || str_contains(strtolower($car->car), 'hi ace'))

                        <div id="remainingRangeGuidance" style="margin-bottom: 10px;">

                            <span class="hint">Untuk <b>Hiace</b>, gunakan indikator jarak tersisa dari odometer dengan panduan seperti di bawah ini.</span>

                            <img src="{{ asset('images/Hiace.png') }}" alt="Panduan Jarak Tersisa Hiace" style="max-width: 100%; height: auto;">

                        </div>

                        @endif

                    </div>

                </div>

                <div class="ff">

                    <label for="fuelLocation">Lokasi Pengisian BBM</label>

                    <select id="fuelLocation" name="fuel_location">

                        <option value="" disabled selected>Pilih lokasi pengisian BBM</option>

                        <option value="POM Bensin HR Muhammad Surabaya">POM Bensin HR Muhammad Surabaya</option>

                        <option value="POM Bensin Kebon Agung Pasuruan">POM Bensin Kebon Agung Pasuruan</option>

                    </select>

                    <span class="hint">Pilih lokasi pengisian BBM.</span>

                </div>

                <div class="ff">

                    <label for="fuelPrice">Harga BBM per Liter (Rp)</label>

                    <select id="fuelPrice" onchange="updateFuelHiddenFields()">

                        <option value="" disabled selected>Pilih jenis BBM</option>

                        @foreach ($bbm as $bbmOption)

                            @php

                                $parts = explode('_', $bbmOption);

                                $type = $parts[0];

                                $price = $parts[1];

                            @endphp

                            <option value="{{ $price }}">{{ $type }} -

                                Rp{{ number_format($price, 0, ',', '.') }}/liter</option>

                        @endforeach

                    </select>

                    <span class="hint">Pilih jenis BBM dan harga per liter.</span>

                    <input type="hidden" id="fuelPriceHidden" name="fuel_price" value="">

                    <input type="hidden" id="fuelTypeHidden" name="fuel_type" value="">

                </div>

                <div class="ff voucher-related" id="div_voucher">

                    <label for="voucherDenomination">Nominal per Voucher (Rp)</label>

                    <input type="text" id="voucherDenomination" readonly value="100000">

                    <span class="hint">Default Rp100.000 per voucher.</span>

                </div>

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

                    <div class="result-item voucher-related" style="display: none;">

                        <span class="result-label">Sisa Nilai Voucher</span>

                        <span class="result-value" id="voucherDifferenceResult">-</span>

                        <small>Total voucher − estimasi biaya</small>
                        <small>Abaikan jika negatif, supaya tidak ada voucher yang tersisa.</small>

                    </div>

                </div>

                <div class="calculation-note" id="calculationNote">

                    Masukkan odometer dan range tersisa untuk menghitung kebutuhan BBM secara otomatis.

                </div>

                {{-- Nilai kalkulasi untuk dikirim ke backend --}}

                <input type="hidden" id="remaining_fuel_liter" name="remaining_fuel_liter">

                <input type="hidden" id="fuel_to_buy_liter" name="fuel_to_buy_liter">

                <input type="hidden" id="fuel_total_cost" name="fuel_total_cost">

                <input type="hidden" id="voucher_count" name="voucher_count">

                <input type="hidden" id="voucher_total" name="voucher_total">

            </div>

        </div>

        <div class="sec-card">

            <div class="sec-card-header"><span class="dot dot-blue"></span> Lokasi GPS</div>

            <div class="sec-card-body">

                <div id="map"></div>

                <div class="location-status">

                    <div class="loc-dot" id="locDot"></div>

                    <span id="locText">Mendapatkan lokasi...</span>

                </div>

            </div>

        </div>

        <div class="sec-card">

            <div class="sec-card-header"><span class="dot dot-green"></span> Foto Dokumentasi</div>

            <div class="sec-card-body">

                <div class="photo-card">

                    <span class="photo-label">

                        <i class="fas fa-tachometer-alt"></i>

                        Foto Odometer &amp; Indikator Sebelum Pengisian <span class="req">*</span>

                    </span>

                    <input type="file" id="fileDataOdoBefore" name="fileDataOdoBefore" accept="image/*"

                        capture="environment" required>

                    <div class="photo-preview" id="previewOdoBefore">

                        <img id="blahOdoBefore" src="" alt="Preview odometer sebelum pengisian">

                    </div>

                </div>

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

    <script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"

        integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM=" crossorigin=""></script>

    <script>

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

        const fuelConsumption = Number(@json($car->fuel_consumption ?? 0));

        const fuelCapacity = Number(@json($car->fuel_capacity ?? 0));

        const submitUrl = @json($submitUrl ?? '');

        let map = null;

        let marker = null;

        let lastCalculation = null;

        function updateFuelHiddenFields() {

            const fuelPriceSelect = document.getElementById('fuelPrice');

            const selectedOption = fuelPriceSelect.options[fuelPriceSelect.selectedIndex];

            document.getElementById('fuelPriceHidden').value = selectedOption.value;

            document.getElementById('fuelTypeHidden').value = selectedOption.text.split(' - ')[0];

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

            const fuelPrice = Number($('#fuelPrice').val());

            const voucherDenomination = Number($('#voucherDenomination').val());

            const noVoucher = isNoVoucherLocation();

            if (rangeValue === '' || !Number.isFinite(range) || range < 0 ||

                !Number.isFinite(fuelConsumption) || fuelConsumption <= 0 ||

                !Number.isFinite(fuelCapacity) || fuelCapacity <= 0 ||

                !$('#fuelPrice').val() || !Number.isFinite(fuelPrice) || fuelPrice < 0 ||

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

        function initMap(lat, lng) {

            if (!map) {

                map = L.map('map').setView([lat, lng], 15);

                L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {

                    attribution: '&copy; OpenStreetMap contributors'

                }).addTo(map);

            } else {

                map.setView([lat, lng], 15);

            }

            if (marker) map.removeLayer(marker);

            marker = L.marker([lat, lng]).addTo(map).bindPopup('<b>Lokasi Anda</b>').openPopup();

            setTimeout(function() {

                map.invalidateSize();

            }, 100);

        }

        function getLocation() {

            if (!navigator.geolocation) {

                $('#locText').text('Browser tidak mendukung geolokasi.');

                return;

            }

            navigator.geolocation.getCurrentPosition(function(position) {

                const lat = position.coords.latitude;

                const lng = position.coords.longitude;

                $('#latitude').val(lat);

                $('#longitude').val(lng);

                $('#locDot').addClass('got');

                $('#locText').text('Lokasi diperoleh: ' + lat.toFixed(5) + ', ' + lng.toFixed(5));

                initMap(lat, lng);

            }, function() {

                $('#locText').text('Gagal mendapatkan lokasi. Pastikan izin lokasi diaktifkan.');

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

                    $('#' + previewId).show();

                };

                image.src = event.target.result;

            };

            reader.readAsDataURL(file);

        }

        function submitDriver() {

            const odometerValue = $('#odometer').val();

            const fuelLocation = $('#fuelLocation').val();

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

            if (!$('#latitude').val() || !$('#longitude').val()) {

                if (!confirm('Lokasi GPS belum tersedia. Tetap lanjutkan pengajuan?')) return;

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
                        window.location.href = '{{ url("index/driver/job") }}';

                        // if (response.redirect) {


                        // }

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

            $('#fuelLocation').on('change', updateFuelLocationUI);

            $('#odometer').on('input', calculateFuel);

            $('#fileDataOdoBefore').on('change', function() {

                previewImage(this, 'blahOdoBefore', 'previewOdoBefore');

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
