<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT. YMPI - HOLIDAY CONFIRMATION</title>
    <!-- <link rel="shortcut icon" type="image/x-icon" href="{{ url('logo_mirai.png') }}"> -->
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/bootstrap/css/bootstrap.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/font-awesome-4.7.0/css/font-awesome.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('fonts/Linearicons-Free-v1.0.0/icon-font.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/animate/animate.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/css-hamburgers/hamburgers.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/animsition/css/animsition.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/select2/select2.min.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('vendor/daterangepicker/daterangepicker.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/util.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/main.css') }}">
    <link rel="stylesheet" type="text/css" href="{{ url('css/jquery.gritter.css') }}">
    <link rel="stylesheet" href="{{ url('bower_components/datatables.net-bs/css/dataTables.bootstrap.min.css') }}">
    <link rel="stylesheet"
        href="{{ url('bower_components/bootstrap-datepicker/dist/css/bootstrap-datepicker.min.css') }}">
    <style type="text/css">
        @font-face {
            font-family: Raleway-SemiBold;
            src: url('../fonts/raleway/Raleway-SemiBold.ttf');
        }

        @font-face {
            font-family: Raleway-Black;
            src: url('../fonts/raleway/Raleway-Black.ttf');
        }

        /*.container-contact100 {
            background: url('ympi2.jpg') no-repeat fixed top;
        }*/

        .contact100-form-title {
            padding-top: 20px;
        }

        td {
            padding-right: 5px;
            padding-left: 5px;
            padding-top: 0px;
            padding-bottom: 0px;
            font-size: 12px;
            vertical-align: middle;
        }

        th {
            font-size: 13px;
            border: 1px solid black;
            background-color: #aee571;
            text-align: center;
            padding-right: 5px;
            padding-left: 5px;
        }

        #main-title {
            color: white;
            background-color: #605ca8;
            padding-bottom: 0;
            text-align: center;
            font-size: 2.0vw;
            font-weight: bold;
            margin-top: 20px;
            padding: 10px;
        }
        .wrap-contact100{
            background-color: #eee;
        } 
        p {
          font-family: -apple-system,BlinkMacSystemFont,"Segoe UI",Roboto,"Helvetica Neue",Arial,sans-serif;
          font-size: 20px;
          line-height: 1.7;
          color: #000 !important;
          margin: 0px;
        }
    </style>
</head>

<body>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div id="loading"
        style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
        <p style="position: absolute; color: White; top: 45%; left: 35%;">
            <span style="font-size: 20px">Loading, please wait . . . <i class="fa fa-spin fa-refresh"></i></span>
        </p>
    </div>

    <div class="container-contact100" style="align-items: start">
        <div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2" style="padding: 0 10px; padding-bottom: 3%;">
            <span class="contact100-form-title" id="main-title">
                PT. YMPI<br>HOLIDAY CONFIRMATION
            </span>

            <div id="not_filled" style="width: 100%;">
                <div class="col-xs-12 col-md-8 col-md-offset-2" id="po_field" style="margin-left: 16.67%;">
                    <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                </div>

                <div class="col-xs-12 col-md-12" id="main" style="vertical-align: middle; margin-top: 3%;">
                    <p style="color:black">Dear {{ strtoupper($data->vendor_name) }}</p>
                    <br>
                    @if($data->country == 'ID')
                    <p style="color:black">
                        Kami mengucapkan terima kasih yang sebesar-besarnya atas konfirmasi dan pemahaman terhadap kebijakan perusahaan kami.
                        <br>
                        Atas nama pimpinan, staf, dan karyawan PT Yamaha Musical Products Indonesia, kami mengucapkan <br><b>Selamat Hari Raya Idul Fitri 1446 H</b>
                    </p>
                    @elseif($data->country == 'EN')
                    <p>
                        We extend our sincerest gratitude for your confirmation and understanding of our company's policy, and we look forward to continuing a transparent, fair, and mutually beneficial business relationship in the future.
                    </p>
                    @elseif($data->country == 'JP')
                    <p>
                        弊社の方針についてご確認とご理解を賜りましたこと、心より感謝申し上げます。今後も引き続き、公正で透明性のある、双方にとって有益なビジネス関係を築いていけることを願っております。
                    </p>

                    @endif
                    
                    <!-- <br>
                    <label style="font-size:16px"><input type="checkbox" class="minimal" id="confirmation"> Saya telah membaca dan memahami aturan tersebut</label> -->

                   <!--  <br>
                    <center>
                        <div class="container-contact100-form-btn" style="margin-top: 2%;">

                            <button class="contact100-form-btn" onclick="save()"
                                style="display: inline-block; font-family: sans-serif;">
                                <span>
                                    &nbsp;SUBMIT&nbsp;
                                    <i class="fa fa-save"></i>&nbsp;
                                </span>
                            </button>
                        </div>
                    </center> -->
                </div>
            </div>

            <div id="already_filled" style="width: 100%; display: none; margin-top: 5%;">
                <div class="col-xs-12 col-md-12">
                    <center style="font-size: 20px">Thank you for the response. Have e good day.</center>
                </div>
            </div>
        </div>
    </div>
</body>

<script src="{{ url('vendor/jquery/jquery-3.2.1.min.js') }}"></script>
<script src="{{ url('vendor/animsition/js/animsition.min.js') }}"></script>
<script src="{{ url('vendor/bootstrap/js/popper.js') }}"></script>
<script src="{{ url('vendor/bootstrap/js/bootstrap.min.js') }}"></script>
<script src="{{ url('vendor/select2/select2.min.js') }}"></script>
<script src="{{ url('vendor/daterangepicker/moment.min.js') }}"></script>
<script src="{{ url('vendor/daterangepicker/daterangepicker.js') }}"></script>
<script src="{{ url('vendor/countdowntime/countdowntime.js') }}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('bower_components/bootstrap-datepicker/dist/js/bootstrap-datepicker.min.js') }}"></script>
<script src="{{ url('bower_components/datatables.net/js/jquery.dataTables.min.js') }}"></script>
<script src="{{ url('bower_components/datatables.net-bs/js/dataTables.bootstrap.min.js') }}"></script>


<script type="text/javascript">
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    jQuery(document).ready(function() {
        clearAll();

    });

    function clearAll() {
        $("#not_filled").show();
        $("#already_filled").hide();

        no_item = [];
    }

    var no_item = [];


    function save() {

        var po_number = $("#po_number").val();
        var data = [];

        var x = {
            po_number: po_number
        }

        if (confirm("Are you sure to confirm this PO?")) {
            $("#loading").show();

            $.post("{{ url('input/po_eq_confirmation') }}", x, function(result, status, xhr) {
                if (result.status) {
                    clearAll();
                    $("#not_filled").hide();
                    $("#already_filled").show();
                    openSuccessGritter('Success', 'PO successfully confirmed');
                    $("#loading").hide();

                } else {
                    openErrorGritter('Error!', 'Tick all column before submit');
                    console.log('Error : ' + result.message)
                    $("#loading").hide();
                }
            });
        }
    }



    function openSuccessGritter(title, message) {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-success',
            image: '{{ url('images/image-screen.png') }}',
            sticky: false,
            time: '2000'
        });
    }

    function openErrorGritter(title, message) {
        jQuery.gritter.add({
            title: title,
            text: message,
            class_name: 'growl-danger',
            image: '{{ url('images/image-stop.png') }}',
            sticky: false,
            time: '2000'
        });
    }
</script>

</html>
