<nav class="main-header navbar navbar-expand bg-primary">
    <!-- Left navbar links -->
    <ul class="navbar-nav">
        <li class="nav-item">
            <a id="toggle-sidebar" class="nav-link ms-3 text-light" data-widget="pushmenu" href="#" role="button"><i class="fas fa-bars"></i></a>
        </li>
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
            <a class="nav-link text-light" data-toggle="dropdown" href="#">
                <i class="far fa-user-circle"></i>
                <span>{{ Auth::user()->name }}</span>
            </a>
            <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right">
                <span class="dropdown-header"><i class="far fa-user-circle"></i> {{ Auth::user()->name }}</span>
                {{-- @foreach (Auth::user()->getAllPermissions() as $permission)
                    <div class="dropdown-divider"></div>
                    <span class="ms-1">
                        - {{ $permission->name }}
                    </span>
                @endforeach --}}
                <div class="dropdown-divider"></div>
                <a href="#" class="dropdown-item dropdown-footer"><i class="fas fa-cog"></i> Setting</a>                
                <!-- <a href="#" class="">Log Out</a> -->                
                <a class="dropdown-item dropdown-footer" href="{{ route('logout') }}" onclick="event.preventDefault();
                    document.getElementById('logout-form').submit();">
                    <i class="fas fa-sign-out-alt"></i> 
                    Log Out
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" class="d-none">@csrf</form>
                </div>
            </div>
        </li>
    </ul>
</nav>
