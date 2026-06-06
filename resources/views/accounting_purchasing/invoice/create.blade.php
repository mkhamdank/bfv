@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link rel="stylesheet" href="http://10.109.32.55:8879/f/css/dropzone.min.css" />
<script src="http://10.109.32.55:8879/f/javascript/dropzone.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background: #f0f2f7 !important; }

    body p, body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div, body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 24px 36px 22px;
        margin: 24px 0 24px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 14px;
        position: relative;
        overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -50px; top: -50px;
        width: 220px; height: 220px; border-radius: 50%;
        background: rgba(255,255,255,.05); pointer-events: none;
    }
    .page-header-modern::after {
        content: ''; position: absolute; left: -30px; bottom: -70px;
        width: 180px; height: 180px; border-radius: 50%;
        background: rgba(255,255,255,.04); pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 22px !important; font-weight: 700 !important; margin: 0 0 3px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    .btn-back {
        background: #fff; color: #4a4690;
        border: none; border-radius: 12px;
        padding: 12px 24px; font-size: 13px; font-weight: 700;
        cursor: pointer;
        display: inline-flex; align-items: center; gap: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,.18);
        text-decoration: none;
        transition: all .2s;
        position: relative; z-index: 10;
    }
    .btn-back:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }
    /* ══════════════════════════════════════
       LAYOUT
    ══════════════════════════════════════ */
    .create-layout {
        display: grid;
        grid-template-columns: 1fr 340px;
        gap: 20px;
        align-items: start;
        margin-bottom: 32px;
    }

    /* ══════════════════════════════════════
       FORM CARD
    ══════════════════════════════════════ */
    .form-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(96,92,168,.08);
        border: 1px solid rgba(96,92,168,.08);
        overflow: hidden;
    }

    .form-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .form-card-header .fch-icon {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex; align-items: center; justify-content: center;
        color: #fff; font-size: 14px; flex-shrink: 0;
    }
    .form-card-header .fch-title { font-size: 14px; font-weight: 700; color: #1a202c; }
    .form-card-header .fch-sub   { font-size: 12px; color: #a0aec0; margin-top: 1px; }

    .form-card-body { padding: 24px; }

    /* ══════════════════════════════════════
       FORM FIELDS
    ══════════════════════════════════════ */
    .form-grid-2 { display: grid; grid-template-columns: 1fr 1fr; gap: 16px 20px; }
    .form-grid-3 { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 16px 20px; }
    .col-span-2  { grid-column: span 2; }

    .ff { display: flex; flex-direction: column; gap: 6px; }

    .ff label {
        font-size: 11px; font-weight: 700; color: #4a5568;
        letter-spacing: .06em; text-transform: uppercase; margin: 0;
    }
    .ff label .req { color: #e03131; margin-left: 2px; }

    .ff input, .ff select, .ff textarea {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        padding: 10px 13px !important;
        font-size: 13.5px !important;
        color: #1a202c !important;
        background: #fafbff !important;
        outline: none !important;
        transition: border-color .18s, box-shadow .18s, background .18s !important;
        width: 100% !important;
    }
    .ff input:focus, .ff select:focus, .ff textarea:focus {
        border-color: #605ca8 !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[type=file] { padding: 8px 10px !important; cursor: pointer; background: #fff !important; }
    .ff textarea { resize: vertical; min-height: 80px; }

    .ff .iw { position: relative; }
    .ff .iw i {
        position: absolute; left: 12px; top: 50%;
        transform: translateY(-50%); color: #a0aec0; font-size: 13px;
        pointer-events: none; z-index: 1;
    }
    .ff .iw input {
        padding-left: 36px !important;
        background: #fafbff !important;
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        font-size: 13.5px !important;
        color: #1a202c !important;
        outline: none !important;
        transition: border-color .18s, box-shadow .18s !important;
        width: 100% !important;
    }
    .ff .iw input:focus {
        border-color: #605ca8 !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }

    /* Field hint */
    .ff-hint { font-size: 11.5px; color: #a0aec0; margin-top: 2px; }

    /* Section divider */
    .sec-div {
        display: flex; align-items: center; gap: 10px;
        margin: 24px 0 18px;
    }
    .sec-div span {
        font-size: 11px; font-weight: 700; letter-spacing: .08em;
        text-transform: uppercase; color: #a0aec0; white-space: nowrap;
    }
    .sec-div::before, .sec-div::after { content: ''; flex: 1; height: 1px; background: #edf0f5; }

    /* ══════════════════════════════════════
       FILE UPLOAD ZONE
    ══════════════════════════════════════ */
    .file-upload-zone {
        border: 2px dashed #c4bfef;
        border-radius: 12px;
        background: #faf9ff;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: all .2s;
        position: relative;
    }
    .file-upload-zone:hover { border-color: #605ca8; background: #f0eef9; }
    .file-upload-zone.has-file { border-color: #15803d; background: #f0fdf4; border-style: solid; }
    .file-upload-zone input[type=file] {
        position: absolute; inset: 0; opacity: 0; cursor: pointer;
        width: 100% !important; height: 100%; padding: 0 !important; border: none !important;
    }
    .fuz-icon { font-size: 28px; color: #c4bfef; margin-bottom: 8px; display: block; }
    .fuz-text { font-size: 13px; color: #718096; margin: 0 0 4px; font-weight: 500; }
    .fuz-sub  { font-size: 11.5px; color: #a0aec0; }
    .fuz-name { font-size: 13px; font-weight: 700; color: #15803d; margin-top: 6px; display: none; }

    /* ══════════════════════════════════════
       SIDEBAR CARDS
    ══════════════════════════════════════ */
    .side-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(96,92,168,.08);
        border: 1px solid rgba(96,92,168,.08);
        overflow: hidden;
        margin-bottom: 16px;
    }
    .side-card-header {
        padding: 14px 20px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        font-size: 13px; font-weight: 700; color: #1a202c;
        display: flex; align-items: center; gap: 8px;
    }
    .side-card-header i { color: #605ca8; font-size: 13px; }
    .side-card-body { padding: 18px 20px; }

    /* Summary items */
    .summary-item {
        display: flex; justify-content: space-between; align-items: flex-start;
        gap: 10px; padding: 9px 0;
        border-bottom: 1px solid #f5f4fb;
        font-size: 12.5px;
    }
    .summary-item:last-child { border-bottom: none; padding-bottom: 0; }
    .si-label { color: #718096; font-weight: 500; flex-shrink: 0; }
    .si-value { color: #1a202c; font-weight: 600; text-align: right; word-break: break-all; }
    .si-value.empty { color: #c4bfef; font-style: italic; font-weight: 400; }

    /* Tips */
    .tip-item {
        display: flex; gap: 10px; align-items: flex-start;
        padding: 8px 0; border-bottom: 1px solid #f5f4fb; font-size: 12.5px;
    }
    .tip-item:last-child { border-bottom: none; }
    .tip-icon { width: 22px; height: 22px; border-radius: 6px; display: flex; align-items: center; justify-content: center; font-size: 11px; flex-shrink: 0; margin-top: 1px; }
    .tip-icon.blue   { background: #dbeafe; color: #1d4ed8; }
    .tip-icon.amber  { background: #fef3c7; color: #b45309; }
    .tip-icon.green  { background: #dcfce7; color: #15803d; }
    .tip-text { color: #4a5568; line-height: 1.5; }

    /* OCR Section */
    .ocr-wrap {
        border: 1.5px dashed #c4bfef;
        border-radius: 12px;
        padding: 16px;
        background: #faf9ff;
    }
    .ocr-head { display: flex; align-items: center; gap: 8px; flex-wrap: wrap; margin-bottom: 12px; }
    .ocr-head h6 { font-size: 13px; font-weight: 700; color: #1a202c; margin: 0; }
    .badge-beta {
        font-size: 9.5px; font-weight: 700; background: #fef3c7;
        color: #92400e; padding: 2px 8px; border-radius: 20px;
        letter-spacing: .05em; text-transform: uppercase;
    }
    .ocr-stat { font-size: 11px; font-weight: 600; padding: 2px 9px; border-radius: 20px; }
    .ocr-online   { background: #dcfce7; color: #15803d; }
    .ocr-offline  { background: #fee2e2; color: #991b1b; }
    .ocr-checking { background: #f0eef9;  color: #605ca8; }

    .ocr-dropzone {
        border: 2px dashed #c4bfef; border-radius: 10px;
        background: #fff; padding: 18px 14px;
        text-align: center; cursor: pointer;
        transition: all .18s;
    }
    .ocr-dropzone:hover { border-color: #605ca8; background: #f0eef9; }
    .ocr-dropzone i { font-size: 22px; color: #c4bfef; display: block; margin-bottom: 6px; }
    .ocr-dropzone p { font-size: 12px; color: #718096; margin: 0; }
    .ocr-dropzone small { font-size: 11px; color: #c4bfef; }

    .btn-ocr {
        display: inline-flex; align-items: center; gap: 6px;
        margin-top: 10px; padding: 7px 14px; border-radius: 8px;
        background: #f0eef9; color: #605ca8;
        border: 1.5px solid #e2dff5; font-size: 12px; font-weight: 700;
        cursor: pointer; transition: all .18s; width: 100%; justify-content: center;
    }
    .btn-ocr:hover { background: #e8e4f8; }
    .btn-ocr:disabled { opacity: .5; cursor: not-allowed; }

    /* ══════════════════════════════════════
       SUBMIT BAR
    ══════════════════════════════════════ */
    .submit-bar {
        display: flex; gap: 12px; align-items: center;
        padding: 20px 24px;
        border-top: 1px solid #f0f2f7;
        background: #fafbff;
    }
    .btn-submit {
        flex: 1; padding: 13px;
        border: none; border-radius: 10px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff; font-size: 14px; font-weight: 700;
        cursor: pointer; display: flex; align-items: center; justify-content: center; gap: 8px;
        box-shadow: 0 4px 14px rgba(96,92,168,.35);
        transition: all .2s;
    }
    .btn-submit:hover { opacity: .88; transform: translateY(-1px); box-shadow: 0 8px 20px rgba(96,92,168,.4); }
    .btn-submit:active { transform: translateY(0); }

    .btn-reset {
        padding: 13px 24px;
        border: 1.5px solid #e2e8f0; border-radius: 10px;
        background: #fff; color: #718096; font-size: 13.5px; font-weight: 600;
        cursor: pointer; transition: all .2s;
    }
    .btn-reset:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }

    /* ══════════════════════════════════════
       SELECT2
    ══════════════════════════════════════ */
    .select2-container .select2-selection--single {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 9px !important;
        height: 42px !important;
        background: #fafbff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__placeholder {
        display: inline-flex !important;
        align-items: center !important;
        color: #a0aec0 !important;
        width: 100% !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 40px !important; }
    .select2-container--focus .select2-selection--single,
    .select2-container--open  .select2-selection--single {
        border-color: #605ca8 !important;
        box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
        outline: none !important;
    }
    .select2-dropdown { border: 1.5px solid #e2e8f0 !important; border-radius: 12px !important; box-shadow: 0 8px 32px rgba(96,92,168,.12) !important; }
    .select2-results__option--highlighted { background: #605ca8 !important; }
    .select2-search--dropdown .select2-search__field { border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important; padding: 6px 10px !important; outline: none !important; }

    /* Loading overlay */
    #loading { display: none; position: fixed; inset: 0; background: rgba(30,31,58,.35); backdrop-filter: blur(4px); z-index: 9999; align-items: center; justify-content: center; }
    #loading.show-flex { display: flex; }
    .loading-box { background: #fff; border-radius: 20px; padding: 36px 48px; display: flex; flex-direction: column; align-items: center; gap: 14px; box-shadow: 0 12px 40px rgba(0,0,0,.15); }
    .loading-spinner { width: 42px; height: 42px; border: 3px solid #e2e8f0; border-top-color: #605ca8; border-radius: 50%; animation: spin .75s linear infinite; }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }
</style>
@endsection


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Menyimpan data...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-file-invoice"></i> Invoice Management</div>
            <h1>Buat Tanda Terima</h1>
            <p>Isi formulir di bawah untuk membuat invoice tanda terima baru</p>
        </div>
        <a href="{{ url('index/invoice/tanda_terima') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i> Kembali ke Daftar
        </a>
    </div>

    {{-- ── MAIN LAYOUT ── --}}
    <div class="create-layout">

        {{-- ════════════════════
             FORM CARD (LEFT)
        ════════════════════ --}}
        <div>
            <form id="createForm" method="POST" enctype="multipart/form-data">
                @csrf

                {{-- ── Card: Info Supplier ── --}}
                <div class="form-card" style="margin-bottom:16px;">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-building"></i></div>
                        <div>
                            <div class="fch-title">Informasi Supplier</div>
                            <div class="fch-sub">Pilih supplier dan metode pembayaran</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="form-grid-2">
                            <div class="ff">
                                <label>Supplier Name <span class="req">*</span></label>
                                <select class="select4" id="supplier_code" name="supplier_code"
                                        data-placeholder="Cari & pilih supplier..." onchange="getSupplier(this)">
                                    <option value="">&nbsp;</option>
                                    <option value="SUP001">SUP001 - PT. Supplier Indonesia</option>
                                    <option value="SUP002">SUP002 - CV. Mitra Usaha</option>
                                    <option value="SUP003">SUP003 - PT. Global Trade</option>
                                    <option value="SUP004">SUP004 - UD. Jaya Perkasa</option>
                                    <option value="SUP005">SUP005 - PT. Sinar Jaya</option>
                                </select>
                                <input type="hidden" id="supplier_name" name="supplier_name">
                            </div>
                            <div class="ff">
                                <label>Payment Term <span class="req">*</span></label>
                                <select class="select4" id="payment_term" name="payment_term"
                                        data-placeholder="Pilih metode pembayaran...">
                                    <option value="">&nbsp;</option>
                                    <option value="Immediate Payment">Immediate Payment</option>
                                    <option value="End of Month">End of Month</option>
                                    <option value="End.of Next Month After Rec.Date">End.of Next Month After Rec.Date</option>
                                    <option value="7 Days">7 Days</option>
                                    <option value="14 Days">14 Days</option>
                                    <option value="30 Days">30 Days</option>
                                </select>
                            </div>
                            <div class="ff">
                                <label>NPWP</label>
                                <input type="text" id="npwp" name="npwp" placeholder="Nomor Pokok Wajib Pajak">
                            </div>
                            <div class="ff">
                                <label>Faktur Pajak</label>
                                <input type="text" id="faktur_pajak" name="faktur_pajak" placeholder="Nomor Faktur Pajak">
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Card: Info Invoice ── --}}
                <div class="form-card" style="margin-bottom:16px;">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-file-invoice"></i></div>
                        <div>
                            <div class="fch-title">Detail Invoice</div>
                            <div class="fch-sub">Nomor invoice, tanggal, dan referensi dokumen</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="form-grid-2">
                            <div class="ff">
                                <label>Invoice No <span class="req">*</span></label>
                                <input type="text" id="invoice_no" name="invoice_no"
                                       placeholder="Contoh: INV/2025/001" oninput="updateSummary()">
                            </div>
                            <div class="ff">
                                <label>PO Number <span class="req">*</span></label>
                                <input type="text" id="po_number" name="po_number"
                                       placeholder="Contoh: PO-2025-0011" oninput="updateSummary()">
                            </div>
                            <div class="ff">
                                <label>Invoice Date <span class="req">*</span></label>
                                <div class="iw">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" class="datepicker" id="invoice_date" name="invoice_date"
                                           placeholder="yyyy-mm-dd" oninput="updateSummary()">
                                </div>
                            </div>
                            
                            <div class="ff">
                                <label>Surat Jalan</label>
                                <input type="text" id="surat_jalan" name="surat_jalan"
                                       placeholder="Nomor Surat Jalan">
                            </div>
                            <div class="ff">
                                <label>BAP</label>
                                <input type="text" id="bap" name="bap"
                                       placeholder="Berita Acara Pemeriksaan">
                            </div>
                            <div class="ff">
                                <label>DO Date <span class="req">*</span></label>
                                <div class="iw">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" class="datepicker" id="do_date" name="do_date"
                                           placeholder="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="ff">
                                <label>Due Date <span class="req">*</span></label>
                                <div class="iw">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" class="datepicker" id="due_date" name="due_date"
                                           placeholder="yyyy-mm-dd">
                                </div>
                            </div>
                            <div class="ff">
                                <label>Distribution Date</label>
                                <div class="iw">
                                    <i class="fas fa-calendar-alt"></i>
                                    <input type="date" class="datepicker" id="distribution_date" name="distribution_date"
                                           placeholder="yyyy-mm-dd">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- ── Card: Keuangan ── --}}
                <div class="form-card" style="margin-bottom:16px;">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-coins"></i></div>
                        <div>
                            <div class="fch-title">Informasi Keuangan</div>
                            <div class="fch-sub">Mata uang, jumlah, dan detail pembayaran</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="form-grid-3" style="margin-bottom:16px;">
                            <div class="ff">
                                <label>Currency <span class="req">*</span></label>
                                <select class="select4" id="currency" name="currency"
                                        data-placeholder="Mata Uang" onchange="updateSummary()">
                                    <option value="IDR">IDR</option>
                                    <option value="USD">USD</option>
                                    <option value="JPY">JPY</option>
                                </select>
                            </div>
                            <div class="ff col-span-2">
                                <label>Amount <span class="req">*</span></label>
                                <input type="text" id="amount" name="amount"
                                       placeholder="Masukkan total amount" oninput="updateSummary()">
                            </div>
                        </div>
                        <div class="ff">
                            <label>Detail Payment <span class="req">*</span></label>
                            <textarea id="detail_item" name="detail_item"
                                      placeholder="Deskripsi item atau keterangan pembayaran..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- ── Card: File Invoice ── --}}
                <div class="form-card" style="margin-bottom:16px;">
                    <div class="form-card-header">
                        <div class="fch-icon"><i class="fas fa-paperclip"></i></div>
                        <div>
                            <div class="fch-title">File Invoice</div>
                            <div class="fch-sub">Upload file PDF invoice dari supplier</div>
                        </div>
                    </div>
                    <div class="form-card-body">
                        <div class="file-upload-zone" id="fileZone">
                            <input type="file" id="file_attach" name="file_attach"
                                   accept="application/pdf" onchange="onFileChange(this)">
                            <i class="fas fa-cloud-upload-alt fuz-icon" id="fuz-icon"></i>
                            <p class="fuz-text">Klik atau drag & drop file PDF di sini</p>
                            <p class="fuz-sub">Hanya file PDF yang diperbolehkan • Maks. 10 MB</p>
                            <p class="fuz-name" id="fuz-name"></p>
                        </div>
                    </div>
                </div>

                {{-- ── Submit Bar ── --}}
                <div class="submit-bar" style="border-radius:0 0 16px 16px;border:1px solid rgba(96,92,168,.08);border-top:1px solid #f0f2f7;">
                    <button type="button" class="btn-reset" onclick="resetForm()">
                        <i class="fas fa-undo"></i> Reset
                    </button>
                    <button type="button" class="btn-submit" onclick="SaveInvoice()">
                        <i class="fas fa-check"></i> Simpan Tanda Terima
                    </button>
                </div>

            </form>
        </div>

        {{-- ════════════════════
             SIDEBAR (RIGHT)
        ════════════════════ --}}
        <div>

            {{-- Tips Card --}}
            <div class="side-card">
                <div class="side-card-header">
                    <i class="fas fa-lightbulb"></i> Panduan Pengisian
                </div>
                <div class="side-card-body">
                    <div class="tip-item">
                        <div class="tip-icon blue"><i class="fas fa-star"></i></div>
                        <div class="tip-text">Field bertanda <strong style="color:#e03131;">*</strong> wajib diisi sebelum menyimpan.</div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon amber"><i class="fas fa-file-pdf"></i></div>
                        <div class="tip-text">File invoice wajib dilampirkan dalam format <strong>PDF</strong>.</div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon green"><i class="fas fa-magic"></i></div>
                        <div class="tip-text">Gunakan fitur <strong>OCR</strong> untuk mengisi form secara otomatis dari dokumen.</div>
                    </div>
                    <div class="tip-item">
                        <div class="tip-icon blue"><i class="fas fa-calendar-check"></i></div>
                        <div class="tip-text"><strong>Due Date</strong> diisi manual sesuai kesepakatan dengan supplier.</div>
                    </div>
                </div>
            </div>

            {{-- OCR Card --}}
            <div class="side-card">
                <div class="side-card-header">
                    <i class="fas fa-magic"></i> OCR Processing
                </div>
                <div class="side-card-body">
                    <div class="ocr-wrap">
                        <div class="ocr-head">
                            <h6>MIRAI AI OCR</h6>
                            <span class="badge-beta">Beta</span>
                            <span id="serverStatusMessage" class="ocr-stat ocr-checking">
                                <i class="fas fa-circle-notch fa-spin"></i> Checking...
                            </span>
                        </div>
                        <p style="font-size:12px;color:#718096;margin:0 0 10px;line-height:1.6;">
                            Upload dokumen invoice untuk mengisi form secara otomatis.
                            <strong style="color:#e03131;">Pastikan cek ulang hasil OCR.</strong>
                        </p>
                        <form action="" class="ocr-dropzone dropzone" id="ocrDropzone">
                            <div class="dz-message" data-dz-message>
                                <i class="fas fa-cloud-upload-alt"></i>
                                <p>Drop file di sini atau klik upload</p>
                                <small>PDF atau gambar</small>
                            </div>
                            <div class="fallback">
                                <input name="file" type="file" accept="application/pdf,image/*">
                            </div>
                        </form>
                        <div id="ocrProcessingStatus" style="display:none;margin:10px 0 0;">
                            <div class="progress" style="border-radius:8px;overflow:hidden;height:7px;">
                                <div class="progress-bar progress-bar-striped active" role="progressbar"
                                     style="width:100%;background:#605ca8"></div>
                            </div>
                            <small style="color:#718096;font-size:11.5px;">Memproses dokumen...</small>
                        </div>
                        <button type="button" class="btn-ocr" id="processOcrBtn" disabled>
                            <i class="fas fa-file-alt"></i> Process OCR
                        </button>
                    </div>
                </div>
            </div>

            {{-- Summary Card --}}
            <div class="side-card">
                <div class="side-card-header">
                    <i class="fas fa-receipt"></i> Ringkasan Invoice
                </div>
                <div class="side-card-body">
                    <div class="summary-item">
                        <span class="si-label">Supplier</span>
                        <span class="si-value empty" id="sum-supplier">Belum dipilih</span>
                    </div>
                    <div class="summary-item">
                        <span class="si-label">Invoice No</span>
                        <span class="si-value empty" id="sum-inv">—</span>
                    </div>
                    <div class="summary-item">
                        <span class="si-label">PO Number</span>
                        <span class="si-value empty" id="sum-po">—</span>
                    </div>
                    <div class="summary-item">
                        <span class="si-label">Tgl Invoice</span>
                        <span class="si-value empty" id="sum-date">—</span>
                    </div>
                    <div class="summary-item">
                        <span class="si-label">Currency</span>
                        <span class="si-value empty" id="sum-curr">—</span>
                    </div>
                    <div class="summary-item" style="border-bottom:none;padding-top:12px;margin-top:4px;">
                        <span class="si-label" style="font-size:13px;font-weight:700;color:#1a202c;">Total</span>
                        <span class="si-value empty" id="sum-amount" style="font-size:16px;font-weight:800;color:#605ca8;">—</span>
                    </div>
                </div>
            </div>

        </div>
    </div>

</div>
@endsection


@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var payment_term_all = [
        { payment_term: 'Immediate Payment' },
        { payment_term: 'End of Month' },
        { payment_term: 'End.of Next Month After Rec.Date' },
        { payment_term: '7 Days' },
        { payment_term: '14 Days' },
        { payment_term: '30 Days' }
    ];
    var vendor_all = [
        { vendor_code: 'SUP001', supplier_name: 'PT. Supplier Indonesia', supplier_duration: 'End of Month' },
        { vendor_code: 'SUP002', supplier_name: 'CV. Mitra Usaha',        supplier_duration: '30 Days' },
        { vendor_code: 'SUP003', supplier_name: 'PT. Global Trade',        supplier_duration: '14 Days' },
        { vendor_code: 'SUP004', supplier_name: 'UD. Jaya Perkasa',        supplier_duration: '7 Days' },
        { vendor_code: 'SUP005', supplier_name: 'PT. Sinar Jaya',          supplier_duration: 'End.of Next Month After Rec.Date' }
    ];

    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');
    var audio_ok    = new Audio('{{ url("sounds/sukses.mp3") }}');

    jQuery(document).ready(function () {
        $('#side_tanda_terima').addClass('menu-open');
        $('body').toggleClass("sidebar-collapse");

        $('input[type="text"].datepicker').datepicker({ autoclose: true, format: "yyyy-mm-dd", todayHighlight: true });
        $('.select4').select2({ allowClear: true, dropdownAutoWidth: true, tags: true });

        /* Default invoice date = hari ini */
        var now = new Date();
        var y = now.getFullYear();
        var m = String(now.getMonth()+1).padStart(2,'0');
        var d = String(now.getDate()).padStart(2,'0');
        $('#invoice_date').val(y+'-'+m+'-'+d);

        /* Default currency */
        $('#currency').val('IDR').trigger('change');
        $('#payment_term').val('End.of Next Month After Rec.Date').trigger('change');

        updateSummary();
        checkOcrServerAvailability();
        setInterval(checkOcrServerAvailability, 5000);

        /* Live summary update on select2 change */
        $('#supplier_code').on('change', function() { updateSummary(); });
        $('#currency').on('change', function() { updateSummary(); });
        $('#invoice_date').on('change', function() { updateSummary(); });
    });

    /* ── Loading ── */
    function showLoading() { $('#loading').addClass('show-flex'); }
    function hideLoading() { $('#loading').removeClass('show-flex'); }

    /* ── File input ── */
    function onFileChange(input) {
        var file = input.files[0];
        var zone = document.getElementById('fileZone');
        var name = document.getElementById('fuz-name');
        var ico  = document.getElementById('fuz-icon');
        if (file) {
            zone.classList.add('has-file');
            name.innerText = file.name;
            name.style.display = 'block';
            ico.style.color = '#15803d';
        } else {
            zone.classList.remove('has-file');
            name.style.display = 'none';
            ico.style.color = '#c4bfef';
        }
    }

    /* ── Live summary ── */
    function updateSummary() {
        var supCode = $('#supplier_code').val();
        var supName = vendor_all.find(function(v){ return v.vendor_code == supCode; });
        var invNo   = $('#invoice_no').val();
        var poNo    = $('#po_number').val();
        var date    = $('#invoice_date').val();
        var curr    = $('#currency').val() || '—';
        var amount  = $('#amount').val();

        setSumVal('sum-supplier', supName ? supName.supplier_name : null);
        setSumVal('sum-inv',    invNo);
        setSumVal('sum-po',     poNo);
        setSumVal('sum-date',   date);
        setSumVal('sum-curr',   curr);
        setSumVal('sum-amount', amount ? curr + ' ' + amount : null);
    }

    function setSumVal(id, val) {
        var el = document.getElementById(id);
        if (val && val.trim && val.trim() !== '' && val !== '—') {
            el.innerText = val;
            el.classList.remove('empty');
        } else {
            el.innerText = '—';
            el.classList.add('empty');
        }
    }

    /* ── Supplier change ── */
    function getSupplier(elem) {
        var isi  = elem.value;
        var list = '<option value=""></option>';
        var name = '';
        $('#payment_term').html('');
        vendor_all.forEach(function(v) {
            if (v.vendor_code == isi) {
                list += '<option value="'+v.supplier_duration+'" selected>'+v.supplier_duration+'</option>';
                name  = v.supplier_name;
            }
        });
        payment_term_all.forEach(function(p) {
            list += '<option value="'+p.payment_term+'">'+p.payment_term+'</option>';
        });
        $('#payment_term').append(list);
        $('#supplier_name').val(name);
        updateSummary();
    }

    /* ── Validate & Save ── */
    function SaveInvoice() {
        var required = [
            { id: 'supplier_code', label: 'Supplier Name' },
            { id: 'payment_term',  label: 'Payment Term' },
            { id: 'invoice_no',    label: 'Invoice No' },
            { id: 'po_number',     label: 'PO Number' },
            { id: 'invoice_date',  label: 'Invoice Date' },
            { id: 'do_date',       label: 'DO Date' },
            { id: 'due_date',      label: 'Due Date' },
            { id: 'currency',      label: 'Currency' },
            { id: 'amount',        label: 'Amount' },
            { id: 'detail_item',   label: 'Detail Payment' },
        ];
        for (var i = 0; i < required.length; i++) {
            var val = $('#'+required[i].id).val();
            if (!val || (Array.isArray(val) && !val.length)) {
                openErrorGritter('Validasi Gagal', required[i].label + ' wajib diisi.'); return;
            }
        }
        if (!$('#file_attach').val()) {
            openErrorGritter('Validasi Gagal', 'File invoice wajib dilampirkan.'); return;
        }
        showLoading();
        var formData = new FormData();
        formData.append('category', 'General');
        ['invoice_date','supplier_code','supplier_name','invoice_no','surat_jalan','bap','npwp',
         'faktur_pajak','po_number','payment_term','currency','amount','do_date','due_date',
         'detail_item','distribution_date'].forEach(function(f){ formData.append(f, $('#'+f).val()||''); });
        formData.append('file_attach', $('#file_attach').prop('files')[0]);

        $.ajax({
            url: '{{ url("create/invoice/tanda_terima") }}',
            method: 'POST', data: formData, dataType: 'JSON',
            contentType: false, cache: false, processData: false,
            success: function(data) {
                if (data.status) {
                    openSuccessGritter('Berhasil', data.message);
                    audio_ok.play();
                    hideLoading();
                    setTimeout(function(){ window.location.href = '{{ route("admin.vfi.index") }}'; }, 1800);
                } else {
                    openErrorGritter('Error!', data.message);
                    hideLoading();
                    audio_error.play();
                }
            },
            error: function() { openErrorGritter('Error', 'Terjadi kesalahan, coba lagi.'); hideLoading(); }
        });
    }

    /* ── Reset ── */
    function resetForm() {
        if (!confirm('Reset semua isian form?')) return;
        $('#createForm')[0].reset();
        $('#supplier_code,#payment_term,#currency').val('').trigger('change');
        $('#currency').val('IDR').trigger('change');
        document.getElementById('fileZone').classList.remove('has-file');
        document.getElementById('fuz-name').style.display = 'none';
        document.getElementById('fuz-icon').style.color = '#c4bfef';
        updateSummary();
    }

    /* ── OCR ── */
    function checkOcrServerAvailability() {
        $.ajax({ url: 'http://10.109.44.70:3001/api/health', method: 'GET', timeout: 5000,
            success: function() {
                $('#processOcrBtn').prop('disabled', false);
                $('#serverStatusMessage').removeClass('ocr-checking ocr-offline').addClass('ocr-online').html('<i class="fas fa-check"></i> Online');
            },
            error: function() {
                $('#processOcrBtn').prop('disabled', true);
                $('#serverStatusMessage').removeClass('ocr-checking ocr-online').addClass('ocr-offline').html('<i class="fas fa-times"></i> Offline');
            }
        });
    }

    Dropzone.autoDiscover = false;
    var ocrDropzone = null;
    $(document).ready(function () {
        ocrDropzone = new Dropzone('#ocrDropzone', {
            url: 'http://10.109.44.70:3001/api/ocr/invoice-trial?user={{ Auth::user()->username . "|" . Auth::user()->name }}',
            method: 'post', acceptedFiles: 'image/*,.pdf', autoProcessQueue: false, addRemoveLinks: true,
            init: function () {
                this.on('success', function (file, response) {
                    $('#ocrProcessingStatus').hide();
                    openSuccessGritter('OCR Selesai', 'Formulir telah diisi otomatis. Harap periksa kembali.');
                    var d = response.data;
                    $('#invoice_date').val(d.invoice_date);
                    $('#supplier_code').val(d.vendor_code).trigger('change');
                    $('.select4').select2({ allowClear: true, dropdownAutoWidth: true, tags: true });
                    $('#supplier_name').val(d.vendor_details.supplier_name);
                    $('#invoice_no').val(d.invoice_number);
                    $('#surat_jalan').val(d.surat_jalan_number);
                    $('#bap').val(d.bap_number);
                    $('#npwp').val(d.vendor_details.supplier_npwp || '');
                    $('#faktur_pajak').val(d.faktur_pajak);
                    $('#po_number').val(d.po_number);
                    $('#payment_term').val('End.of Next Month After Rec.Date').trigger('change');
                    $('#currency').val(d.currency).trigger('change');
                    $('#amount').val(Number(d.total_amount).toFixed(2));
                    $('#do_date').val(d.do_date);
                    $('#due_date').val(d.due_date);
                    $('#detail_item').val(d.detail_payment);
                    updateSummary();
                });
                this.on('error', function (file, response) { $('#ocrProcessingStatus').hide(); openErrorGritter('OCR Error', response); });
            }
        });
        $('#processOcrBtn').on('click', function () {
            if (ocrDropzone.getQueuedFiles().length > 0) { $('#ocrProcessingStatus').show(); ocrDropzone.processQueue(); }
            else { openErrorGritter('OCR', 'Harap upload file terlebih dahulu.'); }
        });
    });

    /* ── Gritter ── */
    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection