@extends('layouts.master')

@section('title', 'VFI')

@section('styles')
    <style>
        body { background: #f0f2f7 !important; }

        /* ── PAGE HEADER (sama persis dengan tanda_terima) ── */
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

        /* ── SECTION CARD ── */
        .section-card {
            background: #fff;
            border-radius: 16px;
            box-shadow: 0 2px 12px rgba(0,0,0,.06);
            border: 1px solid rgba(0,0,0,.05);
            overflow: hidden;
            margin-bottom: 24px;
        }
        .section-card-header {
            padding: 16px 20px;
            border-bottom: 1px solid #f0f2f7;
            background: #fafbff;
            display: flex;
            align-items: center;
            gap: 10px;
        }
        .section-card-header .dot {
            width: 8px; height: 8px;
            background: #2d6bc4;
            border-radius: 50%;
            flex-shrink: 0;
        }
        .section-card-header h4 {
            font-size: 13px; font-weight: 700; color: #1a202c;
            margin: 0; display: flex; align-items: center; gap: 8px;
        }
        .section-card-body {
            padding: 20px 24px;
        }

        /* ── CATEGORY COLUMNS ── */
        .vfi-columns {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 20px;
        }
        @media (max-width: 768px) {
            .vfi-columns { grid-template-columns: 1fr; }
        }

        .vfi-col-header {
            display: flex;
            align-items: center;
            gap: 10px;
            margin-bottom: 14px;
            padding-bottom: 10px;
            border-bottom: 2px solid #edf0f5;
        }
        .vfi-col-header .col-icon {
            width: 36px; height: 36px; border-radius: 10px;
            display: flex; align-items: center; justify-content: center;
            font-size: 16px; flex-shrink: 0;
        }
        .vfi-col-header .col-icon.blue   { background: #ebf2ff; color: #2d6bc4; }
        .vfi-col-header .col-icon.green  { background: #dcfce7; color: #16a34a; }
        .vfi-col-header .col-icon.yellow { background: #fff8e1; color: #b7740a; }
        .vfi-col-header h5 {
            font-size: 13px; font-weight: 700; color: #1a202c; margin: 0;
            text-transform: uppercase; letter-spacing: .5px;
        }

        /* ── MENU BUTTONS ── */
        .vfi-menu-btn {
            display: flex;
            align-items: center;
            gap: 12px;
            width: 100%;
            padding: 12px 16px;
            margin-bottom: 8px;
            background: #fafbff;
            border: 1.5px solid #e2e8f0;
            border-radius: 10px;
            text-decoration: none;
            color: #2d3748;
            font-size: 13px;
            font-weight: 600;
            transition: all 0.2s ease;
            cursor: pointer;
        }
        .vfi-menu-btn:hover {
            background: #ebf2ff;
            border-color: #2d6bc4;
            color: #2d6bc4;
            text-decoration: none;
            transform: translateX(2px);
            box-shadow: 0 2px 8px rgba(45,107,196,.12);
        }
        .vfi-menu-btn .btn-icon {
            width: 32px; height: 32px; border-radius: 8px;
            display: flex; align-items: center; justify-content: center;
            font-size: 14px; flex-shrink: 0;
            background: #ebf2ff; color: #2d6bc4;
            transition: all 0.2s;
        }
        .vfi-menu-btn:hover .btn-icon {
            background: #2d6bc4; color: #fff;
        }
        .vfi-menu-btn .btn-text { flex: 1; text-align: left; }
        .vfi-menu-btn .btn-text .btn-label {
            display: block;
            font-size: 13px;
            font-weight: 600;
            color: inherit;
        }
        .vfi-menu-btn .btn-text .btn-sub {
            display: block;
            font-size: 11px;
            font-weight: 400;
            color: #718096;
            margin-top: 1px;
        }
        .vfi-menu-btn:hover .btn-text .btn-sub { color: #5a9ae0; }
        .vfi-menu-btn .btn-arrow {
            font-size: 11px; color: #a0aec0;
            transition: transform .2s;
        }
        .vfi-menu-btn:hover .btn-arrow {
            transform: translateX(3px); color: #2d6bc4;
        }
    </style>
@stop

@section('content')
<div class="container-fluid" style="padding: 0 24px;">

    {{-- PAGE HEADER --}}
    <div class="page-header-modern" >
        <div class="header-left">
            <div class="badge-tag">
                <i class="fas fa-clipboard-check"></i> VFI
            </div>
            <h1>Vendor Final Inspection</h1>
            <p>Kelola proses inspeksi akhir, display, dan laporan produksi</p>
        </div>
    </div>

    <?php if (Auth::user()->username == 'true'): ?>
    {{-- MAIN SECTION CARD --}}
    <div class="section-card">
        <div class="section-card-header">
            <span class="dot"></span>
            <h4><i class="fas fa-th-large"></i> Menu VFI</h4>
        </div>
        <div class="section-card-body">
            <div class="vfi-columns">

                {{-- PROCESS --}}
                <div>
                    <div class="vfi-col-header">
                        <div class="col-icon blue">
                            <i class="fas fa-cogs"></i>
                        </div>
                        <h5>Process</h5>
                    </div>

                    <a href="{{ url('vfi/index/true') }}" class="vfi-menu-btn">
                        <div class="btn-icon"><i class="fas fa-edit"></i></div>
                        <div class="btn-text">
                            <span class="btn-label">Input VFI</span>
                            <span class="btn-sub">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="fas fa-chevron-right btn-arrow"></i>
                    </a>
                </div>

                {{-- DISPLAY --}}
                <div>
                    <div class="vfi-col-header">
                        <div class="col-icon green">
                            <i class="fas fa-tv"></i>
                        </div>
                        <h5>Display</h5>
                    </div>

                    <a href="{{ url('vfi/index/ng_rate/true') }}" class="vfi-menu-btn">
                        <div class="btn-icon"><i class="fas fa-chart-line"></i></div>
                        <div class="btn-text">
                            <span class="btn-label">Production NG Rate</span>
                            <span class="btn-sub">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="fas fa-chevron-right btn-arrow"></i>
                    </a>

                    <a href="{{ url('vfi/index/pareto/true') }}" class="vfi-menu-btn">
                        <div class="btn-icon"><i class="fas fa-chart-bar"></i></div>
                        <div class="btn-text">
                            <span class="btn-label">Production Pareto</span>
                            <span class="btn-sub">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="fas fa-chevron-right btn-arrow"></i>
                    </a>
                </div>

                {{-- REPORT --}}
                <div>
                    <div class="vfi-col-header">
                        <div class="col-icon yellow">
                            <i class="fas fa-file-alt"></i>
                        </div>
                        <h5>Report</h5>
                    </div>

                    <a href="{{ url('vfi/index/true/report') }}" class="vfi-menu-btn">
                        <div class="btn-icon"><i class="fas fa-copy"></i></div>
                        <div class="btn-text">
                            <span class="btn-label">Report Production Check</span>
                            <span class="btn-sub">{{ Auth::user()->name }}</span>
                        </div>
                        <i class="fas fa-chevron-right btn-arrow"></i>
                    </a>
                </div>

            </div>
        </div>
    </div>
    <?php endif; ?>


</div>
@endsection

@section('scripts')
    <script>
        $(document).ready(function() {
            $('#side_vfi').addClass('menu-open');
            $('body').addClass('sidebar-collapse');
        });
    </script>
@endsection