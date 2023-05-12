<nav class="navbar navbar-expand navbar-black navbar-dark" style="padding: 0 !important">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <a href="#" class="brand-link bg-dark" style="text-decoration:none;">

            <img src="{{ url('img/bridgesmall.png') }}" alt="Bridge for Vendor logo" class="brand-image">

            <span class="brand-text">BridgeforVendor</span>
        </a>
    </ul>

    <!-- SEARCH FORM -->
    <div class="form-inline ml-3">
        {{-- <div class="input-group input-group-sm">
            <input class="form-control form-control-navbar" type="search" placeholder="Search" aria-label="Search">
            <div class="input-group-append">
                <button class="btn btn-navbar">
                    <i class="fas fa-search"></i>
                </button>
            </div>
        </div> --}}

        {{-- label Dashboard --}}
        <div class="input-group input-group-sm">
            {{-- <label class="form-control form-control-navbar" id="label-dashboard">Dashboard</label> --}}
        </div>

    </div>

    <ul class="navbar-nav ml-auto">
        <li class="nav-item dropdown">
            <a class="nav-link" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
                <span id="hi-user">{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header">{{ Auth::user()->name }}</span>
                @foreach (Auth::user()->getAllPermissions() as $permission)
                    <div class="dropdown-divider"></div>
                    <span class="ms-1">
                        - {{ $permission->name }}
                    </span>
                @endforeach
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Setting</a>
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer">Log Out</a>
            </div>
        </li>
    </ul>
</nav>
