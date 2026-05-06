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
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgba(0,0,0,0.7); z-index: 30001; display: none; display: flex; align-items: center; justify-content: center;display: none; ">
            <div style="text-align: center;">
            <span style="font-size: 48px; color: white;"><i class="fa fa-spinner fa-spin"></i></span>
            <p style="color: white; margin-top: 15px; font-size: 16px;">Loading...</p>
            </div>
        </div>

        <div style="padding: 20px; max-width: 600px; margin: 0 auto;">
            <div style="text-align: center; margin-bottom: 30px;">
            <h1 style="font-size: 24px; font-weight: 600; color: #333; margin: 0;">
                {{ $title }}
            </h1>
            </div>

            @if($status == 'error')
            <div style="background-color: #fee; border-left: 4px solid #c33; padding: 16px; border-radius: 4px; margin-bottom: 20px;">
                <p style="font-size: 16px; font-weight: 600; color: #c33; margin: 0 0 8px 0;">Error!</p>
                <span style="font-size: 14px; color: #666;">{{$message}}</span>
            </div>
            @endif

            @if($status == 'success')
            <input type="hidden" id="id" value="{{$driver_task->id}}">
            <input type="hidden" id="task_id" value="{{$driver_task->task_id}}">

            <!-- Section 1: Driver Info -->
            <div id="div_driver_1" style="margin-bottom: 30px;">
                <div style="margin-bottom: 16px;">
                <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Driver</label>
                <input type="text" id="driver" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 14px; background-color: #f9f9f9;" placeholder="Driver" readonly value="{{$driver_task->driver_name}}">
                </div>
                <div>
                <label style="display: block; font-size: 13px; font-weight: 600; color: #666; margin-bottom: 8px; text-transform: uppercase; letter-spacing: 0.5px;">Tanggal</label>
                <input type="text" id="date" class="form-control" style="width: 100%; padding: 12px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 14px; background-color: #f9f9f9;" placeholder="Date" readonly value="{{date('d-m-Y',strtotime($driver_task->date_from))}}">
                </div>
            </div>

            <!-- Section 2: Form Input -->
            <div id="div_driver_2">
                <!-- E-Toll Table -->
                <div style="margin-bottom: 30px; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">
                <div style="background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); color: white; padding: 16px; font-weight: 600; font-size: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <span>💳 E-Toll</span>
                    <button type="button" onclick="addEtoll()" style="background: rgba(255,255,255,0.2); border: 1px solid white; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: 600;">
                    <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
                <div style="padding: 16px; background-color: #fafafa;">
                    <table id="bodyEtoll" style="width: 100%; border-collapse: collapse;">
                    <tr id="tr_etoll_0">
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="text" name="from_0" id="from_0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Dari">
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="text" name="to_0" id="to_0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Ke">
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="text" name="etoll_0" id="etoll_0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Biaya" inputmode="numeric" pattern="[0-9]*">
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="file" name="file_etoll_0" id="file_etoll_0" class="form-control" style="width: 100%; padding: 8px; border: 1px dashed #667eea; border-radius: 4px; font-size: 12px;" placeholder="Pilih file" onchange="readURLEtoll(this,0);">
                        <img id="blah_etoll_0" src="" style="display: none; width: 100%; margin-top: 10px; border-radius: 4px; border: 1px solid #e0e0e0;" />
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0; text-align: center;"></td>
                    </tr>
                    </table>
                </div>
                </div>

                <!-- Parking Table -->
                <div style="margin-bottom: 30px; border-radius: 8px; overflow: hidden; border: 1px solid #e0e0e0;">
                <div style="background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%); color: white; padding: 16px; font-weight: 600; font-size: 15px; display: flex; justify-content: space-between; align-items: center;">
                    <span>🅿️ Parkir</span>
                    <button type="button" onclick="addParking()" style="background: rgba(255,255,255,0.2); border: 1px solid white; color: white; padding: 6px 12px; border-radius: 4px; font-size: 12px; cursor: pointer; font-weight: 600;">
                    <i class="fa fa-plus"></i> Tambah
                    </button>
                </div>
                <div style="padding: 16px; background-color: #fafafa;">
                    <table id="bodyParking" style="width: 100%; border-collapse: collapse;">
                    <tr id="tr_parking_0">
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="text" name="parking_at_0" id="parking_at_0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Lokasi">
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="text" name="parking_0" id="parking_0" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Biaya" inputmode="numeric" pattern="[0-9]*">
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">
                        <input type="file" name="file_parking_0" id="file_parking_0" class="form-control" style="width: 100%; padding: 8px; border: 1px dashed #f5576c; border-radius: 4px; font-size: 12px;" placeholder="Pilih file" onchange="readURLParking(this,0);">
                        <img id="blah_parking_0" src="" style="display: none; width: 100%; margin-top: 10px; border-radius: 4px; border: 1px solid #e0e0e0;" />
                        </td>
                        <td style="padding: 4px; border-bottom: 1px solid #e0e0e0; text-align: center;"></td>
                    </tr>
                    </table>
                </div>
                </div>

                <!-- Submit Button -->
                <button class="btn btn-success" onclick="submitDriver()" style="width: 100%; padding: 16px; background: linear-gradient(135deg, #667eea 0%, #764ba2 100%); border: none; color: white; font-weight: 600; font-size: 16px; border-radius: 6px; cursor: pointer; transition: transform 0.2s; margin-bottom: 20px;">
                <i class="fa fa-check"></i> Submit
                </button>
            </div>

            <!-- Section 3: Success -->
            <div class="col-xs-12" id="div_driver_3" style="text-align: center; display: none; padding: 40px 20px; background: linear-gradient(135deg, #d4fc79 0%, #96e6a1 100%); border-radius: 8px;">
                <p style="font-size: 48px; margin: 0; color: #2d5016;">✓</p>
                <p style="font-size: 20px; font-weight: 600; color: #2d5016; margin: 12px 0 8px 0;">Sukses!</p>
                <span style="font-size: 14px; color: #2d5016;">Data berhasil dikirim<br>データの入力に成功しました</span>
            </div>
            @endif
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
            var etollBody = '';

            etollBody += '<tr id="tr_etoll_'+count_etoll+'">';
            etollBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;"><input type="text" name="from_'+count_etoll+'" id="from_'+count_etoll+'" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Dari" value=""></td>';
            etollBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;"><input type="text" name="to_'+count_etoll+'" id="to_'+count_etoll+'" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Ke" value=""></td>';
            etollBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;"><input type="text" name="etoll_'+count_etoll+'" id="etoll_'+count_etoll+'" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Biaya" value="" inputmode="numeric" pattern="[0-9]*"></td>';
            etollBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">';
            etollBody += '<input type="file" name="file_etoll_'+count_etoll+'" id="file_etoll_'+count_etoll+'" class="form-control" style="width: 100%; padding: 8px; border: 1px dashed #667eea; border-radius: 4px; font-size: 12px;" placeholder="Pilih file" onchange="readURLEtoll(this,'+count_etoll+');">';
            etollBody += '<img id="blah_etoll_'+count_etoll+'" src="" style="display: none; width: 100%; margin-top: 10px; border-radius: 4px; border: 1px solid #e0e0e0;">';
            etollBody += '</td>';
            etollBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0; text-align: center;"><button style="font-size: 12px; padding: 6px 10px;" onclick="removeEtoll('+count_etoll+')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>';
            etollBody += '</tr>';

            $('#bodyEtoll').append(etollBody);

            count_etoll++;
        }

        function removeEtoll(id) {
            $('#tr_etoll_'+id).remove();
        }

        function addParking() {
            var parkingBody = '';

            parkingBody += '<tr id="tr_parking_'+count_parking+'">';
            parkingBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;"><input type="text" name="parking_at_'+count_parking+'" id="parking_at_'+count_parking+'" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Lokasi" value=""></td>';
            parkingBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;"><input type="text" name="parking_'+count_parking+'" id="parking_'+count_parking+'" class="form-control" style="width: 100%; padding: 10px; border: 1px solid #e0e0e0; border-radius: 4px; font-size: 13px;" placeholder="Biaya" value="" inputmode="numeric" pattern="[0-9]*"></td>';
            parkingBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0;">';
            parkingBody += '<input type="file" name="file_parking_'+count_parking+'" id="file_parking_'+count_parking+'" class="form-control" style="width: 100%; padding: 8px; border: 1px dashed #f5576c; border-radius: 4px; font-size: 12px;" placeholder="Pilih file" onchange="readURLParking(this,'+count_parking+');">';
            parkingBody += '<img id="blah_parking_'+count_parking+'" src="" style="display: none; width: 100%; margin-top: 10px; border-radius: 4px; border: 1px solid #e0e0e0;">';
            parkingBody += '</td>';
            parkingBody += '<td style="padding: 4px; border-bottom: 1px solid #e0e0e0; text-align: center;"><button style="font-size: 12px; padding: 6px 10px;" onclick="removeParking('+count_parking+')" class="btn btn-danger btn-xs"><i class="fa fa-trash"></i></button></td>';
            parkingBody += '</tr>';

            $('#bodyParking').append(parkingBody);

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

        var etoll = [];
        var etoll_from = [];
        var etoll_to = [];
        var parking = [];
        var parking_at = [];

        var file_etoll = [];
        var file_parking = [];

        function submitDriver() {
            $('#loading').show();

            etoll = [];
            etoll_from = [];
            etoll_to = [];
            parking = [];
            parking_at = [];

            file_etoll = [];
            file_parking = [];

            for(var i = 0; i < count_etoll;i++){
                if($('#etoll_'+i).val() != '' && $('#etoll_'+i).val() != undefined && $('#etoll_'+i).val() != 'undefined'){
                    var etoll_val = parseInt($('#etoll_'+i).val());
                    
                    // Validasi: minimal 3 digit (nominal harga minimum 100)
                    if(isNaN(etoll_val) || etoll_val < 100){
                        $('#loading').hide();
                        openErrorGritter('Error!','E-Toll harus berupa nominal harga minimal 3 digit');
                        return false;
                    }
                    
                    etoll.push(etoll_val);
                    etoll_from.push($('#from_'+i).val());
                    etoll_to.push($('#to_'+i).val());
                    if ($('#file_etoll_'+i).prop('files')[0] == undefined) {
                        $('#loading').hide();
                        openErrorGritter('Error!','Isikan Foto Bukti E-Toll');
                        return false;
                    }
                    var file_etolls = $('#blah_etoll_'+i).attr('src');
                    file_etoll.push(file_etolls);
                }
            }

            for(var i = 0; i < count_parking;i++){
                if($('#parking_'+i).val() != '' && $('#parking_'+i).val() != undefined && $('#parking_'+i).val() != 'undefined'){
                    var parking_val = parseInt($('#parking_'+i).val());
                    
                    // Validasi: minimal 3 digit (nominal harga minimum 100)
                    if(isNaN(parking_val) || parking_val < 100){
                        $('#loading').hide();
                        openErrorGritter('Error!','Parkir harus berupa nominal harga minimal 3 digit');
                        return false;
                    }
                    
                    parking.push(parking_val);
                    parking_at.push($('#parking_at_'+i).val());
                    if ($('#file_parking_'+i).prop('files')[0] == undefined) {
                        $('#loading').hide();
                        openErrorGritter('Error!','Isikan Foto Bukti Parkir');
                        return false;
                    }
                    var file_parkings = $('#blah_parking_'+i).attr('src');
                    file_parking.push(file_parkings);
                }
            }

            if(etoll.length == 0 && parking.length == 0){
                var data = {
                    id : $('#id').val(),
                    task_id : $('#task_id').val(),
                }
                $.post('{{ url("input/additional/driver/job") }}',data, function(result, status, xhr) {
                    if (result.status) {
                        $('#div_driver_3').show();
                        $('#div_driver_2').hide();
                        $('#div_driver_1').hide();
                        $('#loading').hide();
                        openSuccessGritter('Success','Success Input Data (データの入力に成功しました)');
                    }else{
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                    }
                });
            }else{
                if(etoll.length > 0){
                    saveEtoll();
                }
                if(parking.length > 0){
                    saveParking();
                }
            }
        }

        var all_sudah = 0;

        function saveEtoll(){
            for(var i = 0; i < etoll.length;i++){
                var data = {
                    id : $('#id').val(),
                    task_id : $('#task_id').val(),
                    etoll : etoll[i],
                    etoll_from : etoll_from[i],
                    etoll_to : etoll_to[i],
                    file_etoll : file_etoll[i],
                    index : i,
                }
                $.post('{{ url("input/additional/driver/job/etoll") }}',data, function(result, status, xhr) {
                    if (result.status) {
                        all_sudah++;
                        if(all_sudah == (etoll.length + parking.length)){
                            $('#div_driver_3').show();
                            $('#div_driver_2').hide();
                            $('#div_driver_1').hide();
                            $('#loading').hide();
                            openSuccessGritter('Success','Success Input Data (データの入力に成功しました)');
                        }
                    }else{
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                        return false;
                    }
                });
            }
        }

        function saveParking(){
            for(var i = 0; i < parking.length;i++){
                var data = {
                    id : $('#id').val(),
                    task_id : $('#task_id').val(),
                    parking : parking[i],
                    parking_at : parking_at[i],
                    file_parking : file_parking[i],
                    index : i,
                }
                $.post('{{ url("input/additional/driver/job/parking") }}',data, function(result, status, xhr) {
                    if (result.status) {
                        all_sudah++;    
                        if(all_sudah == (etoll.length + parking.length)){
                            $('#div_driver_3').show();
                            $('#div_driver_2').hide();
                            $('#div_driver_1').hide();
                            $('#loading').hide();
                            openSuccessGritter('Success','Success Input Data (データの入力に成功しました)');
                        }
                    }else{
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                        return false;
                    }
                });
            }
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
