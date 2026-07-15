@extends('layouts.app')

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
            font-family: 'Plus Jakarta Sans', sans-serif;
        }

        /* ── Language Switcher ── */
        .lang-switcher {
            position: fixed;
            top: 20px;
            right: 20px;
            z-index: 1000;
        }

        .lang-btn {
            background-color: #605ca8;
            color: white;
            border: none;
            padding: 10px 16px;
            border-radius: 50px;
            cursor: pointer;
            font-weight: 600;
            font-size: 13px;
            transition: all 0.3s;
            box-shadow: 0 4px 14px rgba(96, 92, 168, 0.35);
        }

        .lang-btn:hover {
            background-color: #534da0;
            transform: translateY(-2px);
            box-shadow: 0 6px 20px rgba(96, 92, 168, 0.45);
        }

        /* ── Page background ── */
        .login-page {
            min-height: 100vh;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #f0eef9;
            background-image:
                radial-gradient(ellipse 60% 50% at 20% 20%, rgba(96, 92, 168, 0.12) 0%, transparent 70%),
                radial-gradient(ellipse 50% 60% at 80% 80%, rgba(96, 92, 168, 0.08) 0%, transparent 70%);
            padding: 24px;
        }

        /* ── Card ── */
        .login-card {
            background: #ffffff;
            border-radius: 20px;
            box-shadow:
                0 1px 3px rgba(96, 92, 168, 0.08),
                0 8px 32px rgba(96, 92, 168, 0.12),
                0 32px 64px rgba(96, 92, 168, 0.06);
            width: 100%;
            max-width: 440px;
            padding: 44px 40px 40px;
            animation: cardIn 0.5s cubic-bezier(0.22, 1, 0.36, 1) both;
        }

        @keyframes cardIn {
            from { opacity: 0; transform: translateY(16px); }
            to   { opacity: 1; transform: translateY(0); }
        }

        /* ── Logo / Brand ── */
        .brand-wrap {
            display: flex;
            align-items: center;
            justify-content: center;
            margin-bottom: 32px;
        }

        .brand-pill {
            display: inline-flex;
            align-items: center;
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

        /* ── Heading ── */
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

        /* ── Divider ── */
        .divider {
            height: 1px;
            background: #eeecfb;
            margin-bottom: 28px;
        }

        /* ── Alerts ── */
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

        /* ── Fields ── */
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

        /* toggle password */
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

        /* ── Submit ── */
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

            .lang-switcher {
                top: 10px;
                right: 10px;
            }
        }
    </style>
@endsection

@section('content')
<!-- Language Switcher -->
<div class="lang-switcher">
    <style>
        .lang-switch {
            position: relative;
            width: 60px;
            height: 32px;
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
            color: white;
        }
    </style>
    <label class="lang-switch">
        <input type="checkbox" id="langToggle">
        <span class="lang-switch-slider"></span>
    </label>
</div>

<div class="login-page">
    <div class="login-card">

        {{-- Heading --}}
        <div class="form-heading">
            <h1 id="heading">Selamat Datang</h1>
            <p id="subheading">Sistem Manajemen Kunjungan</p>
        </div>

        <div class="divider"></div>

        @if (session('success'))
            <div class="alert-box success">
                <span class="alert-icon">&#10003;</span>
                <span>{{ session('success') }}</span>
            </div>
        @endif

        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 28px;">
            <!-- Ajukan Kunjungan -->
            <div style="border: 1.5px solid #e2dff5; border-radius: 14px; padding: 28px 20px; text-align: center; transition: all 0.3s;">
                <div style="font-size: 48px; margin-bottom: 14px; color: #605ca8;">📅</div>
                <h3 id="card1Title" style="font-size: 16px; font-weight: 700; color: #1e1b3a; margin-bottom: 6px;">Ajukan<br>Kunjungan</h3>
                <p id="card1Desc" style="font-size: 13px; color: #8b87b5; margin-bottom: 18px;">Kirim permohonan kunjungan baru</p>
                <a id="card1Btn" href="" style="display: block; padding: 11px 16px; background-color: #605ca8; color: white; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;">Mulai Sekarang</a>
            </div>

            <!-- Cek Status -->
            <div style="border: 1.5px solid #e2dff5; border-radius: 14px; padding: 28px 20px; text-align: center; transition: all 0.3s;">
                <div style="font-size: 48px; margin-bottom: 14px; color: #605ca8;">🔍</div>
                <h3 id="card2Title" style="font-size: 16px; font-weight: 700; color: #1e1b3a; margin-bottom: 6px;">Cek<br> Kunjungan</h3>
                <p id="card2Desc" style="font-size: 13px; color: #8b87b5; margin-bottom: 18px;">Lihat status permintaan Anda</p>
                <a href="{{url('index/visitor/check')}}" id="card2Btn" style="display: block; padding: 11px 16px; background-color: #3d3a5c; color: white; border-radius: 10px; text-decoration: none; font-weight: 600; font-size: 14px; transition: all 0.2s;">Cek Sekarang</a>
            </div>
        </div>

        <div class="card-footer">
            &copy; {{ date('Y') }} PT. Yamaha Musical Products Indonesia
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script>
        const translations = {
            id: {
                langText: 'English',
                heading: 'Selamat Datang',
                subheading: 'Sistem Manajemen Kunjungan',
                card1Title: 'Ajukan<br>Kunjungan',
                card1Desc: 'Kirim permohonan kunjungan baru',
                card1Btn: 'Mulai Sekarang',
                card2Title: 'Cek<br> Kunjungan',
                card2Desc: 'Lihat status permintaan Anda',
                card2Btn: 'Cek Sekarang'
            },
            en: {
                langText: 'Bahasa Indonesia',
                heading: 'Welcome',
                subheading: 'Visit Management System',
                card1Title: 'Submit<br>Visit',
                card1Desc: 'Send a new visit request',
                card1Btn: 'Start Now',
                card2Title: 'Check<br>Visit',
                card2Desc: 'View your request status',
                card2Btn: 'Check Now'
            }
        };

        let currentLang = localStorage.getItem('appLang') || 'id';

        function updateLanguage(lang) {
            currentLang = lang;
            localStorage.setItem('appLang', lang);
            
            Object.keys(translations[lang]).forEach(key => {
            const element = document.getElementById(key);
            if (element) {
                element.innerHTML = translations[lang][key];
            }
            });
        }

        document.getElementById('langToggle').addEventListener('click', function() {
            const newLang = currentLang === 'id' ? 'en' : 'id';
            updateLanguage(newLang);
            this.checked = newLang === 'en';
            $('#card1Btn').attr('href', newLang === 'en' ? '{{ url("index/visitor/input/en") }}' : '{{ url("index/visitor/input/id") }}');
            $('#card2Btn').attr('href', newLang === 'en' ? '{{ url("index/visitor/check/en") }}' : '{{ url("index/visitor/check/id") }}');
        });

        // Initialize on page load
        jQuery(document).ready(function () {
            updateLanguage(currentLang);
            document.getElementById('langToggle').checked = currentLang === 'en';
            $('#card1Btn').attr('href', currentLang === 'en' ? '{{ url("index/visitor/input/en") }}' : '{{ url("index/visitor/input/id") }}');
            $('#card2Btn').attr('href', currentLang === 'en' ? '{{ url("index/visitor/check/en") }}' : '{{ url("index/visitor/check/id") }}');
        });
    </script>
@endsection
