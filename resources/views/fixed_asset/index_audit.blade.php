@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    {{-- <link rel="stylesheet" href="{{ url('css/buttons.dataTables.min.css') }}"> --}}
    {{-- <link rel="stylesheet" href="{{ url('css/buttons.bootstrap4.min.css') }}"> --}}
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
                <span style="font-size: 40px">Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
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

                            <div class="col-2">
                                <button class="btn btn-block btn-primary btn-sm" onclick="getData()"><i
                                        class="fa fa-search"></i>
                                    Filter</button>
                            </div>
                            {{-- <div class="col-5">
                                <button type="button" class="btn btn-danger float-right" onclick="generateReport()">
                                    <i class="fas fa-sync fa-spin"></i> Generate Report</button>
                            </div> --}}
                        </div>

                        <div class="col-sm-12">
                            <br>
                            <table id="AuditAssetTable" class="table table-bordered table-striped table-hover">
                                <thead style="background-color: rgba(126,86,134,.7);">
                                    <tr>
                                        <th style="width: 5%">Period</th>
                                        <th style="width: 15%">Location</th>
                                        <th>PIC</th>
                                        <th>Auditor</th>
                                        <th style="width: 1%">Qty Asset</th>
                                        <th style="width: 1%">Min Audit</th>
                                        <th style="width: 1%">Actual Audit</th>
                                        <th style="width: 1%">Status</th>
                                        <th style="width: 1%">Report</th>
                                        <th style="width: 10%">Action</th>
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
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

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

            $.get('{{ url('fetch/fixed_asset/audit/audit/list') }}', data, function(result, status, xhr) {
                $("#loading").hide();
                $('#AuditAssetTable').DataTable().clear();
                $('#AuditAssetTable').DataTable().destroy();
                $("#AuditAssetBody").empty();
                body = "";

                $.each(result.assets, function(index, value) {
                    body += "<tr>";
                    body += "<td>" + value.period + "</td>";
                    body += "<td>" + value.location + "</td>";
                    body += "<td>" + (value.name || '') + "</td>";

                    var auditor = [];
                    $.each(value.checked_by.split(','), function(index2, value2) {
                        if (jQuery.inArray(value2.split('/')[1], auditor) != -1) {} else {
                            auditor.push(value2.split('/')[1]);
                        }
                    })

                    body += "<td>" + auditor.join(', ') + "</td>";
                    var must_audit = Math.round(parseInt(value.jml_asset) / 100 * 10);
                    if (must_audit < 1) must_audit = 1;

                    body += "<td style='text-align : right'>" + value.jml_asset + "</td>";
                    body += "<td style='text-align : right'>" + must_audit + "</td>";
                    body += "<td style='text-align : right'>" + value.audited + "</td>";
                    if (value.status_audit == "Open") {
                        body +=
                            "<td style='font-weight: bold; vertical-align: middle; background-color: #dc3545; color: white'><center>OPEN</center></td>";
                    } else {
                        body +=
                            "<td style='font-weight: bold; vertical-align: middle; background-color: #28a745; color: white'><center>CLOSE</center></td>";
                    }
                    body += "<td><button class='btn btn-block btn-danger btn-sm' onclick='cek_report(\""+value.location+"\")'><i class='far fa-file-pdf'></i> PDF</button></td>";
                    if (value.appr_manager_at && value.status_audit == "Open") {
                        body += "<td><center>";
                        body +=
                            "<a class='btn btn-primary btn-xs' href='{{ url('index/audit/fixed_asset/audit') }}/" +
                            value.location + "/" + value.period2 +
                            "'><i class='fas fa-clipboard-check'></i> Audit</a>";

                        if (value.audited >= must_audit && value.status_audit == "Open") {
                            body += "<br><button style='margin-top: 1%'  class='btn btn-success btn-xs' onclick='confirmAudit(\"" +
                                value.location + "\",\"" + value.period2 +
                                "\")'><i class='fas fa-check-double'></i> Confirm All</button>";
                        }
                        body += "</center></td>";
                    } else {
                        body += "<td></td>";
                    }
                    body += "</tr>";
                })

                $("#AuditAssetBody").append(body);

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

        function openModal() {
            $("#mapModal").modal('show');
        }

        function confirmAudit(lokasi, period) {
            if (confirm('Are you sure want to save audit asset in '+ lokasi+'?')) {
                var data = {
                    location: lokasi,
                    period: period,
                    category: 'Vendor'
                }
                $("#loading").show();

                $.post('{{ url('confirm/fixed_asset/audit') }}', data, function(result, status, xhr) {
                    $("#loading").hide();
                    if (result.status) {
                        toastr.success('Fixed Asset Successfully Audited', 'Success!');
                        getData();
                    } else {
                        toastr.error(result.message, 'Error!');

                    }
                })
            }
        }

        function closeModal() {
            $("#mapModal").modal('hide');
        }

        function cek_report(location) {
            
        }

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    </script>

@stop
