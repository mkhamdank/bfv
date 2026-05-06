<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Hasil Diagnosa Molding</title>
    <!-- Hapus Tailwind CSS, diganti dengan CSS Manual -->
    <style>
        /* Import Font */
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        /* Global Styles */
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f4f7f9;
            padding: 16px;
            margin: 0;
            display: flex;
            justify-content: center;
        }

        /* Container Utama */
        .container-main {
            /* max-width: 896px; <--- DIHAPUS agar mengisi lebar penuh */
            width: 100%;
            background-color: #ffffff;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05); /* shadow-xl */
            border-radius: 12px;
            overflow: hidden;
        }

        /* Header Otomatis Email */
        .header-auto {
            padding: 16px;
            text-align: center;
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* text-gray-500 */
            background-color: #f9fafb; /* bg-gray-50 */
            border-bottom: 1px solid #e5e7eb;
        }

        /* Bagian Laporan Utama Content */
        .report-content {
            padding: 24px;
        }
        @media (min-width: 768px) {
            .report-content {
                padding: 40px;
            }
        }

        /* Judul Laporan */
        .report-header {
            background-color: #4b1e78; /* bg-blue-800 */
            color: #ffffff;
            padding: 24px;
            border-top-left-radius: 8px;
            border-top-right-radius: 8px;
            margin-bottom: 32px;
        }
        .report-header h1 {
            font-size: 1.875rem; /* text-3xl */
            font-weight: 800; /* font-extrabold */
            text-align: center;
            margin-bottom: 4px;
        }
        .report-header h2 {
            font-size: 1.125rem; /* text-xl */
            font-weight: 500; /* font-medium */
            text-align: center;
            opacity: 0.9;
        }

        /* Bagian Detail Data */
        .detail-data {
            border: 1px solid #e5e7eb;
            border-radius: 8px;
            overflow: hidden;
        }
        .detail-grid {
            display: grid;
            grid-template-columns: 1fr;
        }
        @media (min-width: 768px) {
            .detail-grid {
                grid-template-columns: 1fr 1fr;
            }
        }

        /* Baris Detail */
        .detail-row {
            display: flex;
            align-items: center;
            padding: 16px;
            border-bottom: 1px solid #e5e7eb;
        }
        .detail-row-odd {
            background-color: #eff6ff; /* bg-blue-50 */
        }
        .detail-row-even {
            background-color: #ffffff; /* bg-white */
        }
        /* Mengatur border kanan hanya di desktop */
        @media (min-width: 768px) {
             .detail-grid > div:nth-child(odd) {
                border-right: 1px solid #e5e7eb;
            }
        }
        /* Menghapus border bawah pada baris terakhir di desktop */
        .detail-grid > div:nth-child(5) { border-bottom: none; }
        .detail-grid > div:nth-child(6) { border-bottom: none; }
        @media (min-width: 768px) {
             .detail-grid > div:nth-child(5) { border-right: 1px solid #e5e7eb; }
             .detail-grid > div:nth-child(4) { border-bottom: none; }
        }

        .detail-label {
            width: 33.3333%; /* w-1/3 */
            font-size: 0.875rem; /* text-sm */
            font-weight: 600; /* font-semibold */
            color: #374151; /* text-gray-700 */
        }
        .detail-value {
            width: 66.6667%; /* w-2/3 */
            font-size: 1rem; /* text-base */
            font-weight: 600;
            color: #111827; /* text-gray-900 */
        }
        .detail-value-normal {
            font-weight: 400; /* Untuk Qty Shot dan Tgl Produksi */
        }


        /* Bagian Summary Scorecard */
        .scorecard-summary {
            margin-top: 40px;
        }
        .scorecard-summary h3 {
            font-size: 1.25rem; /* text-xl */
            font-weight: 700; /* font-bold */
            color: #1f2937; /* text-gray-800 */
            margin-bottom: 16px;
            border-bottom: 1px solid #e5e7eb;
            padding-bottom: 8px;
        }
        .scorecard-grid {
            display: grid;
            grid-template-columns: repeat(2, 1fr);
            gap: 16px;
        }
        @media (min-width: 640px) {
            .scorecard-grid {
                grid-template-columns: repeat(4, 1fr);
            }
        }
        
        /* Kartu Score */
        .score-card {
            background-color: #ffffff;
            padding: 12px;
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -2px rgba(0, 0, 0, 0.06); /* shadow-md */
            border-bottom: 4px solid #c69fecff; /* border-blue-500 */
            text-align: center;
            transition: box-shadow 0.3s;
        }
        .score-card:hover {
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1); /* hover:shadow-lg */
        }
        @media (min-width: 640px) {
            .score-card {
                padding: 20px;
            }
        }
        .score-label {
            font-size: 0.875rem; /* text-sm */
            font-weight: 500; /* font-medium */
            color: #4f11c1ff; /* text-gray-500 */
            margin-bottom: 4px;
        }
        .score-value {
            font-size: 1.25rem; /* text-xl */
            font-weight: 800; /* font-extrabold */
            color: #1f2937; /* text-gray-800 */
        }
        @media (min-width: 640px) {
            .score-value {
                font-size: 1.875rem; /* text-3xl */
            }
        }
        .score-value-blue {
            color: #210b37ff; /* text-blue-600 */
        }

        /* Kartu Ranking (Penekanan) */
        .ranking-card {
            background-color: #b7a5c9; /* bg-blue-500 */
            border-bottom: 4px solid #c69fecff; /* border-blue-700 */
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 8px 10px -6px rgba(0, 0, 0, 0.1); /* shadow-lg */
            transition: box-shadow 0.3s;
        }
        .ranking-card:hover {
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25); /* hover:shadow-xl */
        }
        .ranking-card .score-label {
            color: #ffffff;
            opacity: 0.9;
        }
        .ranking-card .score-value {
            color: #ffffff;
        }

        /* Teks Penutup */
        .footer-text {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center;
            font-size: 0.875rem; /* text-sm */
            color: #6b7280; /* text-gray-500 */
        }

        /* Baris Kosong yang disembunyikan di Mobile */
        .hidden-md-block {
            display: none;
        }
        @media (min-width: 768px) {
            .hidden-md-block {
                display: block;
            }
        }

        /* --- STYLES BARU UNTUK TOMBOL AKSI EMAIL --- */
        .action-buttons {
            margin-top: 40px;
            padding-top: 16px;
            border-top: 1px solid #e5e7eb;
            text-align: center; /* Memusatkan tombol jika menggunakan block-level elements */
            
            /* Menggunakan grid untuk tata letak modern yang tetap aman di klien email modern */
            display: grid; 
            grid-template-columns: repeat(2, 1fr);
            gap: 12px;
        }
        
        /* Media query untuk mobile - membuat tombol bertumpuk di layar kecil jika perlu */
        @media (max-width: 480px) {
            .action-buttons {
                grid-template-columns: 1fr; /* 1 kolom di layar yang sangat kecil */
            }
        }
        
        .btn {
            display: inline-block; /* Penting untuk email */
            padding: 12px 16px;
            font-size: 1rem;
            font-weight: 600;
            line-height: 1.2;
            text-align: center;
            text-decoration: none; /* Menghilangkan underline pada link */
            border-radius: 8px;
            cursor: pointer;
            transition: background-color 0.3s, box-shadow 0.3s;
        }

        /* Warna Tombol Approve (Hijau) */
        .btn-approve {
            background-color: #10b981; /* green-500 */
            color: #ffffff;
            border: 1px solid #059669; /* green-600 */
            box-shadow: 0 2px 4px rgba(16, 185, 129, 0.4);
        }
        .btn-approve:hover {
            background-color: #059669; /* green-600 */
        }
        
        /* Warna Tombol Hold (Kuning/Orange) */
        .btn-hold {
            background-color: #f59e0b; /* amber-500 */
            color: #ffffff;
            border: 1px solid #d97706; /* amber-600 */
            box-shadow: 0 2px 4px rgba(245, 158, 11, 0.4);
        }
        .btn-hold:hover {
            background-color: #d97706; /* amber-600 */
        }
        
        /* Warna Tombol Reject (Merah) */
        .btn-reject {
            background-color: #ef4444; /* red-500 */
            color: #ffffff;
            border: 1px solid #dc2626; /* red-600 */
            box-shadow: 0 2px 4px rgba(239, 68, 68, 0.4);
        }
        .btn-reject:hover {
            background-color: #dc2626; /* red-600 */
        }
        /* --- END STYLES BARU --- */

        /* Warna Tombol Attachment (Light Blue/Gray) */
        .btn-attachment {
            background-color: #dbeafe; /* blue-100 */
            color: #1e3a8a; /* blue-800 */
            border: 1px solid #93c5fd; /* blue-300 */
            box-shadow: 0 2px 4px rgba(219, 234, 254, 0.4);
            margin-bottom: 20px; /* Jarak antara tombol attachment dan tombol aksi */
            display: block; /* Agar mengambil lebar penuh di atas tombol grid */
        }
        .btn-attachment:hover {
            background-color: #bfdbfe; /* blue-200 */
        }
    </style>
</head>
<body class="p-4 md:p-8">

    <!-- Container Utama (Simulasi Email/Laporan) -->
    <div class="container-main">

        <!-- Header Otomatis Email -->
        <div class="header-auto">
            <!-- Komentar: Teks informasi pengiriman otomatis -->
            <p>Ini adalah email otomatis dari sistem YMPI's MIRAI. Mohon untuk tidak membalas ke alamat ini.</p>
        </div>

        <!-- Bagian Laporan Utama -->
        <div class="report-content">
            
            <!-- Judul Laporan -->
            <header class="report-header">
                <h1>Laporan Pengiriman Molding dari Vendor</h1>
                <h2>PT Yamaha Musical Products Indonesia</h2>
            </header>

            <!-- Bagian Detail Data -->
            @if($data['status'] == 'holded')
            <div class="detail-data" style="border: 1px solid #4475efff;"> <!-- Mengubah warna border menjadi biru -->
                <div class="detail-grid">
                    <!-- Baris 1: Nama Mold (Ganjil, Kiri) -->
                    <div class="detail-row detail-row-odd">
                        <span class="detail-label" style="width: 100%; text-align: center; font-size: 20px; font-weight: bold; color: #4475efff;">APPROVAL ANDA DITUNDA</span>
                        <!-- <span class="detail-value detail-value-normal"></span> -->
                    </div>
                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Ditunda Oleh</span>
                        <span class="detail-value detail-value-normal">: {{ $data['approval'] }}</span>
                    </div>
                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Komentar</span>
                        <span class="detail-value detail-value-normal">: {{ $data['comment'] }}</span>
                    </div>
                </div>
            </div>
            <br>
            @elseif($data['status'] == 'rejected')
            <div class="detail-data" style="border: 1px solid #ef4444;"> <!-- Mengubah warna border menjadi biru -->
                <div class="detail-grid">
                    <!-- Baris 1: Nama Mold (Ganjil, Kiri) -->
                    <div class="detail-row detail-row-odd">
                        <span class="detail-label" style="width: 100%; text-align: center; font-size: 20px; font-weight: bold; color: #ef4444;">APPROVAL ANDA DI TOLAK</span>
                        <!-- <span class="detail-value detail-value-normal"></span> -->
                    </div>
                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Ditolak Oleh</span>
                        <span class="detail-value detail-value-normal">: {{ $data['approval'] }}</span>
                    </div>
                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Komentar</span>
                        <span class="detail-value detail-value-normal">: {{ $data['comment'] }}</span>
                    </div>
                </div>
            </div>
            <br>
            @elseif($data['status'] == 'approval_5')
            <div>
                <center><p style="color: #16a34a; font-size: 25px; font-weight: bold;">✅ Approval Sudah Disetujui Penuh</p></center>
            </div>
            <br>
            @endif
            <div class="detail-data">
                <!-- Komentar: Tabel detail menggunakan layout grid/flex untuk responsivitas -->

                <div class="detail-grid">
                    <!-- Baris 1: Nama Mold (Ganjil, Kiri)-->
                     <div class="detail-row detail-row-odd">
                        <span class="detail-label">Nomor Molding</span>
                        <span class="detail-value detail-value-normal">: {{ $data['data_form_molding']->fixed_asset_number }}</span>
                    </div>

                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Nama Molding</span>
                        <span class="detail-value detail-value-normal">: {{ $data['data_form_molding']->molding_name }}</span>
                    </div>
                    <!-- Baris 1: Jenis Produk (Ganjil, Kanan) -->
                    <div class="detail-row detail-row-odd">
                        <span class="detail-label">Nama Vendor</span>
                        <span class="detail-value detail-value-normal">: {{ $data['data_form_molding']->vendor }}</span>
                    </div>

                    <!-- Baris 1: Jenis Produk (Ganjil, Kanan) -->
                    <div class="detail-row detail-row-even">
                        <span class="detail-label">Status Kawasan</span>
                        <span class="detail-value detail-value-normal">: {{ $data['data_form_molding']->status_kawasan }}</span>
                    </div>

                    <!-- Baris 2: Qty Shot (Genap, Kiri) -->
                    <div class="detail-row detail-row-odd">
                        <span class="detail-label">Tanggal Pengiriman</span>
                        <span class="detail-value detail-value-normal">: {{ $data['data_form_molding']->tgl_pengiriman }}</span>
                    </div>
                    <!-- Baris Kosong (Ganjil, Kanan, hanya terlihat di desktop) -->
                    <div class="detail-row detail-row-even hidden-md-block" style="border-bottom: none;"></div>
                </div>

            </div>
            
            @php
                $document = json_decode($data['data_form_molding']->document);
            @endphp

            @foreach($document as $doc)
                @php
                    $docArray = (array) $doc;
                    $key   = array_key_first($docArray);
                    $value = $docArray[$key];
                @endphp

                <a href="{{ url('workshop/molding/file_pengiriman/'.$key.'/'.$value) }}"
                class="btn btn-attachment"
                style="margin-top: 20px; margin-bottom: 10px;">
                    📃 {{ ucwords(str_replace('_', ' ', $key)) }}
                </a>
            @endforeach
            <!-- END BARU: Tombol Attachment -->

             <!-- BARU: Tombol Aksi -->
            @if($data['status'] != 'approval_5' && $data['status'] != 'holded' && $data['status'] != 'rejected')
            <div class="action-buttons">

                <!-- Tombol Approve (Hijau) -->
                <a href="{{ url('approval/molding/pengiriman/approve/'.$data['data_form_molding']->id) }}" class="btn btn-approve">Approve</a>
                
                <!-- Tombol Reject (Merah) -->
                <a href="{{ url('approval/molding/pengiriman/reject_view/'.$data['data_form_molding']->id) }}" class="btn btn-reject">Reject</a>
            </div>
            @endif
            <!-- END BARU: Tombol Aksi -->
            
            <!-- Teks Penutup Opsional -->
            <div class="footer-text">
                <p>Dokumen ini dihasilkan berdasarkan data terbaru dari sistem MIRAI.</p>
            </div>

        </div>
    </div>

</body>
</html>
