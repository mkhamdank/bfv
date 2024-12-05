@extends('layouts.master_full')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
    <style>
        #vfi-container {
            color: #333333;
        }

        .menu-btn {
            width: 100%;
            margin: 1% 0;
        }

        .menu-name {

        }

        .auth-name {
            font-weight: 600;
            color: #BA241C;
        }

        thead>tr>th{
            text-align:center;
            overflow:hidden;
        }
        tbody>tr>td{
            text-align:center;
        }
        tfoot>tr>th{
            text-align:center;
        }
        th:hover {
            overflow: visible;
        }
        td:hover {
            overflow: visible;
        }
        table.table-bordered{
            border:1px solid black;
        }
        table.table-bordered > thead > tr > th{
            border:1px solid black;
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
        }
        table.table-bordered > tbody > tr > td{
            border:1px solid black;
            padding: 0px;
            vertical-align: middle;
        }
        table.table-bordered > tfoot > tr > th{
            border:1px solid black;
            padding:0;
            vertical-align: middle;
            background-color: rgb(126,86,134);
            color: #FFD700;
        }
        thead {
            background-color: rgb(126,86,134);
        }
        td{
            overflow:hidden;
            text-overflow: ellipsis;
        }
        #ngTemp {
            height:200px;
            overflow-y: scroll;
        }

        #ngList2 {
            height:454px;
            overflow-y: scroll;
            /*padding-top: 5px;*/
        }
        #loading, #error { display: none; }
        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            /* display: none; <- Crashes Chrome on hover */
            -webkit-appearance: none;
            margin: 0; /* <-- Apparently some margin are still there even though it's hidden */
        }

        input[type=number] {
            -moz-appearance:textfield; /* Firefox */
        }
        .page-wrapper{
            padding-top: 0px;
        }
        .datepicker-days > table > thead,
        .datepicker-days > table > thead >tr>th,
        .datepicker-months > table > thead>tr>th,
        .datepicker-years > table > thead>tr>th,
        .datepicker-decades > table > thead>tr>th,
        .datepicker-centuries > table > thead>tr>th{
            background-color: white;
            color: #696969 !important;
        }

        #map { height: 180px; }

    </style>
@stop

@section('content')
    <section id="vfi-container">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: white; top: 45%; left: 35%;">
                <span style="font-size: 40px">Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <h1>
                    {{ $title }}
                </h1>
            </div>
        </div>
        <div class="row" style="padding-top: 20px; padding-left: 20px; padding-right: 20px; margin-bottom: 10px;">
            <a style="margin-bottom: 10px;" class="btn btn-danger" href="{{url('')}}"><i class="fa fa-arrow-left"></i>Kembali</a>
            <a class="btn btn-success" href="{{url('index/driver/attendance')}}">Input Kehadiran</a>
        </div>
        <div class="row" id="divDriver" style="padding-bottom: 20px; padding-left: 20px; padding-right: 20px; overflow-y: scroll;">
            
        </div>

    </section>

@endsection

@section('scripts')
<script src="<?php echo e(url("js/jquery.numpad.js")); ?>"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="https://unpkg.com/leaflet@1.9.3/dist/leaflet.js"
integrity="sha256-WBkoXOwTeyKclOHuWtc+i2uENFpDZ9YPdf5Hf+D7ewM="
crossorigin=""></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        
        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        $(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");
            $('#side_driver_attendance').addClass('menu-open');
            fetchDriverAttendance();
            setInterval(fetchDriverAttendance,300000);
        });

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

        function fetchDriverAttendance() {
            $.get('{{ url("fetch/driver/attendance/report") }}',  function(result, status, xhr){
                if(result.status){
                    $('#divDriver').html('');
                    var attendance = '';

                    for(var i = 0; i < result.attendance.length;i++){
                        attendance += '<div class="col-lg-12 col-md-12 col-sm-12" style="padding-left: 0px;padding-right: 0px;margin-bottom: 10px;">';
                            attendance += '<table style="border: 1px solid black;width: 100%">';
                                attendance += '<tr>';
                                    attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight:bold;" colspan="2"><span class="pull-left">'+result.attendance[i].date+'</span></td>';
                                attendance += '</tr>';
                                attendance += '<tr>';
                                    attendance += '<td style="border: 1px solid black;padding-left:4px;">Jam Masuk</td>';
                                    attendance += '<td style="border: 1px solid black;padding-left:4px;">Jam Keluar</td>';
                                attendance += '</tr>';
                                attendance += '<tr>';
                                var starts = 'start';
                                var ends = 'end';
                                    attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight: bold;font-size: 20px;color: #149600">'+result.attendance[i].startss+'<span class="pull-right" style="padding-right:2px;color:cornflowerblue; cursor:pointer; float: right;" onclick="showLoc(\''+i+'\',\''+result.attendance.length+'\',\''+starts+'\')"><i class="fas fa-map-marker-alt"></i></span></td>';
                                    if (result.attendance[i].startss == result.attendance[i].endss) {
                                        attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight: bold;font-size: 20px;color: #eb0000">--:--</td>';
                                    }else{
                                        attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight: bold;font-size: 20px;color: #eb0000">'+result.attendance[i].endss+'<span class="pull-right" style="padding-right:2px;color:cornflowerblue; cursor:pointer; float: right;" onclick="showLoc(\''+i+'\',\''+result.attendance.length+'\',\''+ends+'\')"><i class="fas fa-map-marker-alt"></i></span></td>';
                                    }
                                attendance += '</tr>';
                                attendance += '<tr id="tr_start_'+i+'" style="display:none">';
                                    attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight:bold;text-align:center;" colspan="2">';
                                    attendance += '<div class="mapouter"><div class="gmap_canvas"><iframe width="300" height="200" id="gmap_canvas" src="https://maps.google.com/maps?q='+result.attendance[i].latitude_start+','+result.attendance[i].longitude_start+'&t=&z=10&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://2yu.co">2yu</a><br><style>.mapouter{position:relative;text-align:right;height:200px;width:300px;}</style><a href="https://embedgooglemap.2yu.co">html embed google map</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:200px;width:300px;}</style></div></div>';
                                    attendance += '</td>';
                                attendance += '</tr>';

                                if (result.attendance[i].startss != result.attendance[i].endss) {
                                    attendance += '<tr id="tr_end_'+i+'" style="display:none">';
                                        attendance += '<td style="border: 1px solid black;padding-left:4px;font-weight:bold;text-align:center;" colspan="2">';
                                        attendance += '<div class="mapouter"><div class="gmap_canvas"><iframe width="300" height="200" id="gmap_canvas" src="https://maps.google.com/maps?q='+result.attendance[i].latitude_end+','+result.attendance[i].longitude_end+'&t=&z=10&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe><a href="https://2yu.co">2yu</a><br><style>.mapouter{position:relative;text-align:right;height:200px;width:300px;}</style><a href="https://embedgooglemap.2yu.co">html embed google map</a><style>.gmap_canvas {overflow:hidden;background:none!important;height:200px;width:300px;}</style></div></div>';
                                        attendance += '</td>';
                                    attendance += '</tr>';
                                }
                            attendance += '</table>';
                        attendance += '</div>';
                    }

                    $('#divDriver').append(attendance);
                }
                else{
                    audio_error.play();
                    openErrorGritter('Error', result.message);
                    $('#operator').val('');
                }
            });
        }

        function showLoc(index,length,type) {
            for(var i = 0; i < length;i++){
                $('#tr_start_'+i).hide();
                $('#tr_end_'+i).hide();
            }
            $('#tr_'+type+'_'+index).show();
        }

        function getActualFullDate() {
            var d = new Date();
            var day = addZero(d.getDate());
            var month = addZero(d.getMonth()+1);
            var year = addZero(d.getFullYear());
            var h = addZero(d.getHours());
            var m = addZero(d.getMinutes());
            var s = addZero(d.getSeconds());
            return year + "-" + month + "-" + day + " " + h + ":" + m + ":" + s;
        }

        function openSuccessGritter(title, message){
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url("images/image-screen.png") }}',
                sticky: false,
                time: '3000'
            });
        }

        function openErrorGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url("images/image-stop.png") }}',
                sticky: false,
                time: '3000'
            });
        }

    </script>
@endsection
