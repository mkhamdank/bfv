@extends('layouts.master')

@section('title', 'Tol & Parkir')

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

    /* ── Page header ── */
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
    .header-left h1 { color: #fff !important; font-size: 22px !important; font-weight: 700 !important; margin: 0 0 3px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }
    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: rgba(255,255,255,.15); color: #fff;
        border: 1.5px solid rgba(255,255,255,.25); border-radius: 10px;
        padding: 10px 20px; font-size: 13px; font-weight: 600;
        text-decoration: none; transition: all .2s; z-index: 999;
    }
    .btn-back:hover { background: rgba(255,255,255,.25); color: #fff; text-decoration: none; }

    /* ── Table card ── */
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
    .table-count { font-size: 12px; color: #a0aec0; font-weight: 500; }

    /* ── Table ── */
    #tp-table { width: 100%; border-collapse: collapse; }
    #tp-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700; letter-spacing: .7px; text-transform: uppercase;
        padding: 12px 16px; border-bottom: 2px solid #edf0f5; white-space: nowrap; text-align: left;
    }
    #tp-table tbody tr { transition: background .15s; }
    #tp-table tbody tr:hover td { background: #f5f8ff !important; }
    #tp-table tbody td {
        padding: 14px 16px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }

    /* ── Mobile Responsive ── */
    @media (max-width: 768px) {
        .page-header-modern {
            flex-direction: column; align-items: flex-start;
            padding: 20px 16px;
        }
        
        .btn-back { align-self: flex-start; padding: 8px 16px; font-size: 12px; }
        .header-left h1 { font-size: 20px !important; }
        
        #tp-table { width: 100%; }
        #tp-table thead { display: none; }
        
        #tp-table tbody tr {
            display: block;
            padding: 16px;
            margin-bottom: 12px;
            background: #fff;
            border: 1px solid #e2e8f0;
            border-radius: 12px;
            box-shadow: 0 1px 4px rgba(0,0,0,.04);
        }
        
        #tp-table tbody tr:hover td { background: transparent !important; }
        
        #tp-table tbody td {
            display: block;
            padding: 8px 0 12px 0;
            border: none !important;
            border-bottom: 1px solid #f0f2f7;
            font-size: 13px;
            position: relative;
        }
        
        #tp-table tbody td:last-child { border-bottom: none; padding: 12px 0 0 0; }
        
        #tp-table tbody td::before {
            content: attr(data-label);
            display: block;
            font-size: 10px;
            font-weight: 700;
            color: #a0aec0;
            text-transform: uppercase;
            letter-spacing: .5px;
            margin-bottom: 4px;
        }
        
        #tp-table tbody td:first-child { padding: 0; }
        #tp-table tbody td:first-child::before { display: none; }
        
        .date-chip {
            display: inline-flex;
            align-items: center;
            gap: 6px;
            background: #f0f5ff;
            color: #2d6bc4;
            padding: 6px 12px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: 600;
        }
        
        .time-range {
            display: flex;
            align-items: center;
            gap: 6px;
            font-size: 13px;
            color: #2d3748;
            font-weight: 500;
        }
        
        .user-cell {
            display: flex;
            align-items: center;
            gap: 10px;
        }
        
        .user-avatar {
            width: 32px;
            height: 32px;
            flex-shrink: 0;
        }
        
        .car-plat {
            font-size: 13px;
            font-weight: 700;
            letter-spacing: 1px;
        }
        
        .car-name {
            font-size: 11px;
            color: #a0aec0;
            margin-top: 3px;
        }
        
        .btn-isi {
            display: flex;
            align-items: center;
            justify-content: center;
            width: 100%;
            padding: 10px 12px;
            font-size: 12px;
            gap: 6px;
        }
    }

    @media (max-width: 480px) {
        .page-header-modern {
            padding: 16px 12px;
        }
        
        .header-left h1 { font-size: 18px !important; }
        .header-left p { font-size: 12px; }
        
        #tp-table tbody tr {
            padding: 12px;
            margin-bottom: 10px;
        }
        
        #tp-table tbody td {
            padding: 6px 0 10px 0;
            font-size: 12px;
        }
        
        #tp-table tbody td::before {
            font-size: 9px;
            margin-bottom: 3px;
        }
        
        .table-card-header {
            padding: 12px 16px;
            flex-direction: column;
            align-items: flex-start;
            gap: 8px;
        }
        
        .table-card-title { font-size: 13px; }
        .table-count { font-size: 11px; }
        
        .date-chip { padding: 5px 10px; font-size: 11px; }
        .time-range { font-size: 12px; }
        .user-avatar { width: 28px; height: 28px; font-size: 10px; }
        .car-plat { font-size: 12px; }
        .btn-isi { padding: 9px 10px; font-size: 11px; }
    }

    /* Date chip */
    .date-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: #f0f5ff; color: #2d6bc4;
        padding: 4px 10px; border-radius: 6px; font-size: 12px; font-weight: 600;
    }

    /* Time */
    .time-range {
        display: inline-flex; align-items: center; gap: 6px;
        font-size: 12.5px; color: #4a5568; font-weight: 500;
    }
    .time-sep { color: #c4bfef; }

    /* User cell */
    .user-cell { display: flex; align-items: center; gap: 9px; }
    .user-avatar {
        width: 30px; height: 30px; border-radius: 8px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .user-name { font-weight: 600; color: #1a202c; }

    /* Car cell */
    .car-plat { font-weight: 700; color: #1a202c; font-family: monospace; font-size: 13px; }
    .car-name { font-size: 11.5px; color: #a0aec0; margin-top: 2px; }

    /* Action button */
    .btn-isi {
        display: inline-flex; align-items: center; gap: 6px;
        padding: 7px 16px; border: none; border-radius: 8px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff; font-size: 12.5px; font-weight: 700;
        text-decoration: none; transition: all .18s;
        box-shadow: 0 3px 10px rgba(96,92,168,.3);
    }
    .btn-isi:hover { opacity: .88; transform: translateY(-1px); color: #fff; text-decoration: none; }

    /* State row */
    .state-row td { text-align: center; padding: 48px 24px; color: #a0aec0; }
    .state-row td i { font-size: 32px; display: block; margin-bottom: 10px; }
</style>
@endsection

@section('content')

<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-car"></i> Driver</div>
            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }}</p>
        </div>
        <a href="{{ url('index/driver') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- TABLE CARD --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Penugasan Tol & Parkir
            </div>
            <span class="table-count" id="row-count">Memuat...</span>
        </div>

        <div style="overflow-x:auto;">
            <table id="tp-table" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:46px;">#</th>
                        <th>Tanggal</th>
                        <th>Jam</th>
                        <th>User</th>
                        <th>Kendaraan</th>
                        <th style="width: 150px;">Tujuan</th>
                        <th style="width: 130px;">Aksi</th>
                    </tr>
                </thead>
                <tbody id="toll_parking_table">
                    <tr class="state-row">
                        <td colspan="7">
                            <i class="fas fa-spinner fa-spin"></i>
                            Memuat data...
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection

@section('scripts')
<script src="{{ url('js/jquery.numpad.js') }}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    var months = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];

    $(document).ready(function () {
        $('body').toggleClass("sidebar-collapse");
        $('#side_driver').addClass('menu-open');
        fetchData();
    });

    function fetchData() {
        $('#loading').addClass('show');

        $.get('{{ url("fetch/driver/toll_parking") }}', { driver_id: '{{ $driver_id }}' }, function (result) {
            $('#loading').removeClass('show');

            if (result.status) {
                var data = result.data;
                $('#row-count').text(data.length + ' data');

                if (data.length === 0) {
                    $('#toll_parking_table').html(
                        '<tr class="state-row"><td colspan="7"><i class="fas fa-inbox"></i>Tidak ada data penugasan.</td></tr>'
                    );
                    return;
                }

                var html = '';
                data.forEach(function (item, i) {
                    var parts    = item.date_from.split(' ')[0].split('-');
                    var fmtDate  = parts[2] + ' ' + months[parseInt(parts[1], 10) - 1] + ' ' + parts[0].substr(2, 2);
                    var timeFrom = item.date_from.substr(11, 5);
                    var timeTo   = item.date_to.substr(11, 5);

                    var actionUrl = item.remark.match(/daily/gi)
                        ? '{{ url("index/additional/driver/daily_job") }}/' + item.id
                        : '{{ url("index/additional/driver/job") }}/' + btoa(item.task_id);

                    var initials = (item.created_by_name || '?')
                        .split(' ').map(function(w){ return w[0]; }).slice(0,2).join('').toUpperCase();

                    html += '<tr>';
                    html += '<td style="text-align:center;color:#a0aec0;font-size:12px;font-weight:600;" data-label="#">' + (i+1) + '</td>';
                    html += '<td data-label="Tanggal"><span class="date-chip"><i class="fas fa-calendar-alt"></i> ' + fmtDate + '</span></td>';
                    html += '<td data-label="Jam"><div class="time-range"><i class="fas fa-clock" style="color:#c4bfef;font-size:12px;"></i> ' + timeFrom + '<span class="time-sep">→</span>' + timeTo + '</div></td>';
                    html += '<td data-label="User"><div class="user-cell"><div class="user-avatar">' + initials + '</div><span class="user-name">' + item.created_by_name + '</span></div></td>';
                    html += '<td data-label="Kendaraan"><div class="car-plat">' + item.plat_no + '</div><div class="car-name">' + item.car + '</div></td>';
                    html += '<td data-label="Tujuan">' + (item.destination || '') + '</td>';
                    html += '<td data-label="Aksi"><a class="btn-isi" href="' + actionUrl + '"><i class="fas fa-pen"></i> Isi Data</a></td>';
                    html += '</tr>';
                });

                $('#toll_parking_table').html(html);
            } else {
                $('#row-count').text('0 data');
                $('#toll_parking_table').html(
                    '<tr class="state-row"><td colspan="7"><i class="fas fa-exclamation-circle" style="color:#fca5a5;"></i>' + (result.message || 'Gagal memuat data.') + '</td></tr>'
                );
                openErrorGritter('Error!', result.message);
            }
        });
    }

    function addZero(i) { return i < 10 ? '0' + i : i; }

    function getActualFullDate() {
        var d = new Date();
        return d.getFullYear() + '-' + addZero(d.getMonth()+1) + '-' + addZero(d.getDate()) + ' ' + addZero(d.getHours()) + ':' + addZero(d.getMinutes()) + ':' + addZero(d.getSeconds());
    }

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }

    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection