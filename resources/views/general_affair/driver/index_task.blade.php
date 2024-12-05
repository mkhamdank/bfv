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
        <input type="hidden" name="id" id="id" value="{{$id}}">

        <div class="row">
            <div class="col-md-12" style="text-align: center;">
                <h1>
                    {{ $title }}
                </h1>
            </div>
        </div>
        <div class="row">
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <!-- <span style="color: white; font-weight: bold;">Kerjakan Tugas</span> -->
            </div>
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <span style=""></span>
                <input type="hidden" class="form-control" id="latitude" name="latitude">
                <input type="hidden" class="form-control" id="longitude" name="longitude">
            </div>
            
                <table id="div_vehicle" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Driver</label>
                            <input type="text" name="driver" id="driver" class="form-control" style="width: 100%; text-align: center;" placeholder="Driver" readonly="" value="{{$driver_task->driver_id}} - {{$driver_task->driver_name}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Kendaraan</label>
                            <input type="text" name="vehicle" id="vehicle" class="form-control" style="width: 100%; text-align: center;" placeholder="Kendaraan" readonly="" value="{{$driver_task->car}} - {{$driver_task->plat_no}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Destinasi</label>
                            <input type="text" name="location" id="location" class="form-control" style="width: 100%; text-align: center;" placeholder="Destination" readonly="" value="{{$driver_task->destination}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px;">
                            <label>Tanggal Pengisian</label>
                            <input type="text" name="date" id="date" class="form-control" style="width: 100%; text-align: center;" placeholder="Tanggal" readonly="" value="{{date('Y-m-d')}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px;">
                            <label>Jam Pengisian</label>
                            <table style="width: 100%; padding-left: 20px; padding-right: 20px;">
                                <tr>
                                    <td><input type="number" name="hour" id="hour" class="form-control" style="width: 100%; text-align: center;" placeholder="Jam" value="{{date('H')}}"></td>
                                    <td><input type="number" name="minute" id="minute" class="form-control" style="width: 100%; text-align: center;" placeholder="Menit" value="{{str_pad(date('i'), 2, '0', STR_PAD_LEFT);}}"></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block;">
                            <label>Odometer (KM) <span style="color: red;">*</span></label>
                            <?php if ($data_vehicle != null) { ?>
                                <input type="text" name="odometer" id="odometer" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Odometer" value="{{$data_vehicle->odometer}}" readonly>
                            <?php }else{ ?>
                                <input type="number" name="odometer" id="odometer" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Odometer" value="">
                            <?php } ?>
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block;">
                            <label>Jenis BBM <span style="color: red;">*</span></label>
                            <select class="form-control" style="width: 100%;" data-placeholder="Pilih Jenis BBM" id="fuel_type" onchange="changeFuelType(this.value)">
                                <option value="-">Pilih BBM</option>
                                @foreach($bbm as $bbms)
                                <option value="{{explode('_',$bbms)[0]}}">{{explode('_',$bbms)[0]}}</option>
                                @endforeach
                            </select>
                        </td>
                    </tr>

                    <?php if ($data_vehicle != null && $data_vehicle_fuel != null): ?>
                        <tr style="">
                            <td style="display: inline-block;">
                                <label>Kondisi BBM Sebelum Isi (Liter)</label>
                                <?php if ($data_vehicle != null && $data_vehicle_fuel != null) { ?>
                                    <input type="text" name="fuel_actual" id="fuel_actual" class="form-control" style="width: 100%; background-color: white; text-align: center;" placeholder="Kondisi BBM" value="{{($data_vehicle_fuel->fuelFiltered/100)*$data_vehicle_fuel->fuelCapacity}}" readonly>
                                <?php }else{ ?>
                                    <input type="number" name="fuel_actual" id="fuel_actual" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Kondisi BBM" value="">
                                <?php } ?>
                            </td>
                        </tr>
                    <?php endif ?>
                    
                    <tr>
                        <td style="display: inline-block;">
                            <label>Pengisian BBM (Liter) <span style="color: red;">*</span></label>
                            <input type="number" name="fuel" id="fuel" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Pengisian BBM" value="">
                        </td>
                    </tr>

                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px;">
                            <label>Harga <span style="color: red;">*</span></label>
                            <table style="width: 100%;">
                                <tr>
                                    <td>Per Liter <span style="color: red;">*</span></td>
                                    <td>Total <span style="color: red;">*</span></td>
                                </tr>
                                <tr>
                                    <td><input type="number" name="fuel_amount_liter" id="fuel_amount_liter" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Harga Per Liter" value="" onchange="changeLiter(this.value)"></td>
                                    <td><input type="number" name="fuel_amount" id="fuel_amount" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Harga Total" value=""></td>
                                </tr>
                            </table>
                        </td>
                    </tr>

                    <tr>
                        <td style="display: inline-block;">
                            <label>Foto Pengisian <span style="color: red;">*</span></label>
                            <input type="file" name="fileData" id="fileData" class="form-control" style="width: 100%" placeholder="File" onchange="readURL(this);">
                            <br>
                            <img width="200px" id="blah" src="" style="display: none" alt="your image" />
                        </td>
                    </tr>

                    <tr>
                        <td style="display: inline-block;">
                            <label>Foto Odometer Sebelum Pengisian <span style="color: red;">*</span></label>
                            <input type="file" name="fileDataOdoBefore" id="fileDataOdoBefore" class="form-control" style="width: 100%" placeholder="File" onchange="readURLOdoBefore(this);">
                            <br>
                            <img width="200px" id="blahOdoBefore" src="" style="display: none" alt="your image" />
                        </td>
                    </tr>

                    <tr>
                        <td style="display: inline-block;">
                            <label>Foto Odometer Setelah Pengisian <span style="color: red;">*</span></label>
                            <input type="file" name="fileDataOdoAfter" id="fileDataOdoAfter" class="form-control" style="width: 100%" placeholder="File" onchange="readURLOdoAfter(this);">
                            <br>
                            <img width="200px" id="blahOdoAfter" src="" style="display: none" alt="your image" />
                        </td>
                    </tr>

                    <tr>
                        <td style="padding-top: 10px;">
                            <button class="btn btn-success btn-sm" style="width: 90%; font-weight: bold; font-size: 20px;" onclick="submitDriver();">
                                Submit
                            </button>
                        </td>
                    </tr>
                </table>
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

        function readURLOdoBefore(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
              $('#blahOdoBefore').show();
              $('#blahOdoBefore')
              .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
          }
        }

        function readURLOdoAfter(input) {
          if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
              $('#blahOdoAfter').show();
              $('#blahOdoAfter')
              .attr('src', e.target.result);
            };

            reader.readAsDataURL(input.files[0]);
          }
        }

        function changeFuelType(fuel_type) {
            if ($("#fuel").val() == '') {
                openErrorGritter('Error!','Isi Jumlah Liter');
                return false;
            }
            var harga = 0;
            for(var i = 0; i < bbm.length;i++){
                if (fuel_type == bbm[i].split('_')[0]) {
                    harga = bbm[i].split('_')[1];
                }
            }
            $('#fuel_amount_liter').val(harga);
            $('#fuel_amount').val(parseFloat(harga)*parseFloat($("#fuel").val()));
        }

        function changeLiter(amountLiter) {
            if ($('#fuel').val() == '') {
                openErrorGritter('Isikan Pengisian BBM (Liter)');
                $('#fuel').val('');
                $('#fuel_amount_liter').val('');
                $('#fuel_amount').val('');
                return false;
            }

            var total_amount = parseFloat($('#fuel').val()) * parseInt(amountLiter);
            $('#fuel_amount').val(total_amount);
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
        }

        var bbm = <?php echo json_encode($bbm2); ?>;

        function submitDriver() {
            // $('#loading').show();
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
            if ($('#fuel').val() == '' || $('#fuel_type').val() == '-' || $('#odometer').val() == '' || $('#fuel_amount').val() == '' || $('#fuel_amount_liter').val() == '') {
                $('#loading').hide();
                openErrorGritter('Error!','Isikan BBM dan Odometer');
                return false;
            }

            var fileData = null;

            if ($('#fileData').prop('files')[0] == undefined) {
                $('#loading').hide();
                openErrorGritter('Error!','Isikan Foto Saat Pengisian');
                return false;
            }
            fileData = $('#fileData').prop('files')[0];

            var fileDataOdoBefore = null;

            if ($('#fileDataOdoBefore').prop('files')[0] == undefined) {
                $('#loading').hide();
                openErrorGritter('Error!','Isikan Foto Odometer dan Indikator Sebelum Pengisian');
                return false;
            }
            fileDataOdoBefore = $('#fileDataOdoBefore').prop('files')[0];

            var fileDataOdoAfter = null;

            if ($('#fileDataOdoAfter').prop('files')[0] == undefined) {
                $('#loading').hide();
                openErrorGritter('Error!','Isikan Foto Odometer dan Indikator Setelah Pengisian');
                return false;
            }
            fileDataOdoAfter = $('#fileDataOdoBefore').prop('files')[0];

            var formData = new FormData();
            formData.append('fileData', fileData);
            formData.append('fileDataOdoBefore', fileDataOdoBefore);
            formData.append('fileDataOdoAfter', fileDataOdoAfter);
            formData.append('latitude',$('#latitude').val());
            formData.append('longitude',$('#longitude').val());
            formData.append('fuel',$('#fuel').val());
            formData.append('fuel_actual',$('#fuel_actual').val());
            formData.append('fuel_type',$('#fuel_type').val());
            formData.append('times',$('#hour').val()+':'+$('#minute').val());
            formData.append('fuel_amount',$('#fuel_amount').val());
            formData.append('fuel_amount_liter',$('#fuel_amount_liter').val());
            formData.append('location',$('#location').val());
            formData.append('odometer',$('#odometer').val());
            formData.append('latitude',$('#latitude').val());
            formData.append('longitude',$('#longitude').val());

            $.ajax({
                url:"{{ url('input/driver/job/') }}/{{$id}}",
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
                        openSuccessGritter('Success','Success Kerjakan Tugas');
                        $('#div_vehicle').hide();
                    }else{
                        openErrorGritter('Error!',data.message);
                        $('#loading').hide();
                    }

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
