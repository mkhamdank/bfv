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

    /* ── Loading overlay ── */
    #loading {
        position: fixed; inset: 0;
        background: rgba(30,30,60,0.55);
        backdrop-filter: blur(3px);
        z-index: 30001; display: none;
        align-items: center; justify-content: center;
    }
    .loading-box {
        background: #fff; border-radius: 16px;
        padding: 36px 48px; text-align: center;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
    }
    .loading-box i { font-size: 36px; color: #2d2b4e; margin-bottom: 12px; display: block; }
    .loading-box p { font-size: 15px; font-weight: 600; color: #4a4690; margin: 0; }

    /* ── Page Header ── */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px;
        margin: 24px 0 24px;
        border-radius: 18px;
        display: flex; align-items: center;
        justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color:#fff !important; font-size:26px !important; font-weight:700 !important; margin:0 0 4px !important; }
    .header-left p  { color:rgba(255,255,255,.5); font-size:13px; margin:0; }
    .header-actions { display:flex; align-items:center; gap:10px; flex-wrap:wrap; }
    .btn-header {
        display:inline-flex; align-items:center; gap:7px;
        padding:10px 20px; border-radius:10px; font-size:13px;
        font-weight:700; border:none; cursor:pointer; transition:all .2s;
        text-decoration:none; font-family:'Plus Jakarta Sans',sans-serif;
    }
    .btn-header.white  { background:#fff; color:#4a4690; }
    .btn-header.red    { background:#ef4444; color:#fff; }
    .btn-header.ghost  { background:rgba(255,255,255,.15); border:1.5px solid rgba(255,255,255,.3); color:#fff; }
    .btn-header:hover  { transform:translateY(-1px); box-shadow:0 4px 14px rgba(0,0,0,.2); text-decoration:none; color:inherit; }

    /* ── Filter Card ── */
    .filter-card {
        background:#fff; border-radius:16px;
        box-shadow:0 2px 12px rgba(0,0,0,.06);
        border:1px solid rgba(0,0,0,.05);
        padding:18px 24px; margin-bottom:22px;
        display:flex; align-items:center; gap:14px; flex-wrap:wrap;
    }
    .filter-label { font-size:12px; font-weight:700; color:#718096; text-transform:uppercase; letter-spacing:.7px; white-space:nowrap; }
    .filter-card .select2-container { min-width:220px; }
    .select2-container--default .select2-selection--single {
        border:1.5px solid #e2e8f0 !important; border-radius:9px !important;
        height:38px !important; display:flex !important; align-items:center !important;
        background:#fafbfc !important; font-family:'Plus Jakarta Sans',sans-serif !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height:38px !important; font-size:13px !important;
        color:#1a202c !important; padding-left:12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height:36px !important; }
    .btn-filter {
        display:inline-flex; align-items:center; gap:6px;
        padding:9px 20px; border-radius:9px; font-size:13px;
        font-weight:700; border:none; cursor:pointer;
        background:linear-gradient(135deg,#4a4690,#605ca8);
        color:#fff; transition:all .2s;
        font-family:'Plus Jakarta Sans',sans-serif;
    }
    .btn-filter:hover { transform:translateY(-1px); box-shadow:0 4px 12px rgba(96,92,168,.35); }

    /* ── Table Card ── */
    .table-card {
        background:#fff; border-radius:16px;
        box-shadow:0 2px 12px rgba(0,0,0,.06);
        border:1px solid rgba(0,0,0,.05);
        overflow:hidden; margin-bottom:24px;
    }
    .table-card-header {
        padding:16px 24px; border-bottom:1px solid #f0f2f7;
        background:#fafbff; display:flex;
        align-items:center; justify-content:space-between; gap:10px;
    }
    .table-card-title {
        font-size:13px; font-weight:700; color:#1a202c;
        display:flex; align-items:center; gap:8px;
    }
    .table-card-title .dot { width:8px; height:8px; background:#605ca8; border-radius:50%; }

    /* ── DataTable ── */
    #AuditAssetTable { width:100% !important; border-collapse:separate !important; border-spacing:0 !important; }
    #AuditAssetTable thead tr th {
        background: #f7f8fc !important;
        color: #718096 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: .7px !important;
        text-transform: uppercase !important;
        padding: 10px 12px !important;
        height: 42px !important;
        border-bottom: 2px solid #edf0f5 !important;
        border-top: none !important;
        white-space: nowrap !important;
        vertical-align: middle !important;
        text-align: center !important;
    }
    #AuditAssetTable tbody tr { transition:background .15s; }
    #AuditAssetTable tbody tr:hover { background:#f5f3ff !important; }
    #AuditAssetTable tbody td {
        padding:11px 12px !important; text-align:center !important;
        font-size:13px !important; color:#2d3748 !important;
        border-bottom:1px solid #f0f2f7 !important;
        border-left:none !important; border-right:none !important;
        vertical-align:middle !important;
    }
    #AuditAssetTable tbody tr:last-child td { border-bottom:none !important; }
    .dataTables_wrapper .dataTables_filter input {
        border:1.5px solid #e2e8f0 !important; border-radius:8px !important;
        padding:6px 12px !important; font-size:13px !important;
        font-family:'Plus Jakarta Sans',sans-serif !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border:1.5px solid #e2e8f0 !important; border-radius:8px !important;
        padding:4px 8px !important; font-family:'Plus Jakarta Sans',sans-serif !important;
    }
    .dataTables_wrapper .dataTables_info { font-size:12.5px !important; color:#718096 !important; }
    .paginate_button { border-radius:7px !important; font-family:'Plus Jakarta Sans',sans-serif !important; }
    .paginate_button.current { background:#605ca8 !important; color:#fff !important; border-color:#605ca8 !important; }

    /* ── Status Badges ── */
    .status-pill {
        display:inline-flex; align-items:center; gap:5px;
        padding:4px 11px; border-radius:20px;
        font-size:11.5px; font-weight:700; white-space:nowrap;
    }
    .status-pill.approved  { background:#e8f7f0; color:#1a7a4a; }
    .status-pill.waiting   { background:#fef3c7; color:#b45309; }
    .status-pill.check1    { background:#e0e7ff; color:#4338ca; }
    .status-pill.check2    { background:#ebf2ff; color:#2d6bc4; }
    .status-pill.notcheck  { background:#f0f2f7; color:#718096; }
    .status-pill.temp      { background:#fff5f5; color:#c0392b; }

    /* ── Action Buttons ── */
    .btn-act {
        display:inline-flex; align-items:center; gap:5px;
        padding:6px 14px; border-radius:8px;
        font-size:12px; font-weight:700; border:none;
        cursor:pointer; text-decoration:none; transition:all .2s;
        white-space:nowrap; font-family:'Plus Jakarta Sans',sans-serif;
    }
    .btn-act:hover { transform:translateY(-1px); text-decoration:none; }
    .btn-act.cek1        { background:#e0e7ff; color:#4338ca; }
    .btn-act.cek2        { background:#ebf2ff; color:#2d6bc4; }
    .btn-act.send        { background:#e8f7f0; color:#1a7a4a; }
    .btn-act.full-appr   { background:#e8f7f0; color:#1a7a4a; cursor:default; }
    .btn-act.save-check  { background:linear-gradient(135deg,#1a7a4a,#16a34a); color:#fff; }
    .btn-act.pdf         { background:#fee2e2; color:#dc2626; }

    /* ── FA Number chip ── */
    .fa-chip {
        font-family:monospace; font-size:12px;
        background:#f0f2f7; color:#4a5568;
        padding:3px 9px; border-radius:6px; font-weight:700;
    }

    /* ── Image thumbnail ── */
    .asset-thumb {
        width:60px; height:60px; object-fit:cover;
        border-radius:10px; border:2px solid #e2e8f0;
        cursor:pointer; transition:transform .2s, box-shadow .2s;
    }
    .asset-thumb:hover { transform:scale(1.08); box-shadow:0 4px 14px rgba(0,0,0,.15); }

    /* ── Modal ── */
    .modal-content { border-radius:16px; border:none; box-shadow:0 20px 60px rgba(0,0,0,.2); }
    .modal-header { background:linear-gradient(135deg,#2d2b4e,#605ca8); border-radius:16px 16px 0 0; padding:18px 24px; border:none; }
    .modal-header .modal-title { color:#fff; font-weight:700; font-size:15px; }
    .modal-header .close { color:#fff; opacity:.7; }
    .modal-body { padding:24px; }
    .modal-footer { padding:16px 24px; border-top:1px solid #f0f2f7; }
</style>
@endsection

@section('header')
<section class="content-header"><ol class="breadcrumb" style="margin:0;"></ol></section>
@endsection

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Loading Overlay --}}
<div id="loading">
    <div class="loading-box">
        <i class="fas fa-circle-notch fa-spin"></i>
        <p>Loading, please wait...</p>
    </div>
</div>

<div class="container-fluid" style="padding:0 20px;">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-cubes"></i>&nbsp; Fixed Asset</div>
            <h1>Fixed Asset Check</h1>
            <p>Pemeriksaan kondisi dan status aset tetap perusahaan</p>
        </div>
        <div class="header-actions" style="z-index:999;">
            <a href="{{ url('data_file/fixed_asset/Panduan Cek Fixed Asset YMPI.mp4') }}" target="_blank" class="btn-header ghost">
                <i class="fas fa-video"></i> Panduan Pengisian
            </a>
            <button type="button" class="btn-header red" onclick="generateReport()">
                <i class="fas fa-file-pdf"></i> Generate Report
            </button>
        </div>
    </div>

    {{-- Filter --}}
    <div class="filter-card">
        <span class="filter-label"><i class="fas fa-filter"></i> Filter Period</span>
        <select class="form-control select2" id="period" data-placeholder="Pilih Period" style="width:auto;">
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
                Data Fixed Asset Check
            </div>
        </div>
        <div style="padding:16px 20px;">
            <table id="AuditAssetTable" class="table" style="width:100%">
                <thead>
                    <tr>
                        <th>Period</th>
                        <th>FA Number</th>
                        <th>Fixed Asset Name</th>
                        <th>PIC</th>
                        <th>Auditor</th>
                        <th>Image</th>
                        <th>Report</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="AuditAssetBody"></tbody>
            </table>
        </div>
    </div>

    {{-- Map Modal --}}
    <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form id="upload_map_form">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                    <div class="modal-header">
                        <h4 class="modal-title"><i class="fas fa-map-marked-alt"></i> Update Asset MAP</h4>
                        <button type="button" class="close" data-dismiss="modal" onclick="closeModal()">
                            <span>&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="form-group mb-3">
                            <label style="font-size:12px;font-weight:700;color:#718096;text-transform:uppercase;letter-spacing:.7px;">Vendor</label>
                            <input type="text" id="vendor_map" class="form-control" readonly
                                style="border:1.5px solid #e2e8f0;border-radius:9px;font-size:13px;">
                        </div>
                        <table class="table" style="width:100%">
                            <thead>
                                <tr>
                                    <th style="background:linear-gradient(135deg,#2d2b4e,#605ca8);color:#fff;padding:10px 12px;font-size:11.5px;font-weight:700;text-transform:uppercase;">Location</th>
                                    <th style="background:linear-gradient(135deg,#2d2b4e,#605ca8);color:#fff;padding:10px 12px;font-size:11.5px;font-weight:700;text-transform:uppercase;">Map</th>
                                    <th style="background:linear-gradient(135deg,#2d2b4e,#605ca8);color:#fff;padding:10px 12px;font-size:11.5px;font-weight:700;text-transform:uppercase;">Upload Map</th>
                                </tr>
                            </thead>
                            <tbody id="body_map"></tbody>
                        </table>
                    </div>
                    <div class="modal-footer" style="display:flex;gap:10px;justify-content:flex-end;">
                        <button type="button" class="btn-act send" data-dismiss="modal" onclick="closeModal()">
                            <i class="fas fa-times"></i> Cancel
                        </button>
                        <button type="submit" class="btn-act save-check">
                            <i class="fas fa-check"></i> Update Map
                        </button>
                    </div>
                </form>
            </div>
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

@if (Session::has('message'))
<script>toastr.error("{{ Session::get('message') }}", "Error!");</script>
@endif

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var period = '';
    var loc    = '';

    jQuery(document).ready(function () {
        $('body').toggleClass("sidebar-collapse");
        $('#side_fixed_asset').addClass('menu-open');

        $('.select2').select2({ dropdownPosition: 'below' });

        if ("{{ Auth::user()->username }}" == 'bahanacheck1' ||
            "{{ Auth::user()->username }}" == 'bahanacheck2' ||
            "{{ Auth::user()->username }}" == 'bahanaaudit') {

            $("#vendor_map").val("Bahana Unindo");
            $("#body_map").empty();
            var bd  = '<tr>';
                bd += '<td class="loc" style="vertical-align:middle;font-weight:600;">Bahana Unindo</td>';
                bd += '<td style="vertical-align:middle;"><a class="btn-act cek1" target="_blank" href="{{ url('files/fixed_asset/map/Bahana Unindo.pdf') }}"><i class="fas fa-map"></i> Map</a></td>';
                bd += '<td style="vertical-align:middle;"><input type="file" class="form-control map" accept="application/pdf" style="border-radius:8px;border:1.5px solid #e2e8f0;padding:4px 8px;font-size:12px;"></td>';
                bd += '</tr>';
            $("#body_map").append(bd);
        }

        getData();
    });

    function getData() {
        $("#loading").show();
        var data = { period: $("#period").val() };

        $.get('{{ url('fetch/fixed_asset/audit/list') }}', data, function (result) {
            $("#loading").hide();

            if ($.fn.DataTable.isDataTable('#AuditAssetTable')) {
                $('#AuditAssetTable').DataTable().clear().destroy();
            }
            $("#AuditAssetBody").empty();

            var body       = "";
            var total_cek2 = 0;

            $.each(result.assets, function (index, value) {
                period = value.period;
                loc    = value.location;

                if (value.remark === "temporary save 2") total_cek2++;

                // Status pill
                var statusHtml = '';
                if (value.appr_status) {
                    if (value.appr_manager_at) {
                        statusHtml = '<span class="status-pill approved"><i class="fas fa-check-circle"></i> Fully Approved</span>';
                    } else {
                        statusHtml = '<span class="status-pill waiting"><i class="fas fa-clock"></i> Waiting Approval</span>';
                    }
                } else {
                    if (value.status === 'Not Checked') {
                        statusHtml = '<span class="status-pill notcheck"><i class="fas fa-minus-circle"></i> Not Checked</span>';
                    } else if (value.status === 'Check 1') {
                        statusHtml = '<span class="status-pill check1"><i class="fas fa-search"></i> Check 1</span>';
                    } else if (value.status === 'Check 2') {
                        statusHtml = '<span class="status-pill check2"><i class="fas fa-search-plus"></i> Check 2</span>';
                    } else {
                        statusHtml = '<span class="status-pill notcheck">' + (value.status || '-') + '</span>';
                    }
                    if (value.remark === 'temporary save 1') {
                        statusHtml += '<br><span class="status-pill temp" style="margin-top:4px;"><i class="fas fa-save"></i> Temp Save 1</span>';
                    } else if (value.remark === 'temporary save 2') {
                        statusHtml += '<br><span class="status-pill temp" style="margin-top:4px;"><i class="fas fa-save"></i> Temp Save 2</span>';
                    }
                }

                var imgUrl = "{{ url('data_file/fixed_asset/master_picture') }}/" + value.asset_images;
                var auditor = value.checked_by ? value.checked_by.split('/')[1] : '-';

                body += "<tr>";
                body += "<td><span style='font-size:12px;font-weight:600;color:#4a5568;'>" + (value.period||'-') + "</span></td>";
                body += "<td><span class='fa-chip'>" + (value.sap_number||'-') + "</span></td>";
                body += "<td style='text-align:left;font-weight:600;'>" + (value.asset_name||'-') + "</td>";
                body += "<td style='font-size:12px;color:#718096;'>" + (value.location||'-') + "</td>";
                body += "<td style='font-size:12px;color:#718096;'>" + auditor + "</td>";
                body += "<td><img src='" + imgUrl + "' class='asset-thumb' onclick='modalImage(\"" + imgUrl + "\",\"" + value.sap_number + "\",\"" + value.period + "\")' alt='No Image'></td>";
                body += "<td><button class='btn-act pdf' onclick='cek_report()'><i class='far fa-file-pdf'></i> PDF</button></td>";
                body += "<td>" + statusHtml + "</td>";
                body += "<td class='btn_act'>";

                // Action button
                var perm = '{{ $permiss->permission }}';
                if (((value.status == 'Check 1' && !value.remark) || value.remark == 'temporary save 1') && perm.indexOf('Check 2') >= 0) {
                    body += "<a class='btn-act cek2' href='{{ url('index/check/fixed_asset/check2') }}/" + value.location + "/" + value.period + "'><i class='fas fa-edit'></i> Cek 2</a>";
                } else if (value.status == 'Check 2' && !value.appr_manager_at && perm.indexOf('Check 2') >= 0) {
                    body += "<button class='btn-act send' onclick='send_mail(\"" + value.period + "\",\"" + value.location + "\")'><i class='fas fa-paper-plane'></i> Send Approval</button>";
                } else if (value.status == 'Not Checked' && perm.indexOf('Check 1') >= 0 && !value.remark) {
                    body += "<a class='btn-act cek1' href='{{ url('index/check/fixed_asset/check1') }}/" + value.location + "/" + value.period + "'><i class='fas fa-edit'></i> Cek 1</a>";
                } else if (value.appr_manager_at) {
                    body += "<span class='btn-act full-appr'><i class='fas fa-check-double'></i> Fully Approved</span>";
                }

                body += "</td></tr>";
            });

            $("#AuditAssetBody").append(body);

            if (total_cek2 == result.assets.length && result.assets.length > 0) {
                $(".btn_act").append("<button class='btn-act save-check mt-1' onclick='save_check()'><i class='fas fa-check-double'></i> Save Check</button>");
            }

            $('#AuditAssetTable').DataTable({
                dom: '<"d-flex align-items-center justify-content-between mb-3"lf>rt<"d-flex align-items-center justify-content-between mt-3"ip>',
                responsive: true,
                lengthMenu: [[10,25,50,-1],['10','25','50','Semua']],
                paging: true, lengthChange: false,
                searching: true, ordering: false, info: true,
                autoWidth: false,
                language: {
                    search: '', searchPlaceholder: 'Cari data...',
                    lengthMenu: 'Tampilkan _MENU_ data',
                    info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                    paginate: { first:'«', last:'»', next:'›', previous:'‹' }
                }
            });
        });
    }

    function send_mail(period, location) {
        if (confirm('Send Fixed Asset Check Approval in "' + location + '" ?')) {
            $("#loading").show();
            $.post('{{ url('approval/fixed_asset/check') }}', { location, period, category: 'Vendor' }, function (result) {
                $("#loading").hide();
                if (result.status) { toastr.success('Approval berhasil terkirim', 'Success!'); getData(); }
                else               { toastr.error(result.message, 'Error!'); }
            });
        }
    }

    function openModal()  { $("#mapModal").modal('show'); }
    function closeModal() { $("#mapModal").modal('hide'); }

    $("form#upload_map_form").submit(function (e) {
        e.preventDefault();
        $("#loading").show();
        var arr_loc = [], formData = new FormData();
        $('.loc').each(function () { arr_loc.push($(this).text()); });
        formData.append('location', arr_loc);
        $('.map').each(function (i) { formData.append('map_' + i, $(this).prop('files')[0]); });
        $.ajax({
            url: '{{ url('upload/fixed_asset/map') }}', type: 'POST', data: formData,
            success: function () { $("#loading").hide(); toastr.success('Upload Map Successfully', 'Success!'); setTimeout(() => location.reload(), 1500); },
            error:   function () { $("#loading").hide(); toastr.error('Upload failed', 'Error!'); },
            cache: false, contentType: false, processData: false
        });
    });

    function generateReport() {
        $("#loading").show();
        $.get('{{ url('generate/fixed_asset/report') }}', { period, location: loc, category: 'Check' }, function () {
            $("#loading").hide();
            toastr.success('Report PDF berhasil dibuat', 'Success!');
        });
    }

    function cek_report() {
        $.get('{{ url('check/fixed_asset/report') }}', { period, location: loc, category: 'Check' }, function (result) {
            if (result.status) { window.open("{{ url('') }}/" + result.message, '_blank'); }
            else               { toastr.error(result.message, 'Error!'); }
        });
    }

    function save_check() {
        $.post('{{ url('confirm/fixed_asset/check') }}', { period, location: loc, category: 'Check' }, function () {
            $("#loading").hide();
            toastr.success('Hasil Cek Berhasil Disimpan', 'Success!');
            getData();
        });
    }

    var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
</script>
@stop