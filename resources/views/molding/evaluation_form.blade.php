@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    body { background: #f0f2f7 !important; }
    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }
    #loading, #error { display: none; }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px;
        display: flex; align-items: center; justify-content: space-between;
        flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .page-header-modern::after {
        content: ''; position: absolute; left: 30%; bottom: -60px;
        width: 160px; height: 160px; border-radius: 50%;
        background: rgba(255,255,255,.03);
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700;
        letter-spacing: 1.2px; text-transform: uppercase;
        padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ══════════════════════════════════════
       SECTION CARDS
    ══════════════════════════════════════ */
    .section-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 20px;
    }
    .section-card-header {
        padding: 14px 22px; border-bottom: 1px solid #f0f2f7;
        background: #fafbff; display: flex; align-items: center; gap: 10px;
    }
    .section-card-header .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; flex-shrink: 0; }
    .section-card-header h4 { font-size: 13px; font-weight: 700; color: #1a202c; margin: 0; }
    .section-card-header .note {
        font-size: 11px; color: #a0aec0; font-weight: 500;
        margin-left: auto; font-style: italic;
    }
    .section-card-body { padding: 20px 24px; }

    /* ══════════════════════════════════════
       STANDAR PENILAIAN TABLE
    ══════════════════════════════════════ */
    .rank-table { width: 100%; border-collapse: collapse; }
    .rank-table thead th {
        background: #f7f8fc; color: #718096;
        font-size: 11px; font-weight: 700;
        letter-spacing: .7px; text-transform: uppercase;
        padding: 10px 14px; border-bottom: 2px solid #edf0f5;
        text-align: left;
    }
    .rank-table tbody tr { border-bottom: 1px solid #f0f2f7; }
    .rank-table tbody tr:last-child { border-bottom: none; }
    .rank-table tbody tr:hover td { background: #f5f3ff; }
    .rank-table tbody td { padding: 12px 14px; font-size: 13px; color: #2d3748; vertical-align: middle; }

    /* Rank badges */
    .rank-badge {
        display: inline-flex; align-items: center; justify-content: center;
        width: 64px; height: 28px; border-radius: 20px;
        font-size: 12px; font-weight: 800; letter-spacing: .5px;
    }
    .rank-AA { background: #dcfce7; color: #15803d; }
    .rank-A  { background: #ebf2ff; color: #2d6bc4; }
    .rank-B  { background: #fef3c7; color: #b45309; }
    .rank-C  { background: #fee2e2; color: #dc2626; }

    .score-range { font-weight: 600; color: #4a5568; }

    /* ══════════════════════════════════════
       RANKING MOLD HIGHLIGHT CARD
    ══════════════════════════════════════ */
    .mold-rank-grid {
        display: grid; grid-template-columns: 1fr 1fr 1fr 1fr; gap: 16px;
    }
    @media (max-width: 768px) { .mold-rank-grid { grid-template-columns: 1fr 1fr; } }

    .mold-rank-card {
        border-radius: 14px; padding: 18px 20px;
        display: flex; flex-direction: column; gap: 6px;
    }
    .mold-rank-card .mrc-lbl {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .7px; color: #718096;
    }
    .mold-rank-card .mrc-val {
        font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1.1;
    }
    .mrc-name  { background: #fafbff; border: 1.5px solid #e2e8f0; }
    .mrc-jenis { background: #ebf2ff; border: 1.5px solid #c3d9f8; }
    .mrc-jenis .mrc-val { color: #2d6bc4; }
    .mrc-point { background: #ede9fe; border: 1.5px solid #ddd6fe; }
    .mrc-point .mrc-val { color: #5b21b6; font-size: 32px; }
    .mrc-rank  { border: 1.5px solid; }

    /* Dynamic rank color set by JS */
    .mrc-rank-AA { background: #dcfce7; border-color: #bbf7d0; }
    .mrc-rank-AA .mrc-val { color: #15803d; }
    .mrc-rank-A  { background: #ebf2ff; border-color: #c3d9f8; }
    .mrc-rank-A  .mrc-val { color: #2d6bc4; }
    .mrc-rank-B  { background: #fef3c7; border-color: #fde68a; }
    .mrc-rank-B  .mrc-val { color: #b45309; }
    .mrc-rank-C  { background: #fee2e2; border-color: #fecaca; }
    .mrc-rank-C  .mrc-val { color: #dc2626; }

    /* ══════════════════════════════════════
       INFORMASI MOLD
    ══════════════════════════════════════ */
    .info-mold-grid {
        display: grid; grid-template-columns: repeat(3, 1fr); gap: 16px;
    }
    @media (max-width: 768px) { .info-mold-grid { grid-template-columns: 1fr; } }

    .info-mold-item { }
    .info-mold-item .im-lbl {
        font-size: 11px; font-weight: 700; text-transform: uppercase;
        letter-spacing: .7px; color: #718096; margin-bottom: 6px; display: block;
    }
    .info-mold-item .im-val {
        display: flex; align-items: center; gap: 8px;
        background: #fafbff; border: 1.5px solid #e2e8f0;
        border-radius: 10px; padding: 12px 16px;
        font-size: 14px; font-weight: 700; color: #1a202c;
    }
    .info-mold-item .im-val i { color: #605ca8; font-size: 14px; }

    /* ══════════════════════════════════════
       CATATAN / SUMMERNOTE
    ══════════════════════════════════════ */
    .catatan-group { margin-bottom: 20px; }
    .catatan-group:last-of-type { margin-bottom: 0; }

    .catatan-lbl {
        display: block; font-size: 12px; font-weight: 700;
        color: #4a5568; margin-bottom: 8px;
        display: flex; align-items: center; gap: 7px;
    }
    .catatan-lbl i { color: #605ca8; }

    /* Summernote toolbar override */
    .note-toolbar { background: #f7f8fc !important; border-color: #e2e8f0 !important; }
    .note-editor.note-frame { border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important; overflow: hidden !important; }
    .note-editor.note-frame:focus-within { border-color: #605ca8 !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important; }
    .note-editable { font-family: 'Plus Jakarta Sans', sans-serif !important; font-size: 13px !important; }

    /* ══════════════════════════════════════
       SAVE BUTTON
    ══════════════════════════════════════ */
    .btn-save-eval {
        display: inline-flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff; border: none; border-radius: 12px;
        padding: 13px 32px; font-size: 14px; font-weight: 700;
        cursor: pointer; box-shadow: 0 4px 14px rgba(21,128,61,.28);
        transition: opacity .18s, transform .18s;
    }
    .btn-save-eval:hover { opacity: .88; transform: translateY(-1px); }

    .btn-back {
        display: inline-flex; align-items: center; gap: 8px;
        background: #f0f2f7; color: #718096;
        border: none; border-radius: 12px;
        padding: 13px 24px; font-size: 14px; font-weight: 600;
        cursor: pointer; text-decoration: none;
        transition: background .18s;
        z-index: 999;
    }
    .btn-back:hover { background: #e2e8f0; color: #4a5568; text-decoration: none; }
</style>
@stop

@section('header')
@stop

@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div class="container-fluid" style="padding:0 24px;">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-tasks"></i>&nbsp; Diagnosa Molding</div>
            <h1>Form Evaluasi</h1>
            <p>{{ $data_master->fixed_asset_name }}</p>
        </div>
        <a href="{{ url('index/diagnose_molding/molding_form') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali
        </a>
    </div>

    {{-- 1. STANDAR PENILAIAN --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-star" style="color:#605ca8;margin-right:6px;"></i> Standar Penilaian</h4>
        </div>
        <div class="section-card-body" style="padding:0;">
            <table class="rank-table">
                <thead>
                    <tr>
                        <th style="width:100px;">Ranking</th>
                        <th style="width:160px;">Standar Penilaian</th>
                        <th>Keputusan</th>
                    </tr>
                </thead>
                <tbody>
                    <tr>
                        <td><span class="rank-badge rank-AA">Rank AA</span></td>
                        <td><span class="score-range">90 ~ 100 poin</span></td>
                        <td>Tidak perlu repair. Ditangani dengan maintenance rutin atau overhaul.</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-A">Rank A</span></td>
                        <td><span class="score-range">70 ~ 90 poin</span></td>
                        <td>Ada sebagian yang direpair. Perlu direpair dengan schedule sesingkat mungkin menyesuaikan dengan schedule produksi.</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-B">Rank B</span></td>
                        <td><span class="score-range">30 ~ 70 poin</span></td>
                        <td>Ada beberapa titik yang perlu direpair secepatnya. Diskusi peremajaan.</td>
                    </tr>
                    <tr>
                        <td><span class="rank-badge rank-C">Rank C</span></td>
                        <td><span class="score-range">≤ 29 poin</span></td>
                        <td>Sulit melanjutkan proses produksi. Perlu peremajaan.</td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>

    {{-- 2. RANKING MOLD --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-trophy" style="color:#605ca8;margin-right:6px;"></i> Ranking Mold</h4>
            <span class="note">Poin diisi dengan poin yang rendah dari diagnosa barang injection &amp; diagnosa mold</span>
        </div>
        <div class="section-card-body">
            <div class="mold-rank-grid">
                <div class="mold-rank-card mrc-name">
                    <span class="mrc-lbl"><i class="fas fa-cube"></i> Nama Mold</span>
                    <span class="mrc-val" id="nama_mold">{{ $data_master->fixed_asset_name }}</span>
                </div>
                <div class="mold-rank-card mrc-jenis">
                    <span class="mrc-lbl"><i class="fas fa-tag"></i> Jenis Produk</span>
                    <span class="mrc-val" id="jenis_produk">{{ $data_master->product_category }}</span>
                </div>
                <div class="mold-rank-card mrc-point">
                    <span class="mrc-lbl"><i class="fas fa-chart-bar"></i> Total Poin</span>
                    <span class="mrc-val" id="point">{{ $data_master->total_score }}</span>
                </div>
                @php
                    $rank = $data_master->rank;
                    $rankClass = 'mrc-rank-' . str_replace(' ', '', $rank);
                @endphp
                <div class="mold-rank-card mrc-rank {{ $rankClass }}">
                    <span class="mrc-lbl"><i class="fas fa-medal"></i> Rank</span>
                    <span class="mrc-val" id="rank">{{ $rank }}</span>
                </div>
            </div>
        </div>
    </div>

    {{-- 3. INFORMASI MOLD --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-info-circle" style="color:#605ca8;margin-right:6px;"></i> Informasi Mold</h4>
        </div>
        <div class="section-card-body">
            <div class="info-mold-grid">
                <div class="info-mold-item">
                    <span class="im-lbl">Qty Total Produksi</span>
                    <div class="im-val">
                        <i class="fas fa-industry"></i>
                        <span><span id="qty_total">{{ number_format($data_master->production_qty) }}</span> Shot</span>
                    </div>
                </div>
                <div class="info-mold-item">
                    <span class="im-lbl">Tgl Mulai Produksi</span>
                    <div class="im-val">
                        <i class="fas fa-calendar-alt"></i>
                        <span>{{ $data_master->acquired_date_text }}</span>
                        <input type="hidden" id="tgl_mulai" value="{{ $data_master->acquired_date }}">
                    </div>
                </div>
                <div class="info-mold-item">
                    <span class="im-lbl">Periode Produksi</span>
                    <div class="im-val">
                        <i class="fas fa-clock"></i>
                        <span id="periode_produksi">{{ $data_master->year_diff }} Tahun {{ $data_master->month_diff }} Bulan</span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- 4. CATATAN HASIL DIAGNOSA --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-clipboard-list" style="color:#605ca8;margin-right:6px;"></i> Catatan Hasil Diagnosa</h4>
            <span class="note">Diisi dengan tingkat efek terhadap hasil produk Molding serta tingkat efek terhadap life time mold yang lebih tinggi</span>
        </div>
        <div class="section-card-body">

            <div class="catatan-group">
                <label class="catatan-lbl">
                    <i class="fas fa-pen-nib"></i> Catatan
                </label>
                <textarea id="penilaian" name="penilaian">{{ $data_master->penilaian_keseluruhan }}</textarea>
            </div>

            <div class="catatan-group">
                <label class="catatan-lbl">
                    <i class="fas fa-history"></i> Pertimbangan dari Histori Repair Mold
                </label>
                <textarea id="pertimbangan" name="pertimbangan">{{ $data_master->pertimbangan_histori }}</textarea>
            </div>

            <div class="catatan-group">
                <label class="catatan-lbl">
                    <i class="fas fa-lightbulb"></i> Ide Tindakan Perbaikan
                    <span style="font-size:11px;color:#a0aec0;font-weight:400;font-style:italic;">— Lebih baik jika dapat diisi dengan perkiraan lama waktu dan biaya untuk repair</span>
                </label>
                <textarea id="ide_tindakan" name="ide_tindakan">{{ $data_master->tindakan_perbaikan }}</textarea>
            </div>

            <div style="display:flex;gap:12px;margin-top:24px;">
                <a href="{{ url('index/diagnose_molding/molding_form') }}" class="btn-back">
                    <i class="fas fa-times"></i> Batal
                </a>
                <button class="btn-save-eval" onclick="saveEvaluation()">
                    <i class="fas fa-save"></i> Simpan Evaluasi
                </button>
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
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var audio_success = new Audio('{{ url("sounds/success.mp3") }}');
    var audio_error   = new Audio('{{ url("sounds/error.mp3") }}');

    /* ── Summernote init ── */
    var summernoteConfig = {
        tabsize: 2,
        height: 120,
        toolbar: [
            ['style',  ['bold', 'italic', 'underline', 'clear']],
            ['font',   ['strikethrough']],
            ['para',   ['ul', 'ol']],
            ['view',   ['fullscreen']]
        ]
    };
    $('#penilaian').summernote($.extend({}, summernoteConfig,   { placeholder: 'Isi catatan penilaian keseluruhan...' }));
    $('#pertimbangan').summernote($.extend({}, summernoteConfig, { placeholder: 'Isi pertimbangan dari histori repair mold...' }));
    $('#ide_tindakan').summernote($.extend({}, summernoteConfig,  { placeholder: 'Isi ide tindakan perbaikan (perkiraan waktu & biaya)...' }));

    jQuery(document).ready(function () {
        $("#wrapper").toggleClass("toggled");
        $('#side_diagnosa_molding').addClass('menu-open');
        $('body').toggleClass("sidebar-collapse");
    });

    /* ── Save Evaluation ── */
    function saveEvaluation() {
        var penilaian    = $('#penilaian').summernote('code');
        var pertimbangan = $('#pertimbangan').summernote('code');
        var ide_tindakan = $('#ide_tindakan').summernote('code');
        var tgl_mulai    = $('#tgl_mulai').val();
        var periode      = $('#periode_produksi').text();
        var url_segments = window.location.href.split('/');
        var form_number  = url_segments[url_segments.length - 1];

        $.ajax({
            url: '{{ url("save/diagnose_molding/evaluation") }}',
            type: 'POST',
            data: {
                form_number:      form_number,
                penilaian:        penilaian,
                pertimbangan:     pertimbangan,
                ide_tindakan:     ide_tindakan,
                tgl_mulai:        tgl_mulai,
                periode_produksi: periode,
                qty_total:        $('#qty_total').text(),
            },
            success: function (response) {
                if (response.status) {
                    toastr.success(response.message);
                    audio_success.play();
                    setTimeout(function () {
                        window.location.href = '{{ url("index/diagnose_molding/molding_form") }}';
                    }, 2000);
                } else {
                    toastr.error(response.message);
                    audio_error.play();
                }
            },
            error: function () {
                toastr.error('Terjadi kesalahan. Coba lagi.');
                audio_error.play();
            }
        });
    }
</script>
@endsection