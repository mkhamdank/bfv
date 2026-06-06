@extends('layouts.master')

@section('styles')
<link href="{{ url('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; font-family: 'Plus Jakarta Sans', sans-serif !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1,body h2,body h3,body h4,body h5,body h6,body li {
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

    /* ── Page Header ── */
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

    /* ── Filter Card ── */
    .filter-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        padding: 18px 24px; margin-bottom: 22px;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .filter-label { font-size: 12px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: .7px; white-space: nowrap; }
    .filter-card .select2-container { min-width: 220px; }
    .select2-container--default .select2-selection--single {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        height: 38px !important; display: flex !important; align-items: center !important;
        background: #fafbfc !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 38px !important; font-size: 13px !important;
        color: #1a202c !important; padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
    .btn-filter {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 9px 20px; border-radius: 9px; font-size: 13px; font-weight: 700;
        border: none; cursor: pointer;
        background: linear-gradient(135deg, #4a4690, #605ca8); color: #fff;
        transition: all .2s; font-family: 'Plus Jakarta Sans', sans-serif;
    }
    .btn-filter:hover { transform: translateY(-1px); box-shadow: 0 4px 12px rgba(96,92,168,.35); }

    /* ── Table Card ── */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 24px;
    }
    .table-card-header {
        padding: 16px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title {
        font-size: 14px; font-weight: 700; color: #1a202c;
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── DataTable ── */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_filter label, .dataTables_length label { font-size: 13px !important; color: #4a5568 !important; }
    .dataTables_filter input, .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 6px 10px !important; font-size: 13px !important; outline: none !important;
    }
    table.dataTable { border-collapse: collapse !important; width: 100% !important; }
    table.dataTable thead th {
        background: #f7f8fc !important; color: #718096 !important;
        font-size: 11px !important; font-weight: 700 !important; letter-spacing: .7px !important;
        text-transform: uppercase !important; padding: 10px 12px !important; height: 42px !important;
        border-bottom: 2px solid #edf0f5 !important; border-top: none !important;
        white-space: nowrap !important; vertical-align: middle !important; text-align: center !important;
    }
    table.dataTable tbody tr:hover td { background: #f5f3ff !important; }
    table.dataTable tbody td {
        padding: 11px 12px !important; font-size: 13px !important; color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important; border-top: none !important;
        vertical-align: middle !important; text-align: center !important;
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

    /* ── Status Badges ── */
    .status-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 11.5px; font-weight: 700; white-space: nowrap;
    }
    .status-pill.open   { background: #fee2e2; color: #dc2626; }
    .status-pill.close  { background: #dcfce7; color: #15803d; }

    /* ── Number chip ── */
    .num-chip {
        display: inline-flex; align-items: center; justify-content: center;
        min-width: 36px; padding: 3px 10px; border-radius: 8px;
        font-size: 13px; font-weight: 700;
    }
    .num-chip.default { background: #f0f2f7; color: #4a5568; }
    .num-chip.warning { background: #fef3c7; color: #b45309; }
    .num-chip.success { background: #dcfce7; color: #15803d; }

    /* ── Action buttons ── */
    .btn-act {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 6px 13px; border-radius: 8px;
        font-size: 12px; font-weight: 700; border: none;
        cursor: pointer; text-decoration: none; transition: all .2s;
        white-space: nowrap; font-family: 'Plus Jakarta Sans', sans-serif;
        margin: 2px;
    }
    .btn-act:hover { transform: translateY(-1px); text-decoration: none; }
    .btn-act.audit   { background: #ebf2ff; color: #2d6bc4; }
    .btn-act.confirm { background: #dcfce7; color: #15803d; }
    .btn-act.pdf     { background: #fee2e2; color: #dc2626; }
</style>
@endsection

@section('header')
<section class="content-header"><ol class="breadcrumb" style="margin:0;"></ol></section>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Loading --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Loading, please wait...</p>
    </div>
</div>

<div class="container-fluid" style="padding: 0 20px;">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-clipboard-check"></i>&nbsp; Fixed Asset</div>
            <h1>Fixed Asset Audit</h1>
            <p>Audit menyeluruh terhadap kondisi dan data aset tetap perusahaan</p>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <span class="filter-label"><i class="fas fa-filter"></i> Filter Period</span>
        <select class="form-control select2" id="period" data-placeholder="Pilih Period" style="width: auto;">
            <option value=""></option>
            @foreach ($period as $per)
                <option value="{{ $per->period }}">{{ $per->period }}</option>
            @endforeach
        </select>
        <button class="btn-filter" onclick="getData()">
            <i class="fas fa-search"></i> Tampilkan
        </button>
    </div>

    {{-- Table Card --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                <i class="fas fa-table" style="color:#605ca8;"></i>
                Data Fixed Asset Audit
            </div>
        </div>
        <div style="padding: 16px 20px;">
            <table id="AuditAssetTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>Location</th>
                        <th>PIC</th>
                        <th>Auditor</th>
                        <th>Qty Asset</th>
                        <th>Min Audit</th>
                        <th>Actual Audit</th>
                        <th>Status</th>
                        <th>Report</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="AuditAssetBody"></tbody>
            </table>
        </div>
    </div>

</div>
@stop

@section('scripts')
<script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('js/buttons.html5.min.js') }}"></script>
<script src="{{ url('js/buttons.print.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    jQuery(document).ready(function () {
        $('body').toggleClass("sidebar-collapse");
        $('#side_fixed_asset').addClass('menu-open');
        $('.select2').select2({ dropdownPosition: 'below' });
        getData();
    });

    function getData() {
        $('#loading').addClass('show');
        var data = { period: $("#period").val() };

        $.get('{{ url('fetch/fixed_asset/audit/audit/list') }}', data, function (result) {
            $('#loading').removeClass('show');

            if ($.fn.DataTable.isDataTable('#AuditAssetTable')) {
                $('#AuditAssetTable').DataTable().clear().destroy();
            }
            $("#AuditAssetBody").empty();

            var body = "";

            $.each(result.assets, function (index, value) {
                var must_audit = Math.round(parseInt(value.jml_asset) / 100 * 10);
                if (must_audit < 1) must_audit = 1;

                // Auditor — deduplicate
                var auditor = [];
                $.each(value.checked_by.split(','), function (i, v) {
                    var name = v.split('/')[1];
                    if ($.inArray(name, auditor) === -1) auditor.push(name);
                });

                // Actual audit chip — color by progress
                var actualClass = value.audited >= must_audit ? 'success' : (value.audited > 0 ? 'warning' : 'default');

                // Status pill
                var isOpen  = value.status_audit === "Open";
                var statusHtml = isOpen
                    ? '<span class="status-pill open"><i class="fas fa-exclamation-circle"></i> Open</span>'
                    : '<span class="status-pill close"><i class="fas fa-check-circle"></i> Close</span>';

                body += "<tr>";
                body += "<td><span style='font-size:12px;font-weight:600;color:#4a5568;'>" + (value.period || '-') + "</span></td>";
                body += "<td style='text-align:left;font-weight:600;'>" + (value.location || '-') + "</td>";
                body += "<td style='font-size:12px;color:#718096;'>" + (value.name || '-') + "</td>";
                body += "<td style='font-size:12px;color:#718096;'>" + (auditor.join(', ') || '-') + "</td>";
                body += "<td><span class='num-chip default'>" + value.jml_asset + "</span></td>";
                body += "<td><span class='num-chip default'>" + must_audit + "</span></td>";
                body += "<td><span class='num-chip " + actualClass + "'>" + value.audited + "</span></td>";
                body += "<td>" + statusHtml + "</td>";
                body += "<td><button class='btn-act pdf' onclick='cek_report(\"" + value.location + "\")'><i class='far fa-file-pdf'></i> PDF</button></td>";

                // Action
                body += "<td>";
                if (value.appr_manager_at && isOpen) {
                    body += "<a class='btn-act audit' href='{{ url('index/audit/fixed_asset/audit') }}/" + value.location + "/" + value.period2 + "'><i class='fas fa-clipboard-check'></i> Audit</a>";
                    if (value.audited >= must_audit) {
                        body += "<button class='btn-act confirm' onclick='confirmAudit(\"" + value.location + "\",\"" + value.period2 + "\")'><i class='fas fa-check-double'></i> Confirm All</button>";
                    }
                }
                body += "</td>";
                body += "</tr>";
            });

            $("#AuditAssetBody").append(body);

            $('#AuditAssetTable').DataTable({
                dom: '<"d-flex align-items-center justify-content-between mb-3"lf>rt<"d-flex align-items-center justify-content-between mt-3"ip>',
                responsive: true,
                lengthMenu: [[10, 25, 50, -1], ['10', '25', '50', 'Semua']],
                paging: true, lengthChange: false, searching: true,
                ordering: false, info: true, autoWidth: false,
                language: {
                    search: '', searchPlaceholder: 'Cari data...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    paginate: { first: '«', last: '»', next: '›', previous: '‹' }
                }
            });
        });
    }

    function confirmAudit(lokasi, period) {
        Swal.fire({
            title: 'Konfirmasi Audit',
            html: 'Simpan hasil audit aset di lokasi <b>' + lokasi + '</b>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#15803d', cancelButtonColor: '#718096',
            confirmButtonText: '<i class="fas fa-check-double"></i> Ya, Konfirmasi',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (result.isConfirmed) {
                $('#loading').addClass('show');
                $.post('{{ url('confirm/fixed_asset/audit') }}',
                    { location: lokasi, period: period, category: 'Vendor' },
                    function (res) {
                        $('#loading').removeClass('show');
                        if (res.status) {
                            toastr.success('Fixed Asset Successfully Audited', 'Success!');
                            getData();
                        } else {
                            toastr.error(res.message, 'Error!');
                        }
                    }
                );
            }
        });
    }

    function cek_report(location) {
        // implement as needed
    }

    var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
</script>
@stop