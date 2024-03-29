<!DOCTYPE html>
<html lang="">

<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>PT. YMPI - CONTROL DELIVERY</title>
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
        <div class="wrap-contact100 col-xs-12 col-md-8 col-md-offset-2" style="padding: 0 10px; padding-bottom: 6%;">
            <span class="contact100-form-title" id="main-title">
                <small style="font-size: 1.25vw;">CONTROL DELIVERY PT. YMPI</small><br>PO CONFIRMATION
            </span>

            <div id="already_filled" style="width: 100%; margin-top: 5%;">
                <div class="col-xs-12 col-md-12">
                    <center style="font-size: 20px">Thank you for the response!<br>We will check it as soon as possible</center>
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
        $("#main").hide();
        $("#po_number").prop('disabled', false);

        $("#po_number").val('');
        // $("#po_number").focus();

        no_item = [];
    }

    var no_item = [];

    function searhPo() {
        var po_number = $("#po_number").val();
        var check_po = $("#check_po").val();

        if (po_number.length >= 12) {

            if (check_po != po_number) {
                openErrorGritter('Error!', "PO number doesn't match the link");
                return false;
            }

            var data = {
                po_number: po_number
            }

            $("#loading").show();
            $.get('{{ url('fetch/po_eq_confirmation') }}', data, function(result, status, xhr) {
                if (result.status) {

                    $('#loading').show();

                    $('#tableDetail').DataTable().clear();
                    $('#tableDetail').DataTable().destroy();
                    $('#bodyDetail').html("");
                    no_item = [];

                    $("po_number").prop('disabled', true);

                    var tableData = "";
                    for (var i = 0; i < result.data.length; i++) {
                        tableData += '<tr>';
                        tableData += '<td style="width:30%; padding:0px 5px 0px 5px; text-align:left;">' +
                            result.data[i].item_name + '</td>';
                        tableData += '<td style="width:10%; padding:0px 5px 0px 5px; text-align:center;">' +
                            result.data[i].delivery_date + '</td>';
                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            result.data[i].quantity + ' ' + result.data[i].uom + '</td>';
                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            parseInt(result.data[i].price).toLocaleString('de-DE') + '</td>';

                        var amount = result.data[i].price * result.data[i].quantity;

                        tableData += '<td style="width:5%; padding:0px 5px 0px 5px; text-align:right;">' +
                            amount.toLocaleString('de-DE') + '</td>';
                        tableData += '<td style="width:5%; padding:18px 5px 0px 5px; text-align:center;">';
                        tableData += '<label><input type="checkbox" class="minimal" id="check_' + result.data[i]
                            .no_item + '"></label><br>'
                        tableData += '</td>';

                        tableData += '<td style="width:15%; padding:0px 5px 0px 5px; text-align:center;">';
                        tableData +=
                            '<input type="text" class="form-control" style="font-size: 12px;" id="note_' +
                            result.data[i].no_item + '" placeholder="Reason ...">';
                        tableData += '</td>';

                        tableData += '</tr>';

                        no_item.push(result.data[i].no_item);

                    }

                    $('#bodyDetail').append(tableData);
                    $('#tableDetail').DataTable({
                        'dom': 'Bfrtip',
                        'responsive': true,
                        'lengthMenu': [
                            [-1],
                            ['Show all']
                        ],
                        'buttons': {
                            buttons: []
                        },
                        'paging': false,
                        'lengthChange': false,
                        'searching': false,
                        'ordering': false,
                        'info': false,
                        'autoWidth': true,
                        'sPaginationType': 'full_numbers',
                        'bJQueryUI': true,
                        'bAutoWidth': false,
                        'processing': true
                    });


                    $('#main').show();
                    $('#loading').hide();

                }
            });

        } else {
            $('#tableDetail').DataTable().clear();
            $('#tableDetail').DataTable().destroy();
            $('#bodyDetail').html("");
            var tableData = "";

            $('#bodyDetail').append(tableData);
            $('#tableDetail').DataTable({
                'dom': 'Bfrtip',
                'responsive': true,
                'lengthMenu': [
                    [-1],
                    ['Show all']
                ],
                'buttons': {
                    buttons: []
                },
                'paging': false,
                'lengthChange': false,
                'searching': false,
                'ordering': false,
                'info': false,
                'autoWidth': true,
                'sPaginationType': 'full_numbers',
                'bJQueryUI': true,
                'bAutoWidth': false,
                'processing': true
            });
        }

    }

    function save() {

        var po_number = $("#po_number").val();
        var data = [];

        console.log(no_item);

        for (var i = 0; i < no_item.length; i++) {
            if ($('#check_' + no_item[i]).is(":checked")) {
                data.push({
                    'no_item': no_item[i],
                    'note': $('#note_' + no_item[i]).val(),
                });
            } else {
                openErrorGritter('Error!', 'Tick all column before submit');
                return false;
            }
        }

        var x = {
            po_number: po_number,
            data: data
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
