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
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: white; top: 20%; left: 10%;">
                <span style="font-size: 40px"><i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <h1 style="font-size: 18px; font-weight: bold;">
                    {{ $title }}<br><span style="color: #605ca8; font-size: 15px;">{{$title_jp}}</span>
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <div class="table-responsive" style="margin-top: 20px;">
                    <table class="table table-bordered table-striped" style="width: 100%; text-align: center;">
                        <thead class="thead-dark">
                            <tr>
                                <th style="width: 5%;">#</th>
                                <th style="width: 20%;">Tgl</th>
                                <th style="width: 15%;">Jam</th>
                                <th style="width: 25%;">User</th>
                                <th style="width: 20%;">Car</th>
                                <th style="width: 15%;">Action</th>
                            </tr>
                        </thead>
                        <tbody id="toll_parking_table">
                            <!-- Data rows go here -->
                        </tbody>
                    </table>
                </div>
            </div>
            <table style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px; margin-top: 20px;">
                <tr>
                    <td>
                        <a class="btn btn-danger btn-sm" style="width: 90%; font-weight: bold; font-size: 18px;" href="{{url('')}}">
                            Kembali
                        </a>
                    </td>
                </tr>
            </table>
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
                        datas += "<tr>";
                        datas += "<td>" + (i+1) + "</td>";
                        var dateParts = result.data[i].date_from.split(' ')[0].split('-');
                        var months = ['Jan', 'Feb', 'Mar', 'Apr', 'May', 'Jun', 'Jul', 'Aug', 'Sep', 'Oct', 'Nov', 'Dec'];
                        var day = dateParts[2];
                        var month = months[parseInt(dateParts[1], 10) - 1];
                        var year = dateParts[0].substr(2,2);
                        datas += "<td>" + day + "-" + month + "-" + year + "</td>";
                        datas += "<td>" + result.data[i].date_from.substr(11,5) + " - " + result.data[i].date_to.substr(11,5) + "</td>";
                        datas += "<td>" + result.data[i].created_by_name + "</td>";
                        datas += "<td>" + result.data[i].plat_no + " - " + result.data[i].car + "</td>";
                        if(result.data[i].remark.match(/daily/gi)){
                            var url = '{{ url("index/additional/driver/daily_job") }}/' + result.data[i].id;
                            datas += "<td><a class='btn btn-success btn-sm' href='" + url + "'>Isi Data</a></td>";
                        } else {
                            var url = '{{ url("index/additional/driver/job") }}/' + btoa(result.data[i].task_id);
                            datas += "<td><a class='btn btn-success btn-sm' href='" + url + "'>Isi Data</a></td>";
                        }
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
