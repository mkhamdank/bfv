@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <style type="text/css">
        #loading,
        #error {
            display: none;
        }

        table.table-bordered>thead>tr>th {
            color: white;
            background-color: black;
        }

        table.table-bordered>tbody>tr>td {
            color: black;
            background-color: white;
        }

        #loading {
            display: none;
        }

        div.dataTables_filter {
            float: right !important;
            text-align: right;
        }
    </style>
@stop
@section('header')
@stop
@section('content')

    <meta name="csrf-token" content="{{ csrf_token() }}">
    <section class="content" style="padding: 10px">
        <div id="loading"
            style="margin: 0px; padding: 0px; position: fixed; right: 0px; top: 0px; width: 100%; height: 100%; background-color: rgb(0,191,255); z-index: 30001; opacity: 0.8; display:none">
            <p style="position: absolute; color: white; top: 45%; left: 35%;">
                <span style="font-size: 40px">Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-11">
                <input type="hidden" id="green">
                <h2>Daftar Form Diagnosa Molding</h2>
            </div>
            <div class="col-md-1">
                <button class="btn btn-success" id="btnCreateForm" data-toggle="modal" data-target="#modalCreateForm"><i class="fa fa-plus"></i> Buat Form</button>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <table class="table table-bordered table-striped dataTable dtr-inline" style="width: 100%; margin-bottom: 10px" id="tableMaster">
                            <thead>
                                <tr>
                                    <th style="width: 1%">No</th>
                                    <th style="width: 8%">Create Month</th>
                                    <th style="width: 10%">Form Number</th>
                                    <th style="width: 10%">FA Number</th>
                                    <th>Nama Molding</th>
                                    <th>Location</th>
                                    <th style="width: 12%">Form Produk</th>
                                    <th style="width: 12%">Form Molding</th>
                                    <th style="width: 10%">Form Evaluasi</th>
                                    <th style="width: 10%">Status</th>
                                    <th style="width: 10%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTableMaster">
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- buat modal -->
    <div class="modal fade modal-success" id="modalCreateForm" role="dialog">
        <div class="modal-dialog">
            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title" style="color: #28a745; font-weight: bold">Buat Form</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="createMolding">Pilih Molding</label>
                                <select class="form-control select2" id="createMolding" style="width: 100%" data-placeholder="Pilih Molding">
                                    <option value=""></option>
                                    @foreach ($moldings as $molding)
                                        <option value="{{ $molding->fixed_asset_number }}">{{ $molding->fixed_asset_number }} - {{ $molding->fixed_asset_name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" id="btnSaveForm"><i class="fa fa-check"></i> OK</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal"><i class="fa fa-times"></i> Close</button>
                </div>
            </div>
        </div>
    </div>

@endsection
@section('scripts')
    <script src="{{ url('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
    <script src="{{ url('js/buttons.flash.min.js') }}"></script>
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <script src="{{ url('js/buttons.html5.min.js') }}"></script>
    <script src="{{ url('js/buttons.print.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var check_point = [];
        var moldings = [];
        var parts = [];
        var part_status = [];
        var part_err = [];
        var num = 1;
        const compressedFiles = [];

        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");

            getData();

            $('.select2').select2({
                dropdownAutoWidth: true,
                allowClear: true,
                dropdownParent: $('#molding_select')
            });
        })

        $(function () {
           var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        })

        function getData() {
            $.get('{{ url('fetch/diagnose_molding/molding_form') }}', function(result, status, xhr) {
                if (result.status) {
                    $("#sidebar-toggle").click();
                    var tableData = "";
                    $.each(result.data, function(key, value) {
                        tableData += '<tr>';
                        tableData += '<td></td>';
                        tableData += '<td>' + value.month + '</td>';
                        tableData += '<td>' + value.form_number + '</td>';
                        tableData += '<td>' + value.fixed_asset_number + '</td>';
                        tableData += '<td>' + value.fixed_asset_name + '</td>';
                        tableData += '<td>' + value.vendor + '</td>';
                        if (!value.id_product_check) {
                            tableData +=
                                '<td><center>';
                                
                                if (value.form_product_id) {
                                    if(value.status != "Approval" && value.status != "Approved" && value.status != "Rejected" && value.status != "Holded" ) {
                                        tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_product_check/create') }}/' +
                                        value.form_number +
                                        '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit Form</a>';
                                    }
                                    tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_product_check/report') }}/' +
                                    value.form_number +
                                    '" class="btn btn-danger btn-sm" target="_blank"><i class="far fa-file-pdf"></i> Report</a>';

                                    tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_product_check/report_ng') }}/' +
                                    value.form_number +
                                    '" class="btn btn-danger btn-sm" target="_blank"><i class="far fa-file-pdf"></i> NG</a>';
                                } else {
                                    tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_product_check/create') }}/' +
                                    value.form_number +
                                    '" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Buat Form</a>';
                                }
                                tableData += '</center></td>';
                        } else {
                            tableData += '<td>Form</td>';
                        }

                        if (value.form_molding_id) {
                            tableData +=
                                '<td><center>';
                                if(value.status != "Approval" && value.status != "Approved" && value.status != "Rejected" && value.status != "Holded") {
                                    tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_molding_check/create') }}/' +
                                    value.form_number +
                                    '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit Form</a>';
                                }

                                tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_molding_check/report') }}/' +
                                    value.form_number +
                                    '" class="btn btn-danger btn-sm" target="_blank"><i class="far fa-file-pdf"></i> Report</a>';

                                tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_molding_check/report_ng') }}/' +
                                    value.form_number +
                                    '" class="btn btn-danger btn-sm" target="_blank"><i class="far fa-file-pdf"></i> NG</a>';
                                tableData += '</center></td>';
                        } else {
                            tableData +=
                                '<td><center>';
                                if(value.status != "Approval" && value.status != "Approved" && value.status != "Rejected" && value.status != "Holded") {
                                    tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/mold_molding_check/create') }}/' +
                                    value.form_number +
                                    '" class="btn btn-success btn-sm"><i class="fas fa-plus"></i> Buat Form</a></center>';
                                }
                                tableData += '</td>';
                        }

                        tableData += '<td><center>';
                        if(value.status != "Approval" && value.status != "Approved" && value.status != "Rejected" && value.status != "Holded") {
                            tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/evaluation/edit') }}/' +
                                        value.form_number +
                                        '" class="btn btn-primary btn-sm"><i class="fas fa-edit"></i> Edit Form</a><br>';
                        }

                        tableData += '<a style="margin-bottom: 5px; margin-right: 5px;" href="{{ url('index/diagnose_molding/evaluation/report') }}/' +
                                    value.form_number +
                                    '" class="btn btn-danger btn-sm" target="_blank"><i class="far fa-file-pdf"></i> Report</a></center>';
                        tableData += '</td>';
                        tableData += '<td style="text-align: center;">' + (value.status ? value.status : '-') + '</td>';
                        tableData += '<td style="text-align: center;">';
                        if(!value.status) {
                            tableData += '<button class="btn btn-success btn-sm" onclick="saveAndSend(\'' + value.form_number + '\')"><i class="fas fa-save"></i> Simpan & Kirim</button>';
                        } else if (value.status == 'Approval') {
                            tableData += '<button class="btn btn-primary btn-sm" onclick="resend(\'' + value.form_number + '\')"><i class="fas fa-paper-plane"></i> Resend</button>';
                        }

                        tableData += '</td>';
                        tableData += '</tr>';
                    });
                    $('#bodyTableMaster').html(tableData);

                    // Initialize DataTable
                    var table = $('#tableMaster').DataTable({
                        "buttons": [
                            'copy', 'excel',
                            {
                                text: 'Show All',
                                className: 'btn btn-info',
                                action: function (e, dt, node, config) {
                                    // dt.search('').columns().search('').draw();
                                    // $('#tableMaster thead input, #tableMaster thead select').val('');
                                    window.location.href = '{{ url("index/diagnose_molding/molding_form") }}';
                                }
                            }
                        ],
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "dom": '<"row mb-2"<"col-md-6"B><"col-md-6 text-end"f>>' +
                        '<"row"<"col-12"tr>>' +
                        '<"row mt-2"<"col-md-6"i><"col-md-6"p>>',
                        "paging": true,
                        "lengthChange": true,
                        "searching": true,
                        "ordering": true,
                        "order": [[2, 'desc']],
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                    });

                    table.on('order.dt search.dt draw.dt', function() {
                        let pageInfo = table.page.info();
                        table.column(0, {
                                search: 'applied',
                                order: 'applied',
                                page: 'current'
                            })
                            .nodes()
                            .each(function(cell, i) {
                                cell.innerHTML = i + 1 + pageInfo.start;
                            });
                    }).draw();

                    if('{{ $asset_number }}' != null) {
                        var param = '{{ $asset_number }}';
                        if (param.includes("MLD")) {
                            table.column(2).search('{{ $asset_number }}').draw();
                        } else {
                            table.column(3).search('{{ $asset_number }}').draw();
                        }
                    }

                }
            })
        }

        function fetchDetailRecord() {
            $('#loading').show();
            var date_from = $('#date_from').val();
            var date_to = $('#date_to').val();
            var moldings = $('#molding_select').val();
            var pics = $('#molding_select').val();

            var data = {
                date_from: date_from,
                date_to: date_to,
                moldings: moldings
            }
            $.get('{{ url('fetch/workshop/check_molding_vendor/record') }}', data, function(result, status, xhr) {
                if (result.status) {
                    $('#bodyTableDetail').empty();
                    var tableData = "";

                    $.each(result.datas, function(key, value) {
                        tableData += '<tr>';
                        tableData += '<td>' + value.id + '</td>';
                        tableData += '<td>' + value.check_date + '</td>';
                        tableData += '<td>' + value.molding_name + '</td>';

                        var nama = value.pic;
                        $.each(result.employees, function(key2, value2) {
                            if (value.pic == value2.employee_id) {
                                nama = value2.name
                            }
                        })

                        tableData += '<td>' + nama + '</td>';
                        tableData += '<td>' + value.point_check + '</td>';
                        tableData +=
                            '<td><img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_before1 + '" alt="">';
                        tableData +=
                            '<img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_before2 + '" alt=""></td>';

                        tableData +=
                            '<td><img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_after1 + '" alt="">';
                        tableData +=
                            '<img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_after2 + '" alt=""></td>';

                        tableData +=
                            '<td><img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_activity1 + '" alt="">';
                        tableData +=
                            '<img style="max-width: 90px; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/check_att') }}/' +
                            value.photo_activity2 + '" alt=""></td>';

                        tableData += '<td>' + value.judgement + '</td>';
                        tableData += '<td>' + value.status + '</td>';
                        tableData += '</tr>';
                    });

                    $('#bodyTableDetail').append(tableData);
                    $('#loading').hide();
                } else {
                    $('#loading').hide();
                    toastr.error(result.message)
                }
            });
        }

        // function readURL(input, idfile) {
        //     const files = input.files;

        //     // const files = input.target.files;
        //     const imagePreview = $('#imagePreview');

        //     // Clear any previous previews
        //     imagePreview.empty();

        //     for (let i = 0; i < files.length; i++) {
        //         const file = files[i];
        //         const option = {
        //             quality: 0.7,
        //             maxWidth: 800,
        //             maxHeight: 600,
        //         };
        //         compressImage(file, option)
        //             .then(function(compressedFile) {
        //                 // compressedFiles.push(compressedFile);
        //                 var img = $(input).closest("td").find("." + idfile);
        //                 $(img).show();
        //                 $(img).attr('src', compressedFile);
        //                 console.log(compressedFile);
        //             })
        //             .catch(function(error) {
        //                 console.log(error.message);
        //             });
        //     }
        // }

        function readURL(input, idfile) {
            if (input.files && input.files[0]) {
                quality = 60;
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = $(input).parent().find("." + idfile);
                    $(img).show();

                    // Create a new image element
                    var tempImage = new Image();

                    tempImage.onload = function() {
                        // Create a canvas element
                        var canvas = document.createElement('canvas');
                        var ctx = canvas.getContext('2d');

                        // Set the canvas dimensions to the image dimensions
                        canvas.width = tempImage.width;
                        canvas.height = tempImage.height;

                        // Draw the image on the canvas
                        ctx.drawImage(tempImage, 0, 0);

                        // Get the compressed data URL
                        var compressedDataUrl = canvas.toDataURL('image/jpeg', quality);

                        // Set the source of the img element to the compressed data URL
                        $(img).attr('src', compressedDataUrl);
                    };

                    // Set the source of the temporary image to the FileReader result
                    tempImage.src = e.target.result;
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function readURL2(input, idfile) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();

                reader.onload = function(e) {
                    var img = $(input).parent().find("." + idfile);
                    $(img).show();
                    $(img).attr('src', e.target.result);
                };

                reader.readAsDataURL(input.files[0]);
            }
        }

        function cariTemuan() {
            // tableMasalah
            $("#bodyMasalah").empty();
            body = '';

            var data = {
                date_from: $("#riwayat_dari").val(),
                date_to: $("#riwayat_sampai").val()
            }

            $.get('{{ url('fetch/workshop/check_molding_vendor/temuan') }}', data, function(result, status, xhr) {
                if (result.status) {
                    $.each(result.datas, function(index, value) {
                        body += "<tr>";
                        body += "<td>" + (index + 1) + "</td>";
                        body += "<td>" + value.check_date + "</td>";
                        body += "<td>" + value.molding_name + "</td>";
                        body += "<td>" + value.part_name + "</td>";
                        body += "<td>" + value.problem + "<br>";

                        if (value.problem_att) {
                            var problem_att = value.problem_att.split(",");
                            body +=
                                '<img style="max-width: 50%; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/problem_att') }}/' +
                                problem_att[0] + '" alt="">';
                            body +=
                                '<img style="max-width: 50%; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/problem_att') }}/' +
                                problem_att[1] + '" alt="">';
                        }

                        body += "</td>";
                        body += "<td>" + value.handling_temporary + "<br>";

                        if (value.handling_att) {
                            var handling_att = value.handling_att.split(",");
                            body +=
                                '<img style="max-width: 50%; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/problem_att') }}/' +
                                handling_att[0] + '" alt="">';
                            body +=
                                '<img style="max-width: 50%; margin-bottom: 20px" src="{{ url('workshop/Audit_Molding/Check_Molding/problem_att') }}/' +
                                handling_att[1] + '" alt="">';
                        }

                        body += "</td>";
                        body += "<td>" + (value.note_problem || "") + "</td>";

                        var label = '';

                        if (value.status == "Open")
                            label = 'badge-danger';
                        else if (value.status == "Temporary Close")
                            label = 'badge-warning';
                        else
                            label = 'badge-success';

                        body += "<td><center><span class='badge " + label + "'>" + value.status +
                            "</span></center></td>";
                        body += "</tr>";
                    })

                    $("#bodyMasalah").append(body);
                } else {
                    openErrorGritter('Error', result.message);
                }
            })
        }

        function saveAndSend(formNumber) {
            if(confirm('Apakah anda yakin ingin menyimpan dan mengirim form ini?')) {
                $.get('{{ url('save_and_send/diagnose_molding/molding_form') }}', {formNumber: formNumber}, function(result, status, xhr) {
                    if (result.status) {
                        toastr.success(result.message);
                        audio_success.play();
                    } else {
                        toastr.error(result.message);
                        audio_error.play();
                    }
                })
            }
        }

        function resend(formNumber) {
            if(confirm('Apakah anda yakin ingin mengirim ulang form ini?')) {
                $.get('{{ url('resend/diagnose_molding/molding_form') }}', {formNumber: formNumber}, function(result, status, xhr) {
                    if (result.status) {
                        toastr.success(result.message);
                        audio_success.play();
                    } else {
                        toastr.error(result.message);
                        audio_error.play();
                    }
                })
            }
        }

        $('#modalCreateForm').on('shown.bs.modal', function () {
            $('#createMolding').select2({
                dropdownParent: $('#modalCreateForm') // WAJIB untuk di dalam modal
            });
        });

        $('#btnSaveForm').click(function() {
            var asset_number = $('#createMolding').val();
            generate_form(asset_number);
            $('#modalCreateForm').modal('hide');
        });

        function generate_form(asset_number) {
            window.location.href = '{{ url('generate/diagnose_molding/mold_product_check/new') }}?asset_number='+asset_number;
        }

        // Handle the change event for the file input
        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
        var audio_success = new Audio('{{ url('sounds/success.mp3') }}');
    </script>
@endsection
