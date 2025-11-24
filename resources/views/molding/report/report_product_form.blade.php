<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lembar Diagnosa Produk Molding</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Inter', sans-serif;
            background-color: #f3f4f6;
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
        <!-- Header Section -->
        <div class="flex justify-between items-start">
            <div class="text-xl">Lembar Diagnosa Produk Molding</div>
            <div class="flex items-start gap-4">
                <!-- Point Box -->
                <div class="flex flex-col">
                    <div class="w-28 h-8 border-box-thick flex items-center justify-center text-sm" style="border-bottom: none;">Poin</div>
                    <div class="w-28 h-10 flex items-center justify-center border-box-thick text-3xl" style="background-color: #daeef3; border-top-width: 1px">{{ $molding_name->points }}</div>
                </div>
                <!-- Dibuat Boxes -->
                <div class="flex">
                    <div class="flex flex-col">
                        <div class="w-20 h-8 border-box-thick flex items-center justify-center text-sm" style="border-bottom: none;">Dibuat</div>
                        <div class="w-20 h-10 border-box-thick flex items-center justify-center text-sm" style="border-top-width: 1px;">{{ $molding_name->check_by }}</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Nama Model / Mold Section -->
        <div>
            <div class="flex items-center">
                <span class="text-lg mr-2">&diamond;</span>
                <span class="text-lg ">Nama Model atau Nama Mold</span>
            </div>
            <div class="flex items-center ml-6 border-b border-black">
                <span class="text-lg mr-2">&#9654;</span>
                <span class="text-lg ">{{ $molding_name->fixed_asset_name }}</span>
            </div>
        </div>

        <!-- Standar Diagnosa Section -->
        <div>
            <div class="text-lg">
                <span class="text-lg  mr-2">&diamond;</span>
                <span class="text-lg ">Standar Diagnosa</span>
            </div>
            <div class="ml-4">
                <table class="w-full">
                    <tr>
                        <th style="width: 15%" class="text-left">OK</th>
                        <th style="width: 20px" class="text-left">:</th>
                        <td>Yang tidak ada masalah. → Tidak ada masalah berdasarkan hasil keputusan QA.</td>
                    </tr>
                    <tr>
                        <th class="text-left">NG</th>
                        <th class="text-left">:</th>
                        <td>Ada masalah.</td>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;" class="text-left">OK Sementara</th>
                        <th style="vertical-align: top;" class="text-left">:</th>
                        <td>Berdasarkan poin saat ini, QA memutuskan tidak ada masalah, tetapi kedepannya akan menjadi masalah pada saat melanjutkan produksi. <br> Ada penanganan lanjutan setelah dilakukan injection.</td>
                    </tr>
                </table>
            </div>
        </div>

        <!-- Posisi NG Section -->
        <div>
            <div class="flex items-center">
                <span class="text-lg font-bold mr-2">&diamond;</span>
                <span>Posisi NG pada produk hasil injection serta item nya</span>
            </div>
            <div class="ml-6 text-sm">
                <span class="font-bold">Note: Gunakan sheet lain jika ingin melampirkan foto dll bagian detail nya ! </span>
            </div>
            <div class="w-full h-64 border-box-thin bg-gray-100 flex items-center justify-center text-gray-400 text-sm">
                @if ($molding_name->photo_product)
                    <img src="{{ url('workshop/molding/photo_product/main/' . $molding_name->photo_product) }}" alt="Photo Product" class="w-full h-full object-contain">
                @else
                    [Area untuk gambar/foto]
                @endif
            </div>
        </div>

        <!-- Item NG Injection Section -->
        <div>
            <div class="flex items-center mb-2">
                <span class="text-lg font-bold mr-2">&diamond;</span>
                <span>Item NG injection</span>
                <div class="ml-4" style="flex: 1">
                    <table class="w-full">
                        <tr>
                            <td style="width: 20%">OK</td>
                            <td style="width: 20px">:</td>
                            <td style="width: 25%">0 poin</td>
                            <td class="mr-2">NG</td>
                            <td>:</td>
                            <td><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ff99ff;">&nbsp;</span> = -10 poin, <span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #00b0f0;">&nbsp;</span> = -5 poin, <span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ffffff;">&nbsp;</span> = 0 poin</td>
                        </tr>
                        <tr>
                            <td>OK Sementara</td>
                            <td>:</td>
                            <td colspan="4"><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ff99ff;">&nbsp;</span> = -3 poin, <span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #00b0f0;">&nbsp;</span> = -1 poin, <span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ffffff;">&nbsp;</span> = 0 poin</td>
                        </tr>
                    </table>
                </div>
            </div>
        </div>

        <!-- Diagnosis Table Section -->
        <div class="grid grid-cols-2 gap-4">
            <!-- Left Column -->
            <div>
                <table class="w-full border-box-thin">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 border-r border-box-thin border-black">No</th>
                            <th class="px-2 border-r border-box-thin border-black">Item NG</th>
                            <th class="px-2 border-box-thin border-black w-2/3">Hasil diagnosa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checklist as $item)
                        @if ($loop->iteration < 15)
                        @php
                            $rowspan = 1;
                        @endphp
                        @if ($item->category_check == 'Isi')
                            @php
                                $rowspan = 2;
                            @endphp
                        @endif

                        @if ($item->grouping == 'Main')
                            @php
                                $color = '#ff99ff';
                            @endphp
                        @elseif ($item->grouping == 'Secondary')
                            @php
                                $color = '#00b0f0';
                            @endphp
                        @else
                            @php
                                $color = '#ffffff';
                            @endphp
                        @endif
                        <tr class="border-b border-black">
                            <td class="px-2 border-r border-black" rowspan="{{ $rowspan }}" style="background-color: {{ $color }}">{{ $loop->iteration }}</td>
                            <td class="px-2 border-r border-black" rowspan="{{ $rowspan }}">{{ $item->item_ng }}</td>
                            @if ($item->category_check == 'Isi')
                            <td class="px-2">
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="10" class="mr-1 prevent-click" {{ $item->actual_deduction == '10' ? 'checked' : '' }}>1 ~ 10 : -10</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="30" class="mr-1 prevent-click" {{ $item->actual_deduction == '30' ? 'checked' : '' }}>31 ~ 50 : -30</label>
                            </td>
                            @else
                            <td class="px-2">
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK' ? 'checked' : '' }}>OK</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="NG" class="mr-1 prevent-click" {{ $item->diagnose_result == 'NG' ? 'checked' : '' }}>NG</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK Sementara" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK Sementara' ? 'checked' : '' }}>OK Sementara</label>
                            </td>
                            @endif
                        </tr>
                        @if ($item->category_check == 'Isi')
                        <tr class="border-b border-black">
                            <td class="px-2">
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="20" class="mr-1 prevent-click" {{ $item->actual_deduction == '20' ? 'checked' : '' }}>11 ~ 30 : -20</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="50" class="mr-1 prevent-click" {{ $item->actual_deduction == '50' ? 'checked' : '' }}>> 51 : -50</label>
                            </td>
                        </tr>
                        @endif
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>

            <!-- Right Column -->
            <div>
                <table class="w-full border-box-thin">
                    <thead>
                        <tr class="bg-gray-100">
                            <th class="px-2 border-r border-box-thin border-black">No</th>
                            <th class="px-2 border-r border-box-thin border-black">Item NG</th>
                            <th class="px-2 border-box-thin border-black w-2/3">Hasil diagnosa</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($checklist as $item)
                        @if ($loop->iteration >= 15)
                        <tr class="border-b border-black">
                            <td class="px-2 border-r border-black" style="background-color: #ffffff">{{ $loop->iteration }}</td>
                            <td class="px-2 border-r border-black">{{ $item->item_ng }}</td>
                            <td class="px-2">
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK' ? 'checked' : '' }}>OK</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="NG" class="mr-1 prevent-click" {{ $item->diagnose_result == 'NG' ? 'checked' : '' }}>NG</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK Sementara" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK Sementara' ? 'checked' : '' }}>OK Sementara</label>
                            </td>
                        </tr>
                        @endif
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
        <!-- <button onclick="generatePDF()" style="position:fixed;bottom:20px;right:20px;">Download PDF</button> -->
    </div>
    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/html2canvas/1.4.1/html2canvas.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.5.1/jspdf.umd.min.js"></script>

    <script>
        async function generatePDF() {
            const { jsPDF } = window.jspdf;

            const doc = new jsPDF({
                unit: 'mm',
                format: 'a4',
                orientation: 'portrait'
            });

            await doc.html(document.querySelector('.container'), {
                callback: function (doc) {
                    doc.save("full-body.pdf");
                },
                x: 0,
                y: 0,
                width: 210, // A4 width in mm
                html2canvas: {
                    scale: 1  // Lower scale = smaller output
                }
            });
        }

        //document ready
        $(document).ready(function() {
           //open window print 
           window.print();

        //    generatePDF();
        });

        document.querySelectorAll('.prevent-click').forEach(el => {
            el.addEventListener('click', e => e.preventDefault());
        });
    </script>
</body>
</html>
