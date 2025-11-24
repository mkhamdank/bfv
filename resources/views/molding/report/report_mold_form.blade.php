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
            tbody {
                page-break-inside: avoid;
            }
        }

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
            <div class="text-xl">Lembar Diagnosa Molding</div>
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
                <span class="text-lg ">Nama Molding</span>
            </div>
            <div class="flex items-center ml-6 border-b border-black">
                <span class="text-lg mr-2">&#9654;</span>
                <span class="text-lg ">{{ $molding_name->fixed_asset_name }}</span>
            </div>
        </div>

        <!-- Standar Diagnosa Section -->
            <div class="w-full">
                <div class="text-lg">
                    <span class="text-lg mr-2">&diamond;</span>
                    <span class="text-lg ">Standar Penilaian</span>
                </div>
                <div class="ml-4">
                    <table class="w-full border-black border-box-thin">
                        <tr class="point-table-header bg-gray-100">
                            <th class="border-black border-box-thin text-center" style="width: 10%">Kategori</th>
                            <th class="border-black border-box-thin text-center" style="width: 68%">Keterangan</th>
                            <th class="border-black border-box-thin text-center">Poin Pengurangan</th>
                        </tr>  
                        <tr>
                            <td class="text-center border-black border-box-thin">OK</td>
                            <td class="text-left border-black border-box-thin px-1">・Yang tidak ada masalah. <br> ・Kondisi awal dapat dipelihara konsistensinya.</td>
                            <td class="border-black border-box-thin text-center">0 Poin</td>
                        </tr>
                        <tr>
                            <td class="text-center border-black border-box-thin">NG</td>
                            <td class="text-left border-black border-box-thin px-1">・Yang ada masalah. <br> ・Yang harus direpair setelah menyesuaikan schedule lagi, atau yang harus diganti part-nya. <br> ・Tidak bisa direpair.</td>
                            <td class="border-black border-box-thin text-center"><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ff99ff;">&nbsp;</span> = -10 poin, <br><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ffffff;">&nbsp;</span> = -5 poin</td>
                        </tr>
                        <tr>
                            <td class="text-center border-black border-box-thin">OK Sementara</td>
                            <td class="text-left border-black border-box-thin px-1">・Yang masalah nya dapat diselesaikan dengan repair. (Sedang diobservasi prosesnya)<br> ・Ada resiko mempengaruhi stabilitas produksi kedepannya.</td>
                            <td class="border-black border-box-thin text-center"><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ff99ff;">&nbsp;</span> = -3 poin, <br><span class="inline-block w-3 h-3 text-center align-middle border border-black rounded-sm text-sm font-bold" style="background-color: #ffffff;">&nbsp;</span> = -1 poin</td>
                        </tr>
                    </table>
                </div>
            </div>

        <!-- Posisi NG Section -->
        <div>
            <div class="flex items-center">
                <span class="text-lg font-bold mr-2">&diamond;</span>
                <span class="text-lg">Item Diagnosa</span>
            </div>
        </div>

        <!-- Diagnosis Table Section -->
            <div>
                <table class="w-full border-box-thin">
                    @foreach ($checklist as $item)
                    <tbody style="page-break-inside: avoid;">
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
                        <tr class="border-b border-black border-box-thick">
                            <td class="px-2 border-r border-black text-center" style="background-color: {{ $color }}; width: 1%">{{ $item->id }}</td>
                            <td class="px-2 border-r border-black" style="width: 25%">{{ $item->item_ng }}</td>
                            <td class="px-2 border-r border-black" style="width: 7%">Hasil Diagnosa</td>
                            <td class="px-2" style="width: 15%">
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK' ? 'checked' : '' }}>OK</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="NG" class="mr-1 prevent-click" {{ $item->diagnose_result == 'NG' ? 'checked' : '' }}>NG</label>
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="diagnosis_{{ $loop->iteration }}[]" value="OK Sementara" class="mr-1 prevent-click" {{ $item->diagnose_result == 'OK Sementara' ? 'checked' : '' }}>OK Sementara</label>
                            </td>
                        </tr>
                        @if ($item->daerah_ng)
                        <tr>
                            <td colspan="2" class="px-2 border border-black border-l-2"></td>
                            <td class="px-2 border border-black">NG daerah</td>
                            <td class="px-2 border border-black border-r-2">
                                @php
                                    $daerah_ng = $item->daerah_ng;
                                    $daerah_ng = explode(', ', $daerah_ng);
                                @endphp

                                @foreach ($daerah_ng as $daerah)
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="daerah_{{ $daerah }}_{{ $loop->iteration }}[]" value="{{ $daerah }}" class="mr-1 prevent-click" {{ in_array($daerah, explode(', ', $item->parts)) ? 'checked' : '' }}>{{ $daerah }}</label>
                                @endforeach
                            </td>
                        </tr>
                        @endif
                        <tr>
                            <td class="px-2 border border-black border-l-2">Item</td>
                            <td colspan="3" class="px-2 border border-black border-r-2">
                            @php
                                    $item_cek = $item->item_check;
                                    $item_cek = explode(', ', $item_cek);
                                @endphp

                                @foreach ($item_cek as $itemz)
                                <label class="checkbox-label inline-flex items-center mr-2"><input type="checkbox" name="item_cek_{{ $loop->iteration }}[]" value="{{ $itemz }}" class="mr-1 prevent-click" {{ in_array($itemz, explode(', ', $item->item_name)) ? 'checked' : '' }}>{{ $itemz }}</label>
                                @endforeach
                            </td>
                        </tr>
                        <tr>
                            <td colspan="4" class="px-2 border-black border-l-2 border-r-2">Rincian lain : {{ $item->note }}</td>
                        </tr>
                    </tbody>
                        @endforeach
                </table>
            </div>
    </div>
    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>

    <script>
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
