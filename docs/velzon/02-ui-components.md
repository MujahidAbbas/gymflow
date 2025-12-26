# UI Components

Comprehensive guide to Velzon's UI components including buttons, alerts, badges, cards, modals, and other interface elements.

---

## Buttons

Velzon provides a comprehensive button system with multiple variants and states.

### Default Buttons

Basic button variations using theme colors:

```blade
<!-- Primary Button -->
<button type="button" class="btn btn-primary">Primary</button>

<!-- Secondary Button -->
<button type="button" class="btn btn-secondary">Secondary</button>

<!-- Success Button -->
<button type="button" class="btn btn-success">Success</button>

<!-- Info Button -->
<button type="button" class="btn btn-info">Info</button>

<!-- Warning Button -->
<button type="button" class="btn btn-warning">Warning</button>

<!-- Danger Button -->
<button type="button" class="btn btn-danger">Danger</button>

<!-- Dark Button -->
<button type="button" class="btn btn-dark">Dark</button>

<!-- Light Button -->
<button type="button" class="btn btn-light">Light</button>
```

**Source:** `resources/views/velzon/ui-buttons.blade.php`

### Outline Buttons

Buttons with transparent backgrounds and colored borders:

```blade
<!-- Outline Primary -->
<button type="button" class="btn btn-outline-primary">Primary</button>

<!-- Outline Secondary -->
<button type="button" class="btn btn-outline-secondary">Secondary</button>

<!-- Outline Success -->
<button type="button" class="btn btn-outline-success">Success</button>

<!-- Outline Danger -->
<button type="button" class="btn btn-outline-danger">Danger</button>
```

### Rounded Buttons

Add `rounded-pill` class for fully rounded buttons:

```blade
<button type="button" class="btn btn-primary rounded-pill">Primary</button>
<button type="button" class="btn btn-success rounded-pill">Success</button>
<button type="button" class="btn btn-danger rounded-pill">Danger</button>
```

### Soft Buttons

Buttons with subtle background colors:

```blade
<button type="button" class="btn btn-soft-primary">Soft Primary</button>
<button type="button" class="btn btn-soft-success">Soft Success</button>
<button type="button" class="btn btn-soft-warning">Soft Warning</button>
<button type="button" class="btn btn-soft-danger">Soft Danger</button>
```

### Ghost Buttons

Minimal buttons with no background:

```blade
<button type="button" class="btn btn-ghost-primary">Ghost Primary</button>
<button type="button" class="btn btn-ghost-success">Ghost Success</button>
<button type="button" class="btn btn-ghost-danger">Ghost Danger</button>
```

### Gradient Buttons

Buttons with gradient backgrounds:

```blade
<button type="button" class="btn btn-primary bg-gradient">Gradient Primary</button>
<button type="button" class="btn btn-success bg-gradient">Gradient Success</button>
<button type="button" class="btn btn-danger bg-gradient">Gradient Danger</button>
```

### Button Sizes

Control button size with sizing classes:

```blade
<!-- Large Button -->
<button type="button" class="btn btn-primary btn-lg">Large Button</button>

<!-- Default Button -->
<button type="button" class="btn btn-primary">Default Button</button>

<!-- Small Button -->
<button type="button" class="btn btn-primary btn-sm">Small Button</button>
```

### Button with Icons

Combine icons with button text:

```blade
<button type="button" class="btn btn-primary">
    <i class="ri-add-line align-middle me-1"></i> Add New
</button>

<button type="button" class="btn btn-success">
    <i class="ri-check-line align-middle me-1"></i> Save Changes
</button>

<button type="button" class="btn btn-danger">
    <i class="ri-delete-bin-line align-middle me-1"></i> Delete
</button>
```

### Icon Only Buttons

Buttons with only icons:

```blade
<button type="button" class="btn btn-icon btn-primary">
    <i class="ri-settings-3-line"></i>
</button>

<button type="button" class="btn btn-icon btn-success rounded-circle">
    <i class="ri-check-line"></i>
</button>
```

---

## Alerts

Alert components for displaying contextual feedback messages.

### Default Alerts

Basic alerts in various theme colors:

```blade
<!-- Primary Alert -->
<div class="alert alert-primary" role="alert">
    <strong>Hi!</strong> A simple <b>Primary alert</b> —check it out!
</div>

<!-- Success Alert -->
<div class="alert alert-success" role="alert">
    <strong>Yey! Everything worked!</strong> A simple <b>success alert</b> —check it out!
</div>

<!-- Danger Alert -->
<div class="alert alert-danger" role="alert">
    <strong>Something is very wrong!</strong> A simple <b>danger alert</b> —check it out!
</div>

<!-- Warning Alert -->
<div class="alert alert-warning" role="alert">
    <strong>Uh oh, something went wrong</strong> A simple <b>warning alert</b> —check it out!
</div>

<!-- Info Alert -->
<div class="alert alert-info" role="alert">
    <strong>Don't forget it!</strong> A simple <b>info alert</b> —check it out!
</div>
```

**Source:** `resources/views/velzon/ui-alerts.blade.php`

### Borderless Alerts

Remove borders using `border-0` class:

```blade
<div class="alert alert-primary border-0" role="alert">
    <strong>Hi!</strong> A simple <b>Primary alert</b> —check it out!
</div>

<div class="alert alert-success border-0" role="alert">
    <strong>Yey! Everything worked!</strong> A simple <b>success alert</b> —check it out!
</div>
```

### Dismissible Alerts

Add close button to alerts:

```blade
<div class="alert alert-primary alert-dismissible fade show" role="alert">
    <strong>Hi!</strong> A simple <b>Dismissible primary Alert</b> — check it out!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-success alert-dismissible fade show" role="alert">
    <strong>Right Way!</strong> A simple <b>Dismissible success alert</b> —check it out!
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
```

### Outline Alerts

Alerts with outlined style:

```blade
<div class="alert alert-primary alert-dismissible border-2 bg-body-secondary fade show" role="alert">
    <strong>Hi!</strong> - Outline <b>primary alert</b> example
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-success alert-dismissible border-2 bg-body-secondary fade show" role="alert">
    <strong>Yey! Everything worked!</strong> - Outline <b>success alert</b> example
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
```

### Left Border Alerts

Alerts with prominent left border:

```blade
<div class="alert alert-primary alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-user-smile-line me-3 align-middle fs-16"></i><strong>Primary</strong> - Left border alert
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-success alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-notification-off-line me-3 align-middle fs-16"></i><strong>Success</strong> - Left border alert
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-danger alert-border-left alert-dismissible fade show" role="alert">
    <i class="ri-error-warning-line me-3 align-middle fs-16"></i><strong>Danger</strong> - Left border alert
    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
```

### Label Icon Alerts

Alerts with background icons:

```blade
<div class="alert alert-primary alert-dismissible bg-primary text-white alert-label-icon fade show" role="alert">
    <i class="ri-user-smile-line label-icon"></i><strong>Primary</strong> - Label icon alert
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>

<div class="alert alert-success alert-dismissible bg-success text-white alert-label-icon fade show" role="alert">
    <i class="ri-check-double-line label-icon"></i><strong>Success</strong> - Label icon alert
    <button type="button" class="btn-close btn-close-white" data-bs-dismiss="alert" aria-label="Close"></button>
</div>
```

### Alert with Links

Style links within alerts using `alert-link` class:

```blade
<div class="alert alert-primary" role="alert">
    A simple Primary alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
</div>

<div class="alert alert-success" role="alert">
    A simple Success alert with <a href="#" class="alert-link">an example link</a>. Give it a click if you like.
</div>
```

---

## Badges

Compact components for labeling and categorization.

### Default Badges

Basic badges in theme colors:

```blade
<span class="badge bg-primary">Primary</span>
<span class="badge bg-secondary">Secondary</span>
<span class="badge bg-success">Success</span>
<span class="badge bg-info">Info</span>
<span class="badge bg-warning">Warning</span>
<span class="badge bg-danger">Danger</span>
<span class="badge bg-dark">Dark</span>
<span class="badge bg-light text-body">Light</span>
```

**Source:** `resources/views/velzon/ui-badges.blade.php`

### Soft Badges

Badges with subtle backgrounds:

```blade
<span class="badge bg-primary-subtle text-primary">Primary</span>
<span class="badge bg-secondary-subtle text-secondary">Secondary</span>
<span class="badge bg-success-subtle text-success">Success</span>
<span class="badge bg-info-subtle text-info">Info</span>
<span class="badge bg-warning-subtle text-warning">Warning</span>
<span class="badge bg-danger-subtle text-danger">Danger</span>
<span class="badge bg-dark-subtle text-body">Dark</span>
```

### Outline Badges

Badges with borders:

```blade
<span class="badge border border-primary text-primary">Primary</span>
<span class="badge border border-secondary text-secondary">Secondary</span>
<span class="badge border border-success text-success">Success</span>
<span class="badge border border-danger text-danger">Danger</span>
```

### Rounded Pill Badges

Fully rounded badges:

```blade
<span class="badge rounded-pill bg-primary">Primary</span>
<span class="badge rounded-pill bg-secondary">Secondary</span>
<span class="badge rounded-pill bg-success">Success</span>
<span class="badge rounded-pill bg-danger">Danger</span>
```

### Rounded Pill with Soft Effect

Combine rounded shape with soft backgrounds:

```blade
<span class="badge rounded-pill bg-primary-subtle text-primary">Primary</span>
<span class="badge rounded-pill bg-success-subtle text-success">Success</span>
<span class="badge rounded-pill bg-warning-subtle text-warning">Warning</span>
<span class="badge rounded-pill bg-danger-subtle text-danger">Danger</span>
```

### Soft Border Badges

Badges with soft backgrounds and borders:

```blade
<span class="badge bg-primary-subtle text-primary badge-border">Primary</span>
<span class="badge bg-success-subtle text-success badge-border">Success</span>
<span class="badge bg-danger-subtle text-danger badge-border">Danger</span>
<span class="badge bg-warning-subtle text-warning badge-border">Warning</span>
```

### Label Badges

Badges with dot indicators:

```blade
<span class="badge badge-label bg-primary">
    <i class="mdi mdi-circle-medium"></i> Primary
</span>

<span class="badge badge-label bg-success">
    <i class="mdi mdi-circle-medium"></i> Success
</span>

<span class="badge badge-label bg-danger">
    <i class="mdi mdi-circle-medium"></i> Danger
</span>
```

### Gradient Badges

Badges with gradient backgrounds:

```blade
<span class="badge badge-gradient-primary">Primary</span>
<span class="badge badge-gradient-success">Success</span>
<span class="badge badge-gradient-danger">Danger</span>
<span class="badge badge-gradient-warning">Warning</span>
```

### Badges in Buttons

Use badges as counters in buttons:

```blade
<button type="button" class="btn btn-primary">
    Notifications <span class="badge bg-success ms-1">4</span>
</button>

<button type="button" class="btn btn-success">
    Messages <span class="badge bg-danger ms-1">2</span>
</button>

<button type="button" class="btn btn-outline-secondary">
    Draft <span class="badge bg-success ms-1">2</span>
</button>
```

### Position Badges

Position badges on buttons or avatars:

```blade
<button type="button" class="btn btn-primary position-relative">
    Mails 
    <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-success">
        +99
        <span class="visually-hidden">unread messages</span>
    </span>
</button>

<button type="button" class="btn btn-light position-relative">
    Alerts 
    <span class="position-absolute top-0 start-100 translate-middle badge border border-light rounded-circle bg-danger p-1">
        <span class="visually-hidden">unread messages</span>
    </span>
</button>
```

### Badges in Headings

Combine badges with heading elements:

```blade
<h1>Example heading <span class="badge text-bg-secondary">New</span></h1>
<h2>Example heading <span class="badge text-bg-secondary">New</span></h2>
<h3>Example heading <span class="badge text-bg-secondary">New</span></h3>
<h4>Example heading <span class="badge text-bg-secondary">New</span></h4>
<h5>Example heading <span class="badge text-bg-secondary">New</span></h5>
<h6>Example heading <span class="badge text-bg-secondary">New</span></h6>
```

---

## Cards

Flexible content containers with multiple variants.

### Simple Card

Basic card with image and content:

```blade
<div class="card">
    <img class="card-img-top img-fluid" src="path/to/image.jpg" alt="Card image cap">
    <div class="card-body">
        <h4 class="card-title mb-2">Web Developer</h4>
        <p class="card-text">At missed advice my it no sister. Miss told ham dull knew see she spot near can. Spirit her entire her called.</p>
        <div class="text-end">
            <a href="javascript:void(0);" class="btn btn-primary">Submit</a>
        </div>
    </div>
</div>
```

**Source:** `resources/views/velzon/ui-cards.blade.php`

### Card with Footer

Add a footer section to cards:

```blade
<div class="card">
    <img class="card-img-top img-fluid" src="path/to/image.jpg" alt="Card image cap">
    <div class="card-body">
        <h4 class="card-title mb-2">How apps is changing the IT world</h4>
        <p class="card-text mb-0">Whether article spirits new her covered hastily sitting her. Money witty books nor son add.</p>
    </div>
    <div class="card-footer">
        <a href="javascript:void(0);" class="card-link link-secondary">
            Read More <i class="ri-arrow-right-s-line ms-1 align-middle lh-1"></i>
        </a>
        <a href="javascript:void(0);" class="card-link link-success">
            Bookmark <i class="ri-bookmark-line align-middle ms-1 lh-1"></i>
        </a>
    </div>
</div>
```

### Card with List Group

Integrate list groups within cards:

```blade
<div class="card">
    <img class="card-img-top img-fluid" src="path/to/image.jpg" alt="Card image cap">
    <div class="card-body">
        <p class="card-text">We quickly learn to fear and thus automatically avoid potentially stressful situations of all kinds.</p>
    </div>
    <ul class="list-group list-group-flush">
        <li class="list-group-item">An item</li>
        <li class="list-group-item">A second item</li>
        <li class="list-group-item">A third item</li>
    </ul>
</div>
```

### Card Text Alignment

Control text alignment within cards:

```blade
<!-- Left Aligned (Default) -->
<div class="card card-body">
    <div class="avatar-sm mb-3">
        <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
            <i class="ri-smartphone-line"></i>
        </div>
    </div>
    <h4 class="card-title">Text Application</h4>
    <p class="card-text text-muted">Send a link to apply on mobile device.</p>
    <a href="javascript:void(0);" class="btn btn-success">Apply Now</a>
</div>

<!-- Center Aligned -->
<div class="card card-body text-center">
    <div class="avatar-sm mx-auto mb-3">
        <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
            <i class="ri-add-line"></i>
        </div>
    </div>
    <h4 class="card-title">Add New Application</h4>
    <p class="card-text text-muted">Send a link to apply on mobile device.</p>
    <a href="javascript:void(0);" class="btn btn-success">Add New</a>
</div>

<!-- Right Aligned -->
<div class="card card-body text-end">
    <div class="avatar-sm ms-auto mb-3">
        <div class="avatar-title bg-success-subtle text-success fs-17 rounded">
            <i class="ri-gift-fill"></i>
        </div>
    </div>
    <h4 class="card-title">Text Application</h4>
    <p class="card-text text-muted">Send a link to apply on mobile device.</p>
    <a href="javascript:void(0);" class="btn btn-success">Add New</a>
</div>
```

### Card with Header

Add structured headers to cards:

```blade
<div class="card">
    <div class="card-header">
        <button type="button" class="btn-close float-end fs-11" aria-label="Close"></button>
        <h6 class="card-title mb-0">Hi, Erica Kernan</h6>
    </div>
    <div class="card-body">
        <h6 class="card-title">How to get creative in your work?</h6>
        <p class="card-text text-muted mb-0">A business consulting agency is involved in the planning, implementation, and education of businesses.</p>
    </div>
    <div class="card-footer">
        <a href="javascript:void(0);" class="link-success float-end">
            Read More <i class="ri-arrow-right-s-line align-middle ms-1 lh-1"></i>
        </a>
        <p class="text-muted mb-0">1 days Ago</p>
    </div>
</div>
```

### Card Image Caps

Position images at top or bottom:

```blade
<!-- Image Top -->
<div class="card">
    <img class="card-img-top img-fluid" src="path/to/image.jpg" alt="Card image cap">
    <div class="card-body">
        <h4 class="card-title mb-2">A day in the of a professional fashion designer</h4>
        <p class="card-text text-muted">Exercitation +1 labore velit, blog sartorial PBR leggings next level.</p>
        <p class="card-text">Last updated 3 mins ago</p>
    </div>
</div>

<!-- Image Bottom -->
<div class="card">
    <div class="card-body">
        <h4 class="card-title mb-2">A day in the of a professional fashion designer</h4>
        <p class="card-text text-muted">Exercitation +1 labore velit, blog sartorial PBR leggings next level.</p>
        <p class="card-text">Last updated 3 mins ago</p>
    </div>
    <img class="card-img-bottom img-fluid" src="path/to/image.jpg" alt="Card image cap">
</div>
```

### Card Image Overlay

Overlay content on images:

```blade
<div class="card card-overlay">
    <img class="card-img img-fluid" src="path/to/image.jpg" alt="Card image">
    <div class="card-img-overlay p-0 d-flex flex-column">
        <div class="card-header bg-transparent">
            <h4 class="card-title text-white mb-0">Design your apps in your own way</h4>
        </div>
        <div class="card-body">
            <p class="card-text text-white mb-2">Each design is a new, unique piece of art birthed into this world.</p>
            <p class="card-text">
                <small class="text-white">Last updated 3 mins ago</small>
            </p>
        </div>
        <div class="card-footer bg-transparent text-center">
            <a href="javascript:void(0);" class="link-light">
                Read More <i class="ri-arrow-right-s-line align-middle ms-1 lh-1"></i>
            </a>
        </div>
    </div>
</div>
```

### Employee/Profile Cards

Centered profile cards:

```blade
<div class="card">
    <div class="card-header">
        <button type="button" class="btn-close float-end fs-11" aria-label="Close"></button>
        <h6 class="card-title mb-0">Employee Card</h6>
    </div>
    <div class="card-body p-4 text-center">
        <div class="mx-auto avatar-md mb-3">
            <img src="path/to/avatar.jpg" alt="" class="img-fluid rounded-circle">
        </div>
        <h5 class="card-title mb-1">Gabriel Palmer</h5>
        <p class="text-muted mb-0">Graphic Designer</p>
    </div>
    <div class="card-footer text-center">
        <ul class="list-inline mb-0">
            <li class="list-inline-item">
                <a href="javascript:void(0);" class="lh-1 align-middle link-secondary">
                    <i class="ri-facebook-fill"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="javascript:void(0);" class="lh-1 align-middle link-success">
                    <i class="ri-whatsapp-line"></i>
                </a>
            </li>
            <li class="list-inline-item">
                <a href="javascript:void(0);" class="lh-1 align-middle link-primary">
                    <i class="ri-linkedin-fill"></i>
                </a>
            </li>
        </ul>
    </div>
</div>
```

### Card with Collapse

Interactive collapsible cards:

```blade
<div class="card" id="card-none1">
    <div class="card-header">
        <div class="d-flex align-items-center">
            <div class="flex-grow-1">
                <h6 class="card-title mb-0">Card with Spinner Loader</h6>
            </div>
            <div class="flex-shrink-0">
                <ul class="list-inline card-toolbar-menu d-flex align-items-center mb-0">
                    <li class="list-inline-item">
                        <a class="align-middle" data-toggle="reload" href="javascript:void(0);">
                            <i class="mdi mdi-refresh align-middle"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <a class="align-middle minimize-card" data-bs-toggle="collapse" 
                           href="#collapseexample1" role="button" aria-expanded="false">
                            <i class="mdi mdi-plus align-middle plus"></i>
                            <i class="mdi mdi-minus align-middle minus"></i>
                        </a>
                    </li>
                    <li class="list-inline-item">
                        <button type="button" onclick="delthis('card-none1')" 
                                class="btn-close fs-10 align-middle"></button>
                    </li>
                </ul>
            </div>
        </div>
    </div>
    <div class="card-body collapse show" id="collapseexample1">
        <div class="d-flex">
            <div class="flex-shrink-0">
                <i class="ri-checkbox-circle-fill text-success"></i>
            </div>
            <div class="flex-grow-1 ms-2 text-muted">
                Some placeholder content for the collapse component.
            </div>
        </div>
    </div>
</div>
```

---

## Modals

Modal dialogs for displaying content in overlay windows.

### Default Modal

Basic modal structure:

```blade
<!-- Trigger Button -->
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#myModal">
    Standard Modal
</button>

<!-- Modal -->
<div id="myModal" class="modal fade" tabindex="-1" aria-labelledby="myModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="myModalLabel">Modal Heading</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h5 class="fs-15">Overflowing text to show scroll behavior</h5>
                <p class="text-muted">One morning, when Gregor Samsa woke from troubled dreams, he found himself transformed in his bed into a horrible vermin.</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
```

**Source:** `resources/views/velzon/ui-modals.blade.php`

### Vertically Centered Modal

Center modal vertically:

```blade
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target=".bs-example-modal-center">
    Center Modal
</button>

<div class="modal fade bs-example-modal-center" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/hrqwmuhr.json" 
                           trigger="loop" 
                           colors="primary:#121331,secondary:#08a88a" 
                           style="width:120px;height:120px">
                </lord-icon>
                <div class="mt-4">
                    <h4 class="mb-3">Oops something went wrong!</h4>
                    <p class="text-muted mb-4">The transfer was not successfully received by us.</p>
                    <div class="hstack gap-2 justify-content-center">
                        <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                        <a href="javascript:void(0);" class="btn btn-danger">Try Again</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### Modal with Grid

Use Bootstrap grid inside modals:

```blade
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalgrid">
    Launch Demo Modal
</button>

<div class="modal fade" id="exampleModalgrid" tabindex="-1" aria-labelledby="exampleModalgridLabel">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalgridLabel">Grid Modals</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <form action="javascript:void(0);">
                    <div class="row g-3">
                        <div class="col-xxl-6">
                            <div>
                                <label for="firstName" class="form-label">First Name</label>
                                <input type="text" class="form-control" id="firstName" placeholder="Enter firstname">
                            </div>
                        </div>
                        <div class="col-xxl-6">
                            <div>
                                <label for="lastName" class="form-label">Last Name</label>
                                <input type="text" class="form-control" id="lastName" placeholder="Enter lastname">
                            </div>
                        </div>
                        <div class="col-lg-12">
                            <div class="hstack gap-2 justify-content-end">
                                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                                <button type="submit" class="btn btn-primary">Submit</button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
```

### Static Backdrop Modal

Prevent closing when clicking outside:

```blade
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#staticBackdrop">
    Static Backdrop Modal
</button>

<div class="modal fade" id="staticBackdrop" data-bs-backdrop="static" data-bs-keyboard="false" 
     tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <lord-icon src="https://cdn.lordicon.com/lupuorrc.json" 
                           trigger="loop" 
                           colors="primary:#121331,secondary:#08a88a" 
                           style="width:120px;height:120px">
                </lord-icon>
                <div class="mt-4">
                    <h4 class="mb-3">You've made it!</h4>
                    <p class="text-muted mb-4">The transfer was not successfully received by us.</p>
                    <div class="hstack gap-2 justify-content-center">
                        <a href="javascript:void(0);" class="btn btn-link link-success fw-medium" data-bs-dismiss="modal">
                            <i class="ri-close-line me-1 align-middle"></i> Close
                        </a>
                        <a href="javascript:void(0);" class="btn btn-success">Completed</a>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
```

### Scrollable Modal

Modal with scrollable content:

```blade
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#exampleModalScrollable">
    Scrollable Modal
</button>

<div class="modal fade" id="exampleModalScrollable" tabindex="-1" role="dialog" aria-hidden="true">
    <div class="modal-dialog modal-dialog-scrollable">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="exampleModalScrollableTitle">Scrollable Modal</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <h6 class="fs-15">Give your text a good structure</h6>
                <p class="text-muted">Long content that requires scrolling...</p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                <button type="button" class="btn btn-primary">Save Changes</button>
            </div>
        </div>
    </div>
</div>
```

### Modal Sizes

Control modal width:

```blade
<!-- Extra Large Modal -->
<div class="modal-dialog modal-xl">...</div>

<!-- Large Modal -->
<div class="modal-dialog modal-lg">...</div>

<!-- Small Modal -->
<div class="modal-dialog modal-sm">...</div>

<!-- Fullscreen Modal -->
<div class="modal-dialog modal-fullscreen">...</div>
```

### Toggle Between Modals

Navigate between multiple modals:

```blade
<button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#firstmodal">
    Open First Modal
</button>

<!-- First Modal -->
<div class="modal fade" id="firstmodal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <h4>Uh oh, something went wrong!</h4>
                <p class="text-muted">The transfer was not successfully received by us.</p>
                <button class="btn btn-warning" data-bs-target="#secondmodal" 
                        data-bs-toggle="modal" data-bs-dismiss="modal">
                    Continue
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Second Modal -->
<div class="modal fade" id="secondmodal" aria-hidden="true" tabindex="-1">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="modal-body text-center p-5">
                <h4 class="mb-3">Follow-Up Email</h4>
                <p class="text-muted mb-4">Hide this modal and show the first with the button below.</p>
                <div class="hstack gap-2 justify-content-center">
                    <button class="btn btn-soft-danger" data-bs-target="#firstmodal" 
                            data-bs-toggle="modal" data-bs-dismiss="modal">
                        Back to First
                    </button>
                    <button type="button" class="btn btn-light" data-bs-dismiss="modal">Close</button>
                </div>
            </div>
        </div>
    </div>
</div>
```

---

## Component Styling Classes

### Common Utility Classes

Frequently used utility classes with Velzon components:

```scss
// Spacing
.mb-0, .mb-1, .mb-2, .mb-3, .mb-4, .mb-5  // Margin bottom
.mt-0, .mt-1, .mt-2, .mt-3, .mt-4, .mt-5  // Margin top
.p-0, .p-1, .p-2, .p-3, .p-4, .p-5        // Padding all sides

// Display & Flexbox
.d-flex                                    // Display flex
.flex-grow-1, .flex-shrink-0              // Flex sizing
.align-items-center                        // Vertical centering
.justify-content-center                    // Horizontal centering
.gap-2, .gap-3                            // Gap between flex items

// Text
.text-center, .text-end, .text-start      // Text alignment
.text-muted                                // Muted text color
.fw-medium, .fw-semibold, .fw-bold        // Font weights
.fs-12, .fs-13, .fs-14, .fs-15, .fs-16    // Font sizes

// Colors
.text-primary, .text-success, .text-danger // Text colors
.bg-primary, .bg-success, .bg-danger      // Background colors
.bg-*-subtle                               // Subtle backgrounds
.border-primary, .border-success          // Border colors
```

### Component-Specific Variables

Key SCSS variables for customizing UI components:

```scss
// Button variables
$btn-padding-y: 0.5rem;
$btn-padding-x: 0.9rem;
$btn-font-size: 0.875rem;
$btn-border-radius: 0.25rem;

// Badge variables
$badge-font-size: 0.75em;
$badge-padding-y: 0.35em;
$badge-padding-x: 0.55em;
$badge-border-radius: 0.25rem;

// Card variables
$card-spacer-y: 1.25rem;
$card-spacer-x: 1.25rem;
$card-border-radius: 0.25rem;
$card-border-color: rgba(0, 0, 0, 0.125);

// Modal variables
$modal-inner-padding: 1rem;
$modal-header-padding-y: 1rem;
$modal-header-padding-x: 1rem;
$modal-content-border-radius: 0.4rem;
```

**SCSS File:** `resources/scss/_variables.scss`

---

## Best Practices

### Accessibility

1. **Always include ARIA attributes** for modals and interactive components
2. **Use semantic HTML** elements (button, nav, header, etc.)
3. **Provide alt text** for all images
4. **Ensure proper contrast** between text and backgrounds
5. **Include visually-hidden text** for icon-only buttons

### Performance

1. **Lazy load images** in cards when possible
2. **Minimize DOM manipulation** with modals
3. **Use data attributes** for Bootstrap JavaScript instead of custom scripts
4. **Avoid nesting too many cards** in a single view

### Consistency

1. **Use theme colors** (`primary`, `success`, `danger`, etc.) instead of custom colors
2. **Follow spacing scale** (0-5) for margins and padding
3. **Maintain button sizing** consistency across the application
4. **Stick to standard modal sizes** unless absolutely necessary

### Responsive Design

1. **Test components** on all breakpoints (xs, sm, md, lg, xl, xxl)
2. **Use responsive utility classes** (`.d-none .d-md-block`, etc.)
3. **Stack cards** on mobile devices
4. **Consider touch targets** for buttons on mobile (minimum 44x44px)

---

## Next Steps

- Explore [Form Components](03-form-components.md) for input elements and validation
- Review [Tables](04-tables.md) for data display patterns
- Check [Advanced UI](05-advanced-ui.md) for specialized components
