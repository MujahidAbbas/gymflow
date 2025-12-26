# Frontend & UI Architecture
## FitHub SaaS - Complete Frontend Reference

**Version**: 1.0  
**Date**: 2025-11-28  
**Framework**: Laravel Blade + Tailwind CSS  
**Total Views**: 148+ Blade templates

---

## Overview

### Technology Stack

**Frontend Framework**:
- **Templating**: Laravel Blade
- **CSS Framework**: Tailwind CSS v3.0.18
- **JavaScript**: Alpine.js v3.4.2 + Vanilla JS
- **Build Tool**: Laravel Mix (Webpack)
- **Icons**: Font Awesome / SVG icons
- **HTTP Client**: Axios v0.25

**Dependencies** (package.json):
```json
{
  "@tailwindcss/forms": "^0.4.0",
  "alpinejs": "^3.4.2",
  "autoprefixer": "^10.4.2",
  "axios": "^0.25",
  "laravel-mix": "^6.0.6",
  "tailwindcss": "^3.0.18"
}
```

---

## 1. View Structure

### 1.1 Directory Organization

**Base Path**: `resources/views/`

**Total Modules**: 38 view directories

```
resources/views/
├── layouts/           # Master layouts (4 files)
├── auth/              # Authentication views (5 files)
├── dashboard/         # Dashboard views (4 files)
├── admin/             # Super admin views (5 files)
├── user/              # User management (4 files)
├── trainer/           # Trainer views (4 files)
├── trainee/           # Trainee views (4 files)
├── classes/           # Class management (5 files)
├── workout/           # Workout plans (5 files)
├── health_update/     # Health tracking (4 files)
├── attendance/        # Attendance (5 files)
├── invoice/           # Invoice (5 files)
├── expense/           # Expense (4 files)
├── locker/            # Locker management (6 files)
├── event/             # Events (4 files)
├── product/           # Products (4 files)
├── subscription/      # Subscriptions (5 files)
├── settings/          # Settings (2 files)
├── email/             # Email templates (5 files)
├── home_pages/        # Landing page CMS (2 files)
├── auth_page/         # Auth customization (1 file)
└── ... 18 more directories
```

### 1.2 Layout Architecture

**Master Layout Pattern**:
```blade
<!-- resources/views/layouts/app.blade.php -->
<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    
    <title>{{ config('app.name', 'FitHub') }}</title>
    
    <!-- Styles -->
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    
    @stack('styles')
</head>
<body>
    @include('layouts.navigation')
    
    <!-- Page Content -->
    <main>
        @yield('content')
    </main>
    
    <!-- Scripts -->
    <script src="{{ asset('js/app.js') }}"></script>
    @stack('scripts')
</body>
</html>
```

**Layout Files** (in `layouts/`):
1. `app.blade.php` - Main application layout
2. `guest.blade.php` - Guest/unauthenticated layout
3. `navigation.blade.php` - Sidebar/navbar component
4. `footer.blade.php` - Footer component

---

## 2. Tailwind CSS Implementation

### 2.1 Configuration

**File**: `tailwind.config.js` (should exist in project root)

**Purge/Content Paths**:
```javascript
module.exports = {
  content: [
    './resources/**/*.blade.php',
    './resources/**/*.js',
    './resources/**/*.vue',
  ],
  theme: {
    extend: {
      colors: {
        // Custom FitHub colors
      }
    },
  },
  plugins: [
    require('@tailwindcss/forms'),
  ],
}
```

### 2.2 CSS Entry Point

**File**: `resources/css/app.css`

```css
@tailwind base;
@tailwind components;
@tailwind utilities;

/* Custom styles */
@layer components {
  .btn-primary {
    @apply bg-blue-500 hover:bg-blue-700 text-white font-bold py-2 px-4 rounded;
  }
  
  .card {
    @apply bg-white shadow-md rounded px-8 pt-6 pb-8 mb-4;
  }
}
```

### 2.3 Build Process

**File**: `webpack.mix.js`

```javascript
const mix = require('laravel-mix');

mix.js('resources/js/app.js', 'public/js')
   .postCss('resources/css/app.css', 'public/css', [
       require('tailwindcss'),
       require('autoprefixer'),
   ]);
```

**Build Commands**:
```bash
# Development
npm run dev

# Watch for changes
npm run watch

# Production build (minified)
npm run production
```

---

## 3. Alpine.js Usage

### 3.1 Setup

**File**: `resources/js/app.js`

```javascript
import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();
```

### 3.2 Common Patterns

**Dropdown Example**:
```blade
<div x-data="{ open: false }">
    <button @click="open = !open">Toggle</button>
    <div x-show="open" @click.away="open = false">
        Dropdown content
    </div>
</div>
```

**Form Validation**:
```blade
<div x-data="{ 
    form: {
        name: '',
        email: ''
    },
    errors: {}
}">
    <form @submit.prevent="submitForm">
        <input x-model="form.name" type="text">
        <span x-show="errors.name" x-text="errors.name"></span>
    </form>
</div>
```

---

## 4. Multi-Language System

### 4.1 Supported Languages (13 total)

Languages in `resources/lang/`:

1. **English** (`en/`, `english.json`)
2. **Arabic** (`arabic/`, `arabic.json`) - RTL support
3. **Danish** (`danish/`, `danish.json`)
4. **Dutch** (`dutch/`, `dutch.json`)
5. **French** (`french/`, `french.json`)
6. **German** (`german/`, `german.json`)
7. **Italian** (`italian/`, `italian.json`)
8. **Japanese** (`japanese/`, `japan.json`)
9. **Polish** (`polish/`, `polish.json`)
10. **Portuguese** (`portuguese/`, `portuguese.json`)
11. **Russian** (`russian/`, `russian.json`)
12. **Spanish** (`spanish/`, `spanish.json`)
13. **Chinese** (if present)

### 4.2 Translation Structure

Each language has:
- **JSON file**: `{language}.json` (~40-50 KB)
- **Directory**: `{language}/` with subdirectories:
  - `auth.php` - Authentication strings
  - `pagination.php` - Pagination text
  - `passwords.php` - Password reset messages
  - `validation.php` - Validation error messages

### 4.3 Usage in Blade

**Translation Helpers**:
```blade
<!-- Using __ helper -->
{{ __('Welcome to FitHub') }}

<!-- Using @lang directive -->
@lang('auth.login')

<!-- With parameters -->
{{ __('Welcome, :name', ['name' => $user->name]) }}

<!-- JSON translations -->
{{ __('Dashboard') }}
```

### 4.4 Language Switching

**Implementation** (in views):
```blade
<select onchange="window.location.href='/lang-change/' + this.value">
    <option value="en">English</option>
    <option value="arabic">Arabic</option>
    <option value="french">French</option>
    <!-- ... other languages -->
</select>
```

**Route** (from routes/web.php):
```php
Route::get('/lang-change/{lang}', [SettingController::class, 'lanquageChange'])
    ->name('lanquageChange');
```

### 4.5 RTL Support

**For Arabic and RTL languages**:

In layout:
```blade
<html dir="{{ app()->getLocale() == 'arabic' ? 'rtl' : 'ltr' }}">
```

Theme setting stored in database:
- `layout` setting: 'ltr' or 'rtl'

---

## 5. View Components & Partials

### 5.1 Common Components

**Alert Messages**:
```blade
<!-- resources/views/components/alert.blade.php -->
@if(session('success'))
    <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded">
        {{ session('success') }}
    </div>
@endif

@if(session('error'))
    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded">
        {{ session('error') }}
    </div>
@endif
```

**Data Tables**:
```blade
<!-- Common table structure -->
<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase">
                    Name
                </th>
                <!-- More headers -->
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @foreach($items as $item)
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap">
                        {{ $item->name }}
                    </td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
```

**Form Inputs**:
```blade
<!-- resources/views/components/input.blade.php -->
<div class="mb-4">
    <label class="block text-gray-700 text-sm font-bold mb-2">
        {{ $label }}
    </label>
    <input 
        type="{{ $type ?? 'text' }}" 
        name="{{ $name }}"
        class="shadow appearance-none border rounded w-full py-2 px-3 text-gray-700"
        value="{{ old($name, $value ?? '') }}"
    >
    @error($name)
        <p class="text-red-500 text-xs italic">{{ $message }}</p>
    @enderror
</div>
```

---

## 6. Module View Patterns

### 6.1 Standard CRUD Views

Each module typically has 4-5 views:

**User Module Example** (`resources/views/user/`):
1. `index.blade.php` - List all users (table view)
2. `create.blade.php` - Create new user form
3. `edit.blade.php` - Edit user form
4. `show.blade.php` - View user details
5. `logged_history.blade.php` - Additional view (optional)

### 6.2 Index View Pattern

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold">{{ __('Users') }}</h1>
        
        @can('create user')
            <a href="{{ route('user.create') }}" class="btn-primary">
                {{ __('Add User') }}
            </a>
        @endcan
    </div>
    
    <!-- Search/Filter -->
    <div class="mb-4">
        <input type="text" placeholder="{{ __('Search...') }}" class="...">
    </div>
    
    <!-- Data Table -->
    <table class="...">
        <!-- Table content -->
    </table>
    
    <!-- Pagination -->
    {{ $users->links() }}
</div>
@endsection
```

### 6.3 Form View Pattern

```blade
@extends('layouts.app')

@section('content')
<div class="container mx-auto px-4">
    <h1>{{ __('Create User') }}</h1>
    
    <form method="POST" action="{{ route('user.store') }}" enctype="multipart/form-data">
        @csrf
        
        <div class="grid grid-cols-2 gap-4">
            <!-- Name field -->
            <div>
                <label>{{ __('Name') }}</label>
                <input type="text" name="name" value="{{ old('name') }}" required>
                @error('name')
                    <span class="text-red-500">{{ $message }}</span>
                @enderror
            </div>
            
            <!-- More fields -->
        </div>
        
        <div class="mt-6">
            <button type="submit" class="btn-primary">
                {{ __('Save') }}
            </button>
            <a href="{{ route('user.index') }}" class="btn-secondary">
                {{ __('Cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
```

---

## 7. Email Templates

### 7.1 Email View Structure

**Location**: `resources/views/email/`

**Email Templates** (5 files):
1. `common.blade.php` - Base email template
2. `verification.blade.php` - Email verification
3. `password_reset.blade.php` - Password reset
4. `invoice.blade.php` - Invoice emails
5. `notification.blade.php` - General notifications

### 7.2 Email Template Pattern

```blade
<!-- resources/views/email/common.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <style>
        /* Email-safe inline CSS */
        body { font-family: Arial, sans-serif; }
        .container { max-width: 600px; margin: 0 auto; }
        .header { background: #3490dc; color: white; padding: 20px; }
        .content { padding: 20px; }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <img src="{{ $logo }}" alt="Logo">
        </div>
        <div class="content">
            {!! $message !!}
        </div>
        <div class="footer">
            <p>&copy; {{ date('Y') }} {{ config('app.name') }}</p>
        </div>
    </div>
</body>
</html>
```

---

## 8. Dashboard Views

### 8.1 Dashboard Structure

**Location**: `resources/views/dashboard/`

**Files** (4 total):
1. `index.blade.php` - Main dashboard
2. `owner.blade.php` - Owner dashboard
3. `trainer.blade.php` - Trainer dashboard
4. `trainee.blade.php` - Trainee dashboard

### 8.2 Dashboard Widgets

**KPI Cards**:
```blade
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Users -->
    <div class="bg-white rounded-lg shadow p-6">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-gray-500 text-sm">{{ __('Total Users') }}</p>
                <p class="text-3xl font-bold">{{ $totalUsers }}</p>
            </div>
            <div class="text-blue-500">
                <i class="fas fa-users fa-3x"></i>
            </div>
        </div>
    </div>
    
    <!-- More KPI cards -->
</div>
```

**Charts** (using Chart.js or similar):
```blade
<div class="bg-white rounded-lg shadow p-6">
    <h3 class="text-lg font-bold mb-4">{{ __('Revenue Chart') }}</h3>
    <canvas id="revenueChart"></canvas>
</div>

@push('scripts')
<script>
    // Chart initialization
</script>
@endpush
```

---

## 9. Authentication Views

### 9.1 Auth View Structure

**Location**: `resources/views/auth/`

**Files** (5 views):
1. `login.blade.php` - Login page
2. `register.blade.php` - Registration
3. `forgot-password.blade.php` - Password reset request
4. `reset-password.blade.php` - Password reset form
5. `verify-email.blade.php` - Email verification

### 9.2 Login View Pattern

```blade
@extends('layouts.guest')

@section('content')
<div class="min-h-screen flex items-center justify-center bg-gray-100">
    <div class="max-w-md w-full bg-white rounded-lg shadow-md p-8">
        <h2 class="text-2xl font-bold text-center mb-6">
            {{ __('Login to FitHub') }}
        </h2>
        
        <form method="POST" action="{{ route('login') }}">
            @csrf
            
            <!-- Email -->
            <div class="mb-4">
                <label>{{ __('Email') }}</label>
                <input type="email" name="email" required autofocus>
            </div>
            
            <!-- Password -->
            <div class="mb-4">
                <label>{{ __('Password') }}</label>
                <input type="password" name="password" required>
            </div>
            
            <!-- Remember Me -->
            <div class="mb-4">
                <label class="inline-flex items-center">
                    <input type="checkbox" name="remember">
                    <span class="ml-2">{{ __('Remember me') }}</span>
                </label>
            </div>
            
            <!-- reCAPTCHA -->
            @if(settings()->recaptcha_enable == 'on')
                {!! NoCaptcha::display() !!}
            @endif
            
            <button type="submit" class="w-full btn-primary">
                {{ __('Login') }}
            </button>
        </form>
        
        <div class="mt-4 text-center">
            <a href="{{ route('register') }}">{{ __('Create account') }}</a>
            <a href="{{ route('password.request') }}">{{ __('Forgot password?') }}</a>
        </div>
    </div>
</div>
@endsection
```

---

## 10. Landing Page CMS

### 10.1 Home Page Views

**Location**: `resources/views/home_pages/`

**Files** (2):
1. `index.blade.php` - Landing page
2. `edit.blade.php` - CMS editor

### 10.2 Editable Sections

Landing page typically includes:
- Hero section
- Features section
- Pricing section
- Testimonials
- FAQ section
- Contact form
- Footer

All content editable via database (`home_pages` table).

---

## 11. Theme & Customization

### 11.1 Theme Settings

Stored in `settings` table:

```php
[
    'theme_mode' => 'light',  // light or dark
    'theme_color' => 'theme-1',  // Color scheme
    'sidebar_mode' => 'light',  // light or dark sidebar
    'layout' => 'ltr',  // ltr or rtl
]
```

### 11.2 Dynamic Theme Application

In layout:
```blade
<body class="{{ settings()->theme_mode }} {{ settings()->theme_color }}">
    <!-- Content -->
</body>
```

CSS classes defined in Tailwind config for theme variations.

---

## 12. JavaScript Architecture

### 12.1 Entry Point

**File**: `resources/js/app.js`

```javascript
require('./bootstrap');

import Alpine from 'alpinejs';
window.Alpine = Alpine;
Alpine.start();

// Global jQuery/Vanilla JS
$(document).ready(function() {
    // Initialize tooltips, modals, etc.
});
```

### 12.2 Bootstrap File

**File**: `resources/js/bootstrap.js`

```javascript
window._ = require('lodash');

/**
 * Axios HTTP library
 */
import axios from 'axios';
window.axios = axios;

window.axios.defaults.headers.common['X-Requested-With'] = 'XMLHttpRequest';

// CSRF token
let token = document.head.querySelector('meta[name="csrf-token"]');
if (token) {
    window.axios.defaults.headers.common['X-CSRF-TOKEN'] = token.content;
}
```

---

## 13. Asset Management

### 13.1 Public Assets

**Directory**: `public/`

```
public/
├── css/
│   └── app.css          # Compiled Tailwind CSS
├── js/
│   └── app.js           # Compiled JavaScript
├── images/
│   ├── logos/
│   └── uploads/
├── storage/             # Symlink to storage/app/public
└── vendor/              # Third-party assets
```

### 13.2 Storage Link

```bash
php artisan storage:link
```

Creates symlink: `public/storage` → `storage/app/public`

---

## 14. Best Practices

### 14.1 Blade Directives

**Use built-in directives**:
```blade
@auth
@guest
@can('permission')
@cannot('permission')
@error('field')
@isset($variable)
@empty($variable)
@foreach
@forelse
```

### 14.2 Component Reusability

Extract reusable components:
```bash
php artisan make:component Alert
```

### 14.3 Asset Versioning

In production, use versioning:
```blade
<link href="{{ mix('css/app.css') }}" rel="stylesheet">
<script src="{{ mix('js/app.js') }}"></script>
```

### 14.4 Performance

- **Minimize CSS/JS** in production
- **Use CDN** for third-party libraries
- **Lazy load** images
- **Cache views**: `php artisan view:cache`

---

## 15. Development Workflow

### 15.1 Setup

```bash
# Install dependencies
npm install

# Run development server
npm run dev

# Watch for changes
npm run watch
```

### 15.2 Production Build

```bash
# Build for production (minified)
npm run production

# Cache views
php artisan view:cache

# Cache config
php artisan config:cache
```

---

## Quick Reference

### Common Blade Patterns

```blade
<!-- Translation -->
{{ __('text') }}

<!-- Route -->
{{ route('name', $id) }}

<!-- Asset -->
{{ asset('path') }}

<!-- Old input -->
{{ old('field') }}

<!-- Auth user -->
{{ Auth::user()->name }}

<!-- Settings -->
{{ settings()->app_name }}

<!-- Permission check -->
@can('create user')
@endcan

<!-- Date format -->
{{ dateFormat($date) }}

<!-- Price format -->
{{ priceFormat($amount) }}
```

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-28  
**Total Views**: 148+ Blade templates  
**Languages**: 13 supported
