@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
<link href="<?php echo e(url("css/jquery.numpad.css")); ?>" rel="stylesheet">
<link href="{{ url("css/jquery.gritter.css") }}" rel="stylesheet">
<link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.3/dist/leaflet.css" integrity="sha256-kLaT2GOSpHechhsozzB+flnD+zUyjE2LlfWPgU04xyI=" crossorigin=""/>

<style>
    body { background: #f0f2f7 !important; }

    /* Reuse style dari index_list */
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body button {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    .select2-container .select2-selection--single {
        height: 40px !important;
        border-radius: 8px !important;
    }

    /* Loading */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.4); backdrop-filter: blur(5px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 48px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }

    /* Page Header */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px; color: white;
    }

    .form-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); 
        border: 1px solid rgba(0,0,0,.05);
        padding: 32px; margin-bottom: 24px;
    }

    .form-label {
        font-size: 13px; font-weight: 700; color: #4a5568;
        letter-spacing: .06em; text-transform: uppercase; margin-bottom: 8px;
    }

    .form-control-modern {
        width: 100%;
        display: block;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        padding: 12px 16px !important;
        font-size: 15px !important;
        background: #fafbff !important;
    }
    .select2-container {
        width: 100% !important;
    }
    .select2-container .select2-selection--single {
        width: 100% !important;
    }

    .form-control-modern:focus {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
        background: #fff !important;
    }

    .photo-upload {
        border: 2px dashed #c4bfef;
        border-radius: 12px;
        padding: 24px;
        text-align: center;
        transition: all 0.2s;
        cursor: pointer;
    }
    .photo-upload:hover {
        border-color: #605ca8;
        background: #f0eef9;
    }

    .btn-submit {
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: white;
        font-size: 18px;
        font-weight: 700;
        padding: 14px 32px;
        border-radius: 12px;
        width: 100%;
        margin-top: 20px;
        box-shadow: 0 4px 12px rgba(21,128,61,.3);
    }
    .btn-submit:hover {
        transform: translateY(-2px);
        opacity: 0.95;
    }

    .numpad-input {
        text-align: center;
        font-size: 24px;
        font-weight: 600;
        background: white;
    }
    .select2-container .select2-selection--single .select2-selection__rendered {
        padding-left: 1px !important;
        padding-right: 0px !important;
        width: 210px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow {
        height: 38px !important;
        right: 4px !important;
        width: 30px !important;
    }
</style>
@stop

@section('content')
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memproses...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    <!-- Page Header -->
    <div class="page-header-modern">
        <div>
            <div class="badge-tag" style="background: rgba(255,255,255,.2); border: 1px solid rgba(255,255,255,.3); color: #fff; display:inline-flex; align-items:center; gap:6px; padding:6px 14px; border-radius:20px; font-size:11px; font-weight:700;">
                <i class="fas fa-car"></i> DRIVER ATTENDANCE
            </div>
            <h1 style="color:#fff; font-size:28px; font-weight:700; margin:12px 0 4px 0;">Absensi Driver</h1>
            <p style="color:rgba(255,255,255,.75); margin:0;">運転手の不在</p>
        </div>
    </div>

    <div class="form-card">
        <input type="hidden" id="employee_id" value="{{Auth::user()->username}}">
        <input type="hidden" id="name" value="{{Auth::user()->name}}">
        <input type="hidden" id="department" value="General Affairs Department">
        <input type="hidden" id="latitude" name="latitude">
        <input type="hidden" id="longitude" name="longitude">

        <div class="row">
            <div class="col-md-12">
                <div class="form-label">Karyawan</div>
                <input type="text" class="form-control form-control-modern" readonly 
                       value="{{Auth::user()->username}} - {{Auth::user()->name}}">
            </div>
        </div>

        <div class="row mt-4">
            <div class="col-md-4">
                <div class="form-label">Kendaraan <span style="color:red">*</span></div>
                <select class="form-control form-control-modern select2" id="vehicle" onchange="changeVehicle(this.value)" style="width:100%;">
                    <option value="-">Pilih Kendaraan</option>
                    @foreach($vehicle as $v)
                        <option value="{{$v->plat_no}}_{{$v->car}}">{{$v->plat_no}} - {{$v->car}}</option>
                    @endforeach
                </select>
            </div>
            <div class="col-md-4">
                <div class="form-label">Odometer (KM) <span style="color:red">*</span></div>
                <input type="text" id="odometer" class="form-control form-control-modern numpad" placeholder="0">
            </div>
            <div class="col-md-4">
                <div class="form-label">Fuel (Liter) <span style="color:red">*</span></div>
                <input type="text" id="fuel" class="form-control form-control-modern numpad" placeholder="0">
            </div>
        </div>

        <!-- Photo Uploads -->
        <div class="row mt-4">
            <div class="col-md-4">
                <div class="form-label">Foto Selfie <span style="color:red">*</span></div>
                <div class="photo-upload" onclick="buttonImage(this)">
                    <i class="fas fa-camera fa-2x mb-2" style="color:#605ca8"></i>
                    <p style="margin:0; font-weight:600; color:#4a5568;">Selfie</p>
                    <input type="file" id="file_foto" style="display:none" onclick="event.stopPropagation();" onchange="readURL(this)">
                    <img id="blahsim" src="" style="display:none; width:100%; margin-top:8px; border-radius:8px;" alt="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-label">Foto Odometer <span style="color:red">*</span></div>
                <div class="photo-upload" onclick="buttonImageOdo(this)">
                    <i class="fas fa-tachometer-alt fa-2x mb-2" style="color:#605ca8"></i>
                    <p style="margin:0; font-weight:600; color:#4a5568;">Odometer</p>
                    <input type="file" id="file_foto_odometer" style="display:none" onclick="event.stopPropagation();" onchange="readURL2(this)">
                    <img id="blahOdo" src="" style="display:none; width:100%; margin-top:8px; border-radius:8px;" alt="">
                </div>
            </div>

            <div class="col-md-4">
                <div class="form-label">Foto Lokasi <span style="color:red">*</span></div>
                <div class="photo-upload" onclick="buttonImageLoc(this)">
                    <i class="fas fa-map-marker-alt fa-2x mb-2" style="color:#605ca8"></i>
                    <p style="margin:0; font-weight:600; color:#4a5568;">Lokasi</p>
                    <input type="file" id="file_location" style="display:none" onclick="event.stopPropagation();" onchange="readURL3(this)">
                    <img id="blahLoc" src="" style="display:none; width:100%; margin-top:8px; border-radius:8px;" alt="">
                </div>
            </div>
        </div>

        <button class="btn btn-submit" onclick="save()">
            <i class="fas fa-save"></i> SUBMIT ABSENSI
        </button>
    </div>

</div>
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

        var isMobile = window.innerWidth <= 767;
        var btnW  = isMobile ? '62px'  : '76px';
        var btnH  = isMobile ? '54px'  : '66px';
        var btnFS = isMobile ? '20px'  : '24px';
        var dispH = isMobile ? '70px'  : '86px';
        var dispFS= isMobile ? '30px'  : '38px';
        var padW  = isMobile ? '220px' : '270px';

        var numBtnStyle = [
            'background:#fff',
            'color:#2d3748',
            'border:1.5px solid #e2e8f0',
            'border-radius:12px',
            'font-size:'+btnFS,
            'font-weight:700',
            'width:'+btnW,
            'height:'+btnH,
            'line-height:1',
            'box-shadow:0 2px 6px rgba(0,0,0,.06)',
            'transition:background .15s',
            'cursor:pointer',
            'font-family:Plus Jakarta Sans,sans-serif'
        ].join(';');

        var funcBtnStyle = [
            'border-radius:12px',
            'font-size:13px',
            'font-weight:700',
            'height:'+btnH,
            'line-height:1',
            'width:'+btnW,
            'cursor:pointer',
            'transition:all .15s',
            'font-family:Plus Jakarta Sans,sans-serif'
        ].join(';');

        $.fn.numpad.defaults.gridTpl = [
            '<table style="',
                'background:#f7f8fc;',
                'border-radius:0 0 20px 20px;',
                'border-collapse:separate;',
                'border-spacing:6px;',
                'padding:10px;',
                'width:'+padW+';',
                'position:fixed;',
                'top:50%;',
                'left:50%;',
                'transform:translate(-50%,-50%);',
                'z-index:30100;',
                'box-shadow:0 24px 64px rgba(45,43,78,.4);',
                'border-radius:20px;',
                'overflow:hidden;',
            '"></table>'
        ].join('');

        $.fn.numpad.defaults.backgroundTpl = '<div class="modal-backdrop in" style="opacity:.55;background:#2d2b4e;z-index:30099;"></div>';

        $.fn.numpad.defaults.displayTpl = [
            '<input type="text" style="',
                'background:linear-gradient(135deg,#2d2b4e,#605ca8);',
                'color:#fff;',
                'font-size:'+dispFS+';',
                'font-weight:800;',
                'text-align:center;',
                'border:none;',
                'border-radius:0;',
                'height:'+dispH+';',
                'letter-spacing:3px;',
                'padding:0 20px;',
                'box-shadow:none;',
                'width:100%;',
                'font-family:Plus Jakarta Sans,sans-serif;',
            '"/>'
        ].join('');

        $.fn.numpad.defaults.buttonNumberTpl  = '<button type="button" style="'+numBtnStyle+'"></button>';
        $.fn.numpad.defaults.buttonFunctionTpl= '<button type="button" style="'+funcBtnStyle+'"></button>';

        $.fn.numpad.defaults.onKeypadCreate = function() {
            var $table = $(this);
            if ($table.find('.numpad-title').length === 0) {
                $table.prepend('<tr class="numpad-title"><td colspan="3" style="padding:0; border:none;"></td></tr>');
            }

            $table.find('.done').css({
                'background': 'linear-gradient(135deg,#15803d,#16a34a)',
                'color':       '#fff',
                'border':      'none',
                'width':       btnW,
                'height':      btnH,
                'border-radius': '12px',
                'font-size':   '13px',
                'font-weight': '700',
                'font-family': 'Plus Jakarta Sans,sans-serif'
            });

            $table.find('.cancel').css({
                'background':    '#fee2e2',
                'color':         '#dc2626',
                'border':        '1.5px solid #fecaca',
                'width':         btnW,
                'height':        btnH,
                'border-radius': '12px',
                'font-size':     '13px',
                'font-weight':   '700',
                'font-family':   'Plus Jakarta Sans,sans-serif'
            });

            $table.find('.clear').css({
                'background':    '#fef3c7',
                'color':         '#b45309',
                'border':        '1.5px solid #fde68a',
                'width':         btnW,
                'height':        btnH,
                'border-radius': '12px',
                'font-size':     '13px',
                'font-weight':   '700',
                'font-family':   'Plus Jakarta Sans,sans-serif'
            });

            $table.find('.del').css({
                'background':    '#f1f5f9',
                'color':         '#475569',
                'border':        '1.5px solid #e2e8f0',
                'width':         btnW,
                'height':        btnH,
                'border-radius': '12px',
                'font-size':     '18px',
                'font-family':   'Plus Jakarta Sans,sans-serif'
            });

            $table.find('.sep').css({
                'background':    '#ebf2ff',
                'color':         '#2d6bc4',
                'border':        '1.5px solid #c3d9f8',
                'width':         btnW,
                'height':        btnH,
                'border-radius': '12px',
                'font-size':     btnFS,
                'font-weight':   '700',
                'font-family':   'Plus Jakarta Sans,sans-serif'
            });

            $table.find('button').not('.done,.cancel,.clear,.del,.sep').on('mouseenter', function(){
                $(this).css({'background':'#ede9fe','border-color':'#605ca8','color':'#4a4690'});
            }).on('mouseleave', function(){
                $(this).css({'background':'#fff','border-color':'#e2e8f0','color':'#2d3748'});
            });
        };

        
        $(document).ready(function() {
            $('#vehicle').select2();
            $('.numpad').numpad({ hidePlusMinusButton: true, decimalSeparator: '.' });
            getLocation();
            $('body').addClass("sidebar-collapse");
            $('#side_driver').addClass('menu-open');
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
