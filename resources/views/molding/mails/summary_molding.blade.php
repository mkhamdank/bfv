<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Summary Molding</title>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@100..900&display=swap');

        body {
            font-family: 'Inter', sans-serif;
        }
    </style>
</head>
<!-- Menggunakan inline style untuk kompatibilitas maksimal di klien email -->
<body style="margin: 0; padding: 0; background-color: #f0f3f8; font-family: Arial, sans-serif; line-height: 1.6;">
    <!-- TABLE UTAMA: Untuk centering dan background page -->
    <table width="100%" border="0" cellspacing="0" cellpadding="0" style="background-color: #f0f3f8;">
        <tr>
            <td align="center" style="padding: 20px 0;">

                <!-- WRAPPER KONTEN EMAIL: Batasan lebar 600px, standar untuk email -->
                <table width="800" border="0" cellspacing="0" cellpadding="0" style="background-color: #ffffff; border-radius: 12px; overflow: hidden; box-shadow: 0 6px 15px rgba(0, 0, 0, 0.1);">
                    
                    <!-- Pre-Header (Teks peringatan MIRAI) - Diubah sesuai gaya yang Anda berikan -->
                    <tr>
                        <td style="
                            padding: 16px 40px; /* Padding di sekitar teks, 16px vertikal, 40px horizontal */
                            text-align: center; 
                            font-size: 12px; /* text-sm setara dengan 0.875rem */
                            color: #6b7280; /* text-gray-500 */
                            background-color: #f9fafb; /* bg-gray-50 */
                            border-bottom: 1px solid #e5e7eb;
                        ">
                            <!-- Teks Peringatan Otomatis -->
                            <p style="margin: 0;">Ini adalah email otomatis dari sistem YMPI's MIRAI. Mohon untuk tidak membalas ke alamat ini.</p>
                        </td>
                    </tr>

                    <!-- Header Banner -->
                    <tr>
                        <td style="
                            padding: 30px 40px; 
                            background-color: #4b1e78; /* Warna ungu dari kode CSS Anda */
                            color: #ffffff; 
                            /* border-top-left-radius dan border-top-right-radius dihapus di sini karena sudah ada di elemen tabel wrapper */
                            text-align: center;
                        ">
                            <!-- H1 sesuai gaya CSS Anda (font-size: 1.875rem / 28px, font-weight: bold) -->
                            <h1 style="
                                font-size: 28px; 
                                margin: 0 0 5px 0; 
                                font-weight: bold; 
                                line-height: 1.2;
                                color: #ffffff;
                            ">Laporan Diagnosa Molding</h1>
                            
                            <!-- H2 sesuai gaya CSS Anda (font-size: 1.125rem / 16px, opacity: 0.9) -->
                            <p style="
                                font-size: 16px; 
                                margin: 0; 
                                font-weight: normal; 
                                opacity: 0.9;
                                color: #ffffff;
                            ">PT Yamaha Musical Products Indonesia</p>
                        </td>
                    </tr>
                    
                    <!-- Body Introduction (Diubah menjadi informasi ringkas & jumlah item kosong) -->
                    <tr>
                        <td style="padding: 30px 40px 10px 40px;">
                            <p style="margin-top: 0; margin-bottom: 20px; color: #333333; font-size: 16px;">
                                Report Summary Molding di Area <b style="color: #dc3545;">{{ $data['vendor'] }}</b> bulan {{ $data['month'] }}. Dengan jumlah molding : <b>{{ $data['total_molding'] }}</b> molding.
                            </p>
                        </td>
                    </tr>

                    <!-- Data Table Content -->
                    <tr>
                        <td style="padding: 10px 40px 30px 40px;">
                            <!-- Data Table -->
                            @php
                                $jumlah_ok = 0;
                                $jumlah_sudah_diperiksa = 0;
                                $jumlah_sedang_diperiksa = 0;
                                $jumlah_perlu_pemeriksaan = 0;
                                
                                foreach ($data['daftar_cek'] as $item_cek) {
                                    if ($item_cek->status == 'OK') {
                                        $jumlah_ok++;
                                    } elseif ($item_cek->status == 'Sudah Diperiksa') {
                                        $jumlah_sudah_diperiksa++;
                                    } elseif ($item_cek->status == 'Butuh Pemeriksaan') {
                                        $jumlah_perlu_pemeriksaan++;
                                    } elseif ($item_cek->status == 'Sedang Diperiksa') {
                                        $jumlah_sedang_diperiksa++;
                                    }
                                }
                            @endphp
                            <table width="100%" border="1" cellspacing="0" cellpadding="12" style="border-collapse: collapse; border: 1px solid #dddddd;">
                                <thead>
                                    <tr style="background-color: #e9ecef; text-align: left; font-weight: bold;">
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Status Molding</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Perlu Pemeriksaan</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Sedang Diperiksa</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Sudah Diperiksa</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">OK</th>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Jumlah Molding</th>
                                        <td style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">{{ $jumlah_perlu_pemeriksaan }}</td>
                                        <td style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">{{ $jumlah_sedang_diperiksa }}</td>
                                        <td style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">{{ $jumlah_sudah_diperiksa }}</td>
                                        <td style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">{{ $jumlah_ok }}</td>
                                    </tr>
                                </thead>
                                </table>
                                
                                <!-- Tombol Aksi -->
                            <p style="text-align: center; margin-top: 30px;">
                                <a href="{{ url('index/diagnose_molding/molding_list') }}" style="display: inline-block; padding: 10px 20px; background-color: #28a745; color: #ffffff; text-decoration: none; border-radius: 5px; font-weight: bold; font-size: 14px;">
                                    Lihat dan Buat Diagnosa Form
                                </a>
                            </p>

                        </td>
                    </tr>

                </table>
                <!-- End Wrapper Konten Email -->

            </td>
        </tr>
    </table>
    <!-- End Table Utama -->

</body>
</html>