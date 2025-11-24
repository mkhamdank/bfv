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

        a {
            color: #001dcb;
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
                    <div class="col-md-12">
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
                                <th>Poin Molding</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="total_point" style="font-weight: bold; font-size: 40px;">100</td>
                            </tr>
                            <tr>
                                <th>Poin Produk</th>
                                <td style="width: 20px; text-align: center">:</td>
                                <td id="total_produk_point" style="font-weight: bold; font-size: 40px;">
                                    @if(isset($product_point))
                                        {{ $product_point->points }}
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
                </div>
            </div>
            <!-- /.card-body -->
            </div>
        </div>

        <div class="container-fluid" id="form_diagnose_container">
            <div class="card card-primary color-palette-box">
                <div class="card-header">
                    <h3 class="card-title" style="font-weight: bold"><i class="fas fa-info-circle"></i> Lembar Diagnosa Molding</h3>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12">
                            <center><h5 style="font-weight: bold">Standar Penilaian</h5></center>
                            <table width="100%" class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Kategori</th>
                                        <th>Keterangan</th>
                                        <th>Poin Pengurangan</th>
                                    </tr>   
                                </thead>
                                <tbody>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle">OK</td>
                                        <td>Yang tidak ada masalah.</td>
                                        <td rowspan="2" style="text-align: center; vertical-align: middle">0 Poin</td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td>Kondisi awal dapat dipelihara konsistensinya.</td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td rowspan="3" style="font-weight: bold; text-align: center; vertical-align: middle">NG</td>
                                        <td>Yang ada masalah.</td>
                                        <td rowspan="3" style="vertical-align: middle; text-align: center"><div style="display: flex; justify-content: center; align-items: center"><button class="btn btn-xs" style="background-color: #ffa3a3; border: 1px solid rgb(182, 182, 182); margin: 5px">&nbsp;</button> : -10 Poin <br> <button class="btn btn-xs" style="background-color: #6dcdf0; border: 1px solid rgb(182, 182, 182); margin: 5px">&nbsp;</button> : -5 Poin</div></td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td>Yang harus direpair setelah menyesuaikan schedule lagi, atau yang harus diganti part-nya.</td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td>Yang tidak dapat direpair.</td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td rowspan="2" style="font-weight: bold; text-align: center; vertical-align: middle">OK Sementara</td>
                                        <td>Yang masalah nya dapat diselesaikan dengan repair. (Sedang diobservasi prosesnya)</td>
                                        <td rowspan="2" style="vertical-align: middle; text-align: center"><div style="display: flex; justify-content: center; align-items: center"><button class="btn btn-xs" style="background-color: #ffa3a3; border: 1px solid rgb(182, 182, 182); margin: 5px">&nbsp;</button> : -3 Poin <br> <button class="btn btn-xs" style="background-color: #6dcdf0; border: 1px solid rgb(182, 182, 182); margin: 5px">&nbsp;</button> : -1 Poin</div></td>
                                    </tr>
                                    <tr style="border-top: 1px solid #dee2e6">
                                        <td>Ada resiko mempengaruhi stabilitas produksi kedepannya.</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <div class="row">
                        <div class="col-12">
                            <table class="table table-bordered" id="tableMaster">
                                <thead>
                                    <tr style="text-align: center; background-color:rgb(147, 40, 255); color: white;">
                                        <th width="17%">Jenis NG</th>
                                        <th width="13%">Hasil Diagnosa</th>
                                        <th width="8%">Part</th>
                                        <th>Nama Item</th>
                                        <th width="17%">Rincian Lain</th>
                                        <th width="5%">Pengurangan</th>
                                        <th width="7%">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="bodyTableMaster">
                                </tbody>
                            </table>
                        </div>
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
                <table style="width: 100%">
                    <thead>
                    <tr>
                        <th style="width: 20%">OK</th>
                        <th style="width: 20px">:</th>
                        <td>Tidak ada masalah → kondisi awal terjaga</td>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">NG</th>
                        <th style="vertical-align: top;">:</th>
                        <td>Ada masalah → reschedule untuk repair, atau ganti parts. <br> Tidak bisa direpair.</td>
                    </tr>
                    <tr>
                        <th style="vertical-align: top;">OK Sementara</th>
                        <th style="vertical-align: top;">:</th>
                        <td>Bisa diselesaikan dengan repair. (perlu dipantau hasilnya) <br> Diperkirakan beresiko memberi dampak ke kestabilan produksi selanjutnya.</td>
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

    <div class="modal fade" id="modal_image" tabindex="-1" role="dialog" aria-labelledby="modalImageLabel">
        <div class="modal-dialog modal-lg" role="document" style="width: 90%;">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title w-100 text-center" id="modalImageLabel" style="font-weight: bold; color: #007bff">
                        Detail Foto
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close" data-bs-dismiss="modal">
                        <span aria-hidden="true">×</span>
                    </button>
                </div>
                <div class="modal-body">
                <img src="" alt="" id="image" style="width: 100%;">
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
        var actual_check_list = {!! json_encode($actual_check_list) !!};
        var ranks = {!! json_encode($ranks) !!};

        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");

            $("#modal_info").modal('show');

            $('.select2').select2({
                dropdownAutoWidth: true,
                allowClear: true,
                dropdownParent: $('#molding_select')
            });

            getData();
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
            $('#bodyTableMaster').html('');
            var tableData = '';
            $.each(master_check_list, function(key, value) {
                if(value.grouping == 'Main') {
                    bgcolor = 'background-color:rgb(255, 163, 163);';
                    ng = 10;
                    ok_temp = 5;
                } else {
                    bgcolor = 'background-color:#6dcdf0;';
                    ng = 5;
                    ok_temp = 1;
                }

                tableData += '<tr style="' + bgcolor + '"><td>' + value.item_ng + '</td>';
                
                tableData += '<td>';
                tableData += '<div class="icheck-danger d-inline"><input type="radio" name="r3_' + value.id + '" tag="OK" id="' + value.id + '_OK" value="0" onclick="changePengurangan(' + value.id + ', \''+ value.grouping + '\')"><label for="' + value.id + '_OK">&nbsp; OK</label></div><br>';
                tableData += '<div class="icheck-danger d-inline"><input type="radio" name="r3_' + value.id + '" tag="NG" id="' + value.id + '_NG" value="' + ng + '" onclick="changePengurangan(' + value.id + ', \''+ value.grouping + '\')"><label for="' + value.id + '_NG">&nbsp; NG</label></div><br>';
                tableData += '<div class="icheck-danger d-inline"><input type="radio" name="r3_' + value.id + '" tag="OK Sementara" id="' + value.id + '_OK_Sementara" value="' + ok_temp + '" onclick="changePengurangan(' + value.id + ', \''+ value.grouping + '\')"><label for="' + value.id + '_OK_Sementara">&nbsp; OK Sementara</label></div>';
                tableData += '</td>';

                if(value.daerah_ng) {
                    var daerah_ng = value.daerah_ng.split(', ');

                    tableData += '<td>';
                    $.each(daerah_ng, function(key2, value2) {
                        tableData += '<div class="icheck-danger d-inline"><input type="checkbox" name="ck_' + value.id +'" value="' + value2 + '" id="' + value.id + '_'+ value2 +'" onchange="changePengurangan(' + value.id + ', \''+ value.grouping + '\')"><label for="' + value.id + '_'+ value2 +'">&nbsp; ' + value2 + '</label></div><br>';
                    });
                    tableData += '</td>';
                } else {
                    tableData += '<td></td>';
                }

                if(value.item_check) {
                    var item_check = value.item_check.split(', ');

                    tableData += '<td>';
                    $.each(item_check, function(key2, value2) {
                        tableData += '<table style="width: 100%; border-color: transparent !important;">';
                        tableData += '<tr>';
                        tableData += '<td style="width: 60%; padding: 3px"><div class="icheck-danger d-inline"><input type="checkbox" id="ckitem_' + value.id + '_' + key2 + '" name="ckitem_' + value.id + '" value="' + value2 + '" onchange="changeItemCheck(' + value.id + ','+ key2 +')"><label for="ckitem_' + value.id + '_' + key2 +'">&nbsp; ' + value2 + '</label></div></td>';
                        tableData += '<td style="width: 20%; padding: 3px; text-align: center"><input type="file" id="photo_file1_' + value.id + '_' + key2 + '" class="form-control" style="display:none;" accept="image/*" onchange="showImage(this, \'img_photo1_' + value.id + '_' + key2 + '\')"><button type="button" class="btn btn-block btn-primary btn-sm" id="btn_photo1_' + value.id + '_' + key2 + '" style="display: none" onclick="document.getElementById(\'photo_file1_' + value.id + '_' + key2 +'\').click();"><i class="fas fa-camera"></i></button> <br> <a href="javascript:void(0);" onclick="openImage(this)" id="img_photo1_' + value.id + '_' + key2 + '" style="display: none">image</a></td>';
                        tableData += '<td style="width: 20%; padding: 3px; text-align: center"><input type="file" id="photo_file2_' + value.id + '_' + key2 + '" class="form-control" style="display:none;" accept="image/*" onchange="showImage(this, \'img_photo2_' + value.id + '_' + key2 + '\')"><button type="button" class="btn btn-block btn-primary btn-sm" id="btn_photo2_' + value.id + '_' + key2 + '" style="display: none" onclick="document.getElementById(\'photo_file2_' + value.id + '_' + key2 +'\').click();"><i class="fas fa-camera"></i></button> <br> <a href="javascript:void(0);" onclick="openImage(this)" id="img_photo2_' + value.id + '_' + key2 + '" style="display: none">image</a></td>';
                        tableData += '</tr>';
                        tableData += '</table>';
                    });
                    tableData += '</td>';
                } else {
                    tableData += '<td></td>';
                }

                tableData += '<td><textarea class="form-control" placeholder="Rincian Lain" id="rincian_lain_' + value.id + '"></textarea></td>';

                tableData += '<td style="text-align: center; font-weight: bold" id="pengurangan_' + value.id + '" class="pengurangan">0</td>';
                tableData += '<td style="text-align: center"><i class="fas fa-check" style="color: #ddd; font-size: 22px; font-weight: bold" id="cek_' + value.id + '"></i><br><button type="button" class="btn btn-block btn-primary btn-sm"><i class="fas fa-info"></i> Details</button></td></tr>';
            })

            $('#bodyTableMaster').html(tableData);

            if(actual_check_list.length > 0) {
                $.each(actual_check_list, function(key, value) {
                    $("input[name='r3_" + value.ng_id + "'][value='" + value.deduction + "']").prop("checked", true);
                    // console.log(value.ng_id);

                    $("#cek_" + value.ng_id).css("color", "#3a9e54");

                    if(value.parts) {
                        var part = value.parts.split(', ');

                        $.each(part, function(key2, value2) {
                            $("input[name='ck_" + value.ng_id + "'][value='" + value2 + "']").prop("checked", true);
                        })
                    }

                    $("#ckitem_" + value.ng_id + "_" + value.item_number).prop("checked", true);

                    changeItemCheck(value.ng_id, value.item_number);

                    if(value.photo1) {
                        //set input file
                        $("#img_photo1_" + value.ng_id + "_" + value.item_number).show();
                        $("#img_photo1_" + value.ng_id + "_" + value.item_number).attr("src", "{{ url('workshop/molding/photo_molding/ng/') }}/" + value.photo1);
                    }

                    if(value.photo2) {
                        //set input file
                        $("#img_photo2_" + value.ng_id + "_" + value.item_number).show();
                        $("#img_photo2_" + value.ng_id + "_" + value.item_number).attr("src", "{{ url('workshop/molding/photo_molding/ng/') }}/" + value.photo2);
                    }

                    if(value.note) {
                        $("#rincian_lain_" + value.ng_id).val(value.note);
                    }

                    changePengurangan(value.ng_id, value.grouping);
                })
            }

            if($("#total_produk_point").text() != "") {
                //get the smallest number
                var smallest = Math.min(parseInt($("#total_point").text()), parseInt($("#total_produk_point").text()));

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

        function changePengurangan(id, grouping) {
            var value_ng = $('input[name="r3_' + id + '"][type="radio"]:checked').val();
            var checkedParts = $('input[name="ck_' + id + '"][type="checkbox"]:checked').map(function() {
                return this.value;
            }).get();

            if(checkedParts.length > 0) {
                var pengurangan = parseInt(value_ng) * checkedParts.length;
            } else {
                var pengurangan = parseInt(value_ng);
            }

            if (pengurangan == 0) {
                $('#pengurangan_' + id).text(pengurangan);
            } else {
                $('#pengurangan_' + id).text("-" + pengurangan);
            }

            var total = 0;
            $('.pengurangan').each(function() {
                total += Math.abs(parseInt($(this).text())) || 0;
            });
            $('#total_point').text((100 - total));
            // $('#total_pengurangan').text(total);

            if($("#total_produk_point").text() != "") {
                //get the smallest number
                var smallest = Math.min(parseInt($("#total_point").text()), parseInt($("#total_produk_point").text()));

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

        function changeItemCheck(id, key2) {
            if ($('#ckitem_' + id + '_' + key2).is(':checked')) {
                $("#btn_photo1_" + id + "_" + key2).show();
                $("#btn_photo2_" + id + "_" + key2).show();
            } else {
                $("#btn_photo1_" + id + "_" + key2).hide();
                $("#btn_photo2_" + id + "_" + key2).hide();
            }
        }

        function showImage(input, id) {
            if (input.files && input.files[0]) {
                var reader = new FileReader();
                reader.onload = function (e) {
                    $("#" + id).show();
                    $("#" + id).attr('src', e.target.result);
                };
                reader.readAsDataURL(input.files[0]);
            }
        }

        function openImage(input) {
            var id = input.id;
            var src = $("#" + id).attr('src');

            $('#modal_image').modal('show');
            $('#image').attr('src', src);
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
                            tableData += '<td><img src="{{ asset('workshop/molding/photo_product/ng/') }}/' + value.photo2 + '" alt="Photo 2" style="width: 200px; height: auto;"></td>';
                            tableData += '<td><button class="btn btn-danger btn-sm" onclick="deleteProductNg(' + value.id + ')"><i class="fas fa-trash"></i> Hapus</button></td>';
                            tableData += '</tr>';
                        });
                    }else{
                        tableData += '<tr><td colspan="5" style="text-align: center; color: #a5a4a4;">Tidak ada data</td></tr>';
                    }
                    $('#tbody_detail').html(tableData);
                }else{
                    toastr.error(result.message);
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
                            $('#modal_detail').modal('hide');
                        } else {
                            toastr.error(result.message);
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
                var datas = [];
                var total_poin = 100;

                $.each(master_check_list, function(key, value) {
                    var hasil_diagnosa = '';
                    var pengurangan = 0;

                    // get hasil diagnosa on radio button
                    $('input[name="r3_' + value.id + '"]:checked').each(function() {
                        hasil_diagnosa = $(this).attr('tag');
                        pengurangan = $(this).attr('value');
                    });
                    
                    // get part checked
                    var part_checked = [];
                    $('input[name="ck_' + value.id + '"]:checked').each(function() {
                        part_checked.push($(this).val());
                    });

                    var part_checked_name = part_checked.join(', ');

                    var status_ng = false;

                    $.each(value.item_check.split(', '), function(key2, value2) {
                        if ($('#ckitem_' + value.id + '_' + key2)[0].checked) {

                            status_ng = true;
                            var photo1 = null;
                            var photo2 = null;
                            
                            if($('#photo_file1_' + value.id + '_' + key2)[0].files.length > 0) {
                                photo1 = $('#photo_file1_' + value.id + '_' + key2)[0].files[0];
                            }
                            
                            if($('#photo_file2_' + value.id + '_' + key2)[0].files.length > 0) {
                                photo2 = $('#photo_file2_' + value.id + '_' + key2)[0].files[0];
                            }

                            total_poin -= parseInt($('#pengurangan_' + value.id).text());
                            
                            datas.push({
                                form_number: $('#form_number').text(),
                                ng_id: value.id,
                                item_ng: value.item_ng,
                                hasil_diagnosa: hasil_diagnosa,
                                part_checked: part_checked_name,
                                id_items: key2,
                                item_name: value2,
                                rincian_lain: $('#rincian_lain_' + value.id).val(),
                                pengurangan: pengurangan,
                                photo1: photo1,
                                photo2: photo2,
                            });
                        }
                    });

                    if(!status_ng && hasil_diagnosa == 'OK'){
                        datas.push({
                            ng_id: value.id,
                            item_ng: value.item_ng,
                            hasil_diagnosa: hasil_diagnosa,
                            part_checked: part_checked_name,
                            id_items: null,
                            item_name: null,
                            rincian_lain: $('#rincian_lain_' + value.id).val(),
                            pengurangan: 0,
                            photo1: null,
                            photo2: null,
                        });
                    }
                });

                let formData = new FormData();
                formData.append('total_poin', $('#total_point').text());
                formData.append('form_number', $('#form_number').text());

                datas.forEach((item, index) => {
                    formData.append(`data[${index}][ng_id]`, item.ng_id);
                    formData.append(`data[${index}][item_ng]`, item.item_ng);
                    formData.append(`data[${index}][hasil_diagnosa]`, item.hasil_diagnosa);
                    formData.append(`data[${index}][part_checked]`, item.part_checked);
                    formData.append(`data[${index}][id_items]`, item.id_items);
                    formData.append(`data[${index}][item_name]`, item.item_name);
                    formData.append(`data[${index}][rincian_lain]`, item.rincian_lain);
                    formData.append(`data[${index}][pengurangan]`, item.pengurangan);
                    formData.append(`data[${index}][photo1]`, item.photo1);
                    formData.append(`data[${index}][photo2]`, item.photo2);
                });

                $.ajax({
                    url: '{{ url('save/diagnose_molding/mold_molding_check') }}',
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
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
            if(confirm('Yakin ingin menyimpan data ini?')) {
                var datas = [];
                var total_poin = 100;

                $.each(master_check_list, function(key, value) {
                    var hasil_diagnosa = '';
                    var pengurangan = 0;

                    // get hasil diagnosa on radio button
                    $('input[name="r3_' + value.id + '"]:checked').each(function() {
                        hasil_diagnosa = $(this).attr('tag');
                        pengurangan = $(this).attr('value');
                    });
                    
                    // get part checked
                    var part_checked = [];
                    $('input[name="ck_' + value.id + '"]:checked').each(function() {
                        part_checked.push($(this).val());
                    });

                    var part_checked_name = part_checked.join(', ');

                    var status_ng = false;

                    $.each(value.item_check.split(', '), function(key2, value2) {
                        if ($('#ckitem_' + value.id + '_' + key2)[0].checked) {

                            status_ng = true;
                            var photo1 = null;
                            var photo2 = null;
                            
                            if($('#photo_file1_' + value.id + '_' + key2)[0].files.length > 0) {
                                photo1 = $('#photo_file1_' + value.id + '_' + key2)[0].files[0];
                            }
                            
                            if($('#photo_file2_' + value.id + '_' + key2)[0].files.length > 0) {
                                photo2 = $('#photo_file2_' + value.id + '_' + key2)[0].files[0];
                            }

                            total_poin -= parseInt($('#pengurangan_' + value.id).text());
                            
                            datas.push({
                                form_number: $('#form_number').text(),
                                ng_id: value.id,
                                item_ng: value.item_ng,
                                hasil_diagnosa: hasil_diagnosa,
                                part_checked: part_checked_name,
                                id_items: key2,
                                item_name: value2,
                                rincian_lain: $('#rincian_lain_' + value.id).val(),
                                pengurangan: pengurangan,
                                photo1: photo1,
                                photo2: photo2,
                            });
                        }
                    });

                    if(!status_ng && hasil_diagnosa == 'OK'){
                        datas.push({
                            ng_id: value.id,
                            item_ng: value.item_ng,
                            hasil_diagnosa: hasil_diagnosa,
                            part_checked: part_checked_name,
                            id_items: null,
                            item_name: null,
                            rincian_lain: $('#rincian_lain_' + value.id).val(),
                            pengurangan: 0,
                            photo1: null,
                            photo2: null,
                        });
                    }
                });

                let formData = new FormData();
                formData.append('total_poin', total_poin);
                formData.append('form_number', $('#form_number').text());

                datas.forEach((item, index) => {
                    formData.append(`data[${index}][ng_id]`, item.ng_id);
                    formData.append(`data[${index}][item_ng]`, item.item_ng);
                    formData.append(`data[${index}][hasil_diagnosa]`, item.hasil_diagnosa);
                    formData.append(`data[${index}][part_checked]`, item.part_checked);
                    formData.append(`data[${index}][id_items]`, item.id_items);
                    formData.append(`data[${index}][item_name]`, item.item_name);
                    formData.append(`data[${index}][rincian_lain]`, item.rincian_lain);
                    formData.append(`data[${index}][pengurangan]`, item.pengurangan);
                    formData.append(`data[${index}][photo1]`, item.photo1);
                    formData.append(`data[${index}][photo2]`, item.photo2);
                });

                $.ajax({
                    url: '{{ url('save/diagnose_molding/mold_molding_check') }}',
                    type: 'POST',
                    contentType: false,
                    processData: false,
                    data: formData,
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
        var audio_error = new Audio('{{ url('sounds/error_2.mp3') }}');
        var audio_success = new Audio('{{ url('sounds/success.mp3') }}');
    </script>
@endsection
