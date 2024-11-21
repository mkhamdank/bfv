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
                <h1>
                    {{ $title }}
                </h1>
            </div>
        </div>
        <!-- <div class="row" style="padding-top: 20px; padding-left: 20px; padding-right: 20px;">
            <button class="btn btn-success" href="javascript:void(0)" class="btn btn-success btn-flat"
                                data-toggle="modal" data-target="#scanModal">Scan QR Code</button>
        </div> -->
        <input type="hidden" name="urgent" id="urgent" value="0">
        <div class="row" style="padding-top: 20px; padding-left: 20px; padding-right: 20px;">
            <button onclick="openModalUrgent()" class="btn btn-primary">Tugas Dadakan</button>
        </div>
        <div class="row" id="divDriver" style="padding-bottom: 20px; padding-left: 20px; padding-right: 20px; overflow-y: scroll;">
        </div>

    </section>

    <div class="modal modal-default fade" id="scanModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title text-center"><b>SCAN QR CODE HERE</b></h4>
                </div>
                <div class="modal-body">
                    <div id='scanner' class="col-xs-12">
                        <center>
                            <div id="loadingMessage">
                                🎥 Unable to access video stream
                                (please make sure you have a webcam enabled)
                            </div>
                            <video autoplay muted playsinline id="video"></video>
                            <div id="output" hidden>
                                <div id="outputMessage">No QR code detected.</div>
                            </div>
                        </center>
                    </div>
                    <input type="hidden" name="qr_code" id="qr_code">
                    <p style="visibility: hidden;">camera</p>
                    <input type="hidden" id="code">
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <button class="btn btn-danger" onclick="stopScan()" style="font-weight: bold; width: 100%; margin-top: 20px;">BATAL</button>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal modal-default fade" id="urgentJobModal" data-bs-keyboard="false" data-bs-backdrop="static">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header" style="background-color: #0d6efd; color: white;">
                    <h4 class="modal-title text-center"><b>Tugas Dadakan</b></h4>
                </div>
                <div class="modal-body">
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <label>Destinasi</label>
                        <input type="text" class="form-control" placeholder="Destinasi" id="destination">
                    </div>
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <label>Penumpang</label>
                        <input type="text" class="form-control" placeholder="Penumpang" id="passenger">
                    </div>
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <label>Jam Berangkat</label>
                        <input type="text" class="form-control" placeholder="Jam Berangkat" id="valid_from" readonly value="{{date('Y-m-d H:i:s')}}" hidden>
                        <table style="width: 80%">
                            <tr>
                                <td style="width: 2%"><input type="text" class="form-control" readonly value="{{date('H')}}" placeholder="Jam" id="valid_from_1"></td>
                                <td style="width: 1%">:</td>
                                <td style="width: 2%"><input type="text" class="form-control" readonly value="{{date('i')}}" placeholder="Menit" id="valid_from_2"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <label>Jam Kembali</label>
                        <table style="width: 80%">
                            <tr>
                                <td style="width: 2%"><input type="text" class="form-control numpad" placeholder="Jam" id="valid_to_1"></td>
                                <td style="width: 1%">:</td>
                                <td style="width: 2%"><input type="text" class="form-control numpad" placeholder="Menit" id="valid_to_2"></td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-xs-12 col-lg-6 col-mg-6">
                        <button class="btn btn-success" onclick="submitUrgentJob()" style="font-weight: bold; width: 100%; margin-top: 20px;">
                            SUBMIT
                        </button>
                        <br>
                        <br>
                        <button class="btn btn-danger" onclick="hideModalUrgent()" style="font-weight: bold; width: 100%; margin-top: 20px;">
                            BATAL
                        </button>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('scripts')
<script src="<?php echo e(url("js/jquery.numpad.js")); ?>"></script>
<script src="{{ url("js/jquery.gritter.min.js") }}"></script>
<script src="{{ url('js/jsQR.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var video;
        function stopScan() {
            $('#urgent').val(0);
            $('#scanModal').modal('hide');
        }

        function startScan(qr_code,urgent) {
            $('#qr_code').val(qr_code);
            $('#urgent').val(urgent);
            $('#scanModal').modal('show');
        }

        function videoOff() {
            video.pause();
            video.src = "";
            video.srcObject.getTracks()[0].stop();
        }

        
        // $.fn.numpad.defaults.onKeypadCreate = function(){$(this).find('.cancel').css('border-color','rgb(219,103,115)');};

        var hour;
        var minute;
        var second;
        var intervalTime;
        var intervalUpdate;
        var doing = 0;

        $(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");
            $('#side_driver_job').addClass('menu-open');
            fetchDriverJob();
            $("#urgent").val(0);
            $("#qr_code").val('');
            new_code = '';
            setInterval(fetchDriverJob,300000);
            $('.numpad').numpad({
                hidePlusMinusButton : true,
                decimalSeparator : '.'
            });
            doing = 0;
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
                $(this).find('.done').css('width','100px');

                $(this).find('.sep').css('background-color','white');
                $(this).find('.sep').css('color','black');
                $(this).find('.sep').css('border-color','#0dcaf0');
                $(this).find('.sep').css('width','50px');

                $(this).find('.cancel').css('background-color','white');
                $(this).find('.cancel').css('color','rgb(219,103,115)');
                $(this).find('.cancel').css('border-color','rgb(219,103,115)');
                $(this).find('.cancel').html('<i class="fas fa-times"></i>');
                $(this).find('.cancel').css('width','50px');

                $(this).find('.clear').css('background-color','white');
                $(this).find('.clear').css('color','black');
                $(this).find('.clear').css('border-color','black');
                $(this).find('.clear').html('<i class="fas fa-trash"></i>');
                $(this).find('.clear').css('width','50px');

                $(this).find('.del').css('background-color','white');
                $(this).find('.del').css('color','black');
                $(this).find('.del').css('border-color','black');
                $(this).find('.del').css('font-size','4vw');
                $(this).find('.del').html('<i class="fas fa-backspace"></i>');
                $(this).find('.del').css('width','50px');
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

        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
        var new_code = '';
        function fetchDriverJob() {
            $.get('{{ url("fetch/driver/job") }}',  function(result, status, xhr){
                if(result.status){
                    $('#divDriver').html('');
                    var divDriver = '';
                    new_code = '';
                    for(var i = 0; i < result.driver_job.length;i++){
                        if (result.driver_job[i].duty_status == 'on duty') {
                            if (result.driver_job[i].code.charAt(0) == 'D') {
                                divDriver += '<div class="col-md-3" style="padding: 3px; cursor: pointer;" onclick="startScan(\''+(result.driver_job[i].remark.split('_')[2]+'_'+result.driver_job[i].remark.split('_')[3])+'\',1)">';
                                new_code = result.driver_job[i].code;
                            }else{
                                divDriver += '<div class="col-md-3" style="padding: 3px; cursor: pointer;" onclick="startScan(\''+result.driver_job[i].code+'\',0)">';
                            }
                        }else{
                            if (result.driver_job[i].duty_status != 'completed') {
                                divDriver += '<div class="col-md-3" style="padding: 3px; cursor: pointer;" onclick="doingJob(\''+result.driver_job[i].code+'\',\''+result.driver_job[i].remark+'\')">';
                            }else{
                                divDriver += '<div class="col-md-3" style="padding: 3px;">';
                            }
                        }
                        if (result.driver_job[i].duty_status == null) {
                            divDriver += '<div style="margin-top: 12px; border: 1px solid black; background-color: #fff1c2; padding: 5px;">';
                        }else if (result.driver_job[i].duty_status == 'on duty') {
                            divDriver += '<div style="margin-top: 12px; border: 1px solid black; background-color: #d4ffd4; padding: 5px;">';
                        }else if (result.driver_job[i].duty_status == 'back to base') {
                            divDriver += '<div style="margin-top: 12px; border: 1px solid black; background-color: #ffd4d4; padding: 5px;">';
                        }else if (result.driver_job[i].duty_status == 'completed') {
                            divDriver += '<div style="margin-top: 12px; border: 1px solid black; background-color: #c9c9c9; padding: 5px;">';
                        }

                        divDriver += '<span style="font-weight: bold; font-size: 12px;">';
                        if (result.driver_job[i].remark.match(/regular/gi)) {
                            divDriver += 'REGULER';
                        }else{
                            divDriver += 'TUGAS BARU';
                        }
                        if (result.driver_job[i].duty_status == 'on duty') {
                                divDriver += ' (SEDANG DIKERJAKAN)';
                            }else if (result.driver_job[i].duty_status == 'back to base') {
                                divDriver += ' (KEMBALI KE TEMPAT ASAL)';
                            }else if (result.driver_job[i].duty_status == 'completed') {
                                divDriver += ' (SELESAI)';
                            }
                        divDriver += '</span><br>';

                        divDriver += '<span>Driver : '+result.driver_job[i].driver_name+'</span><br>';
                        divDriver += '<span>From : '+result.driver_job[i].froms+'</span><br>';
                        divDriver += '<span>To : '+result.driver_job[i].tos+'</span><br>';
                        divDriver += '<span>Destination : '+result.driver_job[i].destination+'</span><br>';
                        divDriver += '<span>Car : '+result.driver_job[i].code.split('_')[1]+'</span><br>';
                        if (result.driver_job[i].passenger.length > 25) {
                            divDriver += '<span>Passenger : '+result.driver_job[i].passenger.slice(0,20)+'...</span><br>';
                        }else{
                            divDriver += '<span>Passenger : '+result.driver_job[i].passenger.split(' ')[0]+'</span><br>';
                        }
                        divDriver += '</div>';
                        divDriver += '</div>';

                        if (result.driver_job[i].duty_status == 'on duty') {
                            doing = 1;
                        }
                    }

                    $('#divDriver').append(divDriver);
                }
                else{
                    audio_error.play();
                    openErrorGritter('Error', result.message);
                    $('#operator').val('');
                }
            });
        }

        $("#scanModal").on('shown.bs.modal', function() {
            showCheck('123','0','123');
        });

        $('#scanModal').on('hidden.bs.modal', function() {
            videoOff();
        });

        function doingJob(code,remark) {
            if (doing == 1) {
                openErrorGritter('Error!','Selesaikan Tugas Sebelumnya');
                return false;
            }
            var url = '{{url("index/scan/qr_code/driver")}}/'+code;
            window.location.replace(url);
        }

        function showCheck(kode,urgent) {
            $(".modal-backdrop").add();
            $('#scanner').show();

            var vdo = document.getElementById("video");
            video = vdo;
            var tickDuration = 200;
            video.style.boxSizing = "border-box";
            video.style.position = "absolute";
            video.style.left = "0px";
            video.style.top = "0px";
            video.style.width = "300px";
            video.style.zIndex = 1000;

            var loadingMessage = document.getElementById("loadingMessage");
            var outputContainer = document.getElementById("output");
            var outputMessage = document.getElementById("outputMessage");

            navigator.mediaDevices.getUserMedia({
                video: {
                    facingMode: "environment"
                }
            }).then(function(stream) {
                video.srcObject = stream;
                video.play();
                setTimeout(function() {
                    tick();
                }, tickDuration);
            });

            function tick() {
                loadingMessage.innerText = "⌛ Loading video..."

                try {

                    loadingMessage.hidden = true;
                    video.style.position = "static";

                    var canvasElement = document.createElement("canvas");
                    var canvas = canvasElement.getContext("2d");
                    canvasElement.height = video.videoHeight;
                    canvasElement.width = video.videoWidth;
                    canvas.drawImage(video, 0, 0, canvasElement.width, canvasElement.height);
                    var imageData = canvas.getImageData(0, 0, canvasElement.width, canvasElement.height);
                    var code = jsQR(imageData.data, imageData.width, imageData.height, {
                        inversionAttempts: "dontInvert"
                    });
                    if (code) {
                        outputMessage.hidden = true;
                        videoOff();
                        checkCode(code.data,urgent);

                    } else {
                        outputMessage.hidden = false;
                    }
                } catch (t) {
                    console.log("PROBLEM: " + t);
                }

                setTimeout(function() {
                    tick();
                }, tickDuration);
            }

        }

        function checkCode(codes,urgent) {
            if ($('#urgent').val() == 1 && $('#qr_code').val() == '') {
                inputUrgentJob(codes);
            }else{
                if ($('#urgent').val() == 0 && new_code == '') {
                    window.location.replace(codes);
                }else if ($('#urgent').val() == 1 && new_code == '') {
                    window.location.replace(codes);
                }else{
                    window.location.replace('{{url("index/scan/qr_code/driver")}}/'+new_code);
                }
            }
        }

        function hideModalUrgent() {
            $('#urgentJobModal').modal('hide');
            $('#urgent').val(0);
        }
        function openModalUrgent() {
            if (doing == 1) {
                openErrorGritter('Error!','Selesaikan Tugas Sebelumnya');
                return false;
            }
            $('#urgent').val(1);
            $('#urgentJobModal').modal('show');
        }

        function submitUrgentJob() {
            if ($('#destination').val() == '' || $('#passenger').val() == '' || $('#valid_to_1').val() == '' || $('#valid_to_2').val() == '') {
                openErrorGritter('Error!','Isi Semua Data');
                return false;
            }
            $('#urgentJobModal').modal('hide');
            startScan('','1');
        }

        function inputUrgentJob(code) {
            var data = {
                destination: $('#destination').val(),
                passenger: $('#passenger').val(),
                valid_to_1: $('#valid_to_1').val(),
                valid_to_2: $('#valid_to_2').val(),
                valid_from: $('#valid_from').val(),
                code: code.split('/')[9],
            }

            $.post('{{ url("input/driver/job/urgent") }}', data, function(result, status, xhr){
                if(result.status){
                    clearModalUrgent();
                    $('#urgent').val(0);
                    $('#qr_code').val('');
                    window.location.replace('{{url("index/scan/qr_code/driver")}}/'+result.codes);
                }else{
                    stopScan();
                    clearModalUrgent();
                    $('#qr_code').val('');
                    $('#urgent').val(0);
                    audio_error.play();
                    openErrorGritter('Error', result.message);
                }
            });
        }

        function clearModalUrgent() {
            $('#destination').val('');
            $('#passenger').val('');
            $('#valid_to_1').val('');
            $('#valid_to_2').val('');
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
