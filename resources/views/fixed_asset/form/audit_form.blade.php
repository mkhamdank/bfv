@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
        thead>tr>th {
            text-align: center;
        }

        tbody>tr>td {
            text-align: center;
        }

        tfoot>tr>th {
            text-align: center;
        }

        td:hover {
            overflow: visible;
        }

        table.table-bordered {
            border: 1px solid black;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid rgb(211, 211, 211);
        }

        table.table-bordered>tfoot>tr>th {
            border: 1px solid rgb(211, 211, 211);
        }

        /*#body_asset > tr:hover > td {
          cursor: pointer;
          background-color: #fff;
          }*/



        #body_asset>tr>td {
            background-color: #fff;
        }

        /* .radio {
            display: inline-block;
            position: relative;
            padding-left: 35px;
            margin-bottom: 12px;
            cursor: pointer;
            font-size: 16px;
            -webkit-user-select: none;
            -moz-user-select: none;
            -ms-user-select: none;
            user-select: none;
        } */

        .form-check {
			cursor: pointer;
		}

        #loading,
        #error {
            display: none;
        }
    </style>
@stop
@section('header')
    <section class="content-header">
        <input type="hidden" id="green">
    </section>
@stop
@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: white; top: 45%; left: 35%;">
                <span style="font-size: 40px">Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-sm-12">
                <div class="col-sm-12" style="padding-right: 0; padding-left: 0;">
                    <table class="table table-bordered" style="width: 100%;">
                        <thead>
                            <tr>
                                <th style="width:15%; background-color: rgb(220,220,220); text-align: center; color: black; padding:0;font-size: 18px;"
                                    colspan="3">Fixed Asset {{  ucfirst(Request::segment(4)) }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td
                                    style="padding: 0px; background-color: rgb(204,255,255); text-align: center; color: yellow; background-color: rgb(50, 50, 50); font-size:20px; width: 30%; vertical-align: middle;">
                                    PIC</td>
                                <td
                                    style="background-color: rgb(204,255,255); text-align: center; color: #000000;font-size: 20px">
                                    <span id="pic_name">{{ Auth::user()->username }}</span>
                                </td>
                                <td
                                    style="background-color: rgb(204,255,255); text-align: center; color: #000000;font-size: 20px">
                                    <span id="pic_name">{{ Auth::user()->name }}</span>
                                    <input type="hidden" id="pic_id" value="{{ Auth::user()->username }}">
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="padding: 0px; background-color: rgb(204,255,255); text-align: center; color: yellow; background-color: rgb(50, 50, 50); font-size:20px; width: 30%; vertical-align: middle;">
                                    Periode Cek</td>
                                <td colspan="2"
                                    style="background-color: rgb(204,255,255); text-align: center; color: #000000;font-size: 20px">
                                    <span id="cek_period"></span>
                                </td>
                            </tr>

                            <tr>
                                <td
                                    style="padding: 0px; background-color: rgb(204,255,255); text-align: center; color: yellow; background-color: rgb(50, 50, 50); font-size:20px; width: 30%; vertical-align: middle;">
                                    Location</td>
                                <td colspan="2"
                                    style="background-color: rgb(204,255,255); text-align: center; color: #000000;font-size: 20px">
                                    <span id="cek_location"></span>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                </div>

                <div class="col-sm-12" style="padding: 0;overflow-x: scroll;">
                    {{-- <a class='btn btn-primary' target='_blank' href='#' id="maps"><i class='fa fa-map'></i> Map
                        Asset</a> --}}
                    <table class="table" style="width: 100%; margin-top: 5px">
                        <thead>
                            <tr
                                style="background-color: rgb(126,86,134); color: #FFD700; font-weight: bold; font-size: 16px;">
                                <th style="padding: 0px;width: 1%;border: 2px solid white; color: white">
                                    <center>No</center>
                                </th>
                                <th style="padding: 0px;width: 3%;border: 2px solid white; color: white">
                                    <center>Asset Name</center>
                                </th>
                                <th style="padding: 0px;width: 2%;border: 2px solid white; color: white">
                                    <center>SAP Number</center>
                                </th>
                                <th style="padding: 0px;width: 3%;border: 2px solid white; color: white">
                                    <center>Foto Master</center>
                                </th>
                                <th style="padding: 0px;width: 3%;border: 2px solid white; color: white">
                                    <center>Foto Cek</center>
                                </th>
                                <th colspan="4" style="padding: 0px;width: 9%;border: 2px solid white; color: white">
                                    <center>Detail Audit</center>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="body_asset">

                        </tbody>
                        <!-- <tr> -->
                        <!-- <td style="width: 30%">
               <table style="width: 100%" class="table table-bordered">
                <thead style="background-color:rgba(127, 81, 207,0.5)">
                 <tr>
                  <th style="padding: 0px" width="10%">No</th>
                  <th style="padding: 0px">Asset</th>
                 </tr>
                </thead>
                <tbody id="body_asset"></tbody>
               </table>
              </td> -->
                        <!-- <td style="width: 70%">
               <img src="#" id="peta_lokasi" style="max-width: 100%" alt="Asset Map">
              </td> -->
                        <!-- </tr> -->
                    </table>

                </div>
            </div>
        </div>
        <div class="col-12">
            <div class="row">
                <div class="col-sm-4" style="margin-top: 10px;">
                    <button class="btn btn-danger" onclick="kembali()"
                        style="width: 100%;font-size: 25px;font-weight: bold;">
                        BACK
                    </button>
                </div>
                <div class="col-sm-4" style="margin-top: 10px;">
                    <button class="btn btn-warning" onclick="confirmTemp()"
                        style="width: 100%;font-size: 25px;font-weight: bold;">
                        TEMPORARY SAVE
                    </button>
                </div>
                <div class="col-sm-4" style="margin-top: 10px;">
                    <button class="btn btn-success" onclick="confirmAll()"
                        style="width: 100%;font-size: 25px;font-weight: bold;">
                        SAVE
                    </button>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-sm">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <label for="reason">Select Period </label>
                                <input type="text" class="form-control datepicker" name="period" id="period"
                                    placeholder="Select Period" style="text-align: center">
                            </div>


                            <div class="col-xs-12">
                                <label for="reason">Select Location </label>
                                <select class="select2" data-placeholder="Select Location" style="width: 100%"
                                    id="location" onchange="changeLocation(this.value)">
                                    <option value=""></option>
                                    <option value="YMPI">Internal YMPI</option>
                                    <option value="Vendor">External Vendor</option>
                                </select>
                            </div>

                            <div class="col-xs-12" id="div_section">
                                <label for="reason">Select Section </label>
                                <input type="text" id="section">
                            </div>

                            <div class="col-xs-12" id="div_area">
                                <label for="reason">Select Area </label>
                                <input type="text" id="area">
                            </div>

                            <div class="col-xs-12">
                                <center>
                                    <button class="btn btn-success" id="create_btn" onclick="save_form()"
                                        style="width: 100%; margin-top: 10px"><i class="fa fa-check"></i> OK </button>
                                </center>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal modal-default fade" id="scanModal">
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

                        <p style="visibility: hidden;">camera</p>
                        <input type="hidden" id="code">
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="auditModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-xs-12">
                                <table class="table table-bordered" style="width: 100%">
                                    <tr>
                                        <th colspan="6" style="background-color: #9378c2">
                                            <center>Detail Asset</center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #bea7e7">
                                            <center>Id</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>SAP ID</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Asset Description</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Registration Month</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Location</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Picture</center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td id="id_asset"></td>
                                        <td id="sap_id"></td>
                                        <td id="asset_desc"></td>
                                        <td id="reg_date"></td>
                                        <td id="asset_location"></td>
                                        <td><img src="#" id="asset_picture"></td>
                                    </tr>
                                </table>

                                <br>

                                <table class="table table-bordered" style="width: 100%">
                                    <tr>
                                        <th colspan="7" style="background-color: #9378c2">
                                            <center>Poin Cek</center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th colspan="2" style="background-color: #bea7e7">
                                            <center>Keberadaan</center>
                                        </th>
                                        <th colspan="5" style="background-color: #bea7e7">
                                            <center>Kondisi Pengecualian</center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <th style="background-color: #bea7e7">
                                            <center>Ada</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Tidak Ada</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Asset Tidak Digunakan</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Asset Rusak</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Label Tidak Ada / Rusak</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Map Tidak Sesuai</center>
                                        </th>
                                        <th style="background-color: #bea7e7">
                                            <center>Others</center>
                                        </th>
                                    </tr>
                                    <tr>
                                        <td><label class='radio' style='margin-top: 5px;margin-left: 5px'><input
                                                    onclick='goodchoice(this.id)' type='radio' id='keberadaan_ada'
                                                    name='keberadaan' value='Ada'><span
                                                    class='checkmark'></span></label></td>
                                        <td><label class='radio' style='margin-top: 5px;margin-left: 5px'><input
                                                    onclick='goodchoice(this.id)' type='radio'
                                                    id='keberadaan_tidak_ada' name='keberadaan' value='Tidak Ada'><span
                                                    class='checkmark'></span></label></td>
                                        <td><input type="checkbox" class="big-checkbox" id="pengecualian_tidak"></td>
                                        <td><input type="checkbox" class="big-checkbox" id="pengecualian_rusak"></td>
                                        <td><input type="checkbox" class="big-checkbox" id="pengecualian_label"></td>
                                        <td><input type="checkbox" class="big-checkbox" id="pengecualian_peta"></td>
                                        <td>
                                            <textarea class="form-control" id="pengecualian_oth" placeholder="Other Reason"></textarea>
                                        </td>
                                    </tr>
                                </table>
                                <br>
                                <button class="btn btn-success" style="width: 100%" id="save_btn"><i
                                        class="fa fa-check"></i> Submit Cek</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="modalImage">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <div class="modal-header">
                        <center> <b style="font-size: 2vw"></b> </center>
                        <div class="modal-body table-responsive no-padding">
                            <div class="col-xs-12" style="padding-top: 20px">
                                <div class="modal-footer">
                                    <div class="row">
                                        <button class="btn btn-danger btn-block pull-right" data-dismiss="modal"
                                            aria-hidden="true" style="font-size: 20px;font-weight: bold;">
                                            CLOSE
                                        </button>
                                    </div>
                                </div>
                            </div>
                            <div class="col-xs-12" id="images" style="padding-top: 20px">

                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    @endsection
    @section('scripts')
        <script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
        <script src="{{ url('js/sweetalert2.min.js') }}"></script>
        <script src="{{ url('js/toastr.min.js') }}"></script>
        <script src="{{ url('js/jszip.min.js') }}"></script>
        <script src="{{ url('js/vfs_fonts.js') }}"></script>
        <script>
            $.ajaxSetup({
                headers: {
                    'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                }
            });

            var loc = <?php echo json_encode($location); ?>;
            var video;
            var audited = 0;

            jQuery(document).ready(function() {
                $('body').toggleClass("sidebar-collapse");

                $('.datepicker').datepicker({
                    autoclose: true,
                    format: "yyyy-mm-dd",
                    viewMode: "months",
                    minViewMode: "months"
                });

                $('.select2').select2({
                    dropdownParent: $("#createModal")
                });

				save_form();
                // if ('{{ Request::segment(3) }}' == 'check1' || '{{ Request::segment(3) }}' == 'check2') {
                //     $("#period").val('{{ Request::segment(6) }}');
                //     $("#section").val('{{ Request::segment(4) }}')
                //     $("#area").val('{{ Request::segment(5) }}');
                // } else {
                //     $("#period").val('{{ Request::segment(5) }}');
                //     $("#section").val('{{ Request::segment(3) }}')
                //     $("#area").val('{{ Request::segment(4) }}');
                //     save_form();
                // }
            });

            function changeLocation(location) {
                // if (location === 'YMPI') {
                // 	$('#div_section').show();
                // 	$('#section').val('');
                // }else{
                // 	$('#div_section').hide();
                // 	$('#section').val('');
                // }

                $('#div_area').show();
            }

            function clearAll() {
                $('#div_section').hide();
                $('#tr_section').show();
                $('#section').val('').trigger('change');
                $('#location').val('').trigger('change');
                $('#period').val('');
                count_point = 0;
            }

            const monthNames = ["January", "February", "March", "April", "May", "June",
                "July", "August", "September", "October", "November", "December"
            ];

            var count_point = 0;

            function save_form() {
                var dateObj = new Date('{{ Request::segment(6) }}');
                var month = dateObj.getUTCMonth();
                var day = dateObj.getUTCDate();
                var year = dateObj.getUTCFullYear();

                var newdate = monthNames[month] + " " + year;

                $("#cek_period").text(newdate);
                $("#cek_location").text('{{ Request::segment(5) }}');

                $("#maps").attr('href', "{{ url('files/fixed_asset/map/') }}/" + $("#area").val() + ".pdf")

                if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                    stat_get = '{{ Request::segment(4) }}';
                } else {
                    stat_get = '{{ Request::segment(4) }}';
                }

                var data = {
                    period:'{{ Request::segment(6) }}',
                    area: "{{ Request::segment(5) }}",
                    status: stat_get
                }

                $.get('{{ url('fetch/fixed_asset/location/list') }}', data, function(result, status, xhr) {
                    if (result.status) {
                        audited = result.audit;
                        $("#body_asset").empty();
                        var body = "";
                        var loc = "";
                        count_point = 0;
                        var ii = 0;
                        if (result.asset.length == 0) {
							toastr.error('Data Kosong', 'Error!');
                            return false;
                        }
                        $.each(result.asset, function(index, value) {
                            if ((index + 1) % 2 == 0) {
                                var color = '#e6e6e6';
                                var colorth = 'black';
                                var colorbg = '#a3e3ff';
                            } else {
                                var color = '#ffffff';
                                var colorth = 'black';
                                var colorbg = '#fff1a3';
                            }

                            if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' ==
                                'check2') {
                                category = 'checked';
                                cek_stat = '';
                            } else {
                                category = '';
                                cek_stat = 'disabled';
                            }

                            body += "<tr onclick='detail_aset(" + value.sap_number + ")'>";
                            body += "<tr>";
                            body += "<input type='hidden' id='id_" + ii + "' value='" + value.sap_number + "'>";
                            body += "<td rowspan='4' style='background-color:" + color +
                                ";border:2px solid rgb(60, 60, 60);border-top:3px solid #af45ff;'>" + (index +
                                    1) + "</td>";
                            body += "<td rowspan='4' style='background-color:" + color +
                                ";border:2px solid rgb(60, 60, 60);border-top:3px solid #af45ff;'><span id='asset_name_" +
                                ii + "'>" + value.asset_name +
                                "</span><br><span style='color: green; font-size: 0.8vw'>Reg. date : " + (value
                                    .request_date || '-') +
                                "</span><br><br><button class='btn btn-warning btn-sm' onclick='confirmTemp()' style='width: 100%;font-weight: bold;'>TEMPORARY SAVE</button></td>";
                            body += "<td rowspan='4' style='background-color:" + color +
                                ";border:2px solid rgb(60, 60, 60);border-top:3px solid #af45ff;'>" + value
                                .sap_number + "</td>";

                            var url = '{{ url('data_file/fixed_asset/master_picture') }}' + '/' + value
                                .picture;
                            var url2 = '{{ url('data_file/fixed_asset/check_picture') }}' + '/' + value
                                .result_images;

                            body += '<td rowspan="4" id="point_check_images_' + ii +
                                '" style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);border-top:3px solid #af45ff;padding:5px;"><img style="width:150px;cursor:pointer" src="' +
                                url + '" class="user-image" alt="User image" onclick="modalImage(\'' + url +
                                '\')"><input type="hidden" id="audit_images_' + ii + '" value="' + value
                                .asset_images + '"></td>';
                            body += '<td rowspan="4" style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);border-top:3px solid #af45ff;padding:5px;"><img style="width:150px;cursor:pointer" src="' +
                                url2 + '" class="user-image" alt="image not found" onclick="modalImage(\'' +
                                url + '\')"></td>';

                            body +=
                                '<th style="border:2px solid black;border-top:3px solid #af45ff;background-color: ' +
                                colorbg + ';color: ' + colorth +
                                '; font-weight: bold; font-size: 16px;"><center>Keberadaan</center></th>';
                            body +=
                                '<th style="border:2px solid black;border-top:3px solid #af45ff;background-color: ' +
                                colorbg + ';color: ' + colorth +
                                '; font-weight: bold; font-size: 16px;"><center>Asset Digunakan</center></th>';
                            body +=
                                '<th style="border:2px solid black;border-top:3px solid #af45ff;background-color: ' +
                                colorbg + ';color: ' + colorth +
                                '; font-weight: bold; font-size: 16px;"><center>Asset Rusak</center></th>';
                            body +=
                                '<th style="border:2px solid black;border-top:3px solid #af45ff;background-color: ' +
                                colorbg + ';color: ' + colorth +
                                '; font-weight: bold; font-size: 16px;"><center>Label Rusak</center></th>';

                            body += "</tr>";
                            body += "<tr>";

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="avail_' + ii + '" id="avail_a_' + ii + '" value="Ada">';
                            body += '<label class="form-check-label font-weight-bold" for="avail_a_' + ii +
                                '">Ada</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="avail_' + ii + '" id="avail_b_' + ii + '" value="Tidak Ada">';
                            body += '<label class="form-check-label font-weight-bold" for="avail_b_' + ii +
                                '">Tidak Ada</label>';
                            body += '</div>';
                            body += '</td>';


                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="asset_usable_' + ii + '" id="asset_usable_a_' + ii +
                                '" value="Digunakan" ' + category + '>';
                            body += '<label class="form-check-label font-weight-bold" for="asset_usable_a_' +
                                ii + '">Digunakan</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="asset_usable_' + ii + '" id="asset_usable_b_' + ii +
                                '" value="Tidak Digunakan">';
                            body += '<label class="form-check-label font-weight-bold" for="asset_usable_b_' +
                                ii + '">Tidak Digunakan</label>';
                            body += '</div>';
                            body += '</td>';

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="asset_condition_' + ii + '" id="asset_condition_a_' + ii +
                                '" value="Tidak Rusak" ' + category + '>';
                            body += '<label class="form-check-label font-weight-bold" for="asset_condition_a_' +
                                ii + '">Tidak Rusak</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="asset_condition_' + ii + '" id="asset_condition_b_' + ii +
                                '" value="Rusak">';
                            body += '<label class="form-check-label font-weight-bold" for="asset_condition_b_' +
                                ii + '">Rusak</label>';
                            body += '</div>';

                            body += '</td>';

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';

                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="label_condition_' + ii + '" id="label_condition_a_' + ii +
                                '" value="Tidak Rusak" ' + category + '>';
                            body += '<label class="form-check-label font-weight-bold" for="label_condition_a_' +
                                ii + '">Tidak Rusak</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="label_condition_' + ii + '" id="label_condition_b_' + ii +
                                '" value="Rusak">';
                            body += '<label class="form-check-label font-weight-bold" for="label_condition_b_' +
                                ii + '">Rusak</label>';
                            body += '</div>';

                            body += '</td>';

                            body += "</tr>";

                            body += '<tr style="background-color: ' + colorbg + ';color: ' + colorth +
                                '; font-weight: bold; font-size: 16px;">';
                            body += '<th style="padding: 0px;width: 10%;"><center>Map Sesuai</center></th>';
                            body += '<th style="padding: 0px;width: 10%;"><center>Image Sesuai</center></th>';
                            body += '<th style="padding: 0px;width: 1%;"><center>Image Evidence</center></th>';
                            body += '<th style="padding: 0px;width: 10%;"><center>Note</center></th>';
                            body += '</tr>';

                            body += "<tr>";
                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="map_condition_' + ii + '" id="map_condition_a_' + ii +
                                '" value="Sesuai" ' + category + '>';
                            body += '<label class="form-check-label font-weight-bold" for="map_condition_a_' +
                                ii + '">Sesuai</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="map_condition_' + ii + '" id="map_condition_b_' + ii +
                                '" value="Tidak Sesuai">';
                            body += '<label class="form-check-label font-weight-bold" for="map_condition_b_' +
                                ii + '">Tidak Sesuai</label>';
                            body += '</div>';
                            body += '</td>';

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="image_condition_' + ii + '" id="image_condition_a_' + ii +
                                '" value="Sesuai" ' + category + '>';
                            body += '<label class="form-check-label font-weight-bold" for="image_condition_a_' +
                                ii + '">Sesuai</label>';
                            body += '</div>';
                            body += '<div class="form-check">';
                            body += '<input class="form-check-input" type="radio" ' + cek_stat +
                                ' name="image_condition_' + ii + '" id="image_condition_b_' + ii +
                                '" value="Tidak Sesuai">';
                            body += '<label class="form-check-label font-weight-bold" for="image_condition_b_' +
                                ii + '">Tidak Sesuai</label>';
                            body += '</div>';
                            body += '</td>';

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;vertical-align:middle">';

                            if ('{{ Request::segment(2) }}' == 'audit') {
                                body += '<div class="checkbox" style="font-size: 18px" onchange="open_radio(' +
                                    ii + ')"><label><input type="checkbox" name="audit_' + ii + '" id="audit_' +
                                    ii + '" value="audit"><b> Do Audit</b></label></div>';
                            }
                            // accept="image/*" capture="environment"

                            body += '<input type="file" id="file_' + ii + '" accept="image/*">';
                            body += '</td>';

                            body += '<td style="font-size:15px;background-color:' + color +
                                ';border:2px solid rgb(60, 60, 60);width:1%;padding: 0;">';
                            body += '<textarea placeholder="Notes" id="note_' + ii +
                                '" style="width:100%;height:100%;-webkit-box-sizing: border-box;-moz-box-sizing: border-box;box-sizing: border-box;border: none;padding: 0;" value="'+(value.note || '')+'">'+(value.note || '')+'</textarea>';
                            body += '</td>';
                            body += "</tr>";
                            ii++;

                            loc = value.location;
                            count_point++;
                        })
                        $("#body_asset").append(body);

                        if ('{{ Request::segment(2) }}' == 'audit') {
                            $.each(result.asset, function(index2, value2) {
                                $("input[name=avail_" + index2 + "][value='" + value2.availability + "']").prop(
                                    "checked", true);
                                $("input[name=asset_usable_" + index2 + "][value='" + value2.usable_condition +
                                    "']").prop("checked", true);
                                $("input[name=asset_condition_" + index2 + "][value='" + value2
                                    .asset_condition + "']").prop("checked", true);
                                $("input[name=label_condition_" + index2 + "][value='" + value2
                                    .label_condition + "']").prop("checked", true);
                                $("input[name=map_condition_" + index2 + "][value='" + value2.map_condition +
                                    "']").prop("checked", true);
                                $("input[name=image_condition_" + index2 + "][value='" + value2
                                    .asset_image_condition + "']").prop("checked", true);
                            })
                        }
                    }

                    loc = loc.replace(" ", "_");
                    // $("#peta_lokasi").attr('src', '{{ url('files/fixed_asset/map') }}/' + loc + ".png");
                })


                $("#createModal").modal('hide');
            }

            function modalImage(url) {
                $('#images').html('<img style="width:100%" src="' + url + '" class="user-image" alt="User image">');
                $('#modalImage').modal('show');
            }

            function confirmAll() {
                if (confirm('Apakah anda ingin Menyimpan Pengecekan ?')) {
                    $("#loading").show();
                    var stat = 0;
                    var stat_audit = [];

                    var audit = Math.floor(parseInt(count_point) / 100 * 20);
                    if (audit == 0) audit = 1;

                    for (var i = 0; i < count_point; i++) {
                        var availability = null;
                        $("input[name='avail_" + i + "']:checked").each(function(i) {
                            availability = $(this).val();
                        });

                        var asset_condition = null;
                        $("input[name='asset_condition_" + i + "']:checked").each(function(i) {
                            asset_condition = $(this).val();
                        });

                        var label_condition = null;
                        $("input[name='label_condition_" + i + "']:checked").each(function(i) {
                            label_condition = $(this).val();
                        });

                        var usable_condition = null;
                        $("input[name='asset_usable_" + i + "']:checked").each(function(i) {
                            usable_condition = $(this).val();
                        });

                        var map_condition = null;
                        $("input[name='map_condition_" + i + "']:checked").each(function(i) {
                            map_condition = $(this).val();
                        });

                        var image_condition = null;
                        $("input[name='image_condition_" + i + "']:checked").each(function(i) {
                            image_condition = $(this).val();
                        });

                        if ((availability == null || asset_condition == null || label_condition == null || usable_condition ==
                                null || map_condition == null || image_condition == null) && ("{{ Request::segment(3) }}" ==
                                'check1' || "{{ Request::segment(3) }}" == 'check2')) {
                            $('#loading').hide();
                            audio_error.play();
							toastr.error('Isi Semua Data', 'Error!');
                            return false;
                        }

                        if ($('#file_' + i).val().replace(/C:\\fakepath\\/i, '').split(".")[0] == '' && availability == 'Ada' &&
                            '{{ Request::segment(2) }}' != 'audit') {
                            $('#loading').hide();
                            audio_error.play();
							toastr.error('Lengkapi Foto Kolom No. '+(i+1), 'Error!');
                            return false;
                        }

                        if ($('#file_' + i).val().replace(/C:\\fakepath\\/i, '').split(".")[0] == '' && availability == 'Ada' &&
                            '{{ Request::segment(2) }}' == 'audit' && $('#audit_' + i).is(":checked")) {
                            $('#loading').hide();
                            audio_error.play();
							toastr.error('Lengkapi Foto Kolom No. '+(i+1), 'Error!');
                            return false;
                        }

                        if ('{{ Request::segment(2) }}' == 'audit') {
                            if ($('#audit_' + i).is(":checked")) {
                                stat_audit.push('ada');
                            }
                        }

                    }

                    if ((stat_audit.length + audited) < audit && '{{ Request::segment(2) }}' == 'audit') {
                        $("#loading").hide();
                        audio_error.play();
                        openErrorGritter('Error!', 'Quantity Audit < ' + audit);
                        return false;
                    }

                    for (var i = 0; i < count_point; i++) {
                        var category = '';


                        var url = '';
                        if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                            category = '{{ Request::segment(4) }}';
                            url = "{{ url('input/fixed_asset/check') }}";
                        } else {
                            category = 'audit';
                            url = "{{ url('input/fixed_asset/audit') }}";
                        }


                        var availability = '';
                        var asset_id = $("#id_" + i).val();
                        var asset_name = $("#asset_name_" + i).text();
                        var note = $("#note_" + i).val();
                        $("input[name='avail_" + i + "']:checked").each(function(i) {
                            availability = $(this).val();
                        });

                        var asset_condition = '';
                        $("input[name='asset_condition_" + i + "']:checked").each(function(i) {
                            asset_condition = $(this).val();
                        });

                        var label_condition = '';
                        $("input[name='label_condition_" + i + "']:checked").each(function(i) {
                            label_condition = $(this).val();
                        });

                        var usable_condition = '';
                        $("input[name='asset_usable_" + i + "']:checked").each(function(i) {
                            usable_condition = $(this).val();
                        });

                        var map_condition = '';
                        $("input[name='map_condition_" + i + "']:checked").each(function(i) {
                            map_condition = $(this).val();
                        });

                        var image_condition = '';
                        $("input[name='image_condition_" + i + "']:checked").each(function(i) {
                            image_condition = $(this).val();
                        });

                        var fileData = $('#file_' + i).prop('files')[0];

                        file = $('#file_' + i).val().replace(/C:\\fakepath\\/i, '').split(".");


                        var formData = new FormData();
                        formData.append('fileData', fileData);
                        formData.append('asset_id', asset_id);
                        formData.append('availability', availability);
                        formData.append('asset_condition', asset_condition);
                        formData.append('label_condition', label_condition);
                        formData.append('usable_condition', usable_condition);
                        formData.append('map_condition', map_condition);
                        formData.append('image_condition', image_condition);
                        formData.append('note', note);
                        formData.append('asset_name', asset_name);
                        formData.append('extension', file[1]);
                        formData.append('foto_name', file[0]);
                        formData.append('category', category);
                        formData.append('location', '{{ Request::segment(5) }}');
                        formData.append('period', '{{ Request::segment(6) }}');
                        formData.append('index', i + 1);

                        var adt_stat = '';
                        if ($('#audit_' + i).is(":checked")) {
                            adt_stat = 'audited';
                        }
                        formData.append('audit_status', adt_stat);
                        formData.append('counter', count_point);

                        $.ajax({
                            url: url,
                            method: "POST",
                            data: formData,
                            dataType: 'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(data) {
                                if (data.status == false) {
                                    $("#loading").hide();
                                    audio_error.play();
									toastr.error(data.message, 'Error!');
                                } else if (data.status == true) {
									toastr.success('Save Data Success', 'Success!');
                                    stat++;
                                }
                                if (stat == count_point) {
                                    $("#loading").hide();
									toastr.success('Save Data Success', 'Success!');

                                    // $.get('{{ url('pdf/fixed_asset_check') }}/' + loc + '/' + $("#period").val(),
                                        // function(result, status, xhr) {})

                                }
                            },
                            error: function(data) {
                                $('#loading').hide();
                                audio_error.play();
								toastr.error(data.message, 'Error!');
                            }
                        });
                    }

                    if (count_point == 0) {
                        var url = '';
                        if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                            category = '{{ Request::segment(4) }}';
                            url = "{{ url('input/fixed_asset/check') }}";
                        } else {
                            category = 'audit';
                            url = "{{ url('input/fixed_asset/audit') }}";
                        }

                        var formData = new FormData();
                        formData.append('category', category);
                        formData.append('period', $("#period").val());
                        formData.append('section', $("#section").val());
                        formData.append('location', $("#area").val());
                        formData.append('index', i + 1);

                        var adt_stat = '';
                        if ($('#audit_' + i).is(":checked")) {
                            adt_stat = 'audited';
                        }
                        formData.append('audit_status', adt_stat);
                        formData.append('counter', count_point);

                        // console.log(formData);

                        $.ajax({
                            url: url,
                            method: "POST",
                            data: formData,
                            dataType: 'JSON',
                            contentType: false,
                            cache: false,
                            processData: false,
                            success: function(data) {
                                $("#loading").hide();
								toastr.success('Save Data Success', 'Success!');

                                if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                                	window.setTimeout( window.location.href = '{{ url('index/fixed_asset') }}', 2000 );
                                } else {
                                	window.setTimeout( window.location.href = '{{ url('index/fixed_asset/audit_list') }}', 2000 );
                                }
                            },
                            error: function(data) {
                                $('#loading').hide();
                                audio_error.play();
								toastr.error(data.message, 'Error!');
                            }
                        });
                    }

                }
            }

            function confirmTemp() {
                if (confirm('Apakah anda ingin Menyimpan Sementara Pengecekan?')) {
                    $("#loading").show();
                    var stat = 0;
                    var stat_audit = [];

                    // if (!availability) {
                    // 	$('#loading').hide();
                    // 	audio_error.play();
                    // 	openErrorGritter('Error!','Lengkapi Keberadaan');
                    // 	return false;
                    // }

                    var audit = Math.floor(parseInt(count_point) / 100 * 20);
                    if (audit == 0) audit = 1;
					var stat_pilih = false;

                    for (var i = 0; i < count_point; i++) {
                        var availability = null;
                        $("input[name='avail_" + i + "']:checked").each(function(i) {
                            availability = $(this).val();
							stat_pilih = true;
                        });

                        var asset_condition = null;
                        $("input[name='asset_condition_" + i + "']:checked").each(function(i) {
                            asset_condition = $(this).val();
                        });

                        var label_condition = null;
                        $("input[name='label_condition_" + i + "']:checked").each(function(i) {
                            label_condition = $(this).val();
                        });

                        var usable_condition = null;
                        $("input[name='asset_usable_" + i + "']:checked").each(function(i) {
                            usable_condition = $(this).val();
                        });

                        var map_condition = null;
                        $("input[name='map_condition_" + i + "']:checked").each(function(i) {
                            map_condition = $(this).val();
                        });

                        var image_condition = null;
                        $("input[name='image_condition_" + i + "']:checked").each(function(i) {
                            image_condition = $(this).val();
                        });

                        if ($('#file_' + i).val().replace(/C:\\fakepath\\/i, '').split(".")[0] == '' && availability == 'Ada' &&
                            '{{ Request::segment(4) }}' != 'audit') {
                            $('#loading').hide();
                            audio_error.play();
							toastr.error('Lengkapi Foto Kolom No. '+(i+1), 'Error!');
                            return false;
                        }

						
                        if ('{{ Request::segment(4) }}' == 'audit') {
							if ($('#audit_' + i).is(":checked")) {
								stat_audit.push('ada');
                            }
                        }
						
                    }
					
					if (!stat_pilih) {
					    $("#loading").hide();
					    toastr.error('Tidak Ada Asset yang Dipilih', 'Gagal Save Data');
					    return false;
					}

                    for (var i = 0; i < count_point; i++) {
                        var category = '';


                        var url = '';
                        if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                            category = '{{ Request::segment(4) }}';
                            url = "{{ url('input/fixed_asset/check/temp') }}";
                        } else {
                            category = 'audit';
                            url = "{{ url('input/fixed_asset/audit/temp') }}";
                        }

                        var availability = '';
                        var asset_id = $("#id_" + i).val();
                        var asset_name = $("#asset_name_" + i).text();
                        var note = $("#note_" + i).val();
                        $("input[name='avail_" + i + "']:checked").each(function(i) {
                            availability = $(this).val();
                        });


                        var asset_condition = '';
                        $("input[name='asset_condition_" + i + "']:checked").each(function(i) {
                            asset_condition = $(this).val();
                        });

                        var label_condition = '';
                        $("input[name='label_condition_" + i + "']:checked").each(function(i) {
                            label_condition = $(this).val();
                        });

                        var usable_condition = '';
                        $("input[name='asset_usable_" + i + "']:checked").each(function(i) {
                            usable_condition = $(this).val();
                        });

                        var map_condition = '';
                        $("input[name='map_condition_" + i + "']:checked").each(function(i) {
                            map_condition = $(this).val();
                        });

                        var image_condition = '';
                        $("input[name='image_condition_" + i + "']:checked").each(function(i) {
                            image_condition = $(this).val();
                        });

                        var fileData = $('#file_' + i).prop('files')[0];

                        file = $('#file_' + i).val().replace(/C:\\fakepath\\/i, '').split(".");


                        if (availability != '') {
                            var stat_item = 1;
                            if (!$('#audit_' + i).is(":checked") && category == 'audit') {
                                stat_item = 0;
                            }

                            if (stat_item == 1) {
                                var formData = new FormData();
                                formData.append('fileData', fileData);
                                formData.append('asset_id', asset_id);
                                formData.append('availability', availability);
                                formData.append('asset_condition', asset_condition);
                                formData.append('label_condition', label_condition);
                                formData.append('usable_condition', usable_condition);
                                formData.append('map_condition', map_condition);
                                formData.append('image_condition', image_condition);
                                formData.append('note', note);
                                formData.append('asset_name', asset_name);
                                formData.append('extension', file[1]);
                                formData.append('foto_name', file[0]);
                                formData.append('category', category);
                                formData.append('period', '{{ Request::segment(6) }}');
                                formData.append('index', i + 1);

                                var adt_stat = '';
                                if ($('#audit_' + i).is(":checked")) {
                                    adt_stat = 'temporary save';
                                }
                                formData.append('audit_status', adt_stat);
                                formData.append('counter', count_point);

                                $.ajax({
                                    url: url,
                                    method: "POST",
                                    data: formData,
                                    dataType: 'JSON',
                                    contentType: false,
                                    cache: false,
                                    processData: false,
                                    success: function(data) {
                                        if (data.status == false) {
                                            $("#loading").hide();
                                            audio_error.play();
											toastr.error(data.message, 'Error!');
                                        } else if (data.status == true) {
                                            stat++;
                                        }
                                        $("#loading").hide();
										
                                        // setTimeout(location.reload.bind(location), 60000 * 2);
										console.log(stat+" "+count_point);
                                        
                                        if (stat == count_point) {
                                            toastr.success('Save Data Success', 'Success!');
                                            // window.setTimeout( window.location.href = '{{ url('index/fixed_asset') }}', 2000 );
                                        }
                                    },
                                    error: function(data) {
                                        $('#loading').hide();
                                        audio_error.play();
										toastr.error(data.message, 'Error!');
                                    }
                                });
                            }
                        }
                    }
                }
            }

            function open_radio(index) {
                if ($("#audit_" + index).is(":checked")) {
                    $("input[name=avail_" + index + "]").attr('disabled', false);
                    $("input[name=asset_usable_" + index + "]").attr('disabled', false);
                    $("input[name=asset_condition_" + index + "]").attr('disabled', false);
                    $("input[name=label_condition_" + index + "]").attr('disabled', false);
                    $("input[name=map_condition_" + index + "]").attr('disabled', false);
                    $("input[name=image_condition_" + index + "]").attr('disabled', false);
                } else {
                    $("input[name=avail_" + index + "]").attr('disabled', true);
                    $("input[name=asset_usable_" + index + "]").attr('disabled', true);
                    $("input[name=asset_condition_" + index + "]").attr('disabled', true);
                    $("input[name=label_condition_" + index + "]").attr('disabled', true);
                    $("input[name=map_condition_" + index + "]").attr('disabled', true);
                    $("input[name=image_condition_" + index + "]").attr('disabled', true);
                }
            }

            function kembali() {
                if (confirm('Apakah anda ingin kembali ke List Asset ?')) {
                    if ('{{ Request::segment(4) }}' == 'check1' || '{{ Request::segment(4) }}' == 'check2') {
                        window.location.href = "{{ url('index/fixed_asset') }}";
                    } else {
                        window.location.href = "{{ url('index/fixed_asset/auditor_audit/list') }}";
                    }
                }
            }

            var audio_error = new Audio('{{ url('sounds/error.mp3') }}');

            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });
        </script>
    @endsection
