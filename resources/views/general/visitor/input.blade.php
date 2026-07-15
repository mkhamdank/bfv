@extends('layouts.app')

@php
    // Laravel 5.4 compatible bilingual text.
    // $lang is supplied by the controller with value: id or en.
    $lang = isset($lang) && $lang === 'en' ? 'en' : 'id';

    $translations = [
        'id' => [
            'page_title' => 'Sistem Manajemen Pengunjung',
            'welcome' => 'Selamat Datang',
            'company' => 'Perusahaan',
            'company_placeholder' => 'Nama Perusahaan...',
            'email_label' => 'Masukkan alamat email Anda',
            'email_placeholder' => 'Alamat Email...',
            'start_date' => 'Tanggal Mulai',
            'start_time' => 'Waktu Mulai',
            'end_date' => 'Tanggal Berakhir',
            'end_time' => 'Waktu Berakhir',
            'select_date' => 'Pilih tanggal',
            'host_label' => 'PIC YMPI / Karyawan yang ditemui',
            'host_placeholder' => 'Pilih PIC YMPI / Karyawan yang ditemui...',
            'host_short_placeholder' => 'Pilih PIC...',
            'purpose' => 'Tujuan',
            'purpose_placeholder' => 'Pilih Tujuan...',
            'purpose_details_placeholder' => 'Jelaskan tujuan kunjungan Anda (min 5 karakter)',
            'visitor_data' => 'Data Pengunjung',
            'id_number' => 'No. KTP',
            'full_name' => 'Nama Lengkap',
            'phone_number' => 'Nomor Telepon',
            'origin' => 'Asal/Kota',
            'add' => 'Tambah',
            'remove' => 'Hapus',
            'consent_title' => 'Saya menyetujui penggunaan data pribadi saya',
            'consent_description' => 'Data pribadi Anda akan digunakan untuk keperluan perusahaan dan dokumentasi keselamatan. Informasi ini tidak akan dibagikan kepada pihak ketiga dan akan ditangani sesuai dengan kebijakan perlindungan data.',
            'submit' => 'Kirim',
            'sending' => 'Mengirim...',
            'back' => 'Kembali',
            'registration_success' => 'Pendaftaran Pengunjung Berhasil',
            'registration_success_description' => 'Pendaftaran pengunjung telah berhasil. Anda akan menerima email konfirmasi.',
            'visitor_id' => 'ID Pengunjung',
            'qr_security_title' => 'Keamanan QR',
            'qr_security_description' => 'Tunjukkan kode QR ini di pos keamanan untuk verifikasi kedatangan.',
            'generating_qr' => 'Membuat QR...',
            'download_qr' => 'Unduh QR',
            'close' => 'Tutup',
            'confirm_submit' => 'Apakah Anda yakin ingin mengirim data pengunjung?',
            'error' => 'Kesalahan',
            'success' => 'Sukses',
            'invalid_email' => 'Format email tidak valid.',
            'end_date_limit' => 'Tanggal berakhir tidak boleh lebih dari 1 minggu dari tanggal mulai. Saran tanggal berakhir: ',
            'all_required' => 'Semua kolom wajib diisi.',
            'submit_error' => 'Terjadi kesalahan saat mengirim data. Silakan coba lagi.',
            'consent_required_title' => 'Persetujuan Diperlukan',
            'consent_required_message' => 'Anda harus menyetujui penggunaan data pribadi sebelum mengirim.',
            'data_refilled' => 'Data Diisi Ulang',
            'data_refilled_message' => 'Data pengunjung telah diisi ulang berdasarkan kunjungan sebelumnya.',
            'not_found' => 'Tidak Ditemukan',
            'search_error' => 'Terjadi kesalahan saat mencari data pengunjung. Silakan coba lagi.',
            'input_required' => 'Input Diperlukan',
            'email_search_required' => 'Silakan masukkan email untuk mencari data pengunjung sebelumnya.',
            'qr_line_1' => 'Kode QR untuk verifikasi kedatangan',
            'qr_line_2' => 'di Security YMPI',
            'download_filename' => 'Pengunjung',
            'purpose_dinas' => 'DINAS',
            'purpose_inspection' => 'INSPEKSI ATAU PENGECEKAN',
            'purpose_project' => 'INSTALASI PROYEK',
            'purpose_goods_delivery' => 'KIRIM BARANG',
            'purpose_document_delivery' => 'KIRIM DOKUMEN',
            'purpose_company_visit' => 'KUNJUNGAN PERUSAHAAN',
            'purpose_mapat' => 'MAPAT BARANG',
            'purpose_meeting' => 'MEETING',
            'purpose_loading' => 'MUAT BARANG',
            'purpose_waste_loading' => 'MUAT LIMBAH ATAU B3',
            'purpose_scrap_loading' => 'MUAT SCRAP',
            'purpose_service' => 'PERBAIKAN ATAU SERVICE',
        ],
        'en' => [
            'page_title' => 'Visitor Management System',
            'welcome' => 'Welcome',
            'company' => 'Company',
            'company_placeholder' => 'Company Name...',
            'email_label' => 'Enter your email address',
            'email_placeholder' => 'Email Address...',
            'start_date' => 'Start Date',
            'start_time' => 'Start Time',
            'end_date' => 'End Date',
            'end_time' => 'End Time',
            'select_date' => 'Select date',
            'host_label' => 'YMPI PIC / Employee to Meet',
            'host_placeholder' => 'Select YMPI PIC / Employee to Meet...',
            'host_short_placeholder' => 'Select PIC...',
            'purpose' => 'Purpose',
            'purpose_placeholder' => 'Select Purpose...',
            'purpose_details_placeholder' => 'Describe the purpose of your visit (min. 5 characters)',
            'visitor_data' => 'Visitor Data',
            'id_number' => 'ID Card Number',
            'full_name' => 'Full Name',
            'phone_number' => 'Phone Number',
            'origin' => 'Origin/City',
            'add' => 'Add',
            'remove' => 'Remove',
            'consent_title' => 'I agree to the use of my personal data',
            'consent_description' => 'Your personal data will be used for company purposes and safety documentation. This information will not be distributed to third parties and will be handled in accordance with the data protection policy.',
            'submit' => 'Submit',
            'sending' => 'Submitting...',
            'back' => 'Back',
            'registration_success' => 'Visitor Registration Successful',
            'registration_success_description' => 'Your visit has been registered. Please save the QR code below.',
            'visitor_id' => 'Visitor ID',
            'qr_security_title' => '📱 QR Code for arrival verification at YMPI Security',
            'qr_security_description' => 'Please show or scan this QR code at YMPI Security upon arrival to verify your visit.',
            'generating_qr' => 'Generating QR Code...',
            'download_qr' => 'Download QR',
            'close' => 'Close',
            'confirm_submit' => 'Are you sure you want to submit the visitor data?',
            'error' => 'Error',
            'success' => 'Success',
            'invalid_email' => 'Invalid email format.',
            'end_date_limit' => 'The end date cannot be more than 1 week after the start date. Suggested end date: ',
            'all_required' => 'All fields are required.',
            'submit_error' => 'An error occurred while submitting the data. Please try again.',
            'consent_required_title' => 'Consent Required',
            'consent_required_message' => 'You must agree to the use of personal data before submitting.',
            'data_refilled' => 'Data Refilled',
            'data_refilled_message' => 'Visitor data has been refilled based on the previous visit.',
            'not_found' => 'Not Found',
            'search_error' => 'An error occurred while searching for visitor data. Please try again.',
            'input_required' => 'Input Required',
            'email_search_required' => 'Please enter an email address to search for previous visitor data.',
            'qr_line_1' => 'QR Code for arrival verification',
            'qr_line_2' => 'at YMPI Security',
            'download_filename' => 'Visitor',
            'purpose_dinas' => 'BUSINESS TRIP',
            'purpose_inspection' => 'INSPECTION OR CHECKING',
            'purpose_project' => 'PROJECT INSTALLATION',
            'purpose_goods_delivery' => 'GOODS DELIVERY',
            'purpose_document_delivery' => 'DOCUMENT DELIVERY',
            'purpose_company_visit' => 'COMPANY VISIT',
            'purpose_mapat' => 'GOODS FITTING / MATCHING',
            'purpose_meeting' => 'MEETING',
            'purpose_loading' => 'GOODS LOADING',
            'purpose_waste_loading' => 'WASTE OR B3 LOADING',
            'purpose_scrap_loading' => 'SCRAP LOADING',
            'purpose_service' => 'REPAIR OR SERVICE',
        ],
    ];

    $text = $translations[$lang];
@endphp


@section('styles')
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.css">
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
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

        /* ── Latar belakang halaman ── */
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

        /* ── Pemisah ── */
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

        /* ── Kolom ── */
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

        /* ── Kirim ── */
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

        .btn-back {
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

        .btn-back:hover {
            background-color: #534da0;
            box-shadow: 0 6px 20px rgba(96, 92, 168, 0.45);
            transform: translateY(-1px);
        }

        .btn-back:active {
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
        #app {
            min-height: 100vh;
            display: block !important;
            overflow: visible !important;
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

        .field-inner .select2-container {
            width: 100% !important;
        }

        .field-inner .select2-container--default .select2-selection--single {
            height: 46px !important;
            border: 1.5px solid #e2dff5 !important;
            border-radius: 12px !important;
            background: #faf9ff !important;
            display: flex !important;
            align-items: center !important;
            padding-left: 34px !important;
            transition: all 0.2s ease;
        }

        .field-inner .select2-container--default.select2-container--focus .select2-selection--single,
        .field-inner .select2-container--default.select2-container--open .select2-selection--single {
            border-color: #605ca8 !important;
            background: #ffffff !important;
            box-shadow: 0 0 0 3px rgba(96, 92, 168, 0.12) !important;
        }

        .field-inner .select2-container--default .select2-selection--single .select2-selection__rendered {
            color: #1e1b3a !important;
            font-size: 14px !important;
            font-weight: 500 !important;
            line-height: 46px !important;
            padding-left: 0 !important;
            padding-right: 36px !important;
        }

        .field-inner .select2-container--default .select2-selection--single .select2-selection__placeholder {
            color: #c4c0e0 !important;
        }

        .field-inner .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 46px !important;
            right: 12px !important;
        }

        .field-inner .select2-container--default .select2-selection--single .select2-selection__clear {
            margin-right: 18px !important;
            color: #1e1b3a !important;
            font-size: 16px !important;
            font-weight: 800 !important;
        }

        .select2-dropdown {
            border: 1.5px solid #e2dff5 !important;
            border-radius: 12px !important;
            overflow: hidden !important;
            box-shadow: 0 12px 30px rgba(96, 92, 168, 0.16) !important;
        }

        .select2-search--dropdown {
            padding: 10px !important;
            background: #ffffff !important;
        }

        .select2-search--dropdown .select2-search__field {
            border: 1.5px solid #e2dff5 !important;
            border-radius: 10px !important;
            padding: 9px 12px !important;
            outline: none !important;
        }

        .select2-results__option {
            padding: 10px 14px !important;
            font-size: 14px !important;
            color: #1e1b3a !important;
        }

        .select2-container--default .select2-results__option--highlighted[aria-selected] {
            background: #605ca8 !important;
            color: #ffffff !important;
        }

        .select2-container--default .select2-results__option[aria-selected=true] {
            background: #f0eef9 !important;
            color: #605ca8 !important;
            font-weight: 700 !important;
        }
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
@endsection

@section('content')
<div class="lang-switcher" style="position: absolute; top: 20px; right: 20px; z-index: 1000000;">
    <label class="lang-switch">
        <input type="checkbox" id="langToggle">
        <span class="lang-switch-slider"></span>
    </label>
</div>
<div class="login-page">
    <div class="login-card">

        {{-- Judul --}}
        <div class="form-heading">
            <h1>{{ $text['welcome'] }}</h1>
            <p>{{ $text['page_title'] }}</p>
        </div>

        <div class="divider"></div>

        {{-- Peringatan Pengunjung Kembali --}}
        {{-- <div class="alert-box" style="background: #f0f4ff; border: 1px solid #d0e0ff; color: #0051ba; margin-bottom: 20px;">
            <span class="alert-icon">↻</span>
            <span>Pengunjung Kembali? <button onclick="returnVisitor();" type="button" id="returning_visitor_btn" style="background: none; border: none; color: #0051ba; cursor: pointer; text-decoration: underline; font-weight: 600; padding: 0; font-size: inherit;">Isi ulang dari kunjungan sebelumnya</button></span>
            <input type="hidden" id="returning_visitor" value="0">
        </div> --}}

        {{-- Email atau Telepon --}}
        {{-- <div class="field" style="display: none;" id="emailPhoneField">
            <label>Masukkan email Anda</label>
            <div class="field-inner">
                <i class="f-icon">@</i>
                <input type="text" id="email_old" placeholder="Email..." value="{{ old('email_old') }}">
                <button type="button" onclick="serchOldData();" class="toggle-pw" style="right: 12px;">🔍</button>
            </div>
            @error('email_old')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div> --}}

        <div class="field" id="emailField">
            <label>{{ $text['company'] }}</label>
            <div class="field-inner">
                <i class="f-icon">🏢</i>
                <input type="text" name="company" id="company" placeholder="{{ $text['company_placeholder'] }}" value="{{ old('company') }}">
            </div>
            @error('company')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Email atau Telepon --}}
        <div class="field" id="emailField">
            <label>{{ $text['email_label'] }}</label>
            <div class="field-inner">
                <i class="f-icon">@</i>
                <input type="email" name="email" id="email" placeholder="{{ $text['email_placeholder'] }}" value="{{ old('email') }}">
            </div>
            @error('email')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tanggal & Waktu Mulai --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="field">
                <label>{{ $text['start_date'] }}</label>
                <div class="field-inner">
                    <i class="f-icon">📅</i>
                    <input type="text" name="start_date" id="start_date" class="datepicker" placeholder="{{ $text['select_date'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px 11px 40px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;" required>
                </div>
                @error('start_date')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label>{{ $text['start_time'] }}</label>
                <div class="field-inner">
                    <input type="text" class="timepicker" name="start_time" id="start_time" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;" value="09:00" required>
                </div>
                @error('start_time')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Tanggal & Waktu Berakhir --}}
        <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
            <div class="field">
                <label>{{ $text['end_date'] }}</label>
                <div class="field-inner">
                    <i class="f-icon">📅</i>
                    <input type="text" name="end_date" id="end_date" class="datepicker" placeholder="{{ $text['select_date'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px 11px 40px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;" required>
                </div>
                @error('end_date')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
            <div class="field">
                <label>{{ $text['end_time'] }}</label>
                <div class="field-inner">
                    <input type="text" class="timepicker" name="end_time" id="end_time" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;" value="17:00" required>
                </div>
                @error('end_time')
                    <div class="field-error">{{ $message }}</div>
                @enderror
            </div>
        </div>

        {{-- Host --}}
        <div class="field">
            <label>{{ $text['host_label'] }}</label>
            <div class="field-inner">
                <i class="f-icon">👤</i>
                <select name="host" id="host" class="host-select" placeholder="{{ $text['host_short_placeholder'] }}" required style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px 11px 40px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s;">
                    <option value="">{{ $text['host_placeholder'] }}</option>
                    @foreach([
                        ['PI9801001', 'Abdul Majid', 'HR'],
                        ['PI9806001', 'Abdullah', 'HR'],
                        ['PI0503004', 'Achmad Khuzaini', 'WLD'],
                        ['PI2312165', 'Achmad Riski Bayu Efendi', 'HR'],
                        ['PI2002019', 'Ade Laksmana Putra', 'PC'],
                        ['PI1209001', 'Adhi Satya Indradhi', 'HR'],
                        ['PI1504002', 'Adityo Bagus Prabowo', 'GA'],
                        ['PI1505001', 'Afifatuz Yulaichah', 'ACC'],
                        ['PI1802029', 'Agung Manggala Putra', 'GA'],
                        ['PI1810011', 'Agung Prasetyo Nugroho', 'PE'],
                        ['PI9909004', 'Agus Salam', 'ST'],
                        ['PI9707001', 'Agustina Hayati', 'STD'],
                        ['PI1602001', 'Ahmad Aminnudinsyah', 'PE'],
                        ['PI2402101', 'Ahmad Athoillah', 'PC'],
                        ['PI2603003', 'Ahmad Rizky Dwi Syahputra', 'ACC'],
                        ['PI1410007', 'Ahmad Subhan Hidayat', 'PP'],
                        ['PI0005015', 'Akhmad Khoiron', 'PP'],
                        ['PI2403008', 'Aldila Karisma Putri', 'ACC'],
                        ['PI1506001', 'Amelia Levina Novrinta Sakata', 'PCH'],
                        ['PI0104003', 'Anang Zahroni', 'PCH'],
                        ['PI1108003', 'Andik Yayan Setyawan', 'PE'],
                        ['PI2411001', 'Andy Hermawan Wijaya', 'PE'],
                        ['PI2002020', 'Angga Setiawan', 'LOG'],
                        ['PI0906001', 'Anton Budi Santoso', 'MIS'],
                        ['PI9902018', 'Ardiyanto', 'FA'],
                        ['PI1908031', 'Arief Asmo Saputro', 'PE'],
                        ['PI9709001', 'Arief Soekamto', 'Management'],
                        ['PI1103002', 'Bagus Nur Hidayat', 'WLD'],
                        ['PI1402005', 'Bagus Panuntun Adi Utarya Putra', 'GA'],
                        ['PI9906003', 'Bambang Supriyadi', 'MTC'],
                        ['PI9902015', 'Bambang Wahyudi', 'WLD'],
                        ['PI2408004', 'Basyiruddin Muchamad', 'STD'],
                        ['PI1605005', 'Bondan Satriya Permadi Widjayanto', 'PP'],
                        ['PI0109004', 'Budhi Apriyanto', 'Management'],
                        ['PI2508014', 'Choirul Anam', 'MTC'],
                        ['PI1106001', 'Darma Bagus Prasetya', 'PE'],
                        ['PI1110002', 'Dicky Kurniawan', 'HR'],
                        ['PI0702002', 'Donni Asri Putra', 'PP'],
                        ['PI1503001', 'Duta Narendratama', 'PE'],
                        ['PI0202001', 'Dwi Misnanto', 'LOG'],
                        ['PI2509003', 'Dwika Tirta Mitrawan', 'ACC'],
                        ['PI2411002', 'Eka Putra Hariono', 'PE'],
                        ['PI1212001', 'Eko Junaedi', 'WLD'],
                        ['PI1110001', 'Eko Prasetyo Wicaksono', 'EI'],
                        ['PI1908032', 'Erlangga Kharisma Muhammad', 'PCH'],
                        ['PI0811001', 'Ertikto Singgih Ambarekmono', 'STD'],
                        ['PI0904001', 'Evi Nur Cholifah', 'GA'],
                        ['PI2507024', 'Faris Ahmad Junaedi', 'PE'],
                        ['PI1506003', 'Farizca Nurma Rahma Bintari', 'PCH'],
                        ['PI9805006', 'Fatchur Rozi', 'PP'],
                        ['PI1111001', 'Fathor Rahman', 'PC'],
                        ['PI9707006', 'Fattatul Mufidah', 'FA'],
                        ['PI0004003', 'Hadi Firmansyah', 'WLD'],
                        ['PI1108001', 'Hanin Hamidi', 'PCH'],
                        ['PI9806004', 'Hartono', 'ST'],
                        ['PI1710002', 'Hendri Susilo', 'PC'],
                        ['PI0904002', 'Heriyanto', 'HR'],
                        ['PI2111044', 'Hiromichi Ichimura', 'Management'],
                        ['PI2508015', 'Ilham Krissetyawan Nusantara', 'PE'],
                        ['PI9707008', 'Imbang Prasetyo', 'ST'],
                        ['PI9807014', 'Imron Faizal', 'MIS'],
                        ['PI1005001', 'Ipung Dwi Setiawan', 'PE'],
                        ['PI0905001', 'Ismail Husen', 'ACC'],
                        ['PI0904003', 'Istiqomah', 'PCH'],
                        ['PI0904004', 'Jihan Rusdi', 'PCH'],
                        ['PI0711002', 'Karina Elnusawati', 'LOG'],
                        ['PI9906002', 'Khoirul Umam', 'HR'],
                        ['PI0902001', 'Lailatul Chusnah', 'ACC'],
                        ['PI2604016', 'Larasati Sekar Palupi', 'LOG'],
                        ['PI1906001', 'Linda Rahmadhani Febrian', 'HR'],
                        ['PI2101043', 'Lukman Hakim Saputra', 'PE'],
                        ['PI2101044', 'Lukmannul Arif', 'PCH'],
                        ['PI2604017', 'M. Adzka Sari\'ul Fahmi Ridwan', 'MIS'],
                        ['PI1911001', 'M. Ali Murdani', 'GA'],
                        ['PI9809012', 'M. Arifin', 'ST'],
                        ['PI0904006', 'M. Hamzah', 'PC'],
                        ['PI9801002', 'M. Hasan Husen', 'HR'],
                        ['PI9903007', 'M. Irfan Haris', 'HR'],
                        ['PI0604014', 'M. Lutfi Al Azam', 'PP'],
                        ['PI0302001', 'M. Nadif', 'MTC'],
                        ['PI0811002', 'Mahendra Putra', 'HR'],
                        ['PI0812002', 'Mamlu\'atul Atiyah', 'MIS'],
                        ['PI1201001', 'Maruli Sapta Adi', 'GA'],
                        ['PI9707010', 'Mawan Sujianto', 'WLD'],
                        ['PI9905001', 'Mei Rahayu', 'Management'],
                        ['PI9809008', 'Mey Indah Astuti', 'EI'],
                        ['PI2302030', 'Mikinori Yano', 'Management'],
                        ['PI1910001', 'Moch. Nofan Sugiasturi', 'PE'],
                        ['PI0604009', 'Mochammad Roziqi', 'ST'],
                        ['PI1108002', 'Mohammad Abdissalam Sa\'idi', 'STD'],
                        ['PI1910002', 'Mokhamad Khamdan Khabibi', 'MIS'],
                        ['PI1810013', 'Muhammad Burhanuddin', 'PE'],
                        ['PI2102025', 'Muhammad Dzulkifli', 'MTC'],
                        ['PI2009022', 'Muhammad Ikhlas Dermawan', 'MIS'],
                        ['PI2002021', 'Muhammad Nasiqul Ibat', 'MIS'],
                        ['PI9804003', 'Mukhamad Khoirul Anam', 'HR'],
                        ['PI2502001', 'Mukhammad Fakhrizal Ihza Mahendra', 'MIS'],
                        ['PI0303002', 'Mukhammad Furqoon', 'FA'],
                        ['PI1102002', 'Nanang Kurniawan', 'FA'],
                        ['PI1112001', 'Noval Fauzi', 'PE'],
                        ['PI9803003', 'Noviera Prasetyarini', 'PC'],
                        ['PI1209002', 'Nunik Erwantiningsih', 'PCH'],
                        ['PI9811008', 'Nurul Hidayat', 'LOG'],
                        ['PI1404002', 'Priyo Jatmiko', 'MTC'],
                        ['PI9910001', 'Purnomo', 'HR'],
                        ['PI2606037', 'Puteri Anggraini Kurniawan', 'GA'],
                        ['PI0804012', 'Putri Airin Sucin', 'PP'],
                        ['PI1106002', 'Putri Sukma Riyanti', 'STD'],
                        ['PI0904007', 'Rani Nurdiyana Sari', 'STD'],
                        ['PI1302001', 'Rano Anugrawan', 'STD'],
                        ['PI0311001', 'Ratri Sulistyorini', 'PCH'],
                        ['PI1201002', 'Rianita Tri Widiastuti', 'STD'],
                        ['PI1910003', 'Rio Irvansyah', 'MIS'],
                        ['PI9902017', 'Romy Agung Kurniawan', 'ACC'],
                        ['PI2602028', 'Rovil Kikik Johan Purwantoko', 'PCH'],
                        ['PI0603010', 'Rozaki', 'EI'],
                        ['PI2607001', 'Sandi Yuda', 'PE'],
                        ['PI2307060', 'Seiya Sekino', 'PE'],
                        ['PI1810020', 'Shega Erik Wicaksono', 'PCH'],
                        ['PI0008010', 'Silvy Firliany', 'PC'],
                        ['PI9903003', 'Slamet Hariadi', 'WLD'],
                        ['PI2412001', 'Srianingsih', 'WLD'],
                        ['PI2607002', 'Sugeng Mulyono', 'STD'],
                        ['PI9807013', 'Sugeng Wibowo', 'HR'],
                        ['PI1503007', 'Sulismawati', 'PCH'],
                        ['PI0703002', 'Susilo Basri Prasetyo', 'Management'],
                        ['PI0007005', 'Sutrisno (c)', 'STD'],
                        ['PI2407001', 'Syafrizal Carnov Purwanto', 'STD'],
                        ['PI1605006', 'Tegar Brillian Nanang', 'ST'],
                        ['PI0711001', 'Telasati Murnomo Fitri', 'ST'],
                        ['PI2512012', 'Thomi Aditya Alhakiim', 'MIS'],
                        ['PI9809011', 'Tofik Nur Hidayat', 'FA'],
                        ['PI2304052', 'Toshiki Hayashi', 'STD'],
                        ['PI0006028', 'Totok Untung Basuni', 'HR'],
                        ['PI1505003', 'Triandini', 'LOG'],
                        ['PI0603019', 'Ummi Ernawati', 'FA'],
                        ['PI0607002', 'Uswatun Khasanah', 'HR'],
                        ['PI0805002', 'Vidiya Chalista', 'STD'],
                        ['PI2401113', 'Virasetya Maharani', 'GA'],
                        ['PI0704008', 'Wachid Hasyim', 'EI'],
                        ['PI9811006', 'Wawang Dwi Putra Y.', 'WLD'],
                        ['PI1210001', 'Whica Parama Sastra', 'MTC'],
                        ['PI1211001', 'Widura', 'GA'],
                        ['PI9710001', 'Yayuk Wahyuni', 'STD'],
                        ['PI9802001', 'Yeny Arisanty', 'ACC'],
                        ['PI1412008', 'Yoga Aditya Agassi Virgiawan', 'LOG'],
                        ['PI2011029', 'Yoga Karunia Perdana', 'PE'],
                        ['PI2111045', 'Yoichi Oyama', 'PC'],
                        ['PI0108010', 'Yudi Abtadipa', 'Management'],
                    ] as $staff)
                        <option value="{{ $staff[0] }} - {{ $staff[1] }} - {{ $staff[2] }}">{{ $staff[1] }} - {{ $staff[2] }}</option>
                    @endforeach
                </select>
            </div>
            @error('host')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tujuan --}}
        <div class="field">
            <label>{{ $text['purpose'] }}</label>
            <div class="field-inner">
                <i class="f-icon">📋</i>
                <select name="purpose" id="purpose" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px 11px 40px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; outline: none; transition: border-color 0.2s, box-shadow 0.2s; appearance: none;" required>
                    <option value="">{{ $text['purpose_placeholder'] }}</option>
                    <option value="DINAS">{{ $text['purpose_dinas'] }}</option>
                    <option value="INSPEKSI ATAU PENGECEKAN">{{ $text['purpose_inspection'] }}</option>
                    <option value="INSTALASI PROYEK">{{ $text['purpose_project'] }}</option>
                    <option value="KIRIM BARANG">{{ $text['purpose_goods_delivery'] }}</option>
                    <option value="KIRIM DOKUMEN">{{ $text['purpose_document_delivery'] }}</option>
                    <option value="KUNJUNGAN PERUSAHAAN">{{ $text['purpose_company_visit'] }}</option>
                    <option value="MAPAT BARANG">{{ $text['purpose_mapat'] }}</option>
                    <option value="MEETING">{{ $text['purpose_meeting'] }}</option>
                    <option value="MUAT BARANG">{{ $text['purpose_loading'] }}</option>
                    <option value="MUAT LIMBAH ATAU B3">{{ $text['purpose_waste_loading'] }}</option>
                    <option value="MUAT SCRAP">{{ $text['purpose_scrap_loading'] }}</option>
                    <option value="PERBAIKAN ATAU SERVICE">{{ $text['purpose_service'] }}</option>
                </select>
            </div>
            <textarea name="purpose_details" id="purpose_details" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff; resize: vertical; min-height: 80px; margin-top: 10px;" placeholder="{{ $text['purpose_details_placeholder'] }}" required></textarea>
            @error('purpose')
                <div class="field-error">{{ $message }}</div>
            @enderror
            @error('purpose_details')
                <div class="field-error">{{ $message }}</div>
            @enderror
        </div>

        {{-- Data Pengunjung --}}
        <div class="field">
            <label>{{ $text['visitor_data'] }}</label>
            <div id="first-visitor" style="display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 80px; gap: 10px; margin-bottom: 12px; align-items: flex-end;">
                <div>
                    <input type="text" name="visitors[0][id_name]" id="visitors_0_id" placeholder="{{ $text['id_number'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" inputmode="numeric" pattern="[0-9]*" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div>
                    <input type="text" name="visitors[0][full_name]" id="visitors_0_name" placeholder="{{ $text['full_name'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" required>
                </div>
                <div>
                    <input type="text" name="visitors[0][phone]" id="visitors_0_phone" placeholder="{{ $text['phone_number'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" inputmode="numeric" pattern="[0-9]*" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '');">
                </div>
                <div>
                    <input type="text" name="visitors[0][origin]" id="visitors_0_origin" placeholder="{{ $text['origin'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" required>
                </div>
                <div>
                    <button type="button" class="btn-add" onclick="addVisitor()" style="background-color: #605ca8; color: white; border: none; border-radius: 8px; padding: 11px 14px; cursor: pointer; font-size: 14px; font-weight: 600; height: fit-content; width: 100%;">{{ $text['add'] }}</button>
                </div>
            </div>
            <div id="visitors-container"></div>
        </div>

        <script>
        let visitorCount = 1;

        function addVisitor() {
            const container = document.getElementById('visitors-container');
            const newRow = document.createElement('div');
            newRow.className = 'visitor-row';
            newRow.style.cssText = 'display: grid; grid-template-columns: 1fr 1fr 1fr 1fr 80px; gap: 10px; margin-bottom: 12px; align-items: flex-end;';
            
            newRow.innerHTML = `
                <input type="text" name="visitors[${visitorCount}][id_name]" id="visitors_${visitorCount}_id" placeholder="{{ $text['id_number'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" inputmode="numeric" pattern="[0-9]*" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '');">
                <input type="text" name="visitors[${visitorCount}][full_name]" id="visitors_${visitorCount}_name" placeholder="{{ $text['full_name'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" required>
                <input type="text" name="visitors[${visitorCount}][phone]" id="visitors_${visitorCount}_phone" placeholder="{{ $text['phone_number'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" inputmode="numeric" pattern="[0-9]*" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '');">
                <input type="text" name="visitors[${visitorCount}][origin]" id="visitors_${visitorCount}_origin" placeholder="{{ $text['origin'] }}" style="width: 100%; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 11px 14px; font-family: 'Plus Jakarta Sans', sans-serif; font-size: 14px; color: #1e1b3a; background: #faf9ff;" required>
                <button type="button" class="btn-remove" onclick="removeVisitor(this)" style="background: #f04438; color: white; border: none; border-radius: 8px; padding: 11px 14px; cursor: pointer; font-size: 14px; font-weight: 600; height: fit-content; width: 100%;">{{ $text['remove'] }}</button>
            `;
            
            container.appendChild(newRow);
            visitorCount++;
        }

        function removeVisitor(btn) {
            btn.closest('.visitor-row').remove();
        }
        </script>

        <style>
            @media (max-width: 768px) {
                #first-visitor {
                    grid-template-columns: 1fr !important;
                }

                #first-visitor .btn-add {
                    grid-column: 1 / -1 !important;
                }

                .visitor-row {
                    grid-template-columns: 1fr !important;
                }

                .visitor-row .btn-remove {
                    grid-column: 1 / -1 !important;
                }
            }
        </style>

        {{-- Persetujuan Data Pribadi --}}
        <div style="margin-bottom: 20px; padding: 12px 14px; background: #faf9ff; border: 1.5px solid #e2dff5; border-radius: 10px; transition: all 0.2s;">
            <label style="display: flex; align-items: flex-start; gap: 10px; font-weight: 500; cursor: pointer; user-select: none; margin: 0;">
                <input type="checkbox" name="data_consent" value="1" style="width: 18px; height: 18px; cursor: pointer; accent-color: #605ca8; margin-top: 3px; flex-shrink: 0;" required onchange="checkConsent(this)">
                <div>
                    <span style="color: #1e1b3a; font-size: 14px; display: block; margin-bottom: 6px;">{{ $text['consent_title'] }}</span>
                    <p style="font-size: 12px; color: #8b87b5; margin: 0; font-weight: 400; line-height: 1.4;">{{ $text['consent_description'] }}</p>
                </div>
            </label>
            @error('data_consent')
                <div class="field-error" style="margin-top: 8px; padding-left: 28px;">{{ $message }}</div>
            @enderror
        </div>

        {{-- Tombol Kirim --}}
        <button onclick="confirmVisitor()" disabled style="background-color: #168027;" class="btn-submit">{{ $text['submit'] }}</button>
        <button onclick="window.location.href='{{ url('index/visitor') }}'" class="btn-back" style="display: inline-block; margin-top: 16px; text-align: center; text-decoration: none;">← {{ $text['back'] }}</button>

        <!-- Modal Sukses dengan Kode QR -->
        <div id="successModal" style="display: none; position: fixed; top: 0; left: 0; width: 100%; height: 100%; background: rgba(0, 0, 0, 0.5); z-index: 9999; justify-content: center; align-items: center;">
            <div style="background: white; border-radius: 20px; padding: 40px; max-width: 500px; width: 90%; text-align: center; box-shadow: 0 20px 60px rgba(96, 92, 168, 0.2); animation: slideUp 0.3s ease;">
                <!-- Ikon Sukses -->
                <div style="font-size: 60px; margin-bottom: 20px;">✓</div>
                
                <!-- Judul -->
                <h2 style="color: #166534; font-size: 24px; margin-bottom: 10px; font-weight: 700;">{{ $text['registration_success'] }}</h2>
                <p style="color: #8b87b5; font-size: 14px; margin-bottom: 30px;">{{ $text['registration_success_description'] }}</p>
                
                <!-- ID Pengunjung -->
                <div style="background: #f0fdf4; border: 2px solid #bbf7d0; border-radius: 10px; padding: 15px; margin-bottom: 20px;">
                    <p style="color: #8b87b5; font-size: 12px; margin: 0 0 5px 0;">{{ $text['visitor_id'] }}</p>
                    <p style="color: #166534; font-size: 20px; font-weight: 700; margin: 0; font-family: monospace;" id="visitorIdDisplay">-</p>
                </div>
                
                <!-- Judul & Instruksi Kode QR -->
                <div style="background: #fef3f2; border: 1px solid #fecdca; border-radius: 10px; padding: 12px; margin-bottom: 20px;">
                    <p style="color: #b42318; font-size: 13px; font-weight: 600; margin: 0 0 6px 0;">{{ $text['qr_security_title'] }}</p>
                    <p style="color: #8b87b5; font-size: 12px; margin: 0; line-height: 1.4;">{{ $text['qr_security_description'] }}</p>
                </div>
                
                <!-- Kontainer Kode QR -->
                <div style="background: #faf9ff; border: 2px dashed #e2dff5; border-radius: 10px; padding: 20px; margin-bottom: 20px;">
                    <div id="qrCodeContainer" style="display: flex; justify-content: center; align-items: center; min-height: 250px;">
                        <p style="color: #c4c0e0;">{{ $text['generating_qr'] }}</p>
                    </div>
                </div>
                
                <!-- Tombol -->
                <div style="display: grid; grid-template-columns: 1fr 1fr; gap: 12px;">
                    <button onclick="downloadQRCode()" style="background-color: #605ca8; color: white; border: none; border-radius: 10px; padding: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                        {{ $text['download_qr'] }}
                    </button>
                    <button onclick="closeSuccessModal()" style="background-color: #f0eef9; color: #605ca8; border: 1.5px solid #e2dff5; border-radius: 10px; padding: 12px; font-weight: 600; cursor: pointer; transition: all 0.2s;">
                        {{ $text['close'] }}
                    </button>
                </div>

                <img src="{{ url('images/safety.png') }}" alt="Safety" style="margin-top: 20px; width: 100%; height: auto;">
            </div>
        </div>

        <style>
            @keyframes slideUp {
                from {
                    opacity: 0;
                    transform: translateY(20px);
                }
                to {
                    opacity: 1;
                    transform: translateY(0);
                }
            }
            
            #successModal {
                display: none !important;
            }
            
            #successModal.show {
                display: flex !important;
            }
        </style>

        <script src="https://cdnjs.cloudflare.com/ajax/libs/qrcodejs/1.0.0/qrcode.min.js"></script>
        <script>
            let currentVisitorId = null;

            function showSuccessModal(visitorId) {
                currentVisitorId = visitorId;
                document.getElementById('visitorIdDisplay').textContent = visitorId;
                document.getElementById('successModal').classList.add('show');
                
                // Generate Kode QR
                generateQRCode(visitorId);
            }

            function generateQRCode(visitorId) {
                const container = document.getElementById('qrCodeContainer');
                container.innerHTML = '';
                
                new QRCode(container, {
                    text: visitorId,
                    width: 250,
                    height: 250,
                    colorDark: '#605ca8',
                    colorLight: '#ffffff',
                    correctLevel: QRCode.CorrectLevel.H
                });
            }

            function closeSuccessModal() {
                document.getElementById('successModal').classList.remove('show');
                setTimeout(function() {
                    window.location.href = '{{ url("index/visitor") }}';
                }, 500);
            }

            function downloadQRCode() {
                const canvas = document.querySelector('#qrCodeContainer canvas');
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
                    const lines = [uiText.qr_line_1, uiText.qr_line_2];
                    lines.forEach((line, index) => {
                        ctx.fillText(line, paddedCanvas.width / 2, canvas.height + padding + 20 + (index * 18));
                    });
                    
                    // Gambar ID pengunjung
                    ctx.fillStyle = '#605ca8';
                    ctx.font = 'bold 16px "Plus Jakarta Sans", sans-serif';
                    ctx.fillText(currentVisitorId, paddedCanvas.width / 2, canvas.height + padding + 65);
                    
                    const link = document.createElement('a');
                    link.href = paddedCanvas.toDataURL('image/png');
                    link.download = `${uiText.download_filename} ${currentVisitorId}.png`;
                    link.click();
                }
            }
        </script>

        <div class="card-footer">
            &copy; {{ date('Y') }} PT. Yamaha Musical Products Indonesia
        </div>

    </div>
</div>
@endsection

@section('scripts')
    <script src="{{ url("js/jquery.gritter.min.js") }}"></script>
    <script src="https://code.jquery.com/ui/1.13.2/jquery-ui.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/timepicker/1.3.5/jquery.timepicker.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <script>
        var uiText = {!! json_encode($text) !!};

        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        jQuery(document).ready(function () {
            // Set language toggle based on current language
            const isEnglish = @json($lang === 'en');
            document.getElementById('langToggle').checked = isEnglish;
            resetForm();
            $('#pageTitle').text(@json($text['page_title']));

            $(".datepicker").datepicker({
                dateFormat: "yy-mm-dd",
                minDate: 0,
                beforeShow: function(input, inst) {
                    inst.dpDiv.addClass('custom-datepicker');
                }
            });

            $('#host').select2({
                placeholder: @json($text['host_short_placeholder']),
                allowClear: true,
                width: '100%',
            });
            $('#purpose').select2({
                placeholder: @json($text['purpose_placeholder']),
                allowClear: true,
                width: '100%',
            });

            // Inisialisasi Pemilih Waktu
            $('.timepicker').timepicker({
                timeFormat: 'HH:mm',
                interval: 15,
                minTime: '00:00',
                maxTime: '23:45',
                dynamic: false,
                dropdown: true,
                scrollbar: true
            });

            // Gaya pemilih waktu
            $('<style>').text(`
                .ui-timepicker-wrapper {
                    background-color: #ffffff !important;
                    border: 1px solid #e2dff5 !important;
                    border-radius: 10px !important;
                    box-shadow: 0 8px 32px rgba(96, 92, 168, 0.15) !important;
                    font-family: 'Plus Jakarta Sans', sans-serif !important;
                }

                .ui-timepicker-wrapper .ui-timepicker-list {
                    padding: 8px 0 !important;
                }

                .ui-timepicker-wrapper .ui-timepicker-list li {
                    padding: 8px 12px !important;
                    color: #1e1b3a !important;
                    font-size: 14px !important;
                    font-weight: 500 !important;
                    transition: all 0.2s !important;
                }

                .ui-timepicker-wrapper .ui-timepicker-list li:hover {
                    background-color: #f0eef9 !important;
                    color: #605ca8 !important;
                }

                .ui-timepicker-wrapper .ui-timepicker-list li.ui-timepicker-selected {
                    background-color: #605ca8 !important;
                    color: #ffffff !important;
                }
            `).appendTo('head');
        });
        // Gaya Pemilih Tanggal Kustom

        $('<style>').text(`
            .custom-datepicker {
                background-color: #ffffff !important;
                border: 1px solid #e2dff5 !important;
                border-radius: 10px !important;
                box-shadow: 0 8px 32px rgba(96, 92, 168, 0.15) !important;
                font-family: 'Plus Jakarta Sans', sans-serif !important;
            }

            .custom-datepicker .ui-datepicker-header {
                background-color: #605ca8 !important;
                border: none !important;
                border-radius: 8px 8px 0 0 !important;
                color: #ffffff !important;
            }

            .custom-datepicker .ui-datepicker-title {
                color: #ffffff !important;
                font-weight: 600 !important;
            }

            .custom-datepicker .ui-datepicker-prev, 
            .custom-datepicker .ui-datepicker-next {
                color: #ffffff !important;
                top: 6px !important;
            }

            .custom-datepicker .ui-datepicker-calendar a {
                border-radius: 6px !important;
                color: #1e1b3a !important;
                font-weight: 500 !important;
            }

            .custom-datepicker .ui-datepicker-calendar a:hover {
                background-color: #f0eef9 !important;
                color: #605ca8 !important;
            }

            .custom-datepicker .ui-datepicker-calendar .ui-state-active {
                background-color: #605ca8 !important;
                border: 1px solid #605ca8 !important;
                color: #ffffff !important;
            }

            .custom-datepicker .ui-datepicker-calendar .ui-state-disabled {
                color: #d0c8e8 !important;
            }

            .custom-datepicker th {
                background-color: #f0eef9 !important;
                color: #605ca8 !important;
                font-weight: 600 !important;
                padding: 8px 2px !important;
            }
        `).appendTo('head');

        function returnVisitor() {
            if($('#returning_visitor').val() == '0') {
                $('#returning_visitor').val('1');
                $('#emailPhoneField').show();
                $('#returning_visitor_btn').text({!! json_encode($lang === 'en' ? 'Cancel Refill' : 'Batal Isi Ulang') !!});
                $('#emailPhoneField input[name="email_old"]').focus();
            } else {
                $('#returning_visitor').val('0');
                $('#emailPhoneField').hide();
                $('#returning_visitor_btn').text({!! json_encode($lang === 'en' ? 'Refill from previous visit' : 'Isi ulang dari kunjungan sebelumnya') !!});
            }
        }
        function checkConsent(checkbox) {
            if (checkbox.checked) {
                document.querySelector('.btn-submit').disabled = false;
                document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                document.querySelector('.btn-submit').style.cursor = 'pointer';
            } else {
                document.querySelector('.btn-submit').disabled = true;
                document.querySelector('.btn-submit').style.backgroundColor = '#999999';
                document.querySelector('.btn-submit').style.cursor = 'not-allowed';
                openErrorGritter(uiText.consent_required_title, uiText.consent_required_message);
            }
        }

        function checkEmail(emails) {
            const emailPattern = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
            return emailPattern.test(emails);
        }

        function checkNoKtp(id_name) {
            const ktpPattern = /^[0-9]{16}$/;
            return ktpPattern.test(id_name);
        }

        function confirmVisitor() {
            if (confirm(uiText.confirm_submit)) {
                document.querySelector('.btn-submit').disabled = true;
                document.querySelector('.btn-submit').innerText = uiText.sending;
                document.querySelector('.btn-submit').style.backgroundColor = '#999999';
                document.querySelector('.btn-submit').style.cursor = 'not-allowed';
                document.querySelector('.btn-submit').style.boxShadow = 'none';
                document.querySelector('.btn-submit').style.transform = 'none';
                document.querySelector('.btn-submit').style.pointerEvents = 'none';
                document.querySelector('.btn-submit').style.opacity = '0.7';
                document.querySelector('.btn-submit').style.transition = 'all 0.2s';

                if(checkEmail($('input[name="email"]').val()) == false) {
                    openErrorGritter(uiText.error, uiText.invalid_email);
                    document.querySelector('.btn-submit').disabled = false;
                    document.querySelector('.btn-submit').innerText = uiText.submit;
                    document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                    document.querySelector('.btn-submit').style.cursor = 'pointer';
                    document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                    document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                    document.querySelector('.btn-submit').style.opacity = '1';
                    return false;
                }

                // Validasi end_date tidak boleh lebih dari 1 minggu dari start_date
                const startDate = new Date($('#start_date').val());
                const endDate = new Date($('#end_date').val());
                const maxEndDate = new Date(startDate);
                maxEndDate.setDate(maxEndDate.getDate() + 7);
                var suggestedEndDate = new Date(startDate);
                suggestedEndDate.setDate(suggestedEndDate.getDate() + 7);

                if (endDate > maxEndDate) {
                    openErrorGritter(uiText.error, uiText.end_date_limit + suggestedEndDate.toISOString().split('T')[0]);
                    document.querySelector('.btn-submit').disabled = false;
                    document.querySelector('.btn-submit').innerText = uiText.submit;
                    document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                    document.querySelector('.btn-submit').style.cursor = 'pointer';
                    document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                    document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                    document.querySelector('.btn-submit').style.opacity = '1';
                    return false;
                }


                // Kirim formulir
                const formData = new FormData();
                formData.append('_token', '{{ csrf_token() }}');
                formData.append('returning_visitor', $('#returning_visitor').val());
                formData.append('email_old', $('input[name="email_old"]').val());
                formData.append('email', $('input[name="email"]').val());
                formData.append('company', $('input[name="company"]').val());
                formData.append('start_date', $('#start_date').val());
                formData.append('start_time', $('#start_time').val());
                formData.append('end_date', $('#end_date').val());
                formData.append('end_time', $('#end_time').val());
                formData.append('host', $('#host').val());
                formData.append('purpose', $('#purpose').val());
                formData.append('purpose_details', $('#purpose_details').val());
                formData.append('data_consent', $('input[name="data_consent"]').is(':checked') ? 1 : 0);

                if($('input[name="email"]').val() == '' || $('input[name="company"]').val() == '' || $('#start_date').val() == '' || $('#start_time').val() == '' || $('#end_date').val() == '' || $('#end_time').val() == '' || $('#host').val() == '' || $('#purpose').val() == '' || $('#purpose_details').val() == '') {
                    openErrorGritter(uiText.error, uiText.all_required);
                    document.querySelector('.btn-submit').disabled = false;
                    document.querySelector('.btn-submit').innerText = uiText.submit;
                    document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                    document.querySelector('.btn-submit').style.cursor = 'pointer';
                    document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                    document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                    document.querySelector('.btn-submit').style.opacity = '1';
                    return false;
                }

                // Tambahkan data pengunjung
                var salah = 0;
                $('.visitor-row, #first-visitor').each(function(index) {
                    const id_name = $(this).find(`input[name^="visitors"][name$="[id_name]"]`).val();
                    const full_name = $(this).find(`input[name^="visitors"][name$="[full_name]"]`).val();
                    const phone = $(this).find(`input[name^="visitors"][name$="[phone]"]`).val();
                    const origin = $(this).find(`input[name^="visitors"][name$="[origin]"]`).val();

                    if (id_name && full_name && phone && origin) {
                        formData.append(`visitors[${index}][id_name]`, id_name);
                        formData.append(`visitors[${index}][full_name]`, full_name);
                        formData.append(`visitors[${index}][phone]`, phone);
                        formData.append(`visitors[${index}][origin]`, origin);
                        // if(checkNoKtp(id_name) == false) {
                        //     salah = 1;
                        // }
                    }
                });
                
                // if(salah == 1) {
                //     openErrorGritter('Kesalahan', 'Format No. KTP harus 16 digit angka.');
                //     document.querySelector('.btn-submit').disabled = false;
                //     document.querySelector('.btn-submit').innerText = uiText.submit;
                //     document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                //     document.querySelector('.btn-submit').style.cursor = 'pointer';
                //     document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                //     document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                //     document.querySelector('.btn-submit').style.opacity = '1';
                //     return false;
                // }

                $.ajax({
                    url: '{{ url("input/visitor") }}',
                    method: 'POST',
                    data: formData,
                    processData: false,
                    contentType: false,
                    success: function(response) {
                        if (response.status === 'success') {
                            openSuccessGritter(uiText.success, response.message);
                            showSuccessModal(response.visitor_id);
                        } else {
                            openErrorGritter(uiText.error, response.message);
                            document.querySelector('.btn-submit').disabled = false;
                            document.querySelector('.btn-submit').innerText = uiText.submit;
                            document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                            document.querySelector('.btn-submit').style.cursor = 'pointer';
                            document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                            document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                            document.querySelector('.btn-submit').style.opacity = '1';
                        }
                    },
                    error: function(xhr) {
                        openErrorGritter(uiText.error, uiText.submit_error);
                        document.querySelector('.btn-submit').disabled = false;
                        document.querySelector('.btn-submit').innerText = uiText.submit;
                        document.querySelector('.btn-submit').style.backgroundColor = '#168027';
                        document.querySelector('.btn-submit').style.cursor = 'pointer';
                        document.querySelector('.btn-submit').style.boxShadow = '0 4px 14px rgba(96, 92, 168, 0.35)';
                        document.querySelector('.btn-submit').style.pointerEvents = 'auto';
                        document.querySelector('.btn-submit').style.opacity = '1';
                    }
                });
            }
        }

        function serchOldData() {
            var email = $('#email_old').val();
            if (email) {
                $.ajax({
                    url: '{{ url("search/visitor") }}',
                    method: 'POST',
                    data: {
                        _token: '{{ csrf_token() }}',
                        visitor_id: email
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            console.log(response.data);
                            $('#email').val(response.data.email);
                            $('#company').val(response.data.company);
                            $('#start_date').val(response.data.start_date);
                            $('#start_time').val(response.data.start_time);
                            $('#end_date').val(response.data.end_date);
                            $('#end_time').val(response.data.end_time);
                            $('#host').val(response.data.host);
                            $('#purpose').val(response.data.purpose).trigger('change');
                            $('#purpose_details').val(response.data.purpose_detail);
                            for (let i = 0; i < response.details.length; i++) {
                                if (i === 0) {
                                    $('#visitors_0_id').val(response.details[i].card_id);
                                    $('#visitors_0_name').val(response.details[i].name);
                                    $('#visitors_0_phone').val(response.details[i].phone);
                                    $('#visitors_0_origin').val(response.details[i].origin);
                                } else {
                                    addVisitor();
                                    $(`#visitors_${i}_id`).val(response.details[i].card_id);
                                    $(`#visitors_${i}_name`).val(response.details[i].name);
                                    $(`#visitors_${i}_phone`).val(response.details[i].phone);
                                    $(`#visitors_${i}_origin`).val(response.details[i].origin);
                                }
                            }
                            openSuccessGritter(uiText.data_refilled, uiText.data_refilled_message);
                        } else {
                            openErrorGritter(uiText.not_found, response.message);
                        }
                    },
                    error: function(xhr) {
                        openErrorGritter(uiText.error, uiText.search_error);
                    }
                });
            } else {
                openErrorGritter(uiText.input_required, uiText.email_search_required);
            }
        }

        function resetForm() {
            $('#visitors-container').empty();
            visitorCount = 1;
            $('#first-visitor input').val('');
            $('#returning_visitor').val('0');
            $('#emailPhoneField').hide();
            $('#returning_visitor_btn').text({!! json_encode($lang === 'en' ? 'Refill from previous visit' : 'Isi ulang dari kunjungan sebelumnya') !!});
            $('#company').val('');
            $('#email').val('');
            $('#start_date').val('');
            $('#start_time').val('');
            $('#end_date').val('');
            $('#end_time').val('');
            $('#host').val('');
            $('#purpose').val('').trigger('change');
            $('#purpose_details').val('');
            $('#data_consent').prop('checked', false);
            $('.btn-submit').prop('disabled', true);
            $('.btn-submit').css('background-color', '#999999');
            $('.btn-submit').css('cursor', 'not-allowed');
        }

        function openSuccessGritter(title, message){
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url("images/image-screen.png") }}',
                sticky: false,
                time: '3000'
            });
        }

        function openErrorGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url("images/image-stop.png") }}',
                sticky: false,
                time: '3000'
            });
        }
        function updateLanguage(lang) {
            window.location.href = '{{ url("index/visitor/input") }}/' + lang;
        }

        document.getElementById('langToggle').addEventListener('change', function() {
            const newLang = this.checked ? 'en' : 'id';
            updateLanguage(newLang);
        });
    </script>
@endsection
