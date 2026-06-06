@extends('layouts.master')

@section('title', 'Diagnosa Molding')

@section('styles')
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

    /* ══════════════════════════════════════
       MENU CARD
    ══════════════════════════════════════ */
    .menu-card {
        background: #fff;
        border-radius: 16px;
        box-shadow: 0 2px 12px rgba(0,0,0,.06);
        border: 1px solid rgba(0,0,0,.05);
        overflow: hidden;
        margin-bottom: 24px;
    }
    .menu-card-header {
        padding: 16px 24px;
        border-bottom: 1px solid #f0f2f7;
        background: #fafbff;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    .menu-card-header .dot {
        width: 8px; height: 8px;
        background: #605ca8;
        border-radius: 50%;
        flex-shrink: 0;
    }
    .menu-card-header h4 {
        font-size: 13px;
        font-weight: 700;
        color: #1a202c;
        margin: 0;
        display: flex;
        align-items: center;
        gap: 8px;
    }
    .menu-card-body {
        padding: 20px 24px;
        display: grid;
        grid-template-columns: repeat(3, 1fr);
        gap: 14px;
    }
    @media (max-width: 768px) {
        .menu-card-body { grid-template-columns: 1fr; }
    }
    @media (min-width: 769px) and (max-width: 1024px) {
        .menu-card-body { grid-template-columns: repeat(2, 1fr); }
    }

    /* ══════════════════════════════════════
       MENU BUTTONS
    ══════════════════════════════════════ */
    .mol-menu-btn {
        display: flex;
        align-items: center;
        gap: 16px;
        padding: 20px;
        background: #fafbff;
        border: 1.5px solid #e2e8f0;
        border-radius: 12px;
        text-decoration: none;
        color: #2d3748;
        transition: all 0.2s ease;
        cursor: pointer;
    }
    .mol-menu-btn:hover {
        background: #ede9fe;
        border-color: #605ca8;
        color: #4a4690;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(96,92,168,.15);
    }
    .mol-menu-btn .btn-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    .mol-menu-btn:hover .btn-icon { transform: scale(1.08); }

    .btn-icon.purple { background: #ede9fe; color: #7c3aed; }
    .btn-icon.blue   { background: #ebf2ff; color: #2d6bc4; }
    .btn-icon.red    { background: #fee2e2; color: #dc2626; }
    .btn-icon.amber  { background: #fef3c7; color: #b45309; }
    .btn-icon.green  { background: #dcfce7; color: #15803d; }

    .mol-menu-btn .btn-content { flex: 1; min-width: 0; }
    .mol-menu-btn .btn-content .btn-title {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: inherit;
        margin-bottom: 3px;
    }
    .mol-menu-btn .btn-content .btn-desc {
        display: block;
        font-size: 12px;
        font-weight: 400;
        color: #718096;
        line-height: 1.4;
    }
    .mol-menu-btn:hover .btn-content .btn-desc { color: #7c6fd0; }

    .mol-menu-btn .btn-arrow {
        font-size: 12px;
        color: #c4c1e0;
        flex-shrink: 0;
        transition: transform .2s, color .2s;
    }
    .mol-menu-btn:hover .btn-arrow {
        transform: translateX(4px);
        color: #605ca8;
    }
</style>
@stop

@section('content')
<div class="container-fluid" style="padding: 0 24px;">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern">
        <div class="header-left">
            <div class="badge-tag">
                <i class="fas fa-tasks"></i>&nbsp; Molding
            </div>
            <h1>Diagnosa Molding</h1>
            <p>Kelola daftar molding, riwayat shot, kerusakan, form, dan monitoring</p>
        </div>
    </div>

    {{-- MENU CARD --}}
    <div class="menu-card">
        <div class="menu-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-th-large" style="color:#605ca8;"></i>&nbsp; Menu</h4>
        </div>
        <div class="menu-card-body">

            {{-- Daftar Molding --}}
            <a href="{{ route('user.molding_list') }}" class="mol-menu-btn">
                <div class="btn-icon purple">
                    <i class="fas fa-cubes"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Daftar Molding</span>
                    <span class="btn-desc">Lihat seluruh data molding yang terdaftar</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Riwayat Shot --}}
            <a href="{{ route('user.molding_input_shot') }}" class="mol-menu-btn">
                <div class="btn-icon blue">
                    <i class="fas fa-user-edit"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Riwayat Shot</span>
                    <span class="btn-desc">Input dan pantau riwayat shot molding</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Riwayat Kerusakan --}}
            <a href="{{ route('user.molding_trouble') }}" class="mol-menu-btn">
                <div class="btn-icon red">
                    <i class="fas fa-hand-holding-medical"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Riwayat Kerusakan</span>
                    <span class="btn-desc">Catat dan lacak kerusakan molding</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Daftar Form --}}
            <a href="{{ route('user.molding_form') }}" class="mol-menu-btn">
                <div class="btn-icon amber">
                    <i class="fas fa-file-alt"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Daftar Form</span>
                    <span class="btn-desc">Kelola form inspeksi dan pemeriksaan molding</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Monitoring --}}
            <a href="{{ route('user.molding_form') }}" class="mol-menu-btn">
                <div class="btn-icon green">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Monitoring</span>
                    <span class="btn-desc">Dashboard pemantauan kondisi molding secara keseluruhan</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Maintenance Molding Vendor --}}
            <a href="{{ url('/index/workshop/check_molding_vendor') }}" class="mol-menu-btn">
                <div class="btn-icon red">
                    <i class="fas fa-wrench"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Maintenance Molding Vendor</span>
                    <span class="btn-desc">Monitoring & penanganan temuan cek molding vendor</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

        </div>
    </div>

</div>
@endsection

@section('scripts')
<script>
    $(document).ready(function() {
        $('#side_diagnosa_molding').addClass('menu-open');
        $('body').addClass("sidebar-collapse");
    });
</script>
@endsection