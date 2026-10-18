<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    @foreach ($headAssets as $asset)
        {!! $asset !!}
    @endforeach
    <style>
        @page {
            size: A4 portrait;
            margin: 8mm;
        }

        html,
        body {
            display: block !important;
            width: 100%;
            margin: 0;
            padding: 0;
        }
        .report-section {
            width: 100%;
            box-sizing: border-box;
            page-break-after: always;
            break-after: page;
        }
        .report-section > .container {
            width: 100% !important;
            max-width: 100% !important;
            margin: 0 auto !important;
            padding: 8px !important;
            box-sizing: border-box !important;
            background: #fff;
        }
        .report-section:last-child {
            page-break-after: auto;
            break-after: auto;
        }
    </style>
</head>
<body>
    @foreach ($sections as $section)
        <div class="report-section">{!! $section !!}</div>
    @endforeach
</body>
</html>