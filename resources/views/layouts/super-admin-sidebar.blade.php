<!-- ========== Super Admin App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="{{ route('super-admin.dashboard') }}" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="{{ route('super-admin.dashboard') }}" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover"
            id="vertical-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <div id="scrollbar">
        <div class="container-fluid">

            <div id="two-column-menu">
            </div>
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                
                {{-- Dashboard --}}
                <li class="nav-item">
                    <a href="{{ route('super-admin.dashboard') }}" class="nav-link menu-link @if(Route::is('super-admin.dashboard')) active @endif">
                        <i class="ri-dashboard-2-line"></i> 
                        <span>Platform Dashboard</span>
                    </a>
                </li>

                {{-- Customer Management --}}
                <li class="nav-item">
                    <a href="{{ route('super-admin.customers.index') }}" class="nav-link menu-link @if(Route::is('super-admin.customers.*')) active @endif">
                        <i class="ri-building-line"></i> 
                        <span>Customers</span>
                    </a>
                </li>

                {{-- Contact Management --}}
                <li class="nav-item">
                    <a href="{{ route('contacts.index') }}" class="nav-link menu-link @if(Route::is('contacts.*')) active @endif">
                        <i class="ri-mail-line"></i> 
                        <span>Contact</span>
                    </a>
                </li>

                {{-- Notice Board --}}
                <li class="nav-item">
                    <a href="{{ route('notice-boards.index') }}" class="nav-link menu-link @if(Route::is('notice-boards.*')) active @endif">
                        <i class="ri-notification-3-line"></i> 
                        <span>Notice Board</span>
                    </a>
                </li>

                {{-- Platform Analytics --}}
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarAnalytics" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarAnalytics">
                        <i class="ri-bar-chart-line"></i> 
                        <span>Analytics</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarAnalytics">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('super-admin.analytics.revenue') }}" class="nav-link">Revenue</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('super-admin.analytics.customers') }}" class="nav-link">Customer Growth</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

                {{-- Subscriptions --}}
                {{-- <li class="nav-item">
                    <a href="{{ route('super-admin.subscriptions.index') }}" class="nav-link menu-link">
                        <i class="ri-bank-card-line"></i> 
                        <span>Subscriptions</span>
                    </a>
                </li> --}}

                {{-- Pricing / Platform Subscriptions --}}
                <li class="nav-item">
                    <a href="{{ route('super-admin.platform-subscriptions.index') }}" class="nav-link menu-link @if(Route::is('super-admin.platform-subscriptions.*')) active @endif">
                        <i class="ri-price-tag-3-line"></i> 
                        <span>Pricing</span>
                    </a>
                </li>

                {{-- Platform Settings --}}
                <li class="menu-title"><span>Platform Management</span></li>

                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link menu-link @if(Route::is('settings.*')) active @endif">
                        <i class="ri-settings-3-line"></i> 
                        <span>Platform Settings</span>
                    </a>
                </li>

                {{-- System Monitoring --}}
                {{-- <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarMonitoring" data-bs-toggle="collapse" role="button" aria-expanded="false" aria-controls="sidebarMonitoring">
                        <i class="ri-shield-check-line"></i> 
                        <span>System</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarMonitoring">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="#" class="nav-link">Audit Logs</a>
                            </li>
                            <li class="nav-item">
                                <a href="#" class="nav-link">System Health</a>
                            </li>
                        </ul>
                    </div>
                </li> --}}

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
