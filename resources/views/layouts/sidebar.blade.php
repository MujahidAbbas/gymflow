@role('super-admin')
    @include('layouts.super-admin-sidebar')
@else
<!-- ========== App Menu ========== -->
<div class="app-menu navbar-menu">
    <!-- LOGO -->
    <div class="navbar-brand-box">
        <!-- Dark Logo-->
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <!-- Light Logo-->
        <a href="index" class="logo logo-light">
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
                    <a href="{{ route('dashboard') }}" class="nav-link menu-link @if(Route::is('dashboard')) active @endif">
                        <i class="ri-dashboard-2-line"></i> 
                        <span>Dashboard</span>
                    </a>
                </li>

                {{-- User Management --}}
                @canany(['view users', 'view roles'])
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('users.*')) active @endif" href="#sidebarUser" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('users.*') ? 'true' : 'false' }}" aria-controls="sidebarUser">
                        <i class="ri-user-settings-line"></i> 
                        <span>User Management</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('users.*')) show @endif" id="sidebarUser">
                        <ul class="nav nav-sm flex-column">
                            @can('view users')
                            <li class="nav-item">
                                <a href="{{ route('users.index') }}" class="nav-link @if(Route::is('users.*')) active @endif">Users</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany

                {{-- Member Management --}}
                @canany(['view members', 'view plans'])
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('members.*') || Route::is('membership-plans.*')) active @endif" href="#sidebarMembers" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('members.*') || Route::is('membership-plans.*') ? 'true' : 'false' }}" aria-controls="sidebarMembers">
                        <i class="ri-group-line"></i> 
                        <span>Members</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('members.*') || Route::is('membership-plans.*')) show @endif" id="sidebarMembers">
                        <ul class="nav nav-sm flex-column">
                            @can('view members')
                            <li class="nav-item">
                                <a href="{{ route('members.index') }}" class="nav-link @if(Route::is('members.*')) active @endif">All Members</a>
                            </li>
                            @endcan
                            @can('view plans')
                            <li class="nav-item">
                                <a href="{{ route('membership-plans.index') }}" class="nav-link @if(Route::is('membership-plans.*')) active @endif">Membership Plans</a>
                            </li>
                            @endcan
                        </ul>
                    </div>
                </li>
                @endcanany

                {{-- Trainers --}}
                @can('view trainers')
                <li class="nav-item">
                    <a href="{{ route('trainers.index') }}" class="nav-link menu-link @if(Route::is('trainers.*')) active @endif">
                        <i class="ri-user-star-line"></i> 
                        <span>Trainers</span>
                    </a>
                </li>
                @endcan

                {{-- Classes & Scheduling --}}
                @can('view classes')
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('gym-classes.*') || Route::is('categories.*')) active @endif" href="#sidebarClasses" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('gym-classes.*') || Route::is('categories.*') ? 'true' : 'false' }}" aria-controls="sidebarClasses">
                        <i class="ri-calendar-event-line"></i> 
                        <span>Classes & Schedule</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('gym-classes.*') || Route::is('categories.*')) show @endif" id="sidebarClasses">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('gym-classes.index') }}" class="nav-link @if(Route::is('gym-classes.*')) active @endif">Classes</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('categories.index') }}" class="nav-link @if(Route::is('categories.*')) active @endif">Categories</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Workouts --}}
                @can('view members')
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('workouts.*') || Route::is('healths.*')) active @endif" href="#sidebarWorkouts" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('workouts.*') || Route::is('healths.*') ? 'true' : 'false' }}" aria-controls="sidebarWorkouts">
                        <i class="ri-fitness-line"></i> 
                        <span>Workouts</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('workouts.*') || Route::is('healths.*')) show @endif" id="sidebarWorkouts">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('workouts.index') }}" class="nav-link @if(Route::is('workouts.index')) active @endif">All Workouts</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('workouts.create') }}" class="nav-link @if(Route::is('workouts.create')) active @endif">Assign Workout</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('healths.index') }}" class="nav-link @if(Route::is('healths.*')) active @endif">Health Tracking</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Attendance --}}
                @can('view attendance')
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('attendances.*')) active @endif" href="#sidebarAttendance" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('attendances.*') ? 'true' : 'false' }}" aria-controls="sidebarAttendance">
                        <i class="ri-fingerprint-line"></i> 
                        <span>Attendance</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('attendances.*')) show @endif" id="sidebarAttendance">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('attendances.index') }}" class="nav-link @if(Route::is('attendances.index')) active @endif">Daily Attendance</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('attendances.report') }}" class="nav-link @if(Route::is('attendances.report')) active @endif">Attendance Report</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Financial --}}
                @can('view payments')
                <li class="nav-item">
                    <a class="nav-link menu-link @if(Route::is('invoices.*') || Route::is('expenses.*') || Route::is('types.*')) active @endif" href="#sidebarFinancial" data-bs-toggle="collapse" role="button" aria-expanded="{{ Route::is('invoices.*') || Route::is('expenses.*') || Route::is('types.*') ? 'true' : 'false' }}" aria-controls="sidebarFinancial">
                        <i class="ri-money-dollar-circle-line"></i> 
                        <span>Financial</span>
                    </a>
                    <div class="collapse menu-dropdown @if(Route::is('invoices.*') || Route::is('expenses.*') || Route::is('types.*')) show @endif" id="sidebarFinancial">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="{{ route('invoices.index') }}" class="nav-link @if(Route::is('invoices.*')) active @endif">Invoices</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('expenses.index') }}" class="nav-link @if(Route::is('expenses.*')) active @endif">Expenses</a>
                            </li>
                            <li class="nav-item">
                                <a href="{{ route('types.index') }}" class="nav-link @if(Route::is('types.*')) active @endif">Types & Categories</a>
                            </li>
                        </ul>
                    </div>
                </li>
                @endcan

                {{-- Products & Inventory --}}
                <li class="nav-item">
                    <a href="{{ route('products.index') }}" class="nav-link menu-link @if(Route::is('products.*')) active @endif">
                        <i class="ri-store-2-line"></i> 
                        <span>Products</span>
                    </a>
                </li>

                {{-- Contact Inquiries --}}
                <li class="nav-item">
                    <a href="{{ route('contacts.index') }}" class="nav-link menu-link @if(Route::is('contacts.*')) active @endif">
                        <i class="ri-mail-line"></i> 
                        <span>Contact Inquiries</span>
                    </a>
                </li>

                {{-- Support Tickets --}}
                <li class="nav-item">
                    <a href="{{ route('support-tickets.index') }}" class="nav-link menu-link @if(Route::is('support-tickets.*')) active @endif">
                        <i class="ri-customer-service-2-line"></i> 
                        <span>Support Tickets</span>
                    </a>
                </li>

                {{-- Settings --}}
                @can('manage settings')
                <li class="nav-item">
                    <a href="{{ route('settings.index') }}" class="nav-link menu-link @if(Route::is('settings.*')) active @endif">
                        <i class="ri-settings-3-line"></i> 
                        <span>Settings</span>
                    </a>
                </li>
                @endcan

                {{-- Documentation --}}
                <li class="nav-item">
                    <a href="{{ url('docs') }}" class="nav-link menu-link @if(request()->is('docs*')) active @endif">
                        <i class="ri-book-2-line"></i> 
                        <span>Documentation</span>
                    </a>
                </li>

            </ul>
        </div>
        <!-- Sidebar -->
    </div>
    <div class="sidebar-background"></div>
</div>
<!-- Left Sidebar End -->
<!-- Vertical Overlay-->
<div class="vertical-overlay"></div>
@endrole
