# Velzon UI Component Reference

## Overview

**Velzon** is a comprehensive admin dashboard template with pre-built UI components that we will leverage for the FitHub SaaS implementation. The Velzon components are located in `resources/views/velzon/`.

---

## Available Components

### UI Components (`/velzon/ui/`) - 24 Components
1. **Accordions** - Collapsible content panels
2. **Alerts** - Success, error,warning, info messages  
3. **Badges** - Status indicators, labels
4. **Buttons** - Multiple button styles (primary, secondary, outline, soft, ghost, gradient, animated)
5. **Cards** - Container components with headers/bodies
6. **Carousel** - Image/content sliders
7. **Colors** - Color palette reference
8. **Dropdowns** - Dropdown menus
9. **Embed Video** - Video embeds
10. **General** - General UI utilities
11. **Grid** - Grid layout system
12. **Images** - Image components
13. **Links** - Link styles
14. **Lists** - List components
15. **Media** - Media objects
16. **Modals** - Modal dialogs
17. **Notifications** - Toast notifications
18. **Offcanvas** - Slide-out panels
19. **Placeholders** - Loading placeholders
20. **Progress** - Progress bars
21. **Ribbons** - Corner ribbons
22. **Tabs** - Tab navigation
23. **Typography** - Text styles
24. **Utilities** - Utility classes

### Form Components (`/velzon/forms/`) - 13 Components
1. **Advanced** - Advanced form features
2. **Checkboxes & Radios** - Radio buttons and checkboxes
3. **Editors** - Rich text editors
4. **Elements** - Form input elements
5. **File Uploads** - File upload components
6. **Layouts** - Form layouts
7. **Masks** - Input masks
8. **Pickers** - Date/time/color pickers
9. **Range Sliders** - Range selection sliders
10. **Select** - Select dropdowns
11. **Select2** - Enhanced select with search
12. **Validation** - Form validation
13. **Wizard** - Multi-step forms

### Table Components (`/velzon/tables/`) - 4 Types
1. **Basic** - Basic HTML tables
2. **Datatables** - Advanced datatables with search/sort/pagination
3. **GridJS** - Grid JS tables
4. **ListJS** - ListJS tables

### Dashboard Templates (`/velzon/dashboards/`) - 7 Templates
1. **Analytics** - Analytics dashboard
2. **Blog** - Blog dashboard
3. **CRM** - CRM dashboard
4. **Crypto** - Cryptocurrency dashboard
5. **Job** - Job dashboard
6. **NFT** - NFT dashboard
7. **Projects** - Project management dashboard

### Other Modules
- **Advanced UI** (`/velzon/advanced-ui/`) - 9 advanced components
- **Apps** (`/velzon/apps/`) - 59 pre-built app screens
- **Auth** (`/velzon/auth/`) - 21 authentication pages
- **Charts** (`/velzon/charts/`) - 21 chart types
- **Icons** (`/velzon/icons/`) - 6 icon libraries
- **Landing** (`/velzon/landing/`) - 3 landing pages
- **Maps** (`/velzon/maps/`) - 3 map integrations
- **Pages** (`/velzon/pages/`) - 17 utility pages
- **Widgets** (`/velzon/widgets/`) - Data widgets

---

## Layout System

**Master Layout**: `resources/views/layouts/master.blade.php`

**Layout Components**:
- `topbar.blade.php` - Top navigation bar (54KB - comprehensive)
- `sidebar.blade.php` - Left sidebar navigation
- `footer.blade.php` - Footer section
- `customizer.blade.php` - Theme customizer panel (54KB)
- `head-css.blade.php` - CSS includes
- `vendor-scripts.blade.php` - JS includes

**Layout Variants**:
- Vertical layout (default)
- Horizontal layout
- Two-column layout
- Detached layout
- Vertical hovered layout

---

## Component Usage Pattern

All Velzon components extend the master layout:

```blade
@extends('layouts.master')

@section('title')
    Page Title
@endsection

@section('content')
    <!-- Your content using Velzon components -->
@endsection
```

---

## Integration with FitHub

For the FitHub implementation, we will:

1. **Use Velzon Master Layout** as the base for all application pages
2. **Leverage Velzon UI Components** instead of building from scratch:
   - Buttons, Cards, Modals for CRUD operations
   - Datatables for listing pages (trainers, trainees, invoices, etc.)
   - Forms for create/edit operations
   - Dashboards for KPI displays
3. **Customize Velzon Components** with FitHub branding and specific functionality
4. **Add FitHub-specific views** that extend Velzon components

---

## Benefits

✅ **Pre-built Components** - Save 60-70% development time on UI  
✅ **Responsive Design** - Mobile-friendly out of the box  
✅ **Consistent UI/UX** - Professional, cohesive design  
✅ **Rich Feature Set** - Advanced tables, charts, forms  
✅ **Customizable** - Easy to modify and extend  
✅ **Well-structured** - Clean Blade component architecture

---

**Next Steps**: Update implementation plan to utilize Velzon components for all UI development.
