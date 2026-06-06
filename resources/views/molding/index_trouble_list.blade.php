@extends('layouts.master')

@section('styles')
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background: #f0f2f7 !important; }

    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="dtr"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Loading ── */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.4); backdrop-filter: blur(5px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 48px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04); pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title { font-size: 14px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 10px; }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── DataTable ── */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_filter label, .dataTables_length label { font-size: 13px !important; color: #4a5568 !important; }
    .dataTables_filter input, .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 6px 10px !important; font-size: 13px !important; outline: none !important;
    }

    table.dataTable { border-collapse: collapse !important; table-layout: auto !important; width: 100% !important; }
    table.dataTable thead th {
        background: #f7f8fc !important; color: #718096 !important;
        font-size: 11px !important; font-weight: 700 !important; letter-spacing: .7px !important;
        text-transform: uppercase !important; padding: 12px 14px !important; height: auto !important;
        border-bottom: 2px solid #edf0f5 !important; border-top: none !important; white-space: nowrap !important;
        vertical-align: middle !important;
    }
    table.dataTable tbody tr:hover td { background: #f5f8ff !important; }
    table.dataTable tbody td {
        padding: 12px 14px !important; font-size: 13px !important; color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important; border-top: none !important; vertical-align: middle !important;
    }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }
    .dataTables_info { font-size: 12px !important; color: #718096 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important; font-size: 13px !important; font-weight: 600 !important;
        color: #4a5568 !important; border: 1px solid transparent !important;
        padding: 5px 10px !important; margin: 0 2px !important;
    }
    .dataTables_paginate .paginate_button:hover { background: #ede9fe !important; color: #605ca8 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg,#4a4690,#605ca8) !important; color: #fff !important; }

    /* Action buttons */
    .action-group { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
    .btn-act {
        display: inline-flex; align-items: center; justify-content: center;
        border-radius: 8px; border: none;
        cursor: pointer; font-size: 11.5px; font-weight: 700; transition: opacity .18s, transform .15s;
        text-decoration: none; flex-shrink: 0; padding: 6px 12px; gap: 4px;
    }
    .btn-act:hover { opacity: .8; transform: scale(1.05); text-decoration: none; }
    .btn-act.a-edit  { background: #dbeafe; color: #1e40af; }
    .btn-act.a-pdf   { background: #fee2e2; color: #991b1b; }
    .btn-act.a-create { background: #dcfce7; color: #15803d; }

    .status-pill {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        min-width: 90px;
        padding: 6px 14px;
        border-radius: 999px;
        font-size: 12px;
        font-weight: 700;
        letter-spacing: .02em;
        text-transform: capitalize;
    }
    .status-pill.normal { background: #dcfce7; color: #166534; }
    .status-pill.info   { background: #dbeafe; color: #1e3a8a; }
    .status-pill.warning{ background: #fef9c3; color: #92400e; }
    .status-pill.danger { background: #fee2e2; color: #991b1b; }
    .status-pill.muted  { background: #f3f4f6; color: #475569; }
</style>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-list-check"></i> Diagnosa Molding</div>
            <h1>Daftar Riwayat Kerusakan Molding</h1>
            <p>Kelola semua form diagnosis molding dan riwayat pemeriksaan</p>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Formulir
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="dataTable dtr-inline" style="width:100%" id="tableTrouble">
                <thead>
                    <tr style="text-align: center;">
                        <th style="width:44px; text-align:center;">#</th>
                        <th>FA Number</th>
                        <th>Nama Molding</th>
                        <th style="width:120px">Bulan</th>
                        <th style="width:110px">Form Number</th>
                        <th style="width:160px">Product Check</th>
                        <th style="width:160px">Molding Check</th>
                        <th style="width:160px">Evaluation</th>
                        <th style="width:100px; text-align:center;">Status</th>
                    </tr>
                </thead>
                <tbody id="bodyTableTrouble"></tbody>
            </table>
        </div>
    </div>

</div>

@endsection

@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('js/buttons.flash.min.js') }}"></script>
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/vfs_fonts.js') }}"></script>
<script src="{{ url('js/buttons.html5.min.js') }}"></script>
<script src="{{ url('js/buttons.print.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    var audio_error   = new Audio('{{ url('sounds/error.mp3') }}');
    var audio_success = new Audio('{{ url('sounds/success.mp3') }}');

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    jQuery(document).ready(function() {
        $('body').toggleClass("sidebar-collapse");
        $('#side_diagnosa_molding').addClass('menu-open');
        getData();
    });

    /* ══════════════════════════════════════
       getData
    ══════════════════════════════════════ */
    function getData() {
        $('#loading').addClass('show');
        $.ajax({
            url: '{{ url('fetch/diagnose_molding/molding_form') }}',
            type: 'GET',
            success: function(result) {
                if (result.status) {
                    var tableData = "";
                    $.each(result.data, function(key, value) {
                        var productCheckBtn = '';
                        if (value.form_product_id) {
                            productCheckBtn = '<div class="action-group">' +
                                '<a href="{{ url('index/diagnose_molding/mold_product_check/create') }}/' + value.form_number + '" class="btn-act a-edit"><i class="fas fa-edit"></i> Edit</a>' +
                                '<a href="{{ url('index/diagnose_molding/mold_product_check/report') }}/' + value.form_number + '" class="btn-act a-pdf" target="_blank"><i class="fas fa-file-pdf"></i> Report</a>' +
                                '<a href="{{ url('index/diagnose_molding/mold_product_check/report_ng') }}/' + value.form_number + '" class="btn-act a-pdf" target="_blank"><i class="fas fa-file-pdf"></i> NG</a>' +
                                '</div>';
                        } else {
                            productCheckBtn = '<a href="{{ url('index/diagnose_molding/mold_product_check/create') }}/' + value.form_number + '" class="btn-act a-create"><i class="fas fa-plus"></i> Buat</a>';
                        }

                        var moldingCheckBtn = '';
                        if (value.form_molding_id) {
                            moldingCheckBtn = '<div class="action-group">' +
                                '<a href="{{ url('index/diagnose_molding/mold_molding_check/create') }}/' + value.form_number + '" class="btn-act a-edit"><i class="fas fa-edit"></i> Edit</a>' +
                                '<a href="{{ url('index/diagnose_molding/mold_molding_check/report') }}/' + value.form_number + '" class="btn-act a-pdf" target="_blank"><i class="fas fa-file-pdf"></i> Report</a>' +
                                '<a href="{{ url('index/diagnose_molding/mold_molding_check/report_ng') }}/' + value.form_number + '" class="btn-act a-pdf" target="_blank"><i class="fas fa-file-pdf"></i> NG</a>' +
                                '</div>';
                        } else {
                            moldingCheckBtn = '<a href="{{ url('index/diagnose_molding/mold_molding_check/create') }}/' + value.form_number + '" class="btn-act a-create"><i class="fas fa-plus"></i> Buat</a>';
                        }

                        var evaluationBtn = '<div class="action-group">' +
                            '<a href="{{ url('index/diagnose_molding/evaluation/edit') }}/' + value.form_number + '" class="btn-act a-edit"><i class="fas fa-edit"></i> Edit</a>' +
                            '<a href="{{ url('index/diagnose_molding/evaluation/report') }}/' + value.form_number + '" class="btn-act a-pdf" target="_blank"><i class="fas fa-file-pdf"></i> Report</a>' +
                            '</div>';

                        var statusText = value.status ? value.status : '-';
                        var statusClass = 'muted';

                        if (value.status) {
                            var statusLower = value.status.toString().toLowerCase();
                            if (statusLower.match(/open|need|ng|failed|problem|rejected|reject|pending|draft/i)) {
                                statusClass = 'danger';
                            } else if (statusLower.match(/sent|completed|done|ok|normal|success|approval/i)) {
                                statusClass = 'normal';
                            } else if (statusLower.match(/progress|process|review|in progress|waiting/i)) {
                                statusClass = 'info';
                            } else if (statusLower.match(/hold|warning|attention|review/i)) {
                                statusClass = 'warning';
                            } else {
                                statusClass = 'muted';
                            }
                        }
                        var statusBadge = '<span class="status-pill ' + statusClass + '">' + statusText + '</span>';

                        tableData += '<tr>';
                        tableData += '<td style="text-align:center;font-weight:600;color:#a0aec0;"></td>';
                        tableData += '<td style="font-family:monospace;font-weight:700;color:#605ca8;">' + value.fixed_asset_number + '</td>';
                        tableData += '<td style="font-weight:600;">' + value.fixed_asset_name + '</td>';
                        tableData += '<td style="text-align:center;font-size:12px;">' + (value.month || '-') + '</td>';
                        tableData += '<td style="text-align:center;font-family:monospace;font-weight:600;color:#605ca8;">' + (value.form_number || '-') + '</td>';
                        tableData += '<td>' + productCheckBtn + '</td>';
                        tableData += '<td>' + moldingCheckBtn + '</td>';
                        tableData += '<td>' + evaluationBtn + '</td>';
                        tableData += '<td style="text-align:center;font-size:12px;">' + statusBadge + '</td>';
                        tableData += '</tr>';
                    });

                    $('#bodyTableTrouble').html(tableData);

                    // Initialize DataTable
                    if ($.fn.DataTable.isDataTable('#tableTrouble')) {
                        $('#tableTrouble').DataTable().destroy();
                    }

                    $('#tableTrouble').DataTable({
                        paging: true,
                        searching: true,
                        ordering: false,
                        info: true,
                        autoWidth: false,
                        lengthChange: false,
                        pageLength: 25,
                        lengthMenu: [10, 25, 50, 100],
                        language: {
                            lengthMenu: 'Tampilkan _MENU_ data',
                            zeroRecords: 'Tidak ada data',
                            info: '_START_–_END_ dari _TOTAL_ data',
                            infoEmpty: '0 data',
                            infoFiltered: '(dari _MAX_)',
                            search: 'Cari:',
                            paginate: { first:'«', last:'»', next:'›', previous:'‹' }
                        },
                        columnDefs: [
                            { targets: 0, orderable: false, width: '44px' }
                        ]
                    });

                    // Auto-number first column
                    var table = $('#tableTrouble').DataTable();
                    table.on('order.dt search.dt draw.dt', function() {
                        let pageInfo = table.page.info();
                        table.column(0, { search: 'applied', order: 'applied', page: 'current' })
                            .nodes()
                            .each(function(cell, i) {
                                cell.innerHTML = i + 1 + pageInfo.start;
                            });
                    }).draw();
                }
            },
            error: function(xhr, status, error) {
                toastr.error('Gagal memuat data');
                audio_error.play();
            },
            complete: function() {
                $('#loading').removeClass('show');
            }
        });
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-success',
            image: '{{ url('images/image-screen.png') }}',
            sticky: false,
            time: '2000'
        });
    }

    function openErrorGritter(title, message) {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-danger',
            image: '{{ url('images/image-stop.png') }}',
            sticky: false,
            time: '2000'
        });
        audio_error.play();
    }
</script>
@endsection
