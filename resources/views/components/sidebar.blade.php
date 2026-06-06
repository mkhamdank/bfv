<style>
    /* ── Sidebar shell ── */
    .main-sidebar {
        background-color: #ffffff !important;
        border-right: 1px solid #eeecfb;
        box-shadow: 2px 0 16px rgba(96,92,168,.07);
    }

    /* ── Brand / Logo area ── */
    .main-sidebar .brand-link {
        background-color: #605ca8 !important;
        border-bottom: none !important;
        padding: 0 16px;
        display: flex !important;
        align-items: center !important;
        justify-content: flex-start;
        gap: 10px;
        height: 57px;
        min-height: 57px;
        width: 100%;
        line-height: 57px;
    }

    .main-sidebar .brand-link:hover {
        background-color: #534da0 !important;
    }

    .main-sidebar .brand-image {
        width: 32px !important;
        height: 32px !important;
        max-height: 32px !important;
        border-radius: 8px;
        margin: 0 !important;
        float: none !important;
        object-fit: contain;
    }

    .main-sidebar .brand-text {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 14px !important;
        font-weight: 700 !important;
        color: #ffffff !important;
        letter-spacing: 0.01em;
    }

    /* ── Sidebar inner ── */
    .sidebar {
        padding: 12px 0 !important;
        background: transparent !important;
    }

    /* ── Section label (if any) ── */
    .nav-sidebar .nav-header {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 10.5px;
        font-weight: 700;
        letter-spacing: .1em;
        text-transform: uppercase;
        color: #b0acd8;
        padding: 16px 20px 6px;
    }

    /* ── Nav item ── */
    /* margin sengaja tidak di-set di sini agar sidebar lebar mengikuti default AdminLTE */

    /* Collapsed: beri margin agar kotak aktif tidak melebar penuh */
    /* .sidebar-mini.sidebar-collapse .nav-sidebar > .nav-item {
        margin: 2px 6px !important;
    } */

    .nav-sidebar .nav-link {
        font-family: 'Plus Jakarta Sans', sans-serif !important;
        font-size: 13.5px !important;
        font-weight: 500 !important;
        color: #4a4770 !important;
        border-radius: 10px !important;
        padding: 10px 14px !important;
        display: flex !important;
        align-items: center !important;
        gap: 10px;
        min-height: 44px !important;
        transition: background 0.18s, color 0.18s !important;
    }

    .nav-sidebar .nav-link p {
        margin: 0 !important;
        flex: 1;
        white-space: nowrap;
    }

    .nav-sidebar .nav-link:hover {
        background-color: #f0eef9 !important;
        color: #605ca8 !important;
    }

    .nav-sidebar .nav-link .right {
        margin-left: auto;
        font-size: 11px;
        opacity: .65;
        transition: transform 0.2s, opacity 0.2s;
    }

    /* Active & open — saat sidebar terbuka */
    .sidebar-mini:not(.sidebar-collapse) .nav-sidebar .nav-link.active,
    .sidebar-mini:not(.sidebar-collapse) .nav-sidebar .nav-item.menu-open > .nav-link {
        background-color: #605ca8 !important;
        color: #ffffff !important;
        box-shadow: 0 4px 12px rgba(96,92,168,.25);
    }

    .sidebar-mini:not(.sidebar-collapse) .nav-sidebar .nav-link.active .nav-icon,
    .sidebar-mini:not(.sidebar-collapse) .nav-sidebar .nav-item.menu-open > .nav-link .nav-icon {
        color: rgba(255,255,255,.9) !important;
    }

    /* Active — saat sidebar collapsed: hanya warna ikon, tanpa kotak */
    .sidebar-collapse .nav-sidebar .nav-link.active,
    .sidebar-collapse .nav-sidebar .nav-item.menu-open > .nav-link {
        background-color: #605ca8 !important;
        box-shadow: 0 4px 12px rgba(96,92,168,.35) !important;
        color: #ffffff !important;
        border-radius: 10px !important;
        padding: 10px !important;
        width: auto !important;
    }

    .sidebar-collapse .nav-sidebar .nav-link.active .nav-icon,
    .sidebar-collapse .nav-sidebar .nav-item.menu-open > .nav-link .nav-icon {
        color: #ffffff !important;
    }

    /* ── Nav icons ── */
    .nav-sidebar .nav-icon {
        font-size: 14px !important;
        width: 20px !important;
        text-align: center;
        color: #9d99cc !important;
        transition: color 0.18s;
        margin-right: 0 !important;
        flex-shrink: 0;
    }

    .nav-sidebar .nav-link:hover .nav-icon {
        color: #605ca8 !important;
    }

    /* ── Treeview children ── */
    .nav-sidebar .nav-treeview {
        padding: 2px 0 4px 0;
        background: transparent !important;
    }

    .nav-sidebar .nav-treeview > .nav-item {
        margin: 1px 0 1px 10px;
    }

    .nav-sidebar .nav-treeview .nav-link {
        font-size: 13px !important;
        font-weight: 400 !important;
        color: #6b679a !important;
        padding: 8px 12px !important;
        border-radius: 8px !important;
    }

    .nav-sidebar .nav-treeview .nav-link:hover {
        background-color: #f0eef9 !important;
        color: #605ca8 !important;
    }

    .nav-sidebar .nav-treeview .nav-link.active {
        background-color: #ece9fb !important;
        color: #605ca8 !important;
        font-weight: 600 !important;
        box-shadow: none !important;
    }

    .nav-sidebar .nav-treeview .nav-icon {
        font-size: 13px !important;
    }

    /* ── Chevron arrow ── */
    .nav-sidebar .nav-link .right {
        margin-left: auto;
        font-size: 11px;
        opacity: .5;
        transition: transform 0.2s;
    }

    .nav-sidebar .nav-item.menu-open > .nav-link .right {
        transform: rotate(-90deg);
        opacity: .8;
    }

    /* ── Badge ── */
    .nav-sidebar .badge {
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 10px;
        font-weight: 700;
        border-radius: 20px;
        padding: 2px 7px;
    }

    /* ── Scrollbar ── */
    .os-scrollbar-handle {
        background: rgba(96,92,168,.2) !important;
        border-radius: 4px !important;
    }

    /* ══════════════════════════════════════
       DISABLE SIDEBAR HOVER EXPAND (collapsed mode)
       Ganti dengan tooltip custom
    ══════════════════════════════════════ */

    /* ── Disable sidebar hover expand (collapsed mode) ── */
    .sidebar-mini.sidebar-collapse .main-sidebar:hover {
        width: 4.6rem !important;
    }
    .sidebar-mini.sidebar-collapse .main-sidebar .brand-link {
        overflow: hidden !important;
    }
    .sidebar-mini.sidebar-collapse .main-sidebar:hover .brand-text,
    .sidebar-mini.sidebar-collapse .main-sidebar .brand-text {
        display: none !important;
    }
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link p,
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link > .right,
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link > .badge,
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-item.menu-open > .nav-treeview {
        display: none !important;
        visibility: hidden !important;
        opacity: 0 !important;
        width: 0 !important;
        overflow: hidden !important;
    }
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-link {
        width: auto !important;
        overflow: hidden !important;
        padding: 10px !important;
        min-height: 44px !important;
    }
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-icon {
        width: 100%;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
    }
    .sidebar-mini.sidebar-collapse .nav-sidebar .nav-icon {
        margin: auto !important;
        width: 20px !important;
        height: 20px !important;
        display: flex !important;
        align-items: center !important;
        justify-content: center !important;
        line-height: 1 !important;
    }
    .sidebar-mini.sidebar-collapse .nav-sidebar > .nav-item > .nav-treeview {
        display: none !important;
    }

    /* ── JS Tooltip ── */
    .sb-tooltip {
        position: fixed;
        background: #2d2b4e;
        color: #fff;
        font-size: 12px;
        font-weight: 600;
        font-family: 'Plus Jakarta Sans', sans-serif;
        padding: 6px 12px;
        border-radius: 8px;
        white-space: nowrap;
        pointer-events: none;
        z-index: 99999;
        box-shadow: 0 4px 16px rgba(0,0,0,.2);
        opacity: 0;
        transform: translateX(-4px);
        transition: opacity .18s, transform .18s;
    }
    .sb-tooltip.show {
        opacity: 1;
        transform: translateX(0);
    }
    .sb-tooltip::before {
        content: '';
        position: absolute;
        right: 100%;
        top: 50%;
        transform: translateY(-50%);
        border: 5px solid transparent;
        border-right-color: #2d2b4e;
    }
</style>

<aside class="main-sidebar sidebar-light-primary elevation-2 control-sidebar-push" style="position: fixed;">

    <a href="#" class="brand-link" style="text-decoration:none; padding-left: 20px !important;">
        <img src="{{ url('img/bridgesmall.png') }}" alt="Bridge for Vendor logo" class="brand-image">
        <span class="brand-text">BridgeforVendor</span>
    </a>

    <div class="mt-2 sidebar os-host os-theme-light os-host-resize-disabled os-host-transition">
        <div class="os-resize-observer-host observed">
            <div class="os-resize-observer" style="left: 0px; right: auto;"></div>
        </div>
        <div class="os-size-auto-observer observed" style="height: calc(100% + 1px); float: left;">
            <div class="os-resize-observer"></div>
        </div>
        <div class="os-content-glue" style="margin: 0px -8px; width: 249px; height: 520px;"></div>
        <div class="os-padding">
            <div class="os-viewport os-viewport-native-scrollbars-invisible" style="overflow-y: scroll;">
                <div class="os-content" style="padding: 0px 8px; height: 100%; width: 100%;">

                    <nav class="mt-1">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">

                            <?php if (isset(Auth::user()->username)) { ?>
                                <li class="nav-item" id="side_dashboard">
                                    <a href="{{ route('admin.dashboard') }}" class="nav-link" data-tooltip="Dashboard">
                                        <i class="nav-icon fas fa-tachometer-alt"></i>
                                        <p>Dashboard</p>
                                    </a>
                                </li>
                                
                            <?php } ?>

                            @can('view vfi')
                            <li class="nav-item" id="side_vfi">
                                <a href="{{ route('admin.vfi.index') }}" class="nav-link" data-tooltip="VFI">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        VFI
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                            </li>
                            @endcan

                            @can('view invoice')
                            <li class="nav-item" id="side_tanda_terima">
                                <a href="{{ url('index/invoice/tanda_terima') }}" class="nav-link" data-tooltip="Tanda Terima">
                                    <i class="nav-icon fas fa-file-invoice"></i>
                                    <p>
                                        Tanda Terima
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                            </li>
                            @endcan

                            @can('view driver')
                            <li class="nav-item" id="side_driver">
                                <a href="{{ url('index/driver') }}" class="nav-link" data-tooltip="Driver">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Driver</p>
                                </a>
                            </li>
                            <!-- <li class="nav-item">
                                <a href="{{ url('index/driver/attendance/report') }}" class="nav-link" data-tooltip="Rekam Kehadiran">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Rekam Kehadiran</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('index/passenger/attendance') }}" class="nav-link" data-tooltip="Absensi Penumpang Reguler">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>Absensi Penumpang Reguler</p>
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ url('index/driver/toll_parking') }}" class="nav-link" data-tooltip="Tol &amp; Parkir">
                                    <i class="nav-icon fas fa-parking"></i>
                                    <p>Tol &amp; Parkir</p>
                                </a>
                            </li> -->
                            @endcan

                            @can('view stock')
                            <li class="nav-item">
                                <a href="#" class="nav-link" data-tooltip="Stock">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>Stock</p>
                                </a>
                            </li>
                            @endcan

                            <!-- @can('view molding')
                            <li class="nav-item" id="side_molding">
                                <a href="{{ url('/index/workshop/check_molding_vendor') }}" class="nav-link" data-tooltip="Molding">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>Molding</p>
                                </a>
                            </li>
                            @endcan -->

                            @can('view recruitment')
                            <li class="nav-item" id="side_hr">
                                <a href="{{ url('index/human_resource') }}" class="nav-link" data-tooltip="Human Resource">
                                    <i class="nav-icon fas fa-user-circle"></i>
                                    <p>Recruitment</p>
                                </a>
                            </li>
                            @endcan

                            @can('view logistic')
                            <li class="nav-item" id="side_logistic">
                                <a href="#" class="nav-link" data-tooltip="Logistic">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>Logistic</p>
                                </a>
                            </li>
                            @endcan

                            @can('view molding new')
                            <li class="nav-item" id="side_diagnosa_molding">
                                <a href="{{ url('molding') }}" class="nav-link" data-tooltip="Diagnosa Molding">
                                    <i class="nav-icon fas fa-tasks"></i>
                                    <p>
                                        Diagnosa Molding
                                        <!-- <i class="fas fa-angle-left right"></i> -->
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('user.molding_list') }}" class="nav-link" data-tooltip="Daftar Molding">
                                            <i class="nav-icon fas fa-cubes"></i>
                                            <p>Daftar Molding</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.molding_input_shot') }}" class="nav-link" data-tooltip="Riwayat Shot">
                                            <i class="nav-icon fas fa-user-edit"></i>
                                            <p>Riwayat Shot</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.molding_trouble') }}" class="nav-link" data-tooltip="Riwayat Kerusakan">
                                            <i class="nav-icon fas fa-hand-holding-medical"></i>
                                            <p>Riwayat Kerusakan</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.molding_form') }}" class="nav-link" data-tooltip="Daftar Form">
                                            <i class="nav-icon fas fa-tasks"></i>
                                            <p>Daftar Form</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.molding_form') }}" class="nav-link" data-tooltip="Monitoring">
                                            <i class="nav-icon fas fa-chart-bar"></i>
                                            <p>Monitoring</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>
                            @endcan

                            @can('view fixed asset')
                            <li class="nav-item" id="side_fixed_asset">
                                <a href="{{ url('index/fixed_asset/dashboard') }}" class="nav-link" data-tooltip="Fixed Asset">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>
                                        Fixed Asset
                                        <!-- <i class="fas fa-angle-left right"></i> -->
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset') }}" class="nav-link" data-tooltip="Fixed Asset Check">
                                            <i class="nav-icon fas fa-tasks"></i>
                                            <p>Fixed Asset Check</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset_audit') }}" class="nav-link" data-tooltip="Fixed Asset Audit">
                                            <i class="nav-icon fas fa-clipboard-check"></i>
                                            <p>Fixed Asset Audit</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset_report') }}" class="nav-link" data-tooltip="Fixed Asset Report">
                                            <i class="nav-icon fas fa-chart-bar"></i>
                                            <p>Fixed Asset Report</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>
                            @endcan

                            @can('view quotation')
                            <li class="nav-item" id="side_quotation">
                                <a href="{{ url('index/quotation/dashboard') }}" class="nav-link" data-tooltip="Quotation">
                                    <i class="nav-icon fas fa-file-invoice-dollar"></i>
                                    <p>
                                        Quotation
                                        <!-- <i class="fas fa-angle-left right"></i> -->
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset') }}" class="nav-link" data-tooltip="Fixed Asset Check">
                                            <i class="nav-icon fas fa-tasks"></i>
                                            <p>Fixed Asset Check</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset_audit') }}" class="nav-link" data-tooltip="Fixed Asset Audit">
                                            <i class="nav-icon fas fa-clipboard-check"></i>
                                            <p>Fixed Asset Audit</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('user.fixed_asset_report') }}" class="nav-link" data-tooltip="Fixed Asset Report">
                                            <i class="nav-icon fas fa-chart-bar"></i>
                                            <p>Fixed Asset Report</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>
                            @endcan

                            @can('view users')
                            <li class="nav-item" id="side_user_management">
                                <a href="{{ url('/user') }}" class="nav-link" data-tooltip="Setting">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>
                                        Setting

                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                <!-- <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link" data-tooltip="User">
                                            <i class="fa fa-user nav-icon"></i>
                                            <p>User</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permission.index') }}" class="nav-link" data-tooltip="User Permission">
                                            <i class="fa fa-user-cog nav-icon"></i>
                                            <p>User Permission</p>
                                        </a>
                                    </li>
                                </ul> -->
                            </li>
                            <!-- <li class="nav-item">
                                <a href="{{ route('admin.settings.index') }}" class="nav-link" data-tooltip="Setting">
                                    <i class="nav-icon fas fa-cog"></i>
                                    <p>Setting</p>
                                </a>
                            </li> -->
                            @endcan

                        </ul>
                    </nav>

                </div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-horizontal os-scrollbar-unusable os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="width: 100%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar os-scrollbar-vertical os-scrollbar-auto-hidden">
            <div class="os-scrollbar-track">
                <div class="os-scrollbar-handle" style="height: 42.5313%; transform: translate(0px, 0px);"></div>
            </div>
        </div>
        <div class="os-scrollbar-corner"></div>
    </div>

</aside>

<script>
(function () {
    /* Periksa apakah tooltip sudah ada, jika ada jangan buat ulang */
    var tip = document.getElementById('sb-tooltip-instance');
    if (!tip) {
        tip = document.createElement('div');
        tip.id = 'sb-tooltip-instance';
        tip.className = 'sb-tooltip';
        document.body.appendChild(tip);
    }

    var hideTimer;
    var isTooltipInitialized = tip.dataset.initialized === 'true';

    /* Jika sudah diinisialisasi, hentikan eksekusi */
    if (isTooltipInitialized) return;
    tip.dataset.initialized = 'true';

    function showTip(el) {
        /* Hanya tampilkan saat sidebar collapsed */
        if (!document.body.classList.contains('sidebar-collapse')) return;

        var label = el.getAttribute('data-tooltip');
        if (!label) return;

        clearTimeout(hideTimer);
        var rect = el.getBoundingClientRect();
        tip.textContent = label;
        tip.style.top  = (rect.top + rect.height / 2) + 'px';
        tip.style.left = (rect.right + 10) + 'px';
        tip.style.transform = 'translateY(-50%) translateX(-4px)';
        tip.style.opacity = '0';
        tip.classList.add('show');

        /* Force reflow lalu animate */
        requestAnimationFrame(function () {
            tip.style.transform = 'translateY(-50%) translateX(0)';
            tip.style.opacity   = '1';
        });
    }

    function hideTip() {
        tip.style.opacity   = '0';
        tip.style.transform = 'translateY(-50%) translateX(-4px)';
        hideTimer = setTimeout(function () { tip.classList.remove('show'); }, 200);
    }

    /* Gunakan event delegation dengan mouseenter/mouseleave */
    document.addEventListener('mouseenter', function (e) {
        var link = e.target.closest('.nav-sidebar .nav-link');
        if (link) {
            showTip(link);
        }
    }, true);

    document.addEventListener('mouseleave', function (e) {
        var link = e.target.closest('.nav-sidebar .nav-link');
        if (link) {
            hideTip();
        }
    }, true);
})();
</script>