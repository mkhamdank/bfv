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
    </style>
@endsection

@section('content')
<div class="login-page">
    <div class="login-card">

        {{-- Judul --}}
        <div class="form-heading">
            <h1>Safety Induction</h1>
            <p>Sistem Manajemen Pengunjung</p>
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
        <label>No. KTP</label>
        <div class="field-inner">
            <i class="f-icon">🆔</i>
            <input type="text" name="identity_number" placeholder="Masukkan No. KTP" required  inputmode="numeric" pattern="[0-9]*" required onkeyup="this.value = this.value.replace(/[^0-9]/g, '');">
        </div>
        </div>

        <div class="field">
        <label>Nama</label>
        <div class="field-inner">
            <i class="f-icon">👤</i>
            <input type="text" name="name" placeholder="Masukkan Nama Lengkap" required>
        </div>
        </div>

        <div class="field">
        <label>Tanggal Mulai</label>
        <div class="field-inner">
            <i class="f-icon">📅</i>
            <input type="text" name="start_date" readonly>
        </div>
        </div>

        <div class="field">
        <label>Tanggal Berakhir</label>
        <div class="field-inner">
            <i class="f-icon">📅</i>
            <input type="text" name="end_date" readonly>
        </div>
        </div>

        {{-- Persetujuan Data Pribadi --}}
        <div style="margin-bottom: 20px; padding: 12px 14px; background: #faf9ff; border: 1.5px solid #e2dff5; border-radius: 10px; transition: all 0.2s;">
            <label style="display: flex; align-items: flex-start; gap: 10px; font-weight: 500; cursor: pointer; user-select: none; margin: 0;">
                <input type="checkbox" name="data_consent" value="1" style="width: 18px; height: 18px; cursor: pointer; accent-color: #605ca8; margin-top: 3px; flex-shrink: 0;" required onchange="checkConsent(this)">
                <div>
                    <span style="color: #1e1b3a; font-size: 14px; display: block; margin-bottom: 6px;">Saya setuju dengan penggunaan data pribadi</span>
                    <p style="font-size: 12px; color: #8b87b5; margin: 0; font-weight: 400; line-height: 1.4;">Data pribadi Anda akan digunakan untuk keperluan perusahaan dan dokumentasi keselamatan. Informasi ini tidak akan didistribusikan kepada pihak ketiga dan akan ditangani sesuai dengan kebijakan perlindungan data.</p>
                </div>
            </label>
            @error('data_consent')
                <div class="field-error" style="margin-top: 8px; padding-left: 28px;">{{ $message }}</div>
            @enderror
        </div>

        <button onclick="saveInduction()" class="btn-submit">Simpan</button>

        <script>
            document.addEventListener('DOMContentLoaded', function() {
            const today = new Date();
            const nextYear = new Date(today.getFullYear() + 1, today.getMonth(), today.getDate());
            
            const formatDate = (date) => date.toISOString().split('T')[0];
            
            document.querySelector('input[name="start_date"]').value = formatDate(today);
            document.querySelector('input[name="end_date"]').value = formatDate(nextYear);
            });
        </script>

        <div class="card-footer">
            &copy; {{ date('Y') }} PT. Yamaha Musical Products Indonesia
        </div>
    </div>
</div>
@endsection

@section('scripts')
    <script>
        jQuery(document).ready(function () {
            $('#pageTitle').text('Sistem Manajemen Kunjungan');
            clearForm();
        });

        function clearForm() {
            document.querySelector('input[name="identity_number"]').value = '';
            document.querySelector('input[name="name"]').value = '';
            document.querySelector('input[name="data_consent"]').checked = false;
            document.querySelector('.btn-submit').disabled = true;
            document.querySelector('.btn-submit').style.backgroundColor = '#999999';
            document.querySelector('.btn-submit').style.cursor = 'not-allowed';
        }

        function saveInduction() {
            const identityNumber = document.querySelector('input[name="identity_number"]').value.trim();
            const name = document.querySelector('input[name="name"]').value.trim();
            const dataConsent = document.querySelector('input[name="data_consent"]').checked;

            if (!identityNumber || !name || !dataConsent) {
                alert('Harap lengkapi semua bidang dan setujui penggunaan data pribadi.');
                return;
            }

            // Kirim data ke server menggunakan AJAX
            $.ajax({
                url: '{{ url("input/visitor/safety_induction") }}',
                method: 'POST',
                data: {
                    card_id: identityNumber,
                    name: name,
                    data_consent: dataConsent ? 1 : 0,
                    start_induction: document.querySelector('input[name="start_date"]').value,
                    end_induction: document.querySelector('input[name="end_date"]').value,
                    _token: '{{ csrf_token() }}'
                },
                success: function(response) {
                    if (response.status) {
                        alert('Data berhasil disimpan.');
                        clearForm();
                        location.reload(); // Muat ulang halaman untuk menampilkan data terbaru
                    } else {
                        alert(response.message || 'Terjadi kesalahan saat menyimpan data.');
                        location.reload(); // Muat ulang halaman untuk menampilkan data terbaru
                    }
                },
                error: function() {
                    alert('Terjadi kesalahan saat menyimpan data.');
                }
            });
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
                alert('Persetujuan Diperlukan: Anda harus menyetujui penggunaan data pribadi sebelum mengirim.');
            }
        }
    </script>
@endsection
