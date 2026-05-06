@extends('layouts.master_full')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css"
     integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI="
     crossorigin=""/>
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
        @media (max-width: 600px) {
            .table-responsive {
                font-size: 13px;
            }
            .table th, .table td {
                padding: 6px !important;
            }
            .table thead th {
                font-size: 12px;
            }
        }

    </style>
@stop

@section('content')
    <section id="vfi-container">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgba(0, 0, 0, 0.5); z-index: 30001; display: none; backdrop-filter: blur(4px);">
            <p style="position: absolute; color: white; top: 50%; left: 50%; transform: translate(-50%, -50%);">
            <span style="font-size: 50px"><i class="fa fa-spinner fa-spin"></i></span>
            </p>
        </div>

        <div class="row" style="margin-bottom: 10px;">
            <div class="col-md-12">
            <div style="text-align: center; padding: 5px 0;">
                <h1 style="font-size: 28px; font-weight: 600; margin: 0; color: #2c3e50;">
                {{ $title }}
                </h1>
                <p style="color: #7f8c8d; font-size: 14px; margin-top: 8px;">{{$title_jp}}</p>
            </div>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12" style="padding: 0 15px;">
            <div class="table-responsive" style="border-radius: 8px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); overflow: hidden;">
                <table class="table table-striped" style="width: 100%; margin: 0; border: none;">
                <thead style="background-color: #f8f9fa; border-bottom: 2px solid #e9ecef;">
                    <tr>
                    <th style="width: 5%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">#</th>
                    <th style="width: 20%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">Tgl</th>
                    <th style="width: 15%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">Jam</th>
                    <th style="width: 25%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">User</th>
                    <th style="width: 20%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">Car</th>
                    <th style="width: 15%; padding: 15px; font-weight: 600; color: #2c3e50; border: none;">Action</th>
                    </tr>
                </thead>
                <tbody id="toll_parking_table" style="background-color: white;">
                    <!-- Data rows go here -->
                </tbody>
                </table>
            </div>
            </div>
        </div>

        <div class="row" style="margin-top: 25px;">
            <div class="col-xs-12" style="text-align: center; padding: 0 15px;">
            <a class="btn btn-outline-secondary" style="width: 150px; font-weight: 600; border-radius: 6px; border: 2px solid #bdc3c7; color: #2c3e50; transition: all 0.3s ease;" href="{{url('')}}" onmouseover="this.style.backgroundColor='#ecf0f1'; this.style.borderColor='#95a5a6';" onmouseout="this.style.backgroundColor='transparent'; this.style.borderColor='#bdc3c7';">
                ← Kembali
            </a>
            </div>
        </div>

    </section>
@endsection

@section('scripts')
<script src="<?php echo e(url("js/jquery.numpad.js")); ?>"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        $(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');
            fetchData();
        });

        function fetchData() {
            $('#loading').show();
            var data = {
                driver_id: '{{ $driver_id }}'
            }
            $.get('{{ url("fetch/driver/toll_parking") }}', data, function(result, status, xhr){
                if(result.status){
                    $('#loading').hide();
                    $('#toll_parking_table').html("");
                    var datas = "";
                    for(var i = 0; i < result.data.length; i++){
                        var dateFrom = result.data[i].date_from;
                        var dateParts = dateFrom.split(' ')[0].split('-');
                        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        var formattedDate = dateParts[2] + " " + months[parseInt(dateParts[1], 10) - 1] + " " + dateParts[0].substr(2,2);
                        var timeFrom = dateFrom.substr(11,5);
                        var timeTo = result.data[i].date_to.substr(11,5);
                        
                        var actionUrl = result.data[i].remark.match(/daily/gi) 
                            ? '{{ url("index/additional/driver/daily_job") }}/' + result.data[i].id
                            : '{{ url("index/additional/driver/job") }}/' + btoa(result.data[i].task_id);
                        
                        datas += "<tr style='border-bottom: 1px solid #ecf0f1; transition: background-color 0.2s ease;' onmouseover=\"this.style.backgroundColor='#f8f9fa'\" onmouseout=\"this.style.backgroundColor='white'\">";
                        datas += "<td style='padding: 12px 15px; color: #7f8c8d;'>" + (i+1) + "</td>";
                        datas += "<td style='padding: 12px 15px; color: #2c3e50; font-weight: 500;'>" + formattedDate + "</td>";
                        datas += "<td style='padding: 12px 15px; color: #2c3e50;'>" + timeFrom + " - " + timeTo + "</td>";
                        datas += "<td style='padding: 12px 15px; color: #2c3e50;'>" + result.data[i].created_by_name + "</td>";
                        datas += "<td style='padding: 12px 15px; color: #2c3e50; font-size: 13px;'>" + result.data[i].plat_no + " <br> " + result.data[i].car + "</td>";
                        datas += "<td style='padding: 12px 15px;'><a class='btn btn-sm' style='background-color: #27ae60; color: white; border: none; border-radius: 4px; padding: 6px 12px; font-weight: 500; transition: all 0.2s ease;' href='" + actionUrl + "' onmouseover=\"this.style.backgroundColor='#229954'; this.style.boxShadow='0 2px 4px rgba(0,0,0,0.2)'\" onmouseout=\"this.style.backgroundColor='#27ae60'; this.style.boxShadow='none'\">Isi Data</a></td>";
                        datas += "</tr>";
                    }
                    $('#toll_parking_table').append(datas);
                }
                else{
                    $('#loading').hide();
                    openErrorGritter('Error!', result.message);
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
