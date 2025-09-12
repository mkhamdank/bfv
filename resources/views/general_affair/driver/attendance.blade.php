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
                <h1 style="font-size: 18px; font-weight: bold;">
                    {{ $title }}<br><span style="color: #605ca8; font-size: 15px;">{{$title_jp}}</span>
                </h1>
            </div>
        </div>
        <div class="row" id="divDriver" style="padding-bottom: 20px; padding-left: 20px; padding-right: 20px; overflow-y: scroll;">
            <input type="hidden" name="employee_id" id="employee_id" value="{{Auth::user()->username}}">
            <input type="hidden" name="name" id="name" value="{{Auth::user()->name}}">
            <input type="hidden" id="department" name="department" placeholder="Department" readonly="" value="General Affairs Department">
            <label>Karyawan</label>
            <input type="text" class="form-control"  placeholder="NIK Karyawan" readonly="" value="{{Auth::user()->username}} - {{Auth::user()->name}}" style="margin-bottom: 10px;">
            <input type="hidden" class="form-control" id="latitude" name="latitude">
            <input type="hidden" class="form-control" id="longitude" name="longitude">

            <label>Kendaraan</label>
            <select class="form-control" style="width: 100%; height: 200px;" data-placeholder="Pilih Kendaraan" id="vehicle" onchange="changeVehicle(this.value)">
                <option value="-">Pilih Kendaraan</option>
                <?php for ($i = 0; $i < count($vehicle); ++$i) { ?>
                    <option value="{{$vehicle[$i]->plat_no}}_{{$vehicle[$i]->car}}">{{$vehicle[$i]->plat_no}} - {{$vehicle[$i]->car}}</option>
                <?php } ?>
            </select>

            <label>Odometer (KM) <span style="color: red;">*</span></label>
            <input type="text" name="odometer" id="odometer" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Odometer" value="" >

            <label>Fuel (Liter) <span style="color: red;">*</span></label>
            <input type="text" name="fuel" id="fuel" class="form-control numpad" style="width: 100%; background-color: white; text-align: center;" placeholder="Fuel" value="" >
        </div>
        {{-- <div class="col-lg-12 col-md-12 col-sm-12" >
            <div id="map"></div>
        </div> --}}
        <div class="col-lg-12 col-md-12 col-sm-12" >
            <div class="validate-input" style="position: relative; width: 100% !important;margin-top:10px">
                <label style="font-size: 14px;font-weight: bold;">Foto Absensi<span style="color:red">*</span></label>
                <input type="file" onchange="readURL(this);" id="file_foto" style="display:none;width: 100%; height: 40px; font-size: 20px; text-align: center;" class="file">
                <button class="btn btn-primary btn-lg" id="btnImageSim" value="Photo" onclick="buttonImage(this)" style="width: 100%; font-size: 20px; text-align: center;"><i class="fa fa-camera"></i> Masukkan Foto Selfie</button>
                <img id="blahsim" src="" style="display: none;width: 100%;margin-top: 5px;" alt="your image" />
            </div>
            <div class="validate-input" style="position: relative; width: 100% !important;margin-top:10px">
                <input type="file" onchange="readURL2(this);" id="file_foto_odometer" style="display:none;width: 100%; height: 40px; font-size: 20px; text-align: center;" class="file">
                <button class="btn btn-primary btn-lg" id="btnImageOdo" value="Photo" onclick="buttonImageOdo(this)" style="width: 100%; font-size: 20px; text-align: center;"><i class="fa fa-camera"></i> Masukkan Foto Odometer</button>
                <img id="blahOdo" src="" style="display: none;width: 100%;margin-top: 5px;" alt="your image" />
            </div>
            <div class="validate-input" style="position: relative; width: 100% !important;margin-top:10px">
                <input type="file" onchange="readURL3(this);" id="file_location" style="display:none;width: 100%; height: 40px; font-size: 20px; text-align: center;" class="file">
                <button class="btn btn-primary btn-lg" id="btnImageLoc" value="Photo" onclick="buttonImageLoc(this)" style="width: 100%; font-size: 20px; text-align: center;"><i class="fa fa-camera"></i> Masukkan Foto Lokasi</button>
                <img id="blahLoc" src="" style="display: none;width: 100%;margin-top: 5px;" alt="your image" />
            </div>
            <button class="btn btn-success" style="width: 100%; margin-top: 10px; font-weight: bold; font-size: 20px;" onclick="save()">SUBMIT</button>
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

        
        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        $(document).ready(function() {
            $('#odometer').val('');
            $('#fuel').val('');
            $('#vehicle').val('').trigger('change');
            $('body').toggleClass("sidebar-collapse");
            $('#side_driver_attendance').addClass('menu-open');
            getLocation();
            $("#vehicle").select2({
                allowClear:true
            });

            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });
        });

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

        function changeVehicle(nopol) {
            var data = {
                nopol:nopol.split('_')[0]
            }

            $.get('{{ url("fetch/driver/odometer") }}', data, function(result, status, xhr){
                if(result.status){
                    $('#odometer').val('');
                    if (result.data_vehicle != null) {
                        $('#odometer').val(result.data_vehicle.odometer);
                    }

                    $('#fuel').val('');
                    if (result.data_vehicle_fuel != null) {
                        $('#fuel').val((result.data_vehicle_fuel.fuelFiltered/100)*result.data_vehicle_fuel.fuelCapacity);
                    }
                }else{
                    audio_error.play();
                    openErrorGritter('Error', result.message);
                }
            });
        }

        function buttonImage(elem) {
            $(elem).closest("div").find("input").click();
        }

        function buttonImageOdo(elem) {
            $(elem).closest("div").find("input").click();
        }

        function buttonImageLoc(elem) {
            $(elem).closest("div").find("input").click();
        }

        function readURL(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $(input).closest("div").find("img");
                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        // Resize to 50% of original dimensions
                        canvas.width = image.width * 0.5;
                        canvas.height = image.height * 0.5;
                        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

                        // Compress to JPEG with quality 0.5 (50%)
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', 0.5);

                        $(img).show();
                        $(img).attr('src', compressedDataUrl);
                    };
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $(input).closest("div").find("img");
                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        // Resize to 50% of original dimensions
                        canvas.width = image.width * 0.5;
                        canvas.height = image.height * 0.5;
                        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

                        // Compress to JPEG with quality 0.5 (50%)
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', 0.5);

                        $(img).show();
                        $(img).attr('src', compressedDataUrl);
                    };
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL3(input) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = $(input).closest("div").find("img");
                    var image = new Image();
                    image.src = e.target.result;

                    image.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        // Resize to 50% of original dimensions
                        canvas.width = image.width * 0.5;
                        canvas.height = image.height * 0.5;
                        ctx.drawImage(image, 0, 0, canvas.width, canvas.height);

                        // Compress to JPEG with quality 0.5 (50%)
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', 0.5);

                        $(img).show();
                        $(img).attr('src', compressedDataUrl);
                    };
                };

                reader.readAsDataURL(input.files[0]);
            }
        }
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

    function save() {
        $("#loading").show();

        if ($('#latitude').val() == null || $('#latitude').val() == "") {
            $("#loading").hide();
            openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
            $(window).scrollTop(0);
            return false;
        }

        if ($('#vehicle').val() == '-') {
            $("#loading").hide();
            openErrorGritter('Error!', 'Isi Kendaraan');
            $(window).scrollTop(0);
            return false;
        }

        if ($('#longitude').val() == null || $('#longitude').val() == "") {
            $("#loading").hide();
            openErrorGritter('Error!', 'Izinkan sistem mengakses lokasi Anda');
            $(window).scrollTop(0);
            return false;
        }

        if ($('#file_foto').prop('files')[0] == null || $('#file_foto_odometer').prop('files')[0] == null || $('#file_location').prop('files')[0] == null) {
            $("#loading").hide();
            openErrorGritter('Error!', 'Foto Harus Diisi');
            $(window).scrollTop(0);
            return false;
        }

        var data = {
            employee_id :$('#employee_id').val(),
            name :$('#name').val(),
            department :$('#department').val(),
            latitude :$('#latitude').val(),
            longitude :$('#longitude').val(),
            odometer :$('#odometer').val(),
            fuel :$('#fuel').val(),
            plat_no :$('#vehicle').val().split('_')[0],
            car :$('#vehicle').val().split('_')[1],
        }

        // var fotoSelfie = $('#blahsim').attr('src');
        // var fotoOdometer = $('#blahOdo').attr('src');
        // var fotoLocation = $('#blahLoc').attr('src');

        // formData.append('file_foto', fotoSelfie);
        // formData.append('file_foto_odometer', fotoOdometer);
        // formData.append('file_location', fotoLocation);

        // $.ajax({
        //     url:"{{ url('input/driver/attendance') }}",
        //     method:"POST",
        //     data:formData,
        //     dataType:'JSON',
        //     contentType: false,
        //     cache: false,
        //     processData: false,
        //     success: function (response) {
        //         $("#loading").hide();
        //         openSuccessGritter('Success', 'Data Berhasil Disimpan');
        //         window.location.replace("{{url('index/driver/attendance/report')}}");
        //         // $('#myModal').modal('hide');

        //     },
        //     error: function (response) {
        //         openErrorGritter('Error!', response.message);
        //     },
        // })
        $.post('{{ url("input/driver/attendance") }}',data, function(result, status, xhr) {
            if (result.status) {
                $('#loading').hide();
                openSuccessGritter('Success','Success Saving Data');
                saveImage1(result.id);
                saveImage2(result.id);
                saveImage3(result.id);
                // window.location.replace("{{url('index/driver/attendance/report')}}");
            }else{
                openErrorGritter('Error!', result.message);
                $('#loading').hide();
            }
        });
    }

    var status_image = 0;

    function saveImage1(id){
        $('#loading').show();
        var fotoSelfie = $('#blahsim').attr('src');

        var data = {
            file_foto : fotoSelfie,
            id: id
        }

        $.post('{{ url("input/driver/attendance_image1") }}',data, function(result, status, xhr) {
            if (result.status) {
                $('#loading').hide();
                openSuccessGritter('Success','Success Saving Image');
                status_image += 1;
                if(status_image == 3){
                    window.location.replace("{{url('index/driver/attendance/report')}}");
                }
            }else{
                openErrorGritter('Error!', result.message);
                $('#loading').hide();
            }
        });
    }

    function saveImage2(id){
        $('#loading').show();
        var fotoOdometer = $('#blahOdo').attr('src');

        var data = {
            file_foto_odometer : fotoOdometer,
            id: id
        }

        $.post('{{ url("input/driver/attendance_image2") }}',data, function(result, status, xhr) {
            if (result.status) {
                $('#loading').hide();
                openSuccessGritter('Success','Success Saving Image');
                status_image += 1;
                if(status_image == 3){
                    window.location.replace("{{url('index/driver/attendance/report')}}");
                }
            }else{
                openErrorGritter('Error!', result.message);
                $('#loading').hide();
            }
        });
    }

    function saveImage3(id){
        $('#loading').show();
        var fotoLocation = $('#blahLoc').attr('src');

        var data = {
            file_location : fotoLocation,
            id: id
        }

        $.post('{{ url("input/driver/attendance_image3") }}',data, function(result, status, xhr) {
            if (result.status) {
                $('#loading').hide();
                openSuccessGritter('Success','Success Saving Image');
                status_image += 1;
                if(status_image == 3){
                    window.location.replace("{{url('index/driver/attendance/report')}}");
                }
            }else{
                openErrorGritter('Error!', result.message);
                $('#loading').hide();
            }
        });
    }

    </script>
@endsection
