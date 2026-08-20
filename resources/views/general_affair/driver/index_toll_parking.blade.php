@extends('layouts.master')

@section('title', 'Tol & Parkir')

@section('styles')

<link href="{{ url('css/jquery.gritter.css') }}" rel="stylesheet">
<link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">

<style>
    body {
        background: #f0f2f7 !important;
    }

    body p,
    body span:not([class*="fa"]):not([class*="glyphicon"]),
    body div,
    body label,
    body input,
    body select,
    body textarea,
    body button,
    body a,
    body td,
    body th,
    body h1,
    body h2,
    body h3,
    body h4,
    body h5,
    body h6,
    body li {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
    }


    /* ============================================================
     * LOADING
     * ============================================================ */
    #loading {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(30, 31, 58, .4);
        backdrop-filter: blur(5px);
        z-index: 30001;
        align-items: center;
        justify-content: center;
    }

    #loading.show {
        display: flex !important;
    }

    .loading-box {
        background: #fff;
        border-radius: 20px;
        padding: 36px 48px;
        display: flex;
        flex-direction: column;
        align-items: center;
        gap: 14px;
        box-shadow: 0 12px 40px rgba(0, 0, 0, .15);
    }

    .loading-spinner {
        width: 42px;
        height: 42px;
        border: 3px solid #ede9fe;
        border-top-color: #605ca8;
        border-radius: 50%;
        animation: spin .75s linear infinite;
    }

    @keyframes spin {
        to {
            transform: rotate(360deg);
        }
    }

    .loading-box p {
        font-size: 13px;
        color: #718096;
        margin: 0;
        font-weight: 600;
    }


    /* ============================================================
     * PAGE HEADER
     * ============================================================ */
    .page-header-modern {
        background: linear-gradient(
            135deg,
            #2d2b4e 0%,
            #4a4690 50%,
            #605ca8 100%
        );
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
        right: -40px;
        top: -40px;
        width: 200px;
        height: 200px;
        border-radius: 50%;
        background: rgba(255, 255, 255, .04);
        pointer-events: none;
    }

    .header-left .badge-tag {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255, 255, 255, .14);
        border: 1px solid rgba(255, 255, 255, .22);
        color: #c9c6f0;
        font-size: 11px;
        font-weight: 700;
        letter-spacing: 1.2px;
        text-transform: uppercase;
        padding: 5px 14px;
        border-radius: 20px;
        margin-bottom: 10px;
    }

    .header-left h1 {
        color: #fff !important;
        font-size: 22px !important;
        font-weight: 700 !important;
        margin: 0 0 3px !important;
        line-height: 1.2 !important;
    }

    .header-left p {
        color: rgba(255, 255, 255, .5);
        font-size: 13px;
        margin: 0;
    }

    .btn-back {
        display: inline-flex;
        align-items: center;
        gap: 8px;
        background: rgba(255, 255, 255, .15);
        color: #fff;
        border: 1.5px solid rgba(255, 255, 255, .25);
        border-radius: 10px;
        padding: 10px 20px;
        font-size: 13px;
        font-weight: 600;
        text-decoration: none;
        transition: all .2s;
        z-index: 999;
    }

    .btn-back:hover {
        background: rgba(255, 255, 255, .25);
        color: #fff;
        text-decoration: none;
    }


    /* ============================================================
     * FILTER
     * ============================================================ */
    .filter-card {
        background: #fff;
        border-radius: 16px;
        padding: 18px 20px;
        margin-bottom: 18px;
        border: 1px solid rgba(0, 0, 0, .05);
        box-shadow: 0 2px 12px rgba(0, 0, 0, .05);
    }

    .filter-title {
        display: flex;
        align-items: center;
        gap: 8px;
        font-size: 13px;
        font-weight: 700;
        color: #2d3748;
        margin-bottom: 14px;
    }

    .filter-title i {
        color: #605ca8;
    }

    .filter-row {
        display: flex;
        align-items: flex-end;
        gap: 12px;
        flex-wrap: wrap;
    }

    .filter-group {
        flex: 1;
        min-width: 180px;
    }

    .filter-group label {
        display: block;
        margin-bottom: 6px;
        font-size: 10px;
        text-transform: uppercase;
        letter-spacing: .6px;
        font-weight: 700;
        color: #a0aec0;
    }

    .filter-control {
        width: 100%;
        height: 40px;
        border: 1px solid #e2e8f0;
        border-radius: 9px;
        padding: 0 12px;
        color: #2d3748;
        font-size: 12px;
        outline: none;
        background: #fff;
        transition: all .2s;
    }

    .filter-control:focus {
        border-color: #918bd0;
        box-shadow: 0 0 0 3px rgba(96, 92, 168, .10);
    }

    .filter-action {
        display: flex;
        gap: 8px;
    }

    .btn-filter,
    .btn-reset {
        height: 40px;
        border-radius: 9px;
        border: none;
        padding: 0 18px;
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 7px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        transition: all .2s;
        white-space: nowrap;
    }

    .btn-filter {
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff;
        box-shadow: 0 3px 10px rgba(96, 92, 168, .25);
    }

    .btn-filter:hover {
        opacity: .9;
        transform: translateY(-1px);
    }

    .btn-reset {
        background: #f4f5f9;
        color: #718096;
        border: 1px solid #e2e8f0;
    }

    .btn-reset:hover {
        background: #ebedf3;
    }


    /* ============================================================
     * TABLE CARD
     * ============================================================ */
    .table-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0, 0, 0, .06);
        border: 1px solid rgba(0, 0, 0, .05);
        overflow: hidden;
        margin-bottom: 32px;
    }

    .table-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 12px;
    }

    .table-card-title {
        font-size: 14px;
        font-weight: 700;
        color: #1a202c;
        display: flex;
        align-items: center;
        gap: 10px;
    }

    .table-card-title .dot {
        width: 8px;
        height: 8px;
        background: #605ca8;
        border-radius: 50%;
    }

    .table-count {
        font-size: 12px;
        color: #a0aec0;
        font-weight: 500;
    }


    /* ============================================================
     * TABS
     * ============================================================ */
    .tp-tabs {
        display: flex;
        gap: 6px;
        padding: 14px 18px 0;
        background: #fff;
        border-bottom: 1px solid #edf0f5;
    }

    .tp-tab {
        position: relative;
        border: none;
        background: transparent;
        color: #a0aec0;
        padding: 10px 16px 13px;
        font-size: 12px;
        font-weight: 700;
        cursor: pointer;
        outline: none !important;
        display: inline-flex;
        align-items: center;
        gap: 7px;
        transition: all .2s;
    }

    .tp-tab:hover {
        color: #605ca8;
    }

    .tp-tab.active {
        color: #4a4690;
    }

    .tp-tab.active::after {
        content: '';
        position: absolute;
        bottom: -1px;
        left: 10px;
        right: 10px;
        height: 3px;
        border-radius: 3px 3px 0 0;
        background: #605ca8;
    }

    .tab-badge {
        min-width: 20px;
        height: 20px;
        padding: 0 6px;
        border-radius: 20px;
        background: #f0f2f7;
        color: #718096;
        font-size: 10px;
        font-weight: 700;
        display: inline-flex;
        justify-content: center;
        align-items: center;
    }

    .tp-tab.active .tab-badge {
        background: #edeafd;
        color: #605ca8;
    }

    .tab-pane-custom {
        display: none;
    }

    .tab-pane-custom.active {
        display: block;
    }


    /* ============================================================
     * DESKTOP TABLE
     * ============================================================ */
    .desktop-table-wrapper {
        overflow-x: auto;
    }

    .tp-table {
        width: 100%;
        border-collapse: collapse;
    }

    .tp-table thead th {
        background: #f7f8fc;
        color: #718096;
        font-size: 10px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        padding: 12px 14px;
        border-bottom: 2px solid #edf0f5;
        white-space: nowrap;
        text-align: left;
    }

    .tp-table tbody tr {
        transition: background .15s;
    }

    .tp-table tbody tr:hover td {
        background: #f5f8ff !important;
    }

    .tp-table tbody td {
        padding: 13px 14px;
        font-size: 12px;
        color: #2d3748;
        border-bottom: 1px solid #f0f2f7;
        vertical-align: middle;
    }

    .tp-table tbody tr:last-child td {
        border-bottom: none;
    }


    /* ============================================================
     * COMMON FIELD
     * ============================================================ */
    .date-chip {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        background: #f0f5ff;
        color: #2d6bc4;
        padding: 4px 10px;
        border-radius: 6px;
        font-size: 12px;
        font-weight: 600;
        white-space: nowrap;
    }

    .time-range {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        font-size: 12.5px;
        color: #4a5568;
        font-weight: 500;
        white-space: nowrap;
    }

    .time-sep {
        color: #c4bfef;
    }

    .user-cell {
        display: flex;
        align-items: center;
        gap: 9px;
    }

    .user-avatar {
        width: 30px;
        height: 30px;
        border-radius: 8px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 11px;
        font-weight: 700;
        color: #fff;
        flex-shrink: 0;
    }

    .user-name {
        font-weight: 600;
        color: #1a202c;
    }

    .car-plat {
        font-weight: 700;
        color: #1a202c;
        font-family: monospace;
        font-size: 13px;
    }

    .car-name {
        font-size: 11.5px;
        color: #a0aec0;
        margin-top: 2px;
    }

    .btn-isi {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 6px;
        padding: 7px 16px;
        border: none;
        border-radius: 8px;
        background: linear-gradient(135deg, #4a4690, #605ca8);
        color: #fff !important;
        font-size: 12px;
        font-weight: 700;
        text-decoration: none !important;
        transition: all .18s;
        box-shadow: 0 3px 10px rgba(96, 92, 168, .3);
        white-space: nowrap;
    }

    .btn-isi:hover {
        opacity: .88;
        transform: translateY(-1px);
        color: #fff;
        text-decoration: none;
    }

    .state-row td {
        text-align: center;
        padding: 48px 24px !important;
        color: #a0aec0;
    }

    .state-row td i {
        font-size: 32px;
        display: block;
        margin-bottom: 10px;
    }

    .empty-mobile {
        padding: 44px 20px;
        text-align: center;
        color: #a0aec0;
        font-size: 12px;
    }

    .empty-mobile i {
        font-size: 30px;
        display: block;
        margin-bottom: 10px;
    }


    /* ============================================================
     * CLOSED FIELDS
     * ============================================================ */
    .money-value {
        font-weight: 700;
        color: #2d3748;
        white-space: nowrap;
    }

    .status-filled {
        display: inline-flex;
        align-items: center;
        gap: 5px;
        padding: 4px 8px;
        border-radius: 20px;
        font-size: 10px;
        font-weight: 700;
        background: #ecfdf3;
        color: #278557;
        white-space: nowrap;
    }

    .status-empty {
        color: #cbd5e0;
        font-size: 11px;
    }

    .file-preview {
        display: inline-flex;
        align-items: center;
        gap: 6px;
    }

    .file-thumb {
        width: 42px;
        height: 42px;
        object-fit: cover;
        border-radius: 7px;
        border: 1px solid #e2e8f0;
        background: #f7fafc;
        cursor: pointer;
    }

    .btn-file {
        display: inline-flex;
        align-items: center;
        justify-content: center;
        gap: 5px;
        padding: 5px 8px;
        border-radius: 6px;
        background: #f0f5ff;
        color: #2d6bc4 !important;
        font-size: 10px;
        font-weight: 700;
        text-decoration: none !important;
        white-space: nowrap;
    }

    .btn-file:hover {
        background: #e5edff;
    }


    /* ============================================================
     * MOBILE ACCORDION
     * ============================================================ */
    .mobile-accordion-wrapper {
        display: none;
        padding: 12px;
        background: #f7f8fc;
    }

    .task-accordion {
        background: #fff;
        border: 1px solid #e5e9f0;
        border-radius: 12px;
        margin-bottom: 10px;
        overflow: hidden;
        box-shadow: 0 1px 5px rgba(0, 0, 0, .04);
    }

    .task-accordion:last-child {
        margin-bottom: 0;
    }

    .task-accordion-header {
        width: 100%;
        border: none;
        background: #fff;
        display: flex;
        align-items: center;
        justify-content: space-between;
        gap: 10px;
        text-align: left;
        padding: 13px 14px;
        cursor: pointer;
        outline: none !important;
    }

    .task-accordion-header-left {
        min-width: 0;
        flex: 1;
    }

    .accordion-date {
        display: flex;
        align-items: center;
        gap: 7px;
        margin-bottom: 5px;
        font-size: 12px;
        font-weight: 700;
        color: #2d3748;
    }

    .accordion-date i {
        color: #605ca8;
        font-size: 11px;
    }

    .accordion-subtitle {
        display: flex;
        align-items: center;
        gap: 6px;
        color: #a0aec0;
        font-size: 10.5px;
        font-weight: 600;
        overflow: hidden;
    }

    .accordion-subtitle span {
        overflow: hidden;
        text-overflow: ellipsis;
        white-space: nowrap;
    }

    .accordion-toggle-icon {
        width: 28px;
        height: 28px;
        flex-shrink: 0;
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #f4f2ff;
        color: #605ca8;
        transition: transform .2s, background .2s;
    }

    .task-accordion.open .accordion-toggle-icon {
        transform: rotate(180deg);
        background: #605ca8;
        color: #fff;
    }

    .task-accordion-body {
        display: none;
        border-top: 1px solid #f0f2f7;
        padding: 4px 14px 14px;
    }

    .task-accordion.open .task-accordion-body {
        display: block;
    }

    .mobile-field {
        padding: 10px 0;
        border-bottom: 1px solid #f0f2f7;
    }

    .mobile-field:last-child {
        border-bottom: none;
    }

    .mobile-label {
        display: block;
        color: #a0aec0;
        font-size: 9px;
        font-weight: 700;
        letter-spacing: .6px;
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .mobile-value {
        color: #2d3748;
        font-size: 12px;
        font-weight: 500;
        word-break: break-word;
    }

    .mobile-value .user-cell {
        margin-top: 2px;
    }

    .closed-detail-grid {
        display: grid;
        grid-template-columns: repeat(2, minmax(0, 1fr));
        gap: 8px;
        margin-top: 8px;
    }

    .closed-detail-box {
        border: 1px solid #edf0f5;
        background: #fafbff;
        border-radius: 9px;
        padding: 10px;
        min-width: 0;
    }

    .closed-detail-box.full {
        grid-column: 1 / -1;
    }

    .closed-detail-label {
        color: #a0aec0;
        font-size: 8.5px;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: .5px;
        margin-bottom: 5px;
    }

    .closed-detail-value {
        font-size: 11px;
        color: #2d3748;
        font-weight: 600;
        word-break: break-word;
    }


    /* ============================================================
     * IMAGE MODAL
     * ============================================================ */
    .image-modal-custom {
        display: none;
        position: fixed;
        inset: 0;
        background: rgba(17, 24, 39, .85);
        z-index: 40000;
        padding: 20px;
        align-items: center;
        justify-content: center;
    }

    .image-modal-custom.show {
        display: flex;
    }

    .image-modal-box {
        max-width: 900px;
        width: 100%;
        position: relative;
        display: flex;
        justify-content: center;
    }

    .image-modal-box img {
        max-width: 100%;
        max-height: 85vh;
        object-fit: contain;
        border-radius: 12px;
        background: #fff;
    }

    .image-modal-close {
        position: absolute;
        right: -5px;
        top: -45px;
        width: 34px;
        height: 34px;
        border-radius: 50%;
        border: none;
        background: rgba(255, 255, 255, .16);
        color: #fff;
        cursor: pointer;
        font-size: 17px;
    }


    /* ============================================================
     * RESPONSIVE
     * ============================================================ */
    @media (max-width: 768px) {
        .content-header {
            padding: 0 10px !important;
        }

        .page-header-modern {
            flex-direction: column;
            align-items: flex-start;
            padding: 20px 16px;
            margin-top: 14px;
            margin-bottom: 14px;
        }

        .btn-back {
            align-self: flex-start;
            padding: 8px 16px;
            font-size: 12px;
        }

        .header-left h1 {
            font-size: 20px !important;
        }

        .filter-card {
            padding: 14px;
            border-radius: 13px;
        }

        .filter-row {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 10px;
        }

        .filter-group {
            min-width: 0;
        }

        .filter-action {
            grid-column: 1 / -1;
            display: grid;
            grid-template-columns: 1fr 1fr;
        }

        .btn-filter,
        .btn-reset {
            width: 100%;
        }

        .table-card {
            border-radius: 14px;
        }

        .table-card-header {
            padding: 13px 14px;
        }

        .tp-tabs {
            padding: 10px 10px 0;
            overflow-x: auto;
        }

        .tp-tab {
            flex: 1;
            min-width: max-content;
            justify-content: center;
        }

        .desktop-table-wrapper {
            display: none;
        }

        .mobile-accordion-wrapper {
            display: block;
        }

        .closed-detail-grid {
            grid-template-columns: repeat(2, minmax(0, 1fr));
        }

        .btn-isi {
            width: 100%;
            padding: 10px 12px;
        }
    }

    @media (max-width: 480px) {
        .page-header-modern {
            padding: 16px 12px;
        }

        .header-left h1 {
            font-size: 18px !important;
        }

        .header-left p {
            font-size: 12px;
        }

        .filter-row {
            grid-template-columns: 1fr;
        }

        .filter-action {
            grid-column: auto;
        }

        .filter-control {
            height: 39px;
        }

        .table-card-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 6px;
        }

        .table-card-title {
            font-size: 13px;
        }

        .table-count {
            font-size: 11px;
        }

        .tp-tab {
            font-size: 11px;
            padding-left: 10px;
            padding-right: 10px;
        }

        .mobile-accordion-wrapper {
            padding: 9px;
        }

        .task-accordion-header {
            padding: 12px;
        }

        .task-accordion-body {
            padding: 4px 12px 12px;
        }

        .closed-detail-grid {
            grid-template-columns: 1fr;
        }

        .closed-detail-box.full {
            grid-column: auto;
        }
    }
</style>

@endsection


@section('content')

{{-- ============================================================
    LOADING
============================================================ --}}
<div id="loading">
    <div class="loading-box">
        <div class="loading-spinner"></div>
        <p>Memuat data...</p>
    </div>
</div>


{{-- ============================================================
    IMAGE MODAL
============================================================ --}}
<div class="image-modal-custom" id="image-modal">
    <div class="image-modal-box">
        <button type="button" class="image-modal-close" onclick="closeImageModal()">
            <i class="fas fa-times"></i>
        </button>

        <img id="image-modal-target" src="" alt="Preview">
    </div>
</div>


<div class="content-header" style="padding: 0 20px;">

    {{-- ========================================================
        PAGE HEADER
    ========================================================= --}}
    <div class="page-header-modern">

        <div class="header-left">
            <div class="badge-tag">
                <i class="fas fa-car"></i>
                Driver
            </div>

            <h1>{{ $title }}</h1>
            <p>{{ $title_jp }}</p>
        </div>

        <a href="{{ url('index/driver') }}" class="btn-back">
            <i class="fas fa-arrow-left"></i>
            Kembali
        </a>

    </div>


    {{-- ========================================================
        FILTER TANGGAL
    ========================================================= --}}
    <div class="filter-card">

        <div class="filter-title">
            <i class="fas fa-filter"></i>
            Filter Tanggal
        </div>

        <div class="filter-row">

            <div class="filter-group">
                <label for="filter_date_from">Dari Tanggal</label>

                <input
                    type="date"
                    id="filter_date_from"
                    class="filter-control"
                >
            </div>


            <div class="filter-group">
                <label for="filter_date_to">Sampai Tanggal</label>

                <input
                    type="date"
                    id="filter_date_to"
                    class="filter-control"
                >
            </div>


            <div class="filter-action">

                <button
                    type="button"
                    class="btn-filter"
                    id="btn-filter"
                >
                    <i class="fas fa-search"></i>
                    Tampilkan
                </button>

                <button
                    type="button"
                    class="btn-reset"
                    id="btn-reset"
                >
                    <i class="fas fa-undo"></i>
                    Reset
                </button>

            </div>

        </div>

    </div>


    {{-- ========================================================
        DATA CARD
    ========================================================= --}}
    <div class="table-card">

        <div class="table-card-header">

            <div class="table-card-title">
                <span class="dot"></span>
                Daftar Penugasan Tol & Parkir
            </div>

            <span
                class="table-count"
                id="row-count"
            >
                Memuat...
            </span>

        </div>


        {{-- ====================================================
            TABS
        ===================================================== --}}
        <div class="tp-tabs">

            <button
                type="button"
                class="tp-tab active"
                data-tab="outstanding"
            >
                <i class="fas fa-hourglass-half"></i>

                Outstanding

                <span
                    class="tab-badge"
                    id="badge-outstanding"
                >
                    0
                </span>
            </button>


            <button
                type="button"
                class="tp-tab"
                data-tab="closed"
            >
                <i class="fas fa-check-circle"></i>

                Closed

                <span
                    class="tab-badge"
                    id="badge-closed"
                >
                    0
                </span>
            </button>

        </div>


        {{-- ====================================================
            OUTSTANDING
        ===================================================== --}}
        <div
            class="tab-pane-custom active"
            id="tab-outstanding"
        >

            {{-- DESKTOP --}}
            <div class="desktop-table-wrapper">

                <table class="tp-table">

                    <thead>
                        <tr>
                            <th style="width: 46px;">#</th>
                            <th>Tanggal</th>
                            <th>Jam</th>
                            <th>User</th>
                            <th>Kendaraan</th>
                            <th style="width: 160px;">Tujuan</th>
                            <th style="width: 130px;">Aksi</th>
                        </tr>
                    </thead>

                    <tbody id="outstanding-table">
                        <tr class="state-row">
                            <td colspan="7">
                                <i class="fas fa-spinner fa-spin"></i>
                                Memuat data...
                            </td>
                        </tr>
                    </tbody>

                </table>

            </div>


            {{-- MOBILE --}}
            <div
                class="mobile-accordion-wrapper"
                id="outstanding-mobile"
            >
                <div class="empty-mobile">
                    <i class="fas fa-spinner fa-spin"></i>
                    Memuat data...
                </div>
            </div>

        </div>


        {{-- ====================================================
            CLOSED
        ===================================================== --}}
        <div
            class="tab-pane-custom"
            id="tab-closed"
        >

            {{-- DESKTOP --}}
            <div class="desktop-table-wrapper">

                <table class="tp-table">

                    <thead>

                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>User</th>
                            <th>Kendaraan</th>
                            <th>Tujuan</th>

                            <th>E-Toll</th>
                            <th>E-Toll From</th>
                            <th>E-Toll To</th>
                            <th>E-Toll File</th>

                            <th>Parking</th>
                            <th>Parking At</th>
                            <th>Parking File</th>

                            <th>Parking E-Money</th>
                            <th>Parking At E-Money</th>
                            <th>Parking File E-Money</th>
                        </tr>

                    </thead>

                    <tbody id="closed-table">

                        <tr class="state-row">
                            <td colspan="15">
                                <i class="fas fa-spinner fa-spin"></i>
                                Memuat data...
                            </td>
                        </tr>

                    </tbody>

                </table>

            </div>


            {{-- MOBILE --}}
            <div
                class="mobile-accordion-wrapper"
                id="closed-mobile"
            >
                <div class="empty-mobile">
                    <i class="fas fa-spinner fa-spin"></i>
                    Memuat data...
                </div>
            </div>

        </div>

    </div>

</div>

@endsection


@section('scripts')

<script src="{{ url('js/jquery.numpad.js') }}"></script>
<script src="{{ url('js/jquery.gritter.min.js') }}"></script>

<script>
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });


    var audio_error = new Audio('{{ url("sounds/error.mp3") }}');


    var months = [
        'Jan',
        'Feb',
        'Mar',
        'Apr',
        'May',
        'Jun',
        'Jul',
        'Aug',
        'Sep',
        'Oct',
        'Nov',
        'Dec'
    ];


    /*
     * Path:
     * public/images/driver/japanese/additional
     */
    var additionalFileBaseUrl =
        'https://new.bridgeforvendor.com/images/driver/japanese/additional';


    /*
     * Menyimpan data agar count dapat berubah
     * mengikuti tab yang sedang aktif.
     */
    var outstandingData = [];
    var closedData = [];
    var currentTab = 'outstanding';


    $(document).ready(function () {

        $('body').toggleClass('sidebar-collapse');

        $('#side_driver').addClass('menu-open');


        /*
         * Tab click
         */
        $('.tp-tab').on('click', function () {

            var tab = $(this).data('tab');

            switchTab(tab);

        });


        /*
         * Filter
         */
        $('#btn-filter').on('click', function () {

            var dateFrom = $('#filter_date_from').val();
            var dateTo   = $('#filter_date_to').val();


            if (
                dateFrom !== '' &&
                dateTo !== '' &&
                dateFrom > dateTo
            ) {

                openErrorGritter(
                    'Filter tidak valid',
                    'Tanggal awal tidak boleh lebih besar dari tanggal akhir.'
                );

                return;
            }


            fetchData();

        });


        /*
         * Reset filter
         */
        $('#btn-reset').on('click', function () {

            $('#filter_date_from').val('');
            $('#filter_date_to').val('');

            fetchData();

        });


        /*
         * Tutup modal ketika klik background
         */
        $('#image-modal').on('click', function (e) {

            if (e.target.id === 'image-modal') {
                closeImageModal();
            }

        });


        /*
         * Escape untuk tutup modal
         */
        $(document).on('keyup', function (e) {

            if (e.key === 'Escape') {
                closeImageModal();
            }

        });


        fetchData();

    });


    /* ============================================================
     * FETCH DATA
     * ============================================================ */
    function fetchData() {

        $('#loading').addClass('show');


        var params = {
            driver_id: '{{ $driver_id }}',
            date_from: $('#filter_date_from').val(),
            date_to: $('#filter_date_to').val()
        };


        $.get(
            '{{ url("fetch/driver/toll_parking") }}',
            params,
            function (result) {

                $('#loading').removeClass('show');


                if (result.status) {

                    outstandingData =
                        Array.isArray(result.data)
                            ? result.data
                            : [];


                    closedData =
                        Array.isArray(result.data_closed)
                            ? result.data_closed
                            : [];


                    /*
                     * Update badge
                     */
                    $('#badge-outstanding').text(
                        outstandingData.length
                    );

                    $('#badge-closed').text(
                        closedData.length
                    );


                    /*
                     * Render data
                     */
                    renderOutstanding(outstandingData);

                    renderClosed(closedData);


                    /*
                     * Update count sesuai active tab.
                     */
                    updateCurrentCount();

                } else {

                    outstandingData = [];
                    closedData = [];


                    $('#badge-outstanding').text('0');
                    $('#badge-closed').text('0');


                    renderRequestError(
                        result.message || 'Gagal memuat data.'
                    );


                    updateCurrentCount();


                    openErrorGritter(
                        'Error!',
                        result.message || 'Gagal memuat data.'
                    );

                }

            }
        )
        .fail(function (xhr) {

            $('#loading').removeClass('show');

            outstandingData = [];
            closedData = [];


            $('#badge-outstanding').text('0');
            $('#badge-closed').text('0');


            renderRequestError(
                'Terjadi kesalahan saat mengambil data.'
            );


            updateCurrentCount();


            openErrorGritter(
                'Error!',
                'Terjadi kesalahan saat mengambil data.'
            );

        });

    }


    /* ============================================================
     * TAB
     * ============================================================ */
    function switchTab(tab) {

        currentTab = tab;


        $('.tp-tab').removeClass('active');

        $('.tp-tab[data-tab="' + tab + '"]')
            .addClass('active');


        $('.tab-pane-custom').removeClass('active');

        $('#tab-' + tab).addClass('active');


        updateCurrentCount();

    }


    function updateCurrentCount() {

        if (currentTab === 'closed') {

            $('#row-count').text(
                closedData.length + ' data'
            );

        } else {

            $('#row-count').text(
                outstandingData.length + ' data'
            );

        }

    }


    /* ============================================================
     * OUTSTANDING
     * ============================================================ */
    function renderOutstanding(data) {

        renderOutstandingDesktop(data);

        renderOutstandingMobile(data);

    }


    /*
     * Desktop Outstanding
     */
    function renderOutstandingDesktop(data) {

        if (!data.length) {

            $('#outstanding-table').html(
                '<tr class="state-row">' +
                    '<td colspan="7">' +
                        '<i class="fas fa-inbox"></i>' +
                        'Tidak ada data outstanding.' +
                    '</td>' +
                '</tr>'
            );

            return;

        }


        var html = '';


        data.forEach(function (item, i) {

            var display = getTaskDisplayData(item);

            var actionUrl = getActionUrl(item);


            html += '<tr>';


            html +=
                '<td style="' +
                    'text-align:center;' +
                    'color:#a0aec0;' +
                    'font-size:12px;' +
                    'font-weight:600;' +
                '">' +
                    (i + 1) +
                '</td>';


            html +=
                '<td>' +
                    '<span class="date-chip">' +
                        '<i class="fas fa-calendar-alt"></i> ' +
                        escapeHtml(display.date) +
                    '</span>' +
                '</td>';


            html +=
                '<td>' +
                    '<div class="time-range">' +
                        '<i class="fas fa-clock" ' +
                            'style="' +
                                'color:#c4bfef;' +
                                'font-size:12px;' +
                            '">' +
                        '</i>' +

                        escapeHtml(display.timeFrom) +

                        '<span class="time-sep">→</span>' +

                        escapeHtml(display.timeTo) +
                    '</div>' +
                '</td>';


            html +=
                '<td>' +
                    renderUserCell(
                        item.created_by_name
                    ) +
                '</td>';


            html +=
                '<td>' +
                    renderCarCell(
                        item.plat_no,
                        item.car
                    ) +
                '</td>';


            html +=
                '<td>' +
                    escapeHtml(
                        safeValue(item.destination)
                    ) +
                '</td>';


            html +=
                '<td>' +
                    '<a class="btn-isi" ' +
                        'href="' + escapeAttribute(actionUrl) + '">' +
                        '<i class="fas fa-pen"></i>' +
                        ' Isi Data' +
                    '</a>' +
                '</td>';


            html += '</tr>';

        });


        $('#outstanding-table').html(html);

    }


    /*
     * Mobile Outstanding Accordion
     */
    function renderOutstandingMobile(data) {

        if (!data.length) {

            $('#outstanding-mobile').html(
                '<div class="empty-mobile">' +
                    '<i class="fas fa-inbox"></i>' +
                    'Tidak ada data outstanding.' +
                '</div>'
            );

            return;

        }


        var html = '';


        data.forEach(function (item, i) {

            var display = getTaskDisplayData(item);

            var actionUrl = getActionUrl(item);

            var accordionId =
                'outstanding-accordion-' + i;


            html +=
                '<div class="task-accordion" ' +
                    'id="' + accordionId + '">' +


                    /*
                     * Header
                     */
                    '<button ' +
                        'type="button" ' +
                        'class="task-accordion-header" ' +
                        'onclick="' +
                            "toggleAccordion('" + accordionId + "')" +
                        '"' +
                    '>' +

                        '<div class="task-accordion-header-left">' +

                            '<div class="accordion-date">' +
                                '<i class="fas fa-calendar-alt"></i>' +
                                '<span>' +
                                    escapeHtml(display.date) +
                                '</span>' +
                            '</div>' +

                            '<div class="accordion-subtitle">' +
                                '<i class="fas fa-car"></i>' +
                                '<span>' +
                                    escapeHtml(
                                        safeValue(item.plat_no, '-')
                                    ) +
                                    ' · ' +

                                    escapeHtml(
                                        safeValue(
                                            display.timeFrom,
                                            '-'
                                        )
                                    ) + ' · ' +

                                    escapeHtml(
                                        safeValue(
                                            display.timeTo,
                                            '-'
                                        )
                                    ) +
                                    ' · ' +
                                    escapeHtml(
                                        safeValue(
                                            item.destination,
                                            'Daily'
                                        )
                                    ) +
                                '</span>' +
                            '</div>' +

                        '</div>' +

                        '<div class="accordion-toggle-icon">' +
                            '<i class="fas fa-chevron-down"></i>' +
                        '</div>' +

                    '</button>' +


                    /*
                     * Body
                     */
                    '<div class="task-accordion-body">' +


                        renderMobileField(
                            'Tanggal',
                            '<span class="date-chip">' +
                                '<i class="fas fa-calendar-alt"></i> ' +
                                escapeHtml(display.date) +
                            '</span>'
                        ) +


                        renderMobileField(
                            'Jam',
                            '<div class="time-range">' +
                                '<i class="fas fa-clock" ' +
                                    'style="color:#c4bfef;"></i>' +
                                escapeHtml(display.timeFrom) +
                                '<span class="time-sep">→</span>' +
                                escapeHtml(display.timeTo) +
                            '</div>'
                        ) +


                        renderMobileField(
                            'User',
                            renderUserCell(
                                item.created_by_name
                            )
                        ) +


                        renderMobileField(
                            'Kendaraan',
                            renderCarCell(
                                item.plat_no,
                                item.car
                            )
                        ) +


                        renderMobileField(
                            'Tujuan',
                            escapeHtml(
                                safeValue(
                                    item.destination,
                                    '-'
                                )
                            )
                        ) +


                        renderMobileField(
                            'Aksi',
                            '<a class="btn-isi" ' +
                                'href="' +
                                    escapeAttribute(actionUrl) +
                                '">' +
                                '<i class="fas fa-pen"></i>' +
                                ' Isi Data' +
                            '</a>'
                        ) +


                    '</div>' +

                '</div>';

        });


        $('#outstanding-mobile').html(html);

    }


    /* ============================================================
     * CLOSED
     * ============================================================ */
    function renderClosed(data) {

        renderClosedDesktop(data);

        renderClosedMobile(data);

    }


    /*
     * Desktop Closed
     */
    function renderClosedDesktop(data) {

        if (!data.length) {

            $('#closed-table').html(
                '<tr class="state-row">' +
                    '<td colspan="15">' +
                        '<i class="fas fa-check-circle"></i>' +
                        'Tidak ada data closed.' +
                    '</td>' +
                '</tr>'
            );

            return;

        }


        var html = '';


        data.forEach(function (item, i) {

            var display = getTaskDisplayData(item);


            html += '<tr>';


            html +=
                '<td style="' +
                    'text-align:center;' +
                    'color:#a0aec0;' +
                    'font-size:12px;' +
                    'font-weight:600;' +
                '">' +
                    (i + 1) +
                '</td>';


            html +=
                '<td>' +
                    '<span class="date-chip">' +
                        '<i class="fas fa-calendar-alt"></i> ' +
                        escapeHtml(display.date) +
                    '</span>' +

                    '<div style="' +
                        'margin-top:5px;' +
                        'font-size:10px;' +
                        'color:#a0aec0;' +
                        'white-space:nowrap;' +
                    '">' +
                        escapeHtml(display.timeFrom) +
                        ' - ' +
                        escapeHtml(display.timeTo) +
                    '</div>' +
                '</td>';


            html +=
                '<td>' +
                    renderUserCell(
                        item.created_by_name
                    ) +
                '</td>';


            html +=
                '<td>' +
                    renderCarCell(
                        item.plat_no,
                        item.car
                    ) +
                '</td>';


            html +=
                '<td>' +
                    escapeHtml(
                        safeValue(
                            item.destination,
                            '-'
                        )
                    ) +
                '</td>';


            /*
             * E-Toll
             */
            html +=
                '<td>' +
                    renderMoney(item.etoll) +
                '</td>';


            html +=
                '<td>' +
                    escapeHtml(
                        safeValue(item.etoll_from, '-')
                    ) +
                '</td>';


            html +=
                '<td>' +
                    escapeHtml(
                        safeValue(item.etoll_to, '-')
                    ) +
                '</td>';


            html +=
                '<td>' +
                    renderFile(item.etoll_file) +
                '</td>';


            /*
             * Parking
             */
            html +=
                '<td>' +
                    renderMoney(item.parking) +
                '</td>';


            html +=
                '<td>' +
                    formatDateTimeValue(item.parking_at) +
                '</td>';


            html +=
                '<td>' +
                    renderFile(item.parking_file) +
                '</td>';


            /*
             * Parking e-money
             */
            html +=
                '<td>' +
                    renderMoney(item.parking_emoney) +
                '</td>';


            html +=
                '<td>' +
                    formatDateTimeValue(
                        item.parking_at_emoney
                    ) +
                '</td>';


            html +=
                '<td>' +
                    renderFile(
                        item.parking_file_emoney
                    ) +
                '</td>';


            html += '</tr>';

        });


        $('#closed-table').html(html);

    }


    /*
     * Mobile Closed Accordion
     */
    function renderClosedMobile(data) {

        if (!data.length) {

            $('#closed-mobile').html(
                '<div class="empty-mobile">' +
                    '<i class="fas fa-check-circle"></i>' +
                    'Tidak ada data closed.' +
                '</div>'
            );

            return;

        }


        var html = '';


        data.forEach(function (item, i) {

            var display = getTaskDisplayData(item);

            var accordionId =
                'closed-accordion-' + i;


            html +=
                '<div class="task-accordion" ' +
                    'id="' + accordionId + '">' +


                    /*
                     * Header Accordion
                     */
                    '<button ' +
                        'type="button" ' +
                        'class="task-accordion-header" ' +
                        'onclick="' +
                            "toggleAccordion('" + accordionId + "')" +
                        '"' +
                    '>' +

                        '<div class="task-accordion-header-left">' +

                            '<div class="accordion-date">' +
                                '<i class="fas fa-check-circle"></i>' +
                                '<span>' +
                                    escapeHtml(display.date) +
                                '</span>' +
                            '</div>' +

                            '<div class="accordion-subtitle">' +
                                '<i class="fas fa-car"></i>' +

                                '<span>' +
                                    escapeHtml(
                                        safeValue(
                                            item.plat_no,
                                            '-'
                                        )
                                    ) +

                                    ' · ' +

                                    escapeHtml(
                                        safeValue(
                                            display.timeFrom,
                                            '-'
                                        )
                                    ) + ' · ' +

                                    escapeHtml(
                                        safeValue(
                                            display.timeTo,
                                            '-'
                                        )
                                    ) +

                                    ' · ' +

                                    escapeHtml(
                                        safeValue(
                                            item.destination,
                                            'Daily'
                                        )
                                    ) +
                                '</span>' +
                            '</div>' +

                        '</div>' +


                        '<div class="accordion-toggle-icon">' +
                            '<i class="fas fa-chevron-down"></i>' +
                        '</div>' +

                    '</button>' +


                    /*
                     * Body
                     */
                    '<div class="task-accordion-body">' +


                        renderMobileField(
                            'Tanggal',
                            '<span class="date-chip">' +
                                '<i class="fas fa-calendar-alt"></i> ' +
                                escapeHtml(display.date) +
                            '</span>'
                        ) +


                        renderMobileField(
                            'Jam',
                            '<div class="time-range">' +
                                '<i class="fas fa-clock" ' +
                                    'style="color:#c4bfef;"></i>' +
                                escapeHtml(display.timeFrom) +
                                '<span class="time-sep">→</span>' +
                                escapeHtml(display.timeTo) +
                            '</div>'
                        ) +


                        renderMobileField(
                            'User',
                            renderUserCell(
                                item.created_by_name
                            )
                        ) +


                        renderMobileField(
                            'Kendaraan',
                            renderCarCell(
                                item.plat_no,
                                item.car
                            )
                        ) +


                        renderMobileField(
                            'Tujuan',
                            escapeHtml(
                                safeValue(
                                    item.destination,
                                    '-'
                                )
                            )
                        ) +


                        /*
                         * CLOSED DETAIL
                         */
                        '<div class="mobile-field">' +

                            '<span class="mobile-label">' +
                                'Data Tol & Parkir' +
                            '</span>' +


                            '<div class="closed-detail-grid">' +


                                /*
                                 * E-TOLL
                                 */
                                renderClosedBox(
                                    'E-Toll',
                                    renderMoney(
                                        item.etoll
                                    )
                                ) +


                                renderClosedBox(
                                    'E-Toll From',
                                    escapeHtml(
                                        safeValue(
                                            item.etoll_from,
                                            '-'
                                        )
                                    )
                                ) +


                                renderClosedBox(
                                    'E-Toll To',
                                    escapeHtml(
                                        safeValue(
                                            item.etoll_to,
                                            '-'
                                        )
                                    )
                                ) +


                                renderClosedBox(
                                    'E-Toll File',
                                    renderFile(
                                        item.etoll_file
                                    ),
                                    true
                                ) +


                                /*
                                 * PARKING
                                 */
                                renderClosedBox(
                                    'Parking',
                                    renderMoney(
                                        item.parking
                                    )
                                ) +


                                renderClosedBox(
                                    'Parking At',
                                    formatDateTimeValue(
                                        item.parking_at
                                    )
                                ) +


                                renderClosedBox(
                                    'Parking File',
                                    renderFile(
                                        item.parking_file
                                    ),
                                    true
                                ) +


                                /*
                                 * PARKING E-MONEY
                                 */
                                renderClosedBox(
                                    'Parking E-Money',
                                    renderMoney(
                                        item.parking_emoney
                                    )
                                ) +


                                renderClosedBox(
                                    'Parking At E-Money',
                                    formatDateTimeValue(
                                        item.parking_at_emoney
                                    )
                                ) +


                                renderClosedBox(
                                    'Parking File E-Money',
                                    renderFile(
                                        item.parking_file_emoney
                                    ),
                                    true
                                ) +


                            '</div>' +

                        '</div>' +


                    '</div>' +

                '</div>';

        });


        $('#closed-mobile').html(html);

    }


    /* ============================================================
     * REQUEST ERROR
     * ============================================================ */
    function renderRequestError(message) {

        var safeMessage = escapeHtml(
            safeValue(
                message,
                'Gagal memuat data.'
            )
        );


        $('#outstanding-table').html(
            '<tr class="state-row">' +
                '<td colspan="7">' +
                    '<i class="fas fa-exclamation-circle" ' +
                        'style="color:#fca5a5;"></i>' +
                    safeMessage +
                '</td>' +
            '</tr>'
        );


        $('#closed-table').html(
            '<tr class="state-row">' +
                '<td colspan="15">' +
                    '<i class="fas fa-exclamation-circle" ' +
                        'style="color:#fca5a5;"></i>' +
                    safeMessage +
                '</td>' +
            '</tr>'
        );


        $('#outstanding-mobile').html(
            '<div class="empty-mobile">' +
                '<i class="fas fa-exclamation-circle" ' +
                    'style="color:#fca5a5;"></i>' +
                safeMessage +
            '</div>'
        );


        $('#closed-mobile').html(
            '<div class="empty-mobile">' +
                '<i class="fas fa-exclamation-circle" ' +
                    'style="color:#fca5a5;"></i>' +
                safeMessage +
            '</div>'
        );

    }


    /* ============================================================
     * ACCORDION
     * ============================================================ */
    function toggleAccordion(id) {

        var element = $('#' + id);


        /*
         * Kalau ingin hanya satu accordion yang terbuka
         * dalam tab yang sama, aktifkan bagian ini:
         *
         * element
         *     .siblings('.task-accordion')
         *     .removeClass('open');
         */


        element.toggleClass('open');

    }


    /* ============================================================
     * TASK DISPLAY HELPER
     * ============================================================ */
    function getTaskDisplayData(item) {

        var dateFrom =
            safeValue(item.date_from, '');

        var dateTo =
            safeValue(item.date_to, '');


        return {
            date: formatTaskDate(dateFrom),
            timeFrom: getTimeOnly(dateFrom),
            timeTo: getTimeOnly(dateTo)
        };

    }


    function formatTaskDate(value) {

        if (!value) {
            return '-';
        }


        var datePart =
            String(value)
                .split(' ')[0];


        var parts =
            datePart.split('-');


        if (parts.length !== 3) {
            return value;
        }


        var year =
            parts[0];

        var monthIndex =
            parseInt(parts[1], 10) - 1;

        var day =
            parts[2];


        if (
            monthIndex < 0 ||
            monthIndex > 11
        ) {
            return value;
        }


        return (
            day +
            ' ' +
            months[monthIndex] +
            ' ' +
            year.substr(2, 2)
        );

    }


    function getTimeOnly(value) {

        if (!value) {
            return '-';
        }


        var stringValue =
            String(value);


        /*
         * yyyy-mm-dd HH:mm:ss
         */
        if (
            stringValue.length >= 16 &&
            stringValue.indexOf(' ') !== -1
        ) {
            return stringValue.substr(11, 5);
        }


        /*
         * yyyy-mm-ddTHH:mm:ss
         */
        if (
            stringValue.length >= 16 &&
            stringValue.indexOf('T') !== -1
        ) {
            return stringValue.substr(11, 5);
        }


        return '-';

    }


    /* ============================================================
     * ACTION URL
     * ============================================================ */
    function getActionUrl(item) {

        var remark =
            safeValue(item.remark, '');


        if (/daily/i.test(remark)) {

            return (
                '{{ url("index/additional/driver/daily_job") }}/' +
                encodeURIComponent(
                    safeValue(item.id, '')
                )
            );

        }


        var taskId =
            safeValue(item.task_id, '');


        var encodedTaskId = '';


        try {

            encodedTaskId =
                btoa(
                    String(taskId)
                );

        } catch (e) {

            encodedTaskId =
                String(taskId);

        }


        return (
            '{{ url("index/additional/driver/job") }}/' +
            encodeURIComponent(encodedTaskId)
        );

    }


    /* ============================================================
     * USER
     * ============================================================ */
    function renderUserCell(name) {

        name =
            safeValue(name, '-');


        var initials =
            getInitials(name);


        return (
            '<div class="user-cell">' +

                '<div class="user-avatar">' +
                    escapeHtml(initials) +
                '</div>' +

                '<span class="user-name">' +
                    escapeHtml(name) +
                '</span>' +

            '</div>'
        );

    }


    function getInitials(name) {

        if (
            !name ||
            name === '-'
        ) {
            return '?';
        }


        var words =
            String(name)
                .trim()
                .split(/\s+/);


        var initials =
            words
                .map(function (word) {
                    return word.charAt(0);
                })
                .slice(0, 2)
                .join('')
                .toUpperCase();


        return initials || '?';

    }


    /* ============================================================
     * CAR
     * ============================================================ */
    function renderCarCell(platNo, car) {

        return (
            '<div class="car-plat">' +
                escapeHtml(
                    safeValue(platNo, '-')
                ) +
            '</div>' +

            '<div class="car-name">' +
                escapeHtml(
                    safeValue(car, '-')
                ) +
            '</div>'
        );

    }


    /* ============================================================
     * MOBILE FIELD
     * ============================================================ */
    function renderMobileField(label, valueHtml) {

        return (
            '<div class="mobile-field">' +

                '<span class="mobile-label">' +
                    escapeHtml(label) +
                '</span>' +

                '<div class="mobile-value">' +
                    valueHtml +
                '</div>' +

            '</div>'
        );

    }


    function renderClosedBox(
        label,
        valueHtml,
        full
    ) {

        return (
            '<div class="closed-detail-box' +
                (full ? ' full' : '') +
            '">' +

                '<div class="closed-detail-label">' +
                    escapeHtml(label) +
                '</div>' +

                '<div class="closed-detail-value">' +
                    valueHtml +
                '</div>' +

            '</div>'
        );

    }


    /* ============================================================
     * MONEY
     * ============================================================ */
    function renderMoney(value) {

        if (
            value === null ||
            typeof value === 'undefined' ||
            value === ''
        ) {

            return (
                '<span class="status-empty">-</span>'
            );

        }


        var parsed =
            parseFloat(
                String(value)
                    .replace(/,/g, '')
            );


        if (isNaN(parsed)) {

            return (
                '<span class="money-value">' +
                    escapeHtml(String(value)) +
                '</span>'
            );

        }


        return (
            '<span class="money-value">' +
                'Rp ' +
                formatNumber(parsed) +
            '</span>'
        );

    }


    function formatNumber(value) {

        return Number(value)
            .toLocaleString('id-ID', {
                maximumFractionDigits: 0
            });

    }


    /* ============================================================
     * DATE TIME
     * ============================================================ */
    function formatDateTimeValue(value) {

        if (!value) {
            return '<span class="status-empty">-</span>';
        }


        var text =
            String(value);


        /*
         * yyyy-mm-dd HH:mm:ss
         */
        var matched =
            text.match(
                /^(\d{4})-(\d{2})-(\d{2})[ T](\d{2}):(\d{2})/
            );


        if (!matched) {

            return escapeHtml(text);

        }


        var monthIndex =
            parseInt(matched[2], 10) - 1;


        var formatted =
            matched[3] +
            ' ' +
            months[monthIndex] +
            ' ' +
            matched[1].substr(2, 2) +
            ' ' +
            matched[4] +
            ':' +
            matched[5];


        return escapeHtml(formatted);

    }


    /* ============================================================
     * FILE
     * ============================================================ */
    function renderFile(file) {

        if (!file) {

            return (
                '<span class="status-empty">-</span>'
            );

        }


        var fileName =
            getFileName(file);


        if (!fileName) {

            return (
                '<span class="status-empty">-</span>'
            );

        }


        var fileUrl =
            additionalFileBaseUrl +
            '/' +
            encodeURIComponent(fileName);


        var extension =
            getFileExtension(fileName);


        /*
         * Jika image
         */
        if (
            extension === 'jpg' ||
            extension === 'jpeg' ||
            extension === 'png' ||
            extension === 'webp' ||
            extension === 'gif' ||
            extension === 'bmp'
        ) {

            return (
                '<div class="file-preview">' +

                    '<img ' +
                        'src="' +
                            escapeAttribute(fileUrl) +
                        '" ' +
                        'class="file-thumb" ' +
                        'alt="File" ' +
                        'onclick="' +
                            "openImageModal('" +
                            escapeJsSingleQuote(fileUrl) +
                            "')" +
                        '"' +
                    '>' +

                    '<a ' +
                        'href="' +
                            escapeAttribute(fileUrl) +
                        '" ' +
                        'target="_blank" ' +
                        'rel="noopener noreferrer" ' +
                        'class="btn-file"' +
                    '>' +
                        '<i class="fas fa-external-link-alt"></i>' +
                        ' Lihat' +
                    '</a>' +

                '</div>'
            );

        }


        /*
         * Non-image
         */
        return (
            '<a ' +
                'href="' +
                    escapeAttribute(fileUrl) +
                '" ' +
                'target="_blank" ' +
                'rel="noopener noreferrer" ' +
                'class="btn-file"' +
            '>' +

                '<i class="fas fa-file"></i>' +

                ' Lihat File' +

            '</a>'
        );

    }


    function getFileName(file) {

        if (!file) {
            return '';
        }


        var value =
            String(file)
                .replace(/\\/g, '/');


        /*
         * Jika API ternyata mengembalikan full URL/path,
         * yang kita gunakan hanya nama file terakhir.
         */
        var parts =
            value.split('/');


        return parts[
            parts.length - 1
        ];

    }


    function getFileExtension(fileName) {

        if (!fileName) {
            return '';
        }


        var parts =
            String(fileName)
                .split('.');


        if (parts.length < 2) {
            return '';
        }


        return parts
            .pop()
            .toLowerCase();

    }


    /* ============================================================
     * IMAGE MODAL
     * ============================================================ */
    function openImageModal(url) {

        $('#image-modal-target')
            .attr('src', url);


        $('#image-modal')
            .addClass('show');

    }


    function closeImageModal() {

        $('#image-modal')
            .removeClass('show');


        $('#image-modal-target')
            .attr('src', '');

    }


    /* ============================================================
     * SAFE VALUE / ESCAPE
     * ============================================================ */
    function safeValue(value, fallback) {

        fallback =
            typeof fallback === 'undefined'
                ? ''
                : fallback;


        if (
            value === null ||
            typeof value === 'undefined' ||
            value === ''
        ) {
            return fallback;
        }


        return String(value);

    }


    function escapeHtml(value) {

        if (
            value === null ||
            typeof value === 'undefined'
        ) {
            return '';
        }


        return String(value)
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;')
            .replace(/"/g, '&quot;')
            .replace(/'/g, '&#039;');

    }


    function escapeAttribute(value) {

        return escapeHtml(value);

    }


    function escapeJsSingleQuote(value) {

        return String(value)
            .replace(/\\/g, '\\\\')
            .replace(/'/g, "\\'")
            .replace(/\r/g, '')
            .replace(/\n/g, '');

    }


    /* ============================================================
     * EXISTING HELPER
     * ============================================================ */
    function addZero(i) {

        return i < 10
            ? '0' + i
            : i;

    }


    function getActualFullDate() {

        var d = new Date();


        return (
            d.getFullYear() +
            '-' +
            addZero(d.getMonth() + 1) +
            '-' +
            addZero(d.getDate()) +
            ' ' +
            addZero(d.getHours()) +
            ':' +
            addZero(d.getMinutes()) +
            ':' +
            addZero(d.getSeconds())
        );

    }


    function openSuccessGritter(
        title,
        message
    ) {

        jQuery.gritter.add({

            title: title,

            text: message,

            class_name: 'growl-success',

            image:
                '{{ url("images/image-screen.png") }}',

            sticky: false,

            time: '3000'

        });

    }


    function openErrorGritter(
        title,
        message
    ) {

        jQuery.gritter.add({

            title: title,

            text: message,

            class_name: 'growl-danger',

            image:
                '{{ url("images/image-stop.png") }}',

            sticky: false,

            time: '3000'

        });

    }
</script>

@endsection