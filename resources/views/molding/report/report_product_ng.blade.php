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
                margin-top: 0.5in;
                margin-left: 0in;
                margin-right: 0in;
                margin-bottom: 0in;
            }

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
        <p class="mb-2" style="font-size: 14px;">Foto atau Gambar Detail untuk Diagnosa Produk Molding</p>
        <table class="w-full border-box-thick ng-block">
        @foreach (array_chunk($ngs, 2) as $chunk)
        <tbody style="page-break-inside: avoid;">
            <tr class="text-center">
                {{-- First NG --}}
                <td class="border-b border-black" style="width: 9%">Nama NG</td>
                <td class="border-b border-r border-l border-black">{{ $chunk[0]->ng_name ?? '' }}</td>
                <td class="border-b border-black" style="width: 9%">Nomor</td>
                <td class="border-b border-r border-l border-black">{{ $chunk[0]->id_ng ?? '' }}</td>

                {{-- Second NG (if exists) --}}
                @if (isset($chunk[1]))
                <td class="border-b border-black" style="width: 9%">Nama NG</td>
                <td class="border-b border-r border-l border-black">{{ $chunk[1]->ng_name ?? '' }}</td>
                <td class="border-b border-black" style="width: 9%">Nomor</td>
                <td class="border-b border-r border-l border-black">{{ $chunk[1]->id_ng ?? '' }}</td>
                @endif
            </tr>
            <tr>
                <td class="bg-gray-100 w-1/2 border-r border-b border-black p-2" colspan="4"><img class="w-full object-contain" style="max-height: 20vh;" src="{{ asset('workshop/molding/photo_product/ng/' . $chunk[0]->photo1) }}" alt="{{ $chunk[0]->photo1 }}"></td>
                @if (isset($chunk[1]))
                <td class="bg-gray-100 w-1/2 border-b border-black p-2" colspan="4"><img class="w-full object-contain" style="max-height: 20vh;" src="{{ asset('workshop/molding/photo_product/ng/' . $chunk[1]->photo1) }}" alt="{{ $chunk[1]->photo1 }}"></td>
                @endif
            </tr>
        </tbody>
        @endforeach
        </table>
    </div>
    <script src="{{ url('adminlte/plugins/jquery/jquery.min.js') }}"></script>
    <script>
        //document ready
        $(document).ready(function() {
           //open window print 
           window.print();
        });
    </script>
</body>
</html>
