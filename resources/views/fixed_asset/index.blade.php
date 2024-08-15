@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
        thead>tr>th {
            text-align: center;
            overflow: hidden;
        }

        tbody>tr>td {
            text-align: center;
        }

        tfoot>tr>th {
            text-align: center;
        }

        th:hover {
            overflow: visible;
        }

        td:hover {
            overflow: visible;
        }

        table.table-bordered {
            border: 1px solid grey;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid grey;
            padding-top: 0;
            padding-bottom: 0;
            vertical-align: middle;
        }

        table.table-bordered>tbody>tr>td {
            padding: 0px;
            vertical-align: middle;
        }

        table.table-bordered>tfoot>tr>th {
            padding: 0;
            vertical-align: middle;
            color: #fff !important;
        }

        thead {
            background-color: #fff;
            color: #fff;
        }

        td {
            overflow: hidden;
            text-overflow: ellipsis;
        }

        th {
            color: white;
        }
    </style>
@endsection
@section('header')
    <section class="content-header">
        <ol class="breadcrumb" style="margin-left:10px;margin-bottom: 0px !important;">
            <li></li>
        </ol>
    </section>
@endsection


@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content">
        <input type="hidden" value="{{ csrf_token() }}" name="_token" />
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: white; top: 45%; left: 35%;">
                <span style="font-size: 40px"><i class="fas fa-circle-notch fa-spin"></i> Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-xs-12" style="padding-top: 10px;">
                <div class="box no-border">
                    <div class="box-body">
                        <div class="row">
                            <div class="col-2">
                                <label class="control-label"><b>Select Period : </b></label>
                            </div>
                            <div class="col-3">
                                <select class="form-control select2" id="period" data-placeholder="Select Period">
                                    <option value=""></option>
                                    @foreach ($period as $per)
                                        <option value="{{ $per->period }}">{{ $per->period }}</option>
                                    @endforeach
                                </select>
                            </div>

                            <div class="col-1">
                                <button class="btn btn-block btn-primary btn-sm" onclick="getData()"><i
                                        class="fa fa-search"></i>
                                    Filter</button>
                            </div>
                            <div class="col-6">
                                <button type="button" class="btn btn-danger float-right" onclick="generateReport()">
                                    <i class="fas fa-sync fa-spin"></i> Generate Report</button>
                                    {{-- <button type="button" style="margin-right: 1%" class="btn btn-primary float-right" onclick="openModal()"><i
                                        class="fa fa-upload"></i> <i class="fa fa-map"></i> Upload Map</button> --}}
                                        <a type="button" class="btn btn-secondary float-right" style="margin-right: 2px" href="{{ url('data_file/fixed_asset/Panduan Cek Fixed Asset YMPI.mp4') }}" target="_blank"><i class="fas fa-video"></i> Panduan Pengisian</a>
                            </div>
                        </div>



                        <div class="col-sm-12">
                            <br>
                            <table id="AuditAssetTable" class="table table-bordered table-striped table-hover">
                                <thead style="background-color: rgba(126,86,134,.7);">
                                    <tr>
                                        <th>Period</th>
                                        <th>FA Number</th>
                                        <th>Fixed Asset Name</th>
                                        {{-- <th>Section</th> --}}
                                        <th>PIC</th>
                                        <th>Auditor</th>
                                        <th>Image</th>
                                        <th>Report</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody id="AuditAssetBody">
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="mapModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
            aria-hidden="true">
            <div class="modal-dialog modal-lg">
                <div class="modal-content">
                    <form id="upload_map_form">
                        <input type="hidden" value="{{ csrf_token() }}" name="_token" />
                        <div class="modal-header">
                            <h4 class="modal-title" id="myModalLabel">
                                <center><b>Update Asset MAP</b></center>
                            </h4>
                        </div>
                        <div class="modal-body">
                            <div class="row">
                                <div class="col-xs-12">
                                    <div class="col-xs-12">
                                        <div class="form-group">
                                            <label class="col-sm-2 control-label">Vendor :</label>
                                            <div class="col-sm-5" <input type="text" id="vendor_map" readonly>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="col-xs-12">
                                        <table class="table table-bordered" style="width: 100%">
                                            <thead style="background-color: #a488aa">
                                                <tr>
                                                    <th>Location</th>
                                                    <th>Map</th>
                                                    <th>Upload Map</th>
                                                </tr>
                                            </thead>
                                            <tbody id="body_map">
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <div class="row" style="margin-left: 2%; margin-right: 2%;">
                                <button type="submit" class="btn btn-success"><i class="fa fa-check"></i> Update
                                    Map</button>
                                <button type="button" class="btn btn-danger pull-left" data-dismiss="modal"
                                    onclick="closeModal()"><i class="fa fa-close"></i> Cancel</button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </section>

@stop

@section('scripts')
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{ url('js/buttons.bootstrap4.min.js') }}"></script> --}}
    <script src="{{ url('js/buttons.html5.min.js') }}"></script>
    {{-- <script src="{{ url('js/buttons.flash.min.js') }}"></script>
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <script src="{{ url('js/buttons.html5.min.js') }}"></script> --}}
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>

    @if (Session::has('message'))
        {{-- <div class="alert alert-info">{{ Session::get('message') }}</div> --}}
        <script>
            var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 2000
            });

            toastr.error("{{ Session::get('message') }}", "Error!");
        </script>
    @endif

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var period = '';
        var loc = '';

        jQuery(document).ready(function() {
            $('body').toggleClass("sidebar-collapse");

            $('.monthpicker').datepicker({
                format: "yyyy-mm",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true
            });

            $('.select2').select2({
                // dropdownParent: $("#generateModal")
                dropdownPosition: 'below'
            });

            if ("{{ Auth::user()->username }}" == 'bahanacheck1' || "{{ Auth::user()->username }}" ==
                'bahanacheck2' || "{{ Auth::user()->username }}" == 'bahanaaudit') {
                $("#vendor_map").text("Bahana Unindo");

                $("#body_map").empty();

                var bd = '';
                bd += '<tr>';
                bd += '<td class="loc">Bahana Unindo</td>';
                bd +=
                    '<td><a class="btn btn-primary btn-xs" target="_blank" href="{{ url('files/fixed_asset/map/Bahana Unindo.pdf') }}"><i class="fa fa-map"></i> Map</a></td>';
                bd += '<td><input type="file" id="map" name="map" class="map" accept="application/pdf"></td>';
                bd += '</tr>';
                $("#body_map").append(bd);

            }

            getData();
        });

        function getData() {
            $("#loading").show();
            var data = {
                period: $("#period").val()
            }

            $.get('{{ url('fetch/fixed_asset/audit/list') }}', data, function(result, status, xhr) {
                $("#loading").hide();
                $('#AuditAssetTable').DataTable().clear();
                $('#AuditAssetTable').DataTable().destroy();
                $("#AuditAssetBody").empty();
                body = "";
                var total_cek2 = 0;

                $.each(result.assets, function(index, value) {
                    period = value.period;
                    loc = value.location;

                    if (value.remark == "temporary save 2") {
                        total_cek2 += 1;
                    }

                    body += "<tr>";
                    body += "<td>" + value.period + "</td>";
                    body += "<td>" + value.sap_number + "</td>";
                    body += "<td>" + value.asset_name + "</td>";
                    body += "<td>" + value.location + "</td>";
                    if (value.checked_by) {
                        body += "<td>" + value.checked_by.split('/')[1] + "</td>";
                    } else {
                        body += "<td></td>";
                    }
                    var url = "{{ url('data_file/fixed_asset/master_picture') }}/" + value.asset_images;
                    body += "<td><img src='" + url +
                        "' style='max-width: 100px; max-height: 100px; cursor:pointer' onclick='modalImage(\"" +
                        url + "\", \"" + value.sap_number + "\", \"" + value.period +
                        "\")' Alt='Image Not Found'></td>";
                    body += "<td><button class='btn btn-block btn-danger btn-sm' onclick='cek_report()'><i class='far fa-file-pdf'></i> PDF</button></td>";
                    body += "<td>";
                    if (value.appr_status) {
                        if (value.appr_manager_at) {
                            body += "Fully Approved";
                        } else {
                            body += "Waiting Approval";
                        }
                    } else {
                        body += value.status;
                        if (value.remark == 'temporary save 1') {
                            body += '<br><span class="badge bg-warning">Temporary save 1</span>';
                        } else if (value.remark == 'temporary save 2') {
                            body += '<br><span class="badge bg-warning">Temporary save 2</span>';
                        }
                    }
                    body += "</td>";
                    body += "<td style='vertical-align: middle' class='btn_act'>";

                    if (((value.status == 'Check 1' && !value.remark) || value.remark == 'temporary save 1') &&
                        '{{ $permiss->permission }}'.indexOf("Check 2") >= 0) {
                        body +=
                            "<a class='btn btn-block btn-info btn-sm' href='{{ url('index/check/fixed_asset/check2') }}/" +
                            value.location + "/" + value.period +
                            "'><i class='fas fa-edit'></i> Cek 2</a>";
                    } else if (value.status == 'Check 2' && !value.appr_manager_at &&
                        '{{ $permiss->permission }}'.indexOf("Check 2") >= 0) {
                        body += "<button class='btn btn-block btn-primary btn-sm' onclick='send_mail(\"" +
                            value
                            .period + "\",\"" + value.location +
                            "\")'><i class='fas fa-paper-plane'></i> Send Approval</button>";
                    } else if (value.status == 'Not Checked' &&
                        '{{ $permiss->permission }}'.indexOf("Check 1") >= 0 && !value.remark) {
                        // if (!value.remark) {
                        body +=
                            "<a class='btn btn-block btn-secondary btn-sm' href='{{ url('index/check/fixed_asset/check1') }}/" +
                            value.location + "/" + value.period +
                            "'><i class='fas fa-edit'></i> Cek 1</a>";
                        // }
                    } else if (value.appr_manager_at) {
                        body +=
                            "<label class='btn btn-success btn-xs'><i class='fa fa-check'></i> Fully Approved</label>";
                    }

                    body += "</td>";
                    body += "</tr>";
                })

                $("#AuditAssetBody").append(body);

                console.log(total_cek2, result.assets.length);

                if (total_cek2 == result.assets.length) {
                    $(".btn_act").append("<button class='btn btn-success btn-xs' onclick='save_check()'><i class='fas fa-check-double'></i> Save Check</button>");
                }

                var table = $('#AuditAssetTable').DataTable({
                    'dom': 'Bfrtip',
                    'responsive': true,
                    'lengthMenu': [
                        [10, 25, 50, -1],
                        ['10 rows', '25 rows', '50 rows', 'Show all']
                    ],
                    'paging': true,
                    'lengthChange': true,
                    'searching': true,
                    'ordering': true,
                    'info': true,
                    'autoWidth': true,
                    "sPaginationType": "full_numbers",
                    "bJQueryUI": true,
                    "bAutoWidth": false,
                    "processing": true,
                });
            })

        }

        function send_mail(period, location) {
            if (confirm('Send Fixed Asset Check Approval in "' + location + '" ?')) {
                var data = {
                    location: location,
                    period: period,
                    category: 'Vendor'
                }
                $("#loading").show();

                $.post('{{ url('approval/fixed_asset/check') }}', data, function(result, status, xhr) {
                    $("#loading").hide();
                    if (result.status) {
                        toastr.success('Approval berhasil terkirim', 'Success!');
                        getData();
                    } else {
                        toastr.error(result.message, 'Error!');
                    }
                })

            }
        }

        function openModal() {
            $("#mapModal").modal('show');
        }

        $("form#upload_map_form").submit(function(e) {
            $("#loading").show();

            e.preventDefault();

            var arr_loc = [];
            var arr_map = [];

            $('.map').each(function() {
                arr_map.push($(this).prop('files')[0]);
            });

            $('.loc').each(function() {
                arr_loc.push($(this).text());
            });

            var formData = new FormData();
            formData.append('location', arr_loc);

            $('.map').each(function(index, value) {
                formData.append('map_' + index, $(this).prop('files')[0]);
            });

            $.ajax({
                url: '{{ url('upload/fixed_asset/map') }}',
                type: 'POST',
                data: formData,
                success: function(result, status, xhr) {
                    $("#loading").hide();

                    toastr.success('Upload Map Successfully', 'Success!');

                    setTimeout(function() {
                        window.location.reload();
                    }, 1500);
                },
                error: function(result, status, xhr) {
                    $("#loading").hide();

                    openErrorGritter('Error!', result.message);
                    audio_error.play();
                },
                cache: false,
                contentType: false,
                processData: false
            });
        });

        function closeModal() {
            $("#mapModal").modal('hide');
        }

        function generateReport() {
            $("#loading").show();
            var data = {
                period : period,
                location : loc,
                category : "Check"
            }

            $.get('{{ url('generate/fixed_asset/report') }}', data, function(result, status, xhr) {
                $("#loading").hide();
                toastr.success('Report PDF berhasil dibuat', 'Success!');
            })
        }

        function cek_report() {
            var data = {
                period : period,
                location : loc,
                category : "Check"
            }

            $.get('{{ url('check/fixed_asset/report') }}', data, function(result, status, xhr) {
                if (result.status) {
                    // toastr.success('Approval berhasil terkirim', 'Success!'); 
                    window.open("{{ url('') }}/"+result.message, '_blank');
                } else {
                    toastr.error(result.message, 'Error!');
                }
            })
        }

        function save_check() {
            var data = {
                period : period,
                location : loc,
                category : "Check"
            }

            $.post('{{ url('confirm/fixed_asset/check') }}', data, function(result, status, xhr) {
                $("#loading").hide();
                toastr.success('Hasil Cek Berhasil Disimpan', 'Success!');
                getData();
            })
        }

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    </script>

@stop
