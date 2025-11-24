<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reminder Shot Molding</title>
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
                            ">Laporan Hasil Diagnosa Molding</h1>
                            
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
                                <b style="color: #dc3545;">Perhatian:</b> Berikut daftar molding di <b>{{ $data[0]->vendor }}</b> yang sudah melewati standard shot.
                            </p>
                            <p style="margin-bottom: 20px; color: #555555; font-size: 16px;">
                                Mohon segera ditindak lanjuti dengan melakukan diagnosa molding dan mengisi form diagnosa molding.
                            </p>
                        </td>
                    </tr>

                    <!-- Data Table Content -->
                    <tr>
                        <td style="padding: 10px 40px 30px 40px;">
                            <!-- Data Table -->
                            <table width="100%" border="1" cellspacing="0" cellpadding="12" style="border-collapse: collapse; border: 1px solid #dddddd;">
                                <thead>
                                    <tr style="background-color: #e9ecef; text-align: left; font-weight: bold;">
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">No Fixed Asset</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Nama Molding</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Standard Shot</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Total Shot</th>
                                        <th style="border: 1px solid #dddddd; font-size: 14px; color: #333333;">Tgl Melebihi Standard</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($data as $item)
                                        <tr style="background-color: {{ $loop->odd ? '#ffffff' : '#f8f9fa' }};">
                                            <td style="border: 1px solid #dddddd; color: #555555; font-size: 14px;">{{ $item->fixed_asset_number }}</td>
                                            <td style="border: 1px solid #dddddd; color: #555555; font-size: 14px;">{{ $item->fixed_asset_name }}</td>
                                            <td style="border: 1px solid #dddddd; color: #555555; font-size: 14px;"><center>{{ number_format($item->standard_shot, 0, ',', '.') }}</center></td>
                                            <td style="border: 1px solid #dddddd; color: #555555; font-size: 14px;"><center>{{ number_format($item->total_shot, 0, ',', '.') }}</center></td>
                                            <td style="border: 1px solid #dddddd; color: #555555; font-size: 14px;"><center>{{ date('d M Y', strtotime($item->first_exceed_date)) }}</center></td>
                                        </tr>
                                    @endforeach
                                </tbody>
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