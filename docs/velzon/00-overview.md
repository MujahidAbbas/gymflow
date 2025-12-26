# Overview & Introduction

Introduction to the Velzon Admin & Dashboard Template and its foundational concepts.

## About Velzon

Velzon is a premium admin and dashboard template built with Bootstrap 5. This Laravel application integrates the complete Velzon template with extensive UI components, multiple layout options, and comprehensive theming support.

**Key Features:**
- Bootstrap 5 based responsive design
- 5+ layout variations
- 3 theme modes (Light, Dark, Galaxy Dark)
- 1000+ icons across 6 icon libraries
- 150+ pre-built page templates
- Extensive form controls and validation
- Multiple chart libraries
- Advanced UI components

## Color System

Velzon uses a comprehensive color system based on Bootstrap's contextual colors with custom enhancements.

### Primary Color Palette

```scss
// SCSS Variables (resources/scss/_variables.scss)
$primary: #405189;
$secondary: #3577f1;
$success: #0ab39c;
$info: #299cdb;
$warning: #f7b84b;
$danger: #f06548;
$dark: #212529;
$light: #f3f6f9;
```

### Color Usage

```html
<!-- Text Colors -->
<p class="text-primary">Primary text</p>
<p class="text-success">Success text</p>
<p class="text-danger">Danger text</p>

<!-- Background Colors -->
<div class="bg-primary">Primary background</div>
<div class="bg-soft-success">Soft success background</div>
<div class="bg-gradient">Gradient background</div>

<!-- Border Colors -->
<div class="border border-primary">Primary border</div>
```

**Color Variants:**
- **Default**: Solid colors for buttons, badges, alerts
- **Soft**: Subtle background with colored text (`.bg-soft-*`, `.text-*`)
- **Outline**: Border with transparent background (`.btn-outline-*`, `.border-*`)
- **Gradient**: Gradient backgrounds (`.bg-gradient`)

### SCSS Customization

Override colors in `resources/scss/_variables-custom.scss`:

```scss
// Custom color overrides
$primary: #your-color;
$secondary: #your-color;

// Or use CSS variables
:root {
    --vz-primary: #your-color;
    --vz-secondary: #your-color;
}
```

## Typography System

Velzon uses a carefully crafted typography scale for consistency and hierarchy.

### Font Families

```scss
// Base font family
$font-family-base: 'Poppins', sans-serif;

// Monospace for code
$font-family-monospace: 'Monaco', 'Courier New', monospace;
```

### Heading Styles

```html
<h1>Heading 1</h1>  <!-- 2.5rem / 40px -->
<h2>Heading 2</h2>  <!-- 2rem / 32px -->
<h3>Heading 3</h3>  <!-- 1.75rem / 28px -->
<h4>Heading 4</h4>  <!-- 1.5rem / 24px -->
<h5>Heading 5</h5>  <!-- 1.25rem / 20px -->
<h6>Heading 6</h6>  <!-- 1rem / 16px -->
```

### Display Headings

Larger, more prominent headings:

```html
<h1 class="display-1">Display 1</h1>  <!-- 6rem / 96px -->
<h1 class="display-2">Display 2</h1>  <!-- 5.5rem / 88px -->
<h1 class="display-3">Display 3</h1>  <!-- 4.5rem / 72px -->
<h1 class="display-4">Display 4</h1>  <!-- 3.5rem / 56px -->
<h1 class="display-5">Display 5</h1>  <!-- 3rem / 48px -->
<h1 class="display-6">Display 6</h1>  <!-- 2.5rem / 40px -->
```

### Font Sizes

```html
<!-- Utility font sizes -->
<p class="fs-1">Font size 1 (2.5rem)</p>
<p class="fs-2">Font size 2 (2rem)</p>
<p class="fs-3">Font size 3 (1.75rem)</p>
<p class="fs-4">Font size 4 (1.5rem)</p>
<p class="fs-5">Font size 5 (1.25rem)</p>
<p class="fs-6">Font size 6 (1rem)</p>

<!-- Additional sizes -->
<p class="fs-11">11px</p>
<p class="fs-12">12px</p>
<p class="fs-13">13px</p>
<p class="fs-14">14px</p>
<p class="fs-15">15px</p>
<p class="fs-16">16px</p>
```

### Font Weights

```html
<p class="fw-light">Light (300)</p>
<p class="fw-normal">Normal (400)</p>
<p class="fw-medium">Medium (500)</p>
<p class="fw-semibold">Semibold (600)</p>
<p class="fw-bold">Bold (700)</p>
```

### Text Utilities

```html
<!-- Text alignment -->
<p class="text-start">Left aligned</p>
<p class="text-center">Center aligned</p>
<p class="text-end">Right aligned</p>

<!-- Text transform -->
<p class="text-lowercase">lowercased text</p>
<p class="text-uppercase">UPPERCASED TEXT</p>
<p class="text-capitalize">capitalized text</p>

<!-- Text decoration -->
<p class="text-decoration-none">No decoration</p>
<p class="text-decoration-underline">Underlined</p>
<p class="text-decoration-line-through">Strikethrough</p>

<!-- Text muted -->
<p class="text-muted">Muted text</p>
```

## Spacing System

Velzon uses Bootstrap's spacing utilities with custom enhancements.

### Spacing Scale

```scss
// Spacing variables (resources/scss/_variables.scss)
$spacer: 1rem; // 16px

$spacers: (
  0: 0,
  1: $spacer * 0.25,   // 4px
  2: $spacer * 0.5,    // 8px
  3: $spacer,          // 16px
  4: $spacer * 1.5,    // 24px
  5: $spacer * 3,      // 48px
);
```

### Margin & Padding Utilities

```html
<!-- Margin utilities -->
<div class="m-0">No margin</div>
<div class="m-3">16px margin all sides</div>
<div class="mt-3">16px margin top</div>
<div class="mb-3">16px margin bottom</div>
<div class="ms-3">16px margin start (left)</div>
<div class="me-3">16px margin end (right)</div>
<div class="mx-3">16px margin horizontal</div>
<div class="my-3">16px margin vertical</div>

<!-- Padding utilities -->
<div class="p-0">No padding</div>
<div class="p-3">16px padding all sides</div>
<div class="pt-3">16px padding top</div>
<div class="pb-3">16px padding bottom</div>
<div class="ps-3">16px padding start (left)</div>
<div class="pe-3">16px padding end (right)</div>
<div class="px-3">16px padding horizontal</div>
<div class="py-3">16px padding vertical</div>

<!-- Auto margin -->
<div class="mx-auto">Centered with auto margin</div>
```

### Gap Utilities

```html
<!-- Flexbox/Grid gaps -->
<div class="d-flex gap-2">Elements with 8px gap</div>
<div class="d-flex gap-3">Elements with 16px gap</div>
<div class="d-grid gap-4">Grid with 24px gap</div>
```

## Grid System

Bootstrap's 12-column responsive grid system.

### Container Types

```html
<!-- Fluid container (100% width) -->
<div class="container-fluid">...</div>

<!-- Fixed-width responsive container -->
<div class="container">...</div>

<!-- Breakpoint-specific containers -->
<div class="container-sm">...</div>  <!-- 100% until sm breakpoint -->
<div class="container-md">...</div>  <!-- 100% until md breakpoint -->
<div class="container-lg">...</div>  <!-- 100% until lg breakpoint -->
<div class="container-xl">...</div>  <!-- 100% until xl breakpoint -->
<div class="container-xxl">...</div> <!-- 100% until xxl breakpoint -->
```

### Grid Structure

```html
<div class="container">
    <div class="row">
        <div class="col-12 col-md-6 col-lg-4">
            Column 1 (full on mobile, half on tablet, third on desktop)
        </div>
        <div class="col-12 col-md-6 col-lg-4">
            Column 2
        </div>
        <div class="col-12 col-md-12 col-lg-4">
            Column 3
        </div>
    </div>
</div>
```

### Gutters

```html
<!-- Default gutters -->
<div class="row">...</div>

<!-- No gutters -->
<div class="row g-0">...</div>

<!-- Custom gutters -->
<div class="row g-2">8px gutters</div>
<div class="row g-3">16px gutters</div>
<div class="row g-4">24px gutters</div>
<div class="row g-5">48px gutters</div>

<!-- Horizontal/Vertical gutters -->
<div class="row gx-3">Horizontal gutters</div>
<div class="row gy-3">Vertical gutters</div>
```

## Responsive Breakpoints

```scss
// Breakpoints (resources/scss/_variables.scss)
$grid-breakpoints: (
  xs: 0,
  sm: 576px,
  md: 768px,
  lg: 992px,
  xl: 1200px,
  xxl: 1400px
);
```

### Responsive Utilities

```html
<!-- Display utilities -->
<div class="d-none d-md-block">Hidden on mobile, visible md+</div>
<div class="d-block d-lg-none">Visible until lg, hidden lg+</div>

<!-- Responsive spacing -->
<div class="mt-3 mt-lg-5">16px margin top, 48px on lg+</div>

<!-- Responsive columns -->
<div class="col-12 col-md-6 col-xl-4">
    Responsive column sizing
</div>
```

## Theme System

Velzon supports three theme modes with seamless switching.

### Available Themes

1. **Light Theme** (Default)
   - Clean, bright interface
   - Best for daytime use
   
2. **Dark Theme**
   - Dark backgrounds with light text
   - Reduces eye strain in low light
   
3. **Galaxy Dark Theme**
   - Purple-tinted dark theme
   - Unique, modern aesthetic

### Theme Configuration

Themes are controlled via data attributes:

```html
<html data-bs-theme="light">   <!-- Light theme -->
<html data-bs-theme="dark">    <!-- Dark theme -->
<html data-bs-theme="galaxy">  <!-- Galaxy dark theme -->
```

### Theme Variables

Each theme has its own SCSS variable file:

- `resources/scss/_variables.scss` - Light theme (default)
- `resources/scss/_variables-dark.scss` - Dark theme overrides
- `resources/scss/_variables-galaxy-dark.scss` - Galaxy theme overrides

### Theme Customization

Override theme-specific colors:

```scss
// resources/scss/_variables-dark.scss
$body-bg-dark: #your-dark-bg;
$body-color-dark: #your-dark-text;
```

## Icon Libraries

Velzon includes 6 comprehensive icon libraries with 1000+ icons.

### Primary: Remix Icons

Default icon library, used throughout the template:

```html
<i class="ri-home-line"></i>
<i class="ri-user-line"></i>
<i class="ri-settings-line"></i>
```

**File**: `resources/views/velzon/icons-remix.blade.php`

### BoxIcons

Alternative icon set:

```html
<i class="bx bx-home"></i>
<i class="bx bx-user"></i>
```

**File**: `resources/views/velzon/icons-boxicons.blade.php`

### Feather Icons

Clean, minimalist icons:

```html
<i data-feather="home"></i>
<i data-feather="user"></i>
```

**File**: `resources/views/velzon/icons-feather.blade.php`

### LineAwesome

Font Awesome alternative:

```html
<i class="las la-home"></i>
<i class="las la-user"></i>
```

**File**: `resources/views/velzon/icons-lineawesome.blade.php`

### Material Design Icons

Google's Material icons:

```html
<span class="material-icons">home</span>
<span class="material-icons">person</span>
```

**File**: `resources/views/velzon/icons-materialdesign.blade.php`

### Crypto Icons

Cryptocurrency-specific icons:

```html
<i class="mdi mdi-bitcoin"></i>
<i class="mdi mdi-ethereum"></i>
```

**File**: `resources/views/velzon/icons-crypto.blade.php`

## Utility Classes

Common utility classes for rapid development.

### Display

```html
<div class="d-block">Block</div>
<div class="d-inline">Inline</div>
<div class="d-inline-block">Inline block</div>
<div class="d-flex">Flexbox</div>
<div class="d-grid">Grid</div>
<div class="d-none">Hidden</div>
```

### Flexbox

```html
<!-- Direction -->
<div class="d-flex flex-row">Row</div>
<div class="d-flex flex-column">Column</div>

<!-- Justify content -->
<div class="d-flex justify-content-start">Start</div>
<div class="d-flex justify-content-center">Center</div>
<div class="d-flex justify-content-end">End</div>
<div class="d-flex justify-content-between">Between</div>

<!-- Align items -->
<div class="d-flex align-items-start">Start</div>
<div class="d-flex align-items-center">Center</div>
<div class="d-flex align-items-end">End</div>

<!-- Wrapping -->
<div class="d-flex flex-wrap">Wrap</div>
<div class="d-flex flex-nowrap">No wrap</div>

<!-- Gap -->
<div class="d-flex gap-2">8px gap</div>
<div class="d-flex gap-3">16px gap</div>
```

### Sizing

```html
<!-- Width -->
<div class="w-25">25% width</div>
<div class="w-50">50% width</div>
<div class="w-75">75% width</div>
<div class="w-100">100% width</div>

<!-- Height -->
<div class="h-25">25% height</div>
<div class="h-50">50% height</div>
<div class="h-100">100% height</div>

<!-- Max width/height -->
<div class="mw-100">Max 100% width</div>
<div class="mh-100">Max 100% height</div>
```

### Borders

```html
<!-- Border sides -->
<div class="border">All sides</div>
<div class="border-top">Top only</div>
<div class="border-bottom">Bottom only</div>
<div class="border-start">Start only</div>
<div class="border-end">End only</div>

<!-- Border radius -->
<div class="rounded">Rounded corners</div>
<div class="rounded-circle">Circle</div>
<div class="rounded-pill">Pill shape</div>
<div class="rounded-0">No radius</div>
```

### Shadows

```html
<div class="shadow-none">No shadow</div>
<div class="shadow-sm">Small shadow</div>
<div class="shadow">Regular shadow</div>
<div class="shadow-lg">Large shadow</div>
```

## Getting Started

### File Structure

```
resources/
├── views/velzon/              # All Velzon template files
│   ├── ui-*.blade.php         # UI component examples
│   ├── forms-*.blade.php      # Form examples  
│   ├── tables-*.blade.php     # Table examples
│   ├── charts-*.blade.php     # Chart examples
│   └── ...more
├── scss/
│   ├── _variables.scss        # Light theme variables
│   ├── _variables-dark.scss   # Dark theme variables
│   ├── _variables-galaxy-dark.scss
│   ├── _variables-custom.scss # Your custom overrides
│   ├── components/            # Component styles
│   ├── pages/                 # Page-specific styles
│   └── plugins/               # Plugin styles
└── js/
    ├── app.js                 # Main application JS
    ├── layout.js              # Layout functionality
    └── pages/                 # Page-specific scripts
```

### Next Steps

- **[Layout System](01-layout-system.md)** - Learn about layout variants and structure
- **[UI Components](02-ui-components.md)** - Explore buttons, cards, modals, and more
- **[Forms](03-form-components.md)** - Master form controls and validation
- **[SCSS Architecture](10-scss-architecture.md)** - Deep dive into styling customization
