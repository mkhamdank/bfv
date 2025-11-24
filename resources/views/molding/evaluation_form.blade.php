@extends('layouts.master')
@section('stylesheets')
    <link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
    <link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
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
            <div class="col-md-10">
                <input type="hidden" id="green">
                <h2>Form Evaluasi</h2>
            </div>
        </div>

        <div class="row">
            <div class="col-xs-12">
                <div class="card">
                    <div class="card-body">
                        <h3>Standar Penilaian</h3>
                        <table class="table table-bordered">
                            <tr style="background-color: #e6e6e6; color: black;">
                                <th>Ranking</th>
                                <th>Standar Penilaian</th>
                                <th>Keputusan</th>
                            </tr>
                            <tr>
                                <td>Rank AA</td>
                                <td>90 poin~100 poin</td>
                                <td>Tidak perlu repair. Ditangani dengan maintenance rutin atau overhaul.</td>
                            </tr>
                            <tr>
                                <td>Rank A</td>
                                <td>70 poin~90 poin</td>
                                <td>Ada sebagian yang direpair. Perlu direpair dengan schedule sesingkat mungkin menyesuaikan dengan schedule produksi.</td>
                            </tr>
                            <tr>
                                <td>Rank B</td>
                                <td>30 poin~70 poin</td>
                                <td>Ada beberapa titik yang perlu direpair secepatnya. Diskusi peremajaan.</td>
                            </tr>
                            <tr>
                                <td>Rank C</td>
                                <td>29 poin atau kurang</td>
                                <td>Sulit melanjutkan proses produksi. Perlu peremajaan.</td>
                            </tr>
                        </table><br>
                        <h3>Rangking Mold</h3>
                        <span>Note : Poin diisi dengan poin yang rendah dari diagnosa barang injection & diagnosa mold!</span>

                        <table class="table table-bordered" width="100%">
                            <tr style="background-color: #e6e6e6; color: black;">
                                <th>Nama model atau nama mold</th>
                                <th>Jenis produk</th>
                                <th>Poin</th>
                                <th>Rank</th>
                            </tr>
                            <tr>
                                <td id="nama_mold" style="text-align: center; font-weight: bold; font-size: 20px;">{{ $data_master->fixed_asset_name }}</td>
                                <td id="jenis_produk" style="text-align: center; font-weight: bold; font-size: 20px; width: 12%;">{{ $data_master->product_category }}</td>
                                <td id="point" style="text-align: center; font-weight: bold; font-size: 25px;">{{ $data_master->total_score }}</td>
                                <td id="rank" style="text-align: center; font-weight: bold; font-size: 25px;">{{ $data_master->rank }}</td>
                            </tr>
                        </table> <br>

                        <h3>Informasi Mold</h3>
                        <table class="table table-bordered" width="100%">
                            <tr style="background-color: #e6e6e6; color: black;">
                                <th>Qty Total Produksi</th>
                                <th>Tgl Mulai Produksi</th>
                                <th>Periode Produksi</th>
                            </tr>
                            <tr>
                                <td><span id="qty_total">{{ number_format($data_master->production_qty) }}</span> Shot</td>
                                <td>{{ $data_master->acquired_date_text }} <input type="hidden" id="tgl_mulai" value="{{ $data_master->acquired_date }}"></td>
                                <td id="periode_produksi">{{ $data_master->year_diff }} Tahun {{ $data_master->month_diff }} Bulan</td>
                            </tr>
                        </table> <br>

                        <h3>Catatan Hasil Diagnosa</h3>
                        <span>Note : Diisi dengan tingkat efek terhadap hasil produk Molding serta tingkat efek terhadap life time mold yang lebih tinggi!</span><br>
                        <label>Catatan : </label><br>
                        <textarea id="penilaian" name="penilaian">{{ $data_master->penilaian_keseluruhan }}</textarea>
                        <label>Pertimbangan dari Histori Repair Mold : </label><br>
                        <textarea id="pertimbangan" name="pertimbangan">{{ $data_master->pertimbangan_histori }}</textarea>
                        <label>Ide tindakan perbaikan → Lebih baik jika dapat diisi dengan perkiraan lama waktu dan biaya untuk repair : </label><br>
                        <textarea id="ide_tindakan" name="ide_tindakan">{{ $data_master->tindakan_perbaikan }}</textarea> <br>
                        <button class="btn btn-success" onclick="saveEvaluation()"><i class="fa fa-check"></i> Simpan</button>
                    </div>
                </div>
            </div>
        </div>
    </section>
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
    <script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

    <script>
        $.ajaxSetup({
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            }
        });

        
        $('#penilaian').summernote({
            placeholder: 'Penilaian Keseluruhan',
            tabsize: 2,
            height: 100
        });
        $('#pertimbangan').summernote({
            placeholder: 'Pertimbangan dari Histori Repair Mold',
            tabsize: 2,
            height: 100
        });
        $('#ide_tindakan').summernote({
            placeholder: 'Ide tindakan perbaikan',
            tabsize: 2,
            height: 100
        });
    
        
        jQuery(document).ready(function() {
            $("#wrapper").toggleClass("toggled");
        })

        function saveEvaluation() {
            var penilaian = $('#penilaian').summernote('code');
            var pertimbangan = $('#pertimbangan').summernote('code');
            var ide_tindakan = $('#ide_tindakan').summernote('code');
            var tgl_mulai = $('#tgl_mulai').val();
            var periode_produksi = $('#periode_produksi').text();
            // get url segment
            var url = window.location.href;
            var url_segments = url.split('/');
            var form_number = url_segments[url_segments.length - 1];

            $.ajax({
                url: '{{ url("save/diagnose_molding/evaluation") }}',
                type: 'POST',
                data: {
                    form_number: form_number,
                    penilaian: penilaian,
                    pertimbangan: pertimbangan,
                    ide_tindakan: ide_tindakan,
                    tgl_mulai: tgl_mulai,
                    periode_produksi: periode_produksi,
                    qty_total: $('#qty_total').text(),
                },
                success: function(response) {
                    if (response.status) {
                        toastr.success(response.message);
                        audio_success.play();
                        // wait 2 seconds
                        setTimeout(function() {
                            window.location.href = '{{ url("index/diagnose_molding/molding_form") }}';
                        }, 2000);
                    } else {
                        toastr.error(response.message);
                        audio_error.play();
                    }
                },
                error: function(response) {
                    toastr.error(response.message);
                    audio_error.play();
                }
            });

        }

        // Handle the change event for the file input
        var audio_success = new Audio('{{ url("sounds/success.mp3") }}');
        var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    </script>
@endsection
