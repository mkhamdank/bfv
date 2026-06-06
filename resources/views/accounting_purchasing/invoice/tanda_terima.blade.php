@extends('layouts.master')

@section('styles')
<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link rel="stylesheet" href="http://10.109.32.55:8879/f/css/dropzone.min.css" />
<script src="http://10.109.32.55:8879/f/javascript/dropzone.min.js"></script>
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body { background:#f0f2f7 !important; }

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
        padding: 28px 36px 24px;
        margin: 24px 0 24px;
        border-radius: 18px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        flex-wrap: wrap;
        gap: 16px;
        position: relative;
        overflow: hidden;
    }
    .page-header-modern::before {
        content: '';
        position: absolute;
        right: -40px; top: -40px;
        width: 200px; height: 200px;
        border-radius: 50%;
        background: rgba(255,255,255,.04);
    }
    .page-header-modern::after {
        content: '';
        position: absolute;
        left: 30%; bottom: -60px;
        width: 160px; height: 160px;
        border-radius: 50%;
        background: rgba(255,255,255,.03);
    }
    .header-left .badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,.14);
        border: 1px solid rgba(255,255,255,.22);
        color: #c9c6f0;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 10px;
    }
    .header-left h1 { color: #fff !important; font-size: 26px !important; font-weight: 700 !important; margin: 0 0 4px !important; line-height: 1.2 !important; }
    .header-left p  { color: rgba(255,255,255,.5); font-size: 13px; margin: 0; }

    .btn-add-report {
        background: #fff;
        color: #4a4690;
        border: none;
        border-radius: 12px;
        padding: 12px 24px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        display: inline-flex;
        align-items: center;
        gap: 8px;
        box-shadow: 0 4px 18px rgba(0,0,0,.18);
        text-decoration: none;
        transition: all .2s;
        z-index: 999;
    }
    .btn-add-report:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }

    /* ══════════════════════════════════════
       STAT CARDS
    ══════════════════════════════════════ */
    .stat-row {
        display: grid;
        grid-template-columns: repeat(4, 1fr);
        gap: 14px;
        margin-bottom: 24px;
    }
    .stat-card {
        background: #fff;
        border-radius: 14px;
        padding: 18px 20px;
        box-shadow: 0 2px 8px rgba(0,0,0,.05);
        border: 1px solid rgba(0,0,0,.05);
        display: flex;
        align-items: center;
        gap: 14px;
        transition: box-shadow .2s, transform .2s;
    }
    .stat-card:hover { box-shadow: 0 4px 16px rgba(0,0,0,.09); transform: translateY(-1px); }
    .stat-icon {
        width: 44px; height: 44px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 17px;
        flex-shrink: 0;
    }
    .si-blue   { background: #ebf2ff; color: #2d6bc4; }
    .si-purple { background: #ede9fe; color: #7c3aed; }
    .si-green  { background: #dcfce7; color: #15803d; }
    .si-amber  { background: #fef3c7; color: #b45309; }
    .stat-val  { font-size: 22px; font-weight: 800; color: #1a202c; line-height: 1; margin-bottom: 2px; }
    .stat-lbl  { font-size: 12px; color: #718096; font-weight: 500; }

    /* ══════════════════════════════════════
       TABLE CARD
    ══════════════════════════════════════ */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 32px;
    }
    .table-card-header {
        padding: 18px 24px 16px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
    }
    .table-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .table-card-title .dot { width: 8px; height: 8px; background: #2d6bc4; border-radius: 50%; }

    /* export btn */
    .btn-export {
        display: inline-flex;
        align-items: center;
        gap: 7px;
        background: #ebf2ff;
        color: #2d6bc4;
        border: 1.5px solid #c3d9f8;
        border-radius: 8px;
        padding: 7px 16px;
        font-size: 12.5px;
        font-weight: 600;
        cursor: pointer;
        transition: all .18s;
        text-decoration: none;
    }
    .btn-export:hover { background: #dbeafe; color: #1d4ed8; text-decoration: none; }

    /* DataTable toolbar buttons */
    .btn-dt {
        display: inline-flex !important;
        align-items: center !important;
        gap: 5px !important;
        padding: 6px 12px !important;
        border-radius: 8px !important;
        font-size: 12px !important;
        font-weight: 600 !important;
        border: none !important;
        cursor: pointer !important;
        transition: all .18s !important;
        white-space: nowrap !important;
        box-shadow: none !important;
        outline: none !important;
    }
    .btn-dt-default { background: #f0eef9 !important; color: #605ca8 !important; border: 1.5px solid #e2dff5 !important; }
    .btn-dt-copy    { background: #f0fdf4 !important; color: #15803d !important; border: 1.5px solid #bbf7d0 !important; }
    .btn-dt-excel   { background: #f0fdf4 !important; color: #15803d !important; border: 1.5px solid #bbf7d0 !important; }
    .btn-dt-print   { background: #fefce8 !important; color: #b45309 !important; border: 1.5px solid #fde68a !important; }
    .btn-dt:hover   { opacity: .82 !important; transform: translateY(-1px) !important; }
    .dt-buttons     { display: flex; gap: 4px; }

    /* ══════════════════════════════════════
       DATATABLE
    ══════════════════════════════════════ */
    .dataTables_wrapper { padding: 16px 20px 20px !important; }
    .dataTables_length label,
    .dataTables_filter label {
        font-size: 13px !important;
        color: #4a5568 !important;
        font-weight: 500 !important;
        display: flex !important;
        align-items: center !important;
        gap: 8px !important;
    }
    .dataTables_length select,
    .dataTables_filter input {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 6px 10px !important;
        font-size: 13px !important;
        outline: none !important;
    }
    table.dataTable { border-collapse: collapse !important; width: 100% !important; margin: 8px 0 !important; }
    table.dataTable thead th {
        background: #f7f8fc !important;
        color: #718096 !important;
        font-size: 11px !important;
        font-weight: 700 !important;
        letter-spacing: .8px !important;
        text-transform: uppercase !important;
        padding: 12px 14px !important;
        border-bottom: 2px solid #edf0f5 !important;
        border-top: none !important;
        white-space: nowrap;
        text-align: center;
    }
    table.dataTable tbody tr:hover td { background: #f5f8ff !important; }
    table.dataTable tbody td {
        padding: 12px 14px !important;
        font-size: 13px !important;
        color: #2d3748 !important;
        border-bottom: 1px solid #f0f2f7 !important;
        border-top: none !important;
        vertical-align: middle !important;
        text-align: center;
    }
    table.dataTable tbody td:nth-child(2),
    table.dataTable tbody td:nth-child(3) { text-align: left; }
    table.dataTable tbody tr:last-child td { border-bottom: none !important; }

    /* .dt-top-bar    { display: flex; align-items: center; justify-content: space-between; padding: 0 0 12px; gap: 12px; } */
    .dt-bottom-bar { display: flex; align-items: center; justify-content: space-between; padding: 10px 0 4px; flex-wrap: wrap; gap: 8px; }
    .dataTables_info { font-size: 12px !important; color: #718096 !important; padding-top: 0 !important; }
    .dataTables_paginate .paginate_button {
        border-radius: 7px !important;
        font-size: 13px !important;
        font-weight: 600 !important;
        color: #4a5568 !important;
        border: 1px solid transparent !important;
        padding: 5px 10px !important;
        margin: 0 2px !important;
    }
    .dataTables_paginate .paginate_button:hover { background: #ebf2ff !important; color: #2d6bc4 !important; }
    .dataTables_paginate .paginate_button.current { background: linear-gradient(135deg,#2d6bc4,#1a4d9a) !important; color: #fff !important; }

    /* ══════════════════════════════════════
       CELL ELEMENTS
    ══════════════════════════════════════ */
    .supplier-cell { display: flex; align-items: center; gap: 10px; }
    .sup-avatar {
        width: 34px; height: 34px;
        border-radius: 9px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 12px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
        letter-spacing: -.5px;
    }
    .sup-name { font-weight: 600; color: #1a202c; font-size: 13px; line-height: 1.3; }
    .sup-code { font-size: 11px; color: #a0aec0; margin-top: 1px; }

    .date-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0f5ff;
        color: #2d6bc4;
        padding: 3px 9px;
        border-radius: 6px;
        font-size: 11.5px;
        font-weight: 600;
    }

    .curr-badge {
        font-size: 11px;
        font-weight: 700;
        padding: 2px 8px;
        border-radius: 20px;
    }
    .curr-IDR { background: #ede9fe; color: #7c3aed; }
    .curr-USD { background: #dbeafe; color: #1d4ed8; }
    .curr-JPY { background: #fef3c7; color: #b45309; }

    .amt-cell { font-weight: 700; font-size: 13px; color: #1a202c; text-align: right !important; font-variant-numeric: tabular-nums; }

    .status-badge {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 10px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 700;
    }
    .status-badge::before {
        content: '';
        width: 5px; height: 5px;
        border-radius: 50%;
        display: inline-block;
    }
    .s-not-pay { background: #fce7f3; color: #9d174d; }
    .s-not-pay::before { background: #ec4899; }
    .s-requested { background: #dbeafe; color: #1e40af; }
    .s-requested::before { background: #3b82f6; }

    .file-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #ede9fe;
        color: #7c3aed;
        padding: 4px 10px;
        border-radius: 8px;
        font-size: 11px;
        font-weight: 600;
        text-decoration: none;
        transition: background .15s;
    }
    .file-chip:hover { background: #ddd6fe; color: #6d28d9; text-decoration: none; }

    /* ══════════════════════════════════════
       ACTION BUTTONS
    ══════════════════════════════════════ */
    #listTable .btn {
        border-radius: 7px !important;
        font-size: 12px !important;
        padding: 5px 10px !important;
        font-weight: 600 !important;
        border: none !important;
        transition: all .18s !important;
        display: inline-flex !important;
        align-items: center !important;
        gap: 4px !important;
        white-space: nowrap !important;
        margin: 2px !important;
    }
    #listTable .btn-detail  { background: #ebf2ff !important; color: #2d6bc4 !important; }
    #listTable .btn-edit    { background: #fef3c7 !important; color: #92400e !important; }
    #listTable .btn-pdf     { background: #fee2e2 !important; color: #991b1b !important; text-decoration: none !important; }
    #listTable .btn-del     { background: #fee2e2 !important; color: #991b1b !important; }
    #listTable .btn:hover   { opacity: .82; transform: translateY(-1px); }

    /* ══════════════════════════════════════
       LOADING OVERLAY
    ══════════════════════════════════════ */
    #loading {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30,31,58,.35);
        backdrop-filter: blur(4px);
        z-index: 9999;
        align-items: center;
        justify-content: center;
    }
    #loading.show-flex { display: flex; }
    .loading-box {
        background: #fff;
        border-radius: 20px;
        padding: 36px 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        box-shadow: 0 12px 40px rgba(0,0,0,.15);
    }
    .loading-spinner {
        width: 42px; height: 42px;
        border: 3px solid #e2e8f0;
        border-top-color: #2d6bc4;
        border-radius: 50%;
        animation: spin .75s linear infinite;
    }
    @keyframes spin { to { transform: rotate(360deg); } }
    .loading-box p { font-size: 13px; color: #718096; margin: 0; font-weight: 600; }

    /* ══════════════════════════════════════
       MODAL FORM (Create/Edit)
    ══════════════════════════════════════ */
    .modal-content { border: none; border-radius: 16px; box-shadow: 0 12px 48px rgba(0,0,0,.18); overflow: hidden; }

    .mh-brand {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 18px 24px;
        background: linear-gradient(135deg, #2d2b4e, #605ca8);
    }
    .mh-brand h4 { color: #fff; font-size: 15px; font-weight: 700; margin: 0; }
    .mh-close {
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        width: 30px; height: 30px;
        border-radius: 8px;
        cursor: pointer;
        font-size: 16px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s;
        line-height: 1;
    }
    .mh-close:hover { background: rgba(255,255,255,.28); }

    .mb { padding: 24px; }

    .form-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 14px 22px; }

    .ff { display: flex; flex-direction: column; gap: 5px; }
    .ff label {
        font-size: 11px;
        font-weight: 700;
        color: #4a5568;
        letter-spacing: .06em;
        text-transform: uppercase;
        margin: 0;
    }
    .ff label .req { color: #e03131; margin-left: 2px; }
    .ff input, .ff select, .ff textarea {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        padding: 9px 12px !important;
        font-size: 13px !important;
        color: #1a202c !important;
        background: #fafbff !important;
        outline: none !important;
        transition: border-color .18s, box-shadow .18s !important;
        width: 100% !important;
    }
    .ff input:focus, .ff select:focus, .ff textarea:focus {
        border-color: #2d6bc4 !important;
        background: #fff !important;
        box-shadow: 0 0 0 3px rgba(45,107,196,.1) !important;
    }
    .ff input[type=file] { padding: 7px 10px !important; cursor: pointer; background: #fff !important; }
    .ff .iw { position: relative; }
    .ff .iw i { position: absolute; left: 11px; top: 50%; transform: translateY(-50%); color: #a0aec0; font-size: 12px; pointer-events: none; z-index: 1; }
    .ff .iw input { padding-left: 32px !important; }

    .sec-divider {
        display: flex;
        align-items: center;
        gap: 10px;
        margin: 20px 0 16px;
    }
    .sec-divider span {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #a0aec0;
        white-space: nowrap;
    }
    .sec-divider::before, .sec-divider::after {
        content: '';
        flex: 1;
        height: 1px;
        background: #edf0f5;
    }

    .mf {
        display: flex;
        gap: 10px;
        padding: 16px 24px;
        border-top: 1px solid #edf0f5;
        background: #fafbff;
    }
    .btn-mf {
        flex: 1;
        padding: 11px;
        border: none;
        border-radius: 8px;
        font-size: 13px;
        font-weight: 700;
        cursor: pointer;
        transition: all .18s;
    }
    .btn-mf:hover { opacity: .88; transform: translateY(-1px); }
    .btn-save   { background: linear-gradient(135deg, #2d6bc4, #1a4d9a); color: #fff; box-shadow: 0 4px 14px rgba(45,107,196,.3); }
    .btn-update { background: linear-gradient(135deg, #2563eb, #1d4ed8); color: #fff; }

    /* ══════════════════════════════════════
       MODAL DETAIL
    ══════════════════════════════════════ */
    #modalDetail {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(15,20,40,.55);
        backdrop-filter: blur(6px);
        z-index: 9990;
        align-items: center;
        justify-content: center;
        padding: 24px;
    }
    #modalDetail.show { display: flex; }

    .detail-panel {
        background: #fff;
        border-radius: 18px;
        width: 100%;
        max-width: 740px;
        max-height: 88vh;
        overflow-y: auto;
        box-shadow: 0 20px 60px rgba(0,0,0,.2);
        animation: slideUp .28s cubic-bezier(.22,1,.36,1) both;
    }

    .btn-add-molding {
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
    .btn-add-molding:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 24px rgba(0,0,0,.22);
        color: #605ca8;
        text-decoration: none;
    }
    
    @keyframes slideUp { from { opacity:0; transform:translateY(24px); } to { opacity:1; transform:translateY(0); } }

    .detail-hero {
        background: linear-gradient(135deg, #2d2b4e 0%, #4a4690 60%, #605ca8 100%);
        padding: 24px 28px 20px;
        position: relative;
        overflow: hidden;
    }
    .detail-hero::before {
        content: '';
        position: absolute;
        right: -30px; top: -30px;
        width: 150px; height: 150px;
        border-radius: 50%;
        background: rgba(255,255,255,.05);
    }
    .detail-hero-row { display: flex; align-items: flex-start; justify-content: space-between; gap: 12px; position: relative; z-index: 1; }
    .detail-hero-left h3 { color: #fff; font-size: 17px; font-weight: 700; margin: 0 0 5px; }
    .detail-hero-left p  { color: rgba(255,255,255,.55); font-size: 12.5px; margin: 0; }
    .detail-hero-close {
        background: rgba(255,255,255,.15);
        border: none;
        color: #fff;
        width: 32px; height: 32px;
        border-radius: 9px;
        cursor: pointer;
        font-size: 17px;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: background .18s;
        flex-shrink: 0;
        line-height: 1;
    }
    .detail-hero-close:hover { background: rgba(255,255,255,.28); }

    .detail-meta {
        display: flex;
        gap: 8px;
        flex-wrap: wrap;
        margin-top: 14px;
        position: relative;
        z-index: 1;
    }
    .detail-tag {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: rgba(255,255,255,.12);
        border: 1px solid rgba(255,255,255,.18);
        color: #c3d9f8;
        padding: 4px 11px;
        border-radius: 20px;
        font-size: 11px;
        font-weight: 600;
    }

    .detail-body { padding: 24px 28px; }

    .detail-section-title {
        font-size: 11px;
        font-weight: 700;
        letter-spacing: .08em;
        text-transform: uppercase;
        color: #a0aec0;
        margin-bottom: 14px;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .detail-section-title::after { content: ''; flex: 1; height: 1px; background: #edf0f5; }

    .detail-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 12px 24px; margin-bottom: 22px; }

    .detail-item { display: flex; flex-direction: column; gap: 3px; }
    .detail-item .di-label { font-size: 11px; color: #a0aec0; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; }
    .detail-item .di-value { font-size: 13.5px; color: #1a202c; font-weight: 600; }
    .detail-item .di-value.mono { font-size: 13px; }
    .detail-item .di-value.big  { font-size: 18px; font-weight: 800; color: #1a4d9a; }

    .detail-file-row {
        display: flex;
        align-items: center;
        gap: 12px;
        background: #f7f8fc;
        border: 1.5px solid #edf0f5;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
    }
    .detail-file-icon {
        width: 40px; height: 40px;
        border-radius: 10px;
        background: #ede9fe;
        color: #7c3aed;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 18px;
        flex-shrink: 0;
    }
    .detail-file-info { flex: 1; min-width: 0; }
    .detail-file-info .fi-name { font-size: 13px; font-weight: 700; color: #1a202c; margin-bottom: 2px; }
    .detail-file-info .fi-sub  { font-size: 11.5px; color: #a0aec0; }
    .detail-file-link {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: #7c3aed;
        color: #fff;
        padding: 7px 14px;
        border-radius: 8px;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none;
        transition: background .18s;
        flex-shrink: 0;
    }
    .detail-file-link:hover { background: #6d28d9; color: #fff; text-decoration: none; }
    .detail-no-file { color: #a0aec0; font-size: 12.5px; font-style: italic; }

    .detail-status-row {
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: #f7f8fc;
        border-radius: 10px;
        padding: 14px 16px;
        margin-bottom: 20px;
        gap: 12px;
        flex-wrap: wrap;
    }
    .detail-status-left { display: flex; flex-direction: column; gap: 4px; }
    .detail-status-left .ds-label { font-size: 11px; color: #a0aec0; font-weight: 600; letter-spacing: .04em; text-transform: uppercase; }
    .detail-actions { display: flex; gap: 8px; flex-wrap: wrap; }
    .btn-det-action {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 12.5px;
        font-weight: 700;
        cursor: pointer;
        border: none;
        text-decoration: none;
        transition: all .18s;
    }
    .btn-det-action:hover { opacity: .85; transform: translateY(-1px); text-decoration: none; }
    .bda-edit { background: #fef3c7; color: #92400e; }
    .bda-pdf  { background: #ede9fe; color: #7c3aed; }
    .bda-file { background: #ebf2ff; color: #2d6bc4; }
    .bda-del  { background: #fee2e2; color: #991b1b; }

    /* ══════════════════════════════════════
       MODAL DELETE
    ══════════════════════════════════════ */
    .mh-danger { background: #dc2626 !important; }
    .del-body  { display: flex; gap: 16px; align-items: flex-start; padding: 24px; }
    .del-ico   { width: 46px; height: 46px; border-radius: 12px; background: #fee2e2; display: flex; align-items: center; justify-content: center; flex-shrink: 0; font-size: 18px; color: #dc2626; }
    .del-body h5 { font-size: 15px; font-weight: 700; color: #1a202c; margin: 0 0 5px; }
    .del-body p  { font-size: 13px; color: #718096; margin: 0; line-height: 1.6; }

    /* ══════════════════════════════════════
       SELECT2
    ══════════════════════════════════════ */
    .select2-container .select2-selection--single {
        border: 1.5px solid #e2e8f0 !important;
        border-radius: 8px !important;
        height: 38px !important;
        background: #fafbff !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__rendered {
        line-height: 36px !important;
        font-size: 13px !important;
        color: #1a202c !important;
        padding-left: 12px !important;
    }
    .select2-container--default .select2-selection--single .select2-selection__arrow { height: 36px !important; }
    .select2-container--focus .select2-selection--single,
    .select2-container--open .select2-selection--single {
        border-color: #2d6bc4 !important;
        box-shadow: 0 0 0 3px rgba(45,107,196,.1) !important;
        outline: none !important;
    }
    .select2-dropdown { border: 1.5px solid #e2e8f0 !important; border-radius: 10px !important; box-shadow: 0 8px 32px rgba(0,0,0,.12) !important; }
    .select2-results__option--highlighted { background: #2d6bc4 !important; }

    /* OCR */
    .ocr-drop {
        border: 2px dashed #c3d9f8;
        border-radius: 10px;
        background: #fafbff;
        padding: 24px 20px;
        text-align: center;
        cursor: pointer;
        transition: border-color .18s, background .18s;
    }
    .ocr-drop:hover { border-color: #2d6bc4; background: #ebf2ff; }
    .ocr-drop i { font-size: 26px; color: #a0aec0; display: block; margin-bottom: 8px; }
    .ocr-drop p { font-size: 13px; color: #718096; margin: 0 0 3px; }
    .ocr-drop small { font-size: 11.5px; color: #a0aec0; }
    .badge-beta { font-size: 9.5px; font-weight: 700; background: #fef3c7; color: #92400e; padding: 2px 8px; border-radius: 20px; letter-spacing: .05em; text-transform: uppercase; }
    .ocr-stat  { font-size: 11.5px; font-weight: 600; padding: 3px 10px; border-radius: 20px; }
    .ocr-online   { background: #dcfce7; color: #15803d; }
    .ocr-offline  { background: #fee2e2; color: #991b1b; }
    .ocr-checking { background: #ebf2ff; color: #2d6bc4; }
</style>
@endsection


@section('content')
<meta name="csrf-token" content="{{ csrf_token() }}">

{{-- Loading --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>

<div class="content-header" style="padding:0 28px">
    {{-- ── PAGE HEADER ── --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag"><i class="fas fa-file-invoice"></i> Invoice Management</div>
            <h1>Tanda Terima</h1>
            <p>Kelola dan monitoring invoice vendor secara terpusat</p>
        </div>
        <a href="{{ url('index/invoice/tanda_terima/create') }}" class="btn-add-molding">
            <i class="fas fa-plus"></i> Tambah Tanda Terima
        </a>
    </div>

    {{-- ── STAT CARDS ── --}}
    <div class="stat-row">
        <div class="stat-card">
            <div class="stat-icon si-blue"><i class="fas fa-file-invoice"></i></div>
            <div>
                <div class="stat-val">5</div>
                <div class="stat-lbl">Total Invoice</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-purple"><i class="fas fa-paper-plane"></i></div>
            <div>
                <div class="stat-val">2</div>
                <div class="stat-lbl">Payment Requested</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-amber"><i class="fas fa-clock"></i></div>
            <div>
                <div class="stat-val">3</div>
                <div class="stat-lbl">Belum Dibayar</div>
            </div>
        </div>
        <div class="stat-card">
            <div class="stat-icon si-green"><i class="fas fa-coins"></i></div>
            <div>
                <div class="stat-val">57,7 Jt</div>
                <div class="stat-lbl">Total Amount (IDR)</div>
            </div>
        </div>
    </div>

    {{-- ── TABLE CARD ── --}}
    <div class="table-card">
        <div class="table-card-header">
            <div class="table-card-title">
                <span class="dot"></span>
                Daftar Invoice
            </div>
            <div style="display:flex;gap:8px;align-items:center;flex-wrap:wrap;">
                {{-- Export buttons group --}}
                <div id="dt-btn-group" style="display:flex;gap:4px;align-items:center;"></div>
                {{-- Date filter + export --}}
                <form method="GET" action="{{ url('export/invoice/tanda_terima') }}" style="display:flex;gap:6px;align-items:center;">
                    <div style="position:relative;">
                        <input type="date" class="datepicker" name="datefrom" id="datefrom"
                               style="border:1.5px solid #e2e8f0;border-radius:8px;font-size:12px;outline:none;width:118px;"
                               placeholder="yyyy-mm-dd">
                    </div>
                    <span style="font-size:12px;color:#a0aec0;font-weight:600;">—</span>
                    <div style="position:relative;">
                        <input type="date" class="datepicker" name="dateto" id="dateto"
                               style="border:1.5px solid #e2e8f0;border-radius:8px;font-size:12px;outline:none;width:118px;"
                               placeholder="yyyy-mm-dd">
                    </div>
                </form>
            </div>
        </div>

        <table id="listTable" class="table" style="width:100%">
            <thead>
                <tr>
                    <th>#</th>
                    <th>Supplier</th>
                    <th>Invoice No</th>
                    <th>Currency</th>
                    <th>Amount</th>
                    <th>Due Date</th>
                    <th>Status</th>
                    <th>Aksi</th>
                </tr>
            </thead>
            <tbody id="listTableBody">
                <tr>
                    <td>1001</td>
                    <td>
                        <div class="supplier-cell">
                            <div class="sup-avatar">PT</div>
                            <div>
                                <div class="sup-name">PT. Supplier Indonesia</div>
                                <div class="sup-code">SUP001</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12.5px;">INV/2025/001</span></td>
                    <td><span class="curr-badge curr-IDR">IDR</span></td>
                    <td class="amt-cell">15.500.000</td>
                    <td><span class="date-badge"><i class="fas fa-calendar-alt"></i> 31-Jan-2025</span></td>
                    <td><span class="status-badge s-requested">Payment Requested</span></td>
                    <td>
                        <button class="btn btn-detail" onclick="openDetail({id:1001,supplier:'PT. Supplier Indonesia',code:'SUP001',invoice_no:'INV/2025/001',surat_jalan:'SJ-2025-0045',po_number:'PO-2025-0011',invoice_date:'01-Jan-2025',do_date:'28-Jan-2025',due_date:'31-Jan-2025',currency:'IDR',amount:'15.500.000',payment_term:'End of Month',bap:'BAP-001',npwp:'01.234.567.8-901.000',faktur_pajak:'FP-2025-001',detail_item:'Pembelian material produksi Q1 2025',buyer:'Budi Santoso',distribution_date:'02-Jan-2025',status:'requested',has_file:true})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="btn btn-edit" onclick="newData('1001')"><i class="fas fa-pen"></i></button>
                        <a class="btn btn-pdf" href="#" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1002</td>
                    <td>
                        <div class="supplier-cell">
                            <div class="sup-avatar">CV</div>
                            <div>
                                <div class="sup-name">CV. Mitra Usaha</div>
                                <div class="sup-code">SUP002</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12.5px;">INV/2025/002</span></td>
                    <td><span class="curr-badge curr-USD">USD</span></td>
                    <td class="amt-cell">1,250.00</td>
                    <td><span class="date-badge"><i class="fas fa-calendar-alt"></i> 04-Feb-2025</span></td>
                    <td><span class="status-badge s-not-pay">Not Payment</span></td>
                    <td>
                        <button class="btn btn-detail" onclick="openDetail({id:1002,supplier:'CV. Mitra Usaha',code:'SUP002',invoice_no:'INV/2025/002',surat_jalan:'SJ-2025-0046',po_number:'PO-2025-0012',invoice_date:'05-Jan-2025',do_date:'01-Feb-2025',due_date:'04-Feb-2025',currency:'USD',amount:'1,250.00',payment_term:'30 Days',bap:'-',npwp:'02.345.678.9-012.000',faktur_pajak:'-',detail_item:'Jasa konsultasi teknik',buyer:'Siti Rahayu',distribution_date:'06-Jan-2025',status:'not-pay',has_file:true})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="btn btn-edit" onclick="newData('1002')"><i class="fas fa-pen"></i></button>
                        <a class="btn btn-pdf" href="#" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <button class="btn btn-del" onclick="deleteConfirmationInv(1002)" data-toggle="modal" data-target="#modalDeleteInvoice"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>1003</td>
                    <td>
                        <div class="supplier-cell">
                            <div class="sup-avatar">PT</div>
                            <div>
                                <div class="sup-name">PT. Global Trade</div>
                                <div class="sup-code">SUP003</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12.5px;">INV/2025/003</span></td>
                    <td><span class="curr-badge curr-IDR">IDR</span></td>
                    <td class="amt-cell">8.750.000</td>
                    <td><span class="date-badge"><i class="fas fa-calendar-alt"></i> 09-Feb-2025</span></td>
                    <td><span class="status-badge s-not-pay">Not Payment</span></td>
                    <td>
                        <button class="btn btn-detail" onclick="openDetail({id:1003,supplier:'PT. Global Trade',code:'SUP003',invoice_no:'INV/2025/003',surat_jalan:'SJ-2025-0050',po_number:'PO-2025-0015',invoice_date:'10-Jan-2025',do_date:'06-Feb-2025',due_date:'09-Feb-2025',currency:'IDR',amount:'8.750.000',payment_term:'14 Days',bap:'BAP-003',npwp:'03.456.789.0-123.000',faktur_pajak:'FP-2025-003',detail_item:'Pengadaan spare part mesin',buyer:'Ahmad Fauzi',distribution_date:'11-Jan-2025',status:'not-pay',has_file:false})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="btn btn-edit" onclick="newData('1003')"><i class="fas fa-pen"></i></button>
                        <a class="btn btn-pdf" href="#" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <button class="btn btn-del" onclick="deleteConfirmationInv(1003)" data-toggle="modal" data-target="#modalDeleteInvoice"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
                <tr>
                    <td>1004</td>
                    <td>
                        <div class="supplier-cell">
                            <div class="sup-avatar">UD</div>
                            <div>
                                <div class="sup-name">UD. Jaya Perkasa</div>
                                <div class="sup-code">SUP004</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12.5px;">INV/2025/004</span></td>
                    <td><span class="curr-badge curr-JPY">JPY</span></td>
                    <td class="amt-cell">320.000</td>
                    <td><span class="date-badge"><i class="fas fa-calendar-alt"></i> 14-Feb-2025</span></td>
                    <td><span class="status-badge s-requested">Payment Requested</span></td>
                    <td>
                        <button class="btn btn-detail" onclick="openDetail({id:1004,supplier:'UD. Jaya Perkasa',code:'SUP004',invoice_no:'INV/2025/004',surat_jalan:'SJ-2025-0055',po_number:'PO-2025-0018',invoice_date:'15-Jan-2025',do_date:'11-Feb-2025',due_date:'14-Feb-2025',currency:'JPY',amount:'320.000',payment_term:'End.of Next Month After Rec.Date',bap:'-',npwp:'04.567.890.1-234.000',faktur_pajak:'FP-2025-004',detail_item:'Import komponen elektronik',buyer:'Dewi Lestari',distribution_date:'16-Jan-2025',status:'requested',has_file:true})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <a class="btn btn-pdf" href="#" target="_blank"><i class="fas fa-file-pdf"></i></a>
                    </td>
                </tr>
                <tr>
                    <td>1005</td>
                    <td>
                        <div class="supplier-cell">
                            <div class="sup-avatar">PT</div>
                            <div>
                                <div class="sup-name">PT. Sinar Jaya</div>
                                <div class="sup-code">SUP005</div>
                            </div>
                        </div>
                    </td>
                    <td><span style="font-family:monospace;font-size:12.5px;">INV/2025/005</span></td>
                    <td><span class="curr-badge curr-IDR">IDR</span></td>
                    <td class="amt-cell">33.200.000</td>
                    <td><span class="date-badge"><i class="fas fa-calendar-alt"></i> 19-Feb-2025</span></td>
                    <td><span class="status-badge s-not-pay">Not Payment</span></td>
                    <td>
                        <button class="btn btn-detail" onclick="openDetail({id:1005,supplier:'PT. Sinar Jaya',code:'SUP005',invoice_no:'INV/2025/005',surat_jalan:'SJ-2025-0060',po_number:'PO-2025-0020',invoice_date:'20-Jan-2025',do_date:'16-Feb-2025',due_date:'19-Feb-2025',currency:'IDR',amount:'33.200.000',payment_term:'End of Month',bap:'BAP-005',npwp:'05.678.901.2-345.000',faktur_pajak:'FP-2025-005',detail_item:'Pembelian bahan baku produksi Februari',buyer:'Budi Santoso',distribution_date:'21-Jan-2025',status:'not-pay',has_file:true})">
                            <i class="fas fa-eye"></i> Detail
                        </button>
                        <button class="btn btn-edit" onclick="newData('1005')"><i class="fas fa-pen"></i></button>
                        <a class="btn btn-pdf" href="#" target="_blank"><i class="fas fa-file-pdf"></i></a>
                        <button class="btn btn-del" onclick="deleteConfirmationInv(1005)" data-toggle="modal" data-target="#modalDeleteInvoice"><i class="fas fa-trash"></i></button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr><th></th><th></th><th></th><th></th><th></th><th></th><th></th><th></th></tr>
            </tfoot>
        </table>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL DETAIL
══════════════════════════════════════ --}}
<div id="modalDetail">
    <div class="detail-panel">
        <div class="detail-hero">
            <div class="detail-hero-row">
                <div class="detail-hero-left">
                    <h3 id="dh-title">Detail Invoice</h3>
                    <p id="dh-supplier">—</p>
                </div>
                <button class="detail-hero-close" onclick="closeDetail()">&times;</button>
            </div>
            <div class="detail-meta">
                <span class="detail-tag"><i class="fas fa-hashtag"></i> <span id="dh-id">—</span></span>
                <span class="detail-tag"><i class="fas fa-file-invoice"></i> <span id="dh-inv">—</span></span>
                <span class="detail-tag" id="dh-status-wrap"></span>
            </div>
        </div>

        <div class="detail-body">

            {{-- Status & Actions --}}
            <div class="detail-status-row">
                <div class="detail-status-left">
                    <div class="ds-label">Status Pembayaran</div>
                    <div id="dh-status-badge"></div>
                </div>
                <div class="detail-actions">
                    <button class="btn-det-action bda-file" id="dh-btn-file" onclick="changeQuotation('')"><i class="fas fa-file-alt"></i> Ganti File</button>
                    <button class="btn-det-action bda-edit" id="dh-btn-edit" onclick="newData('')"><i class="fas fa-pen"></i> Edit</button>
                    <a   class="btn-det-action bda-pdf"  href="#" target="_blank"><i class="fas fa-file-pdf"></i> PDF</a>
                </div>
            </div>

            {{-- File Invoice --}}
            <div class="detail-section-title"><i class="fas fa-paperclip" style="color:#a0aec0"></i> File Invoice</div>
            <div id="dh-file-wrap"></div>

            {{-- Info Utama --}}
            <div class="detail-section-title"><i class="fas fa-info-circle" style="color:#a0aec0"></i> Informasi Invoice</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="di-label">Invoice Date</div>
                    <div class="di-value" id="dh-invoice-date">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">PO Number</div>
                    <div class="di-value mono" id="dh-po">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Surat Jalan</div>
                    <div class="di-value mono" id="dh-sj">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Payment Term</div>
                    <div class="di-value" id="dh-term">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">DO Date</div>
                    <div class="di-value" id="dh-do-date">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Due Date</div>
                    <div class="di-value" id="dh-due-date">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Distribution Date</div>
                    <div class="di-value" id="dh-dist-date">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Buyer</div>
                    <div class="di-value" id="dh-buyer">—</div>
                </div>
            </div>

            {{-- Keuangan --}}
            <div class="detail-section-title"><i class="fas fa-coins" style="color:#a0aec0"></i> Informasi Keuangan</div>
            <div class="detail-grid">
                <div class="detail-item">
                    <div class="di-label">Currency</div>
                    <div class="di-value" id="dh-currency">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Total Amount</div>
                    <div class="di-value big" id="dh-amount">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">NPWP</div>
                    <div class="di-value mono" id="dh-npwp">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">Faktur Pajak</div>
                    <div class="di-value mono" id="dh-fp">—</div>
                </div>
                <div class="detail-item">
                    <div class="di-label">BAP</div>
                    <div class="di-value" id="dh-bap">—</div>
                </div>
            </div>

            {{-- Detail --}}
            <div class="detail-section-title"><i class="fas fa-align-left" style="color:#a0aec0"></i> Detail Pembayaran</div>
            <div style="background:#f7f8fc;border-radius:10px;padding:14px 16px;font-size:13.5px;color:#1a202c;line-height:1.6;margin-bottom:8px;" id="dh-detail">—</div>

        </div>
    </div>
</div>


{{-- ══════════════════════════════════════
     MODAL: Ganti File Invoice
══════════════════════════════════════ --}}
<div class="modal fade" id="modalQuotation" tabindex="-1">
    <div class="modal-dialog" style="max-width:460px">
        <div class="modal-content">
            <div class="mh-brand">
                <h4 id="modalQuotationTitle">Update File Invoice</h4>
                <button class="mh-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="mb">
                <input type="hidden" id="id_edit_quo">
                <div class="ff">
                    <label>File Invoice <span class="req">*</span></label>
                    <input type="file" id="file_quo" name="file_quo" accept="application/pdf">
                </div>
            </div>
            <div class="mf">
                <button class="btn-mf btn-update" onclick="updateInvoice()">
                    <i class="fas fa-save"></i>&nbsp; Update File
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ══════════════════════════════════════
     MODAL: Hapus
══════════════════════════════════════ --}}
<div class="modal fade" id="modalDeleteInvoice" tabindex="-1">
    <div class="modal-dialog" style="max-width:420px">
        <div class="modal-content">
            <div class="mh-brand mh-danger">
                <h4>Konfirmasi Hapus</h4>
                <button class="mh-close" data-dismiss="modal">&times;</button>
            </div>
            <div class="del-body">
                <div class="del-ico"><i class="fas fa-trash"></i></div>
                <div>
                    <h5>Hapus Invoice?</h5>
                    <p>Apakah anda yakin ingin menghapus invoice ini? Tindakan ini <strong>tidak dapat dibatalkan</strong>.</p>
                </div>
            </div>
            <div class="mf" style="justify-content:flex-end">
                <button type="button" class="btn-export" data-dismiss="modal">Batal</button>
                <a id="a" name="modalButton" href="javascript:void(0)" onclick="deletePR(this.id)"
                   style="display:inline-flex;align-items:center;gap:6px;padding:9px 20px;background:#dc2626;color:#fff;border-radius:8px;font-size:13px;font-weight:700;text-decoration:none;transition:all .18s;">
                    <i class="fas fa-trash"></i> Hapus
                </a>
            </div>
        </div>
    </div>
</div>

@endsection


@section('scripts')
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>
<script src="{{ url('js/dataTables.buttons.min.js') }}"></script>
<script src="{{ url('js/buttons.flash.min.js') }}"></script>
<script src="{{ url('js/jszip.min.js') }}"></script>
<script src="{{ url('js/vfs_fonts.js') }}"></script>
<script src="{{ url('js/buttons.html5.min.js') }}"></script>
<script src="{{ url('js/buttons.print.min.js') }}"></script>

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

        checkOcrServerAvailability();
        setInterval(checkOcrServerAvailability, 5000);

        /* Default tanggal: awal bulan ini s/d hari ini */
        var now   = new Date();
        var y     = now.getFullYear();
        var m     = String(now.getMonth()+1).padStart(2,'0');
        var d     = String(now.getDate()).padStart(2,'0');
        var today = y+'-'+m+'-'+d;
        var first = y+'-'+m+'-01';
        $('#datefrom').val(first);
        $('#dateto').val(today);

        /* DataTable */
        $('#listTable tfoot th').each(function () {
            var t = $(this).text();
            $(this).html('<input type="text" placeholder="' + t + '" style="width:100%;border:1.5px solid #e2e8f0;border-radius:6px;padding:4px 7px;font-size:11.5px;outline:none;">');
        });

        var table = $('#listTable').DataTable({
            dom: "<'dt-top-bar'l>rt<'dt-bottom-bar'ip>",
            responsive: true,
            lengthMenu: [[10, 25, 50, -1], ['10', '25', '50', 'Semua']],
            buttons: [
                { extend: 'pageLength', className: 'btn-dt btn-dt-default', text: '<i class="fas fa-list"></i> Show' },
                { extend: 'copy',       className: 'btn-dt btn-dt-copy',    text: '<i class="fas fa-copy"></i> Copy',  exportOptions: { columns: ':not(.notexport)' } },
                { extend: 'excel',      className: 'btn-dt btn-dt-excel',   text: '<i class="fas fa-file-excel"></i> Excel', exportOptions: { columns: ':not(.notexport)' } },
                { extend: 'print',      className: 'btn-dt btn-dt-print',   text: '<i class="fas fa-print"></i> Print', exportOptions: { columns: ':not(.notexport)' } },
            ],
            paging: true, lengthChange: false, pageLength: 20,
            searching: true, ordering: true, order: [],
            info: true, autoWidth: false, processing: true,
            sPaginationType: "full_numbers",
            columnDefs: [
                { targets: 0, width: '42px',  className: 'dt-center' },
                { targets: 3, width: '60px',  className: 'dt-center' },
            ],
            initComplete: function() {
                /* Pindahkan buttons ke toolbar header card */
                var api = this.api();
                var btnContainer = new $.fn.dataTable.Buttons(api, {
                    buttons: [
                        { extend: 'pageLength', className: 'btn-dt btn-dt-default', text: '<i class="fas fa-list"></i> Show' },
                        { extend: 'copy',       className: 'btn-dt btn-dt-copy',    text: '<i class="fas fa-copy"></i> Copy',  exportOptions: { columns: ':not(.notexport)' } },
                        { extend: 'excel',      className: 'btn-dt btn-dt-excel',   text: '<i class="fas fa-file-excel"></i> Excel', exportOptions: { columns: ':not(.notexport)' } },
                        { extend: 'print',      className: 'btn-dt btn-dt-print',   text: '<i class="fas fa-print"></i> Print', exportOptions: { columns: ':not(.notexport)' } },
                    ]
                });
                $('#dt-btn-group').append(btnContainer.container());
            }
        });

        table.columns().every(function () {
            var that = this;
            $('input', this.footer()).on('keyup change', function () {
                if (that.search() !== this.value) { that.search(this.value).draw(); }
            });
        });
        $('#listTable tfoot tr').appendTo('#listTable thead');
    });

    /* ── Loading ── */
    function showLoading() { $('#loading').addClass('show-flex'); }
    function hideLoading() { $('#loading').removeClass('show-flex'); }

    /* ══════════════════════════════════════
       MODAL DETAIL
    ══════════════════════════════════════ */
    function openDetail(d) {
        /* hero */
        document.getElementById('dh-title').innerText    = 'Invoice #' + d.id;
        document.getElementById('dh-supplier').innerText = d.supplier + ' (' + d.code + ')';
        document.getElementById('dh-id').innerText       = d.id;
        document.getElementById('dh-inv').innerText      = d.invoice_no;

        /* status tag in hero */
        var isReq = d.status === 'requested';
        document.getElementById('dh-status-wrap').innerHTML =
            isReq ? '<i class="fas fa-paper-plane"></i> Payment Requested'
                  : '<i class="fas fa-clock"></i> Not Payment';

        /* status badge */
        document.getElementById('dh-status-badge').innerHTML =
            isReq ? '<span class="status-badge s-requested">Payment Requested</span>'
                  : '<span class="status-badge s-not-pay">Not Payment</span>';

        /* actions */
        document.getElementById('dh-btn-file').setAttribute('onclick', "changeQuotation('" + d.id + "')");
        document.getElementById('dh-btn-edit').setAttribute('onclick', "newData('" + d.id + "')");

        /* file */
        document.getElementById('dh-file-wrap').innerHTML = d.has_file
            ? '<div class="detail-file-row">'
                + '<div class="detail-file-icon"><i class="fas fa-file-pdf"></i></div>'
                + '<div class="detail-file-info"><div class="fi-name">invoice_' + d.id + '.pdf</div><div class="fi-sub">File invoice tersedia</div></div>'
                + '<a href="#" target="_blank" class="detail-file-link"><i class="fas fa-eye"></i> Lihat File</a>'
                + '</div>'
            : '<p class="detail-no-file"><i class="fas fa-exclamation-circle" style="color:#f59e0b;margin-right:5px;"></i>File invoice belum diupload.</p>';

        /* fields */
        document.getElementById('dh-invoice-date').innerText = d.invoice_date;
        document.getElementById('dh-po').innerText           = d.po_number;
        document.getElementById('dh-sj').innerText           = d.surat_jalan;
        document.getElementById('dh-term').innerText         = d.payment_term;
        document.getElementById('dh-do-date').innerText      = d.do_date;
        document.getElementById('dh-due-date').innerText     = d.due_date;
        document.getElementById('dh-dist-date').innerText    = d.distribution_date;
        document.getElementById('dh-buyer').innerText        = d.buyer;
        document.getElementById('dh-currency').innerHTML     = '<span class="curr-badge curr-' + d.currency + '">' + d.currency + '</span>';
        document.getElementById('dh-amount').innerText       = d.amount;
        document.getElementById('dh-npwp').innerText         = d.npwp;
        document.getElementById('dh-fp').innerText           = d.faktur_pajak;
        document.getElementById('dh-bap').innerText          = d.bap;
        document.getElementById('dh-detail').innerText       = d.detail_item;

        document.getElementById('modalDetail').classList.add('show');
        document.body.style.overflow = 'hidden';
    }

    function closeDetail() {
        document.getElementById('modalDetail').classList.remove('show');
        document.body.style.overflow = '';
    }
    document.getElementById('modalDetail').addEventListener('click', function(e) {
        if (e.target === this) closeDetail();
    });

    /* ══════════════════════════════════════
       FORM FUNCTIONS
    ══════════════════════════════════════ */
    function newData(id) {
        if (id == 'new') {
            $('#modalNewTitle').text('Buat Tanda Terima');
            $('#newButton').show(); $('#updateButton').hide();
            clearNew();
            $('#modalNew').modal('show');
            $('#payment_term').val('End.of Next Month After Rec.Date').trigger('change');
            $('#currency').val('IDR').trigger('change');
        } else {
            $('#newButton').hide(); $('#updateButton').show();
            showLoading();
            $.get('{{ url("invoice/tanda_terima_detail") }}', { id: id }, function (result) {
                if (result.status) {
                    $('#invoice_date').val(result.invoice.invoice_date);
                    $('#id_edit').val(result.invoice.id);
                    $('#modalNewTitle').text('Update Tanda Terima');
                    hideLoading(); $('#modalNew').modal('show');
                } else { openErrorGritter('Error', result.message); hideLoading(); }
            });
        }
    }

    function SaveInvoice(id) {
        showLoading();
        if (id == 'new') {
            if (!$('#invoice_date').val() || !$('#supplier_code').val() || !$('#invoice_no').val() || !$('#po_number').val() || !$('#payment_term').val() || !$('#currency').val() || !$('#amount').val() || !$('#do_date').val() || !$('#due_date').val() || !$('#detail_item').val()) {
                hideLoading(); openErrorGritter('Error', 'Harap isi semua field bertanda (*).'); return;
            }
            if (!$('#file_attach').val()) { openErrorGritter('Error', 'Invoice harus dilampirkan.'); hideLoading(); return; }
        }
        var url = id == 'new' ? '{{ url("create/invoice/tanda_terima") }}' : '{{ url("edit/invoice/tanda_terima") }}';
        var formData = new FormData();
        if (id != 'new') formData.append('id_edit', $('#id_edit').val());
        formData.append('category','General');
        ['invoice_date','supplier_code','supplier_name','invoice_no','surat_jalan','bap','npwp',
         'faktur_pajak','po_number','payment_term','currency','amount','do_date','due_date',
         'detail_item','distribution_date'].forEach(function(f){ formData.append(f, $('#'+f).val()); });
        formData.append('file_attach', $('#file_attach').prop('files')[0]);
        $.ajax({ url: url, method: 'POST', data: formData, dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function (data) {
                if (data.status) { openSuccessGritter('Berhasil', data.message); audio_ok.play(); hideLoading(); $('#modalNew').modal('hide'); clearNew(); }
                else { openErrorGritter('Error!', data.message); hideLoading(); audio_error.play(); }
            }
        });
    }

    function clearNew() {
        $('#id_edit,#invoice_no,#surat_jalan,#bap,#npwp,#faktur_pajak,#po_number,#amount,#do_date,#due_date,#detail_item,#distribution_date,#file_attach,#invoice_date,#supplier_name').val('');
        $('#supplier_code,#payment_term,#currency').val('').trigger('change');
    }

    function changeQuotation(id) {
        $('#id_edit_quo').val(id);
        $('#modalQuotationTitle').text('Update File Invoice — #' + id);
        $('#modalQuotation').modal('show');
    }

    function updateInvoice() {
        showLoading();
        var formData = new FormData();
        formData.append('id_edit_quo', $('#id_edit_quo').val());
        formData.append('file_quo', $('#file_quo').prop('files')[0]);
        $.ajax({ url: '{{ url("edit/quotation") }}', method: 'POST', data: formData, dataType: 'JSON', contentType: false, cache: false, processData: false,
            success: function (data) {
                if (data.status) { openSuccessGritter('Berhasil', data.message); audio_ok.play(); hideLoading(); $('#modalQuotation').modal('hide'); }
                else { openErrorGritter('Error!', data.message); hideLoading(); audio_error.play(); }
            }
        });
    }

    function deleteConfirmationInv(id) { $('[name=modalButton]').attr('id', id); }

    function deletePR(id) {
        showLoading();
        $.post('{{ url("delete/tanda_terima") }}', { id: id }, function (result) {
            if (result.status) { openSuccessGritter('Berhasil', 'Data berhasil dihapus'); hideLoading(); setTimeout(function(){ window.location.reload(); }, 2000); }
            else { openErrorGritter('Error', 'Data gagal dihapus'); hideLoading(); }
        });
    }

    function getDueDate(elem) { /* diisi manual */ }

    function getSupplier(elem) {
        var isi = elem.value, list = '<option value=""></option>', name = '';
        $('#payment_term').html('');
        vendor_all.forEach(function(v) {
            if (v.vendor_code == isi) { list += '<option value="'+v.supplier_duration+'" selected>'+v.supplier_duration+'</option>'; name = v.supplier_name; }
        });
        payment_term_all.forEach(function(p) { list += '<option value="'+p.payment_term+'">'+p.payment_term+'</option>'; });
        $('#payment_term').append(list);
        $('#supplier_name').val(name);
    }

    function checkOcrServerAvailability() {
        $.ajax({ url: 'http://10.109.44.70:3001/api/health', method: 'GET', timeout: 5000,
            success: function() {
                $('#processOcrBtn').prop('disabled', false);
                $('#serverStatusMessage').removeClass('ocr-checking ocr-offline').addClass('ocr-online').html('<i class="fas fa-check"></i> OCR Online');
            },
            error: function() {
                $('#processOcrBtn').prop('disabled', true);
                $('#serverStatusMessage').removeClass('ocr-checking ocr-online').addClass('ocr-offline').html('<i class="fas fa-times"></i> OCR Offline');
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
                    $('#ocrProcessingStatus').hide(); openSuccessGritter('Info', 'OCR selesai diproses.');
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
                });
                this.on('error', function (file, response) { $('#ocrProcessingStatus').hide(); openErrorGritter('Error', response); });
            }
        });
        $('#processOcrBtn').on('click', function () {
            if (ocrDropzone.getQueuedFiles().length > 0) { $('#ocrProcessingStatus').show(); ocrDropzone.processQueue(); }
            else { openErrorGritter('Error', 'Harap upload file terlebih dahulu.'); }
        });
    });

    function openSuccessGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-success', image: '{{ url("images/image-screen.png") }}', sticky: false, time: '3000' });
    }
    function openErrorGritter(title, message) {
        jQuery.gritter.add({ title: title, text: message, class_name: 'growl-danger', image: '{{ url("images/image-stop.png") }}', sticky: false, time: '3000' });
    }
</script>
@endsection