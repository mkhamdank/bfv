<style>
    /* ── Navbar ── */
    .main-header.navbar {
        background-color: #605ca8 !important;
        border-bottom: none;
        box-shadow: 0 2px 12px rgba(96, 92, 168, 0.25);
        min-height: 57px;
        padding: 0 16px;
        position: fixed !important;
        top: 0 !important;
        left: 0 !important;
        right: 0 !important;
        z-index: 1000 !important;
    }

    /* Adjust body/wrapper for fixed navbar */
    body { padding-top: 57px !important; }
    .wrapper { margin-top: 0 !important; }
    .content-wrapper { margin-top: 0 !important; }

    /* Hamburger */
    .main-header .nav-link.text-light {
        color: rgba(255,255,255,0.85) !important;
        font-size: 16px;
        padding: 8px 10px;
        border-radius: 8px;
        transition: background 0.18s;
    }
    .main-header .nav-link.text-light:hover {
        background: rgba(255,255,255,0.12);
        color: #fff !important;
    }

    /* User dropdown trigger */
    .main-header .navbar-nav .nav-link {
        color: rgba(255,255,255,0.9) !important;
        font-family: 'Plus Jakarta Sans', sans-serif;
        font-size: 13.5px;
        font-weight: 500;
        display: flex;
        align-items: center;
        gap: 7px;
        padding: 6px 12px;
        border-radius: 8px;
        transition: background 0.18s;
    }
    .main-header .navbar-nav .nav-link:hover {
        background: rgba(255,255,255,0.12);
        color: #fff !important;
        
    }

    .main-header .navbar-nav .nav-link .far.fa-user-circle {
        font-size: 18px;
        opacity: .85;
    }

    /* Dropdown menu */
    .main-header .dropdown-menu {
        border: none;
        border-radius: 12px;
        box-shadow: 0 4px 6px rgba(96,92,168,.06), 0 12px 32px rgba(96,92,168,.14);
        padding: 6px;
        min-width: 200px;
        margin-top: 6px;
        font-family: 'Plus Jakarta Sans', sans-serif;
    }

    .main-header .dropdown-header {
        font-size: 12px;
        font-weight: 700;
        color: #605ca8;
        letter-spacing: .04em;
        text-transform: uppercase;
        padding: 8px 12px 6px;
        display: flex;
        align-items: center;
        gap: 7px;
    }

    .main-header .dropdown-divider {
        margin: 4px 0;
        border-color: #eeecfb;
    }

    .main-header .dropdown-item {
        font-size: 13.5px;
        font-weight: 500;
        color: #3d3a5c;
        padding: 9px 12px;
        border-radius: 8px;
        display: flex;
        align-items: center;
        gap: 9px;
        transition: background 0.15s, color 0.15s;
    }
    .main-header .dropdown-item:hover {
        background: #f0eef9;
        color: #605ca8;
    }
    .main-header .dropdown-item i {
        font-size: 13px;
        width: 16px;
        text-align: center;
        color: #9d99cc;
    }
    .main-header .dropdown-item:hover i {
        color: #605ca8;
    }

    /* Logout item */
    .main-header .dropdown-item.text-danger {
        color: #e03131;
    }
    .main-header .dropdown-item.text-danger:hover {
        background: #fff5f5;
        color: #c92a2a;
    }
    .main-header .dropdown-item.text-danger i {
        color: #e03131;
    }
</style>

<nav class="main-header navbar navbar-expand">
    <!-- Left: hamburger -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a id="toggle-sidebar" class="nav-link text-light" data-widget="pushmenu" href="#" role="button">
                <i class="fas fa-bars"></i>
            </a>
        </li>
    </ul>

    <!-- Right: user menu -->
    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
                <span>
                    <?php if (Auth::user()) { echo Auth::user()->name; } ?>
                </span>
                <i class="fas fa-chevron-down" style="font-size:10px;opacity:.6;margin-left:2px;"></i>
            </a>
            <div class="dropdown-menu dropdown-menu-right">
                <span class="dropdown-header">
                    <i class="far fa-user-circle"></i>
                    <?php if (Auth::user()) { echo Auth::user()->name; } ?>
                </span>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item">
                    <i class="fas fa-cog"></i> Setting
                </a>
                <div class="dropdown-divider"></div>
                <a class="dropdown-item text-danger" href="{{ route('logout') }}"
                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
            </div>
        </li>
    </ul>
</nav>