<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Diagnosa Produk Molding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        @media print {
            @page {
                margin: 2.2mm 2.2mm 2.2mm 2.2mm;
                size: auto;
            }

            body {
                margin: 0;
                padding: 0;
            }

            tbody {
                page-break-inside: avoid;
            }
        }
        
        body {
            font-family: 'Inter', sans-serif;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            padding: 0px;
            font-size: 12px;
        }
        .container {
            background-color: #ffffff;
            /* border: 1px solid #d1d5db; */
            /* border-radius: 8px; */
            /* box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1); */
            width: 100%;
            max-width: 800px; /* Adjust max-width as needed */
            padding: 10px;
            box-sizing: border-box;
        }
        .border-box-thin {
            border: 1px solid #000;
            box-sizing: border-box;
        }
        .border-box-thick {
            border: 2px solid #000;
            box-sizing: border-box;
        }
        .checkbox-label {
            display: flex;
            align-items: center;
            gap: 4px; /* Space between checkbox and text */
            white-space: nowrap; /* Prevent text wrapping */
        }
        .checkbox-label input[type="checkbox"] {
            margin: 0;
            width: 16px;
            height: 16px;
            flex-shrink: 0; /* Prevent checkbox from shrinking */
        }
        /* Custom styling for numbered list circles */
        .list-circle {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            width: 20px;
            height: 20px;
            border: 1px solid black;
            border-radius: 50%;
            font-size: 0.75rem; /* text-xs */
            font-weight: 500; /* font-medium */
            margin-right: 8px; /* mr-2 */
            flex-shrink: 0;
        }
        .point-table-header {
            background-color: #e5e7eb; /* bg-gray-200 */
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="flex justify-between items-start">
            <div class="text-2xl font-bold text-gray-700">Hasil Inspeksi dan Evaluasi</div>
            <div class="flex items-start">
                <div class="flex">
                    <div class="flex flex-col">
                        <div class="w-28 h-8 border-box-thick flex items-center justify-center text-sm font-bold" style="border-bottom: none; border-right: none">Konfirmasi</div>
                        <div class="w-28 h-10 flex items-center justify-center border-box-thick text-sm" style="border-top-width: 1px; border-right: none;">{{ $data_master[3]->app_name }}</div>
                        <div class="w-28 h-7 flex items-center justify-center border-box-thick text-sm" style="border-top: none; border-right: none;">{{ isset($data_master[3]->app_at) ? $data_master[3]->app_at : '' }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="w-28 h-8 border-box-thick flex items-center justify-center text-sm font-bold" style="border-bottom: none; border-right: none">Konfirmasi</div>
                        <div class="w-28 h-10 flex items-center justify-center border-box-thick text-sm" style="border-top-width: 1px; border-right: none;">{{ $data_master[2]->app_name }}</div>
                        <div class="w-28 h-7 flex items-center justify-center border-box-thick text-xs" style="border-top: none; border-right: none;">{{ isset($data_master[2]->app_at) ? $data_master[2]->app_at : '' }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="w-28 h-8 border-box-thick flex items-center justify-center text-sm font-bold" style="border-bottom: none; border-right: none; border-left-width: 1px">Diperiksa</div>
                        <div class="w-28 h-10 border-box-thick flex items-center justify-center text-sm" style="border-top-width: 1px; border-right: none; border-left-width: 1px;">{{ $data_master[1]->app_name }}</div>
                        <div class="w-28 h-7 border-box-thick flex items-center justify-center text-xs" style="border-top: none; border-right: none; border-left-width: 1px;">{{ isset($data_master[1]->app_at) ? $data_master[1]->app_at : '' }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="w-28 h-8 border-box-thick flex items-center justify-center text-sm font-bold" style="border-bottom: none; border-right: none; border-left-width: 1px">Diperiksa</div>
                        <div class="w-28 h-10 border-box-thick flex items-center justify-center text-sm" style="border-top-width: 1px; border-right: none; border-left-width: 1px;">{{ $data_master[0]->app_name }}</div>
                        <div class="w-28 h-7 border-box-thick flex items-center justify-center text-xs" style="border-top: none; border-right: none; border-left-width: 1px;">{{ isset($data_master[0]->app_at) ? $data_master[0]->app_at : '' }}</div>
                    </div>
                    <div class="flex flex-col">
                        <div class="w-20 h-8 border-box-thick flex items-center justify-center text-sm font-bold" style="border-bottom: none; border-left-width: 1px">Dibuat</div>
                        <div class="w-20 h-10 border-box-thick flex items-center justify-center text-sm" style="border-top-width: 1px; border-left-width: 1px;">{{ $data_master[0]->created_by }}</div>
                        <div class="w-20 h-7 border-box-thick flex items-center justify-center text-xs" style="border-top: none; border-left-width: 1px;">{{ $data_master[0]->creat_at }}</div>
                    </div>
                </div>
            </div>
        </div>

        <div>

        <!-- Standar Ranking Section -->
        <div class="mb-4">
        <div class="flex items-center space-x-2">
            <span class="text-xl font-bold text-gray-700">&#9826; Standar Ranking</span>
        </div>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y border-box-thick">
            <thead class="bg-gray-50">
                <tr>
                <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-black">rangking</th>
                <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black w-1/4">standar penilaian</th>
                <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black">keputusan</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <tr>
                <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900 text-center border border-l-1 border-black">Rank AA</td>
                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 text-center border border-l-1 border-black">90 ~ 100 poin</td>
                <td class="px-2 py-1 text-sm text-gray-700 border border-l-1 border-black">Tidak perlu repair. Cukup dilakukan maintenance periodik atau overhaul</td>
                </tr>
                <tr>
                <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900 text-center border border-l-1 border-black">Rank A</td>
                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 text-center border border-l-1 border-black">70 ~ 89 poin</td>
                <td class="px-2 py-1 text-sm text-gray-700 border border-l-1 border-black">Ada perbaikan sebagian. Atur kembali schedule produksi untuk dilakukan repair dalam waktu dekat.</td>
                </tr>
                <tr>
                <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900 text-center border border-l-1 border-black">Rank B</td>
                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 text-center border border-l-1 border-black">69 ~ 70 poin</td>
                <td class="px-2 py-1 text-sm text-gray-700 border border-l-1 border-black">Ada banyak perbaikan. Perlu repair secepatnya.</td>
                </tr>
                <tr>
                <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900 text-center border border-l-1 border-black">Rank C</td>
                <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 text-center border border-l-1 border-black">29 poin atau kurang</td>
                <td class="px-2 py-1 text-sm text-gray-700 border border-l-1 border-black">Bermasalah untuk melanjutkan produksi. Perlu renewal.</td>
                </tr>
            </tbody>
            </table>
        </div>
        </div>
        
        <!-- Ranking Molding Section -->
        <div class="mb-4">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                <span class="text-xl font-bold text-gray-700 w-full md:w-1/4 flex-shrink-0">&#9826; Ranking Molding</span>
                <span class="text-sm font-medium text-red-500">Note: untuk nilai, isikan nilai yang lebih rendah antara inspeksi molding dan inspeksi produk injeksi</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-box-thick">
                <thead class="bg-gray-50">
                    <tr>
                    <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black">Nama model atau nama molding</th>
                    <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black">Jenis Produk</th>
                    <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black">poin</th>
                    <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black">ranking</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <tr>
                    <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-900 border border-l-1 border-black text-center">{{ $data_master[0]->fixed_asset_name }}</td>
                    <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 border border-l-1 border-black text-center">{{ $data_master[0]->product_category }}</td>
                    <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 border border-l-1 border-black text-center">{{ $data_master[0]->total_score }}</td>
                    <td class="px-2 py-1 whitespace-nowrap text-sm text-gray-700 border border-l-1 border-black text-center">{{ $data_master[0]->rank }}</td>
                    </tr>
                </tbody>
                </table>
            </div>
        </div>

        <div class="mb-4">
            <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
                <span class="text-xl font-bold text-gray-700 w-full md:w-1/4 flex-shrink-0">&#9826; Informasi Mold</span>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200 border-box-thick">
                    <tr>
                        <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black bg-gray-50">Qty Total Produksi</th>
                        <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-1000 border border-l-1 border-black text-center">{{ $data_master[0]->production_qty }}</td>
                        <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black bg-gray-50">Tgl Mulai Produksi</th>
                        <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-1000 border border-l-1 border-black text-center">{{ $data_master[0]->prod_date }}</td>
                        <th scope="col" class="px-2 py-1 text-center text-xs font-medium text-gray-700 uppercase tracking-wider border border-l-1 border-black bg-gray-50">Periode Produksi</th>
                        <td class="px-2 py-1 whitespace-nowrap text-sm font-medium text-gray-1000 border border-l-1 border-black text-center">{{ $data_master[0]->production_period }}</td>
                    </tr>
                </table>
            </div>
        </div>
        
        <!-- Hasil Inspeksi Section -->
        <div>
        <div class="flex flex-col md:flex-row md:items-center space-y-2 md:space-y-0 md:space-x-4">
            <span class="text-xl font-bold text-gray-700">&#9826; Hasil Inspeksi</span>
            <span class="text-sm font-medium text-red-500">Note: isikan dari yang efeknya terhadap produk dan lifetime molding yang lebih tinggi.</span>
        </div>
        <div class="text-base text-gray-700">Penilaian keseluruhan</div>
        </div>
  
        </div>

        <div style="min-height: 220px" class="border-black border-box-thick p-1">
            {!! html_entity_decode($data_master[0]->penilaian_keseluruhan) !!}
        </div>

        <div class="mt-2 text-base text-gray-700">Refleksi dari catatan perbaikan</div>
        <div style="min-height: 130px" class="border-black border-box-thick p-1">
            {!! html_entity_decode($data_master[0]->pertimbangan_histori) !!}
        </div>

        <div class="mt-2 text-base text-gray-700">Usulan Penanganan → Menyertakan perkiraan waktu dan biaya perbaikan</div>
        <div style="min-height: 130px" class="border-black border-box-thick p-1">
            {!! html_entity_decode($data_master[0]->tindakan_perbaikan) !!}
        </div>
    </div>
    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>
    <script>
        //document ready
        $(document).ready(function() {
           //open window print 
           window.print();
        });

        async function cetakSeluruhHTML() {
            const { jsPDF } = window.jspdf;
            const doc = new jsPDF({
                unit: 'mm',
                format: 'a4'
            });

            await doc.html(document.body, {
                callback: function (doc) {
                    doc.save("halaman-lengkap.pdf");
                },
                x: 0,
                y: 0,
                html2canvas: { scale: 1 } // sesuaikan jika halaman besar
            });
        }
    </script>
</body>
</html>
