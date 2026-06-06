@extends('layouts.master')

@section('styles')
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
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
       FILTER CARD
    ══════════════════════════════════════ */
    .filter-card {
        background: #fff; border-radius: 14px; padding: 18px 24px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        margin-bottom: 20px; display: flex; align-items: flex-end; gap: 14px; flex-wrap: wrap;
    }
    .filter-group { display: flex; flex-direction: column; gap: 5px; }
    .filter-group label { font-size: 11px; font-weight: 700; color: #718096; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .filter-group .iw { position: relative; }
    .filter-group .iw i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 13px; pointer-events: none; }
    .filter-group .iw input {
        border: 1.5px solid #e2e8f0; border-radius: 9px;
        padding: 9px 12px 9px 34px; font-size: 13px; color: #1a202c;
        background: #fafbff; outline: none; width: 300px;
        transition: border-color .18s, box-shadow .18s;
    }
    .filter-group .iw input:focus { border-color: #605ca8; box-shadow: 0 0 0 3px rgba(96,92,168,.1); }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 32px;
    }
    .table-card-header {
        padding: 16px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title { font-size: 14px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 10px; }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* Legend */
    .legend-row { display: flex; align-items: center; gap: 14px; }
    .legend-item { display: flex; align-items: center; gap: 6px; font-size: 12px; color: #718096; font-weight: 500; }
    .legend-dot  { width: 10px; height: 10px; border-radius: 3px; flex-shrink: 0; }

    /* ── Matrix table ── */
    .table-wrap { overflow-x: auto; }

    /* Samakan ukuran th dan td */
    #tableShot {
        table-layout: fixed !important;
        width: 100% !important;
    }

    #tableShot th,
    #tableShot td {
        padding: 11px 12px !important;
        vertical-align: middle !important;
        box-sizing: border-box !important;
    }

    /* Pastikan width mengikuti header */
    #tableShot thead th,
    #tableShot tbody td {
        overflow: hidden;
        text-overflow: ellipsis;
    }

    /* Semua kolom month konsisten */
    #tableShot th.month-th,
    #tableShot td.month-td {
        width: 120px !important;
        min-width: 120px !important;
        max-width: 120px !important;
        text-align: center !important;
    }

    /* Kolom fixed */
    #tableShot th:nth-child(1),
    #tableShot td:nth-child(1) {
        width: 46px !important;
    }

    #tableShot th:nth-child(2),
    #tableShot td:nth-child(2) {
        width: 110px !important;
    }

    #tableShot th:nth-child(3),
    #tableShot td:nth-child(3) {
        width: 220px !important;
    }

    /* Month columns */
    #tableShot thead th.month-th {
        background: linear-gradient(135deg, #2d2b4e, #4a4690) !important;
        color: #c9c6f0 !important;
        min-width: 120px !important;
        width: 120px !important;
        white-space: nowrap !important;
        text-align: center !important;
        border-right: 1px solid #3d3a60 !important;
        padding: 11px 12px !important;
        vertical-align: middle !important;
    }

    /* Suppress DataTable sort arrows on month columns */
    #tableShot thead th.month-th::after,
    #tableShot thead th.month-th::before { display: none !important; }

    /* sorting padding only for sortable columns */
    #tableShot thead th.sorting,
    #tableShot thead th.sorting_asc,
    #tableShot thead th.sorting_desc { padding-right: 26px !important; }
    #tableShot thead th.month-th.sorting,
    #tableShot thead th.month-th.sorting_asc,
    #tableShot thead th.month-th.sorting_desc { padding-right: 12px !important; }

    /* hover */
    #tableShot tbody tr:hover td { background: #f5f8ff !important; }
    #tableShot tbody tr:last-child td { border-bottom: none !important; }

    #tableShot tbody tr:hover td { background: rgba(96,92,168,.05) !important; }

    #tableShot tbody td {
        padding: 10px 12px; font-size: 12.5px; color: #2d3748;
        border: 1px solid #edf0f5; vertical-align: middle;
    }

    /* Fixed columns */
    #tableShot tbody td:nth-child(1) { text-align: center; font-weight: 600; color: #a0aec0; font-size: 12px; }
    #tableShot tbody td:nth-child(2) { font-family: monospace; font-size: 12px; font-weight: 700; color: #605ca8; white-space: nowrap; }
    #tableShot tbody td:nth-child(3) { font-weight: 600; color: #1a202c; border-right: 2px solid #605ca8; }

    /* Shot cell states */
    .shot-cell-ok {
        background: #f0fdf4 !important;
        text-align: center;
        cursor: default;
    }
    .shot-cell-ok .shot-val { font-size: 12px; font-weight: 700; color: #15803d; }
    .shot-cell-ok .shot-acc { font-size: 10.5px; color: #86efac; margin-top: 2px; }

    .shot-cell-miss {
        background: #fff8f8 !important;
        text-align: center;
        cursor: pointer;
        transition: background .18s;
    }
    .shot-cell-miss:hover { background: #fee2e2 !important; }
    .shot-cell-miss .shot-dash { font-size: 14px; color: #fca5a5; }

    .shot-cell-latest {
        background: #f0eef9 !important;
        cursor: pointer;
    }

    /* Progress bar inside shot cell */
    .shot-progress { margin-top: 5px; height: 3px; border-radius: 10px; background: #dcfce7; overflow: hidden; }
    .shot-progress-fill { height: 100%; border-radius: 10px; background: #22c55e; }

    /* DataTable minimal */
    .dataTables_wrapper { padding: 12px 20px 16px !important; }
    .dataTables_filter label, .dataTables_length label { font-size: 13px !important; color: #4a5568 !important; }
    .dataTables_filter input, .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 5px 10px !important; font-size: 13px !important; outline: none !important;
    }
    .dataTables_filter { float: right !important; text-align: right !important; }
    .dataTables_info { font-size: 12px !important; color: #718096 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important; font-size: 13px !important; font-weight: 600 !important;
        color: #4a5568 !important; border: 1px solid transparent !important;
        padding: 5px 10px !important; margin: 0 2px !important;
    }
    .dataTables_paginate .paginate_button:hover { background: #ede9fe !important; color: #605ca8 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg,#4a4690,#605ca8) !important; color: #fff !important; }

    /* ══════════════════════════════════════
       MODAL SHOT
    ══════════════════════════════════════ */
    .modal-content { border: none; border-radius: 16px; box-shadow: 0 12px 48px rgba(0,0,0,.18); overflow: hidden; }
    .mh {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 24px; background: linear-gradient(135deg, #2d2b4e, #605ca8);
    }
    .mh h4 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
    .mh-close {
        background: rgba(255,255,255,.18); border: none; color: #fff;
        width: 30px; height: 30px; border-radius: 8px; cursor: pointer;
        font-size: 17px; display: flex; align-items: center; justify-content: center;
        transition: background .18s; line-height: 1;
    }
    .mh-close:hover { background: rgba(255,255,255,.3); }
    .mb { padding: 24px; }

    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }

    .info-val {
        border: 1.5px solid #c4bfef; border-radius: 9px; padding: 9px 13px;
        font-size: 13px; color: #605ca8; background: #f0eef9; font-weight: 600;
    }
    .info-label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 5px; display: block; }

    .ff { display: flex; flex-direction: column; gap: 6px; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff label .req { color: #e03131; }
    .ff input {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input:focus { border-color: #605ca8 !important; background: #fff !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important; }
    .ff input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }

    .shot-input-row {
        display: flex; gap: 12px; align-items: flex-end;
        background: #fafbff; border: 1.5px solid #e2e8f0; border-radius: 12px;
        padding: 16px; margin: 18px 0;
    }
    .shot-input-row .ff { flex: 1; }

    .modal-table { width: 100%; border-collapse: collapse; }
    .modal-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 10px 12px;
        border-bottom: 1.5px solid #edf0f5;
    }
    .modal-table tbody td {
        padding: 9px 12px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }
    .modal-table tbody tr:last-child td { border-bottom: none; }

    .mf { display: flex; gap: 10px; padding: 16px 24px; border-top: 1px solid #f0f2f7; background: #fafbff; justify-content: flex-end; }
    .btn-mf-cancel {
        padding: 10px 18px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #718096; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .18s;
    }
    .btn-mf-cancel:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }
    .btn-mf-save {
        display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px;
        border: none; border-radius: 9px; background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 12px rgba(96,92,168,.3); transition: all .18s;
    }
    .btn-mf-save:hover { opacity: .88; transform: translateY(-1px); }

    table#tableShot {
    table-layout: fixed !important;
    width: 100% !important;
    border-collapse: collapse !important;
    }

    table#tableShot th,
    table#tableShot td {
        padding: 12px !important;  /* Samakan padding semua */
        vertical-align: middle !important;
        box-sizing: border-box !important;
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    /* Width per kolom - terapkan identik ke th dan td */
    table#tableShot th:nth-child(1),
    table#tableShot td:nth-child(1) { width: 46px !important; }

    table#tableShot th:nth-child(2),
    table#tableShot td:nth-child(2) { width: 110px !important; }

    table#tableShot th:nth-child(3),
    table#tableShot td:nth-child(3) { width: 220px !important; }

    table#tableShot th.month-th,
    table#tableShot td.month-td { width: 120px !important; }
</style>
@endsection

@section('content')

<meta name="csrf-token" content="{{ csrf_token() }}">

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
            <div class="badge-tag"><i class="fas fa-chart-bar"></i> Diagnosa Molding</div>
            <h1>Riwayat Shot Molding</h1>
            <p>Monitor jumlah shot per molding dalam rentang waktu tertentu</p>
        </div>
    </div>

    {{-- ── FILTER CARD ── --}}
    <div class="filter-card">
        <div class="filter-group">
            <label>Rentang Bulan</label>
            <div class="iw">
                <i class="fas fa-calendar-alt"></i>
                <input type="text" id="month_range" placeholder="Pilih rentang bulan..." autocomplete="off">
            </div>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Matrix Shot per Periode
            </div>
            <div class="legend-row">
                <div class="legend-item">
                    <span class="legend-dot" style="background:#f0fdf4;border:1.5px solid #22c55e;"></span> Ada Shot
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#fff8f8;border:1.5px solid #fca5a5;"></span> Belum Ada
                </div>
                <div class="legend-item">
                    <span class="legend-dot" style="background:#f0eef9;border:1.5px solid #605ca8;"></span> Bulan Ini (klik untuk input)
                </div>
            </div>
        </div>

        <div class="table-wrap">
            <table id="tableShot" style="width:100%">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>FA Number</th>
                        <th style="text-align: center;">Nama Molding</th>
                        @foreach ($month_range_grouped as $month)
                            <th class="month-th">
                                <div style="font-size:11px;font-weight:700;letter-spacing:.5px;">{{ $month[1] }}</div>
                                <div style="font-size:10px;opacity:.65;font-weight:400;margin-top:2px;">{{ $month[0] }}</div>
                            </th>
                        @endforeach
                    </tr>
                </thead>
                <tbody id="bodyTableShot"></tbody>
            </table>
        </div>
    </div>

</div>

{{-- ══════════════════════════════════════
     MODAL: RIWAYAT SHOT
══════════════════════════════════════ --}}
<div class="modal fade" id="modal_shot" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:780px">
        <div class="modal-content">
            <div class="mh">
                <h4><i class="fas fa-chart-bar" style="margin-right:8px;opacity:.8;"></i> Riwayat Shot Molding</h4>
                <button type="button" class="mh-close" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="mb">
                <div class="g2" style="margin-bottom:18px;">
                    <div>
                        <span class="info-label">Fixed Asset Number</span>
                        <div class="info-val" id="fa_number_disp">—</div>
                        <input type="hidden" id="fa_number">
                    </div>
                    <div>
                        <span class="info-label">Nama Molding</span>
                        <div class="info-val" id="nama_molding_disp">—</div>
                        <input type="hidden" id="nama_molding">
                    </div>
                </div>

                <div class="shot-input-row">
                    <div class="ff">
                        <label>Periode</label>
                        <div style="position:relative;">
                            <i class="fas fa-calendar-alt" style="position:absolute;left:12px;top:50%;transform:translateY(-50%);color:#a0aec0;font-size:12px;"></i>
                            <input type="text" id="period" readonly value="{{ date('Y-m') }}" style="padding-left:34px !important;">
                        </div>
                    </div>
                    <div class="ff">
                        <label>Jumlah Shot <span class="req">*</span></label>
                        <input type="text" id="shot" placeholder="Masukkan jumlah shot" inputmode="numeric">
                    </div>
                    <div>
                        <button type="button" class="btn-mf-save" style="padding:9px 16px;" onclick="updateShot()">
                            <i class="fas fa-sync-alt"></i> Update Shot
                        </button>
                    </div>
                </div>

                <div style="font-size:13px;font-weight:700;color:#1a202c;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-history" style="color:#605ca8;"></i> Riwayat Input Shot
                </div>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th style="width:42px">No</th>
                            <th>Tanggal</th>
                            <th>Jumlah Shot</th>
                            <th>Total Akumulatif</th>
                            <th>Input By</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyShot"></tbody>
                </table>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-bs-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
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

    var check_point = [];
    var moldings        = <?php echo json_encode($master_molding); ?>;
    var month_range_grouped = <?php echo json_encode($month_range_grouped); ?>;
    var parts = [];
    var part_status = [];
    var part_err = [];
    var num = 1;
    const compressedFiles = [];

    var audio_error   = new Audio('{{ url('sounds/error.mp3') }}');
    var audio_success = new Audio('{{ url('sounds/success.mp3') }}');

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    jQuery(document).ready(function () {
        $('body').toggleClass("sidebar-collapse");
        $('#side_diagnosa_molding').addClass('menu-open');

        getData();

        /* Shot input — numeric only */
        $('#shot').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        /* Daterangepicker */
        $('#month_range').daterangepicker({
            autoUpdateInput: false,
            locale: { cancelLabel: 'Clear', format: 'MM/YYYY' },
            ranges: {
                'Bulan Ini':   [moment().startOf('month'), moment().endOf('month')],
                'Bulan Lalu':  [moment().subtract(1, 'month').startOf('month'), moment().subtract(1, 'month').endOf('month')]
            },
            minDate: moment().subtract(12, 'month').startOf('month'),
            maxDate: moment().endOf('month'),
            opens: 'left', drops: 'down'
        }, function (start, end, label) {
            $('#month_range').val(start.format('MM/YYYY') + ' - ' + end.format('MM/YYYY'));
            getData(); // Reload data when date range changes
        });
    });

    /* ══════════════════════════════════════
       DATE HELPERS — ORIGINAL
    ══════════════════════════════════════ */
    function parseDate(str) {
        return new Date(str.replace(/(\d{2}) (\w{3}) (\d{2})/, '$1 $2 20$3'));
    }

    function normalize(d) {
        if (!d || !(d instanceof Date)) return null;
        return new Date(d.getFullYear(), d.getMonth(), d.getDate());
    }

    /* ══════════════════════════════════════
       getData — ORIGINAL
    ══════════════════════════════════════ */
    function getData() {
        $('#loading').addClass('show');
        num = 1;

        let arr = Object.values(month_range_grouped);

        let month_range_grouped_new = arr.map(row => {
            let date1 = parseDate(row[0]);
            let date2 = parseDate(row[1]);
            return [ row[0], row[1], date1, date2 ];
        });

        var params = {
            start_date: month_range_grouped_new[month_range_grouped_new.length - 1][3].toISOString().split('T')[0],
            end_date:   month_range_grouped_new[0][2].toISOString().split('T')[0]
        };

        $.get('{{ url('fetch/diagnose_molding/molding_shot') }}', params, function (result) {
            $('#loading').removeClass('show');

            if (result.status) {
                let data_new = result.data.map(item => {
                    let [y, m, d] = item.create_date.split('-');
                    return { ...item, create_date_obj: new Date(y, m - 1, d) };
                });

                var body = "";
                var isFirstMonth = true;

                $.each(moldings, function (key, value) {
                    body += "<tr>";
                    body += "<td>" + num + "</td>";
                    body += "<td>" + value.fixed_asset_number + "</td>";
                    body += "<td>" + value.fixed_asset_name + "</td>";

                    isFirstMonth = true;

                    $.each(month_range_grouped_new, function (index, week) {
                        let hasil = data_new.filter(item => {
                            let itemDate  = normalize(item.create_date_obj);
                            let endDate   = normalize(week[2]);
                            let startDate = normalize(week[3]);
                            return itemDate >= startDate && itemDate <= endDate && item.fixed_asset_number === value.fixed_asset_number;
                        });

                        var onclick = "";
                        var extraClass = "";

                        if (isFirstMonth) {
                            onclick = "onclick='openModalShot(\"" + value.fixed_asset_number + "\", \"" + value.fixed_asset_name + "\")'";
                            extraClass = "shot-cell-latest";
                            isFirstMonth = false;
                        }

                        if (hasil.length > 0) {
                            body += "<td class='month-td shot-cell-ok' title='Shot: " + hasil[0].total_shot + " | Akumulatif: " + hasil[0].accumulative_shot + "'>";
                            body += "<div class='shot-val'>" + parseInt(hasil[0].total_shot).toLocaleString('id-ID') + "</div>";
                            body += "<div class='shot-acc'>∑ " + parseInt(hasil[0].accumulative_shot).toLocaleString('id-ID') + "</div>";
                            body += "</td>";
                        } else {
                            body += "<td class='month-td shot-cell-miss " + extraClass + "' " + onclick + " title='Klik untuk input shot'>";
                            body += "<div class='shot-dash'><i class='fas fa-minus'></i></div>";
                            body += "</td>";
                        }
                    });

                    body += "</tr>";
                    num++;
                });

                $("#bodyTableShot").html(body);

                if ($.fn.DataTable.isDataTable('#tableShot')) {
                    $('#tableShot').DataTable().destroy();
                }

                $('#tableShot').DataTable({
                    scrollX: false,
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
                    order: [[2, 'asc']],
                    columnDefs: [
                        { targets: [0,1,3,4,5,6], width: 'auto', className: 'dt-head-center dt-body-center' },
                        { targets: 0, width: '46px' },
                        { targets: 1, width: '110px' },
                        { targets: 2, width: '220px' }
                    ]
                });
            }
        });
    }

    /* ══════════════════════════════════════
       openModalShot — ORIGINAL
    ══════════════════════════════════════ */
    function openModalShot(asset_number, asset_name) {
        $('#modal_shot').modal('show');
        $('#fa_number').val(asset_number || '');
        $('#fa_number_disp').text(asset_number || '—');
        $('#nama_molding').val(asset_name || '');
        $('#nama_molding_disp').text(asset_name || '—');
        $('#shot').val('');

        $.get('{{ url('fetch/diagnose_molding/shot_list') }}', { asset_number: asset_number }, function (result) {
            var tableData = "";
            $('#tbodyShot').empty();
            if (result.status && result.data.length > 0) {
                var no = 1;
                $.each(result.data, function (key, value) {
                    tableData += '<tr>';
                    tableData += '<td style="text-align:center;">' + no++ + '</td>';
                    tableData += '<td>' + value.created_at + '</td>';
                    tableData += '<td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;">' + parseInt(value.total_shot).toLocaleString('id-ID') + '</td>';
                    tableData += '<td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;">∑ ' + parseInt(value.accumulative_shot).toLocaleString('id-ID') + '</td>';
                    tableData += '<td>' + value.created_by + '</td>';
                    tableData += '<td></td>';
                    tableData += '</tr>';
                });
            } else {
                tableData = '<tr><td colspan="6" style="text-align:center;color:#a0aec0;padding:16px;">Belum ada riwayat shot.</td></tr>';
            }
            $('#tbodyShot').html(tableData);
        });
    }

    /* ══════════════════════════════════════
       updateShot — ORIGINAL
    ══════════════════════════════════════ */
    function updateShot() {
        var asset_number = $('#fa_number').val();
        var shot         = $('#shot').val();

        if (!shot) { toastr.error('Jumlah shot harus diisi'); audio_error.play(); return; }

        $.post('{{ url('update/diagnose_molding/shot') }}', {
            asset_number: asset_number,
            molding_name: $('#nama_molding').val(),
            period: $('#period').val(),
            shot: shot
        }, function (result) {
            if (result.status) {
                toastr.success(result.message);
                audio_success.play();
                $('#shot').val('');

                $.get('{{ url('fetch/diagnose_molding/shot_list') }}', { asset_number: asset_number }, function (res) {
                    var tableData = "";
                    if (res.status && res.data.length > 0) {
                        var no = 1;
                        $.each(res.data, function (key, value) {
                            tableData += '<tr>';
                            tableData += '<td>' + no++ + '</td>';
                            tableData += '<td>' + value.created_at + '</td>';
                            tableData += '<td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;">' + parseInt(value.total_shot).toLocaleString('id-ID') + '</td>';
                            tableData += '<td style="text-align:right;font-variant-numeric:tabular-nums;font-weight:600;">∑ ' + parseInt(value.accumulative_shot).toLocaleString('id-ID') + '</td>';
                            tableData += '<td>' + value.created_by + '</td>';
                            tableData += '<td></td>';
                            tableData += '</tr>';
                        });
                    } else {
                        tableData = '<tr><td colspan="6" style="text-align:center;color:#a0aec0;padding:16px;">Belum ada riwayat shot.</td></tr>';
                    }
                    $('#tbodyShot').html(tableData);

                    /* reload after 2s — original behaviour */
                    setTimeout(function () { location.reload(); }, 2000);
                });
            } else {
                toastr.error(result.message);
                audio_error.play();
            }
        });
    }
</script>
@endsection