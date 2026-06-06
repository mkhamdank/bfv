@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
<link href="{{ url("css/jquery.numpad.css") }}" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background: #f0f2f7 !important; }

    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
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
    .btn-header-back {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
        color: #fff; font-size: 13px; font-weight: 600;
        cursor: pointer; text-decoration: none; transition: background .18s;
    }
    .btn-header-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

    /* ── Stat Cards ── */
    .stat-row { display: grid; grid-template-columns: repeat(3, 1fr); gap: 14px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        display: flex; align-items: center; gap: 14px;
        transition: box-shadow .2s, transform .2s;
    }
    @media (max-width: 768px) {
        .stat-row { grid-template-columns: 1fr; }
        .stat-card { padding: 16px; }
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .si-purple { background: #ede9fe; color: #7c3aed; }
    .si-yellow { background: #fef3c7; color: #92400e; }
    .si-green  { background: #dcfce7; color: #15803d; }
    .stat-val  { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; margin-bottom: 2px; }
    .stat-lbl  { font-size: 12px; color: #718096; font-weight: 500; }

    /* ── Table Card wrapper ── */
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

    /* ── Jobs Grid ── */
    .jobs-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
        padding: 20px 20px 24px;
        justify-items: center;
    }

    /* ── Job Card ── */
    .job-card {
        background: #fff; border-radius: 14px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; display: flex; flex-direction: column;
        transition: box-shadow .2s, transform .2s;
        width: 100%;
        max-width: 100%;
    }
    @media (max-width: 768px) {
        .jobs-grid { grid-template-columns: 1fr; padding: 16px 16px 20px; }
        .job-card { width: 100%; }
    }
    .job-card.is-new  { border-top: 3px solid #f59e0b; cursor: pointer; }
    .job-card.is-done { border-top: 3px solid #22c55e; }
    .job-card.is-new:hover  { box-shadow: 0 6px 20px rgba(0,0,0,.1); transform: translateY(-2px); }

    /* card header strip */
    .jc-header {
        padding: 11px 16px; background: #fafbff;
        border-bottom: 1px solid #f0f2f7;
        display: flex; align-items: center; justify-content: space-between;
    }

    /* status pill – mirrors index_list pill style */
    .pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 11px; border-radius: 20px;
        font-size: 11.5px; font-weight: 700; white-space: nowrap;
    }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-new  { background: #fef3c7; color: #92400e; }
    .pill-new::before  { background: #f59e0b; }
    .pill-done { background: #dcfce7; color: #15803d; }
    .pill-done::before { background: #22c55e; }

    /* remark badge */
    .remark-badge {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 3px 10px; border-radius: 8px;
        font-size: 11px; font-weight: 700;
    }
    .rb-jp  { background: #ede9fe; color: #7c3aed; }
    .rb-reg { background: #dbeafe; color: #1d4ed8; }

    /* card body */
    .jc-body { padding: 14px 16px; flex: 1; display: flex; flex-direction: column; gap: 8px; }

    /* driver row */
    .driver-row { display: flex; align-items: center; gap: 10px; margin-bottom: 4px; }
    .driver-avatar {
        width: 34px; height: 34px; border-radius: 10px; flex-shrink: 0;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex; align-items: center; justify-content: center;
        font-size: 12px; font-weight: 800; color: #fff;
    }
    .driver-name { font-size: 13.5px; font-weight: 700; color: #1a202c; }
    .driver-sub  { font-size: 11px; color: #a0aec0; margin-top: 1px; }

    .jc-divider { height: 1px; background: #f0f2f7; margin: 2px 0; }

    /* info row */
    .info-row {
        display: flex; align-items: flex-start; gap: 8px;
        font-size: 12.5px;
    }
    .info-row i { font-size: 11px; margin-top: 2px; width: 14px; flex-shrink: 0; }
    .info-row .lbl { color: #a0aec0; font-size: 11px; margin-right: 3px; }
    .info-row .val { color: #2d3748; font-weight: 600; }

    /* card footer */
    .jc-footer {
        padding: 10px 16px; background: #fafbff;
        border-top: 1px solid #f0f2f7;
        display: flex; align-items: center; justify-content: space-between; gap: 10px;
    }
    .car-chip {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 5px 11px; border-radius: 8px;
        background: #f0f2f7; border: 1px solid #e2e8f0;
        font-size: 12px; color: #4a5568; font-weight: 600;
    }
    .car-chip i { color: #a0aec0; font-size: 11px; }

    .btn-action {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 14px; border-radius: 8px; border: none;
        font-size: 12px; font-weight: 700; cursor: pointer;
        transition: opacity .18s, transform .15s;
        text-decoration: none;
    }
    .btn-action:hover { opacity: .85; transform: scale(1.03); text-decoration: none; }
    .btn-start   { background: linear-gradient(135deg, #4a4690, #605ca8); color: #fff; box-shadow: 0 3px 10px rgba(96,92,168,.3); }
    .btn-confirm { background: linear-gradient(135deg, #15803d, #16a34a); color: #fff; box-shadow: 0 3px 10px rgba(21,128,61,.3); }

    /* empty state */
    .empty-state {
        grid-column: 1 / -1; text-align: center;
        padding: 60px 20px; color: #a0aec0;
    }
    .empty-state i { font-size: 38px; margin-bottom: 14px; display: block; color: #c4bfef; }
    .empty-state p { font-size: 13px; }

    .page-wrapper { padding-top: 0 !important; }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button { -webkit-appearance: none; margin: 0; }
    input[type=number] { -moz-appearance: textfield; }

    .datepicker-days > table > thead,
    .datepicker-days > table > thead > tr > th,
    .datepicker-months > table > thead > tr > th,
    .datepicker-years > table > thead > tr > th,
    .datepicker-decades > table > thead > tr > th,
    .datepicker-centuries > table > thead > tr > th {
        background-color: white; color: #696969 !important;
    }

    @keyframes pulse { 0%,100%{opacity:.5} 50%{opacity:1} }
</style>
@stop

@section('content')

{{-- Loading Overlay --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data tugas...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-car"></i> Driver Dispatch</div>
            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }}</p>
        </div>
        <div>
            <a class="btn-header-back" href="{{ url('index/driver') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
    </div>

    {{-- Stat Cards --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-clipboard-list"></i></div>
            <div>
                <div class="stat-val" id="sc-total">—</div>
                <div class="stat-lbl">Total Tugas</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-yellow"><i class="fas fa-bolt"></i></div>
            <div>
                <div class="stat-val" id="sc-new">—</div>
                <div class="stat-lbl">Tugas Baru</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div>
                <div class="stat-val" id="sc-done">—</div>
                <div class="stat-lbl">Sudah Dikerjakan</div>
            </div>
        </div>
    </div>

    {{-- Job Cards --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Tugas Driver
            </div>
        </div>
        <div class="jobs-grid" id="divDriver">
            {{-- skeleton --}}
            <div style="background:#f0f2f7;border-radius:14px;height:210px;animation:pulse 1.4s ease-in-out infinite;"></div>
            <div style="background:#f0f2f7;border-radius:14px;height:210px;animation:pulse 1.4s ease-in-out infinite;animation-delay:.2s;"></div>
            <div style="background:#f0f2f7;border-radius:14px;height:210px;animation:pulse 1.4s ease-in-out infinite;animation-delay:.4s;"></div>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ url("js/jquery.numpad.js") }}"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="{{ url('js/jsQR.js') }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    /* ── Numpad ── */
    var isSmall = screen.width < 400;
    $.fn.numpad.defaults.gridTpl           = '<table class="table modal-content" style="width:' + (isSmall ? '80%' : '25%') + ';background-color:white;"></table>';
    $.fn.numpad.defaults.backgroundTpl     = '<div class="modal-backdrop in" style="opacity:.4"></div>';
    $.fn.numpad.defaults.displayTpl        = '<input type="text" class="form-control" style="font-size:' + (isSmall ? '6vw' : '2vw') + ';height:50px;"/>';
    $.fn.numpad.defaults.buttonNumberTpl   = '<button type="button" class="btn btn-info" style="font-size:' + (isSmall ? '5vw' : '2vw') + ';width:50px;"></button>';
    $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn btn-success" style="font-size:' + (isSmall ? '5vw' : '2vw') + ';width:100%;color:white;background-color:green;border-color:green"></button>';
    $.fn.numpad.defaults.onKeypadCreate = function () {
        $(this).find('.done').css({ 'background-color':'white','color':'rgb(72,156,78)','border-color':'rgb(72,156,78)' });
        if (isSmall) $(this).find('.done').css('width','100px');
        $(this).find('.sep').css({ 'background-color':'white','color':'black','border-color':'#0dcaf0','width':'50px' });
        $(this).find('.cancel').css({ 'background-color':'white','color':'rgb(219,103,115)','border-color':'rgb(219,103,115)' }).html('<i class="fas fa-times"></i>');
        if (isSmall) $(this).find('.cancel').css('width','50px');
        $(this).find('.clear').css({ 'background-color':'white','color':'black','border-color':'black' }).html('<i class="fas fa-trash"></i>');
        if (isSmall) $(this).find('.clear').css('width','50px');
        $(this).find('.del').css({ 'background-color':'white','color':'black','border-color':'black' }).html('<i class="fas fa-backspace"></i>');
        if (isSmall) $(this).find('.del').css({ 'width':'50px','font-size':'4vw' });
    };

    $(document).ready(function () {
        $('body').toggleClass('sidebar-collapse');
        $('#side_driver').addClass('menu-open');
        fetchDriverJob();
        $('.numpad').numpad({ hidePlusMinusButton: true, decimalSeparator: '.' });
    });

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

    function getInitials(name) {
        if (!name) return '?';
        var p = name.trim().split(' ');
        return p.length >= 2 ? (p[0][0] + p[1][0]).toUpperCase() : p[0].slice(0,2).toUpperCase();
    }

    function fetchDriverJob() {
        $.get('{{ url("fetch/driver/job") }}', function (result) {
            if (result.status) {
                var jobs = result.driver_job || [];
                var total = jobs.length, countNew = 0, countDone = 0, html = '';

                if (total === 0) {
                    html = '<div class="empty-state"><i class="fas fa-car"></i><p>Tidak ada tugas untuk hari ini.</p></div>';
                } else {
                    for (var i = 0; i < jobs.length; i++) {
                        var job    = jobs[i];
                        var isDone = job.times != null;
                        var isJP   = job.remark == 'japanese';
                        if (isDone) { countDone++; } else { countNew++; }

                        var base64id = '';
                        if (isJP && result.base64id) {
                            for (var j = 0; j < result.base64id.length; j++) {
                                if (result.base64id[j].id == job.id) { base64id = result.base64id[j].base64id; break; }
                            }
                        }

                        var clickFn = '';
                        if (!isDone) {
                            clickFn = isJP
                                ? "onclick=\"closureJob('" + job.id + "','" + base64id + "')\""
                                : "onclick=\"startJob('" + job.id + "')\"";
                        }

                        var pillHtml = isDone
                            ? '<span class="pill pill-done"><i class="fas fa-check"></i> Selesai</span>'
                            : '<span class="pill pill-new"><i class="fas fa-bolt"></i> Tugas Baru</span>';

                        var remarkHtml = isJP
                            ? '<span class="remark-badge rb-jp"><i class="fas fa-star"></i> Japanese</span>'
                            : '<span class="remark-badge rb-reg"><i class="fas fa-car"></i> Regular</span>';

                        var btnHtml = '';
                        if (!isDone) {
                            if (isJP) {
                                btnHtml = '<button class="btn-action btn-confirm" onclick="closureJob(\'' + job.id + '\',\'' + base64id + '\');event.stopPropagation();"><i class="fas fa-arrow-right"></i> Konfirmasi</button>';
                            } else {
                                btnHtml = '<button class="btn-action btn-start" onclick="startJob(\'' + job.id + '\');event.stopPropagation();"><i class="fas fa-play"></i> Mulai</button>';
                            }
                        }

                        html += '<div class="job-card ' + (isDone ? 'is-done' : 'is-new') + '" ' + clickFn + '>';

                        /* header */
                        html += '<div class="jc-header">' + pillHtml + remarkHtml + '</div>';

                        /* body */
                        html += '<div class="jc-body">';
                        html += '<div class="driver-row">'
                              + '<div class="driver-avatar">' + getInitials(job.driver_name) + '</div>'
                              + '<div><div class="driver-name">' + (job.driver_name || '—') + '</div>'
                              + '<div class="driver-sub">Driver</div></div></div>';
                        html += '<div class="jc-divider"></div>';
                        html += '<div class="info-row"><i class="fas fa-map-marker-alt" style="color:#ef4444;"></i><span><span class="lbl">From</span><span class="val">' + (job.froms || '—') + '</span></span></div>';
                        html += '<div class="info-row"><i class="fas fa-flag-checkered" style="color:#22c55e;"></i><span><span class="lbl">To</span><span class="val">' + (job.tos || '—') + '</span></span></div>';
                        if (job.destination) {
                            html += '<div class="info-row"><i class="fas fa-map-pin" style="color:#605ca8;"></i><span><span class="lbl">Destination</span><span class="val">' + job.destination + '</span></span></div>';
                        }
                        if (isJP) {
                            if (job.requested_name) html += '<div class="info-row"><i class="fas fa-user" style="color:#7c3aed;"></i><span><span class="lbl">By</span><span class="val">' + job.requested_name + '</span></span></div>';
                            if (job.purpose)        html += '<div class="info-row"><i class="fas fa-clipboard" style="color:#1d4ed8;"></i><span><span class="lbl">Purpose</span><span class="val">' + job.purpose + '</span></span></div>';
                            if (job.pick_up)        html += '<div class="info-row"><i class="fas fa-clock" style="color:#92400e;"></i><span><span class="lbl">Pick Up</span><span class="val">' + job.pick_up + '</span></span></div>';
                        }
                        html += '</div>'; /* end jc-body */

                        /* footer */
                        html += '<div class="jc-footer">'
                              + '<div class="car-chip"><i class="fas fa-car-side"></i>' + (job.plat_no || '') + ' &mdash; ' + (job.car || '') + '</div>'
                              + btnHtml + '</div>';

                        html += '</div>'; /* end job-card */
                    }
                }

                $('#divDriver').html(html);
                $('#sc-total').text(total);
                $('#sc-new').text(countNew);
                $('#sc-done').text(countDone);

            } else {
                audio_error.play();
                openErrorGritter('Error', result.message);
                $('#divDriver').html('<div class="empty-state"><i class="fas fa-exclamation-triangle"></i><p>Gagal memuat data tugas.</p></div>');
            }
        });
    }

    function startJob(id) {
        location.replace('{{ url("index/input/driver/job") }}/' + id);
    }

    function closureJob(id, base64id) {
        $('#loading').addClass('show');
        location.replace('{{ url("index/confirmation/driver/job") }}/' + base64id);
    }

    function getActualFullDate() {
        var d = new Date(), z = function(i){ return i < 10 ? '0'+i : i; };
        return z(d.getFullYear())+'-'+z(d.getMonth()+1)+'-'+z(d.getDate())+' '+z(d.getHours())+':'+z(d.getMinutes())+':'+z(d.getSeconds());
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection