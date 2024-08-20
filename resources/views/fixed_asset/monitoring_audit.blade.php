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
                        </div>

                        <div class="col-sm-12">
                            <br>
                            <div id="chart_check"></div><br>
                            <div id="chart_audit"></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <div class="modal fade show" id="modal_details" aria-modal="true" role="dialog">
        <div class="modal-dialog modal-xl">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="title_modal">Details</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body" id="container_table"></div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
            </div>

        </div>

    </div>

@stop

@section('scripts')
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/jquery.dataTables.min.js') }}"></script>
    <script src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>
    {{-- <script src="{{ url('js/buttons.html5.min.js') }}"></script> --}}
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>
    <script src="{{ url('js/highcharts.js') }}"></script>
    <script src="{{ url('js/exporting.js') }}"></script>
    <script src="{{ url('js/export-data.js') }}"></script>
    <script src="{{ url('js/accessibility.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var check_stat = ['Not Checked', 'Check 1', 'Check 2', 'Waiting Approval', 'Fully Approve'];
        var colors = {
            'Not Checked': '#d81b60',
            'Check 1': '#ff851b',
            'Check 2': '#ffc107',
            'Waiting Approval': '#2caffe',
            'Fully Approve': '#00e272'
        };
        var check_details = [];
        var audit_details = [];

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

            getData();
        });

        function getData() {
            var data = {
                period: $("#period").val()
            }
            $.get('{{ url('fetch/fixed_asset/report') }}', data, function(result, status, xhr) {
                var ctg = [];
                var resume_check = [];
                var series_check = [];
                check_details = result.details_check_data;
                audit_details = result.details_audited_data;

                $.each(result.checked_data, function(index, value) {
                    if (ctg.indexOf(value.location) === -1) {
                        ctg[ctg.length] = value.location;
                    }
                })

                $.each(ctg, function(index, value) {
                    $.each(check_stat, function(index2, value2) {
                        var total_asset = 0;
                        $.each(result.checked_data, function(index3, value3) {
                            if (value == value3.location && value2 == value3.real_stat) {
                                total_asset = parseInt(value3.total_asset);
                            }
                        })

                        resume_check.push({
                            'location': value,
                            'status': value2,
                            'total_asset': total_asset
                        });
                    })
                })

                $.each(check_stat, function(index2, value2) {
                    var tmp = [];
                    $.each(resume_check, function(index3, value3) {
                        if (value2 == value3.status) {
                            tmp.push(value3.total_asset);
                        }
                    })
                    series_check.push({
                        'name': value2,
                        'data': tmp,
                        'color': colors[value2]
                    });
                })

                Highcharts.chart('chart_check', {
                    chart: {
                        type: 'column'
                    },
                    title: {
                        text: 'Monitoring Progress Check '+result.period,
                    },
                    xAxis: {
                        categories: ctg
                    },
                    yAxis: {
                        min: 0,
                        title: {
                            text: 'Count Fixed Asset'
                        },
                        stackLabels: {
                            enabled: true
                        }
                    },
                    legend: {
                        backgroundColor: Highcharts.defaultOptions.legend.backgroundColor || 'white',
                        borderColor: '#CCC',
                        borderWidth: 1,
                        shadow: false
                    },
                    tooltip: {
                        headerFormat: '<b>{point.x}</b><br/>',
                        pointFormat: '{series.name}: {point.y}<br/>Total: {point.stackTotal}'
                    },
                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true
                            },
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function() {
                                        modalDetail(this.category, this.series.name, 'check');
                                    }
                                }
                            },
                        },
                        series: {
                            dataSorting: {
                                // enabled: true
                            },
                        }
                    },
                    credits: {
                        enabled: false
                    },
                    series: series_check
                });

                var ctg2 = [];
                var total_asset = [];
                var audited = [];
                var finder = [];
                var yet_audited = [];

                $.each(result.audited_data, function(index, value) {
                    ctg2.push(value.location);
                    total_asset.push(value.total_asset);
                    audited.push(parseInt(value.audited));
                    finder.push(parseInt(value.finding));
                    var must_audit = Math.round(parseInt(value.total_asset) / 100 * 10);
                    if (must_audit == 0) must_audit = 1;

                    var diff = must_audit - (parseInt(value.audited) + parseInt(value.finding));
                    if (diff < 0) diff = 0;
                    yet_audited.push(diff);
                })

                Highcharts.chart('chart_audit', {

                    chart: {
                        type: 'column'
                    },

                    title: {
                        text: 'Monitoring Progress Audit '+result.period,
                    },

                    xAxis: {
                        categories: ctg2
                    },

                    yAxis: {
                        allowDecimals: false,
                        min: 0,
                        title: {
                            text: 'Count Fixed Asset'
                        },
                        stackLabels: {
                            enabled: true
                        }
                    },

                    tooltip: {
                        format: '<b>{key}</b><br/>{series.name}: {y}<br/>' +
                            'Total: {point.stackTotal}'
                    },

                    plotOptions: {
                        column: {
                            stacking: 'normal',
                            dataLabels: {
                                enabled: true
                            },
                            cursor: 'pointer',
                            point: {
                                events: {
                                    click: function() {
                                        modalDetail(this.category, this.series.name, 'Audit');
                                    }
                                }
                            },
                        }
                    },

                    credits: {
                        enabled: false
                    },

                    series: [{
                        name: 'Total Asset',
                        data: total_asset,
                        stack: 'Total Asset',
                        color: '#6d68de'
                    }, {
                        name: 'Audited',
                        data: audited,
                        stack: 'Audit',
                        color: '#19fb8b'
                    }, {
                        name: 'Finding',
                        data: finder,
                        stack: 'Audit',
                        color: '#ffc107'
                    }, {
                        name: 'Must Audit',
                        data: yet_audited,
                        stack: 'Audit',
                        color: '#d81b60'
                    }]
                });
            })
        }

        function modalDetail(loc, status, ctg) {
            $("#modal_details").modal("show");

            Inittable();
            if (ctg == 'check') {
                $("#title_modal").text("Details Check");
                var body = "";
                var num = 1;
                $.each(check_details, function(index, value) {
                    if (value.location == loc && value.real_stat == status) {
                        body += "<tr>";
                        body += "<td style='vertical-align: middle'><center>" + num + "</center></td>";
                        body +=
                            "<td style='vertical-align: middle'><img src='{{ url('data_file/fixed_asset/master_picture') }}/" +
                            value.asset_images + "' alt='' style='max-width: 100px'></td>";
                        body += "<td style='vertical-align: middle'>" + value.sap_number + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.asset_name + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.location + "</td>";
                        body += "<td style='vertical-align: middle'><pre>";
                        body += "Availability   : " + (value.availability || '') + "<br>";
                        body += "Condition      : " + (value.asset_condition || '') + "<br>";
                        body += "Label          : " + (value.label_condition || '') + "<br>";
                        body += "Usable         : " + (value.usable_condition || '') + "<br>";
                        body += "Map            : " + (value.map_condition || '') + "<br>";
                        body += "Note           : " + (value.note || '') + "<br>";
                        body += "</pre></td>";
                        body += "<td style='vertical-align: middle'>" + value.real_stat + "</td>";

                        body += "</tr>";
                        num++;
                    }
                })

                $("#body_details").append(body);

                $('#table_details').DataTable({
                    // "buttons": ["copy", "excel", "print"],
                    "paging": true,
                    "lengthChange": true,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "bDestroy": true
                });
            } else {
                $("#title_modal").text("Details Audit");
                var body = "";
                var num = 1;
                $.each(audit_details, function(index, value) {
                    if (value.location == loc && value.status.toLowerCase() == status.toLowerCase()) {
                        body += "<tr>";
                        body += "<td style='vertical-align: middle'><center>" + num + "</center></td>";
                        body +=
                            "<td style='vertical-align: middle'><img src='{{ url('data_file/fixed_asset/master_picture') }}/" +
                            value.asset_images + "' alt='' style='max-width: 100px'></td>";
                        body += "<td style='vertical-align: middle'>" + value.sap_number + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.asset_name + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.location + "</td>";
                        body += "<td style='vertical-align: middle'><pre>";
                        body += "Availability   : " + (value.availability || '') + "<br>";
                        body += "Condition      : " + (value.asset_condition || '') + "<br>";
                        body += "Label          : " + (value.label_condition || '') + "<br>";
                        body += "Usable         : " + (value.usable_condition || '') + "<br>";
                        body += "Map            : " + (value.map_condition || '') + "<br>";
                        body += "Note           : " + (value.note || '') + "<br>";
                        body += "</pre></td>";
                        if (status == 'Must Audit') {
                            body += "<td style='vertical-align: middle'>Not Audited Yet</td>";
                        } else {
                            body += "<td style='vertical-align: middle'>" + value.status + "</td>";
                        }
                        body += "</tr>";
                        num++;
                    } else if (value.location == loc && status == 'Total Asset') {
                        body += "<tr>";
                        body += "<td style='vertical-align: middle'><center>" + num + "</center></td>";
                        body +=
                            "<td style='vertical-align: middle'><img src='{{ url('data_file/fixed_asset/master_picture') }}/" +
                            value.asset_images + "' alt='' style='max-width: 100px'></td>";
                        body += "<td style='vertical-align: middle'>" + value.sap_number + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.asset_name + "</td>";
                        body += "<td style='vertical-align: middle'>" + value.location + "</td>";
                        body += "<td style='vertical-align: middle'><pre>";
                        body += "Availability   : " + (value.availability || '') + "<br>";
                        body += "Condition      : " + (value.asset_condition || '') + "<br>";
                        body += "Label          : " + (value.label_condition || '') + "<br>";
                        body += "Usable         : " + (value.usable_condition || '') + "<br>";
                        body += "Map            : " + (value.map_condition || '') + "<br>";
                        body += "Note           : " + (value.note || '') + "<br>";
                        body += "</pre></td>";
                        if (value.status == 'Must Audit') {
                            body += "<td style='vertical-align: middle'>Not Audited Yet</td>";
                        } else {
                            body += "<td style='vertical-align: middle'>" + value.status + "</td>";
                        }
                        body += "</tr>";
                        num++;
                    }
                })

                $("#body_details").append(body);

                $('#table_details').DataTable({
                    // "buttons": ["copy", "excel", "print"],
                    "paging": true,
                    "lengthChange": true,
                    "searching": false,
                    "ordering": true,
                    "info": true,
                    "autoWidth": false,
                    "responsive": true,
                    "bDestroy": true
                });
            }
        }

        function Inittable() {
            $("#container_table").empty();
            var table = "";

            table += "<table id='table_details' class='table table-bordered table-hover dataTable dtr-inline'>";
            table += "<thead><tr>";
            table += "<th>No</th>";
            table += "<th>Image</th>";
            table += "<th>SAP Number</th>";
            table += "<th>Asset Name</th>";
            table += "<th>Location</th>";
            table += "<th>Check Point</th>";
            table += "<th>Status</th>";
            table += "</tr></thead>";
            table += "<tbody id='body_details'></tbody>";
            table += "</table>";

            $("#container_table").append(table);
        }

        var Toast = Swal.mixin({
            toast: true,
            position: 'top-end',
            showConfirmButton: false,
            timer: 2000
        });
    </script>

@stop
