@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ url('css/bootstrap4.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <link href="{{ url('css/icheck-bootstrap.min.css') }}" rel="stylesheet">

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

        .radio {
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
        }

        /* Hide the browser's default radio button */
        .radio input {
            position: absolute;
            opacity: 0;
            cursor: pointer;
        }

        /* Create a custom radio button */
        .checkmark {
            position: absolute;
            top: 0;
            left: 0;
            height: 25px;
            width: 25px;
            background-color: #ccc;
            border-radius: 50%;
        }

        /* On mouse-over, add a grey background color */
        .radio:hover input~.checkmark {
            background-color: #ccc;
        }

        /* When the radio button is checked, add a blue background */
        .radio input:checked~.checkmark {
            background-color: #2196F3;
        }

        /* Create the indicator (the dot/circle - hidden when not checked) */
        .checkmark:after {
            content: "";
            position: absolute;
            display: none;
        }

        /* Show the indicator (dot/circle) when checked */
        .radio input:checked~.checkmark:after {
            display: block;
        }

        /* Style the indicator (dot/circle) */
        .radio .checkmark:after {
            top: 9px;
            left: 9px;
            width: 8px;
            height: 8px;
            border-radius: 50%;
            background: white;
        }

        #tableResult>tbody>tr>td {
            border: 1px solid #b0bec5;
        }

        hr {
            margin-top: 2px;
            margin-bottom: 2px;
            border-color: black;
        }

        #bodyTableMaster>tr>td {
            vertical-align: middle;
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
            <div class="col-md-6">
                <input type="hidden" id="green">
                <h2>Form Diagnosa Molding</h2>
            </div>
            <div class="col-md-6">
                <ol class="breadcrumb float-sm-right">
                    <li class="breadcrumb-item">
                        <button type="button" onclick="$('#modal_info').modal('show')" class="btn btn-info btn-sm">
                            <i class="fas fa-info-circle"></i> Standar Diagnosa
                        </button>
                    </li>
                </ol>
            </div>
        </div>

        <div class="container-fluid">
            <div class="card card-success color-palette-box">
            <div class="card-header">
                <h3 class="card-title" style="font-weight: bold"><i class="fas fa-info-circle"></i> Informasi Molding</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-7">
                        <table style="font-size: 1.2rem; width: 100%">
                            <tr>
                                <th>Form Number</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="form_number">{{ $form_number }}</td>
                            </tr>
                            <tr>
                                <th>Nama Molding</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td>{{ $molding_name->fixed_asset_name }}</td>
                            </tr>
                            <tr>
                                <th>Poin Produk</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="total_point" style="font-weight: bold; font-size: 40px;">100</td>
                            </tr>
                            <tr>
                                <th>Poin Molding</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="molding_point" style="font-weight: bold; font-size: 40px;">
                                    @if(isset($penilaian_molding))
                                        {{ $penilaian_molding->points }}
                                    @else
                                        -
                                    @endif
                                </td>
                            </tr>
                            <tr>
                                <th>Ranking</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="ranking" style="font-weight: bold; font-size: 40px;">C</td>
                            </tr>
                            <tr>
                                <th>Keputusan</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="keputusan">Sulit melanjutkan proses produksi. Perlu peremajaan.</td>
                            </tr>
                        </table>
                    </div>
                    <div class="col-md-5">
                        <div class="form-group d-flex align-items-center">
                            <div class="custom-file mr-2" style="flex: 1;">
                                <input type="file" class="custom-file-input" id="customFile" accept="image/*">
                                <label class="custom-file-label" for="customFile">Masukkan Foto Produk Terlebih Dahulu</label>
                            </div>
                            <button type="button" class="btn btn-success mr-2" onclick="uploadImage()"><i class="fas fa-upload"></i> Upload</button>
                        </div>
                        <div class="form-group">
                            <div>
                                <center>
                                    @if($molding_name->photo_product)
                                        <img src="{{ url('workshop/molding/photo_product/main/' . $molding_name->photo_product) }}" alt="Foto Produk" style="width: 100%; height: auto;" id="photo_product">
                                    @else
                                        <i class="fa fa-image" style="font-size: 100px; color: #ccc;"></i>
                                    @endif
                                </center>
                            </div> 
                        </div>
                    </div>
                </div>
            </div>
            <!-- /.card-body -->
            </div>
        </div>

        <div class="container-fluid" style="display: none;" id="form_diagnose_container">
            <div class="card card-primary color-palette-box">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight: bold"><i class="fas fa-info-circle"></i> Lembar Diagnosa Produk</h3>
                </div>
                <div class="card-body">
                    <div class="col-12">
                        <table width="90%">
                            <tr>
                                <th rowspan="3" width="15%">Standar Penilaian :</th>
                                <td width="15%">OK</td>
                                <td> 0 Poin</td>
                            </tr>
                            <tr style="border-top: 1px solid #dee2e6">
                                <td>NG</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #ffa3a3;">&nbsp;</button> : -10 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #6dcdf0;">&nbsp;</button> : -5 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #d4ffb0;">&nbsp;</button> : 0 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #e9a7fb;">&nbsp;</button> : Mengikuti Tabel Hasil Diagnosa</td>
                            </tr>
                            <tr style="border-top: 1px solid #dee2e6">
                                <td>OK Sementara</td>   
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #ffa3a3;">&nbsp;</button> : -3 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #6dcdf0;">&nbsp;</button> : -1 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #d4ffb0;">&nbsp;</button> : 0 Poin</td>
                                <td style="padding-top: 5px; padding-bottom: 5px;"><button class="btn btn-xs" style="background-color: #e9a7fb;">&nbsp;</button> : Mengikuti Tabel Hasil Diagnosa</td>
                            </tr>
                        </table>
                        <br>
                        <table class="table table-bordered" id="tableMaster">
                            <thead>
                                <tr style="text-align: center; background-color:rgb(147, 40, 255); color: white;">
                                    <th colspan="2">Jenis NG</th>
                                    <th>Hasil Diagnosa</th>
                                    <th width="10%">Pengurangan</th>
                                    <th width="5%">Cek</th>
                                    <th width="22%">Action</th>
                                </tr>
                            </thead>
                            <tbody id="bodyTableMaster">
                            </tbody>
                        </table>
                    </div>
                </div>
                <div class="card-footer">
                    <button type="button" class="btn btn-lg btn-warning float-left" id="btn_save_temp" onclick="saveProductCheckTemp()"><i class="fas fa-save"></i> Simpan Sementara</button>
                    <button type="button" class="btn btn-lg btn-success float-right" id="btn_save" onclick="saveProductCheck()"><i class="fas fa-check-double"></i> Simpan Sepenuhnya</button>
                </div>
            </div>
        </div>

    </section>

    <div class="modal fade" id="modal_info" tabindex="-1" role="dialog" aria-labelledby="modalInfoLabel" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <center>
                    <h5 class="modal-title" id="modalInfoLabel" style="font-weight: bold; color: red">
                        <i class="fas fa-bullhorn blinking"></i> Standar Diagnosa
                    </h5>
                </center>
                <style>
                    .blinking {
                        animation: blinkingIcon 1s infinite;
                    }
                    @keyframes blinkingIcon {
                        0% { opacity: 1; }
                        50% { opacity: 0; }
                        100% { opacity: 1; }
                    }
                </style>
                </div>
                <div class="modal-body">
                <table>
                    <thead>
                    <tr>
                        <th style="width: 20%">OK</th>
                        <th style="width: 20px">:</th>
                        <td>Yang tidak ada masalah. → Tidak ada masalah berdasarkan hasil keputusan QA.</td>
                    </tr>
                    <tr>
                        <th>NG</th>
                        <th>:</th>
                        <td>Ada masalah.</td>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">OK Sementara</th>
                        <th style="vertical-align: top;">:</th>
                        <td>Berdasarkan poin saat ini, QA memutuskan tidak ada masalah, tetapi kedepannya akan menjadi masalah pada saat melanjutkan produksi. <br> Ada penanganan lanjutan setelah dilakukan injection.</td>
                    </tr>
                    </thead>
                </table>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-success" data-bs-dismiss="modal" data-dismiss="modal"><i class="fas fa-check"></i> Mengerti</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_ng" tabindex="-1" role="dialog" aria-labelledby="modalNgLabel" data-bs-backdrop="static" data-bs-keyboard="false">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center" id="modalNgLabel" style="font-weight: bold; color: red">
                        Tambah Foto NG
                    </h5>
                </div>
                <div class="modal-body">
                <table width="100%">
                    <thead>
                    <tr>
                        <th style="width: 20%">Nama NG</th>
                        <th style="width: 30px">:</th>
                        <td id="nama_ng" colspan="2"></td>
                    </tr>
                    <tr>
                        <th style="width: 20%">Nomor</th>
                        <th>:</th>
                        <td id="nomor" colspan="2"></td>
                    </tr>
                    <tr>
                        <th rowspan="2" style="vertical-align: top;">Foto</th>
                        <th rowspan="2" style="vertical-align: top;">:</th>
                        <td style="width: 50%">
                            <input type="file" id="photo_file_1" class="form-control" style="display:none;" accept="image/*">
                            <button type="button" id="btn_photo_1" class="btn btn-primary btn-sm" onclick="document.getElementById('photo_file_1').click();"><i class="far fa-image"></i> Choose Image <span style="color: red">*</span></button>
                        </td>
                        <td style="width: 50%">
                            <input type="file" id="photo_file_2" class="form-control" style="display:none;" accept="image/*">
                            <button type="button" id="btn_photo_2" class="btn btn-primary btn-sm" onclick="document.getElementById('photo_file_2').click();"><i class="far fa-image"></i> Choose Image</button>
                        </td>
                    </tr>
                    <tr>
                        <td style="vertical-align: top;">
                            <img id="photo_ng_1" src="" alt="Photo NG" style="width: 200px; height: auto; display: none;">
                            <i class="fa fa-image" style="font-size: 100px; color: #ccc;" id="dummy_photo_1"></i>
                        </td>
                        <td style="vertical-align: top;">
                            <img id="photo_ng_2" src="" alt="Photo NG" style="width: 200px; height: auto; display: none;">
                            <i class="fa fa-image" style="font-size: 100px; color: #ccc;" id="dummy_photo_2"></i>
                        </td>
                    </tr>
                    </thead>
                </table>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal" data-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                    <button type="button" class="btn btn-success" onclick="savePhotoNg()"><i class="fas fa-check"></i> Simpan</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="modal_detail" tabindex="-1" role="dialog" aria-labelledby="modalDetailLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center" id="modalDetailLabel" style="font-weight: bold; color: #007bff">
                        Detail NG '<span id="nama_ng_detail"></span>'
                    </h5>

                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <table class="table table-bordered" id="table_detail" style="width: 100%">
                    <thead>
                    <tr>
                        <th style="width: 1%">No</th>
                        <th style="width: 10%">Tanggal</th>
                        <th style="width: 20%">Foto 1</th>
                        <th style="width: 20%">Foto 2</th>
                        <th style="width: 5%">Aksi</th>
                    </tr>
                    </thead>
                    <tbody id="tbody_detail">
                    </tbody>
                </table>
                </div>
            </div>
        </div>
    </div>
@endsection
@section('scripts')
    <script src="{{ url('js/bootstrap-toggle.min.js') }}"></script>
    <script src="{{ url('plugins/timepicker/bootstrap-timepicker.min.js') }}"></script>
    <!-- <script src="{{ url('js/dataTables.buttons.min.js') }}"></script> -->
    <!-- <script src="{{ url('js/buttons.flash.min.js') }}"></script> -->
    <script src="{{ url('js/jszip.min.js') }}"></script>
    <script src="{{ url('js/vfs_fonts.js') }}"></script>
    <!-- <script src="{{ url('js/buttons.html5.min.js') }}"></script> -->
    <!-- <script src="{{ url('js/buttons.print.min.js') }}"></script> -->
    <!-- <script src="{{ url('js/popper.min.js') }}"></script> -->
    <script src="{{ url('js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ url('js/sweetalert2.min.js') }}"></script>
    <script src="{{ url('js/toastr.min.js') }}"></script>
    <script src="https://adminlte.io/themes/v3/plugins/bs-custom-file-input/bs-custom-file-input.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        var master_check_list = {!! json_encode($master_check_list) !!};
        var ranks = {!! json_encode($ranks) !!};

        var molding_penilaian = {!! isset($penilaian_molding) ? json_encode($penilaian_molding->points) : '0' !!};

        console.log(molding_penilaian);
        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");

            @if($molding_name->photo_product)
                $('#form_diagnose_container').show();
                getData();
            @endif

            $("#modal_info").modal('show');

            $('.select2').select2({
                dropdownAutoWidth: true,
                allowClear: true,
                dropdownParent: $('#molding_select')
            });
        })

        $(function () {
            bsCustomFileInput.init();
        });

        $(function () {
           var Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000
            });
        })

        function getData() {
            var data = {
                form_number: $('#form_number').text(),
            };
            $.get('{{ url('fetch/diagnose_molding/product/check_list') }}', data, function(result, status, xhr) {
                if (result.status) {
                    var tableData = "";
                    $.each(result.master_check_list, function(key, value) {
                        var bgcolor = '';
                        var deduction_ng = 0;
                        var deduction_temp = 0;
                        if (value.grouping == 'Main' && value.category_check == 'OK/NG') {
                            bgcolor = 'background-color:rgb(255, 163, 163);';
                            deduction_ng = 10;
                            deduction_temp = 3;
                        } else if (value.grouping == 'Main' && value.category_check == 'Isi') {
                            bgcolor = 'background-color:rgb(233, 167, 251);';
                        } else if (value.grouping == 'Secondary') {
                            bgcolor = 'background-color:#6dcdf0;';
                            deduction_ng = 5;
                            deduction_temp = 1;
                        } else if (!value.grouping) {
                            bgcolor = 'background-color:rgb(212, 255, 176);';
                        }

                        tableData += '<tr style="' + bgcolor + '">';
                        tableData += '<td class="ng_id">' + value.id + '</td>';
                        tableData += '<td class="ng_name">' + value.item_ng + '</td>';
                        if (value.category_check == 'OK/NG') {
                            tableData += '<td>';
                            tableData += `<div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="r3_${value.id}" id="OK_${value.id}" value="0" onclick="changePengurangan(${value.id})" tag="OK">
                                    <label for="OK_${value.id}">OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="r3_${value.id}" id="NG_${value.id}" value="${deduction_ng}" onclick="changePengurangan(${value.id})" tag="NG">
                                    <label for="NG_${value.id}">NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="r3_${value.id}" id="OK_Sementara_${value.id}" value="${deduction_temp}" onclick="changePengurangan(${value.id})" tag="OK Sementara">
                                    <label for="OK_Sementara_${value.id}">OK Sementara</label>
                                </div>
                            </div>`;
                            tableData += '</td>';
                        } else {
                            tableData += '<td>';
                            tableData += `<div class="form-group clearfix">
                                <table style="width: 100%;">
                                    <tr>
                                        <td style="border: none;">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r3_${value.id}" id="10_${value.id}" value="10" onclick="changePengurangan(${value.id})" tag="1 ~ 10">
                                                <label for="10_${value.id}">1 ~ 10 : -10 Poin</label>
                                            </div>
                                        </td>
                                        <td style="border: none;">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r3_${value.id}" id="20_${value.id}" value="20" onclick="changePengurangan(${value.id})" tag="11 ~ 30">
                                                <label for="20_${value.id}">11 ~ 30 : -20 Poin</label>
                                            </div>
                                        </td>
                                    </tr>
                                    <tr>
                                        <td style="border: none;">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r3_${value.id}" id="30_${value.id}" value="30" onclick="changePengurangan(${value.id})" tag="31 ~ 50">
                                                <label for="30_${value.id}">31 ~ 50 : -30 Poin</label>
                                            </div>
                                        </td>
                                        <td style="border: none;">
                                            <div class="icheck-success d-inline">
                                                <input type="radio" name="r3_${value.id}" id="50_${value.id}" value="50" onclick="changePengurangan(${value.id})" tag="> 51">
                                                <label for="50_${value.id}">> 51 : -50 Poin</label>
                                            </div>
                                        </td>
                                    </tr>
                                </table>
                            </div>`;
                            tableData += '</td>';
                        }
                        tableData += '<td id="pengurangan_' + value.id + '" style="text-align: center; font-weight: bold; font-size: 22px;" class="pengurangan">0</td>';
                        tableData += '<td id="cek_' + value.id + '" style="text-align: center; font-weight: bold; font-size: 22px; color: #ddd;" class="cek"><i class="fas fa-check"></i></td>';
                        tableData += '<td><center>';
                        tableData += '<button class="btn btn-success btn-sm btn_ng" onclick="buatNG(' + value.id + ', \'' + value.item_ng + '\')" style="margin-right: 3px;"><i class="fas fa-plus"></i> Tambah Foto NG</button>';
                        tableData += '<button class="btn btn-primary btn-sm" onclick="modalDetail(' + value.id + ', \'' + value.item_ng + '\')"><i class="fas fa-info"></i> Lihat Detail</button>';
                        tableData += '</center></td>';
                        tableData += '</tr>';
                    });

                    tableData += '<tr>';
                    tableData += '<td colspan="3" style="text-align: right; font-weight: bold; font-size: 22px;">Total Pengurangan</td>';
                    tableData += '<td id="total_pengurangan" style="text-align: center; font-weight: bold; font-size: 20px;">0</td>';
                    tableData += '<td style="text-align: center; font-weight: bold; font-size: 24px;" colspan="2">Total Point : <span id="total_points">100</span></td>';
                    tableData += '</tr>';
                    $('#bodyTableMaster').html(tableData);

                    var status_form = true;

                    if(result.actual_check_list.length > 0) {
                        if(result.actual_check_list[0].status == 'Closed') {
                            status_form = false;
                        }

                        $.each(result.actual_check_list, function(key, value) {
                            if(value.diagnose_result == 'OK') {
                                $('input[name="r3_' + value.ng_id + '"][value="' + value.actual_deduction + '"]').prop('checked', true);
                                changePengurangan(value.ng_id);
                            } else if(value.diagnose_result == 'NG') {
                                $('input[name="r3_' + value.ng_id + '"][value="' + value.actual_deduction + '"]').prop('checked', true);
                                changePengurangan(value.ng_id);
                            } else if(value.diagnose_result == 'OK Sementara') {
                                $('input[name="r3_' + value.ng_id + '"][value="' + value.actual_deduction + '"]').prop('checked', true);
                                changePengurangan(value.ng_id);
                            } else if (value.ng_id == 1) {
                                $('input[name="r3_' + value.ng_id + '"][value="' + value.actual_deduction + '"]').prop('checked', true);
                                changePengurangan(value.ng_id);
                            }
                        });
                    }

                    if(!status_form) {
                        $.each(result.master_check_list, function(key, value) {
                            $('input[name="r3_' + value.id + '"]').prop('disabled', true);
                        });

                        $('.btn_ng').prop('disabled', true);
                        $('#btn_save_temp').prop('disabled', true);
                        $('#btn_save').prop('disabled', true);
                    }

                    if($("#molding_point").text() != "") {
                        //get the smallest number
                        var smallest = Math.min(parseInt($("#total_point").text()), parseInt($("#molding_point").text()));

                        // console.log($("#total_produk_point").text());
                        
                        $.each(ranks, function(key, value) {
                            if(smallest >= value.point) {
                                $("#ranking").text(value.rank);
                                $("#keputusan").text(value.keputusan);
                                return false;
                            }
                        })

                    }
                }
            })
        }

        function changePengurangan(id) {
            var value = $('input[name="r3_' + id + '"]:checked').val();
            if (value == 0) {
                $('#pengurangan_' + id).text(value);
            } else {
                $('#pengurangan_' + id).text("-" + value);
            }

            var total = 0;
            $('.pengurangan').each(function() {
                total += Math.abs(parseInt($(this).text())) || 0;
            });
            $('#total_point').text((100 - total));

            if(total > 0) {
                $('#total_pengurangan').text("-" + total);
            } else {
                $('#total_pengurangan').text(total);
            }
            $('#total_points').text((100 - total));

            $('#cek_' + id).css('color', '#3a9e54');

            if($("#molding_point").text() != "") {
                //get the smallest number
                var smallest = Math.min(parseInt($("#total_point").text()), parseInt($("#molding_point").text()));

                // console.log($("#total_produk_point").text());
                
                $.each(ranks, function(key, value) {
                    if(smallest >= value.point) {
                        $("#ranking").text(value.rank);
                        $("#keputusan").text(value.keputusan);
                        return false;
                    }
                })

            }
        }

        function uploadImage() {
            // if file not selected
            if ($('#customFile')[0].files.length == 0) {
                toastr.error('Mohon pilih file terlebih dahulu')
                audio_error.play();

                return;
            }

            var formData = new FormData();
            formData.append('image', $('#customFile')[0].files[0]);
            formData.append('form_number', $('#form_number').text());
            $.ajax({
                url: '{{ url("upload/diagnose_molding/product_image") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.status) {
                        toastr.success(result.message);
                        $('#customFile').val('');
                        $('#photo_product').attr('src', '{{ asset("workshop/molding/photo_product/main/") }}' + result.image);
                        $('#form_diagnose_container').show();
                    } else {
                        toastr.error(result.message)
                        audio_error.play();
                    }
                },
                error: function(result) {
                    toastr.error(result.message)
                    audio_error.play();
                }
            })
        }

        function buatNG(id, nama_ng) {
            $('#modal_ng').modal('show');
            $('#nomor').text(id);
            $('#nama_ng').text(nama_ng);
        }

        function savePhotoNg() {
            if ($('#photo_file_1')[0].files[0] == null) {
                toastr.error('Mohon input foto dengan tanda (*) terlebih dahulu');
                return;
            }

            var formData = new FormData();
            formData.append('nomor', $('#nomor').text());
            formData.append('nama_ng', $('#nama_ng').text());
            formData.append('photo_file_1', $('#photo_file_1')[0].files[0]);
            if ($('#photo_file_2')[0].files[0]) {
                formData.append('photo_file_2', $('#photo_file_2')[0].files[0]);
            }
            formData.append('form_number', $('#form_number').text());
            $.ajax({
                url: '{{ url("upload/diagnose_molding/photo_ng") }}',
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                success: function(result) {
                    if (result.status) {
                        toastr.success(result.message);
                        $('#modal_ng').modal('hide');

                        $('#photo_file_1').val('');
                        $('#photo_file_2').val('');

                        $('#dummy_photo_1').show();
                        $('#photo_ng_1').hide();
                        $('#photo_ng_1').attr('src', '');

                        if (result.image2) {
                            $('#dummy_photo_2').show();
                            $('#photo_ng_2').hide();
                            $('#photo_ng_2').attr('src', '');
                        }
                    } else {
                        toastr.error(result.message)
                        audio_error.play();
                    }
                },
                error: function(result) {
                    toastr.error(result.message)
                    audio_error.play();
                }
            })
        }

        function selectPhoto(id) {
            var file = $('#photo_file_' + id)[0].files[0];
            var reader = new FileReader();
            reader.onloadend = function() {
                $('#photo_ng_' + id).attr('src', reader.result);
            };
            if (file) {
                reader.readAsDataURL(file);
            }
            $('#photo_ng_' + id).show();
            $('#dummy_photo_' + id).hide();
        }

        //onchange photo_file_1
        $('#photo_file_1').on('change', function() {
            selectPhoto(1);
        });

        //onchange photo_file_2
        $('#photo_file_2').on('change', function() {
            selectPhoto(2);
        });

        function modalDetail(id, nama_ng) {
            var data = {
                id: id,
                form_number: $('#form_number').text()
            }
            $('#tbody_detail').html('');

            $.get('{{ url('fetch/diagnose_molding/product_details') }}', data, function(result, status, xhr){
                if(result.status){
                    var tableData = '';

                    if(result.product_ng.length > 0){
                        $.each(result.product_ng, function(key, value) {
                            tableData += '<tr>';
                            tableData += '<td>' + value.id + '</td>';
                            tableData += '<td>' + value.check_at.replace(/ /g, '<br>') + '</td>';
                            tableData += '<td><img src="{{ asset('workshop/molding/photo_product/ng/') }}/' + value.photo1 + '" alt="Photo 1" style="width: 200px; height: auto;"></td>';
                            if (value.photo2){
                                tableData += '<td><img src="{{ asset('workshop/molding/photo_product/ng/') }}/' + value.photo2 + '" alt="Photo 2" style="width: 200px; height: auto;"></td>';
                            } else {
                                tableData += '<td></td>';
                            }
                            tableData += '<td><button class="btn btn-danger btn-sm" onclick="deleteProductNg(' + value.id + ')"><i class="fas fa-trash"></i> Hapus</button></td>';
                            tableData += '</tr>';
                        });
                    }else{
                        tableData += '<tr><td colspan="5" style="text-align: center; color: #a5a4a4;">Tidak ada data</td></tr>';
                    }
                    $('#tbody_detail').html(tableData);
                }else{
                    toastr.error(result.message);
                    audio_error.play();
                }
            });
            
            $("#modal_detail").modal('show');
            $("#nama_ng_detail").text(nama_ng);
        }

        function deleteProductNg(id) {
            if (confirm('Yakin ingin menghapus data ini?')) {
                $.ajax({
                    url: '{{ url('delete/diagnose_molding/product_ng') }}',
                    type: 'POST',
                    data: {
                        id: id,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(result) {
                        if (result.status) {
                            toastr.success(result.message);
                            audio_success.play();
                            $('#modal_detail').modal('hide');
                        } else {
                            toastr.error(result.message);
                            audio_error.play();
                        }
                    },
                    error: function(result) {
                        toastr.error(result.message);
                    }
                })
            }
        }

        function saveProductCheckTemp() {
            if(confirm('Yakin ingin menyimpan data ini?')) {
                var ng_id = [];
                var ng_name = [];
                var ng_value_name = [];
                var ng_value = [];

                $('.ng_id').each(function() {
                    var ids = $(this).text();
                    $("input[name='r3_" + ids + "']:checked").each(function() {
                        ng_id.push(ids);
                        ng_value.push($(this).val());
                        ng_value_name.push($(this).attr('tag'));
                    });
                });

                $('.ng_name').each(function() {
                    ng_name.push($(this).text());
                });
                
                var data = {
                    form_number: $('#form_number').text(),
                    total_point: $('#total_points').text(),
                    ng_id: ng_id,
                    ng_name: ng_name,
                    ng_value: ng_value,
                    ng_value_name: ng_value_name,
                };

                // console.log(data);

                $.ajax({
                    url: '{{ url('save/diagnose_molding/product_check') }}',
                    type: 'POST',
                    data: data,
                    success: function(result) {
                        if (result.status) {
                            toastr.success(result.message);
                            audio_success.play();
                        } else {
                            toastr.error(result.message);
                            audio_error.play();
                        }
                    },
                    error: function(result) {
                        toastr.error(result.message);
                        audio_error.play();
                    }
                })
            }
        }

        function saveProductCheck() {
            // get all element with class cek
            var cek = $('.cek');
            var belum_cek = 0;

            $.each(cek, function(key, value) {
                if($(value).css('color') == 'rgb(221, 221, 221)') {
                    belum_cek++;
                }
            })
            
            if(belum_cek > 0) {
                toastr.error('Harap cek semua item');
                audio_error.play();
                return;
            }
            
            if(confirm('Yakin ingin menyimpan data ini?')) {
                var ng_id = [];
                var ng_name = [];
                var ng_value_name = [];
                var ng_value = [];

                $('.ng_id').each(function() {
                    var ids = $(this).text();
                    $("input[name='r3_" + ids + "']:checked").each(function() {
                        ng_id.push(ids);
                        ng_value.push($(this).val());
                        ng_value_name.push($(this).attr('tag'));
                    });
                });

                $('.ng_name').each(function() {
                    ng_name.push($(this).text());
                });
                
                var data = {
                    form_number: $('#form_number').text(),
                    total_point: $('#total_points').text(),
                    ng_id: ng_id,
                    ng_name: ng_name,
                    ng_value: ng_value,
                    ng_value_name: ng_value_name,
                };

                $.ajax({
                    url: '{{ url("save/diagnose_molding/product_check_real") }}',
                    type: 'POST',
                    data: data,
                    success: function(result) {
                        if (result.status) {
                            toastr.success(result.message);
                            audio_success.play();
                        } else {
                            toastr.error(result.message);
                            audio_error.play();
                        }
                    },
                    error: function(result) {
                        toastr.error(result.message);
                        audio_error.play();
                    }
                })
            }
            
        }
        
        // Handle the change event for the file input
        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
        var audio_success = new Audio('{{ url("sounds/success.mp3") }}');
    </script>
@endsection
