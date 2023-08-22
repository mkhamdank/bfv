@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <style type="text/css">
        .table>tbody>tr:hover {
            background-color: #7dfa8c !important;
        }

        table.table-bordered {
            border: 1px solid black;
            vertical-align: middle;
        }

        table.table-bordered>thead>tr>th {
            border: 1px solid black;
            vertical-align: middle;
        }

        table.table-bordered>tbody>tr>td {
            border: 1px solid black;
            vertical-align: middle;
            padding: 2px 5px 2px 5px;
        }

        input::-webkit-outer-spin-button,
        input::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type=number] {
            -moz-appearance: textfield;
        }

        .nmpd-grid {
            border: none;
            padding: 20px;
        }

        .nmpd-grid>tbody>tr>td {
            border: none;
        }

        #loading {
            display: none;
        }
    </style>
@endsection

@section('header')
    <section class="content-header">
        <div class="page-breadcrumb" style="padding: 7px">
            <div class="row align-items-center">
                <div class="col-md-6 col-8 align-self-center">
                    <h3 class="page-title mb-0 p-0">{{ $title }}<span class="text-purple"> {{ $vendor }}</span>
                    </h3>
                </div>
            </div>
        </div>
    </section>
@endsection

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content" style="font-size: 0.9vw;">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display: none">
            <p style="position: absolute; color: White; top: 45%; left: 45%;">
                <span style="font-size: 5vw;"><i class="fa fa-spin fa-circle-o-notch"></i></span>
            </p>
        </div>
        {{-- <input type="hidden" id="materials" value="{{$materials}}"> --}}

        <div class="container-fluid" style="padding: 7px;">
            <div class="row">
                <div class="col-sm-6 col-xs-6" style="text-align: center; padding-left: 15px">
                    <div class="card">
                        <div class="card-body" style="padding: 10px; text-align: left;">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Result Date :</p>
                                    <div class="col-xs-5 col-sm-5">
                                        <input type="text" class="form-control datepicker" id="createDate"
                                            placeholder="   Select Date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Material Number :</p>
                                    <div class="col-xs-9 col-sm-9">
                                        <select class="form-control select2" id="createMaterialNumber"
                                            data-placeholder="Select Material" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($materials as $material)
                                                <option
                                                    value="{{ $material->material_number }}||{{ $material->material_description }}">
                                                    {{ $material->material_number }} - {{ $material->material_description }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Issue Location :</p>
                                    <div class="col-xs-5 col-sm-5">
                                        <select class="form-control select2" id="createIssueLocation"
                                            data-placeholder="Select Issue Location" style="width: 100%;">
                                            <option value=""></option>
                                            @foreach ($storage_locations as $sl)
                                                <option value="{{ $sl->storage_location }}">{{ $sl->storage_location_name }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Quantity :</p>
                                    <div class="col-sm-4">
                                        <input type="number" class="form-control" placeholder="Enter Quantity"
                                            id="createQuantity">
                                    </div>
                                </div>
                            </form>
                            <button class="btn btn-primary pull-right" style="width: 30%;"
                                onclick="addResult('each')">Add</button>
                        </div>
                    </div>
                </div>
                <div class="col-sm-6 col-xs-6" style="text-align: center; padding-right: 15px">
                    <div class="card">
                        <div class="card-body" style="padding: 10px">
                            <form class="form-horizontal">
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Result Date :</p>
                                    <div class="col-xs-5 col-sm-5">
                                        <input type="text" class="form-control datepicker" id="bulkDate"
                                            placeholder="   Select Date">
                                    </div>
                                </div>
                                <div class="form-group">
                                    <p style="text-align: left; padding-left: 15px; margin: 0px;">Bulk Input :</p>
                                    <div class="col-xs-9 col-sm-9">
                                        <textarea class="form-control" rows="3" placeholder="Enter Data (ISSUE_LOCATION+MATERIAL+QTY)"
                                            id="bulkProductionResult"></textarea>
                                    </div>
                                </div>
                            </form>
                            <button class="btn btn-primary pull-right" style="width: 30%;"
                                onclick="addResult('bulk')">Add</button>
                        </div>
                    </div>
                </div>
            </div>
            <div class="row">
                <div class="col-sm-12 col-xs-12" style="text-align: center; padding: 15px">
                    <div class="card">
                        <div class="card-body" style="padding: 10px; text-align: left;">
                            <table id="tableResult" class="table table-bordered table-hover">
                                <thead style="background-color: #90ed7d;">
                                    <tr>
                                        <th style="width: 0.5%; text-align: center;">Date</th>
                                        <th style="width: 1%; text-align: center;">Issue Location</th>
                                        <th style="width: 1%; text-align: center;">Material</th>
                                        <th style="width: 5%; text-align: center;">Description</th>
                                        <th style="width: 1%; text-align: center;">Quantity</th>
                                    </tr>
                                </thead>
                                <tbody id="tableResultBody">
                                </tbody>
                            </table>
                            <br>
                            <center>
                                <span style="font-weight: bold; font-size: 1.5vw;" id="count_material">Count Material:
                                    0</span>
                                <br>
                                <button class="btn btn-success" onclick="confirmResult()"
                                    style="width: 40%; font-weight: bold; margin-top: 10px;">CONFIRM</button>
                            </center>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
@endsection
@section('scripts')
    {{-- <script src="{{ url('js/dataTables.buttons.min.js') }}"></script> --}}
    {{-- <script src="{{ url('js/buttons.flash.min.js') }}"></script> --}}
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    {{-- <script src="{{ url('js/buttons.html5.min.js') }}"></script> --}}
    {{-- <script src="{{ url('js/buttons.print.min.js') }}"></script> --}}
    <script src="{{ url('js/jquery.gritter.min.js') }}"></script>
    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        jQuery(document).ready(function() {
            $('#createDate').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('#bulkDate').datepicker({
                autoclose: true,
                format: "yyyy-mm-dd",
                todayHighlight: true
            });
            $('.select2').select2({
                allowClear: true
            });
        });

        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
        var audio_ok = new Audio('{{ url('sounds/sukses.mp3') }}');

        // var materials = $.parseJSON($('#materials').val());
        var vendor_nickname = "{{ $vendor_nickname }}";
        var results = [];
        var result_count = 0;

        function confirmResult() {
            if (confirm("Apakah anda yakin untuk menyimpan data hasil produksi ini?")) {
                $('#loading').show();

                var production_results = [];
                $.each(results, function(key, value) {
                    var date = $('#result_' + value).find('td').eq(0).text();
                    var location = $('#result_' + value).find('td').eq(1).text();
                    var material_number = $('#result_' + value).find('td').eq(2).text();
                    var material_description = $('#result_' + value).find('td').eq(3).text();
                    var quantity = $('#result_' + value).find('td').eq(4).text();

                    production_results.push({
                        vendor_nickname: vendor_nickname,
                        date: date,
                        location: location,
                        material_number: material_number,
                        material_description: material_description,
                        quantity: quantity
                    });
                });

                var data = {
                    production_results: production_results
                }

                $.post('{{ url('input/completion') }}', data, function(result, status, xhr) {
                    if (result.status) {
                        results = [];
                        result_count = 0;
                        $('#tableResultBody').html("");
                        $('#count_material').text('Count Material: 0');

                        audio_ok.play();
                        openSuccessGritter('Success!', result.message);
                        $('#loading').hide();
                    } else {
                        audio_error.play();
                        openErrorGritter('Error!', result.message);
                        $('#loading').hide();
                    }
                });
            }
        }

        function addResult(cat) {
            $('#loading').show();
            if (cat == 'bulk') {
                var date = $('#bulkDate').val();
                var rows = $('#bulkProductionResult').val().split('\n');

                if (date == "" || $('#bulkProductionResult').val() == "") {
                    audio_error.play();
                    openErrorGritter('Error!', 'All required field must be filled.');
                    $('#loading').hide();
                    return false;
                }

                var status = true;

                for (var i = 0; i < rows.length; i++) {
                    var col = rows[i].split('+');
                    var material = col[0];
                    var quantity = col[1];
                    var tableResultBody = "";

                    if (col[0].length == 7) {
                        var found = false;
                        $.each(materials, function(key, value) {
                            if (value.item_code == material) {
                                var data = {
                                    material_number: material,
                                    category: 'production_result',
                                    quantity: quantity
                                }
                                $.get('{{ url('fetch/ymes/inventory_check') }}', data, function(result, status,
                                    xhr) {
                                    if (result.status) {

                                    } else {
                                        results = [];
                                        result_count = 0;
                                        $('#tableResultBody').html("");
                                        audio_error.play();
                                        openErrorGritter('Error!',
                                            'Data tidak sesuai atau stock tidak mencukupi cek YMES 00-45'
                                        );
                                        $('#count_material').text('Count Material: ' + results.length);
                                        $('#loading').hide();
                                        return false;
                                    }
                                });

                                result_count += 1;
                                tableResultBody += '<tr id="result_' + result_count + '">';
                                tableResultBody += '<td style="text-align: right;">' + date + '</td>';
                                tableResultBody += '<td style="text-align: center;">' + value.item_code + '</td>';
                                tableResultBody += '<td style="text-align: left;">' + value.item_name + '</td>';
                                tableResultBody += '<td style="text-align: left;">' + value.unit_code + '</td>';
                                tableResultBody += '<td style="text-align: left;">' + value.issue_loc_code +
                                    '</td>';
                                tableResultBody += '<td style="text-align: left;">W' + value.mrp_ctrl + 'S10</td>';
                                tableResultBody += '<td style="text-align: right;">' + quantity + '</td>';
                                tableResultBody += '</tr>';
                                results.push(result_count);
                                found = true;
                                return false;
                            }
                        });
                        if (found == false) {
                            status = false;
                        }
                        if (quantity == 0) {
                            status = false;
                        }

                    }

                    $('#tableResultBody').append(tableResultBody);
                    $('#count_material').text('Count Material: ' + results.length);

                }

                if (status == false) {
                    results = [];
                    result_count = 0;
                    $('#tableResultBody').html("");
                    audio_error.play();
                    openErrorGritter('Error!', 'Error occured please check your data.');
                    $('#loading').hide();
                    return false;
                }

                audio_ok.play();
                openSuccessGritter('Success!', 'Material successfully added.');
                $('#loading').hide();

                // $('#createDate').val("");
                $('#createMaterialNumber').prop('selectedIndex', 0).change();
                $('#createQuantity').val("");
                $('#bulkDate').val("");
                $('#bulkProductionResult').val("");
                $('#loading').hide();
            }

            if (cat == 'each') {
                var date = $('#createDate').val();
                var material = $('#createMaterialNumber').val();
                var issue = $('#createIssueLocation').val();
                var quantity = $('#createQuantity').val();

                if (date == "" || material == "" || issue == "" || quantity == "") {
                    audio_error.play();
                    openErrorGritter('Error!', 'All required field must be filled.');
                    $('#loading').hide();
                    return false;
                }

                if (quantity == 0) {
                    audio_error.play();
                    openErrorGritter('Error!', 'Quantity must not 0.');
                    $('#loading').hide();
                    return false;
                }

                var data = {
                    material_number: material.split('||')[0],
                    issue: issue,
                    quantity: quantity
                }

                $.get('{{ url('fetch/completion/inventory_check') }}', data, function(result, status, xhr) {
                    if (result.status) {
                        var tableResultBody = "";
                        result_count += 1;
                        tableResultBody += '<tr id="result_' + result_count + '">';
                        tableResultBody += '<td style="text-align: center;">';
                        tableResultBody += date;
                        tableResultBody += '</td>';
                        tableResultBody += '<td style="text-align: left;">';
                        tableResultBody += issue;
                        tableResultBody += '</td>';
                        tableResultBody += '<td style="text-align: center;">';
                        tableResultBody += material.split('||')[0];
                        tableResultBody += '</td>';
                        tableResultBody += '<td style="text-align: left;">';
                        tableResultBody += material.split('||')[1];
                        tableResultBody += '</td>';
                        tableResultBody += '<td style="text-align: right;">';
                        tableResultBody += quantity;
                        tableResultBody += '</td>';
                        tableResultBody += '</tr>';

                        results.push(result_count);
                        $('#tableResultBody').append(tableResultBody);
                        $('#count_material').text('Count Material: ' + results.length);

                        $('#createDate').val("");
                        $('#createMaterialNumber').prop('selectedIndex', 0).change();
                        $('#createIssueLocation').prop('selectedIndex', 0).change();
                        $('#createQuantity').val("");

                        $('#bulkDate').val("");
                        $('#bulkProductionResult').val("");

                        $('#loading').hide();

                    } else {
                        audio_error.play();
                        openErrorGritter('Error!', 'Stock ' + result.bom.vendor_material_description + ' kurang');
                        $('#loading').hide();
                        return false;
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
