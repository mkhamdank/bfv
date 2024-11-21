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
        <input type="hidden" id="ada_data" value="{{$ada_data}}">

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <h1>
                    {{ $title }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <span style="color: <?php echo $color ?>; font-weight: bold;">{{$status}}</span>
            </div>
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <span style="">{{$message}}</span>
                <input type="hidden" class="form-control" id="latitude" name="latitude">
                <input type="hidden" class="form-control" id="longitude" name="longitude">
            </div>
            <?php if ($status != 'Tugas Telah Selesai Dikerjakan!'): ?>
                <table id="div_vehicle" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="display: inline-block;">
                            <label>Kondisi BBM (Liter)</label>
                            <?php if ($data_vehicle != null) { ?>
                                <input type="text" name="fuel" id="fuel" class="form-control" style="width: 100%;" placeholder="Kondisi BBM" value="{{($data_vehicle_fuel->fuelFiltered/100)*$data_vehicle_fuel->fuelCapacity}}" readonly>
                            <?php }else{ ?>
                                <input type="number" name="fuel" id="fuel" class="form-control numpad" style="width: 100%" placeholder="Kondisi BBM" value="">
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block;">
                            <label>Odometer (KM)</label>
                            <?php if ($data_vehicle != null) { ?>
                                <input type="text" name="odometer" id="odometer" class="form-control" style="width: 100%" placeholder="Odometer" value="{{$data_vehicle->odometer}}" readonly>
                            <?php }else{ ?>
                                <input type="number" name="odometer" id="odometer" class="form-control numpad" style="width: 100%" placeholder="Odometer" value="">
                            <?php } ?>
                        </td>
                    </tr>

                    <?php if ($data_vehicle == null) { ?>
                    <tr id="tr_foto">
                        <td style="display: inline-block;">
                            <label>Foto Odometer</label>
                            <input type="file" name="fileData" id="fileData" class="form-control" style="width: 100%" placeholder="File" onchange="readURL(this);" accept="image/*;capture=camera">
                            <br>
                            <img width="200px" id="blah" src="" style="display: none" alt="your image" />
                        </td>
                    </tr>
                    <?php } ?>

                    <tr>
                        <td style="padding-top: 10px;">
                            <button class="btn btn-success btn-sm" style="width: 90%; font-weight: bold; font-size: 20px;" onclick="submitDriver();">
                                Submit
                            </button>
                        </td>
                    </tr>
                </table>
            <?php endif ?>
            <table style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px; margin-top: 20px;">
                <tr>
                    <td>
                        <a class="btn btn-danger btn-sm" style="width: 90%; font-weight: bold; font-size: 18px;" href="{{url('index/driver/job')}}">
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
        function readURL(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
              $('#blah').show();
              $('#blah')
              .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
          }
        }
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });
        if (screen.width < 400) {
            $.fn.numpad.defaults.gridTpl = '<table class="table modal-content" style="width: 80%; left: 20.225px;background-color:white;"></table>';
            $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.4"></div>';
            $.fn.numpad.defaults.displayTpl = '<input type="text" class="form-control" style="font-size:6vw; height: 50px;"/>';
            $.fn.numpad.defaults.buttonNumberTpl =  '<button type="button" class="btn btn-info" style="font-size:5vw; width:50px;"></button>';
            $.fn.numpad.defaults.buttonFunctionTpl = '<button type="button" class="btn btn-success" style="font-size:5vw; width: 100%;color:white;background-color:green;border-color:green"></button>';
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
                $(this).find('.cancel').html('<i class="fas fa-times"></i>');

                $(this).find('.clear').css('background-color','white');
                $(this).find('.clear').css('color','black');
                $(this).find('.clear').css('border-color','black');
                $(this).find('.clear').html('<i class="fas fa-trash"></i>');

                $(this).find('.del').css('background-color','white');
                $(this).find('.del').css('color','black');
                $(this).find('.del').css('border-color','black');
                $(this).find('.del').css('font-size','4vw');
                $(this).find('.del').html('<i class="fas fa-backspace"></i>');
            };
        }else{
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
        }

        
        // $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.cancel').css('border-color','rgb(219,103,115)');};

        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        $(document).ready(function() {

            if ($('#ada_data').val() != 'Ada') {
                $('#fuel').val('');
                $('#odometer').val('');
                $('#fileData').val('');
            }
            $('#tr_foto').show();
            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });
            getLocation();
            $('.select2').select2({
                allowClear:true
            });
        });

        function getLocation() {
          if (navigator.geolocation) {
            navigator.geolocation.getCurrentPosition(showPosition);
          } else { 
            alert("Browser tidak support");
          }
        }

        function showPosition(position) {
             $("#latitude").val(position.coords.latitude);
             $("#longitude").val(position.coords.longitude);
        
            // var map = L.map('map').setView([position.coords.latitude, position.coords.longitude], 13);

            // L.tileLayer('https://tile.openstreetmap.org/{z}/{x}/{y}.png', {
            //     maxZoom: 19,
            //     attribution: '&copy; <a href="http://www.openstreetmap.org/copyright">OpenStreetMap</a>'
            // }).addTo(map);

            // var marker = L.marker([position.coords.latitude, position.coords.longitude]).addTo(map);
        }

        function submitDriver() {
            $('#loading').show();
            if ($('#latitude').val() == null || $('#latitude').val() == "") {
                $("#loading").hide();
                openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
                $(window).scrollTop(0);
                getLocation();
                return false;
            }

            if ($('#longitude').val() == null || $('#longitude').val() == "") {
                $("#loading").hide();
                openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
                $(window).scrollTop(0);
                getLocation();
                return false;
            }
            if ($('#fuel').val() == '' || $('#odometer').val() == '') {
                $('#loading').hide();
                openErrorGritter('Error!','Isikan BBM dan Odometer');
                return false;
            }
            var file = '';

            var fileData = null;

            if ($('#ada_data').val() != 'Ada') {
                if ($('#fileData').prop('files')[0] == undefined) {
                    $('#loading').hide();
                    openErrorGritter('Error!','Isikan Foto Odometer');
                    return false;
                }
                fileData = $('#fileData').prop('files')[0];
            }

            var formData = new FormData();
            formData.append('fileData', fileData);
            formData.append('latitude',$('#latitude').val());
            formData.append('longitude',$('#longitude').val());
            formData.append('fuel',$('#fuel').val());
            formData.append('odometer',$('#odometer').val());
            formData.append('remark','{{$remark}}');

            $.ajax({
                url:"{{ url('input/qr_code/driver/') }}/{{$qr_code}}",
                method:"POST",
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status) {
                        $('#loading').hide();
                        openSuccessGritter('Success','Success Input Data');
                        $('#div_vehicle').hide();
                    }else{
                        openErrorGritter('Error!',data.message);
                        $('#loading').hide();
                    }

                }
            });
        }

        function checkVehicleData(plat_no) {
            $('#fuel').removeAttr('disabled');
            $('#odometer').removeAttr('disabled');
            $('#loading').show();
            var plat_no = $('#plat_no').val().split('_')[1];
            var data = {
                plat_no:plat_no
            }
            $.get('{{ url("fetch/qr_code/vehicle") }}',data,function(result, status, xhr){
                if(result.status){
                    $('#loading').hide();
                    $('#ada_data').val(result.ada_data);
                    $('#tr_foto').show();
                    if (result.data_vehicle != null) {
                        $('#fuel').val((result.data_vehicle_fuel.fuelFiltered/100)*result.data_vehicle_fuel.fuelCapacity);
                        $('#odometer').val(result.data_vehicle.odometer);

                        $('#fuel').prop('disabled',true);

                        $('#odometer').prop('disabled',true);
                    }

                    if (result.ada_data == 'Ada') {
                        $('#tr_foto').hide();
                    }else{
                        $('#fuel').val('');
                        $('#odometer').val('');
                    }
                }else{
                    $('#loading').hide();
                    openErrorGritter('Error!',result.message);
                }
            });
        }

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');


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
