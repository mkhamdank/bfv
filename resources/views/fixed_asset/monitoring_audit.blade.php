@extends('layouts.master')

@section('styles')
<link href="{{ url('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    /* ══ BASE ══ */
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="select2"]):not([class*="highcharts"]):not([class*="dataTables"]),
    body div:not([class*="highcharts"]):not([class*="dataTables"]),
    body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ══ LOADING ══ */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.42); backdrop-filter: blur(6px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 52px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 16px 48px rgba(0,0,0,.18);
    }
    .loading-spinner {
        width: 44px; height: 44px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══ PAGE HEADER ══ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 20px 0 22px;
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
    .header-left h1 {
        color: #fff !important; font-size: 24px !important; font-weight: 700 !important;
        margin: 0 0 4px !important; line-height: 1.2 !important;
    }
    .header-left p { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ══ FILTER CARD ══ */
    .filter-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .filter-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .filter-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }
    .filter-card-body {
        padding: 16px 22px;
        display: flex; align-items: center; gap: 14px; flex-wrap: wrap;
    }
    .select2-selection.select2-selection--single {
        height: 38px !important; border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
    }
    .select2-selection__arrow {
        height: 38px !important;
    }
    .ff { display: flex; flex-direction: column; gap: 6px; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; min-width: 240px;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff select:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .btn-search {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 22px; border-radius: 10px; border: none;
        background: linear-gradient(135deg,#4a4690,#605ca8); color: #fff;
        font-size: 13px; font-weight: 700; cursor: pointer;
        box-shadow: 0 3px 10px rgba(96,92,168,.3); transition: opacity .18s; white-space: nowrap;
    }
    .btn-search:hover { opacity: .88; }

    /* ══ CHART CARD ══ */
    .chart-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .chart-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .chart-card-header .dot { width: 8px; height: 8px; border-radius: 50%; }
    .dot-purple { background: #605ca8; }
    .dot-blue   { background: #3b82f6; }
    .chart-card-body { padding: 16px 20px; }
    #chart_check, #chart_audit { width: 100%; height: 340px; }



    /* ══ MODAL ══ */
    .mh {
        padding: 18px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .mh h4 { margin: 0; font-size: 15px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 9px; }
    .mh-close {
        width: 32px; height: 32px; border-radius: 8px; border: none;
        background: #fee2e2; color: #dc2626; font-size: 18px; cursor: pointer;
        display: flex; align-items: center; justify-content: center;
        flex-shrink: 0; position: relative; z-index: 10;
        line-height: 1;
    }
    .filter-label { font-size: 12px; font-weight: 700; color: #718096; text-transform: uppercase; letter-spacing: .7px; white-space: nowrap; }
    .mh-close:hover { background: #fecaca; }
    .mf {
        padding: 14px 24px; border-top: 1px solid #f0f2f7; background: #fafbff;
        display: flex; justify-content: flex-end;
    }
    .btn-mf-cancel {
        padding: 9px 20px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #718096; font-size: 13px; font-weight: 600; cursor: pointer;
        display: inline-flex; align-items: center; gap: 6px; transition: all .18s;
    }
    .btn-mf-cancel:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }

    /* ══ LEGEND CHIPS ══ */
    .legend-row {
        display: flex; flex-wrap: wrap; gap: 8px;
        padding: 10px 20px 14px;
    }
    .legend-chip {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 5px 14px; border-radius: 20px;
        font-size: 12px; font-weight: 700; border: 1.5px solid;
    }

    /* ══ DETAIL TABLE ══ */
    #table_details thead th {
        background: #f7f8fc !important; color: #718096 !important;
        font-size: 11px !important; font-weight: 700 !important; letter-spacing: .06em !important;
        text-transform: uppercase !important; padding: 10px 12px !important;
        border-bottom: 2px solid #edf0f5 !important; text-align: center !important;
        white-space: nowrap; border: none !important;
    }
    #table_details tbody td {
        padding: 10px 12px !important; font-size: 12.5px !important; color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important; vertical-align: middle !important;
        text-align: center !important; border: none !important;
    }
    #table_details { border: none !important; }
    #table_details.table-bordered { border: none !important; }

    /* status badge in table */
    .stat-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; white-space: nowrap;
    }
    .sb-notchecked { background: #fee2e2; color: #9b1c1c; }
    .sb-check1     { background: #fff3e0; color: #b45309; }
    .sb-check2     { background: #fef9c3; color: #92400e; }
    .sb-waiting    { background: #dbeafe; color: #1d4ed8; }
    .sb-approved   { background: #dcfce7; color: #15803d; }
    .sb-audited    { background: #dcfce7; color: #15803d; }
    .sb-finding    { background: #fef3c7; color: #92400e; }
    .sb-notaudited { background: #fee2e2; color: #9b1c1c; }

    /* checkpoint pre → styled div */
    .cp-block {
        text-align: left; background: #f7f8fc; border-radius: 8px;
        padding: 8px 12px; font-size: 11.5px; color: #4a5568; line-height: 1.7;
        border: 1px solid #edf0f5; min-width: 180px;
    }
    .cp-row { display: flex; gap: 6px; }
    .cp-key { color: #a0aec0; font-weight: 600; min-width: 90px; flex-shrink: 0; }
    .cp-val { color: #1a202c; font-weight: 600; }

    /* dataTables overrides */
    .dataTables_wrapper .dataTables_filter input {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 6px 12px !important; font-size: 13px !important;
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .dataTables_wrapper .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 5px 10px !important; font-size: 13px !important;
    }
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_paginate { font-size: 12.5px; color: #718096; }
    .dataTables_wrapper .paginate_button { border-radius: 6px !important; }
    .dataTables_wrapper .paginate_button.current {
        background: #605ca8 !important; color: #fff !important; border-color: #605ca8 !important;
    }

    .page-wrapper { padding-top: 0 !important; }
    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th { background-color: white; color: #696969 !important; }
</style>
@endsection

@section('header')@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Loading Overlay --}}
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
            <div class="badge-tag"><i class="fas fa-chart-bar"></i> Fixed Asset</div>
            <h1>Monitoring Audit</h1>
            <p>Progress pengecekan &amp; audit fixed asset per periode</p>
        </div>
    </div>

    {{-- ── FILTER CARD ── --}}
    <div class="filter-card">
        <div class="filter-card-header">
            <span class="dot dot-purple"></span> Filter Data
        </div>
        <div class="filter-card-body">
            <span class="filter-label"><i class="fas fa-filter"></i> Filter Period</span>
            <div style="flex:1;min-width:220px;max-width:380px;">
                <select class="select2" id="period" style="width:100%;" data-placeholder="— Pilih Periode —">
                    <option value=""></option>
                    @foreach ($period as $per)
                        <option value="{{ $per->period }}">{{ $per->period }}</option>
                    @endforeach
                </select>
            </div>
            <button class="btn-search" onclick="getData()">
                <i class="fas fa-search"></i> Filter
            </button>
        </div>
    </div>

    {{-- ── CHART: Progress Check ── --}}
    <div class="chart-card">
        <div class="chart-card-header">
            <span class="dot dot-purple" style="width:8px;height:8px;border-radius:50%;flex-shrink:0;"></span>
            Monitoring Progress Check
        </div>
        {{-- legend chips --}}
        <div class="legend-row" id="legend_check">
            <div class="legend-chip" style="background:#fff0f5;border-color:#d81b60;color:#9b1c1c;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#d81b60;flex-shrink:0;"></span>Not Checked
            </div>
            <div class="legend-chip" style="background:#fff3e0;border-color:#ff851b;color:#b45309;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ff851b;flex-shrink:0;"></span>Check 1
            </div>
            <div class="legend-chip" style="background:#fef9c3;border-color:#ffc107;color:#92400e;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ffc107;flex-shrink:0;"></span>Check 2
            </div>
            <div class="legend-chip" style="background:#dbeafe;border-color:#2caffe;color:#1d4ed8;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#2caffe;flex-shrink:0;"></span>Waiting Approval
            </div>
            <div class="legend-chip" style="background:#dcfce7;border-color:#00e272;color:#15803d;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#00e272;flex-shrink:0;"></span>Fully Approve
            </div>
        </div>
        <div class="chart-card-body">
            <div id="chart_check"></div>
        </div>
    </div>

    {{-- ── CHART: Progress Audit ── --}}
    <div class="chart-card" style="margin-bottom:32px;">
        <div class="chart-card-header">
            <span class="dot dot-blue" style="width:8px;height:8px;border-radius:50%;flex-shrink:0;"></span>
            Monitoring Progress Audit
        </div>
        <div class="legend-row">
            <div class="legend-chip" style="background:#ede9fe;border-color:#6d68de;color:#4338ca;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#6d68de;flex-shrink:0;"></span>Total Asset
            </div>
            <div class="legend-chip" style="background:#dcfce7;border-color:#19fb8b;color:#15803d;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#19fb8b;flex-shrink:0;"></span>Audited
            </div>
            <div class="legend-chip" style="background:#fef9c3;border-color:#ffc107;color:#92400e;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#ffc107;flex-shrink:0;"></span>Finding
            </div>
            <div class="legend-chip" style="background:#fee2e2;border-color:#d81b60;color:#9b1c1c;">
                <span style="display:inline-block;width:8px;height:8px;border-radius:50%;background:#d81b60;flex-shrink:0;"></span>Must Audit
            </div>
        </div>
        <div class="chart-card-body">
            <div id="chart_audit"></div>
        </div>
    </div>

</div>

{{-- ══ MODAL: Details ══ --}}
<div class="modal fade" id="modal_details" role="dialog" tabindex="-1" data-backdrop="true" data-keyboard="true">
    <div class="modal-dialog modal-xl" role="document" style="max-width:1200px;">
        <div class="modal-content" style="border-radius:16px;overflow:hidden;border:none;box-shadow:0 24px 64px rgba(0,0,0,.22);">
            <div class="mh">
                <h4 id="title_modal">
                    <i class="fas fa-table" style="color:#605ca8;"></i> Details
                </h4>
                <button type="button" class="mh-close" onclick="$('#modal_details').modal('hide');" aria-label="Close">&times;</button>
            </div>
            <div class="modal-body" id="container_table" style="padding:20px 24px;overflow-x:auto;"></div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" onclick="$('#modal_details').modal('hide');">
                    <i class="fas fa-times"></i> Close
                </button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>
<script src="{{ url('js/buttons.print.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<script src="{{ url('js/highcharts.js') }}"></script>
<script src="{{ url('js/exporting.js') }}"></script>
<script src="{{ url('js/export-data.js') }}"></script>
<script src="{{ url('js/accessibility.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var check_stat    = ['Not Checked', 'Check 1', 'Check 2', 'Waiting Approval', 'Fully Approve'];
    var colors = {
        'Not Checked'      : '#d81b60',
        'Check 1'          : '#ff851b',
        'Check 2'          : '#ffc107',
        'Waiting Approval' : '#2caffe',
        'Fully Approve'    : '#00e272'
    };
    var check_details = [];
    var audit_details = [];

    /* ── Highcharts global theme ── */
    Highcharts.setOptions({
        chart: {
            backgroundColor: '#fff',
            style: { fontFamily: "'Plus Jakarta Sans', sans-serif" },
            borderRadius: 0, plotBorderWidth: 0,
            spacingTop: 10, spacingBottom: 10
        },
        title: { style: { color: '#1a202c', fontWeight: '700', fontSize: '15px' }, text: null },
        xAxis: {
            lineColor: '#e2e8f0', gridLineColor: '#f0f2f7',
            labels: { style: { color: '#718096', fontSize: '12px', fontFamily: "'Plus Jakarta Sans', sans-serif" } }
        },
        yAxis: {
            gridLineColor: '#f0f2f7',
            title: { style: { color: '#a0aec0', fontSize: '12px' } },
            stackLabels: { style: { fontWeight: '700', color: '#4a5568', fontFamily: "'Plus Jakarta Sans', sans-serif" } }
        },
        legend: { enabled: false },
        credits: { enabled: false },
        tooltip: {
            backgroundColor: '#fff', borderColor: '#e2e8f0', borderRadius: 10,
            style: { color: '#1a202c', fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '13px' }
        }
    });

    jQuery(document).ready(function () {
        $('body').toggleClass('sidebar-collapse');
        $('#side_fixed_asset').addClass('menu-open');
        $('.select2').select2({ dropdownPosition: 'below' });
        getData();
    });

    /* ── GET DATA ── */
    function getData() {
        $('#loading').addClass('show');
        $.get('{{ url('fetch/fixed_asset/report') }}', { period: $('#period').val() }, function (result) {
            $('#loading').removeClass('show');

            /* ── BUILD CHECK CHART ── */
            var ctg = [], resume_check = [], series_check = [];
            check_details = result.details_check_data;
            audit_details = result.details_audited_data;

            $.each(result.checked_data, function (i, v) {
                if (ctg.indexOf(v.location) === -1) ctg.push(v.location);
            });

            $.each(ctg, function (i, loc) {
                $.each(check_stat, function (j, stat) {
                    var total = 0;
                    $.each(result.checked_data, function (k, d) {
                        if (d.location == loc && d.real_stat == stat) total = parseInt(d.total_asset);
                    });
                    resume_check.push({ location: loc, status: stat, total_asset: total });
                });
            });

            $.each(check_stat, function (j, stat) {
                var tmp = [];
                $.each(resume_check, function (k, d) { if (d.status == stat) tmp.push(d.total_asset); });
                series_check.push({ name: stat, data: tmp, color: colors[stat] });
            });

            Highcharts.chart('chart_check', {
                chart: { type: 'column' },
                xAxis: { categories: ctg },
                yAxis: {
                    min: 0, title: { text: 'Count Fixed Asset' },
                    stackLabels: { enabled: true }
                },
                tooltip: {
                    headerFormat: '<b>{point.x}</b><br/>',
                    pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                },
                plotOptions: {
                    column: {
                        stacking: 'normal', borderRadius: 4, borderWidth: 0,
                        dataLabels: { enabled: true, style: { fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '11px', fontWeight: '600', color: '#fff', textOutline: 'none' } },
                        cursor: 'pointer',
                        point: { events: { click: function () { modalDetail(this.category, this.series.name, 'check'); } } }
                    }
                },
                series: series_check
            });

            /* ── BUILD AUDIT CHART ── */
            var ctg2 = [], total_asset = [], audited = [], finder = [], yet_audited = [];
            $.each(result.audited_data, function (i, v) {
                ctg2.push(v.location);
                total_asset.push(parseInt(v.total_asset));
                audited.push(parseInt(v.audited));
                finder.push(parseInt(v.finding));
                var must = Math.round(parseInt(v.total_asset) / 100 * 10);
                if (must == 0) must = 1;
                var diff = must - (parseInt(v.audited) + parseInt(v.finding));
                yet_audited.push(diff < 0 ? 0 : diff);
            });

            Highcharts.chart('chart_audit', {
                chart: { type: 'column' },
                xAxis: { categories: ctg2 },
                yAxis: {
                    allowDecimals: false, min: 0, title: { text: 'Count Fixed Asset' },
                    stackLabels: { enabled: true }
                },
                tooltip: { format: '<b>{key}</b><br/>{series.name}: {y}<br/>Total: {point.stackTotal}' },
                plotOptions: {
                    column: {
                        stacking: 'normal', borderRadius: 4, borderWidth: 0,
                        dataLabels: { enabled: true, style: { fontFamily: "'Plus Jakarta Sans', sans-serif", fontSize: '11px', fontWeight: '600', color: '#fff', textOutline: 'none' } },
                        cursor: 'pointer',
                        point: { events: { click: function () { modalDetail(this.category, this.series.name, 'Audit'); } } }
                    }
                },
                series: [
                    { name: 'Total Asset', data: total_asset, stack: 'Total Asset', color: '#6d68de' },
                    { name: 'Audited',     data: audited,     stack: 'Audit',       color: '#19fb8b' },
                    { name: 'Finding',     data: finder,      stack: 'Audit',       color: '#ffc107' },
                    { name: 'Must Audit',  data: yet_audited, stack: 'Audit',       color: '#d81b60' }
                ]
            });
        });
    }

    /* ── STATUS BADGE ── */
    function statBadge(s) {
        var map = {
            'Not Checked':      'sb-notchecked',
            'Check 1':          'sb-check1',
            'Check 2':          'sb-check2',
            'Waiting Approval': 'sb-waiting',
            'Fully Approve':    'sb-approved',
            'Audited':          'sb-audited',
            'Finding':          'sb-finding',
            'Not Audited Yet':  'sb-notaudited'
        };
        var cls = map[s] || 'sb-notchecked';
        return "<span class='stat-badge " + cls + "'>" + s + "</span>";
    }

    /* ── CHECKPOINT BLOCK ── */
    function cpBlock(v) {
        return "<div class='cp-block'>"
            + "<div class='cp-row'><span class='cp-key'>Availability</span><span class='cp-val'>" + (v.availability || '—') + "</span></div>"
            + "<div class='cp-row'><span class='cp-key'>Condition</span><span class='cp-val'>" + (v.asset_condition || '—') + "</span></div>"
            + "<div class='cp-row'><span class='cp-key'>Label</span><span class='cp-val'>" + (v.label_condition || '—') + "</span></div>"
            + "<div class='cp-row'><span class='cp-key'>Usable</span><span class='cp-val'>" + (v.usable_condition || '—') + "</span></div>"
            + "<div class='cp-row'><span class='cp-key'>Map</span><span class='cp-val'>" + (v.map_condition || '—') + "</span></div>"
            + "<div class='cp-row'><span class='cp-key'>Note</span><span class='cp-val'>" + (v.note || '—') + "</span></div>"
            + "</div>";
    }

    /* ── MODAL DETAIL ── */
    function modalDetail(loc, status, ctg) {
        $('#modal_details').modal('show');
        Inittable();

        var isCheck = (ctg == 'check');
        $('#title_modal').html(
            '<i class="fas fa-' + (isCheck ? 'clipboard-check' : 'search') + '" style="color:#605ca8;"></i> '
            + (isCheck ? 'Details Check — ' : 'Details Audit — ') + loc + ' <span style="font-size:13px;color:#718096;">(' + status + ')</span>'
        );

        var body = '';
        var num  = 1;
        var imgBase  = '{{ url('data_file/fixed_asset/master_picture') }}/';
        var imgBase2 = isCheck
            ? '{{ url('data_file/fixed_asset/check_picture') }}/'
            : '{{ url('data_file/fixed_asset/audit_picture') }}/';

        var source = isCheck ? check_details : audit_details;

        $.each(source, function (i, v) {
            var match = false;
            if (isCheck) {
                match = (v.location == loc && v.real_stat == status);
            } else {
                if (status == 'Total Asset') {
                    match = (v.location == loc);
                } else {
                    match = (v.location == loc && v.status.toLowerCase() == status.toLowerCase());
                }
            }

            if (!match) return true;

            var statText = isCheck
                ? v.real_stat
                : (status == 'Must Audit' ? 'Not Audited Yet' : v.status);

            var evidenImg = v.result_images
                ? "<img src='" + imgBase2 + v.result_images + "' style='max-width:90px;border-radius:8px;border:2px solid #ede9fe;'>"
                : "<span style='color:#a0aec0;font-size:11px;'>—</span>";

            body += '<tr>';
            body += '<td><span style="display:inline-flex;align-items:center;justify-content:center;width:26px;height:26px;border-radius:8px;background:#ede9fe;color:#7c3aed;font-size:11px;font-weight:700;">' + num + '</span></td>';
            body += '<td><img src="' + imgBase + v.asset_images + '" style="max-width:80px;border-radius:8px;border:2px solid #e2e8f0;" onerror="this.style.display=\'none\'"></td>';
            body += '<td style="font-weight:600;color:#4a4690;">' + (v.sap_number || '—') + '</td>';
            body += '<td style="font-weight:600;text-align:left;">' + (v.asset_name || '—') + '</td>';
            body += '<td>' + (v.location || '—') + '</td>';
            body += '<td>' + cpBlock(v) + '</td>';
            body += '<td>' + evidenImg + '</td>';
            body += '<td>' + statBadge(statText) + '</td>';
            body += '</tr>';
            num++;
        });

        $('#body_details').html(body || '<tr><td colspan="8" style="text-align:center;color:#a0aec0;padding:32px;">Tidak ada data.</td></tr>');

        $('#table_details').DataTable({
            paging: true, lengthChange: true, searching: true,
            ordering: true, info: true, autoWidth: false,
            responsive: true, bDestroy: true,
            language: {
                search: '<i class="fas fa-search"></i>',
                searchPlaceholder: 'Cari data...',
                lengthMenu: 'Tampilkan _MENU_ data',
                info: 'Menampilkan _START_–_END_ dari _TOTAL_ data',
                paginate: { previous: '‹', next: '›' }
            }
        });
    }

    /* ── INIT TABLE ── */
    function Inittable() {
        $('#container_table').html(
            "<div style='overflow-x:auto;'>"
            + "<table id='table_details' class='table dataTable dtr-inline' style='width:100%;border-collapse:collapse;'>"
            + "<thead><tr>"
            + "<th style='width:40px;'>No</th>"
            + "<th>Image</th>"
            + "<th>SAP Number</th>"
            + "<th style='text-align:left;'>Asset Name</th>"
            + "<th>Location</th>"
            + "<th>Check Point</th>"
            + "<th>Eviden</th>"
            + "<th>Status</th>"
            + "</tr></thead>"
            + "<tbody id='body_details'></tbody>"
            + "</table></div>"
        );
    }

    var Toast = Swal.mixin({ toast: true, position: 'top-end', showConfirmButton: false, timer: 2000 });
</script>
@endsection