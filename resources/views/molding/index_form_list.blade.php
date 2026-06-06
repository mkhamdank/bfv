@extends('layouts.master')

@section('styles')
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    #loading, #error { display: none; }

    /* ══════════════════════════════════════
       LOADING OVERLAY
    ══════════════════════════════════════ */
    #loading {
        position: fixed; inset: 0;
        background: rgba(30,20,60,.45);
        backdrop-filter: blur(4px);
        z-index: 9999;
        display: flex !important;
        align-items: center;
        justify-content: center;
    }
    .loading-box {
        background: #fff; border-radius: 20px;
        padding: 36px 48px;
        display: flex; flex-direction: column;
        align-items: center; gap: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px;
        border: 3px solid #e2e8f0;
        border-top-color: #605ca8;
        border-radius: 50%;
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
        border-radius: 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .page-header-modern::after {
        content: ''; position: absolute; left: 30%; bottom: -60px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.03);
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    .btn-add-report {
        background: #fff; color: #4a4690;
        border: none; border-radius: 12px;
        padding: 12px 22px; font-size: 13px; font-weight: 700;
        cursor: pointer; display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,.18); text-decoration: none;
        transition: all .2s; position: relative; z-index: 1;
    }
    .btn-add-report:hover { transform: translateY(-2px); box-shadow: 0 8px 24px rgba(0,0,0,.22); color: #605ca8; text-decoration: none; }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px; border-bottom: 1px solid #f0f2f7;
        background: #fafbff; display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title {
        font-size: 14px; font-weight: 700; color: #1a202c;
        display: flex; align-items: center; gap: 10px;
    }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── DataTable ── */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_filter label, .dataTables_length label {
        font-size: 13px !important; color: #4a5568 !important; font-weight: 500 !important;
        display: flex !important; align-items: center !important; gap: 8px !important;
    }
    .dataTables_filter input, .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 6px 10px !important; font-size: 13px !important; outline: none !important;
    }
    .dataTables_filter input:focus { border-color: #605ca8 !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important; }

    table#tableMaster { border-collapse: collapse !important; width: 100% !important; }
    table#tableMaster thead th {
        background: #f7f8fc !important; color: #718096 !important;
        font-size: 11px !important; font-weight: 700 !important;
        letter-spacing: .7px !important; text-transform: uppercase !important;
        padding: 12px 14px !important;
        border-bottom: 2px solid #edf0f5 !important; border-top: none !important;
        white-space: nowrap; text-align: center !important;
    }
    table#tableMaster tbody td {
        padding: 10px 12px !important; font-size: 12px !important; color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important; border-top: none !important;
        border-left: none !important; border-right: none !important;
        vertical-align: middle !important; text-align: center !important;
    }
    table#tableMaster tbody tr:hover td { background: #f5f3ff !important; }
    table#tableMaster tbody tr:last-child td { border-bottom: none !important; }

    .dataTables_info { font-size: 12px !important; color: #718096 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important; font-size: 13px !important; font-weight: 600 !important;
        color: #4a5568 !important; border: 1px solid transparent !important;
        padding: 5px 10px !important; margin: 0 2px !important;
    }
    .dataTables_paginate .paginate_button:hover { background: #ebf2ff !important; color: #2d6bc4 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg, #2d6bc4, #1a4d9a) !important; color: #fff !important; }

    /* Export buttons */
    .dt-buttons { display: flex; gap: 6px; flex-wrap: wrap; margin-bottom: 8px; }
    .btn-dt { display: inline-flex !important; align-items: center !important; gap: 5px !important;
        padding: 6px 12px !important; border-radius: 8px !important;
        font-size: 12px !important; font-weight: 600 !important;
        border: 1.5px solid !important; cursor: pointer !important; transition: opacity .18s !important; }
    .btn-dt:hover { opacity: .82 !important; }
    .btn-dt-copy  { background: #f0fdf4 !important; color: #15803d !important; border-color: #bbf7d0 !important; }
    .btn-dt-excel { background: #f0fdf4 !important; color: #15803d !important; border-color: #bbf7d0 !important; }
    .btn-dt-showall { background: #ebf2ff !important; color: #2d6bc4 !important; border-color: #c3d9f8 !important; }

    /* Row number */
    .row-num { font-weight: 700; font-size: 12px; color: #a0aec0; }

    /* Form number badge */
    .form-num-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #ede9fe; color: #5b21b6;
        font-size: 11px; font-weight: 600;
        padding: 3px 9px; border-radius: 6px; white-space: nowrap;
    }

    /* FA number badge */
    .fa-num-badge {
        display: inline-flex; align-items: center;
        background: #ebf2ff; color: #2d6bc4;
        font-size: 11px; font-weight: 600;
        padding: 3px 9px; border-radius: 6px; white-space: nowrap;
    }

    /* Month badge */
    .month-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f0f5ff; color: #4a5568;
        font-size: 11px; font-weight: 600;
        padding: 3px 9px; border-radius: 6px;
    }

    /* Status badges */
    .status-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 10px; border-radius: 20px;
        font-size: 11px; font-weight: 700; white-space: nowrap;
    }
    .status-badge::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .s-approval { background: #fef3c7; color: #b45309; } .s-approval::before { background: #b45309; }
    .s-approved  { background: #dcfce7; color: #15803d; } .s-approved::before  { background: #15803d; }
    .s-rejected  { background: #fee2e2; color: #dc2626; } .s-rejected::before  { background: #dc2626; }
    .s-holded    { background: #f1f5f9; color: #475569; } .s-holded::before    { background: #475569; }
    .s-draft     { background: #e0e7ff; color: #3730a3; } .s-draft::before     { background: #3730a3; }

    /* Action micro-buttons */
    .act-btn {
        display: inline-flex; align-items: center; gap: 4px;
        padding: 5px 10px; border-radius: 7px;
        font-size: 11px; font-weight: 600;
        text-decoration: none; border: none; cursor: pointer;
        transition: opacity .15s, transform .15s; white-space: nowrap;
        margin: 2px;
    }
    .act-btn:hover { opacity: .82; transform: translateY(-1px); text-decoration: none; }
    .ab-edit   { background: #ebf2ff; color: #2d6bc4; }
    .ab-create { background: #dcfce7; color: #15803d; }
    .ab-pdf    { background: #fee2e2; color: #dc2626; }
    .ab-send   { background: #dcfce7; color: #15803d; }
    .ab-resend { background: #ebf2ff; color: #2d6bc4; }

    .action-group { display: flex; flex-wrap: wrap; justify-content: center; gap: 2px; }

    /* ══════════════════════════════════════
       MODAL
    ══════════════════════════════════════ */
    .modal-content { border-radius: 18px !important; overflow: hidden !important; border: none !important; }
    .modal-hdr {
        background: linear-gradient(135deg, #2d2b4e, #605ca8);
        padding: 20px 24px;
        display: flex; align-items: center; justify-content: space-between;
    }
    .modal-hdr h3 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; display: flex; align-items: center; gap: 8px; }
    .modal-hdr .close { color: rgba(255,255,255,.7) !important; font-size: 20px; opacity: 1 !important; padding: 0; margin: 0; }
    .modal-hdr .close:hover { color: #fff !important; }
    .modal-body-custom { padding: 24px; }
    .modal-footer-custom { padding: 16px 24px; border-top: 1px solid #f0f2f7; display: flex; gap: 10px; justify-content: flex-end; }

    .form-lbl { display: block; font-size: 11px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: .6px; margin-bottom: 6px; }
    .btn-modal-ok {
        background: linear-gradient(135deg, #4a4690, #605ca8); color: #fff;
        border: none; border-radius: 10px; padding: 10px 24px;
        font-size: 13px; font-weight: 700; cursor: pointer;
        display: inline-flex; align-items: center; gap: 7px; transition: opacity .18s;
    }
    .btn-modal-ok:hover { opacity: .88; }
    .btn-modal-cancel {
        background: #f0f2f7; color: #718096; border: none;
        border-radius: 10px; padding: 10px 20px;
        font-size: 13px; font-weight: 600; cursor: pointer; transition: background .18s;
    }
    .btn-modal-cancel:hover { background: #e2e8f0; }

    /* Select2 in modal */
    .select2-container .select2-selection--single {
        height: 42px !important; border: 1.5px solid #e2e8f0 !important;
        border-radius: 10px !important; background: #fafbff !important;
        display: flex !important; align-items: center !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        font-size: 13px !important; font-weight: 500 !important; color: #2d3748 !important;
        line-height: 42px !important; padding-left: 14px !important; padding-right: 32px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 42px !important; right: 10px !important; }
    .select2-dropdown { border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: 0 8px 24px rgba(0,0,0,.1) !important; overflow: hidden !important; }
    .select2-results__option { font-size: 13px !important; padding: 9px 14px !important; }
    .select2-results__option--highlighted { background: #ede9fe !important; color: #4a4690 !important; }

    /* Molding name cell */
    td.td-name { text-align: left !important; font-weight: 600; }
</style>
@stop

@section('header')
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid" style="padding: 0 20px; max-width: 1400px; margin: 0 auto;">

    {{-- LOADING --}}
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Memuat data...</p>
        </div>
    </div>

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-tasks"></i>&nbsp; Diagnosa Molding</div>
            <h1>Daftar Form Diagnosa Molding</h1>
            <p>Kelola form inspeksi produk, molding, dan evaluasi</p>
        </div>
        <button class="btn-add-report" data-toggle="modal" data-target="#modalCreateForm">
            <i class="fas fa-plus"></i> Buat Form
        </button>
    </div>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                Daftar Form
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="table" id="tableMaster" width="100%">
                <thead>
                    <tr>
                        <th style="width:40px;">No</th>
                        <th>Create Month</th>
                        <th>Form Number</th>
                        <th>FA Number</th>
                        <th style="text-align:left !important;">Nama Molding</th>
                        <th>Location</th>
                        <th>Form Produk</th>
                        <th>Form Molding</th>
                        <th>Form Evaluasi</th>
                        <th>Status</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody id="bodyTableMaster"></tbody>
            </table>
        </div>
    </div>

</div>

{{-- MODAL BUAT FORM --}}
<div class="modal fade" id="modalCreateForm" role="dialog">
    <div class="modal-dialog" style="max-width:480px;">
        <div class="modal-content">
            <div class="modal-hdr">
                <h3><i class="fas fa-plus-circle"></i> Buat Form Baru</h3>
                <button type="button" class="close" data-dismiss="modal">&times;</button>
            </div>
            <div class="modal-body-custom">
                <label class="form-lbl">Pilih Molding <span style="color:#dc2626;">*</span></label>
                <select class="form-control select2" id="createMolding" style="width:100%;" data-placeholder="Cari & pilih Molding...">
                    <option value=""></option>
                    @foreach ($moldings as $molding)
                        <option value="{{ $molding->fixed_asset_number }}">{{ $molding->fixed_asset_number }} - {{ $molding->fixed_asset_name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="modal-footer-custom">
                <button type="button" class="btn-modal-cancel" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button type="button" class="btn-modal-ok" id="btnSaveForm"><i class="fas fa-check"></i> OK</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ url('js/bootstrap-toggle.min.js') }}"></script>
<script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
<script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('js/buttons.flash.min.js') }}"></script>
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/vfs_fonts.js') }}"></script>
<script src="{{ url('js/buttons.html5.min.js') }}"></script>
<script src="{{ url('js/buttons.print.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var audio_error   = new Audio('{{ url("sounds/error.mp3") }}');
    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');

    jQuery(document).ready(function () {
        $("#wrapper").toggleClass("toggled");
        $('#side_diagnosa_molding').addClass('menu-open');
        $('body').toggleClass("sidebar-collapse");
        getData();
    });

    /* ── Helper: status badge ── */
    function statusBadge(status) {
        if (!status) return '<span class="status-badge s-draft">Draft</span>';
        var map = { 'Approval':'s-approval','Approved':'s-approved','Rejected':'s-rejected','Holded':'s-holded' };
        var cls = map[status] || 's-draft';
        return '<span class="status-badge ' + cls + '">' + (status || 'Draft') + '</span>';
    }

    /* ── Helper: can edit? ── */
    function canEdit(status) {
        return !status || (status !== 'Approval' && status !== 'Approved' && status !== 'Rejected' && status !== 'Holded');
    }

    /* ── getData ── */
    function getData() {
        $('#loading').show();

        $.ajax({
            url: '{{ url("fetch/diagnose_molding/molding_form") }}',
            method: 'GET',
            dataType: 'json',
            success: function (result) {
                if (result.status) {
                    $("#sidebar-toggle").click();

                    var rows = '';
                    $.each(result.data, function (key, v) {
                        rows += '<tr>';
                        rows += '<td><span class="row-num"></span></td>';
                        rows += '<td><span class="month-badge"><i class="fas fa-calendar-alt" style="font-size:10px;"></i> ' + (v.month || '—') + '</span></td>';
                        rows += '<td><span class="form-num-badge"><i class="fas fa-file-alt" style="font-size:9px;"></i> ' + v.form_number + '</span></td>';
                        rows += '<td><span class="fa-num-badge">' + (v.fixed_asset_number || '—') + '</span></td>';
                        rows += '<td class="td-name">' + (v.fixed_asset_name || '—') + '</td>';
                        rows += '<td>' + (v.vendor || '—') + '</td>';

                        /* ── Form Produk ── */
                        rows += '<td><div class="action-group">';
                        if (!v.id_product_check) {
                            if (v.form_product_id) {
                                if (canEdit(v.status)) {
                                    rows += '<a class="act-btn ab-edit" href="{{ url("index/diagnose_molding/mold_product_check/create") }}/' + v.form_number + '"><i class="fas fa-edit"></i> Edit</a>';
                                }
                                rows += '<a class="act-btn ab-pdf" href="{{ url("index/diagnose_molding/mold_product_check/report") }}/' + v.form_number + '" target="_blank"><i class="far fa-file-pdf"></i> Report</a>';
                                rows += '<a class="act-btn ab-pdf" href="{{ url("index/diagnose_molding/mold_product_check/report_ng") }}/' + v.form_number + '" target="_blank"><i class="far fa-file-pdf"></i> NG</a>';
                            } else {
                                rows += '<a class="act-btn ab-create" href="{{ url("index/diagnose_molding/mold_product_check/create") }}/' + v.form_number + '"><i class="fas fa-plus"></i> Buat</a>';
                            }
                        } else {
                            rows += '<span style="color:#a0aec0;font-size:11px;">Form</span>';
                        }
                        rows += '</div></td>';

                        /* ── Form Molding ── */
                        rows += '<td><div class="action-group">';
                        if (v.form_molding_id) {
                            if (canEdit(v.status)) {
                                rows += '<a class="act-btn ab-edit" href="{{ url("index/diagnose_molding/mold_molding_check/create") }}/' + v.form_number + '"><i class="fas fa-edit"></i> Edit</a>';
                            }
                            rows += '<a class="act-btn ab-pdf" href="{{ url("index/diagnose_molding/mold_molding_check/report") }}/' + v.form_number + '" target="_blank"><i class="far fa-file-pdf"></i> Report</a>';
                            rows += '<a class="act-btn ab-pdf" href="{{ url("index/diagnose_molding/mold_molding_check/report_ng") }}/' + v.form_number + '" target="_blank"><i class="far fa-file-pdf"></i> NG</a>';
                        } else if (canEdit(v.status)) {
                            rows += '<a class="act-btn ab-create" href="{{ url("index/diagnose_molding/mold_molding_check/create") }}/' + v.form_number + '"><i class="fas fa-plus"></i> Buat</a>';
                        }
                        rows += '</div></td>';

                        /* ── Form Evaluasi ── */
                        rows += '<td><div class="action-group">';
                        if (canEdit(v.status)) {
                            rows += '<a class="act-btn ab-edit" href="{{ url("index/diagnose_molding/evaluation/edit") }}/' + v.form_number + '"><i class="fas fa-edit"></i> Edit</a>';
                        }
                        rows += '<a class="act-btn ab-pdf" href="{{ url("index/diagnose_molding/evaluation/report") }}/' + v.form_number + '" target="_blank"><i class="far fa-file-pdf"></i> Report</a>';
                        rows += '</div></td>';

                        /* ── Status ── */
                        rows += '<td>' + statusBadge(v.status) + '</td>';

                        /* ── Action ── */
                        rows += '<td><div class="action-group">';
                        if (!v.status) {
                            rows += '<button class="act-btn ab-send" onclick="saveAndSend(\'' + v.form_number + '\')"><i class="fas fa-save"></i> Simpan & Kirim</button>';
                        } else if (v.status === 'Approval') {
                            rows += '<button class="act-btn ab-resend" onclick="resend(\'' + v.form_number + '\')"><i class="fas fa-paper-plane"></i> Resend</button>';
                        }
                        rows += '</div></td>';

                        rows += '</tr>';
                    });

                    $('#bodyTableMaster').html(rows);

                    var table = $('#tableMaster').DataTable({
                        dom: '<"row mb-2"<"col-md-6"B><"col-md-6 text-end"f>>' +
                             '<"row"<"col-12"tr>>' +
                             '<"row mt-2"<"col-md-6"i><"col-md-6"p>>',
                        buttons: [
                            { extend: 'copy',  className: 'btn-dt btn-dt-copy',  text: '<i class="fa fa-copy"></i> Copy' },
                            { extend: 'excel', className: 'btn-dt btn-dt-excel', text: '<i class="fa fa-file-excel"></i> Excel' },
                            {
                                text: '<i class="fas fa-list"></i> Show All',
                                className: 'btn-dt btn-dt-showall',
                                action: function () { window.location.href = '{{ url("index/diagnose_molding/molding_form") }}'; }
                            }
                        ],
                        paging: true, lengthChange: true, searching: true,
                        ordering: true, order: [[2, 'desc']],
                        info: true, autoWidth: false, responsive: true,
                        language: {
                            search: '', searchPlaceholder: 'Cari form...',
                            info: 'Menampilkan _START_–_END_ dari _TOTAL_ form',
                            paginate: { previous: '‹', next: '›' }
                        }
                    });

                    /* Auto-number kolom pertama */
                    table.on('order.dt search.dt draw.dt', function () {
                        var info = table.page.info();
                        table.column(0, { search: 'applied', order: 'applied', page: 'current' })
                            .nodes().each(function (cell, i) { cell.innerHTML = i + 1 + info.start; });
                    }).draw();

                    /* Filter by asset_number dari URL param */
                    var param = '{{ $asset_number }}';
                    if (param) {
                        if (param.includes('MLD')) table.column(2).search(param).draw();
                        else                       table.column(3).search(param).draw();
                    }
                }
            },
            error: function (xhr, status, error) {
                toastr.error('Gagal memuat data. Silakan coba ulang.');
                console.error('AJAX fetch error:', status, error, xhr.responseText);
            },
            complete: function () {
                var loading = document.getElementById('loading');
                if (loading) {
                    loading.style.setProperty('display', 'none', 'important');
                }
            }
        });
    }

    /* ── Modal Buat Form ── */
    $('#modalCreateForm').on('shown.bs.modal', function () {
        $('#createMolding').select2({
            dropdownParent: $('#modalCreateForm'),
            placeholder: 'Cari & pilih Molding...',
            allowClear: true
        });
    });

    $('#btnSaveForm').click(function () {
        var asset = $('#createMolding').val();
        if (!asset) { toastr.warning('Pilih molding terlebih dahulu.'); return; }
        $('#modalCreateForm').modal('hide');
        generate_form(asset);
    });

    function generate_form(asset_number) {
        window.location.href = '{{ url("generate/diagnose_molding/mold_product_check/new") }}?asset_number=' + asset_number;
    }

    /* ── Save & Send ── */
    function saveAndSend(formNumber) {
        Swal.fire({
            title: 'Simpan & Kirim?',
            text: 'Apakah anda yakin ingin menyimpan dan mengirim form ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-save"></i> Ya, Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#15803d',
            cancelButtonColor: '#718096',
            borderRadius: '12px'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.get('{{ url("save_and_send/diagnose_molding/molding_form") }}', { formNumber: formNumber }, function (res) {
                    if (res.status) { toastr.success(res.message); audio_success.play(); getData(); }
                    else            { toastr.error(res.message);   audio_error.play(); }
                });
            }
        });
    }

    /* ── Resend ── */
    function resend(formNumber) {
        Swal.fire({
            title: 'Kirim Ulang?',
            text: 'Apakah anda yakin ingin mengirim ulang form ini?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim',
            cancelButtonText: 'Batal',
            confirmButtonColor: '#2d6bc4',
            cancelButtonColor: '#718096'
        }).then(function (result) {
            if (result.isConfirmed) {
                $.get('{{ url("resend/diagnose_molding/molding_form") }}', { formNumber: formNumber }, function (res) {
                    if (res.status) { toastr.success(res.message); audio_success.play(); }
                    else            { toastr.error(res.message);   audio_error.play(); }
                });
            }
        });
    }

    /* ── Image helpers (dipertahankan) ── */
    function readURL(input, idfile) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = $(input).parent().find('.' + idfile);
                $(img).show();
                var tempImage = new Image();
                tempImage.onload = function () {
                    var canvas = document.createElement('canvas');
                    canvas.width = tempImage.width; canvas.height = tempImage.height;
                    canvas.getContext('2d').drawImage(tempImage, 0, 0);
                    $(img).attr('src', canvas.toDataURL('image/jpeg', 0.6));
                };
                tempImage.src = e.target.result;
            };
            reader.readAsDataURL(input.files[0]);
        }
    }

    function readURL2(input, idfile) {
        if (input.files && input.files[0]) {
            var reader = new FileReader();
            reader.onload = function (e) {
                var img = $(input).parent().find('.' + idfile);
                $(img).show(); $(img).attr('src', e.target.result);
            };
            reader.readAsDataURL(input.files[0]);
        }
    }
</script>
@endsection