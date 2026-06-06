@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link rel="stylesheet" href="{{ url("css/buttons.dataTables.min.css")}}">
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

        .select2-selection__choice{
        	color: black !important;
        }

    </style>
@stop

@section('content')
    <section id="vfi-container">
        <div id="loading" style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8;">
            <p style="position: absolute; color: White; top: 45%; left: 35%;">
                <span style="font-size: 40px">Please Wait...<i class="fa-solid fa-spinner fa-spin"></i></span>
            </p>
        </div>
        <input type="hidden" id="start_time" value="">
        <input type="hidden" id="incoming_check_code" value="">
        <div class="row" style="margin: 1%">
            <div class="card">
                <div class="card-header mt-2">
                    <span>Report Production Check {{Auth::user()->name}}</span>
                </div>
                <div class="row mt-2 mb-2">
                	<div class="col-sm-12 col-xs-12" style="text-align: center;">
		                <div class="card">
		                    <div class="card-body" style="padding: 0px">
		                       <h4 style="padding: 0px">Filter</h4>
		                            <div class="col-md-4" style="padding-left: 5px;text-align: left;display: inline-block;">
		                                <label>Date From</label>
		                                <div class="input-group date">
		                                    <div class="input-group-addon bg-green" style="border: none; background-color: #469937; color: white;">
		                                        <i class="fa fa-calendar" style="padding: 10px"></i>
		                                    </div>
		                                    <input type="date" class="form-control" id="date_from" name="date_from" placeholder="Select Date From">
		                                </div>
		                            </div>
		                            <div class="col-md-4" style="padding-left: 5px;text-align: left;display: inline-block;">
		                                <label>Date To</label>
		                                <div class="input-group date">
		                                    <div class="input-group-addon bg-green" style="border: none; background-color: #469937; color: white;">
		                                        <i class="fa fa-calendar" style="padding: 10px"></i>
		                                    </div>
		                                    <input type="date" class="form-control" id="date_to" name="date_to" placeholder="Select Date To">
		                                </div>
		                            </div>
		                            <div class="col-md-8" style="padding-left: 5px;text-align: left;display: inline-block;padding-top: 10px">
		                                <label>Material</label>
		                                <div class="form-group">
		                                    <select class="form-control select2" multiple="multiple" id='materialSelect' onchange="changeMaterial()" data-placeholder="Select Material" style="width: 100%;color: black !important">
		                                        @foreach($materials as $material)
		                                        <option value="{{$material->material_number}}">{{$material->material_number}} - {{$material->material_description}}</option>
		                                        @endforeach
		                                    </select>
		                                    <input type="text" name="material" id="material" style="color: black !important" hidden>
		                                </div>
		                            </div>
		                            <div class="col-md-12">
		                                <div class="form-group">
		                                    <button class="btn btn-primary col-sm-14" onclick="fetchData()">Search</button>
		                                    <!-- <button class="btn btn-success"><i class="fa fa-download"></i> Export Excel Without Merge</button> -->
		                                    <!-- <input class="btn btn-success" type="submit" name="publish" value="Export Excel Without Merge">
		                                    <input class="btn btn-warning" type="submit" name="save" value="Export Excel With Merge"> -->
		                                </div>
		                            </div>
		                    </div>
		                </div>
		            </div>
		            <div class="col-sm-12 col-xs-12" style="text-align: center;">
		                <div class="card">
		                    <div class="card-body" style="padding: 0px">
		                        <div class="col-md-12" style="padding: 10px;overflow-x: scroll;">
		                            <table class="table user-table no-wrap" id="tableKensa">
		                                <thead>
		                                    <tr>
		                                        <th style="background-color: #3f50b5;color: white !important;">SN</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Date</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Material</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Inspector</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Qty Check</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Total OK</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Total NG</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">NG Ratio (%)</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">NG Name</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">NG Qty</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Lot Status</th>
		                                        <th style="background-color: #3f50b5;color: white !important;">Recheck Status</th>
		                                    </tr>
		                                </thead>
		                                <tbody id="bodyTableKensa">
		                                    
		                                </tbody>
		                            </table>
		                        </div>
		                    </div>
		                </div>
		            </div>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('scripts')
<script src="<?php echo e(url("js/jquery.numpad.js")); ?>"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="https://code.highcharts.com/highcharts.js"></script>
<script src="https://code.highcharts.com/modules/pareto.js"></script>
<script src="https://code.highcharts.com/modules/exporting.js"></script>
<script src="https://code.highcharts.com/modules/export-data.js"></script>
<script src="https://code.highcharts.com/modules/accessibility.js"></script>
<script src="{{ url("js/jquery.dataTables.min.js") }}"></script>
<script src="{{ url("js/dataTables.bootstrap4.min.js") }}"></script>
<script src="{{ url("js/jquery.flot.min.js") }}"></script>
<script src="{{ url("js/dataTables.buttons.min.js")}}"></script>
<script src="{{ url("js/buttons.flash.min.js")}}"></script>
<script src="{{ url("js/jszip.min.js")}}"></script>
<script src="{{ url("js/vfs_fonts.js")}}"></script>
<script src="{{ url("js/buttons.html5.min.js")}}"></script>
<script src="{{ url("js/buttons.print.min.js")}}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        // $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="align-items:left"></table>';
        // $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.5"></div>';
        // $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:1.5vw; height: 50px;width:100%"/>';
        // $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-primary" style="font-size:1.5vw; width:40px;background-color:#b5ffa8;color:black"></button>';
        // $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn" style="font-size:1.5vw; width: 100%;background-color:#ffb84d;color:black"></button>';
        // $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.done').addClass('btn-primary');};

        $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="width: 25%;background-color:white;"></table>';
        $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.4"></div>';
        $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:2vw; height: 50px;"/>';
        $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-info" style="font-size:2vw; width:50px;"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn btn-success" style="font-size:2vw; width: 100%;color:white;background-color:green;border-color:green"></button>';
        $.fn.numpad.defaults.onKeypadCreate = function(){
            $(this).find('.done').css('background-color','white');
            $(this).find('.done').css('color','rgb(72,156,78)');
            $(this).find('.done').css('border-color','rgb(72,156,78)');

            $(this).find('.sep').css('background-color','white');
            $(this).find('.sep').css('color','black');
            $(this).find('.sep').css('border-color','#0dcaf0');
            $(this).find('.sep').css('width','50px');

            $(this).find('.cancel').css('background-color','white');
            $(this).find('.cancel').css('color','rgb(219,103,115)');
            $(this).find('.cancel').css('border-color','rgb(219,103,115)');

            $(this).find('.clear').css('background-color','white');
            $(this).find('.clear').css('color','black');
            $(this).find('.clear').css('border-color','black');

            $(this).find('.del').css('background-color','white');
            $(this).find('.del').css('color','black');
            $(this).find('.del').css('border-color','black');
        };

        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        $(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });

            $('.select2').select2({
            	allowClear:true
            });
            fetchData();
        });

        function changeMaterial() {
	        $("#material").val($("#materialSelect").val());
	    }

	    function fetchData() {
        $('#loading').show();
        var data = {
            material:$('#material').val(),
            date_from:$('#date_from').val(),
            date_to:$('#date_to').val(),
        }
        $.get('{{ url("vfi/fetch/true/report") }}',data,  function(result, status, xhr){
            if(result.status){
                $('#tableKensa').DataTable().clear();
                $('#tableKensa').DataTable().destroy();
                $("#bodyTableKensa").html('');
                var bodyTable = '';
                for (var i = 0; i < result.outgoing.length; i++) {
                    var background = '';
                    if (result.outgoing[i].lot_status == 'LOT OUT') {
                        background = 'background-color:#ffc4c7';
                    }
                    bodyTable += '<tr style="'+background+'">';
                    bodyTable += '<td>'+result.outgoing[i].serial_number+'</td>';
                    bodyTable += '<td>'+getFormattedDateTime(new Date(result.outgoing[i].created))+'</td>';
                    bodyTable += '<td>'+result.outgoing[i].material_number+'<br>'+result.outgoing[i].material_description+'</td>';
                    bodyTable += '<td>'+result.outgoing[i].inspector+'</td>';
                    bodyTable += '<td style="text-align:right">'+result.outgoing[i].qty_check+'</td>';
                    bodyTable += '<td style="text-align:right">'+result.outgoing[i].total_ok+'</td>';
                    bodyTable += '<td style="text-align:right">'+result.outgoing[i].total_ng+'</td>';
                    bodyTable += '<td style="text-align:right">'+result.outgoing[i].ng_ratio+'</td>';
                    bodyTable += '<td>'+result.outgoing[i].ng_name+'</td>';
                    bodyTable += '<td style="text-align:right">'+result.outgoing[i].ng_qty+'</td>';
                    bodyTable += '<td>'+result.outgoing[i].lot_status+'</td>';
                    if (result.outgoing[i].lot_status == 'LOT OUT' && result.outgoing[i].recheck_status == 'Checked') {
                        bodyTable += '<td style="background-color:#dcffc4">'+(result.outgoing[i].recheck_status || '')+'</td>';
                    }else if(result.outgoing[i].lot_status == 'LOT OUT' && result.outgoing[i].recheck_status == null){
                        var url = '{{url("index/outgoing/true/input/lot_out/")}}'+'/'+result.outgoing[i].serial_number+'/'+result.outgoing[i].check_date;
                        bodyTable += '<td><a class="btn btn-primary btn-sm" href="'+url+'">Recheck</a></td>';
                    }else{
                        bodyTable += '<td>'+(result.outgoing[i].recheck_status || '')+'</td>';
                    }
                    bodyTable += '</tr>';
                }
                $('#bodyTableKensa').append(bodyTable);

                var table = $('#tableKensa').DataTable({
                    'dom': 'Bfrtip',
                    'responsive':true,
                    'lengthMenu': [
                    [ 10, 25, 50, -1 ],
                    [ '10 rows', '25 rows', '50 rows', 'Show all' ]
                    ],
                    'buttons': {
                        buttons:[
                        {
                            extend: 'pageLength',
                            className: 'btn btn-default',
                        },
                        {
                            extend: 'copy',
                            className: 'btn btn-success',
                            text: '<i class="fa fa-copy"></i> Copy',
                                exportOptions: {
                                    columns: ':not(.notexport)'
                            }
                        },
                        {
                            extend: 'excel',
                            className: 'btn btn-info',
                            text: '<i class="fa fa-file-excel-o"></i> Excel',
                            exportOptions: {
                                columns: ':not(.notexport)'
                            }
                        },
                        {
                            extend: 'print',
                            className: 'btn btn-warning',
                            text: '<i class="fa fa-print"></i> Print',
                            exportOptions: {
                                columns: ':not(.notexport)'
                            }
                        }
                        ]
                    },
                    'paging': true,
                    'lengthChange': true,
                    'pageLength': 10,
                    'searching': true   ,
                    'ordering': true,
                    'order': [],
                    'info': true,
                    'autoWidth': true,
                    "sPaginationType": "full_numbers",
                    "bJQueryUI": true,
                    "bAutoWidth": false,
                    "processing": true
                });
                $('#loading').hide();
            }else{
                $('#loading').hide();
                openErrorGritter('Error!',result.message);
            }
        });
    }

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');

        $.date = function(dateObject) {
            var d = new Date(dateObject);
            var day = d.getDate();
            var month = d.getMonth() + 1;
            var year = d.getFullYear();
            if (day < 10) {
                day = "0" + day;
            }
            if (month < 10) {
                month = "0" + month;
            }
            var date = day + "/" + month + "/" + year;

            return date;
        };

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

	function onlyUnique(value, index, self) {
	  return self.indexOf(value) === index;
	}

	function getFormattedDateTime(date) {
        var year = date.getFullYear();

        var monthNames = ["Jan", "Feb", "Mar", "Apr", "May", "Jun",
          "Jul", "Aug", "Sep", "Oct", "Nov", "Dec"
        ];

        var month = (date.getMonth()+1).toString();
        month = month.length > 1 ? month : '0' + month;

        var day = date.getDate().toString();
        day = day.length > 1 ? day : '0' + day;

        var hour = date.getHours();
        if (hour < 10) {
            hour = "0" + hour;
        }

        var minute = date.getMinutes();
        if (minute < 10) {
            minute = "0" + minute;
        }
        var second = date.getSeconds();
        if (second < 10) {
            second = "0" + second;
        }
        
        // return day + '-' + monthNames[month] + '-' + year +'<br>'+ hour +':'+ minute +':'+ second;
        return year+'-'+month+'-'+day;
    }

    </script>
@endsection
