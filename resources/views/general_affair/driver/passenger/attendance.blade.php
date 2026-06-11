@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
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
    .header-left h1 { color: #fff !important; font-size: 24px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }
    .header-right .btn-back {
        display: inline-flex; align-items: center; justify-content: center; gap: 7px;
        padding: 10px 20px; border-radius: 10px;
        background: rgba(255,255,255,.15); border: 1.5px solid rgba(255,255,255,.25);
        color: #fff; font-size: 13px; font-weight: 600; cursor: pointer;
        transition: background .18s; text-decoration: none;
    }
    .header-right .btn-back:hover { background: rgba(255,255,255,.25); text-decoration: none; color: #fff; }

    /* ── Stat Cards ── */
    .stat-row { display: grid; grid-template-columns: repeat(5, 1fr); gap: 14px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        display: flex; align-items: center; gap: 14px;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .si-purple { background: #ede9fe; color: #7c3aed; }
    .si-green  { background: #dcfce7; color: #15803d; }
    .si-red    { background: #fee2e2; color: #dc2626; }
    .si-blue   { background: #dbeafe; color: #1d4ed8; }
    .si-yellow { background: #fef3c7; color: #92400e; }
    .stat-val  { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; margin-bottom: 2px; }
    .stat-lbl  { font-size: 12px; color: #718096; font-weight: 500; }

    /* ── Info Card (form area) ── */
    .info-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 24px;
    }
    .info-card-header {
        padding: 16px 24px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; gap: 10px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .info-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }
    .info-card-body { padding: 20px 24px; }

    /* ── Form fields ── */
    .ff { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff input {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input:focus {
        border-color: #605ca8 !important; background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }

    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }

    /* Scan input highlight */
    .scan-field input {
        border-color: #605ca8 !important;
        background: #fff !important;
        font-size: 15px !important;
        font-weight: 600 !important;
        text-align: center !important;
    }
    .scan-field input:focus {
        box-shadow: 0 0 0 4px rgba(96,92,168,.15) !important;
    }

    /* ── Table Card ── */
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

    /* ── Attendance Table ── */
    .att-table { width: 100%; border-collapse: collapse; }
    .att-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700; letter-spacing: .7px;
        text-transform: uppercase; padding: 10px 14px;
        border-bottom: 2px solid #edf0f5; text-align: center;
    }
    .att-table tbody td {
        padding: 10px 14px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle; text-align: center;
    }
    .att-table tbody td:nth-child(2) { text-align: left; font-weight: 500; }
    .att-table tbody tr:hover td { background: #f5f8ff; }
    .att-table tbody tr:last-child td { border-bottom: none; }

    .time-pill {
        display: inline-flex; align-items: center; gap: 5px;
        padding: 4px 12px; border-radius: 20px;
        font-size: 12px; font-weight: 700; white-space: nowrap;
    }
    .time-pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .time-hadir  { background: #dcfce7; color: #15803d; }
    .time-hadir::before  { background: #22c55e; }
    .time-belum  { background: #fee2e2; color: #dc2626; }
    .time-belum::before  { background: #ef4444; }

    .no-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 26px; height: 26px; border-radius: 8px;
        background: #ede9fe; color: #7c3aed; font-size: 11px; font-weight: 700;
    }

    /* ── Error ── */
    .error-card {
        background: #fff; border-radius: 16px; padding: 40px 32px;
        text-align: center; box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05); margin-bottom: 24px;
    }
    .error-icon { font-size: 40px; color: #dc2626; margin-bottom: 16px; }
    .error-card h4 { color: #dc2626; font-weight: 700; font-size: 18px; margin-bottom: 8px; }
    .error-card p  { color: #718096; font-size: 14px; margin: 0; }

    .page-wrapper { padding-top: 0 !important; }

    .stat-row {
        grid-template-columns: repeat(5, minmax(0, 1fr));
    }
    @media (max-width: 1200px) {
        .stat-row { grid-template-columns: repeat(3, minmax(0, 1fr)); }
    }
    @media (max-width: 768px) {
        .stat-row { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        .g2 { grid-template-columns: 1fr !important; }
        .info-card-body { padding: 16px 18px; }
        .table-card-header { padding: 16px 18px 14px; }
    }

    /* datepicker fix */
    .datepicker-days > table > thead,
    .datepicker-days > table > thead>tr>th,
    .datepicker-months > table > thead>tr>th,
    .datepicker-years > table > thead>tr>th {
        background-color: white; color: #696969 !important;
    }
</style>
@stop

@section('content')

{{-- Loading Overlay --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="content-wrapper" style="margin:0% !important; padding: 0 24px;">

    {{-- Page Header --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-bus"></i> Passenger Attendance</div>
            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }}</p>
        </div>
        @if($message == '')
        <div class="header-right">
            <a href="{{ url('index/driver') }}" class="btn-back">
                <i class="fas fa-arrow-left"></i> Kembali
            </a>
        </div>
        @endif
    </div>

    {{-- Error State --}}
    @if($message != '')
    <div class="error-card">
        <div class="error-icon"><i class="fas fa-exclamation-circle"></i></div>
        <h4>Terjadi Kesalahan</h4>
        <p><?php echo $message; ?></p>
        <a href="{{ url('index/driver') }}" class="btn-back" style="display:inline-flex;margin-top:20px;background:#605ca8;border:none;border-radius:10px;padding:10px 20px;color:#fff;font-size:13px;font-weight:600;gap:7px;text-decoration:none;justify-content:center;align-items:center;">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>
    @endif

    @if($message == '')

    {{-- Hidden fields --}}
    <input type="hidden" name="driver_id"   id="driver_id"   value="{{ $detail_attendance->employee_id }}">
    <input type="hidden" name="car"         id="car"         value="{{ $detail_attendance->car }}">
    <input type="hidden" name="plat_no"     id="plat_no"     value="{{ $detail_attendance->plat_no }}">
    <input type="hidden" name="driver_time" id="driver_time" value="{{ $detail_attendance->datetime }}">

    {{-- Info Card --}}
    <div class="info-card">
        <div class="info-card-header" style="cursor:pointer;" data-toggle="collapse" data-target="#accordionInfo" aria-expanded="false">
            <span class="dot"></span> Informasi Perjalanan & Statistik
            <i class="fas fa-chevron-down" style="margin-left:auto;transition:transform .3s;"></i>
        </div>
        <div id="accordionInfo" class="collapse">
            <div class="info-card-body">
                <div class="g2">
                    <div class="ff">
                        <label>ID</label>
                        <input type="text" id="id" name="id" readonly value="{{ $id }}" placeholder="Driver ID">
                    </div>
                    <div class="ff">
                        <label>Tanggal</label>
                        <input type="text" id="date" name="date" readonly value="{{ date('Y-m-d') }}">
                    </div>
                    <div class="ff">
                        <label>Driver</label>
                        <input type="text" id="driver_name" name="driver_name" readonly value="{{ $detail_attendance->name }}">
                    </div>
                    <div class="ff">
                        <label>Shuttle</label>
                        <input type="text" id="destination" name="destination" readonly value="{{ $destination }}">
                    </div>
                </div>

                <div class="ff scan-field" style="margin-bottom:20px;">
                    <label><i class="fas fa-id-card" style="margin-right:5px;color:#605ca8;"></i> Scan ID Card Penumpang</label>
                    <input type="text" id="tag" name="tag" placeholder="Arahkan kursor ke sini lalu scan ID Card..." value="">
                </div>

                {{-- Stat Cards --}}
                <div class="stat-row">
                    <div class="stat-card">
                        <div class="stat-icon si-purple"><i class="fas fa-users"></i></div>
                        <div>
                            <div class="stat-val" id="total">0</div>
                            <div class="stat-lbl">Total Penumpang</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon si-green"><i class="fas fa-sign-in-alt"></i></div>
                        <div>
                            <div class="stat-val" id="hadir_masuk">0</div>
                            <div class="stat-lbl">Hadir Masuk</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon si-red"><i class="fas fa-user-times"></i></div>
                        <div>
                            <div class="stat-val" id="belum_hadir_masuk">0</div>
                            <div class="stat-lbl">Belum Hadir Masuk</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon si-blue"><i class="fas fa-sign-out-alt"></i></div>
                        <div>
                            <div class="stat-val" id="hadir_pulang">0</div>
                            <div class="stat-lbl">Hadir Pulang</div>
                        </div>
                    </div>
                    <div class="stat-card">
                        <div class="stat-icon si-yellow"><i class="fas fa-user-clock"></i></div>
                        <div>
                            <div class="stat-val" id="belum_hadir_pulang">0</div>
                            <div class="stat-lbl">Belum Hadir Pulang</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <style>
        .info-card-header i {
            transition: transform .3s ease;
        }
        .info-card-header[aria-expanded="false"] i {
            transform: rotate(-90deg);
        }
    </style>

    {{-- Attendance Table --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Penumpang
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table class="att-table">
                <thead>
                    <tr>
                        <th style="width:44px;">#</th>
                        <th style="text-align:left;">Nama</th>
                        <th>Masuk</th>
                        <th>Pulang</th>
                    </tr>
                </thead>
                <tbody id="bodyAttendance">
                    <tr>
                        <td colspan="4" style="text-align:center;color:#a0aec0;padding:32px 16px;font-size:13px;">
                            <i class="fas fa-spinner fa-spin" style="margin-right:6px;"></i> Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    @endif
</div>
@endsection

@section('scripts')
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script>
    $.ajaxSetup({
        headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') }
    });

    var count_pass = 0;
    var passenger_attend = [];
    var passenger = null;

    $(document).ready(function() {
        $('body').toggleClass("sidebar-collapse");
        $('#side_driver').addClass('menu-open');

        $('#tag').val('');
        $('#tag').focus();
        count_pass = 0;
        $("#bodyAttendance").html('');
        passenger_attend = [];

        if ('{{ $message }}' == '') {
            fetchAttendance();
            passenger = <?php echo json_encode($passenger); ?>;
        }
    });

    var passenger_save = null;

    $('#tag').keydown(function(event) {
        if (event.keyCode == 13 || event.keyCode == 9) {
            $('#loading').addClass('show');
            passenger_save = null;
            if ($("#tag").val().length >= 9) {
                var tag = $("#tag").val();
                if (passenger != null) {
                    var passengers = null;
                    for (var i = 0; i < passenger.length; i++) {
                        if (passenger[i].tag == tag || passenger[i].employee_id.toUpperCase() == tag.toUpperCase()) {
                            passengers = passenger[i];
                            break;
                        }
                    }
                    if (passengers != null && passenger_attend.indexOf(passengers.employee_id) == -1) {
                        passenger_save = passengers;
                        saveAttendance();
                    } else {
                        $('#loading').removeClass('show');
                        audio_error.play();
                        openErrorGritter('Error!', 'ID Card Invalid or Already Scanned');
                        $('#tag').val('');
                        $('#tag').focus();
                        return false;
                    }
                } else {
                    $('#loading').removeClass('show');
                    audio_error.play();
                    openErrorGritter('Error!', 'Passenger Not Found');
                    $('#tag').val('');
                    $('#tag').focus();
                    return false;
                }
            } else {
                $('#loading').removeClass('show');
                audio_error.play();
                openErrorGritter('Error!', 'ID Card Invalid');
                $('#tag').val('');
                $('#tag').focus();
                return false;
            }
        }
    });

    function saveAttendance() {
        $('#loading').addClass('show');
        var data = {
            destination: $('#destination').val(),
            id: $('#id').val(),
            passengers: passenger_save,
            timestamps: getActualFullDate(),
            timing: '{{ $timing }}',
            driver_id: $('#driver_id').val(),
            car: $('#car').val(),
            plat_no: $('#plat_no').val(),
            driver_name: $('#driver_name').val(),
            driver_time: $('#driver_time').val(),
        };
        $.post('{{ url("input/passenger/attendance") }}', data, function(result) {
            if (result.status) {
                $('#loading').removeClass('show');
                openSuccessGritter('Success!', 'Attendance Saved');
                count_pass++;
                fetchAttendance();
                $('#tag').val('');
                $('#tag').focus();
            } else {
                $('#loading').removeClass('show');
                audio_error.play();
                openErrorGritter('Error!', 'Failed to save attendance');
            }
        });
    }

    function fetchAttendance() {
        var data = {
            destination: $('#destination').val(),
            id: $('#id').val(),
        };
        $.get('{{ url("fetch/passenger/attendance") }}', data, function(result) {
            if (result.status) {
                $('#loading').removeClass('show');
                $("#bodyAttendance").html('');
                passenger_attend = [];
                count_pass = 0;
                var table = "";
                var total = 0, hadir_masuk = 0, belum_hadir_masuk = 0;
                var hadir_pulang = 0, belum_hadir_pulang = 0;
                var passenger_all = result.passenger_all;
                var new_passenger_all = [];

                for (var i = 0; i < passenger_all.length; i++) {
                    var times_masuk = '-';
                    var times_pulang = '-';
                    for (var j = 0; j < result.time_in.length; j++) {
                        if (passenger_all[i].employee_id == result.time_in[j].employee_id) {
                            times_masuk = result.time_in[j].time_in;
                            hadir_masuk++;
                            if ('{{ $timing }}' == 'datang') {
                                passenger_attend.push(passenger_all[i].employee_id);
                            }
                            break;
                        }
                    }
                    for (var k = 0; k < result.time_out.length; k++) {
                        if (passenger_all[i].employee_id == result.time_out[k].employee_id) {
                            times_pulang = result.time_out[k].time_out;
                            break;
                        }
                    }
                    if (times_masuk == times_pulang) {
                        times_pulang = '-';
                    } else {
                        if ('{{ $timing }}' == 'pulang') {
                            hadir_pulang++;
                            passenger_attend.push(passenger_all[i].employee_id);
                        }
                    }
                    new_passenger_all.push({
                        employee_id: passenger_all[i].employee_id,
                        name: passenger_all[i].name,
                        masuk: times_masuk,
                        pulang: times_pulang,
                    });
                    total++;
                }

                new_passenger_all.sort(function(a, b) {
                    var aHas = (a.masuk !== '-' || a.pulang !== '-');
                    var bHas = (b.masuk !== '-' || b.pulang !== '-');
                    if (aHas !== bHas) return aHas ? -1 : 1;
                    var aTime = a.masuk !== '-' ? a.masuk : a.pulang;
                    var bTime = b.masuk !== '-' ? b.masuk : b.pulang;
                    if (aTime === bTime) return 0;
                    return aTime < bTime ? 1 : -1;
                });

                for (var i = 0; i < new_passenger_all.length; i++) {
                    var masukPill = new_passenger_all[i].masuk !== '-'
                        ? "<span class='time-pill time-hadir'>" + new_passenger_all[i].masuk + "</span>"
                        : "<span class='time-pill time-belum'>—</span>";
                    var pulangPill = new_passenger_all[i].pulang !== '-'
                        ? "<span class='time-pill time-hadir'>" + new_passenger_all[i].pulang + "</span>"
                        : "<span class='time-pill time-belum'>—</span>";

                    table += "<tr>";
                    table += "<td><span class='no-badge'>" + (i + 1) + "</span></td>";
                    table += "<td>" + new_passenger_all[i].name + "<br><span style='font-size:12px;color:#a0aec0;'>" + new_passenger_all[i].employee_id + "</span></td>";
                    table += "<td>" + masukPill + "</td>";
                    table += "<td>" + pulangPill + "</td>";
                    table += "</tr>";
                }

                if (table === '') {
                    table = "<tr><td colspan='4' style='text-align:center;color:#a0aec0;padding:32px;font-size:13px;'>Belum ada data penumpang.</td></tr>";
                }

                belum_hadir_masuk  = total - hadir_masuk;
                belum_hadir_pulang = total - hadir_pulang;

                $('#total').text(total);
                $('#hadir_masuk').text(hadir_masuk);
                $('#belum_hadir_masuk').text(belum_hadir_masuk);
                $('#hadir_pulang').text(hadir_pulang);
                $('#belum_hadir_pulang').text(belum_hadir_pulang);
                $("#bodyAttendance").html(table);
            } else {
                $('#loading').removeClass('show');
                audio_error.play();
                openErrorGritter('Error!', 'Failed to fetch attendance');
            }
        });
    }

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

    function addZero(i) { return i < 10 ? "0" + i : i; }

    function getActualFullDate() {
        var d = new Date();
        return addZero(d.getFullYear()) + "-" + addZero(d.getMonth()+1) + "-" + addZero(d.getDate())
             + " " + addZero(d.getHours()) + ":" + addZero(d.getMinutes()) + ":" + addZero(d.getSeconds());
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({
            title: title, text: message, class_name: 'growl-success',
            image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000'
        });
    }

    function openErrorGritter(title, message) {
        jQuery.gritter.add({
            title: title, text: message, class_name: 'growl-danger',
            image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000'
        });
    }
</script>
@endsection