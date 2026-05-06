
@extends('layouts.master')
@section('header')
    
@endsection
@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="{{ url('css/bootstrap4.min.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<meta name="csrf-token" content="{{ csrf_token() }}">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://bossanova.uk/jspreadsheet/v5/jspreadsheet.js"></script>
<script src="https://jsuites.net/v5/jsuites.js"></script>
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v5/jspreadsheet.css" type="text/css" />
<link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css" type="text/css" />

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

        #tableResult>thead>tr>th {
            border: 1px solid black;
        }

        #tableResult>tbody>tr>td {
            border: 1px solid #b0bec5;
        }

        hr {
            margin-top: 2px;
            margin-bottom: 2px;
            border-color: black;
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
                <span style="font-size: 40px"> Loading, Please Wait . . . <i class="fa fa-spin fa-refresh"></i></span>
            </p>
        </div>

        <div class="row">
            <div class="col-md-10">
                <input type="hidden" id="green">
                <h2>Daftar Molding</h2>
            </div>
            <!-- <div class="col-md-2">
                <button class="btn btn-primary" style="width: 100%;" onclick="openModal('modal_history')"><i
                        class="fa fa-book"></i> Riwayat Pengecekan</button>
            </div> -->
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                    <table class="table table-bordered" style="width: 100%; margin-bottom: 10px" id="tableMaster">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>FA Number</th>
                                <th>Nama Molding</th>
                                <th>Lokasi</th>
                                <th>Standard Shot</th>
                                <th>Total Shot</th>
                                <th>Status</th>
                                <th>Action</th>
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

    <div class="modal fade" id="modal_shot" tabindex="-1" role="dialog" aria-labelledby="modalShotLabel">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                <center>
                    <h5 class="modal-title" id="modalShotLabel" style="font-weight: bold;">
                         Riwayat Shot Molding
                    </h5>
                </center>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Fixed Asset Number</label>
                                <input type="text" class="form-control" id="fa_number" readonly>
                            </div>
                        </div>
                        <div class="col-md-9">
                            <div class="form-group">
                                <label>Nama Molding</label>
                                <input type="text" class="form-control" id="nama_molding" readonly>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-3">
                            <div class="form-group">
                                <label>Period</label>
                                <div class="input-group mb-3">
                                    <div class="input-group-prepend">
                                        <span class="input-group-text"><i class="fas fa-calendar"></i></span>
                                    </div>
                                    <input type="text" class="form-control" id="period" placeholder="Periode" style="text-align: center" readonly value="{{ date('Y-m') }}">
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label>Jumlah Shot</label>
                                <input type="text" class="form-control" id="shot" placeholder="Masukkan Jumlah Shot">
                            </div>
                        </div>
                        <div class="col-md-3">
                            <label>&nbsp;</label><br>
                            <button type="button" class="btn btn-primary" style="width: 100%;" onclick="updateShot()"><i class="fas fa-file-export"></i> Update Shot</button>
                        </div>
                    </div>

                    <table class="table table-bordered" style="width: 100%; margin-bottom: 10px" id="tableShot">
                        <thead>
                        <tr>
                            <th style="width: 2%">No</th>
                            <th style="width: 10%">Tanggal</th>
                            <th style="width: 7%">Jumlah Shot</th>
                            <th style="width: 7%">Total Shot</th>
                            <th style="width: 7%">Input By</th>
                            <th style="width: 5%">Action</th>
                        </tr>
                        </thead>
                        <tbody id="tbodyShot">
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- buat modalKerusakan -->
    <div class="modal fade" id="modalKerusakan" tabindex="-1" role="dialog" aria-labelledby="modalKerusakanLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document" style="max-width: 80%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalKerusakanLabel">Tambah Kerusakan</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-4">
                        <div class="form-group">
                            <label>Asset Number</label>
                            <input type="text" class="form-control" id="fa_number_kerusakan" readonly>
                        </div>
                        </div>
                        <div class="col-md-8">
                        <div class="form-group">
                            <label>Nama Molding</label>
                            <input type="text" class="form-control" id="nama_molding_kerusakan" readonly>
                        </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: bold;">▷ Request Repair Mold</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Tgl Kejadian (Tgl Request)</label>
                            <input type="text" class="form-control" id="tanggal_kerusakan">
                        </div>
                        <div class="form-group">
                            <label>Request Due Date</label>
                            <input type="text" class="form-control" id="tanggal_target_kerusakan">
                        </div>

                        <div class="form-group">
                            <label>Jumlah Shot</label>
                            <input type="text" class="form-control" id="jml_shot_kerusakan">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group">
                            <label>Gejala / Kondisi</label>
                            <textarea class="form-control" name="gejala" id="gejala" rows="8" placeholder="Tulis secara ringkas"></textarea>
                        </div>
                    </div>
                    <div class="col-md-3">
                        <div class="form-group">
                            <label>Foto atau Posisi Kerusakan</label>
                            <input type="file" class="form-control" id="photo" accept="image/*" multiple>
                        </div>
                    </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: bold;">▷ Peninjauan Metode Repair (Dari sudut pandang QCD)</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Metode Repair</label>
                                <div id="metode_repair" style="width: 100%;"></div>
                                <!-- <textarea class="form-control" id="metode_repair_1"></textarea> -->
                            </div>
                        </div>

                        <!-- <div class="col-md-12">
                            <div class="form-group">
                                <label>Metode Repair 2</label>
                                <textarea class="form-control" id="metode_repair_2"></textarea>
                            </div>
                        </div>

                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Metode Repair 3</label>
                                <textarea class="form-control" id="metode_repair_3"></textarea>
                            </div>
                        </div> -->
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: bold;">▷ Penentuan Metode Repair</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Metode yang dipilih</label>
                                <input type="text" class="form-control" name="metode_dipilih" id="metode_dipilih" placeholder="Metode yang dipilih">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Alasan pemilihan metode</label>
                                <input type="text" class="form-control" name="alasan_pemilihan" id="alasan_pemilihan" placeholder="Alasan pemilihan metode">
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: bold;">▷ Pelaksanaan Repair Mold</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Sebelum Repair</label>
                                <textarea name="sebelum_repair" id="sebelum_repair" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label>Setelah Repair</label>
                                <textarea name="setelah_repair" id="setelah_repair" class="form-control"></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-12">
                            <div class="form-group">
                                <label style="font-weight: bold;">▷ Pemastian Keabsahan Repair Mold</label>
                            </div>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: bold;">※ Pemastian Mold</label>
                            </div>

                            <div class="form-group">
                                <label>1. Apakah bentuk pada posisi repair OK ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_1" id="pemastian_mold_1_OK" value="OK"
                                    <label for="pemastian_mold_1_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_1" id="pemastian_mold_1_NG" value="NG">
                                    <label for="pemastian_mold_1_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_1" id="pemastian_mold_1_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_mold_1_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>2. Apakah dimensi pada posisi repair 0K ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_2" id="pemastian_mold_2_OK" value="OK">
                                    <label for="pemastian_mold_2_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_2" id="pemastian_mold_2_NG" value="NG">
                                    <label for="pemastian_mold_2_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_2" id="pemastian_mold_2_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_mold_2_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>3. Apakah fungsi pada posisi repair 0K ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_3" id="pemastian_mold_3_OK" value="OK">
                                    <label for="pemastian_mold_3_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_3" id="pemastian_mold_3_NG" value="NG">
                                    <label for="pemastian_mold_3_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_3" id="pemastian_mold_3_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_mold_3_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>4. Apakah tidak ada efek ke bagian sekeliling ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_4" id="pemastian_mold_4_OK" value="OK">
                                    <label for="pemastian_mold_4_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_4" id="pemastian_mold_4_NG" value="NG">
                                    <label for="pemastian_mold_4_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_mold_4" id="pemastian_mold_4_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_mold_4_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>
                        </div>

                        <div class="col-md-6">
                            <div class="form-group">
                                <label style="font-weight: bold;">※ Pemastian pada Produk Molding</label>
                            </div>

                            <div class="form-group">
                                <label>1. Apakah bentuk pada posisi repair OK ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_1" id="pemastian_product_1_OK" value="OK">
                                    <label for="pemastian_product_1_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_1" id="pemastian_product_1_NG" value="NG">
                                    <label for="pemastian_product_1_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_1" id="pemastian_product_1_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_product_1_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>2. Apakah dimensi pada posisi repair 0K ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_2" id="pemastian_product_2_OK" value="OK">
                                    <label for="pemastian_product_2_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_2" id="pemastian_product_2_NG" value="NG">
                                    <label for="pemastian_product_2_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_2" id="pemastian_product_2_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_product_2_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>3. Apakah tidak ada efek ke bagian sekeliling ?</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_3" id="pemastian_product_3_OK" value="OK">
                                    <label for="pemastian_product_3_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_3" id="pemastian_product_3_NG" value="NG">
                                    <label for="pemastian_product_3_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_3" id="pemastian_product_3_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_product_3_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>

                            <div class="form-group">
                                <label>4. Apakah dimensi kontrol harian OK ? (Pemastian ke QA)</label>
                            <div class="form-group clearfix">
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_4" id="pemastian_product_4_OK" value="OK">
                                    <label for="pemastian_product_4_OK">&nbsp; OK</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_4" id="pemastian_product_4_NG" value="NG">
                                    <label for="pemastian_product_4_NG">&nbsp; NG</label>
                                </div>
                                <div class="icheck-success d-inline">
                                    <input type="radio" name="pemastian_product_4" id="pemastian_product_4_Not_Applicable" value="Not Applicable">
                                    <label for="pemastian_product_4_Not_Applicable">&nbsp; Not Applicable</label>
                                </div>
                            </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-between">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal"><i class="fas fa-times"></i> Close</button>
                    <button type="button" class="btn btn-success" onclick="saveKerusakan()"><i class="fas fa-save"></i> Simpan</button>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

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

        var tbl = jspreadsheet(document.getElementById('metode_repair'), {
            worksheets: [{
                minDimensions: [8,6],
                columns: [
                    { width: 100 }, // A
                    { width: 250 }, // B
                    { width: 10 }, // C
                    { width: 100 }, // D
                    { width: 250 },  // E
                    { width: 10 },   // F
                    { width: 100 },   // G
                    { width: 250 }   // H
                ],
                updateTable: function (instance, cell, col, row, val, id) {
                    // col = 3 artinya kolom ke-4 (index mulai 0)
                    // row = 5 artinya baris ke-6
                    if (col === 3 && row === 5) {
                        cell.classList.add('image-cell');
                        cell.innerHTML = '<input type="file" accept="image/*" />';
                    }
                }
            }]
        });

        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");

            getData();

            $('.select2').select2({
                dropdownAutoWidth: true,
                allowClear: true,
                dropdownParent: $('#molding_select')
            });

            $('.datepicker')
            .datepicker({
                autoclose: true,
                format: "yyyy-mm",
                viewMode: "months",
                minViewMode: "months"
            });

            var sheet = tbl[0];
            // set cell values (col, row, value)
            sheet.setValue('A1', 'Metode 1');
            sheet.setMerge('A1', 2, 1);
            sheet.setStyle('A1', 'font-weight', 'bold');
            sheet.setValue('A2', 'Mutu');
            sheet.setValue('A3', 'Biaya');
            sheet.setValue('A4', 'Waktu');
            sheet.setValue('A5', 'Gambar / Foto');
            sheet.setMerge('A5', 2, 1);
            sheet.setMerge('A6', 2, 1);


            sheet.setStyle('C1', 'border-left', '2px solid black');
            sheet.setStyle('C1', 'border-right', '2px solid black');
            sheet.setStyle('C2', 'border-left', '2px solid black');
            sheet.setStyle('C2', 'border-right', '2px solid black');
            sheet.setStyle('C3', 'border-left', '2px solid black');
            sheet.setStyle('C3', 'border-right', '2px solid black');
            sheet.setStyle('C4', 'border-left', '2px solid black');
            sheet.setStyle('C4', 'border-right', '2px solid black');
            sheet.setStyle('C5', 'border-left', '2px solid black');
            sheet.setStyle('C5', 'border-right', '2px solid black');
            sheet.setStyle('C6', 'border-left', '2px solid black');
            sheet.setStyle('C6', 'border-right', '2px solid black');

            sheet.setValue('D1', 'Metode 2');
            sheet.setMerge('D1', 2, 1);
            sheet.setStyle('D1', 'font-weight', 'bold');
            sheet.setValue('D2', 'Mutu');
            sheet.setValue('D3', 'Biaya');
            sheet.setValue('D4', 'Waktu');
            sheet.setValue('D5', 'Gambar / Foto');
            sheet.setMerge('D5', 2, 1);
            sheet.setMerge('D6', 2, 1);

            sheet.setStyle('F1', 'border-left', '2px solid black');
            sheet.setStyle('F1', 'border-right', '2px solid black');
            sheet.setStyle('F2', 'border-left', '2px solid black');
            sheet.setStyle('F2', 'border-right', '2px solid black');
            sheet.setStyle('F3', 'border-left', '2px solid black');
            sheet.setStyle('F3', 'border-right', '2px solid black');
            sheet.setStyle('F4', 'border-left', '2px solid black');
            sheet.setStyle('F4', 'border-right', '2px solid black');
            sheet.setStyle('F5', 'border-left', '2px solid black');
            sheet.setStyle('F5', 'border-right', '2px solid black');
            sheet.setStyle('F6', 'border-left', '2px solid black');
            sheet.setStyle('F6', 'border-right', '2px solid black');

            sheet.setValue('G1', 'Metode 3');
            sheet.setMerge('G1', 2, 1);
            sheet.setStyle('G1', 'font-weight', 'bold');
            sheet.setValue('G2', 'Mutu');
            sheet.setValue('G3', 'Biaya');
            sheet.setValue('G4', 'Waktu');
            sheet.setValue('G5', 'Gambar / Foto');
            sheet.setMerge('G5', 2, 1);
            sheet.setMerge('G6', 2, 1);
        })


        $('#shot').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
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
            $.get('{{ url('fetch/diagnose_molding/molding_list') }}', function(result, status, xhr) {
                if (result.status) {
                    var tableData = "";
                    $.each(result.data, function(key, value) {
                        tableData += '<tr>';
                        tableData += '<td>' + (key + 1) + '</td>';
                        tableData += '<td>' + value.fixed_asset_number + '</td>';
                        tableData += '<td>' + value.fixed_asset_name + '</td>';
                        tableData += '<td>' + value.vendor + '</td>';
                        tableData += '<td>' + (value.standard_shot ? parseInt(value.standard_shot).toLocaleString('id-ID') : '-') + '</td>';
                        tableData += '<td>' + (value.total_shot ? parseInt(value.total_shot).toLocaleString('id-ID') : '-') + '</td>';
                        var color = '';

                        if (value.status == 'Sedang Pemeriksaan') {
                            color = '#6dcdf0';
                        } else if (value.total_shot >= value.standard_shot) {
                            color = '#ffa3a3';
                        } else {
                            color = '#22bf76';
                        }

                        tableData += '<td style="background-color:'+color+'; text-align: center;" >' + (value.status ? value.status : '-') + '</td>';

                        if(value.status == 'Butuh Pemeriksaan') {
                            tableData += '<td><button class="btn btn-warning btn-sm" onclick="openModalShot(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-marker"></i> Input Shot</button>&nbsp;<button class="btn btn-info btn-sm" onclick="openModalKerusakan(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-plus"></i> Kerusakan</button>&nbsp;<button class="btn btn-success btn-sm" onclick="generate_form(\''+value.fixed_asset_number+'\')"><i class="fas fa-plus"></i> Buat Form</button></td>';
                        } else if (value.status == 'InProgress Pemeriksaan') {
                            tableData += '<td><button class="btn btn-warning btn-sm" onclick="openModalShot(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-marker"></i> Input Shot</button>&nbsp;<button class="btn btn-info btn-sm" onclick="openModalKerusakan(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-plus"></i> Kerusakan</button>&nbsp;<a class="btn btn-primary btn-sm" href="{{ url('index/diagnose_molding/molding_form') }}/' + value.fixed_asset_number + '"><i class="fas fa-info"></i> Lihat Form</a></td>';
                        } else {
                            tableData += '<td><button class="btn btn-warning btn-sm" onclick="openModalShot(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-marker"></i> Input Shot</button>&nbsp;<button class="btn btn-info btn-sm" onclick="openModalKerusakan(\''+value.fixed_asset_number+'\', \''+value.fixed_asset_name+'\')"><i class="fas fa-plus"></i> Kerusakan</button></td>';
                        }
                        tableData += '</tr>';
                    });
                    $('#bodyTableMaster').html(tableData);

                    // Initialize DataTable
                    $('#tableMaster').DataTable({
                        "scrollX": true,
                        "scrollY": "600px",
                        "scrollCollapse": true,
                        "paging": false,
                        "searching": true,
                        "ordering": true,
                        "info": true,
                        "autoWidth": false,
                        "responsive": true,
                        "lengthChange": true,
                        "lengthMenu": [10, 25, 50, 75, 100],
                        "language": {
                            "lengthMenu": "Tampilkan _MENU_ data per halaman",
                            "zeroRecords": "Tidak ada data",
                            "info": "Menampilkan _START_ sampai _END_ dari _TOTAL_ data",
                            "infoEmpty": "Menampilkan 0 sampai 0 dari 0 data",
                            "infoFiltered": "(difilter dari _MAX_ total data)",
                            "search": "Cari:",
                        },
                        "order": [[6, "asc"]]
                    });
                }
            })
        }

        function generate_form(asset_number) {
            window.open('{{ url('generate/diagnose_molding/mold_product_check/new') }}?asset_number='+asset_number);

            // var data = {
            //     asset_number : asset_number
            // };

            // $.get('{{ url('generate/diagnose_molding/mold_product_check/new') }}', data, function(result, status, xhr) {
            //     if(result.status) {

            //     } else {

            //     }
            // })
        }
        
        function openModalShot(asset_number, asset_name) {
            $('#modal_shot').modal('show');
            $('#fa_number').val(asset_number);
            $('#nama_molding').val(asset_name);

            var data = {
                asset_number : asset_number
            };

            $.get('{{ url('fetch/diagnose_molding/shot_list') }}', data, function(result, status, xhr) {
                if(result.status) {
                    var tableData = "";
                    $('#tbodyShot').empty();
                    if(result.data.length > 0) {
                        var no = 1;
                        $.each(result.data, function(key, value) {
                            tableData += '<tr>';
                            tableData += '<td>' + no++ + '</td>';
                            tableData += '<td>' + value.created_at + '</td>';
                            tableData += '<td>' + value.total_shot + '</td>';
                            tableData += '<td>' + value.accumulative_shot + '</td>';
                            tableData += '<td>' + value.created_by + '</td>';
                            tableData += '<td></td>';
                            tableData += '</tr>';
                        });
                    } else {
                        tableData += '<tr>';
                        tableData += '<td colspan="6" class="text-center">Tidak ada data</td>';
                        tableData += '</tr>';
                    }
                    $('#tbodyShot').html(tableData);
                } else {
                    toastr.error(result.message);
                    audio_error.play();
                }
            })
        }

        function updateShot() {
            var asset_number = $('#fa_number').val();
            var shot = $('#shot').val();

            var data = {
                asset_number : asset_number,
                molding_name : $('#nama_molding').val(),
                period : $('#period').val(),
                shot : shot
            };

            $.post('{{ url('update/diagnose_molding/shot') }}', data, function(result, status, xhr) {
                if(result.status) {
                    toastr.success(result.message);
                    
                    var data = {
                        asset_number : asset_number
                    };

                    $.get('{{ url('fetch/diagnose_molding/shot_list') }}', data, function(result, status, xhr) {
                        if(result.status) {
                            var tableData = "";
                            $('#tbodyShot').empty();
                            if(result.data.length > 0) {
                                var no = 1;
                                $.each(result.data, function(key, value) {
                                    tableData += '<tr>';
                                    tableData += '<td>' + no++ + '</td>';
                                    tableData += '<td>' + value.created_at + '</td>';
                                    tableData += '<td>' + value.total_shot + '</td>';
                                    tableData += '<td>' + value.accumulative_shot + '</td>';
                                    tableData += '<td>' + value.created_by + '</td>';
                                    tableData += '<td></td>';
                                    tableData += '</tr>';
                                });
                            } else {
                                tableData += '<tr>';
                                tableData += '<td colspan="6" class="text-center">Tidak ada data</td>';
                                tableData += '</tr>';
                            }
                            $('#tbodyShot').html(tableData);
                        } else {
                            toastr.error(result.message);
                            audio_error.play();
                        }
                    })
                } else {
                    toastr.error(result.message);
                    audio_error.play();
                }
            })
        }

        
        function openModalKerusakan(asset_number, molding_name) {
            $('#modalKerusakan').modal('show');
            $('#fa_number_kerusakan').val(asset_number);
            $('#nama_molding_kerusakan').val(molding_name);

            $('#tanggal_kerusakan').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });
            $('#tanggal_target_kerusakan').datepicker({
                format: 'yyyy-mm-dd',
                autoclose: true
            });

            $("#sebelum_repair").summernote({
                placeholder: 'Sebelum Repair',
                tabsize: 2,
                height: 100
            });

            $("#setelah_repair").summernote({
                placeholder: 'Setelah Repair',
                tabsize: 2,
                height: 100
            });
        }

        //jml_shot_kerusakan input number only
        $('#jml_shot_kerusakan').on('input', function() {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        function saveKerusakan() {
            var formData = new FormData();
            formData.append('fa_number', $('#fa_number_kerusakan').val());
            formData.append('molding_name', $('#nama_molding_kerusakan').val());
            formData.append('tanggal_kerusakan', $('#tanggal_kerusakan').val());
            formData.append('tanggal_target_kerusakan', $('#tanggal_target_kerusakan').val());
            formData.append('jml_shot_kerusakan', $('#jml_shot_kerusakan').val());
            formData.append('gejala', $('#gejala').val());
            var files = $('#photo')[0].files;
            for (var i = 0; i < files.length; i++) {
                formData.append('photo_file[]', files[i]);
            }
            formData.append('metode_dipilih', $('#metode_dipilih').val());
            formData.append('alasan_pemilihan', $('#alasan_pemilihan').val());
            formData.append('sebelum_repair', $('#sebelum_repair').summernote('code'));
            formData.append('setelah_repair', $('#setelah_repair').summernote('code'));

            var pemastian_mold = {
                pemastian_mold_1: $('input[name="pemastian_mold_1"]:checked').val(),
                pemastian_mold_2: $('input[name="pemastian_mold_2"]:checked').val(),
                pemastian_mold_3: $('input[name="pemastian_mold_3"]:checked').val(),
                pemastian_mold_4: $('input[name="pemastian_mold_4"]:checked').val(),
            };
            formData.append('pemastian_mold', JSON.stringify(pemastian_mold));

            var pemastian_product = {
                pemastian_product_1: $('input[name="pemastian_product_1"]:checked').val(),
                pemastian_product_2: $('input[name="pemastian_product_2"]:checked').val(),
                pemastian_product_3: $('input[name="pemastian_product_3"]:checked').val(),
                pemastian_product_4: $('input[name="pemastian_product_4"]:checked').val(),
            };
            formData.append('pemastian_product', JSON.stringify(pemastian_product));

            if (typeof tbl !== 'undefined') {
                let sheetData = tbl[0].getData();  // array of array
                formData.append('metode_repair', JSON.stringify(sheetData));
            }

            $.ajax({
                url: '{{ url("post/diagnose_molding/kerusakan") }}',
                type: 'POST',
                contentType: false,
                processData: false,
                data : formData,
                xhr: function() {
                    var xhr = new window.XMLHttpRequest();
                    xhr.upload.addEventListener('progress', function(evt) {
                        if (evt.lengthComputable) {
                            var percentComplete = evt.loaded / evt.total;
                        if (xhr.responseText) {
                            var response = JSON.parse(xhr.responseText);
                            if (!response.status) {
                                audio_error.play();
                                toastr.error(response.message);
                            } else {
                                audio_success.play();
                                toastr.success(response.message);
                                $('#modalKerusakan').modal('hide');
                            }
                        }
                            
                        }
                    });
                    return xhr;
                }
            })
        }

        // Handle the change event for the file input
        var audio_error = new Audio('{{ url('sounds/error.mp3') }}');
        var audio_success = new Audio('{{ url('sounds/success.mp3') }}');
    </script>
@endsection
