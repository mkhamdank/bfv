@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ url('css/dataTables.bootstrap4.min.css') }}" rel="stylesheet">
    <link rel="stylesheet" href="{{ url('css/buttons.dataTables.min.css') }}">
    <link rel="stylesheet" href="https://cdn.datatables.net/1.13.1/css/jquery.dataTables.min.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/fixedcolumns/4.2.1/css/fixedColumns.dataTables.min.css">

    <style type="text/css">
        table.table-condensed>thead>tr>th {
            color: black;
        }

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

        #bodyTableMaterial tr>td:hover {
            overflow: visible;
            background-color: lightgreen;
            font-weight: bold !important;
            cursor: pointer;
            font-size: 17px !important;
        }

        table.table-bordered {
            border: 1px solid lightgray;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black;
            vertical-align: middle;
            color: white;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid lightgray;
            vertical-align: middle;
        }

        table.table-bordered>tfoot>tr>th {
            border: 1px solid black;
            vertical-align: middle;
            background-color: rgb(126, 86, 134);
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

        th,
        td {
            white-space: nowrap;
        }

        div.dataTables_wrapper {
            margin: 0 auto;
        }

        .loading {
            display: none;
        }
    </style>
@stop
@section('header')
    <div class="page-breadcrumb" style="padding: 7px">
        <div class="row align-items-center">
            <div class="col-md-6 col-8 align-self-center">
                <h3 class="page-title mb-0 p-0">{{ $title }}<span class="text-purple"> {{ $vendor }}</span>
                </h3>
            </div>
        </div>
    </div>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid" style="padding: 7px;min-height: 100vh">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8;">
            <p style="position: absolute; color: White; top: 45%; left: 35%;">
                <span style="font-size: 40px">Please Wait...<i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>
        <input type="hidden" id="vendor_nickname" value="{{ $vendor_nickname }}">
        <div class="row" style="padding: 5px">
            <div class="col-sm-12 col-xs-12" style="margin-bottom: 2%;">
                <div class="col-xs-2 col-sm-2" style="display: inline-block; padding: 0px;">
                    <p style="text-align: left; padding-left: 15px; margin: 0px;">
                        Delivery Month :
                    </p>
                    <div class="col-xs-12 col-sm-12">
                        <input type="text" class="form-control datepicker" id="filterMonth" placeholder="Select Month"
                            onchange="fetchData()">
                    </div>
                </div>

                <button type="button" class="btn btn-primary pull-right" onclick="openModal()">
                    <i class="fa fa-upload"></i>&nbsp;&nbsp;Upload Plan Delivery
                </button>

            </div>
            <div class="col-sm-12 col-xs-12" style="text-align: center;">
                <div class="card">
                    <div class="card-body" style="padding: 0px">
                        <div class="col-md-12" style="padding: 10px; overflow-x: scroll;">
                            <table class="table table-bordered user-table no-wrap" id="tableMaterial">
                                <thead style="background-color: #3f50b5; color: white;" id="headTableMaterial">
                                    <th></th>
                                </thead>
                                <tbody id="bodyTableMaterial">
                                    <th></th>
                                </tbody>
                                <tfoot id="footTableMaterial">
                                    <th></th>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="uploadModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-md">
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" id="myModalLabel">
                        <center><b>Update Plan Delivery</b></center>
                    </h4>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-xs-12">
                            <div class="col-xs-12 col-sm-12" style="display: inline-block; padding: 0px;">
                                <p style="text-align: left; padding-left: 15px; margin: 0px; font-weight: bold;">
                                    Delivery Month :
                                </p>
                                <div class="col-xs-6 col-sm-6">
                                    <input type="text" class="form-control datepicker" id="uploadMonth"
                                        placeholder="Select Month">
                                </div>
                            </div>
                            <div class="col-xs-12 col-sm-12" style="display: inline-block; padding: 0px;">
                                <p style="text-align: left; padding-left: 15px; margin: 0px; font-weight: bold;">
                                    Plan Delivery Data :
                                </p>
                                <div class="col-xs-12 col-sm-12">
                                    <textarea class="form-control datepicker" id="uploadData" placeholder="Copy Data [GMC] [DATE(YYYY-MM-DD)] [PLAN]"
                                        cols="30" rows="10"></textarea>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <div class="col-xs-12">
                        <div class="col-xs-2 pull-right">
                            <button style="margin-right: 10px;" class="btn btn-success" onclick="uploadPlan()">
                                &nbsp;<i class="fa fa-check"></i>&nbsp;Save&nbsp;&nbsp;
                            </button>
                        </div>
                        <div class="col-xs-2 pull-right">
                            <button style="margin-left: 10px;" class="btn btn-danger pull-left" data-dismiss="modal"
                                onclick="closeModal()"><i class="fa fa-close"></i> Cancel</button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

@stop
@section('scripts')
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
    <script src="https://cdn.datatables.net/1.13.1/js/jquery.dataTables.min.js"></script>
    <script src="{{ url('js/dataTables.bootstrap4.min.js') }}"></script>
    <script src="{{ url('js/jquery.flot.min.js') }}"></script>
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/buttons.flash.min.js') }}"></script>
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <script src="{{ url('js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="https://cdn.datatables.net/fixedcolumns/4.2.1/js/dataTables.fixedColumns.min.js"></script>


    <script type="text/javascript">
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery(document).ready(function() {
            $('.select2').select2();
            fetchData();
            $('#filterMonth').datepicker({
                format: "yyyy-mm",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true
            });
            $('#uploadMonth').datepicker({
                format: "yyyy-mm",
                startView: "months",
                minViewMode: "months",
                autoclose: true,
                todayHighlight: true
            });
        });

        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');

        function openModal() {
            $('#uploadMonth').val('');
            $('#uploadData').val('');


            $("#uploadModal").modal('show');
        }

        function closeModal() {
            $("#uploadModal").modal('hide');
        }

        function uploadPlan() {
            $('#loading').show();

            if ($('#uploadMonth').val() == '' || $('#uploadData').val() == '') {
                openErrorGritter('Error!', 'Input All Data');
                return false;
            }

            var data = {
                month: $('#uploadMonth').val(),
                data: $('#uploadData').val(),
                vendor_nickname: $('#vendor_nickname').val(),
            }

            $.post('{{ url('input/plan_delivery_data') }}', data, function(result, status, xhr) {
                if (result.status) {

                    closeModal();
                    $('#loading').hide();

                } else {
                    $('#loading').hide();
                    openErrorGritter('Error!', result.message);
                }
            });

        }

        function fetchData() {
            $('#loading').show();

            var data = {
                month: $('#filterMonth').val(),
                vendor_nickname: $('#vendor_nickname').val(),
            }

            $.get('{{ url('fetch/plan_delivery_data') }}', data, function(result, status, xhr) {
                if (result.status) {

                    if ($('#filterMonth').val() != result.month) {
                        $('#filterMonth').val(result.month);
                    }

                    $('#tableMaterial').DataTable().clear();
                    $('#tableMaterial').DataTable().destroy();

                    $("#headTableMaterial").html('');
                    var headTable = '';
                    headTable += '<tr>';
                    headTable += '<th style="text-align: center;">Material</th>';
                    headTable += '<th style="text-align: center;">Description</th>';
                    for (let j = 0; j < result.calendars.length; j++) {
                        headTable += '<th style="text-align: center !important;">';
                        headTable += result.calendars[j].date_text + '</th>';
                    }
                    headTable += '</tr>';
                    $('#headTableMaterial').append(headTable);

                    $("#footTableMaterial").html('');
                    var footTable = '';
                    footTable += '<tr>';
                    footTable += '<th></th>';
                    footTable += '<th></th>';
                    for (let j = 0; j < result.calendars.length; j++) {
                        footTable += '<th></th>';
                    }
                    footTable += '</tr>';
                    $('#footTableMaterial').append(footTable);

                    $("#bodyTableMaterial").html('');
                    var bodyTable = '';
                    for (var i = 0; i < result.materials.length; i++) {
                        bodyTable += '<tr>';
                        bodyTable += '<td style="text-align: center; width: 15%;">';
                        bodyTable += result.materials[i].material_number + '</td>';

                        bodyTable += '<td style="text-align: left; width: 35%;">';
                        bodyTable += result.materials[i].material_description + '</td>';

                        for (let j = 0; j < result.calendars.length; j++) {
                            var if_filled = false;

                            var bgcolor = '';
                            if (result.calendars[j].remark == 'H') {
                                bgcolor = 'background-color: #ffccff;';
                            }

                            for (let k = 0; k < result.plan_deliveries.length; k++) {
                                if (result.plan_deliveries[k].material_number == result.materials[i]
                                    .material_number &&
                                    result.plan_deliveries[k].due_date == result.calendars[j].week_date
                                ) {
                                    bodyTable += '<td style="text-align: center !important; font-weight: bold; ' +
                                        bgcolor + '">';
                                    bodyTable += result.plan_deliveries[k].plan + '</td>';
                                    if_filled = true;
                                    break;
                                }
                            }

                            if (!if_filled) {
                                bodyTable += '<td style="text-align: center !important; ' + bgcolor + '">0</td>';
                            }
                        }


                        bodyTable += '</tr>';
                    }
                    $('#bodyTableMaterial').append(bodyTable);


                    $('#tableMaterial tfoot th').each(function() {
                        var title = $(this).text();
                        $(this).html(
                            '<input style="width: 80%; text-align: center;" type="text" placeholder="Search ' +
                            title +
                            '"/>');
                    });

                    var table = $('#tableMaterial').DataTable({
                        'dom': 'Bfrtip',
                        'responsive': true,
                        'lengthMenu': [
                            [10, 25, 50, -1],
                            ['10 rows', '25 rows', '50 rows', 'Show all']
                        ],
                        'buttons': {
                            buttons: [{
                                    extend: 'pageLength',
                                    className: 'btn btn-default',
                                },
                                {
                                    extend: 'copy',
                                    className: 'btn btn-success',
                                    text: '<i class="fa fa-copy"></i> Copy',
                                    exportOptions: {
                                        columns: ':not(.notexport)'
                                    }
                                },
                                {
                                    extend: 'excel',
                                    className: 'btn btn-info',
                                    text: '<i class="fa fa-file-excel-o"></i> Excel',
                                    exportOptions: {
                                        columns: ':not(.notexport)'
                                    }
                                },
                                {
                                    extend: 'print',
                                    className: 'btn btn-warning',
                                    text: '<i class="fa fa-print"></i> Print',
                                    exportOptions: {
                                        columns: ':not(.notexport)'
                                    }
                                }
                            ]
                        },
                        'paging': true,
                        'lengthChange': true,
                        'pageLength': 10,
                        'searching': true,
                        'ordering': true,
                        'order': [],
                        'info': true,
                        'autoWidth': true,
                        'sPaginationType': 'full_numbers',
                        'bJQueryUI': true,
                        'bAutoWidth': false,
                        'processing': true,
                        'columnDefs': [{
                            "targets": 2,
                            "className": "text-left",
                        }],
                        initComplete: function() {
                            this.api()
                                .columns([1])
                                .every(function(dd) {
                                    var column = this;
                                    var theadname = $("#tableMaterial th").eq([dd]).text();
                                    var select = $(
                                            '<select><option value="" style="font-size:11px;">All</option></select>'
                                        )
                                        .appendTo($(column.footer()).empty())
                                        .on('change', function() {
                                            var val = $.fn.dataTable.util.escapeRegex($(this)
                                                .val());

                                            column.search(val ? '^' + val + '$' : '', true,
                                                    false)
                                                .draw();
                                        });
                                    column
                                        .data()
                                        .unique()
                                        .sort()
                                        .each(function(d, j) {
                                            var vals = d;
                                            if ($("#tableMaterial th").eq([dd]).text() ==
                                                'Category') {
                                                vals = d.split(' ')[0];
                                            }
                                            select.append(
                                                '<option style="font-size:11px;" value="' +
                                                d + '">' + vals + '</option>');
                                        });
                                });
                        },
                    });

                    table.columns().every(function() {
                        var that = this;

                        $('input', this.footer()).on('keyup change', function() {
                            if (that.search() !== this.value) {
                                that
                                    .search(this.value)
                                    .draw();
                            }
                        });
                    })

                    $('#tableMaterial tfoot tr').appendTo('#tableMaterial thead');

                    // table = $('#tableMaterial').DataTable({
                    //     scrollX: true,
                    //     scrollCollapse: true,
                    //     paging: false,
                    //     fixedColumns: {
                    //         left: 2
                    //     },
                    // });

                    $('#loading').hide();
                } else {
                    $('#loading').hide();
                    openErrorGritter('Error!', result.message);
                }
            });
        }

        function openSuccessGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-success',
                image: '{{ url('images/image-screen.png') }}',
                sticky: false,
                time: '3000'
            });
        }

        function openErrorGritter(title, message) {
            jQuery.gritter.add({
                title: title,
                text: message,
                class_name: 'growl-danger',
                image: '{{ url('images/image-stop.png') }}',
                sticky: false,
                time: '3000'
            });
        }
    </script>
@endsection
