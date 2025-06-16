@extends('layouts.master_full')

@section('title', 'VFI')

@section('styles')
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
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
            <p style="position: absolute; color: white; top: 20%; left: 10%;">
                <span style="font-size: 40px"><i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <h1 style="font-size: 18px; font-weight: bold;">
                    {{ $title }}<br><span style="color: #605ca8;">{{$title_jp}}</span>
                </h1>
            </div>
        </div>
        <?php if($message != ''){ ?>
        <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
            <p style="font-size: 20px; font-weight: bold; color: red;">Error!</p>
            <span style="font-size: 18px; color: red;">@php
                echo $message;
            @endphp</span>
        </div>
        <?php } ?>
        <?php if($message == ''){ ?>
            <div class="row">
                <table id="div_driver_1" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>ID</label>
                            <input type="text" name="id" id="id" class="form-control" style="width: 100%; text-align: center;" placeholder="Driver" readonly="" value="{{$id}}">
                            <input type="hidden" name="driver_id" id="driver_id" value="{{$detail_attendance->employee_id}}">
                            <input type="hidden" name="car" id="car" value="{{$detail_attendance->car}}">
                            <input type="hidden" name="plat_no" id="plat_no" value="{{$detail_attendance->plat_no}}">
                            <input type="hidden" name="driver_time" id="driver_time" value="{{$detail_attendance->datetime}}">
                        </td>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Tanggal</label>
                            <input type="text" name="date" id="date" class="form-control" style="width: 100%; text-align: center;" placeholder="Destination" readonly="" value="{{date('Y-m-d')}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Driver</label>
                            <input type="text" name="driver_name" id="driver_name" class="form-control" style="width: 100%; text-align: center;" placeholder="Driver" readonly="" value="{{$detail_attendance->name}}">
                        </td>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Shuttle</label>
                            <input type="text" name="destination" id="destination" class="form-control" style="width: 100%; text-align: center;" placeholder="Destination" readonly="" value="{{$destination}}">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 20px; padding-right: 20px;">
                            <label>Scan ID Card Penumpang</label>
                            <input type="text" name="tag" id="tag" class="form-control" style="width: 100%; text-align: center;" placeholder="Scan ID Card Penumpang" value="">
                        </td>
                    </tr>
                    <tr>
                        <td colspan="2" style="padding-left: 20px; padding-right: 20px;">
                            <button class="btn btn-danger" onclick="window.location.href='{{ url('index/driver/attendance/report') }}'" style="width: 100%;">Kembali</button>
                        </td>
                    </tr>
                </table>
            </div>
            <div style="padding-left: 10px; padding-right: 10px; margin-top: 10px;">
                <table id="div_driver_1" style="text-align: center;">
                    <thead>
                        <tr>
                            <th style="background-color: #605ca8; color: white; width: 1%; border: 1px solid white;">
                                #
                            </th>
                            <th style="background-color: #605ca8; color: white; width: 5%; border: 1px solid white;">
                                Emp
                            </th>
                            <th style="background-color: #605ca8; color: white; width: 5%; border: 1px solid white;">
                                Time
                            </th>
                        </tr>
                    </thead>
                    <tbody id="bodyAttendance">

                    </tbody>
                </table>
            </div>
        <?php } ?>
    </section>
@endsection

@section('scripts')
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var count_pass = 0;
        var passenger_attend = [];
        var passenger = null;
        
        $(document).ready(function() {

            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('#tag').val('');
            $('#tag').focus();
            count_pass = 0;
            $("#bodyAttendance").html('');
            passenger_attend = [];
            if('{{$message}}' == ''){
                fetchAttendance();
                passenger = <?php echo json_encode($passenger); ?>;
            }
        });

        var passenger_save = null;

        $('#tag').keydown(function(event) {
            if (event.keyCode == 13 || event.keyCode == 9) {
                $('#loading').show();
                passenger_save = null;
                if($("#tag").val().length >= 9){
                    var tag = $("#tag").val();
                    if(passenger != null){
                        var passengers = null;
                        for(var i = 0; i < passenger.length;i++){
                            if (passenger[i].tag == tag || passenger[i].employee_id.toUpperCase() == tag.toUpperCase()) {
                                passengers = passenger[i];
                                break;
                            }
                        }
                        if(passengers != null && passenger_attend.indexOf(passengers.employee_id) == -1){
                            $('#loading').show();
                            saveAttendance();

                            count_pass++;

                            var table = "<tr>";
                            table += "<td style='width: 1%; border: 1px solid black;'>" + count_pass + "</td>";
                            table += "<td style='width: 5%; border: 1px solid black; text-align: left; padding-left: 4px;'>" + passengers.name + "</td>";
                            table += "<td style='width: 5%; border: 1px solid black;'>"+getActualFullTime()+"</td>";
                            table += "</tr>";
                            $("#bodyAttendance").append(table);

                            passenger_attend.push(passengers.employee_id);

                            passenger_save = passengers;
                            $('#loading').hide();
                            
                            $('#tag').val('');
                            $('#tag').focus();
                        }else{
                            $('#loading').hide();
                            audio_error.play();
                            openErrorGritter('Error!','ID Card Invalid or Already Scanned');
                            $('#tag').val('');
                            $('#tag').focus();
                            return false;
                        }
                    }else{
                        $('#loading').hide();
                        audio_error.play();
                        openErrorGritter('Error!','Passenger Not Found');
                        $('#tag').val('');
                        $('#tag').focus();
                        return false;
                    }
                }else{
                    $('#loading').hide();
                    audio_error.play();
                    openErrorGritter('Error!','ID Card Invalid');
                    $('#tag').val('');
                    $('#tag').focus();
                    return false;
                }
            }
        });
        
        function saveAttendance() {
            $('#loading').show();
            var data = {
                destination: $('#destination').val(),
                id: $('#id').val(),
                passengers: passenger_save,
                timestamps: getActualFullDate(),
                timing: '{{$timing}}',
                driver_id: $('#driver_id').val(),
                car: $('#car').val(),
                plat_no: $('#plat_no').val(),
                driver_name: $('#driver_name').val(),
                driver_time: $('#driver_time').val(),
            }
            $.post('{{ url("input/passenger/attendance") }}', data, function(result, status, xhr){
                if(result.status){
                    $('#loading').hide();
                    openSuccessGritter('Success!','Attendance Saved');
                }else{
                    $('#loading').hide();
                    audio_error.play();
                    openErrorGritter('Error!','Failed to save attendance');
                }
            });
        }

        function fetchAttendance() {
            var data = {
                destination: $('#destination').val(),
                id: $('#id').val(),
            }
            $.get('{{ url("fetch/passenger/attendance") }}', data, function(result, status, xhr){
                if(result.status){
                    $('#loading').hide();
                    $("#bodyAttendance").html('');
                    passenger_attend = [];
                    count_pass = 0;
                    var table = "";
                    for(var i = 0; i < result.passenger.length; i++){
                        table += "<tr>";
                        table += "<td style='width: 1%; border: 1px solid black;'>" + (i+1) + "</td>";
                        table += "<td style='width: 5%; border: 1px solid black; padding-left: 4px; text-align: left;'>" + result.passenger[i].name + "</td>";
                        table += "<td style='width: 5%; border: 1px solid black; text-align: center;'>" + result.passenger[i].times + "</td>";
                        table += "</tr>";
                        count_pass++;
                        passenger_attend.push(result.passenger[i].employee_id);
                    }
                    $("#bodyAttendance").append(table);
                }else{
                    $('#loading').hide();
                    audio_error.play();
                    openErrorGritter('Error!','Failed to fetch attendance');
                }
            });
        }

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
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

        function getActualFullTime() {
            var d = new Date();
            var h = addZero(d.getHours());
            var m = addZero(d.getMinutes());
            var s = addZero(d.getSeconds());
            return h + ":" + m + ":" + s;
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
