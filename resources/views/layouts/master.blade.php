<!doctype html >
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" data-layout="vertical" data-layout-style="detached" data-sidebar="light" data-topbar="dark" data-sidebar-size="lg" data-sidebar-image="none"  data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    @include('layouts.head-css')
    <style>
        #customer-table_filter{
            float: right;
            margin-top: 19px;
        }
        .pagination{
            float: right;
        }
        /* Custom DataTables layout */
        .top-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 1rem;
        }

        .left-length {
            flex: 1;
        }

        .center-buttons {
            flex: 2;
            text-align: center;
        }

        .right-search {
            flex: 1;
            text-align: right;
        }

        .bottom-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 1rem;
        }

        .dataTables_filter input {
            border: 1px solid #ddd;
            border-radius: 4px;
            padding: 6px 12px;
            width: 250px;
        }

        .dt-buttons {
            margin: 0 10px;
        }

        .dt-buttons .btn {
            margin: 0 3px;
        }

        /* DataTables Sorting Icons Fix - using Remix Icons instead of tabler-icons */
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:before,
        table.dataTable thead .sorting_desc_disabled:after {
            font-family: 'remixicon' !important;
            font-size: 0.75rem !important;
            line-height: 1 !important;
        }

        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting_asc:before,
        table.dataTable thead .sorting_desc:before,
        table.dataTable thead .sorting_asc_disabled:before,
        table.dataTable thead .sorting_desc_disabled:before {
            content: "\ea78" !important; /* ri-arrow-up-s-line */
            top: 50% !important;
            transform: translateY(-100%);
        }

        table.dataTable thead .sorting:after,
        table.dataTable thead .sorting_asc:after,
        table.dataTable thead .sorting_desc:after,
        table.dataTable thead .sorting_asc_disabled:after,
        table.dataTable thead .sorting_desc_disabled:after {
            content: "\ea4e" !important; /* ri-arrow-down-s-line */
            bottom: 50% !important;
            transform: translateY(100%);
            top: auto !important;
        }

        /* Keep opacity for inactive states */
        table.dataTable thead .sorting:before,
        table.dataTable thead .sorting:after {
            opacity: 0.3;
        }

        /* Highlight active sort direction */
        table.dataTable thead .sorting_asc:before {
            opacity: 1;
        }
        table.dataTable thead .sorting_asc:after {
            opacity: 0.3;
        }
        table.dataTable thead .sorting_desc:before {
            opacity: 0.3;
        }
        table.dataTable thead .sorting_desc:after {
            opacity: 1;
        }
    </style>

</head>

@section('body')
    @include('layouts.body')
@show
    <!-- Begin page -->
    <div id="layout-wrapper">
        @if(app('impersonate')->isImpersonating())
            <div class="alert alert-warning mb-0 rounded-0 text-center" role="alert" style="z-index: 9999; position: relative;">
                You are currently impersonating <strong>{{ Auth::user()->name }}</strong>.
{{--                <a href="{{ route('impersonate.leave') }}" class="alert-link ms-2">Leave Impersonation</a>--}}
            </div>
        @endif
        @include('layouts.topbar')
        @include('layouts.sidebar')
        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->
            @include('layouts.footer')
        </div>
        <!-- end main content-->
    </div>
    <!-- END layout-wrapper -->

    @include('layouts.customizer')

    <!-- JAVASCRIPT -->
    @include('layouts.vendor-scripts')
</body>

</html>
