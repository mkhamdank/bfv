@extends('layouts.master_full')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
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
                    {{ $title }}
                </h1>
            </div>
        </div>
        <div class="row">
            <?php if($status == 'error'){ ?>
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                <p style="font-size: 20px; font-weight: bold; color: red;">Error!</p>
                <span style="font-size: 18px; color: red;">{{$message}}</span>
            </div>
            <?php } ?>
            <?php if($status == 'success'){ ?>
            <input type="hidden" id="id" value="{{$driver_task->id}}">
            <input type="hidden" id="task_id" value="{{$driver_task->task_id}}">
                <table id="div_driver_1" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Driver</label>
                            <input type="text" name="driver" id="driver" class="form-control" style="width: 100%; text-align: center;" placeholder="Driver" readonly="" value="{{$driver_task->driver_name}}">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Tanggal</label>
                            <input type="text" name="date" id="date" class="form-control" style="width: 100%; text-align: center;" placeholder="Destination" readonly="" value="{{date('Y-m-d',strtotime($driver_task->date_from))}}">
                        </td>
                    </tr>
                </table>
                <table id="div_driver_2" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px; margin-top: 20px;">
                            <table style="width: 100%; padding-left: 20px; padding-right: 20px;">
                                <thead style="background-color: lightgrey;">
                                    <tr>
                                        <th style="border: 1px solid black;" colspan="3">E-Toll</th>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid black;">Biaya</th>
                                        <th style="border: 1px solid black;">File</th>
                                        <th style="border: 1px solid black;"><button style="font-size: 10px;" onclick="addEtoll()" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody id="bodyEtoll">
                                    <tr id="tr_etoll_0">
                                        <td style="border: 1px solid black; width: 2%;"><input type="text" name="etoll_0" id="etoll_0" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="E-Toll" value="" inputmode="numeric" pattern="[0-9]*"></td>
                                        <td style="border: 1px solid black; width: 2%;">
                                            <input type="file" name="file_etoll_0" id="file_etoll_0" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="File E-Toll" onchange="readURLEtoll(this,0);">
                                            <img id="blah_etoll_0" src="" style="display: none;width: 100%;margin-top: 5px;" alt="your image" />
                                        </td>
                                        <td style="border: 1px solid black; width: 1%;"></td>
                                    </tr>
                                </tbody>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px; margin-top: 20px;">
                            <table style="width: 100%; padding-left: 20px; padding-right: 20px;">
                                <thead style="background-color: lightgrey;">
                                    <tr>
                                        <th style="border: 1px solid black;" colspan="3">Parkir</th>
                                    </tr>
                                    <tr>
                                        <th style="border: 1px solid black;">Biaya</th>
                                        <th style="border: 1px solid black;">File</th>
                                        <th style="border: 1px solid black;"><button style="font-size: 10px;" onclick="addParking()" class="btn btn-success btn-xs"><i class="fa fa-plus"></i></button></th>
                                    </tr>
                                </thead>
                                <tbody id="bodyParking">
                                    <tr id="tr_parking_0">
                                        <td style="border: 1px solid black; width: 2%;"><input type="text" name="parking_0" id="parking_0" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="Parkir" value="" inputmode="numeric" pattern="[0-9]*"></td>
                                        <td style="border: 1px solid black; width: 2%;">
                                            <input type="file" name="file_parking_0" id="file_parking_0" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="File Parkir" onchange="readURLParking(this,0);">
                                            <img id="blah_parking_0" src="" style="display: none;width: 100%;margin-top: 5px;" alt="your image" />
                                        </td>
                                        <td style="border: 1px solid black; width: 1%;"></td>
                                    </tr>
                                </tbody>
                            </table>
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
                <div class="col-xs-12" id="div_driver_3" style="text-align: center; padding-left: 15px; padding-right: 15px; display: none;">
                    <p style="font-size: 20px; font-weight: bold; color: green;">Success!</p>
                    <span style="font-size: 18px; color: green;">Success Input Data<br>データの入力に成功しました</span>
                </div>
            <?php } ?>
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

        function readURLEtoll(input,id) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = new Image();
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        // Set canvas size to 50% of original
                        canvas.width = img.width * 0.5;
                        canvas.height = img.height * 0.5;
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        // Compress to JPEG, quality 0.7 (adjust as needed)
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);
                        $('#blah_etoll_' + id).attr('src', compressedDataUrl);
                        $('#blah_etoll_' + id).show();
                    };
                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        }

        function readURLParking(input,id) {
            if (input.files && input.files[0]) {
                var file = input.files[0];
                var reader = new FileReader();

                reader.onload = function (e) {
                    var img = new Image();
                    img.onload = function () {
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');
                        // Set canvas size to 50% of original
                        canvas.width = img.width * 0.5;
                        canvas.height = img.height * 0.5;
                        ctx.drawImage(img, 0, 0, canvas.width, canvas.height);
                        // Compress to JPEG, quality 0.7 (adjust as needed)
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', 0.7);
                        $('#blah_parking_' + id).attr('src', compressedDataUrl);
                        $('#blah_parking_' + id).show();
                    };
                    img.src = e.target.result;
                };

                reader.readAsDataURL(file);
            }
        }

        var count_etoll = 1;
        var count_parking = 1;

        function addEtoll() {
            var etoll = '';

            etoll += '<tr id="tr_etoll_'+count_etoll+'">';
                etoll += '<td style="border: 1px solid black; width: 2%;"><input type="text" name="etoll_'+count_etoll+'" id="etoll_'+count_etoll+'" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="E-Toll" value="" inputmode="numeric" pattern="[0-9]*"></td>';
                etoll += '<td style="border: 1px solid black; width: 2%;">';
                etoll += '<input type="file" name="file_etoll_'+count_etoll+'" id="file_etoll_'+count_etoll+'" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="File E-Toll" onchange="readURLEtoll(this,'+count_etoll+');">';
                etoll += '<img id="blah_etoll_'+count_etoll+'" src="" style="display:none; width: 100%; height: auto; border: 1px solid black;">';
                etoll += '</td>';
                etoll += '<td style="border: 1px solid black; width: 1%;"><button style="font-size: 10px;" onclick="removeEtoll('+count_etoll+')" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button></td>';
            etoll += '</tr>';

            $('#bodyEtoll').append(etoll);

            count_etoll++;
        }

        function removeEtoll(id) {
            $('#tr_etoll_'+id).remove();
        }

        function addParking() {
            var parking = '';

            parking += '<tr id="tr_parking_'+count_parking+'">';
                parking += '<td style="border: 1px solid black; width: 2%;"><input type="text" name="parking_'+count_parking+'" id="parking_'+count_parking+'" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="Parkir" value="" inputmode="numeric" pattern="[0-9]*"></td>';
                parking += '<td style="border: 1px solid black; width: 2%;">';
                parking += '<input type="file" name="file_parking_'+count_parking+'" id="file_parking_'+count_parking+'" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="File Parkir" onchange="readURLParking(this,'+count_parking+');">';
                parking += '<img id="blah_parking_'+count_parking+'" src="" style="display:none; width: 100%; height: auto; border: 1px solid black;">';
                parking += '</td>';
                parking += '<td style="border: 1px solid black; width: 1%;"><button style="font-size: 10px;" onclick="removeParking('+count_parking+')" class="btn btn-danger btn-xs"><i class="fa fa-minus"></i></button></td>';
            parking += '</tr>';

            $('#bodyParking').append(parking);

            count_parking++;
        }

        function removeParking(id) {
            $('#tr_parking_'+id).remove();
        }
        
        $(document).ready(function() {
            $('#toggle-sidebar').hide();

            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('.select2').select2({
                allowClear:true
            });
        });

        function submitDriver() {
            $('#loading').show();

            var etoll = [];
            var parking = [];

            var file_etoll = [];
            var file_parking = [];

            var formData = new FormData();

            for(var i = 0; i < count_etoll;i++){
                if($('#etoll_'+i).val() != '' && $('#etoll_'+i).val() != undefined && $('#etoll_'+i).val() != 'undefined'){
                    etoll.push(parseInt($('#etoll_'+i).val()));
                    if ($('#file_etoll_'+i).prop('files')[0] == undefined) {
                        $('#loading').hide();
                        openErrorGritter('Error!','Isikan Foto Bukti E-Toll');
                        return false;
                    }
                    // formData.append('file_etoll[]',$('#file_etoll_'+i).prop('files')[0]);
                    var file_etoll = $('#blah_etoll_'+i).attr('src');
                    formData.append('file_etoll[]', file_etoll);
                }
            }

            formData.append('etoll',etoll);

            for(var i = 0; i < count_parking;i++){
                if($('#parking_'+i).val() != '' && $('#parking_'+i).val() != undefined && $('#parking_'+i).val() != 'undefined'){
                    parking.push(parseInt($('#parking_'+i).val()));
                    if ($('#file_parking_'+i).prop('files')[0] == undefined) {
                        $('#loading').hide();
                        openErrorGritter('Error!','Isikan Foto Bukti Parkir');
                        return false;
                    }
                    // formData.append('file_parking[]',$('#file_parking_'+i).prop('files')[0]);
                    var file_parking = $('#blah_parking_'+i).attr('src');
                    formData.append('file_parking[]', file_parking);
                }
            }

            formData.append('parking',parking);
            formData.append('id',$('#id').val());
            formData.append('task_id',$('#task_id').val());

            $.ajax({
                url:"{{ url('input/additional/driver/job') }}",
                method:"POST",
                data:formData,
                dataType:'JSON',
                contentType: false,
                cache: false,
                processData: false,
                success:function(data)
                {
                    if (data.status) {
                        $('#div_driver_3').show();
                        $('#div_driver_2').hide();
                        $('#div_driver_1').hide();
                        $('#loading').hide();
                        openSuccessGritter('Success','Success Input Data (データの入力に成功しました)');
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
