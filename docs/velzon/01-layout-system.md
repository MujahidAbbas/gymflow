# Layout System

The Velzon template uses a modular layout architecture with configurable components. This document covers the complete layout structure, including the master template, topbar navigation, sidebar menu, footer, and customizer.

---

## Table of Contents

- [Master Layout](#master-layout)
- [Layout Data Attributes](#layout-data-attributes)
- [Topbar (Header)](#topbar-header)
- [Sidebar Navigation](#sidebar-navigation)
- [Main Content Area](#main-content-area)
- [Footer](#footer)
- [Theme Customizer](#theme-customizer)
- [Layout Variants](#layout-variants)

---

## Master Layout

The master layout (`resources/views/velzon/layouts/master.blade.php`) serves as the foundation for all pages in the application.

### Basic Structure

```blade
<!doctype html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}" 
      data-layout="vertical" 
      data-layout-style="detached" 
      data-sidebar="light" 
      data-topbar="dark" 
      data-sidebar-size="lg" 
      data-sidebar-image="none"  
      data-preloader="disable">

<head>
    <meta charset="utf-8" />
    <title>@yield('title') | Velzon - Admin & Dashboard Template</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Themesbrand" name="author" />
    <link rel="shortcut icon" href="{{ URL::asset('build/images/favicon.ico')}}">
    @include('layouts.head-css')
</head>

@section('body')
    @include('layouts.body')
@show
    <div id="layout-wrapper">
        @include('layouts.topbar')
        @include('layouts.sidebar')
        
        <!-- Main Content -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>
            @include('layouts.footer')
        </div>
    </div>

    @include('layouts.customizer')
    @include('layouts.vendor-scripts')
</body>
</html>
```

### Template Sections

The master layout provides several extension points:

| Section | Description | Usage |
|---------|-------------|-------|
| `@yield('title')` | Page title | Displayed in browser tab |
| `@yield('css')` | Page-specific CSS | Additional stylesheets |
| `@yield('content')` | Main content | Primary page content |
| `@yield('script')` | Page-specific JS | Additional scripts |

### Using the Master Layout

```blade
@extends('layouts.master')

@section('title') Dashboard @endsection

@section('css')
    <link href="{{ URL::asset('build/libs/custom.css') }}" rel="stylesheet" />
@endsection

@section('content')
    <div class="row">
        <!-- Your page content -->
    </div>
@endsection

@section('script')
    <script src="{{ URL::asset('build/js/custom.js') }}"></script>
@endsection
```

---

## Layout Data Attributes

The `<html>` tag supports various `data-*` attributes to control layout behavior:

### Core Layout Attributes

```html
<html 
    data-layout="vertical"          <!-- vertical | horizontal | twocolumn | semibox -->
    data-layout-style="detached"    <!-- default | detached | compact -->
    data-sidebar="light"            <!-- light | dark | gradient | gradient-2 | gradient-3 | gradient-4 -->
    data-topbar="dark"              <!-- light | dark -->
    data-sidebar-size="lg"          <!-- lg | md | sm | sm-hover -->
    data-sidebar-image="none"       <!-- none | img-1 | img-2 | img-3 | img-4 -->
    data-preloader="disable"        <!-- enable | disable -->
    data-bs-theme="light"           <!-- light | dark -->
>
```

### Attribute Reference

| Attribute | Values | Description |
|-----------|--------|-------------|
| `data-layout` | `vertical`, `horizontal`, `twocolumn`, `semibox` | Main layout orientation |
| `data-layout-style` | `default`, `detached`, `compact` | Layout variant style |
| `data-sidebar` | `light`, `dark`, `gradient`, `gradient-2`, `gradient-3`, `gradient-4` | Sidebar color scheme |
| `data-topbar` | `light`, `dark` | Topbar color theme |
| `data-sidebar-size` | `lg`, `md`, `sm`, `sm-hover` | Sidebar width |
| `data-sidebar-image` | `none`, `img-1`, `img-2`, `img-3`, `img-4` | Background image for sidebar |
| `data-preloader` | `enable`, `disable` | Page preloader |
| `data-bs-theme` | `light`, `dark` | Bootstrap color mode |

---

## Topbar (Header)

The topbar (`resources/views/velzon/layouts/topbar.blade.php`) contains navigation, search, notifications, and user profile.

### Topbar Structure

```blade
<header id="page-topbar">
    <div class="layout-width">
        <div class="navbar-header">
            <!-- Left Side: Logo & Hamburger -->
            <div class="d-flex">
                <div class="navbar-brand-box horizontal-logo">
                    <a href="index" class="logo logo-dark">
                        <span class="logo-sm">
                            <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
                        </span>
                        <span class="logo-lg">
                            <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
                        </span>
                    </a>
                </div>

                <!-- Hamburger Menu -->
                <button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger">
                    <span class="hamburger-icon">
                        <span></span>
                        <span></span>
                        <span></span>
                    </span>
                </button>

                <!-- Search Bar -->
                <form class="app-search d-none d-md-block">
                    <div class="position-relative">
                        <input type="text" class="form-control" placeholder="Search..." autocomplete="off">
                        <span class="mdi mdi-magnify search-widget-icon"></span>
                    </div>
                </form>
            </div>

            <!-- Right Side: Actions & Profile -->
            <div class="d-flex align-items-center">
                <!-- Language Dropdown -->
                <div class="dropdown ms-1 topbar-head-dropdown header-item">
                    <!-- Language selector -->
                </div>

                <!-- Apps Dropdown -->
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <!-- Apps grid -->
                </div>

                <!-- Notifications -->
                <div class="dropdown topbar-head-dropdown ms-1 header-item">
                    <!-- Notification bell -->
                </div>

                <!-- User Profile -->
                <div class="dropdown ms-sm-3 header-item topbar-user">
                    <button type="button" class="btn" id="page-header-user-dropdown" 
                            data-bs-toggle="dropdown">
                        <span class="d-flex align-items-center">
                            <img class="rounded-circle header-profile-user" 
                                 src="{{ URL::asset('build/images/users/avatar-1.jpg') }}" 
                                 alt="Header Avatar">
                            <span class="text-start ms-xl-2">
                                <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">
                                    {{Auth::user()->name}}
                                </span>
                                <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">
                                    Founder
                                </span>
                            </span>
                        </span>
                    </button>
                    <div class="dropdown-menu dropdown-menu-end">
                        <a class="dropdown-item" href="pages-profile">
                            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> 
                            <span class="align-middle">Profile</span>
                        </a>
                        <!-- More menu items -->
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
```

### Topbar Components

#### Search Bar

```blade
<form class="app-search d-none d-md-block">
    <div class="position-relative">
        <input type="text" class="form-control" placeholder="Search..." 
               autocomplete="off" id="search-options" value="">
        <span class="mdi mdi-magnify search-widget-icon"></span>
        <span class="mdi mdi-close-circle search-widget-icon search-widget-icon-close d-none" 
              id="search-close-options"></span>
    </div>
</form>
```

#### Notification Dropdown

```blade
<div class="dropdown topbar-head-dropdown ms-1 header-item" id="notificationDropdown">
    <button type="button" class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle">
        <i class='bx bx-bell fs-22'></i>
        <span class="position-absolute topbar-badge fs-10 translate-middle badge rounded-pill bg-danger">
            3
            <span class="visually-hidden">unread messages</span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-lg dropdown-menu-end">
        <!-- Notification items -->
    </div>
</div>
```

#### User Profile Dropdown

```blade
<div class="dropdown ms-sm-3 header-item topbar-user">
    <button type="button" class="btn" data-bs-toggle="dropdown">
        <span class="d-flex align-items-center">
            <img class="rounded-circle header-profile-user" 
                 src="@if (Auth::user()->avatar != ''){{ URL::asset('images/' . Auth::user()->avatar) }}
                      @else{{ URL::asset('build/images/users/avatar-1.jpg') }}@endif" 
                 alt="Header Avatar">
            <span class="text-start ms-xl-2">
                <span class="d-none d-xl-inline-block ms-1 fw-semibold user-name-text">
                    {{Auth::user()->name}}
                </span>
                <span class="d-none d-xl-block ms-1 fs-13 user-name-sub-text">Founder</span>
            </span>
        </span>
    </button>
    <div class="dropdown-menu dropdown-menu-end">
        <h6 class="dropdown-header">Welcome!</h6>
        <a class="dropdown-item" href="pages-profile">
            <i class="mdi mdi-account-circle text-muted fs-16 align-middle me-1"></i> 
            <span class="align-middle">Profile</span>
        </a>
        <!-- More items -->
    </div>
</div>
```

### Topbar Utility Classes

| Class | Purpose |
|-------|---------|
| `.header-item` | Common header item styling |
| `.topbar-head-dropdown` | Dropdown in topbar |
| `.topbar-badge` | Badge positioning in topbar |
| `.topbar-user` | User profile section |
| `.vertical-menu-btn` | Hamburger menu button |

---

## Sidebar Navigation

The sidebar (`resources/views/velzon/layouts/sidebar.blade.php`) provides the main navigation menu.

### Sidebar Structure

```blade
<div class="app-menu navbar-menu">
    <!-- Logo -->
    <div class="navbar-brand-box">
        <a href="index" class="logo logo-dark">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-dark.png') }}" alt="" height="17">
            </span>
        </a>
        <a href="index" class="logo logo-light">
            <span class="logo-sm">
                <img src="{{ URL::asset('build/images/logo-sm.png') }}" alt="" height="22">
            </span>
            <span class="logo-lg">
                <img src="{{ URL::asset('build/images/logo-light.png') }}" alt="" height="17">
            </span>
        </a>
        <button type="button" class="btn btn-sm p-0 fs-20 header-item float-end btn-vertical-sm-hover">
            <i class="ri-record-circle-line"></i>
        </button>
    </div>

    <!-- Navigation -->
    <div id="scrollbar">
        <div class="container-fluid">
            <ul class="navbar-nav" id="navbar-nav">
                <li class="menu-title"><span>@lang('translation.menu')</span></li>
                
                <!-- Dashboard Menu -->
                <li class="nav-item">
                    <a class="nav-link menu-link" href="#sidebarDashboards" 
                       data-bs-toggle="collapse" role="button" aria-expanded="false">
                        <i class="ri-dashboard-2-line"></i> 
                        <span>@lang('translation.dashboards')</span>
                    </a>
                    <div class="collapse menu-dropdown" id="sidebarDashboards">
                        <ul class="nav nav-sm flex-column">
                            <li class="nav-item">
                                <a href="dashboard-analytics" class="nav-link">
                                    @lang('translation.analytics')
                                </a>
                            </li>
                            <li class="nav-item">
                                <a href="dashboard-crm" class="nav-link">
                                    @lang('translation.crm')
                                </a>
                            </li>
                        </ul>
                    </div>
                </li>

                <!-- More menu items -->
            </ul>
        </div>
    </div>
</div>
```

### Menu Item Types

#### Simple Link

```blade
<li class="nav-item">
    <a href="widgets" class="nav-link menu-link">
        <i class="ri-honour-line"></i> 
        <span>@lang('translation.widgets')</span>
    </a>
</li>
```

#### Collapsible Menu

```blade
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarApps" 
       data-bs-toggle="collapse" role="button" aria-expanded="false">
        <i class="ri-apps-2-line"></i> 
        <span>@lang('translation.apps')</span>
    </a>
    <div class="collapse menu-dropdown" id="sidebarApps">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="apps-chat" class="nav-link">@lang('translation.chat')</a>
            </li>
            <!-- More sub-items -->
        </ul>
    </div>
</li>
```

#### Nested Collapsible Menu

```blade
<li class="nav-item">
    <a href="#sidebarEmail" class="nav-link" data-bs-toggle="collapse">
        @lang('translation.email')
    </a>
    <div class="collapse menu-dropdown" id="sidebarEmail">
        <ul class="nav nav-sm flex-column">
            <li class="nav-item">
                <a href="#sidebaremailTemplates" class="nav-link" data-bs-toggle="collapse">
                    @lang('translation.email-templates')
                </a>
                <div class="collapse menu-dropdown" id="sidebaremailTemplates">
                    <ul class="nav nav-sm flex-column">
                        <li class="nav-item">
                            <a href="apps-email-basic" class="nav-link">
                                @lang('translation.basic-action')
                            </a>
                        </li>
                    </ul>
                </div>
            </li>
        </ul>
    </div>
</li>
```

#### Menu Title

```blade
<li class="menu-title">
    <i class="ri-more-fill"></i> 
    <span>@lang('translation.pages')</span>
</li>
```

#### Menu with Badge

```blade
<li class="nav-item">
    <a class="nav-link menu-link" href="#sidebarLayouts" data-bs-toggle="collapse">
        <i class="ri-layout-3-line"></i> 
        <span>@lang('translation.layouts')</span>
        <span class="badge badge-pill bg-danger">@lang('translation.hot')</span>
    </a>
    <!-- Sub-menu -->
</li>
```

### Sidebar Classes

| Class | Purpose |
|-------|---------|
| `.app-menu` | Main sidebar container |
| `.navbar-menu` | Menu wrapper |
| `.navbar-brand-box` | Logo container |
| `.menu-link` | Main menu item link |
| `.menu-dropdown` | Collapsible dropdown |
| `.menu-title` | Section title |
| `.nav-sm` | Small navigation (sub-menu) |

---

## Main Content Area

The main content area is where page-specific content is rendered.

### Content Structure

```blade
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
<!-- end main content -->
```

### Page Content Example

```blade
@extends('layouts.master')

@section('title') Dashboard @endsection

@section('content')
    <!-- Breadcrumb -->
    @component('components.breadcrumb')
        @slot('li_1') Dashboards @endslot
        @slot('title') Dashboard @endslot
    @endcomponent

    <!-- Page Content -->
    <div class="row">
        <div class="col-xl-12">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title mb-0">Welcome</h4>
                </div>
                <div class="card-body">
                    <!-- Your content -->
                </div>
            </div>
        </div>
    </div>
@endsection
```

---

## Footer

The footer (`resources/views/velzon/layouts/footer.blade.php`) displays copyright and credits.

### Footer Structure

```blade
<footer class="footer">
    <div class="container-fluid">
        <div class="row">
            <div class="col-sm-6">
                <script>document.write(new Date().getFullYear())</script> © Velzon.
            </div>
            <div class="col-sm-6">
                <div class="text-sm-end d-none d-sm-block">
                    Design & Develop by Themesbrand
                </div>
            </div>
        </div>
    </div>
</footer>
```

---

## Theme Customizer

The customizer allows users to configure layout settings on the fly.

### Customizer Location

```blade
@include('layouts.customizer')
```

The customizer is typically placed before the closing `</body>` tag and provides controls for:

- Layout type (Vertical, Horizontal, Two Column, Semi Box)
- Color scheme (Light, Dark)
- Layout width (Fluid, Boxed)
- Sidebar size (Large, Medium, Small)
- Sidebar color
- Topbar color
- Sidebar image

### Customizer Trigger

```blade
<button class="btn btn-icon btn-topbar btn-ghost-secondary rounded-circle" 
        data-bs-toggle="offcanvas" data-bs-target="#theme-settings-offcanvas">
    <i class='bx bx-cog fs-22'></i>
</button>
```

---

## Layout Variants

Velzon supports multiple layout configurations:

### 1. Vertical Layout (Default)

```html
<html data-layout="vertical">
```

- Sidebar on the left
- Topbar at the top
- Most common layout

### 2. Horizontal Layout

```html
<html data-layout="horizontal">
```

- Top navigation bar
- No sidebar
- Suitable for applications with fewer menu items

### 3. Two Column Layout

```html
<html data-layout="twocolumn">
```

- Icon sidebar on the left
- Expanded menu opens on hover/click
- Space-efficient design

### 4. Semi Box Layout

```html
<html data-layout="semibox">
```

- Boxed container with sidebar
- Modern, contained design

### Layout Styles

#### Detached

```html
<html data-layout-style="detached">
```

Header and content are separated from sidebar.

#### Compact

```html
<html data-layout-style="compact">
```

Reduced padding and margins for denser UI.

---

## Responsive Behavior

### Breakpoints

The layout is responsive and adapts to different screen sizes:

| Breakpoint | Screen Width | Behavior |
|------------|--------------|----------|
| `xs` | < 576px | Mobile sidebar collapsed by default |
| `sm` | ≥ 576px | Small devices |
| `md` | ≥ 768px | Tablets - search bar appears |
| `lg` | ≥ 992px | Desktop - full sidebar |
| `xl` | ≥ 1200px | Large desktop |
| `xxl` | ≥ 1400px | Extra large screens |

### Mobile Menu

On mobile devices, the sidebar becomes a slide-in drawer:

```blade
<button type="button" class="btn btn-sm px-3 fs-16 header-item vertical-menu-btn topnav-hamburger">
    <span class="hamburger-icon">
        <span></span>
        <span></span>
        <span></span>
    </span>
</button>
```

---

## Best Practices

### Layout Configuration

1. **Set layout attributes in master template** - Configure default layout via `data-*` attributes on the `<html>` tag
2. **Use the customizer for testing** - Let users test different configurations
3. **Maintain consistent navigation** - Keep menu structure logical and organized
4. **Leverage menu titles** - Group related items under descriptive titles

### Performance

1. **Lazy load dropdowns** - Use Bootstrap's collapse for better initial load
2. **Optimize images** - Compress logo and sidebar images
3. **Minimize includes** - Only include necessary layout components

### Accessibility

1. **Use semantic HTML** - Proper `<header>`, `<nav>`, `<main>`, `<footer>` elements
2. **Add ARIA attributes** - `aria-expanded`, `aria-controls` for collapsible menus
3. **Keyboard navigation** - Ensure all menu items are keyboard accessible
4. **Focus indicators** - Maintain visible focus states

### Customization

1. **Use SCSS variables** - Customize colors, spacing via variables
2. **Override carefully** - Create custom CSS files rather than modifying core files
3. **Test all variants** - Ensure customizations work across all layout types

---

## Related Documentation

- [Overview & Fundamentals](00-overview.md) - Color system, typography, spacing
- [UI Components](02-ui-components.md) - Buttons, alerts, badges, cards, modals
- [Form Components](03-form-components.md) - Form elements and layouts
- [SCSS Architecture](10-scss-architecture.md) - Styling and theming

---

## Summary

The Velzon layout system provides:

- **Flexible master template** with multiple extension points
- **Configurable layout** via data attributes
- **Feature-rich topbar** with search, notifications, and user profile
- **Hierarchical sidebar** with collapsible menus
- **Responsive design** that works across all devices
- **Theme customizer** for live configuration
- **Multiple layout variants** (vertical, horizontal, two-column, semi-box)

By understanding these layout components, you can create consistent, professional interfaces that leverage all of Velzon's layout capabilities.
