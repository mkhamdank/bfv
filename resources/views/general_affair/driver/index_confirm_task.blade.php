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
                    {{ $title }}<br><small style="font-weight: bold; color: #605ca8;">{{$title_jp}}</small>
                </h1>
            </div>
        </div>
        <div class="row">
            <?php if($status == 'error'){ ?>
            <div class="col-xs-12" style="text-align: center; padding-left: 15px; padding-right: 15px;">
                {{-- <p style="font-size: 20px; font-weight: bold; color: green;">Success!</p> --}}
                <br>
                <span style="font-size: 18px; color: #000;">{{$message}}</span>
                <br>
                <span style="font-size: 18px; color: #605ca8;">{{$message_jp}}</span>
            </div>
            <?php } ?>
            <?php if($status == 'success'){ ?>
                <input type="hidden" id="status_daily" value="">
                <input type="hidden" id="id" value="{{$id_daily}}">
                <input type="hidden" id="plat_no" value="{{$plat_no}}">
                <input type="hidden" id="id_fix" value="">
                <input type="hidden" id="task_id" value="{{$id}}">
                <input type="hidden" id="task_id_fix" value="">
                <input type="hidden" id="japanese_id" value="{{$japanese_id}}">
                <input type="hidden" id="driver_list_id" value="{{$driver_list_id}}">
                <input type="hidden" id="real_otp" value="{{ join(',',$driver_task_id_with_otp) }}">
                <table id="div_driver_0" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Masukkan PIN <small style="color: #605ca8;">(PINを入力してください)</small></label>
                            <input type="text" name="otp" id="otp" class="form-control" style="width: 100%; text-align: center;" placeholder="PINを入力してください" inputmode="numeric" pattern="[0-9]*">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 10px;">
                            <button class="btn btn-success btn-sm" id="btn_submit_otp" style="width: 90%; font-weight: bold; font-size: 20px;" onclick="submitOtp();">
                                確認 Konfirmasi
                            </button>
                        </td>
                    </tr>
                </table>
                <table id="div_driver_1" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Driver <small style="color: #605ca8;">(運転手の名前)</small></label>
                            <input type="text" name="driver" id="driver" class="form-control" style="width: 100%; text-align: center;" placeholder="Driver" readonly="">
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-left: 20px; padding-right: 20px;">
                            <label>Tanggal <small style="color: #605ca8;">(日付 )</small></label>
                            <input type="text" name="date" id="date" class="form-control" style="width: 100%; text-align: center;" placeholder="Destination" readonly="">
                        </td>
                    </tr>
                </table>
                <table id="div_driver_2" style="text-align: center; width: 100%; padding-left: 10px;padding-right: 10px;">
                    <tr>
                        <td style="display: inline-block; padding-left: 20px; padding-right: 20px;">
                            <table style="width: 100%; padding-left: 20px; padding-right: 20px;">
                                <tr>
                                    <td colspan="2">
                                        <label>Mulai <small style="color: #605ca8;">(利用開始時刻)</small><span style="color: red;">*</span><br><small style="color: #605ca8;">24時間制</small></label>
                                    </td>
                                    <td>
                                    </td>
                                    <td colspan="2">
                                        <label>Selesai <small style="color: #605ca8;">(利用終了時刻)</small><span style="color: red;">*</span><br><small style="color: #605ca8;">24時間制</small></label>
                                    </td>
                                </tr>
                                <tr>
                                    <td><input type="number" inputmode="numeric" pattern="[0-9]*" name="hour_start" id="hour_start" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="例: 23時"></td>
                                    <td><input type="number" inputmode="numeric" pattern="[0-9]*" name="minute_start" id="minute_start" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="例: 59分"></td>
                                    <td style="padding-left: 5px; padding-right: 5px; font-weight: bold;">
                                        -
                                    </td>
                                    <td><input type="number" inputmode="numeric" pattern="[0-9]*" name="hour_end" id="hour_end" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="例: 23時" value="" onkeyup="jumpToEnd()"></td>
                                    <td><input type="number" inputmode="numeric" pattern="[0-9]*" name="minute_end" id="minute_end" class="form-control" style="width: 100%; text-align: center; background-color: white;" placeholder="例: 59分" value=""></td>
                                </tr>
                            </table>
                        </td>
                    </tr>
                    <tr>
                        <td style="padding-top: 10px;">
                            <button class="btn btn-success btn-sm" id="btn_submit" style="width: 90%; font-weight: bold; font-size: 20px;" onclick="submitDriver();">
                                確認 Konfirmasi
                            </button>
                        </td>
                    </tr>
                </table>
                <div class="col-xs-12" id="div_driver_3" style="text-align: center; padding-left: 15px; padding-right: 15px; display: none;">
                    <p style="font-size: 20px; font-weight: bold; color: green;">Success!</p>
                    <span style="font-size: 18px; color: black;">Success Input Data</span>
                    <br>
                    <span style="font-size: 18px; color: #605ca8;">データの入力に成功しました</span>
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

        function jumpToEnd() {
            if ($('#hour_end').val().length == 2) {
                $('#minute_end').focus();
            }
        }
        
        $('#otp').on('keypress', function(e) {
            if (e.which === 13) {
                $('#btn_submit_otp').click();
            }
        });
        
        $(document).ready(function() {
            $('#toggle-sidebar').hide();

            $('body').toggleClass("sidebar-collapse");
            $('#side_vfi').addClass('menu-open');

            $('#div_driver_1').hide();
            $('#div_driver_2').hide();
            $('#div_driver_3').hide();
            $('#div_driver_0').show();

            $('#otp').val('');
            $('#otp').focus();

            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });
            $('.select2').select2({
                allowClear:true
            });
        });

        var driver_task = <?php echo json_encode($driver_task); ?>;

        function submitDriver() {
            $('#loading').show();
            if ($('#hour_start').val() == '' || $('#minute_start').val() == '' || $('#hour_end').val() == '' || $('#minute_end').val() == '') {
                $('#loading').hide();
                openErrorGritter('Error!', '赤い印の部分にご記入ください。Isikan bintang merah.');
                audio_error.play();
                return false;
            }

            if($('#status_daily').val() == 'daily'){

                var formData = new FormData();
                formData.append('hour_start',$('#hour_start').val());
                formData.append('minute_start',$('#minute_start').val());
                formData.append('minute_end',$('#minute_end').val());
                formData.append('hour_end',$('#hour_end').val());
                formData.append('date',$('#date').val());
                formData.append('id',$('#id').val());
                formData.append('plat_no',$('#plat_no').val());
                formData.append('driver_list_id',$('#driver_list_id').val());
                formData.append('japanese_id',$('#japanese_id').val());

                $.ajax({
                    url:"{{ url('input/confirmation/driver/daily_job') }}",
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
            }else{
                var formData = new FormData();
                formData.append('hour_start',$('#hour_start').val());
                formData.append('hour_end',$('#hour_end').val());
                formData.append('minute_start',$('#minute_start').val());
                formData.append('minute_end',$('#minute_end').val());
                formData.append('date',$('#date').val());
                formData.append('id',$('#id_fix').val());
                formData.append('task_id',$('#task_id_fix').val());

                $.ajax({
                    url:"{{ url('input/confirmation/driver/job') }}",
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
        }

        function submitOtp() {
            $('#loading').show();
            var real_otp = $('#real_otp').val();
            var otp = $('#otp').val();
            if (otp == '') {
                $('#loading').hide();
                openErrorGritter('Error!', '(PINは空にできません) PIN Harus Diisi!');
                $('#otp').val('');
                $('#otp').focus();
                audio_error.play();
                return false;
            }
            var data = real_otp.split(',');
            var ada = false;
            var id_real = null;
            for(var i = 0; i < data.length;i++){
                if(data[i].split('_')[1] == otp){
                    ada = true;
                    id_real = data[i].split('_')[0];
                }
            }
            if (!ada) {
                $('#loading').hide();
                openErrorGritter('Error!', 'PINが間違っています。PIN Salah!');
                $('#otp').val('');
                $('#otp').focus();
                audio_error.play();
                return false;
            }else{
                if(id_real == 'daily'){
                    $('#div_driver_0').hide();
                    $('#div_driver_1').show();
                    $('#div_driver_2').show();
                    $('#div_driver_3').hide();
                    $('#status_daily').val('daily');
                    $('#hour_start').val(getActualHourFromTime('{{ $timestamp_attendance }}'));
                    $('#minute_start').val(getActualMinuteFromTime('{{ $timestamp_attendance }}'));
                    $('#hour_end').val('');
                    $('#minute_end').val('');
                    $('#hour_end').val(getActualHour());
                    $('#minute_end').val(getActualMinute());
                    $('#driver').val('{{ $driver_name }}');
                    $('#date').val('{{date("Y-m-d")}}');
                    // $('#hour_end').focus();
                    $('#loading').hide();
                }else{
                    $('#status_daily').val('not_daily');
                    $('#id_fix').val(id_real);
                    for (var i = 0; i < driver_task.length; i++) {
                        if (driver_task[i].id == id_real) {
                            $('#driver').val(driver_task[i].driver_name);
                            var date = new Date(driver_task[i].date_from);
                            var month = ("0" + (date.getMonth() + 1)).slice(-2);
                            var day = ("0" + date.getDate()).slice(-2);
                            $('#date').val(date.getFullYear() + '-' + month + '-' + day);
                            $('#hour_start').val(driver_task[i].date_from.split(' ')[1].split(':')[0]);
                            $('#minute_start').val(driver_task[i].date_from.split(' ')[1].split(':')[1]);
                            $("#task_id_fix").val(driver_task[i].task_id);
                            break;
                        }
                    }
                    $('#div_driver_0').hide();
                    $('#div_driver_1').show();
                    $('#div_driver_2').show();
                    $('#div_driver_3').hide();
                    $('#hour_end').val('');
                    $('#minute_end').val('');
                    $('#hour_end').val(getActualHour());
                    $('#minute_end').val(getActualMinute());
                    // $('#hour_end').focus();
                    $('#loading').hide();
                }
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

        function getActualHour() {
            var d = new Date();
            var h = addZero(d.getHours());
            return h;
        }

        function getActualHourFromTime(dates) {
            var d = new Date(dates);
            var h = addZero(d.getHours());
            return h;
        }

        function getActualMinute() {
            var d = new Date();
            var m = addZero(d.getMinutes());
            return m;
        }

        function getActualMinuteFromTime(dates) {
            var d = new Date(dates);
            var m = addZero(d.getMinutes());
            return m;
        }

        function addZero(i) {
            if (i < 10) {
                i = "0" + i;
            }
            return i;
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
