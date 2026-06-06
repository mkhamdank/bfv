@extends('layouts.master')

@section('styles')
<link href="{{ url('css/toastr.min.css') }}" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.css" rel="stylesheet">
<script src="https://bossanova.uk/jspreadsheet/v5/jspreadsheet.js"></script>
<!-- <script src="https://jsuites.net/v5/jsuites.js"></script> -->
<link rel="stylesheet" href="https://bossanova.uk/jspreadsheet/v5/jspreadsheet.css">
<link rel="stylesheet" href="https://jsuites.net/v5/jsuites.css">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background: #f0f2f7 !important; }

    body p, body span:not([class*="fa"]):not([class*="glyphicon"]):not([class*="jss"]):not([class*="note"]),
    body div:not([class*="note"]), body label, body input, body select, body textarea,
    body button, body a, body td, body th,
    body h1, body h2, body h3, body h4, body h5, body h6, body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }

    /* ── Loading ── */
    #loading {
        display: none; position: fixed; inset: 0;
        background: rgba(30,31,58,.4); backdrop-filter: blur(5px);
        z-index: 30001; align-items: center; justify-content: center;
    }
    #loading.show { display: flex !important; }
    .loading-box {
        background: #fff; border-radius: 20px; padding: 36px 48px;
        display: flex; flex-direction: column; align-items: center;
        gap: 14px; box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px; border: 3px solid #ede9fe;
        border-top-color: #605ca8; border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══════════════════════════════════════
       PAGE HEADER
    ══════════════════════════════════════ */
    .page-header-modern {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 50%, #605ca8 100%);
        padding: 28px 36px 24px; margin: 24px 0 24px;
        border-radius: 18px; display: flex; align-items: center;
        justify-content: space-between; flex-wrap: wrap; gap: 16px;
        position: relative; overflow: hidden;
    }
    .page-header-modern::before {
        content: ''; position: absolute; right: -40px; top: -40px;
        width: 200px; height: 200px; border-radius: 50%;
        background: rgba(255,255,255,.04); pointer-events: none;
    }
    .header-left .badge-tag {
        display: inline-flex; align-items: center; gap: 6px;
        background: rgba(255,255,255,.14); border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0; font-size: 11px; font-weight: 700; letter-spacing: 1.2px;
        text-transform: uppercase; padding: 5px 14px; border-radius: 20px; margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    /* ══════════════════════════════════════
       STAT CARDS
    ══════════════════════════════════════ */
    .stat-row { display: grid; grid-template-columns: repeat(4,1fr); gap: 14px; margin-bottom: 24px; }
    .stat-card {
        background: #fff; border-radius: 14px; padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05); border: 1px solid rgba(0,0,0,.05);
        display: flex; align-items: center; gap: 14px;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
    .stat-icon { width: 44px; height: 44px; border-radius: 12px; display: flex; align-items: center; justify-content: center; font-size: 17px; flex-shrink: 0; }
    .si-purple { background: #ede9fe; color: #7c3aed; }
    .si-green  { background: #dcfce7; color: #15803d; }
    .si-red    { background: #fee2e2; color: #dc2626; }
    .si-blue   { background: #dbeafe; color: #1d4ed8; }
    .stat-val  { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; margin-bottom: 2px; }
    .stat-lbl  { font-size: 12px; color: #718096; font-weight: 500; }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff; border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06); border: 1px solid rgba(0,0,0,.05);
        overflow: hidden; margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px; border-bottom: 1px solid #f0f2f7; background: #fafbff;
        display: flex; align-items: center; justify-content: space-between;
    }
    .table-card-title { font-size: 14px; font-weight: 700; color: #1a202c; display: flex; align-items: center; gap: 10px; }
    .table-card-title .dot { width: 8px; height: 8px; background: #605ca8; border-radius: 50%; }

    /* ── DataTable ── */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_filter label, .dataTables_length label { font-size: 13px !important; color: #4a5568 !important; }
    .dataTables_filter input, .dataTables_length select {
        border: 1.5px solid #e2e8f0 !important; border-radius: 8px !important;
        padding: 6px 10px !important; font-size: 13px !important; outline: none !important;
    }
    /* Sembunyikan scrollFoot bawaan DataTables */
    .dataTables_scrollFoot,
    .dataTables_scrollFootInner { display: none !important; }

    table.dataTable { border-collapse: collapse !important; table-layout: auto !important; width: 100% !important; }
    table.dataTable thead th {
        background: #f7f8fc !important; color: #718096 !important;
        font-size: 11px !important; font-weight: 700 !important; letter-spacing: .7px !important;
        text-transform: uppercase !important; padding: 10px 12px !important; height: 42px !important;
        border-bottom: 2px solid #edf0f5 !important; border-top: none !important; white-space: nowrap !important;
        vertical-align: middle !important;
    }
    #tableMaster thead th:nth-child(1) { width: 44px; min-width: 44px; }
    #tableMaster thead th:nth-child(2) { width: 130px; min-width: 110px; }
    #tableMaster thead th:nth-child(3) { min-width: 180px; }
    #tableMaster thead th:nth-child(4) { width: 110px; min-width: 90px; }
    #tableMaster thead th:nth-child(5) { width: 170px; min-width: 140px; }
    #tableMaster thead th:nth-child(6) { width: 130px; min-width: 110px; }
    #tableMaster thead th:nth-child(7) { min-width: 180px; }
    /* column search row */
    table.dataTable thead tr:nth-child(2) th { padding: 6px 8px !important; background: #fff !important; border-bottom: 1px solid #edf0f5 !important; height: auto !important; }
    table.dataTable thead tr:nth-child(2) th input {
        width: 100%; border: 1.5px solid #e2e8f0; border-radius: 6px;
        padding: 5px 8px; font-size: 11.5px; color: #1a202c; background: #faf9ff; outline: none;
    }
    table.dataTable thead tr:nth-child(2) th input:focus { border-color: #605ca8; }
    table.dataTable tbody tr:hover td { background: #f5f8ff !important; }
    table.dataTable tbody td {
        padding: 10px 12px !important; font-size: 13px !important; color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important; border-top: none !important; vertical-align: middle !important;
    }
    #tableMaster tbody td:nth-child(1) { text-align: center !important; }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }
    .dataTables_info { font-size: 12px !important; color: #718096 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important; font-size: 13px !important; font-weight: 600 !important;
        color: #4a5568 !important; border: 1px solid transparent !important;
        padding: 5px 10px !important; margin: 0 2px !important;
    }
    .dataTables_paginate .paginate_button:hover { background: #ede9fe !important; color: #605ca8 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg,#4a4690,#605ca8) !important; color: #fff !important; }

    /* ══════════════════════════════════════
       TABLE CELL ELEMENTS
    ══════════════════════════════════════ */
    .molding-cell { display: flex; align-items: center; gap: 9px; }
    .m-avatar {
        width: 32px; height: 32px; border-radius: 9px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex; align-items: center; justify-content: center;
        font-size: 11px; font-weight: 700; color: #fff; flex-shrink: 0;
    }
    .m-name { font-weight: 600; color: #1a202c; font-size: 13px; }
    .m-fa   { font-size: 11px; color: #a0aec0; margin-top: 1px; }

    /* Shot progress */
    .shot-wrap { width: 100%; }
    .shot-nums { display: flex; align-items: baseline; gap: 3px; margin-bottom: 5px; }
    .shot-cur  { font-size: 14px; font-weight: 700; color: #1a202c; }
    .shot-sep, .shot-std { font-size: 11px; color: #a0aec0; }
    .shot-bar  { height: 5px; border-radius: 10px; background: #edf0f5; overflow: hidden; }
    .shot-fill { height: 100%; border-radius: 10px; transition: width .4s; }

    /* Status pills */
    .pill { display: inline-flex; align-items: center; gap: 5px; padding: 4px 11px; border-radius: 20px; font-size: 11.5px; font-weight: 700; white-space: nowrap; }
    .pill::before { content: ''; width: 5px; height: 5px; border-radius: 50%; display: inline-block; }
    .pill-normal { background: #dcfce7; color: #15803d; }
    .pill-normal::before { background: #22c55e; }
    .pill-butuh  { background: #fee2e2; color: #991b1b; }
    .pill-butuh::before  { background: #ef4444; }
    .pill-proses { background: #dbeafe; color: #1e40af; }
    .pill-proses::before { background: #3b82f6; }

    /* Action buttons */
    .action-group { display: flex; gap: 4px; align-items: center; flex-wrap: wrap; }
    .btn-act {
        display: inline-flex; align-items: center; justify-content: center;
        width: 28px; height: 28px; border-radius: 8px; border: none;
        cursor: pointer; font-size: 12px; transition: opacity .18s, transform .15s;
        text-decoration: none; flex-shrink: 0;
    }
    .btn-act:hover { opacity: .8; transform: scale(1.08); text-decoration: none; }
    .btn-act.a-shot  { background: #fef3c7; color: #92400e; }
    .btn-act.a-rusak { background: #fee2e2; color: #991b1b; }
    .btn-act.a-buat  { background: #dcfce7; color: #15803d; width: auto; padding: 0 10px; font-size: 11.5px; font-weight: 700; gap: 4px; }
    .btn-act.a-lihat { background: #dbeafe; color: #1e40af; width: auto; padding: 0 10px; font-size: 11.5px; font-weight: 700; gap: 4px; }
    .btn-act.a-kirim { background: #ede9fe; color: #7c3aed; width: auto; padding: 0 10px; font-size: 11.5px; font-weight: 700; gap: 4px; }

    /* ══════════════════════════════════════
       MODALS — common
    ══════════════════════════════════════ */
    .modal-content { border: none; border-radius: 16px; box-shadow: 0 12px 48px rgba(0,0,0,.18); overflow: hidden; }
    .mh {
        display: flex; align-items: center; justify-content: space-between;
        padding: 18px 24px; background: linear-gradient(135deg, #2d2b4e, #605ca8);
    }
    .mh h4 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
    .mh-close {
        background: rgba(255,255,255,.18); border: none; color: #fff;
        width: 30px; height: 30px; border-radius: 8px; cursor: pointer;
        font-size: 17px; display: flex; align-items: center; justify-content: center;
        transition: background .18s; line-height: 1;
    }
    .mh-close:hover { background: rgba(255,255,255,.3); }
    .mb { padding: 24px; }

    /* ── Form fields ── */
    .ff { display: flex; flex-direction: column; gap: 6px; margin-bottom: 16px; }
    .ff label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .ff label .req { color: #e03131; margin-left: 2px; }
    .ff input, .ff select, .ff textarea {
        border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 13px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fafbff !important; outline: none !important; width: 100% !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .ff input:focus, .ff select:focus, .ff textarea:focus {
        border-color: #605ca8 !important; background: #fff !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important;
    }
    .ff input[readonly] { background: #f0eef9 !important; color: #605ca8 !important; font-weight: 600 !important; border-color: #c4bfef !important; }
    .ff input[type=file] { padding: 7px 10px !important; cursor: pointer; background: #fff !important; }
    .ff textarea { resize: vertical; min-height: 80px; }
    .ff .iw { position: relative; }
    .ff .iw i { position: absolute; left: 12px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 12px; pointer-events: none; }
    .ff .iw input { padding-left: 34px !important; }

    .g2 { display: grid; grid-template-columns: 1fr 1fr; gap: 0 20px; }
    .g3 { display: grid; grid-template-columns: 1fr 2fr 1fr; gap: 0 16px; }

    /* Info value (readonly display) */
    .info-val {
        border: 1.5px solid #c4bfef; border-radius: 9px; padding: 9px 13px;
        font-size: 13px; color: #605ca8; background: #f0eef9; font-weight: 600;
    }
    .info-label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin-bottom: 5px; display: block; }

    /* Section divider */
    .sec-div { display: flex; align-items: center; gap: 10px; margin: 18px 0 14px; }
    .sec-div span { font-size: 11px; font-weight: 700; letter-spacing: .08em; text-transform: uppercase; color: #a0aec0; white-space: nowrap; }
    .sec-div::before, .sec-div::after { content: ''; flex: 1; height: 1px; background: #edf0f5; }

    /* ── Date input card (stylish) ── */
    .date-input-card {
        background: #fafbff; border: 1.5px solid #e2e8f0; border-radius: 12px;
        padding: 14px 16px; display: flex; flex-direction: column; gap: 5px;
    }
    .date-input-card label { font-size: 11px; font-weight: 700; color: #4a5568; letter-spacing: .06em; text-transform: uppercase; margin: 0; }
    .date-input-card .iw { position: relative; }
    .date-input-card .iw i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 13px; pointer-events: none; }
    .date-input-card .iw input {
        width: %; border: 1.5px solid #e2e8f0 !important; border-radius: 9px !important;
        padding: 9px 12px 9px 34px !important; font-size: 13.5px !important; color: #1a202c !important;
        background: #fff !important; outline: none !important;
        transition: border-color .18s, box-shadow .18s !important;
    }
    .date-input-card .iw input:focus { border-color: #605ca8 !important; box-shadow: 0 0 0 3px rgba(96,92,168,.1) !important; }

    /* ── Shot input row ── */
    .shot-input-row {
        display: flex; gap: 12px; align-items: flex-end;
        background: #fafbff; border: 1.5px solid #e2e8f0; border-radius: 12px;
        padding: 16px; margin-bottom: 20px;
    }
    .shot-input-row .ff { margin: 0; flex: 1; }

    /* ── Metode Repair card ── */
    .metode-wrap {
        border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden;
        box-shadow: 0 2px 8px rgba(0,0,0,.04); margin-bottom: 16px;
    }
    .metode-header {
        padding: 12px 16px; display: flex; align-items: center; gap: 8px;
        background: linear-gradient(135deg, #2d2b4e, #605ca8); color: #fff;
        font-size: 13px; font-weight: 700;
    }
    .metode-body { padding: 0; }
    /* jspreadsheet sits inside metode-body */
    #metode_repair { border-radius: 0 0 10px 10px; overflow: hidden; }

    /* ── Pelaksanaan Repair ── */
    .repair-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; margin-bottom: 16px; }
    .repair-note-card { border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .repair-note-header {
        padding: 10px 14px; font-size: 12px; font-weight: 700; color: #fff;
        display: flex; align-items: center; gap: 7px;
    }
    .rnh-before { background: linear-gradient(135deg, #92400e, #b45309); }
    .rnh-after  { background: linear-gradient(135deg, #15803d, #16a34a); }
    .repair-note-body { padding: 10px; background: #fafbff; }

    /* ── Radio pills ── */
    .radio-pill-group { display: flex; gap: 7px; flex-wrap: wrap; }
    .radio-pill {
        display: inline-flex; align-items: center; gap: 6px;
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        padding: 6px 12px; cursor: pointer; font-size: 12.5px; font-weight: 600;
        color: #4a5568; background: #f7f8fc; transition: all .18s; user-select: none;
    }
    .radio-pill input[type=radio] { display: none; }
    .radio-pill:has(input:checked) { border-color: #605ca8; background: #f0eef9; color: #605ca8; }
    .rb { width: 14px; height: 14px; border-radius: 50%; border: 2px solid #c4bfef; flex-shrink: 0; display: flex; align-items: center; justify-content: center; transition: all .18s; }
    .radio-pill:has(input:checked) .rb { border-color: #605ca8; background: #605ca8; }
    .radio-pill:has(input:checked) .rb::after { content: ''; width: 5px; height: 5px; background: #fff; border-radius: 50%; }

    /* ── Pemastian ── */
    .pemastian-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 16px; }
    .pemastian-section { border: 1.5px solid #e2e8f0; border-radius: 12px; overflow: hidden; }
    .pst-header {
        padding: 11px 16px; color: #fff; font-size: 12.5px; font-weight: 700;
        display: flex; align-items: center; gap: 8px;
    }
    .pst-mold    { background: linear-gradient(135deg, #1e40af, #2d6bc4); }
    .pst-product { background: linear-gradient(135deg, #15803d, #16a34a); }
    .pst-body { padding: 14px 16px; background: #fafbff; display: flex; flex-direction: column; gap: 12px; }
    .q-card { background: #fff; border: 1.5px solid #edf0f5; border-radius: 9px; padding: 12px 14px; }
    .q-label { font-size: 12.5px; color: #2d3748; font-weight: 600; margin-bottom: 10px; line-height: 1.5; }

    /* ── Modal footer ── */
    .mf { display: flex; gap: 10px; padding: 16px 24px; border-top: 1px solid #f0f2f7; background: #fafbff; justify-content: flex-end; }
    .btn-mf-cancel {
        padding: 10px 18px; border: 1.5px solid #e2e8f0; border-radius: 9px;
        background: #fff; color: #718096; font-size: 13px; font-weight: 600; cursor: pointer; transition: all .18s;
    }
    .btn-mf-cancel:hover { background: #f5f4fb; border-color: #c4bfef; color: #605ca8; }
    .btn-mf-save {
        display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px;
        border: none; border-radius: 9px; background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 12px rgba(96,92,168,.3); transition: all .18s;
    }
    .btn-mf-save:hover { opacity: .88; transform: translateY(-1px); }
    .btn-mf-green {
        display: inline-flex; align-items: center; gap: 7px; padding: 10px 22px;
        border: none; border-radius: 9px; background: linear-gradient(135deg, #15803d, #16a34a);
        color: #fff; font-size: 13px; font-weight: 700; cursor: pointer;
        box-shadow: 0 4px 12px rgba(21,128,61,.3); transition: all .18s;
    }
    .btn-mf-green:hover { opacity: .88; transform: translateY(-1px); }

    /* ── Modal table ── */
    .modal-table { width: 100%; border-collapse: collapse; }
    .modal-table thead th {
        background: #f7f8fc; color: #718096; font-size: 11px; font-weight: 700;
        letter-spacing: .06em; text-transform: uppercase; padding: 10px 12px;
        border-bottom: 1.5px solid #edf0f5;
    }
    .modal-table tbody td {
        padding: 9px 12px; font-size: 13px; color: #2d3748;
        border-bottom: 1px solid #f0f2f7; vertical-align: middle;
    }
    .modal-table tbody tr:last-child td { border-bottom: none; }

    /* Kirim status */
    .s-sent { background: #dcfce7; color: #15803d; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .s-rej  { background: #fee2e2; color: #991b1b; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .s-wait { background: #fef3c7; color: #92400e; padding: 3px 10px; border-radius: 20px; font-size: 11px; font-weight: 700; }
    .doc-chip {
        display: inline-flex; align-items: center; gap: 5px;
        background: #dbeafe; color: #1e40af; padding: 3px 9px; border-radius: 6px;
        font-size: 11.5px; font-weight: 600; text-decoration: none; transition: background .15s;
    }
    .doc-chip:hover { background: #bfdbfe; color: #1e40af; text-decoration: none; }
    .date-input-sm {
        border: 1.5px solid #e2e8f0; border-radius: 8px;
        padding: 6px 10px; font-size: 12.5px; color: #1a202c;
        background: #fafbff; outline: none; width: 130px;
    }
    .date-input-sm:focus { border-color: #605ca8; box-shadow: 0 0 0 3px rgba(96,92,168,.1); }
    .btn-kirim-sm {
        display: inline-flex; align-items: center; gap: 5px; padding: 5px 12px;
        border-radius: 7px; border: none; background: #dcfce7; color: #15803d;
        font-size: 12px; font-weight: 700; cursor: pointer; transition: all .18s;
    }
    .btn-kirim-sm:hover { opacity: .82; }
</style>
@endsection

<meta name="csrf-token" content="{{ csrf_token() }}">

@section('content')

<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="content-header" style="padding: 0 20px;">

    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-cube"></i> Diagnosa Molding</div>
            <h1>Daftar Molding</h1>
            <p>Monitoring status shot & kondisi molding vendor</p>
        </div>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-cubes"></i></div>
            <div><div class="stat-val" id="sc-total">—</div><div class="stat-lbl">Total Molding</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-check-circle"></i></div>
            <div><div class="stat-val" id="sc-normal">—</div><div class="stat-lbl">Normal</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-red"><i class="fas fa-exclamation-triangle"></i></div>
            <div><div class="stat-val" id="sc-butuh">—</div><div class="stat-lbl">Butuh Pemeriksaan</div></div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-search"></i></div>
            <div><div class="stat-val" id="sc-proses">—</div><div class="stat-lbl">Sedang Pemeriksaan</div></div>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span> Daftar Molding
            </div>
        </div>
        <div style="overflow-x:auto;">
            <table id="tableMaster" style="width:100%">
                <thead>
                    <tr>
                        <th style="width:44px">#</th>
                        <th>FA Number</th>
                        <th>Nama Molding</th>
                        <th>Lokasi</th>
                        <th style="width:170px">Shot Progress</th>
                        <th style="width:130px">Status</th>
                        <th>Aksi</th>
                    </tr>
                </thead>
                <tbody id="bodyTableMaster"></tbody>
            </table>
        </div>
    </div>

</div>


{{-- ══════════════════════════════════════
     MODAL: RIWAYAT SHOT
══════════════════════════════════════ --}}
<div class="modal fade" id="modal_shot" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:780px">
        <div class="modal-content">
            <div class="mh">
                <h4><i class="fas fa-chart-bar" style="margin-right:8px;opacity:.8;"></i> Riwayat Shot Molding</h4>
                <button type="button" class="mh-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="mb">
                <div class="g2" style="margin-bottom:18px;">
                    <div>
                        <span class="info-label">Fixed Asset Number</span>
                        <div class="info-val" id="fa_number_disp">—</div>
                        <input type="hidden" id="fa_number">
                    </div>
                    <div>
                        <span class="info-label">Nama Molding</span>
                        <div class="info-val" id="nama_molding_disp">—</div>
                        <input type="hidden" id="nama_molding">
                    </div>
                </div>

                <div class="shot-input-row">
                    <div class="ff">
                        <label>Periode</label>
                        <div class="iw">
                            <i class="fas fa-calendar-alt"></i>
                            <input type="text" id="period" readonly value="{{ date('Y-m') }}">
                        </div>
                    </div>
                    <div class="ff">
                        <label>Jumlah Shot <span class="req">*</span></label>
                        <input type="text" id="shot" placeholder="Masukkan jumlah shot">
                    </div>
                    <div>
                        <button class="btn-mf-save" style="padding:9px 16px;" onclick="updateShot()">
                            <i class="fas fa-sync-alt"></i> Update Shot
                        </button>
                    </div>
                </div>

                <div style="font-size:13px;font-weight:700;color:#1a202c;margin-bottom:12px;display:flex;align-items:center;gap:8px;">
                    <i class="fas fa-history" style="color:#605ca8;"></i> Riwayat Input Shot
                </div>
                <table class="modal-table" id="tableShot">
                    <thead>
                        <tr>
                            <th style="width:42px">No</th>
                            <th>Tanggal</th>
                            <th>Jumlah Shot</th>
                            <th>Total Shot</th>
                            <th>Input By</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyShot"></tbody>
                </table>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" data-bs-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL: TAMBAH KERUSAKAN
══════════════════════════════════════ --}}
<div class="modal fade" id="modalKerusakan" tabindex="-1">
    <div class="modal-dialog" style="max-width:1000px">
        <div class="modal-content">
            <div class="mh">
                <h4><i class="fas fa-tools" style="margin-right:8px;opacity:.8;"></i> Request Repair Mold</h4>
                <button type="button" class="mh-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="mb" style="max-height:80vh;overflow-y:auto;">

                {{-- Info Molding --}}
                <div class="g2">
                    <div class="ff"><label>Asset Number</label><input type="text" id="fa_number_kerusakan" readonly></div>
                    <div class="ff"><label>Nama Molding</label><input type="text" id="nama_molding_kerusakan" readonly></div>
                </div>

                {{-- ▷ Request Repair Mold --}}
                <div class="sec-div"><span><i class="fas fa-wrench" style="color:#b45309;margin-right:4px;"></i> Request Repair Mold</span></div>

                {{-- Date cards + gejala + foto --}}
                <div class="g3" style="align-items:start;">
                    <div style="display:flex;flex-direction:column;gap:10px;">
                                <div class="date-input-card">
                            <label>Tgl Kejadian / Tgl Request <span class="req">*</span></label>
                            <div class="iw">
                                
                                <input type="date" id="tanggal_kerusakan" autocomplete="off" style="padding-left: 14px !important;">
                            </div>
                        </div>
                        <div class="date-input-card">
                            <label>Request Due Date <span class="req">*</span></label>
                            <div class="iw">
                               
                                <input type="date" id="tanggal_target_kerusakan" autocomplete="off" style="padding-left: 14px !important;">
                            </div>
                        </div>
                        <div class="date-input-card">
                            <label>Jumlah Shot</label>
                            <div class="iw">
                                <i class="fas fa-layer-group"></i>
                                <input type="text" id="jml_shot_kerusakan" placeholder="Total shot saat ini">
                            </div>
                        </div>
                    </div>

                    <div class="ff" style="margin:0;">
                        <label>Gejala / Kondisi <span class="req">*</span></label>
                        <textarea id="gejala" rows="9" placeholder="Tulis kondisi atau gejala kerusakan secara ringkas..."></textarea>
                    </div>

                    <div style="display:flex;flex-direction:column;gap:10px;">
                        <div class="ff" style="margin:0;">
                            <label>Foto / Posisi Kerusakan</label>
                            <div style="border:2px dashed #c4bfef;border-radius:10px;padding:16px;text-align:center;background:#faf9ff;cursor:pointer;transition:.18s;"
                                 onmouseover="this.style.borderColor='#605ca8'" onmouseout="this.style.borderColor='#c4bfef'"
                                 onclick="document.getElementById('photo').click()">
                                <i class="fas fa-cloud-upload-alt" style="font-size:22px;color:#c4bfef;display:block;margin-bottom:6px;"></i>
                                <p style="font-size:12.5px;color:#718096;margin:0 0 3px;font-weight:500;">Klik untuk upload foto</p>
                                <small style="font-size:11px;color:#a0aec0;">Bisa multi-file (image/*)</small>
                                <input type="file" id="photo" accept="image/*" multiple style="display:none;" onchange="updatePhotoLabel(this)">
                            </div>
                            <div id="photoLabel" style="font-size:11.5px;color:#605ca8;font-weight:600;margin-top:6px;"></div>
                        </div>
                    </div>
                </div>

                {{-- ▷ Peninjauan Metode Repair (QCD) --}}
                <div class="sec-div"><span><i class="fas fa-microscope" style="color:#605ca8;margin-right:4px;"></i> Peninjauan Metode Repair (QCD)</span></div>

                <div class="metode-wrap">
                    <div class="metode-header">
                        <i class="fas fa-table"></i> Perbandingan 3 Metode Repair
                    </div>
                    <div class="metode-body">
                        <div id="metode_repair" style="width:100%;"></div>
                    </div>
                </div>

                {{-- ▷ Penentuan Metode Repair --}}
                <div class="sec-div"><span><i class="fas fa-check-double" style="color:#15803d;margin-right:4px;"></i> Penentuan Metode Repair</span></div>

                <div class="g2">
                    <div class="ff"><label>Metode yang Dipilih</label><input type="text" name="metode_dipilih" id="metode_dipilih" placeholder="Contoh: Metode 1"></div>
                    <div class="ff"><label>Alasan Pemilihan Metode</label><input type="text" name="alasan_pemilihan" id="alasan_pemilihan" placeholder="Jelaskan alasan pemilihan..."></div>
                </div>

                {{-- ▷ Pelaksanaan Repair Mold --}}
                <div class="sec-div"><span><i class="fas fa-hammer" style="color:#b45309;margin-right:4px;"></i> Pelaksanaan Repair Mold</span></div>

                <div class="repair-grid">
                    <div class="repair-note-card">
                        <div class="repair-note-header rnh-before"><i class="fas fa-arrow-left"></i> Sebelum Repair</div>
                        <div class="repair-note-body">
                            <textarea name="sebelum_repair" id="sebelum_repair" rows="4" style="width:100%;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 10px;font-size:13px;resize:vertical;outline:none;" placeholder="Kondisi sebelum repair..."></textarea>
                        </div>
                    </div>
                    <div class="repair-note-card">
                        <div class="repair-note-header rnh-after"><i class="fas fa-arrow-right"></i> Setelah Repair</div>
                        <div class="repair-note-body">
                            <textarea name="setelah_repair" id="setelah_repair" rows="4" style="width:100%;border:1.5px solid #e2e8f0;border-radius:8px;padding:8px 10px;font-size:13px;resize:vertical;outline:none;" placeholder="Kondisi setelah repair..."></textarea>
                        </div>
                    </div>
                </div>

                {{-- ▷ Pemastian Keabsahan --}}
                <div class="sec-div"><span><i class="fas fa-clipboard-check" style="color:#1d4ed8;margin-right:4px;"></i> Pemastian Keabsahan Repair Mold</span></div>

                <div class="pemastian-grid">
                    {{-- Pemastian Mold --}}
                    <div class="pemastian-section">
                        <div class="pst-header pst-mold"><i class="fas fa-cube"></i> Pemastian Mold</div>
                        <div class="pst-body">
                            @foreach([
                                ['mold_1', '1. Apakah bentuk pada posisi repair OK?'],
                                ['mold_2', '2. Apakah dimensi pada posisi repair OK?'],
                                ['mold_3', '3. Apakah fungsi pada posisi repair OK?'],
                                ['mold_4', '4. Apakah tidak ada efek ke bagian sekeliling?'],
                            ] as $q)
                            <div class="q-card">
                                <div class="q-label">{{ $q[1] }}</div>
                                <div class="radio-pill-group">
                                    @foreach(['OK','NG','Not Applicable'] as $opt)
                                    <label class="radio-pill">
                                        <input type="radio" name="pemastian_{{ $q[0] }}" value="{{ $opt }}">
                                        <span class="rb"></span> {{ $opt }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Pemastian Produk --}}
                    <div class="pemastian-section">
                        <div class="pst-header pst-product"><i class="fas fa-box"></i> Pemastian pada Produk Molding</div>
                        <div class="pst-body">
                            @foreach([
                                ['product_1', '1. Apakah bentuk pada posisi repair OK?'],
                                ['product_2', '2. Apakah dimensi pada posisi repair OK?'],
                                ['product_3', '3. Apakah tidak ada efek ke bagian sekeliling?'],
                                ['product_4', '4. Apakah dimensi kontrol harian OK? (Pemastian ke QA)'],
                            ] as $q)
                            <div class="q-card">
                                <div class="q-label">{{ $q[1] }}</div>
                                <div class="radio-pill-group">
                                    @foreach(['OK','NG','Not Applicable'] as $opt)
                                    <label class="radio-pill">
                                        <input type="radio" name="pemastian_{{ $q[0] }}" value="{{ $opt }}">
                                        <span class="rb"></span> {{ $opt }}
                                    </label>
                                    @endforeach
                                </div>
                            </div>
                            @endforeach
                        </div>
                    </div>
                </div>

            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-save" onclick="saveKerusakan()"><i class="fas fa-save"></i> Simpan</button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL: KIRIM MOLDING
══════════════════════════════════════ --}}
<div class="modal fade" id="modalKirim" tabindex="-1">
    <div class="modal-dialog modal-lg" style="max-width:820px">
        <div class="modal-content">
            <div class="mh">
                <h4><i class="fas fa-paper-plane" style="margin-right:8px;opacity:.8;"></i> Kirim Molding ke YMPI</h4>
                <button type="button" class="mh-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="mb">
                <input type="hidden" id="kawasan_kirim">
                <div class="g2" style="margin-bottom:20px;">
                    <div>
                        <span class="info-label">Nomor Molding</span>
                        <div class="info-val" id="fa_number_kirim">—</div>
                    </div>
                    <div>
                        <span class="info-label">Nama Molding</span>
                        <div class="info-val" id="nama_molding_kirim">—</div>
                    </div>
                </div>
                <div style="display:flex;align-items:center;justify-content:space-between;margin-bottom:12px;">
                    <div style="font-size:13px;font-weight:700;color:#1a202c;display:flex;align-items:center;gap:8px;">
                        <i class="fas fa-list" style="color:#605ca8;"></i> Riwayat Pengiriman
                    </div>
                    <button class="btn-mf-green" style="padding:7px 14px;font-size:12.5px;" onclick="modal_buat_kirim()">
                        <i class="fas fa-plus"></i> Buat Pengiriman
                    </button>
                </div>
                <table class="modal-table">
                    <thead>
                        <tr>
                            <th style="width:42px">No</th>
                            <th>Tgl Buat</th>
                            <th>Tgl Pengiriman <span style="color:#e03131;">*</span></th>
                            <th>Lampiran</th>
                            <th>Status</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody id="tbodyKirim"></tbody>
                </table>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" data-bs-dismiss="modal"><i class="fas fa-times"></i> Tutup</button>
            </div>
        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL: BUAT PENGIRIMAN
══════════════════════════════════════ --}}
<div class="modal fade" id="modalBuatKirim" tabindex="-1">
    <div class="modal-dialog" style="max-width:500px">
        <div class="modal-content">
            <div class="mh">
                <h4><i class="fas fa-box" style="margin-right:8px;opacity:.8;"></i> Buat Pengiriman</h4>
                <button type="button" class="mh-close" data-dismiss="modal" data-bs-dismiss="modal" aria-label="Close">&times;</button>
            </div>
            <div class="mb">
                <div class="g2" style="margin-bottom:18px;">
                    <div>
                        <span class="info-label">Nomor Molding</span>
                        <div class="info-val" id="fa_number_buat_kirim">—</div>
                    </div>
                    <div>
                        <span class="info-label">Nama Molding</span>
                        <div class="info-val" id="nama_molding_buat_kirim">—</div>
                    </div>
                </div>
                <div id="div_bc_27" style="display:none;">
                    <div class="ff">
                        <label>Dokumen BC 2.7 <span class="req">*</span></label>
                        <input type="file" id="bc_27_buat_kirim" accept="application/pdf">
                    </div>
                </div>
                <div class="ff">
                    <label>Surat Jalan <span class="req">*</span></label>
                    <input type="file" id="surat_jalan_buat_kirim">
                </div>
                <div class="ff" style="margin-bottom:0;">
                    <label>Foto Packing <span class="req">*</span></label>
                    <input type="file" id="foto_packing_buat_kirim" accept="image/*">
                </div>
            </div>
            <div class="mf">
                <button type="button" class="btn-mf-cancel" data-dismiss="modal" data-bs-dismiss="modal"><i class="fas fa-times"></i> Batal</button>
                <button class="btn-mf-green" onclick="buat_pengiriman()"><i class="fas fa-paper-plane"></i> Kirim</button>
            </div>
        </div>
    </div>
</div>

@endsection

@section('scripts')
<script src="{{ url('js/bootstrap.bundle.min.js') }}"></script>
<script src="{{ url('js/sweetalert2.min.js') }}"></script>
<script src="{{ url('js/toastr.min.js') }}"></script>
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/vfs_fonts.js') }}"></script>
<script src="https://cdn.jsdelivr.net/npm/summernote@0.9.0/dist/summernote.min.js"></script>

<script>
    $.ajaxSetup({ headers: { 'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') } });

    var audio_error   = new Audio('{{ url('sounds/error.mp3') }}');
    var audio_success = new Audio('{{ url('sounds/success.mp3') }}');
    var tbl;

    /* ══════════════════════════════════════
       INIT jSpreadsheet
    ══════════════════════════════════════ */
    function initMetodeRepair() {
        if (document.getElementById('metode_repair') && !tbl) {
            tbl = jspreadsheet(document.getElementById('metode_repair'), {
                worksheets: [{
                    minDimensions: [8, 6],
                    columns: [
                        { width: 100 }, { width: 250 }, { width: 10 },
                        { width: 100 }, { width: 250 }, { width: 10 },
                        { width: 100 }, { width: 250 }
                    ]
                }]
            });

            var sheet = tbl[0];
            sheet.setValue('A1', 'Metode 1'); sheet.setMerge('A1', 2, 1); sheet.setStyle('A1', 'font-weight', 'bold');
            sheet.setValue('A2', 'Mutu'); sheet.setValue('A3', 'Biaya'); sheet.setValue('A4', 'Waktu');
            sheet.setValue('A5', 'Gambar / Foto'); sheet.setMerge('A5', 2, 1); sheet.setMerge('A6', 2, 1);
            ['C1','C2','C3','C4','C5','C6'].forEach(function(c) {
                sheet.setStyle(c, 'border-left', '2px solid #e2e8f0');
                sheet.setStyle(c, 'border-right', '2px solid #e2e8f0');
            });
            sheet.setValue('D1', 'Metode 2'); sheet.setMerge('D1', 2, 1); sheet.setStyle('D1', 'font-weight', 'bold');
            sheet.setValue('D2', 'Mutu'); sheet.setValue('D3', 'Biaya'); sheet.setValue('D4', 'Waktu');
            sheet.setValue('D5', 'Gambar / Foto'); sheet.setMerge('D5', 2, 1); sheet.setMerge('D6', 2, 1);
            ['F1','F2','F3','F4','F5','F6'].forEach(function(c) {
                sheet.setStyle(c, 'border-left', '2px solid #e2e8f0');
                sheet.setStyle(c, 'border-right', '2px solid #e2e8f0');
            });
            sheet.setValue('G1', 'Metode 3'); sheet.setMerge('G1', 2, 1); sheet.setStyle('G1', 'font-weight', 'bold');
            sheet.setValue('G2', 'Mutu'); sheet.setValue('G3', 'Biaya'); sheet.setValue('G4', 'Waktu');
            sheet.setValue('G5', 'Gambar / Foto'); sheet.setMerge('G5', 2, 1); sheet.setMerge('G6', 2, 1);
        }
    }

    $('#modalKerusakan').on('shown.bs.modal', function () { initMetodeRepair(); });

    /* ══════════════════════════════════════
       INIT
    ══════════════════════════════════════ */
    $(document).ready(function () {
        $('#side_diagnosa_molding').addClass('menu-open');
        $('body').addClass("sidebar-collapse");
        getData();

        $('#shot').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });

        $('#jml_shot_kerusakan').on('input', function () {
            this.value = this.value.replace(/[^0-9]/g, '');
        });
    });

    /* ══════════════════════════════════════
       getData — ORIGINAL FUNCTION
    ══════════════════════════════════════ */
    function getData() {
        $('#loading').addClass('show');
        $.get('{{ url('fetch/diagnose_molding/molding_list') }}', function (result) {
            $('#loading').removeClass('show');
            if (result.status) {
                var total=0, normal=0, butuh=0, proses=0;
                var tableData = '';

                $.each(result.data, function (key, value) {
                    total++;
                    var pct = value.standard_shot > 0
                        ? Math.min((value.total_shot / value.standard_shot) * 100, 100) : 0;
                    var barColor = pct >= 100 ? '#ef4444' : (pct >= 80 ? '#f59e0b' : '#22c55e');

                    var statusStr = value.status || 'Normal';
                    var pill = '';
                    if (statusStr == 'Sedang Pemeriksaan' || statusStr == 'InProgress Pemeriksaan') {
                        pill = "<span class='pill pill-proses'>" + statusStr + "</span>";
                        proses++;
                    } else if (statusStr == 'Butuh Pemeriksaan') {
                        pill = "<span class='pill pill-butuh'>Butuh Pemeriksaan</span>";
                        butuh++;
                    } else {
                        pill = "<span class='pill pill-normal'>Normal</span>";
                        normal++;
                    }

                    var aksi = "<div class='action-group'>";
                    if ('{{ Auth::user()->username }}' !== 'molding_ympi') {
                        aksi += "<button class='btn-act a-shot' onclick='openModalShot(\"" + value.fixed_asset_number + "\",\"" + value.fixed_asset_name + "\")' title='Input Shot'><i class='fas fa-marker'></i></button>";
                        aksi += "<button class='btn-act a-rusak' onclick='openModalKerusakan(\"" + value.fixed_asset_number + "\",\"" + value.fixed_asset_name + "\")' title='Tambah Kerusakan'><i class='fas fa-tools'></i></button>";
                        if (statusStr == 'Butuh Pemeriksaan') {
                            aksi += "<a class='btn-act a-buat' href='javascript:void(0)' onclick='generate_form(\"" + value.fixed_asset_number + "\")' title='Buat Form'><i class='fas fa-plus'></i> Buat Form</a>";
                        } else if (statusStr == 'InProgress Pemeriksaan') {
                            aksi += "<a class='btn-act a-lihat' href='{{ url('index/diagnose_molding/molding_form') }}/" + value.fixed_asset_number + "' title='Lihat Form'><i class='fas fa-eye'></i> Lihat Form</a>";
                        }
                        aksi += "<button class='btn-act a-kirim' onclick='modal_kirim(\"" + value.fixed_asset_number + "\",\"" + value.fixed_asset_name + "\",\"" + value.status_kawasan + "\")' title='Kirim Molding'><i class='fas fa-paper-plane'></i> Kirim</button>";
                    } else {
                        aksi += "<a class='btn-act a-buat' href='javascript:void(0)' onclick='generate_form(\"" + value.fixed_asset_number + "\")' title='Buat Form'><i class='fas fa-plus'></i> Buat Form</a>";
                    }
                    aksi += "</div>";

                    tableData += '<tr>';
                    tableData += '<td>' + (key + 1) + '</td>';
                    tableData += '<td style="font-family:monospace;font-size:12.5px;font-weight:600;color:#605ca8;">' + value.fixed_asset_number + '</td>';
                    tableData += '<td><div class="molding-cell"><div class="m-avatar">' + value.fixed_asset_name.substring(0,2).toUpperCase() + '</div><div><div class="m-name">' + value.fixed_asset_name + '</div><div class="m-fa">' + (value.vendor || '') + '</div></div></div></td>';
                    tableData += '<td><span style="font-size:12px;font-weight:600;color:#4a5568;">' + (value.status_kawasan || '—') + '</span></td>';
                    tableData += '<td><div class="shot-wrap"><div class="shot-nums"><span class="shot-cur">' + (value.total_shot ? parseInt(value.total_shot).toLocaleString('id-ID') : '0') + '</span><span class="shot-sep">/</span><span class="shot-std">' + (value.standard_shot ? parseInt(value.standard_shot).toLocaleString('id-ID') : '0') + '</span></div><div class="shot-bar"><div class="shot-fill" style="width:' + pct.toFixed(1) + '%;background:' + barColor + ';"></div></div></div></td>';
                    tableData += '<td>' + pill + '</td>';
                    tableData += '<td>' + aksi + '</td>';
                    tableData += '</tr>';
                });

                $('#bodyTableMaster').html(tableData);
                $('#sc-total').text(total);
                $('#sc-normal').text(normal);
                $('#sc-butuh').text(butuh);
                $('#sc-proses').text(proses);

                var dtTable = $('#tableMaster').DataTable({
                    paging: true, searching: true,
                    ordering: false, info: true,
                    autoWidth: false, lengthChange: false,
                    lengthMenu: [10, 25, 50, 100],
                    columnDefs: [
                        { targets: 0, width: '44px',  className: 'text-center' },
                        { targets: 1, width: '130px' },
                        { targets: 2 },
                        { targets: 3, width: '110px', className: 'text-center' },
                        { targets: 4, width: '170px' },
                        { targets: 5, width: '130px', className: 'text-center' },
                        { targets: 6 }
                    ],
                    language: {
                        lengthMenu: 'Tampilkan _MENU_ data', zeroRecords: 'Tidak ada data',
                        info: '_START_–_END_ dari _TOTAL_ data', infoEmpty: '0 data',
                        infoFiltered: '(dari _MAX_)', search: 'Cari:',
                        paginate: { first:'«', last:'»', next:'›', previous:'‹' }
                    }
                });

                /* Auto renumber */
                dtTable.on('order.dt search.dt draw.dt', function () {
                    var pi = dtTable.page.info();
                    dtTable.column(0, { search:'applied', order:'applied', page:'current' })
                        .nodes().each(function (c, i) { c.innerHTML = i + 1 + pi.start; });
                }).draw();
            }
        });
    }

    function generate_form(asset_number) {
        window.open('{{ url('generate/diagnose_molding/mold_product_check/new') }}?asset_number=' + asset_number);
    }

    /* ══════════════════════════════════════
       openModalShot — ORIGINAL
    ══════════════════════════════════════ */
    function openModalShot(asset_number, asset_name) {
        $('#modal_shot').modal('show');
        $('#fa_number').val(asset_number);
        $('#fa_number_disp').text(asset_number);
        $('#nama_molding').val(asset_name);
        $('#nama_molding_disp').text(asset_name);
        $('#shot').val('');

        $.get('{{ url('fetch/diagnose_molding/shot_list') }}', { asset_number: asset_number }, function (result) {
            var body = '';
            if (result.status && result.data.length > 0) {
                var no = 1;
                $.each(result.data, function (k, v) {
                    body += '<tr>';
                    body += '<td>' + no++ + '</td>';
                    body += '<td>' + v.created_at + '</td>';
                    body += '<td>' + (v.total_shot ? parseInt(v.total_shot).toLocaleString('id-ID') : '0') + '</td>';
                    body += '<td>' + (v.accumulative_shot ? parseInt(v.accumulative_shot).toLocaleString('id-ID') : '0') + '</td>';
                    body += '<td>' + v.created_by + '</td>';
                    body += '<td></td>';
                    body += '</tr>';
                });
            } else {
                body = '<tr><td colspan="6" style="text-align:center;color:#a0aec0;padding:16px;">Belum ada riwayat shot.</td></tr>';
            }
            $('#tbodyShot').html(body);
        });
    }

    /* ══════════════════════════════════════
       updateShot — ORIGINAL
    ══════════════════════════════════════ */
    function updateShot() {
        var asset_number = $('#fa_number').val();
        var shot = $('#shot').val();

        $.post('{{ url('update/diagnose_molding/shot') }}', {
            asset_number: asset_number,
            molding_name: $('#nama_molding').val(),
            period: $('#period').val(),
            shot: shot
        }, function (result) {
            if (result.status) {
                toastr.success(result.message);
                audio_success.play();
                $('#shot').val('');
                openModalShot(asset_number, $('#nama_molding').val());
            } else {
                toastr.error(result.message);
                audio_error.play();
            }
        });
    }

    /* ══════════════════════════════════════
       openModalKerusakan — ORIGINAL
    ══════════════════════════════════════ */
    function openModalKerusakan(asset_number, molding_name) {
        $('#fa_number_kerusakan').val(asset_number);
        $('#nama_molding_kerusakan').val(molding_name);
        $('input[name^="pemastian_"]').prop('checked', false);
        $('#tanggal_kerusakan, #tanggal_target_kerusakan, #jml_shot_kerusakan, #gejala, #metode_dipilih, #alasan_pemilihan').val('');
        $('#sebelum_repair, #setelah_repair').val('');
        $('#photoLabel').text('');
        $('#photo').val('');

        // native date input type is used for tanggal fields, so bootstrap datepicker is not required
        // $('#tanggal_kerusakan').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });
        // $('#tanggal_target_kerusakan').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true });

        $('#modalKerusakan').modal('show');
    }

    function updatePhotoLabel(input) {
        var names = Array.from(input.files).map(function(f){ return f.name; }).join(', ');
        $('#photoLabel').text(names || '');
    }

    /* ══════════════════════════════════════
       saveKerusakan — ORIGINAL
    ══════════════════════════════════════ */
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
        formData.append('sebelum_repair', $('#sebelum_repair').val());
        formData.append('setelah_repair', $('#setelah_repair').val());

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
            formData.append('metode_repair', JSON.stringify(tbl[0].getData()));
        }

        $('#loading').addClass('show');
        $.ajax({
            url: '{{ url('post/diagnose_molding/kerusakan') }}',
            type: 'POST', contentType: false, processData: false, data: formData,
            success: function (response) {
                $('#loading').removeClass('show');
                if (!response.status) {
                    audio_error.play();
                    toastr.error(response.message);
                } else {
                    audio_success.play();
                    toastr.success(response.message);
                    $('#modalKerusakan').modal('hide');
                }
            },
            error: function (response) {
                $('#loading').removeClass('show');
                audio_error.play();
                toastr.error(response.responseJSON ? response.responseJSON.message : 'Terjadi kesalahan');
            }
        });
    }

    /* ══════════════════════════════════════
       MODAL KIRIM — ORIGINAL
    ══════════════════════════════════════ */
    function modal_kirim(asset_number, molding_name, kawasan) {
        $('#fa_number_kirim').text(asset_number);
        $('#nama_molding_kirim').text(molding_name);
        $('#kawasan_kirim').val(kawasan);
        $('#tbodyKirim').empty();
        $('#modalKirim').modal('show');

        $.get('{{ url('get/molding/pengiriman') }}', { no_fixed_asset: asset_number }, function (result) {
            if (result.status) {
                var body = '';
                $.each(result.datas, function (key, value) {
                    body += '<tr>';
                    body += '<td>' + (key + 1) + '</td>';
                    body += '<td>' + value.created_at + '</td>';
                    if (value.tgl_pengiriman) {
                        body += '<td>' + value.tgl_pengiriman + '</td>';
                    } else {
                        body += '<td><input type="date" class="date-input-sm" id="tgl_pengiriman_' + value.id + '"></td>';
                    }
                    body += '<td>';
                    var doc = JSON.parse(value.document || '[]');
                    $.each(doc, function (k, v) {
                        var key2 = Object.keys(v)[0];
                        var val2 = v[key2];
                        body += '<a class="doc-chip" href="{{ url("workshop/molding/file_pengiriman/") }}/' + key2 + '/' + val2 + '" target="_blank"><i class="fas fa-file"></i> ' + key2 + '</a> ';
                    });
                    body += '</td>';

                    var sp = !value.status ? "<span class='s-wait'>Menunggu</span>"
                           : value.status == 'Rejected' ? "<span class='s-rej'>Rejected</span>"
                           : "<span class='s-sent'>" + value.status + "</span>";
                    body += '<td>' + sp + '</td>';

                    if (!value.status || value.status == 'Rejected') {
                        body += '<td><button class="btn-kirim-sm" onclick="kirim(' + value.id + ')"><i class="fas fa-paper-plane"></i> Kirim</button></td>';
                    } else {
                        body += '<td></td>';
                    }
                    body += '</tr>';
                });
                $('#tbodyKirim').html(body || '<tr><td colspan="6" style="text-align:center;color:#a0aec0;padding:16px;">Belum ada pengiriman.</td></tr>');

                // the date fields use native type=date inputs now
                // $('.datepicker').datepicker({ format: 'yyyy-mm-dd', autoclose: true, todayHighlight: true, orientation: 'bottom' });
            }
        });
    }

    function modal_buat_kirim() {
        $('#kawasan_kirim').val() == 'KB' ? $('#div_bc_27').show() : $('#div_bc_27').hide();
        $('#fa_number_buat_kirim').text($('#fa_number_kirim').text());
        $('#nama_molding_buat_kirim').text($('#nama_molding_kirim').text());
        $('#surat_jalan_buat_kirim, #foto_packing_buat_kirim, #bc_27_buat_kirim').val('');
        $('#modalBuatKirim').modal('show');
    }

    function buat_pengiriman() {
        var formData = new FormData();
        if (!$('#surat_jalan_buat_kirim')[0].files[0]) { audio_error.play(); toastr.error('Surat Jalan harus diisi'); return; }
        if (!$('#foto_packing_buat_kirim')[0].files[0]) { audio_error.play(); toastr.error('Foto Packing harus diisi'); return; }
        if ($('#kawasan_kirim').val() == 'KB') {
            if ($('#bc_27_buat_kirim')[0].files[0]) {
                formData.append('bc_27_buat_kirim', $('#bc_27_buat_kirim')[0].files[0]);
            } else { audio_error.play(); toastr.error('BC 27 harus diisi'); return; }
        }
        formData.append('fa_number', $('#fa_number_buat_kirim').text());
        formData.append('nama_molding', $('#nama_molding_buat_kirim').text());
        formData.append('surat_jalan_buat_kirim', $('#surat_jalan_buat_kirim')[0].files[0]);
        formData.append('foto_packing_buat_kirim', $('#foto_packing_buat_kirim')[0].files[0]);

        $('#loading').addClass('show');
        $.ajax({
            url: '{{ url('post/molding/pengiriman') }}', type: 'POST',
            contentType: false, processData: false, data: formData,
            success: function (response) {
                $('#loading').removeClass('show');
                if (!response.status) {
                    audio_error.play(); toastr.error(response.message);
                } else {
                    audio_success.play(); toastr.success(response.message);
                    $('#surat_jalan_buat_kirim, #foto_packing_buat_kirim, #bc_27_buat_kirim').val('');
                    $('#modalBuatKirim').modal('hide');
                    modal_kirim($('#fa_number_buat_kirim').text(), $('#nama_molding_buat_kirim').text(), $('#kawasan_kirim').val());
                }
            },
            error: function (response) {
                $('#loading').removeClass('show');
                audio_error.play(); toastr.error(response.message);
            }
        });
    }

    function kirim(id) {
        if (!$('#tgl_pengiriman_' + id).val()) {
            audio_error.play(); toastr.error('Mohon Lengkapi Tanggal Kirim'); return;
        }
        var tgl = $('#tgl_pengiriman_' + id).val();

        Swal.fire({
            title: 'Konfirmasi Pengiriman',
            html: 'Kirim molding pada tanggal <b>' + tgl + '</b>?',
            icon: 'question',
            showCancelButton: true,
            confirmButtonColor: '#15803d', cancelButtonColor: '#718096',
            confirmButtonText: '<i class="fas fa-paper-plane"></i> Ya, Kirim',
            cancelButtonText: 'Batal',
        }).then(function (result) {
            if (result.isConfirmed) {
                $('#loading').addClass('show');
                $.post('{{ url('post/molding/pengiriman/kirim') }}', { id: id, tgl_kirim: tgl }, function (res) {
                    $('#loading').removeClass('show');
                    if (res.status) {
                        toastr.success(res.message); audio_success.play();
                        $('#modalKirim').modal('hide');
                        modal_kirim(res.data.fixed_asset_number, res.data.fixed_asset_name, res.data.status_kawasan);
                    } else {
                        toastr.error(res.message); audio_error.play();
                    }
                });
            }
        });
    }
</script>
@endsection