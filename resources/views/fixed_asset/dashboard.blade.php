@extends('layouts.master')

@section('title', 'Fixed Asset')

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
    .fa-menu-btn {
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
    .fa-menu-btn:hover {
        background: #ede9fe;
        border-color: #605ca8;
        color: #4a4690;
        text-decoration: none;
        transform: translateY(-2px);
        box-shadow: 0 4px 14px rgba(96,92,168,.15);
    }
    .fa-menu-btn .btn-icon {
        width: 48px; height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 20px;
        flex-shrink: 0;
        transition: transform 0.2s;
    }
    .fa-menu-btn:hover .btn-icon { transform: scale(1.08); }

    .btn-icon.purple { background: #ede9fe; color: #7c3aed; }
    .btn-icon.indigo { background: #e0e7ff; color: #4338ca; }
    .btn-icon.teal   { background: #ccfbf1; color: #0f766e; }

    .fa-menu-btn .btn-content { flex: 1; min-width: 0; }
    .fa-menu-btn .btn-content .btn-title {
        display: block;
        font-size: 14px;
        font-weight: 700;
        color: inherit;
        margin-bottom: 3px;
    }
    .fa-menu-btn .btn-content .btn-desc {
        display: block;
        font-size: 12px;
        font-weight: 400;
        color: #718096;
        line-height: 1.4;
    }
    .fa-menu-btn:hover .btn-content .btn-desc { color: #7c6fd0; }

    .fa-menu-btn .btn-arrow {
        font-size: 12px;
        color: #c4c1e0;
        flex-shrink: 0;
        transition: transform .2s, color .2s;
    }
    .fa-menu-btn:hover .btn-arrow {
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
                <i class="fas fa-cubes"></i>&nbsp; Asset Management
            </div>
            <h1>Fixed Asset</h1>
            <p>Kelola pemeriksaan, audit, dan laporan aset tetap perusahaan</p>
        </div>
    </div>

    {{-- MENU CARD --}}
    <div class="menu-card">
        <div class="menu-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-th-large" style="color:#605ca8;"></i>&nbsp; Menu</h4>
        </div>
        <div class="menu-card-body">

            {{-- Fixed Asset Check --}}
            <a href="{{ route('user.fixed_asset') }}" class="fa-menu-btn">
                <div class="btn-icon purple">
                    <i class="fas fa-tasks"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Fixed Asset Check</span>
                    <span class="btn-desc">Pemeriksaan kondisi dan status aset tetap</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Fixed Asset Audit --}}
            <a href="{{ route('user.fixed_asset_audit') }}" class="fa-menu-btn">
                <div class="btn-icon indigo">
                    <i class="fas fa-clipboard-check"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Fixed Asset Audit</span>
                    <span class="btn-desc">Audit menyeluruh terhadap data aset tetap</span>
                </div>
                <i class="fas fa-chevron-right btn-arrow"></i>
            </a>

            {{-- Fixed Asset Report --}}
            <a href="{{ route('user.fixed_asset_report') }}" class="fa-menu-btn">
                <div class="btn-icon teal">
                    <i class="fas fa-chart-bar"></i>
                </div>
                <div class="btn-content">
                    <span class="btn-title">Fixed Asset Report</span>
                    <span class="btn-desc">Laporan dan rekapitulasi data aset tetap</span>
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
        $('#side_fixed_asset').addClass('menu-open');
        $('body').addClass("sidebar-collapse");
    });
</script>
@endsection