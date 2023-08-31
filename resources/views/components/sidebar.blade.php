<aside class="main-sidebar sidebar-light-primary elevation-2 control-sidebar-push">

    <a href="#" class="brand-link bg-primary" style="text-decoration:none;">

        <img src="{{ url('img/bridgesmall.png') }}" alt="Bridge for Vendor logo" class="brand-image">

        <span class="brand-text">BridgeforVendor</span>
    </a>

    <div class="mt-3 sidebar os-host os-theme-light os-host-resize-disabled os-host-transition">
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

                    <nav class="mt-2">
                        <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">
                            <li class="nav-item" id="side_dashboard">
                                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                                    <i class="nav-icon fas fa-tachometer-alt"></i>
                                    <p>
                                        Dashboard
                                    </p>
                                </a>
                            </li>

                            {{-- @ spatie permission view_vfi --}}
                            @can('view vfi')
                            <li class="nav-item" id="side_vfi">
                                <a href="{{ route('admin.vfi.index') }}" class="nav-link">
                                    <i class="nav-icon fas fa-copy"></i>
                                    <p>
                                        VFI
                                        {{-- <i class="fas fa-angle-left right"></i> --}}
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                {{-- <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.vfi.index') }}" class="nav-link">
                                            <i class="fa fa-list nav-icon"></i>
                                            <p>Menu</p>
                                        </a>
                                    </li>
                                </ul> --}}
                            </li>
                            @endcan                            


                            @can('view stock')                                
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>
                                        Stock
                                    </p>
                                </a>
                            </li>
                            @endcan

                            @can('view molding')
                            <li class="nav-item">
                                <a href="{{ url('/index/workshop/check_molding_vendor') }}" class="nav-link">
                                    <i class="nav-icon fas fa-cubes"></i>
                                    <p>
                                        Molding
                                    </p>
                                </a>
                            </li>
                            @endcan
                            
                            @can('view logistic')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-truck"></i>
                                    <p>
                                        Logistic
                                    </p>
                                </a>
                            </li>                                
                            @endcan                            

                            @can('view users')
                            <li class="nav-item">
                                <a href="#" class="nav-link">
                                    <i class="nav-icon fas fa-users"></i>
                                    <p>
                                        User Management
                                        <i class="fas fa-angle-left right"></i>
                                        <span class="badge badge-info right"></span>
                                    </p>
                                </a>
                                <ul class="nav nav-treeview" style="display: none;">
                                    <li class="nav-item">
                                        <a href="{{ route('admin.user.index') }}" class="nav-link">
                                            <i class="fa fa-user nav-icon"></i>
                                            <p>User</p>
                                        </a>
                                    </li>
                                    <li class="nav-item">
                                        <a href="{{ route('admin.permission.index') }}" class="nav-link">
                                            <i class="fa fa-user-cog nav-icon"></i>
                                            <p>User Permission</p>
                                        </a>
                                    </li>
                                </ul>
                            </li>
                            @endcan



                            {{-- end of sidebar menu --}}
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
