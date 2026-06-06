@extends('layouts.master')

@section('title', 'Rekam Kehadiran Driver')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
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
        align-items: center; justify-content: center;
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
        border: 3px solid #e2e8f0; border-top-color: #605ca8;
        border-radius: 50%; animation: spin .75s linear infinite;
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

    .header-actions { display: flex; gap: 10px; flex-wrap: wrap; align-items: center; position: relative; z-index: 1; }
    .btn-hdr {
        display: inline-flex; align-items: center; gap: 7px;
        padding: 10px 18px; border-radius: 10px; font-size: 13px; font-weight: 700;
        border: none; cursor: pointer; text-decoration: none; transition: all .18s;
    }
    .btn-hdr:hover { text-decoration: none; transform: translateY(-1px); }
    .btn-hdr-white { background: #fff; color: #4a4690; box-shadow: 0 4px 14px rgba(0,0,0,.15); }
    .btn-hdr-white:hover { color: #605ca8; }
    .btn-hdr-green { background: rgba(255,255,255,.15); color: #fff; border: 1.5px solid rgba(255,255,255,.3); }
    .btn-hdr-green:hover { background: rgba(255,255,255,.25); color: #fff; }

    /* ══════════════════════════════════════
       ATTENDANCE CARDS
    ══════════════════════════════════════ */
    .attend-grid {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(300px, 1fr));
        gap: 16px;
    }

    .attend-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden;
    }

    .attend-card-header {
        display: flex; align-items: center; justify-content: space-between;
        padding: 14px 18px; background: #fafbff;
        border-bottom: 1px solid #f0f2f7;
    }
    .attend-date {
        display: flex; align-items: center; gap: 8px;
    }
    .attend-date .date-icon {
        width: 34px; height: 34px; border-radius: 9px;
        background: #ede9fe; color: #5b21b6;
        display: flex; align-items: center; justify-content: center;
        font-size: 14px; flex-shrink: 0;
    }
    .attend-date .date-text {
        font-size: 13px; font-weight: 700; color: #1a202c;
    }
    .attend-date .date-day {
        font-size: 11px; color: #718096; font-weight: 500;
    }

    .attend-card-body { padding: 14px 18px; }

    .time-row-card {
        display: grid; grid-template-columns: 1fr 1fr; gap: 10px;
    }

    .time-box {
        border-radius: 12px; padding: 12px 14px;
        display: flex; flex-direction: column; gap: 4px;
    }
    .time-box-in  { background: #f0fdf4; border: 1.5px solid #bbf7d0; }
    .time-box-out { background: #fff5f5; border: 1.5px solid #fecaca; }
    .time-box-empty { background: #f7f8fc; border: 1.5px solid #edf0f5; }

    .time-box .tb-lbl {
        font-size: 10px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .7px;
    }
    .time-box-in  .tb-lbl { color: #15803d; }
    .time-box-out .tb-lbl { color: #dc2626; }
    .time-box-empty .tb-lbl { color: #a0aec0; }

    .time-box .tb-time {
        font-size: 24px; font-weight: 800; line-height: 1;
        display: flex; align-items: center; justify-content: space-between;
    }
    .time-box-in  .tb-time { color: #15803d; }
    .time-box-out .tb-time { color: #dc2626; }
    .time-box-empty .tb-time { color: #cbd5e1; }

    /* Action icon buttons */
    .tb-action {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 7px;
        font-size: 13px; cursor: pointer; border: none; transition: all .18s;
        text-decoration: none; flex-shrink: 0;
    }
    .tb-action-map  { background: #ebf2ff; color: #2d6bc4; }
    .tb-action-map:hover  { background: #c3d9f8; color: #2d6bc4; }
    .tb-action-pass { background: #fef3c7; color: #b45309; }
    .tb-action-pass:hover { background: #fde68a; color: #b45309; }

    /* Map embed row */
    .map-row { display: none; margin-top: 12px; border-radius: 12px; overflow: hidden; border: 1.5px solid #e2e8f0; }
    .map-row iframe { width: 100%; height: 200px; display: block; border: none; }

    /* Empty state */
    .empty-state {
        text-align: center; padding: 60px 20px;
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 10px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
    }
    .empty-state i { font-size: 48px; color: #e2e8f0; display: block; margin-bottom: 14px; }
    .empty-state p { font-size: 14px; color: #a0aec0; font-weight: 500; margin: 0; }

    /* Auto-refresh indicator */
    .refresh-badge {
        display: inline-flex; align-items: center; gap: 5px;
        background: rgba(255,255,255,.12); border: 1px solid rgba(255,255,255,.2);
        color: rgba(255,255,255,.7); font-size: 11px; font-weight: 600;
        padding: 4px 10px; border-radius: 20px;
    }
    .refresh-dot {
        width: 6px; height: 6px; border-radius: 50%; background: #4ade80;
        animation: pulse 2s infinite;
    }
    @keyframes pulse { 0%,100%{opacity:1;} 50%{opacity:.4;} }
</style>
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid" style="padding: 0 24px;">

    {{-- LOADING --}}
    <div id="loading">
        <div class="loading-box">
            <div class="loading-spinner"></div>
            <p>Memuat data kehadiran...</p>
        </div>
    </div>

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-user-check"></i>&nbsp; Driver</div>
            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }} &nbsp;&bull;&nbsp; <span class="refresh-badge"><span class="refresh-dot"></span> Auto-refresh 5 menit</span></p>
        </div>
        <div class="header-actions">
            <!-- <a class="btn-hdr btn-hdr-white" href="{{ url('') }}">
                <i class="fas fa-arrow-left"></i> Kembali
            </a> -->
            <a class="btn-hdr btn-hdr-green" href="{{ url('index/driver/attendance') }}">
                <i class="fas fa-plus"></i> Input Kehadiran
            </a>
        </div>
    </div>

    {{-- ATTENDANCE CARDS --}}
    <div id="divDriver" class="attend-grid"></div>

</div>
@endsection

@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

    $(document).ready(function() {
        $('body').toggleClass('sidebar-collapse');
        $('#side_driver').addClass('menu-open');
        fetchDriverAttendance();
        setInterval(fetchDriverAttendance, 300000);
        var loading = document.getElementById('loading');
        if (loading) {
            loading.style.setProperty('display', 'none', 'important');
        }
    });

    function fetchDriverAttendance() {
        $('#loading').show();
        $.get('{{ url("fetch/driver/attendance/report") }}', function(result) {
            $('#loading').hide();
            if (!result.status) { audio_error.play(); openErrorGritter('Error', result.message); return; }

            var html = '';
            var data = result.attendance;

            if (!data || data.length === 0) {
                html = '<div class="empty-state" style="grid-column:1/-1;">' +
                       '<i class="fas fa-calendar-times"></i>' +
                       '<p>Belum ada data kehadiran</p>' +
                       '</div>';
                $('#divDriver').html(html); return;
            }

            for (var i = 0; i < data.length; i++) {
                var att = data[i];
                var hasEnd = att.startss !== att.endss;

                /* ── Date ── */
                var dateObj = new Date(att.date);
                var days = ['Minggu','Senin','Selasa','Rabu','Kamis','Jumat','Sabtu'];
                var dayName = isNaN(dateObj) ? '' : days[dateObj.getDay()];

                html += '<div class="attend-card">';

                /* Card header */
                html += '<div class="attend-card-header">';
                html += '<div class="attend-date">';
                html += '<div class="date-icon"><i class="fas fa-calendar-day"></i></div>';
                html += '<div><div class="date-text">' + att.date + '</div><div class="date-day">' + dayName + '</div></div>';
                html += '</div>';
                html += '</div>';

                /* Card body */
                html += '<div class="attend-card-body">';
                html += '<div class="time-row-card">';

                /* ── JAM MASUK ── */
                var inBoxClass = 'time-box-in';
                html += '<div class="time-box ' + inBoxClass + '">';
                html += '<span class="tb-lbl"><i class="fas fa-sign-in-alt"></i> Jam Masuk</span>';
                html += '<div class="tb-time">';
                html += '<span>' + att.startss + '</span>';

                /* Action: penumpang link or map */
                var nowFull  = '{{ date("Y-m-d H:i:s") }}';
                var today    = '{{ date("Y-m-d") }}';
                if (att.start_asli >= today + ' 04:00:00' && att.start_asli <= today + ' 07:00:00' && nowFull <= today + ' 07:00:00') {
                    html += '<a class="tb-action tb-action-pass" href="{{ url("index/passenger/attendance") }}" title="Absensi Penumpang"><i class="fas fa-users"></i></a>';
                } else {
                    html += '<button class="tb-action tb-action-map" onclick="showLoc(' + i + ',' + data.length + ',\'start\')" title="Lihat Lokasi"><i class="fas fa-map-marker-alt"></i></button>';
                }
                html += '</div></div>';

                /* ── JAM KELUAR ── */
                if (hasEnd) {
                    html += '<div class="time-box time-box-out">';
                    html += '<span class="tb-lbl"><i class="fas fa-sign-out-alt"></i> Jam Keluar</span>';
                    html += '<div class="tb-time">';
                    html += '<span>' + att.endss + '</span>';
                    if (att.end_asli >= today + ' 15:30:00' && att.end_asli <= today + ' 17:30:00' && nowFull >= today + ' 15:30:00') {
                        html += '<a class="tb-action tb-action-pass" href="{{ url("index/passenger/attendance") }}" title="Absensi Penumpang"><i class="fas fa-users"></i></a>';
                    } else {
                        html += '<button class="tb-action tb-action-map" onclick="showLoc(' + i + ',' + data.length + ',\'end\')" title="Lihat Lokasi"><i class="fas fa-map-marker-alt"></i></button>';
                    }
                    html += '</div></div>';
                } else {
                    html += '<div class="time-box time-box-empty">';
                    html += '<span class="tb-lbl"><i class="fas fa-sign-out-alt"></i> Jam Keluar</span>';
                    html += '<div class="tb-time"><span>--:--</span></div>';
                    html += '</div>';
                }

                html += '</div>'; /* end time-row-card */

                /* ── MAP ROWS ── */
                html += '<div class="map-row" id="tr_start_' + i + '">';
                html += '<iframe src="https://maps.google.com/maps?q=' + att.latitude_start + ',' + att.longitude_start + '&t=&z=15&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>';
                html += '</div>';

                if (hasEnd) {
                    html += '<div class="map-row" id="tr_end_' + i + '">';
                    html += '<iframe src="https://maps.google.com/maps?q=' + att.latitude_end + ',' + att.longitude_end + '&t=&z=15&ie=UTF8&iwloc=&output=embed" allowfullscreen></iframe>';
                    html += '</div>';
                }

                html += '</div>'; /* end card-body */
                html += '</div>'; /* end attend-card */
            }

            $('#divDriver').html(html);
        }).fail(function() {
            $('#loading').hide();
            openErrorGritter('Error', 'Gagal memuat data');
        });
    }

    function showLoc(index, length, type) {
        /* Toggle: tutup semua lalu buka yang diklik (atau tutup jika sudah terbuka) */
        var $target = $('#tr_' + type + '_' + index);
        var isVisible = $target.is(':visible');

        for (var i = 0; i < length; i++) {
            $('#tr_start_' + i).hide();
            $('#tr_end_'   + i).hide();
        }

        if (!isVisible) { $target.show(); }
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title:title, text:message, class_name:'growl-success', image:'{{ url("images/image-screen.png") }}', sticky:false, time:'3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title:title, text:message, class_name:'growl-danger', image:'{{ url("images/image-stop.png") }}', sticky:false, time:'3000' });
    }
</script>
@endsection