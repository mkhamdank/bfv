@extends('layouts.app')

@php
    $lang = isset($lang) && $lang === 'en' ? 'en' : 'id';

    $translations = [
        'id' => [
            'welcome' => 'Selamat Datang',
            'system_title' => 'Sistem Manajemen Kunjungan',
            'visit_id_or_email' => 'ID Kunjungan atau Email',
            'search_placeholder' => 'YMPI-ABCDE / email@contoh.com',
            'search' => 'Cari',
            'back' => 'Kembali',
            'not_found' => 'ID Kunjungan atau Email tidak ditemukan. Silakan cek input Anda dan coba lagi.',
            'input_required' => 'Silakan masukkan ID Kunjungan atau Email.',
            'not_visited' => 'Belum Berkunjung',
            'visited_at' => 'Berkunjung pada',
            'company' => 'Perusahaan',
            'email' => 'Email',
            'start' => 'Mulai',
            'finish' => 'Selesai',
            'purpose' => 'Tujuan',
            'host' => 'PIC yang ditemui',
            'participants' => 'Peserta',
            'name' => 'Nama',
            'phone' => 'Telepon',
            'origin' => 'Asal',
            'safety_induction' => 'Safety Induction',
            'active' => 'Aktif',
            'not_yet' => 'Belum',
            'creating_qr' => 'Membuat Kode QR...',
            'download_qr' => 'Unduh Kode QR',
            'show_qr_security' => 'Tunjukkan kode QR ini di Security',
            'qr_title_1' => 'Kode QR untuk verifikasi kedatangan',
            'qr_title_2' => 'di Security YMPI',
            'visitor_file' => 'Pengunjung',
            'error' => 'Kesalahan',
        ],
        'en' => [
            'welcome' => 'Welcome',
            'system_title' => 'Visit Management System',
            'visit_id_or_email' => 'Visit ID or Email',
            'search_placeholder' => 'YMPI-ABCDE / email@example.com',
            'search' => 'Search',
            'back' => 'Back',
            'not_found' => 'Visit ID or email was not found. Please check your input and try again.',
            'input_required' => 'Please enter a Visit ID or Email.',
            'not_visited' => 'Not Visited',
            'visited_at' => 'Visited at',
            'company' => 'Company',
            'email' => 'Email',
            'start' => 'Start',
            'finish' => 'Finish',
            'purpose' => 'Purpose',
            'host' => 'Host / PIC',
            'participants' => 'Participants',
            'name' => 'Name',
            'phone' => 'Phone',
            'origin' => 'Origin',
            'safety_induction' => 'Safety Induction',
            'active' => 'Active',
            'not_yet' => 'Not Completed',
            'creating_qr' => 'Generating QR Code...',
            'download_qr' => 'Download QR Code',
            'show_qr_security' => 'Show this QR code to Security',
            'qr_title_1' => 'QR Code for arrival verification',
            'qr_title_2' => 'at YMPI Security',
            'visitor_file' => 'Visitor',
            'error' => 'Error',
        ],
    ];

    $t = $translations[$lang];
@endphp


@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        *, *::before, *::after {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body, html {
            min-height: 100%;
            height: auto;
            font-family: 'Plus Jakarta Sans', sans-serif;
            overflow-x: hidden;
        }

        body {
            margin: 0;
            overflow-y: auto !important;
        }

        /* ── Latar Belakang Halaman ── */
        .login-page {
            min-height: 100vh;
            height: auto;
            display: flex;
            justify-content:center !important;
            align-items: flex-start !important;
            background-color: #f0eef9;
            background-image:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(96, 92, 168, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 80% 80%, rgba(96, 92, 168, 0.08) 0%, transparent 70%);
            padding: 24px;
        }

        /* ── Kartu ── */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow:
                0 1px 3px rgba(96, 92, 168, 0.08),
                0 8px 32px rgba(96, 92, 168, 0.12),
                0 32px 64px rgba(96, 92, 168, 0.06);
            width: 100%;
            max-width:840px;
            margin:0 auto;
            padding: 44px 40px 40px;
            animation: cardIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo / Merek ── */
        .brand-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
            /* gap: 5px; */
            background-color: #605ca8;
            padding: 10px 20px 10px 12px;
            border-radius: 50px;
        }

        .brand-pill img {
            width: 36px;
            height: 36px;
            object-fit: contain;
            margin-left: 10px;
        }

        .brand-pill span {
            color: #ffffff;
            font-size: 15px;
            font-weight: 700;
            letter-spacing: 0.01em;
        }

        /* ── Judul ── */
        .form-heading {
            text-align: center;
            margin-bottom: 28px;
        }

        .form-heading h1 {
            font-size: 22px;
            font-weight: 700;
            color: #1e1b3a;
            margin-bottom: 6px;
        }

        .form-heading p {
            font-size: 13.5px;
            color: #8b87b5;
        }

        /* ── Garis Pembatas ── */
        .divider {
            height: 1px;
            background: #eeecfb;
            margin-bottom: 28px;
        }

        /* ── Peringatan ── */
        .alert-box {
            display: flex;
            align-items: flex-start;
            gap: 10px;
            border-radius: 10px;
            padding: 12px 14px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.5;
            animation: fadeIn 0.25s ease;
        }

        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(-4px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        .alert-box.error {
            background: #fef3f2;
            border: 1px solid #fecdca;
            color: #b42318;
        }

        .alert-box.success {
            background: #f0fdf4;
            border: 1px solid #bbf7d0;
            color: #166534;
        }

        .alert-box .alert-icon {
            font-size: 15px;
            line-height: 1.4;
            flex-shrink: 0;
        }

        /* ── Input ── */
        .field {
            margin-bottom: 18px;
        }

        .field label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: #3d3a5c;
            margin-bottom: 7px;
        }

        .field-inner {
            position: relative;
        }

        .field-inner .f-icon {
            position: absolute;
            left: 14px;
            top: 50%;
            transform: translateY(-50%);
            color: #b0acd8;
            font-size: 14px;
            pointer-events: none;
            transition: color 0.2s;
        }

        .field-inner input {
            width: 100%;
            border: 1.5px solid #e2dff5;
            border-radius: 10px;
            padding: 11px 14px 11px 40px;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            color: #1e1b3a;
            background: #faf9ff;
            outline: none;
            transition: border-color 0.2s, box-shadow 0.2s, background 0.2s;
        }

        .field-inner input::placeholder {
            color: #c4c0e0;
        }

        .field-inner input:focus {
            border-color: #605ca8;
            background: #ffffff;
            box-shadow: 0 0 0 3px rgba(96, 92, 168, 0.12);
        }

        .field-inner:focus-within .f-icon {
            color: #605ca8;
        }

        .field-inner input.is-invalid {
            border-color: #f04438;
        }

        .field-error {
            font-size: 12px;
            color: #f04438;
            margin-top: 5px;
            padding-left: 2px;
        }

        /* Tombol Tampilkan Kata Sandi */
        .toggle-pw {
            position: absolute;
            right: 12px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            color: #b0acd8;
            cursor: pointer;
            padding: 4px;
            font-size: 14px;
            line-height: 1;
            transition: color 0.2s;
        }
        .toggle-pw:hover { color: #605ca8; }

        /* ── Tombol Kirim ── */
        .btn-submit {
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 10px;
            background-color: #605ca8;
            color: #ffffff;
            font-family: 'Plus Jakarta Sans', sans-serif;
            font-size: 14px;
            font-weight: 700;
            letter-spacing: 0.02em;
            cursor: pointer;
            transition: background-color 0.2s, transform 0.15s, box-shadow 0.2s;
            box-shadow: 0 4px 14px rgba(96, 92, 168, 0.35);
            margin-top: 6px;
        }

        .btn-submit:hover {
            background-color: #534da0;
            box-shadow: 0 6px 20px rgba(96, 92, 168, 0.45);
            transform: translateY(-1px);
        }

        .btn-submit:active {
            transform: translateY(0);
            box-shadow: 0 2px 8px rgba(96, 92, 168, 0.3);
        }

        /* ── Footer ── */
        .card-footer {
            margin-top: 24px;
            text-align: center;
            font-size: 12px;
            color: #c4c0e0;
        }
        @media (max-width: 768px) {
            .login-page {
                padding: 20px 12px;
                align-items: flex-start;
            }

            .login-card {
                padding: 28px 18px;
            }
        }

        .lang-switch {
            position: relative;
            width: 60px;
            height: 32px;
            display: inline-block;
        }

        .lang-switch input {
            display: none;
        }

        .lang-switch-slider {
            position: absolute;
            cursor: pointer;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background-color: #e2dff5;
            border-radius: 50px;
            transition: 0.3s;
            display: flex;
            align-items: center;
            padding: 0 4px;
            box-shadow: 0 4px 14px rgba(96, 92, 168, 0.18);
        }

        .lang-switch-slider::before {
            content: 'ID';
            position: absolute;
            height: 24px;
            width: 24px;
            left: 4px;
            bottom: 4px;
            background-color: white;
            border-radius: 50%;
            transition: 0.3s;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            font-weight: 700;
            color: #605ca8;
        }

        .lang-switch-slider::after {
            content: 'EN';
            position: absolute;
            right: 6px;
            font-size: 11px;
            font-weight: 600;
            color: #8b87b5;
        }

        .lang-switch input:checked + .lang-switch-slider {
            background-color: #605ca8;
        }

        .lang-switch input:checked + .lang-switch-slider::before {
            left: 32px;
            background-color: white;
            content: 'EN';
            color: #605ca8;
        }

        .lang-switch input:checked + .lang-switch-slider::after {
            content: 'ID';
            left: 8px;
            right: auto;
            color: white;
        }

    </style>
@endsection

@section('content')

<div class="lang-switcher" style="position: absolute; top: 20px; right: 20px; z-index: 1000000;">
    <label class="lang-switch" aria-label="Language switch">
        <input type="checkbox" id="langToggle" {{ $lang === 'en' ? 'checked' : '' }}>
        <span class="lang-switch-slider"></span>
    </label>
</div>

<div class="login-page">
    <div class="login-card">

        {{-- Judul --}}
        <div class="form-heading">
            <h1>{{ $t['welcome'] }}</h1>
            <p>{{ $t['system_title'] }}</p>
        </div>

        <div class="divider"></div>


        @if (session('success'))
            <div class="alert-box success">
                <span class="alert-icon">&#10003;</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        @csrf
        <div class="field">
        <label>{{ $t['visit_id_or_email'] }}</label>
        <div class="field-inner">
            <i class="f-icon">🔍</i>
            <input type="text" name="visit_id" id="visitInput" placeholder="{{ $t['search_placeholder'] }}" required>
        </div>
        </div>
        <button class="btn-submit" style="background-color: #168027;" onclick="checkVisitor()">{{ $t['search'] }}</button>
        <button onclick="window.location.href='{{ url('index/visitor') }}'" class="btn-submit" style="display: inline-block; margin-top: 16px; text-align: center; text-decoration: none;">← {{ $t['back'] }}</button>

        <div id="resultContainer" style="display: none; margin-top: 28px;">
            
        </div>
        <div id="errorContainer" style="display: none; margin-top: 28px;">
            <div class="alert-box error">
                <span class="alert-icon">&#9888;</span>
                <span>{{ $t['not_found'] }}</span>
            </div>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} PT. Yamaha Musical Products Indonesia
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
    <script>
        const T = {!! json_encode($t) !!};

        jQuery(document).ready(function () {
            $('#pageTitle').text(T.system_title);
        });
        function checkVisitor() {
            const visitInput = document.getElementById('visitInput').value.trim();
            const resultContainer = document.getElementById('resultContainer');
            const errorContainer = document.getElementById('errorContainer');

            if (visitInput === '') {
                alert(T.input_required);
                return;
            }

            // Buat permintaan AJAX untuk memeriksa pengunjung
            fetch(`{{ url('input/visitor/check') }}?visitor_id=${encodeURIComponent(visitInput)}`)
                .then(response => response.json())
                .then(data => {
                    if (data.status) {
                        // Tampilkan hasil
                        statusVisit = T.not_visited;
                        if (data.data.data_updated == null) {
                            statusVisit = T.not_visited;
                        } else if (data.data.data_updated != null) {
                            statusVisit = T.visited_at + ' ' + data.data.data_updated;
                        }
                        resultContainer.innerHTML = `
                            <div style="background: linear-gradient(135deg, #ffffff 0%, #f8f7ff 100%); border-radius: 14px; padding: 24px; color: #3d3a5c; border: 1px solid rgba(96, 92, 168, 0.2); box-shadow: 0 8px 32px rgba(96, 92, 168, 0.08);">
                                <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: 20px; padding-bottom: 16px; border-bottom: 1px solid rgba(96, 92, 168, 0.15);">
                                    <h3 style="font-size: 18px; font-weight: 700; color: #1e1b3a; margin: 0;">${data.data.visitor_id}</h3>
                                    <span style="background: ${statusVisit === T.not_visited ? '#fce7f3' : '#10b981'}; color: ${statusVisit === T.not_visited ? '#ec4899' : 'white'}; padding: 6px 12px; border-radius: 6px; font-size: 12px; font-weight: 600;">${statusVisit || T.not_visited}</span>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                    <div>
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.company}</strong></p>
                                        <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.company}</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.email}</strong></p>
                                        <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.email}</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.start}</strong></p>
                                        <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.start_date} ${data.data.start_time}</p>
                                    </div>
                                    <div>
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.finish}</strong></p>
                                        <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.end_date} ${data.data.end_time}</p>
                                    </div>
                                </div>
                                <div style="background: rgba(96, 92, 168, 0.08); border-left: 3px solid #605ca8; padding: 12px 14px; border-radius: 8px; margin-bottom: 16px;">
                                    <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.purpose}</strong></p>
                                    <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.purpose}<br>${data.data.purpose_detail}</p>
                                </div>
                                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px;">
                                    <div>
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 4px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.host}</strong></p>
                                        <p style="font-size: 14px; color: #1e1b3a; margin: 0;">${data.data.host}</p>
                                    </div>
                                </div>
                                ${data.details && data.details.length > 0 ? `
                                    <div style="background: rgba(96, 92, 168, 0.08); border-radius: 8px; padding: 14px; margin-top: 16px;">
                                        <p style="font-size: 12px; color: #8b87b5; margin-bottom: 12px; text-transform: uppercase; letter-spacing: 0.5px;"><strong>${T.participants}</strong></p>
                                        ${data.details.map((participant, index) => {
                                            var inductionStart = null;
                                            var inductionEnd = null;
                                            var isInductionActive = false;
                                            var activeInduction = null;
                                            const visitStart = new Date(data.data.start_date + ' ' + data.data.start_time);
                                            const visitEnd = new Date(data.data.end_date + ' ' + data.data.end_time);
                                            for(var i = 0; i < data.safety_induction.length; i++) {
                                                if(data.safety_induction[i].card_id == participant.card_id) {
                                                    inductionStart = new Date(data.safety_induction[i].start_induction);
                                                    inductionEnd = new Date(data.safety_induction[i].end_induction);
                                                    if(visitStart >= inductionStart && visitEnd <= inductionEnd) {
                                                        isInductionActive = true;
                                                        activeInduction = data.safety_induction[i];
                                                    }
                                                    break;
                                                }
                                            }
                                            console.log(`Participant: ${participant.name}, Induction Active: ${isInductionActive}`, `Visit Start: ${visitStart}, Visit End: ${visitEnd}, Induction Start: ${inductionStart}, Induction End: ${inductionEnd}`);
                                            
                                            return `
                                                <div style="margin-bottom: ${index < data.details.length - 1 ? '12px; padding-bottom: 12px; border-bottom: 1px solid rgba(96, 92, 168, 0.15);' : '0;'}">
                                                    <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px; font-size: 13px; margin-bottom: 10px;">
                                                        <div><span style="color: #8b87b5;">ID:</span> <span style="color: #1e1b3a;">${participant.card_id || '-'}</span></div>
                                                        <div><span style="color: #8b87b5;">${T.name}:</span> <span style="color: #1e1b3a;">${participant.name || '-'}</span></div>
                                                        <div><span style="color: #8b87b5;">${T.phone}:</span> <span style="color: #1e1b3a;">xxx-${participant.phone ? participant.phone.slice(-4) : '----'}</span></div>
                                                        <div><span style="color: #8b87b5;">${T.origin}:</span> <span style="color: #1e1b3a;">${participant.origin || '-'}</span></div>
                                                    </div>
                                                    <div style="padding: 8px 10px; background: ${isInductionActive ? 'rgba(16, 185, 129, 0.1)' : 'rgba(239, 68, 68, 0.1)'}; border-radius: 6px; border-left: 3px solid ${isInductionActive ? '#10b981' : '#ef4444'};">
                                                        <span style="font-size: 12px; font-weight: 600; color: ${isInductionActive ? '#10b981' : '#ef4444'};">${T.safety_induction}: ${isInductionActive ? '✓ ' + T.active + ' ' + (activeInduction ? activeInduction.start_induction + ' - ' + activeInduction.end_induction : '') : '✗ ' + T.not_yet}</span>
                                                    </div>
                                                </div>
                                            `;
                                        }).join('')}
                                    </div>
                                ` : ''}
                            </div>
                            <div style="display: flex; flex-direction: column; gap: 12px; margin-top: 20px;">
                                <div id="QrCodeContainer" style="display: flex; justify-content: center; align-items: center; background: #f0eef9; border-radius: 14px; padding: 20px; min-height: 300px;">
                                    <p style="color: #c4c0e0;">${T.creating_qr}</p>
                                </div>
                                <button onclick="downloadQRCode()" style="background-color: #605ca8; color: white; border: none; border-radius: 10px; padding: 12px; font-size: 14px; font-weight: 600; cursor: pointer; transition: background-color 0.2s;">${T.download_qr}</button>
                            </div>
                        `;
                        resultContainer.style.display = 'block';
                        errorContainer.style.display = 'none';
                        generateQRCode(data.data.visitor_id);
                        currentVisitorId = data.data.visitor_id;
                    } else {
                        resultContainer.style.display = 'none';
                        errorContainer.style.display = 'block';
                    }
                })
                
                .catch(error => {
                    console.error('Error:', error);
                    resultContainer.style.display = 'none';
                    errorContainer.style.display = 'block';
                });
        }

        let currentVisitorId = null;

        function generateQRCode(visitorId) {
            const container = document.getElementById('QrCodeContainer');
            if (!container) return;

            container.innerHTML = `
            <div style="width:100%; max-width:340px; margin:0 auto; text-align:center;">
                <div id="qrCanvasWrap" style="
                background:#ffffff;
                padding:18px;
                border-radius:16px;
                border:1px solid #eeecfb;
                box-shadow: 0 10px 30px rgba(96, 92, 168, 0.12);
                display:inline-block;
                "></div>
                <p style="margin-top:12px; font-size:12px; color:#8b87b5;">${T.show_qr_security}</p>
                <p style="margin-top:4px; font-size:13px; font-weight:700; color:#605ca8;">${visitorId}</p>
            </div>
            `;

            const qrTarget = document.getElementById('qrCanvasWrap');

            new QRCode(qrTarget, {
            text: visitorId,
            width: 220,
            height: 220,
            colorDark: '#605ca8',
            colorLight: '#ffffff',
            correctLevel: QRCode.CorrectLevel.H
            });

            setTimeout(() => {
            const qrEl = qrTarget.querySelector('canvas, img');
            if (qrEl) {
                qrEl.style.display = 'block';
                qrEl.style.margin = '0 auto';
                qrEl.style.background = '#ffffff';
                qrEl.style.padding = '10px';
                qrEl.style.borderRadius = '12px';
            }
            }, 0);
        }

        function closeSuccessModal() {
            document.getElementById('successModal').classList.remove('show');
            setTimeout(function() {
                window.location.href = '{{ url("index/visitor") }}';
            }, 500);
        }

        function downloadQRCode() {
            const canvas = document.querySelector('#QrCodeContainer canvas');
            if (canvas) {
                const padding = 20;
                const paddedCanvas = document.createElement('canvas');
                const textHeight = 60;
                paddedCanvas.width = canvas.width + (padding * 2);
                paddedCanvas.height = canvas.height + (padding * 2) + textHeight;
                
                const ctx = paddedCanvas.getContext('2d');
                ctx.fillStyle = '#ffffff';
                ctx.fillRect(0, 0, paddedCanvas.width, paddedCanvas.height);
                
                // Gambar Kode QR
                ctx.drawImage(canvas, padding, padding);
                
                // Gambar judul
                ctx.fillStyle = '#1e1b3a';
                ctx.font = 'bold 14px "Plus Jakarta Sans", sans-serif';
                ctx.textAlign = 'center';
                const lines = [T.qr_title_1, T.qr_title_2];
                lines.forEach((line, index) => {
                    ctx.fillText(line, paddedCanvas.width / 2, canvas.height + padding + 20 + (index * 18));
                });
                
                // Gambar ID Pengunjung
                ctx.fillStyle = '#605ca8';
                ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
                ctx.fillText(currentVisitorId, paddedCanvas.width / 2, canvas.height + padding + 65);
                
                const link = document.createElement('a');
                link.href = paddedCanvas.toDataURL('image/png');
                link.download = `${T.visitor_file} ${currentVisitorId}.png`;
                link.click();
            }
        }

        function updateLanguage(lang) {
            window.location.href = '{{ url("index/visitor/check") }}/' + lang;
        }

        document.getElementById('langToggle').addEventListener('change', function() {
            const newLang = this.checked ? 'en' : 'id';
            updateLanguage(newLang);
        });

    </script>
@endsection
