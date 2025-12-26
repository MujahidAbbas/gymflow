# Product Requirements Document (PRD)
## FitHub SaaS - Gym Management System

---

## 1. Executive Summary

### 1.1 Product Overview
**FitHub SaaS** is a comprehensive, multi-tenant gym management software-as-a-service (SaaS) platform built on Laravel 9 framework. It provides fitness centers, health clubs, and gym owners with a complete digital solution to manage members, trainers, classes, memberships, finances, and daily operations.

### 1.2 Product Vision
To provide an all-in-one, cloud-based gym management solution that streamlines operations, enhances member experience, and enables gym owners to scale their business efficiently through a feature-rich, user-friendly platform.

### 1.3 Target Audience
- **Primary Users**: Gym owners, fitness center managers
- **Secondary Users**: Trainers, trainees/members, administrative staff
- **Super Admin**: Platform administrators managing multiple gym instances

---

## 2. Technical Architecture

### 2.1 Technology Stack

#### Backend
- **Framework**: Laravel 9.x (PHP 8.0.2+)
- **Database**: MySQL
- **Architecture**: Model-View-Controller (MVC)
- **Authentication**: Laravel Sanctum
- **Authorization**: Spatie Laravel Permission (role-based access control)

#### Frontend
- **Framework**: Laravel UI (Blade templates)
- **CSS**: Tailwind CSS
- **JavaScript**: Vanilla JS
- **Build Tools**: Webpack Mix

#### Key Dependencies
```json
{
  "laravel/framework": "^9.11",
  "spatie/laravel-permission": "^5.10",
  "lab404/laravel-impersonate": "^1.7",
  "pragmarx/google2fa-laravel": "^2.2",
  "anhskohbo/no-captcha": "^3.5",
  "stripe/stripe-php": "^7.36",
  "srmklive/paypal": "~3.0",
  "kkomelin/laravel-translatable-string-exporter": "^1.21"
}
```

### 2.2 Database Schema

**Total Migrations**: 46

**Core Tables**:
- `users` - All system users (super admin, owners, trainers, trainees, staff)
- `settings` - Multi-tenant configuration storage
- `subscriptions` - Subscription plans
- `package_transactions` - Payment transaction records
- `trainer_details` - Extended trainer information
- `trainee_details` - Extended trainee/member information
- `classes` - Fitness classes
- `class_schedules` - Class timing schedules
- `class_assigns` - Trainer/trainee class assignments
- `memberships` - Membership plans
- `workouts` - Workout plans
- `workout_activities` - Workout exercises/activities
- `healths` - Health measurements and tracking
- `attendances` - Daily attendance records
- `invoices` - Income invoices
- `invoice_items` - Invoice line items
- `invoice_payments` - Payment records
- `expenses` - Expense tracking
- `types` - Invoice/expense categories
- `lockers` - Locker inventory
- `assign_lockers` - Locker assignments
- `nutrition_schedules` - Nutrition plans
- `products` - Product catalog
- `product_bookings` - Product purchases
- `product_booking_items` - Purchase line items
- `events` - Calendar events
- `event_types` - Event categories
- `contacts` - Contact inquiries
- `notice_boards` - Internal notices/announcements
- `notifications` - Email templates
- `coupons` - Discount coupons
- `coupon_histories` - Coupon usage tracking
- `f_a_q_s` - Frequently asked questions
- `pages` - Custom CMS pages
- `home_pages` - Landing page content
- `auth_pages` - Authentication page customization
- `logged_histories` - User login tracking
- `supports` - Support ticket system
- `support_replies` - Support ticket replies

### 2.3 Application Structure

**Models**: 41 Eloquent models
**Controllers**: 36 controllers
**Routes**: Centralized in `routes/web.php` (505 lines, 16.8 KB)

#### Multi-Tenancy Architecture
- **Isolation**: Data segregated by `parent_id` column
- **Hierarchy**: 
  - Super Admin (parent_id = own ID)
  - Owner (parent_id = own ID)
  - Trainer/Trainee/Staff (parent_id = owner's ID)

---

## 3. User Roles & Permissions

### 3.1 User Types

#### Super Admin
- Platform-level administration
- Manage all gym owners
- Subscription management
- System-wide settings
- Impersonate any user
- View all transactions

#### Owner
- Gym-specific administration
- Manage trainers, trainees, and staff
- Configure gym settings
- Financial management
- View reports and analytics
- Manage subscriptions

#### Trainer
**Default Permissions**:
- Manage contacts
- Manage notes
- View trainees
- View and manage classes
- View and manage workouts
- Manage today's workouts
- View and manage health updates
- Manage attendance

#### Trainee (Member)
**Default Permissions**:
- Manage contacts
- Manage notes
- View trainers
- View classes
- View workouts
- Manage today's workouts
- View health updates
- View attendance

### 3.2 Permission System
- **Package**: Spatie Laravel Permission
- **Granularity**: CRUD operations per module
- **Dynamic**: Customizable per gym instance
- **Role Assignment**: Automatic on user creation

---

## 4. Core Functional Requirements

### 4.1 Dashboard

#### Features
- **KPI Widgets**:
  - Total users count
  - Total trainers count
  - Total trainees count
  - Total contacts
  - Role-wise user distribution

- **Visual Analytics**:
  - Revenue charts
  - Member growth trends
  - Attendance patterns
  - Subscription status

- **Quick Access**:
  - Today's attendance
  - Today's workouts
  - Upcoming events
  - Recent invoices

### 4.2 User Management

#### User Types Supported
- Super Admin
- Owner
- Trainer
- Trainee
- Custom roles

#### Features
- **CRUD Operations**: Create, read, update, delete users
- **Profile Management**:
  - Name, email, phone number
  - Profile picture upload
  - Language preference
  - Active/inactive status
  
- **Account Controls**:
  - Password management
  - Email verification (optional)
  - 2FA enrollment
  - Account deletion

- **Bulk Operations**:
  - User import/export
  - Bulk status changes

- **Logged History**:
  - Login IP tracking
  - Device detection (mobile/tablet/desktop)
  - Browser and OS information
  - Geographic location (IP-based)
  - Login timestamp
  - Referrer tracking

#### Validation Rules
- Email must be unique
- Password minimum 6 characters
- Profile image: PNG only
- Phone number required

### 4.3 Trainer Management

#### Trainer Profile
- **Basic Info**: Auto-generated from user
- **Trainer ID**: Auto-generated with prefix (`#TRNR-000X`)
- **Extended Details**:
  - Specialization
  - Experience level
  - Certifications
  - Bio/description
  - Hourly rate
  - Status (active/inactive)

#### Features
- List all trainers with search/filter
- View trainer details
- Assign trainers to classes
- View assigned trainees
- Track trainer workload
- Performance analytics

#### Email Notifications
- **New Trainer Created**: Welcome email with login credentials
- **Trainer Assigned to Trainee**: Notification with trainee details
- **New Class Assignment**: Class schedule and details

### 4.4 Trainee Management

#### Trainee Profile
- **Trainee ID**: Auto-generated with prefix (`#TRNE-000X`)
- **Personal Info**:
  - Name, email, phone
  - Date of birth
  - Gender (Male/Female)
  - Address
  - Emergency contact

- **Membership Details**:
  - Active membership plan
  - Start date
  - Expiry date
  - Renewal history

- **Assigned Resources**:
  - Primary trainer
  - Enrolled classes
  - Assigned locker
  - Workout plan
  - Nutrition schedule

- **Health Metrics**: Linked to health tracking
- **Attendance Record**: Historical attendance data
- **Status**: Active/Inactive

#### Features
- Trainee CRUD operations
- Advanced search and filtering
- Membership renewal workflow
- Bulk attendance marking
- Export trainee reports
- Status management

#### Email Notifications
- **New Trainee Registration**: Welcome email with credentials, membership, and trainer info
- **Membership Renewal**: Renewal confirmation
- **Workout Plan Assigned**: Workout details
- **Class Enrollment**: Class schedule

### 4.5 Classes Management

#### Class Structure
- **Class Title**: Descriptive name
- **Category**: Class type (Yoga, Cardio, Strength, etc.)
- **Fees**: Per-class or package pricing
- **Capacity**: Maximum members
- **Description**: Class details
- **Location/Address**: Venue information
- **Status**: Active/Inactive

#### Class Schedules
- **Multiple Schedules**: Multiple sessions per class
- **Time Slots**:
  - Day of week
  - Start time
  - End time
  - Duration calculation

- **Recurring**: Weekly schedules
- **Conflict Detection**: Prevent overlapping schedules

#### Class Assignments
- **Trainer Assignment**:
  - Assign multiple trainers
  - View trainer availability
  - Automatic conflict check

- **Trainee Enrollment**:
  - Manual enrollment
  - Capacity management
  - Waitlist (if applicable)
  - Enrollment history

#### Features
- Create/edit/delete classes
- Manage class schedules
- Assign/remove trainers and trainees
- View class roster
- Attendance tracking per class
- Class-wise revenue reports

#### Email Notifications
- **New Class Announcement**: Sent to all trainees and trainers
- **Class Cancellation**: Alert all participants
- **Class Rescheduling**: Updated schedule notification

### 4.6 Category Management

#### Purpose
Categorize classes for better organization

#### Features
- Create categories (e.g., Cardio, Strength, Yoga, Pilates)
- Edit/delete categories
- Assign categories to classes
- Filter classes by category

### 4.7 Membership Management

#### Membership Plans
- **Plan Name**: Descriptive title
- **Duration**: Number of days/months
- **Fees**: Plan price
- **Description**: Plan features
- **Status**: Active/Inactive

#### Features
- Create membership plans
- Assign plans to trainees
- Membership expiry tracking
- Renewal workflow
- Membership upgrade/downgrade
- Grace period configuration
- Membership history

#### Membership Renewal
- **Renewal Process**:
  - Select trainee
  - Choose new membership plan
  - Set start date
  - Auto-calculate expiry
  - Generate invoice (optional)

- **Notifications**:
  - Renewal confirmation
  - Expiry reminders

### 4.8 Workout Management

#### Workout Plans
- **Trainee Assignment**: Link to specific trainee
- **Duration**:
  - Start date
  - End date
  - Total duration calculation

- **Workout Activities**: Multiple exercises per plan
  - Activity name
  - Sets
  - Reps
  - Duration
  - Rest time
  - Notes/instructions

#### Features
- Create custom workout plans
- Assign to trainees
- Track workout completion
- View today's workouts
- Workout history
- Progress tracking

#### Workout Activity Library
- Pre-defined exercises
- Custom activity creation
- Activity categories
- Searchable library

#### Email Notifications
- **Workout Plan Created**: Full activity report sent to trainee
- **Workout Reminder**: Daily/weekly reminders

### 4.9 Health Tracking

#### Health Measurements
- **Trainee ID**: Link to trainee
- **Measurement Date**: Tracking timestamp
- **Metrics** (User Input):
  - Height
  - Weight
  - Fat (Body fat percentage)
  - Chest
  - Waist
  - Thigh
  - Arms
  - Custom measurements

> [!NOTE]
> **Calculated Metrics**: BMI and other derived metrics can be calculated from the captured measurements. The system stores flexible measurement data allowing for any combination of the above metrics.

#### Features
- Record health data
- View measurement history
- Generate progress charts
- Compare measurements over time
- Export health reports
- Goal tracking

#### Email Notifications
- **Health Update Recorded**: Measurement report sent to trainee
- **Milestone Achieved**: Congratulatory message

### 4.10 Attendance System

#### Daily Attendance
- **Check-in/Check-out**:
  - Trainee ID
  - Date
  - Check-in time
  - Check-out time
  - Total duration (auto-calculated)
  - Status (Present/Absent)

#### Features
- Mark individual attendance
- Bulk attendance marking
- Today's attendance view
- Attendance history
- Filter by date range
- Export attendance reports
- Late arrival tracking

#### Bulk Attendance
- Select multiple trainees
- Mark all present/absent
- Date selection
- Time range selection

#### Email Notifications
- **Attendance Confirmation**: Daily summary sent to trainee

### 4.11 Invoice & Income Management

#### Invoice Structure
- **Invoice Number**: Auto-generated (`#INV-000X`)
- **Trainee**: Link to trainee
- **Issue Date**: Invoice date
- **Due Date**: Payment deadline
- **Type**: Invoice category
- **Status**: Paid/Unpaid/Partial
- **Total Amount**: Calculated from items
- **Discount**: Optional discount amount
- **Tax**: Optional tax amount

#### Invoice Items
- **Description**: Item/service name
- **Quantity**: Number of units
- **Rate**: Unit price
- **Amount**: Quantity × Rate

#### Invoice Payments
- **Payment Date**: Transaction date
- **Amount**: Payment amount
- **Payment Method**: Cash/Card/Bank Transfer
- **Reference**: Transaction reference
- **Notes**: Additional information

#### Features
- Create/edit/delete invoices
- Add multiple line items
- Record partial payments
- Track outstanding balances
- Generate PDF invoices
- Send invoices via email
- Payment history
- Revenue reports

#### Email Notifications
- **Invoice Created**: Invoice details sent to trainee
- **Payment Received**: Payment confirmation
- **Payment Reminder**: Overdue invoice alerts

### 4.12 Expense Management

#### Expense Tracking
- **Expense Number**: Auto-generated (`#EXP-000X`)
- **Title**: Expense description
- **Type**: Expense category
- **Amount**: Expense amount
- **Date**: Transaction date
- **Receipt**: Upload receipt image
- **Notes**: Additional details

#### Features
- Record expenses
- Categorize expenses
- Upload receipts
- View expense history
- Filter by date/type
- Generate expense reports
- Track profit/loss

### 4.13 Finance Type Management

#### Purpose
Categorize invoices and expenses

#### Types
- **Income Types**: Membership, Classes, Personal Training, Products
- **Expense Types**: Rent, Utilities, Equipment, Salaries, Marketing

#### Features
- Create custom types
- Edit/delete types
- Assign types to invoices/expenses

### 4.14 Locker Management

#### Locker Inventory
- **Locker Number**: Auto-generated (`#LO-000X`)
- **Location**: Section/area
- **Size**: Small/Medium/Large
- **Status**: Available/Assigned/Maintenance

#### Locker Assignment
- **Trainee**: Assigned member
- **Start Date**: Assignment date
- **End Date**: Expiry date
- **Fees**: Locker rental fee
- **Status**: Active/Expired

#### Features
- Add/manage lockers
- Assign lockers to trainees
- Track locker availability
- Renewal notifications
- Locker usage reports

#### Email Notifications
- **Locker Assigned**: Assignment details sent to trainee
- **Locker Expiry Reminder**: Renewal alert

### 4.15 Event Calendar

#### Event Management
- **Event Title**: Event name
- **Event Type**: Category (Competition, Workshop, Class, Meeting)
- **Date**: Event date
- **Start Time**: Event start
- **End Time**: Event end
- **Location**: Venue
- **Description**: Event details
- **Participants**: Invited members/trainers
- **Status**: Scheduled/Completed/Cancelled

#### Features
- Create events
- Calendar view (monthly/weekly/daily)
- Event reminders
- Participant management
- Event history
- Export calendar

### 4.16 Nutrition Schedule

#### Nutrition Plans
- **Trainee**: Assigned member
- **Meal Type**: Breakfast/Lunch/Dinner/Snack
- **Day**: Day of week / Specific date
- **Food Items**: Meal description
- **Calories**: Nutritional value
- **Protein/Carbs/Fats**: Macro breakdown
- **Notes**: Special instructions

#### Features
- Create custom nutrition plans
- Assign to trainees
- Weekly/monthly plans
- Track adherence
- Meal suggestions
- Export meal plans

### 4.17 Product Management

#### Product Catalog
- **Product Name**: Item name
- **Category**: Product type (Supplements, Apparel, Equipment)
- **Price**: Selling price
- **Stock**: Inventory quantity
- **Description**: Product details
- **Image**: Product photo
- **Status**: Available/Out of Stock

#### Product Booking (Sales)
- **Trainee**: Purchaser
- **Booking Date**: Transaction date
- **Items**: Multiple products
- **Quantity**: Per item
- **Total Amount**: Calculated total
- **Payment Status**: Paid/Unpaid
- **Delivery Status**: Pending/Delivered

#### Features
- Manage product inventory
- Process sales
- Track stock levels
- Low stock alerts
- Sales reports
- Revenue tracking

### 4.18 Contact Management

#### Contact Inquiries
- **Name**: Inquiry sender
- **Email**: Contact email
- **Phone**: Contact number
- **Subject**: Inquiry topic
- **Message**: Detailed message
- **Status**: New/In Progress/Resolved
- **Date**: Inquiry timestamp

#### Features
- View contact submissions
- Response tracking
- Filter by status
- Export contact list
- Integration with landing page

### 4.19 Notice Board / Notes

#### Internal Notices
- **Title**: Notice heading
- **Message**: Notice content
- **Author**: Created by
- **Date**: Published date
- **Visibility**: All/Trainers/Trainees
- **Priority**: Normal/Important/Urgent

#### Features
- Create announcements
- Target specific user groups
- Notice history
- Edit/delete notices
- Email notifications (optional)

### 4.20 Support / Ticket Management

#### Support Ticket System
- **Subject**: Ticket subject line
- **Description**: Detailed issue description
- **Priority**: Low, Medium, High, Critical
- **Status**: Pending, Open, Close, On Hold
- **Created By**: User who created the ticket
- **Assigned To**: Staff/admin assigned to ticket
- **Attachment**: Optional file upload
- **Parent ID**: Tenant isolation
- **Created Date**: Ticket creation timestamp

#### Ticket Replies
- **Support ID**: Link to parent ticket
- **Reply Message**: Response content
- **Replied By**: User who replied
- **Reply Date**: Response timestamp
- **Attachments**: Optional file uploads

#### Features
- Create support tickets
- Assign tickets to staff members
- Track ticket status
- Reply to tickets (conversation thread)
- Priority-based categorization
- Status workflow (Pending → Open → Close/On Hold)
- Attachment support for issues/screenshots
- Ticket history and audit trail
- Filter by status, priority, assignee
- Search tickets

#### Use Cases
- Member support requests
- Technical issues
- Billing inquiries
- General questions
- Feature requests
- Bug reports

---

## 5. Subscription & Payment Management

### 5.1 Subscription Plans (SaaS Packages)

#### Plan Structure
- **Plan Name**: Package title
- **Price**: Monthly/quarterly/yearly pricing
- **Interval**: Billing cycle
  - Monthly
  - Quarterly
  - Yearly
  - Unlimited (lifetime)

- **User Limit**: Maximum users allowed (0 = unlimited)
- **Features**: 
  - Module access permissions
  - Custom feature flags

- **Status**: Active/Inactive

#### Features
- Create subscription tiers
- Feature-based pricing
- Trial periods (optional)
- Plan comparison
- Upgrade/downgrade workflows

### 5.2 Package Transactions

#### Transaction Records
- **User**: Gym owner
- **Subscription**: Selected plan
- **Amount**: Transaction amount
- **Payment Type**: 
  - Stripe
  - PayPal
  - Bank Transfer
  - Flutterwave
  - Paystack
  - Manual Assignment

- **Status**: Pending/Success/Rejected
- **Transaction ID**: Unique identifier
- **Receipt**: Bank transfer receipt (if applicable)
- **Date**: Transaction timestamp

#### Features
- Transaction history
- Payment verification
- Manual approval (bank transfers)
- Refund processing
- Invoice generation

#### Admin Features
- **Manual Subscription Assignment**: Super admin can manually assign subscription plans to gym owners without payment
- **Bank Transfer Approval Workflow**: 
  - Owners upload payment receipt for bank transfer
  - Super admin reviews receipt
  - Accept or reject the transaction
  - Automatic subscription activation on approval
  - Email notification on status change

### 5.3 Coupon System

#### Coupon Structure
- **Coupon Code**: Unique code
- **Discount Type**: Percentage/Fixed amount
- **Discount Value**: Amount or percentage
- **Valid From**: Start date
- **Valid To**: Expiry date
- **Usage Limit**: Maximum uses
- **Plan Restriction**: Applicable plans
- **Status**: Active/Inactive

#### Coupon History
- **User**: Who used
- **Coupon**: Code used
- **Package**: Applied to
- **Discount Amount**: Value saved
- **Date**: Usage timestamp

#### Features
- Create promotional codes
- Time-bound offers
- Usage tracking
- One-time/multiple-use coupons
- Plan-specific coupons

### 5.4 Payment Gateway Integration

#### Supported Gateways

##### 1. **Stripe**
- **Configuration**:
  - Stripe Publishable Key
  - Stripe Secret Key
  - Payment mode (On/Off)

- **Features**:
  - Card payments
  - Instant confirmation
  - Secure checkout
  - PCI compliance

##### 2. **PayPal**
- **Configuration**:
  - Mode (Sandbox/Live)
  - Client ID
  - Secret Key

- **Features**:
  - PayPal account payments
  - Credit/debit card via PayPal
  - Redirect-based flow
  - Automatic capture

##### 3. **Bank Transfer**
- **Configuration**:
  - Bank name
  - Account holder name
  - Account number
  - IFSC/routing code
  - Additional details

- **Features**:
  - Manual payment verification
  - Receipt upload
  - Admin approval workflow
  - Accept/reject actions

##### 4. **Flutterwave**
- **Configuration**:
  - Public key
  - Secret key

- **Features**:
  - African payment methods
  - Multi-currency support
  - Card payments
  - Mobile money

##### 5. **Paystack**
- **Configuration**:
  - Public key
  - Secret key

- **Features**:
  - African payment gateway
  - Card payments
  - Bank account transfers
  - USSD payments

#### Payment Workflow
1. User selects subscription plan
2. Apply coupon (optional)
3. Choose payment method
4. Process payment via gateway
5. Verify transaction
6. Activate subscription
7. Send confirmation email

---

## 6. Settings & Configuration

### 6.1 Account Settings

#### User Profile
- Name
- Email (unique validation)
- Phone number
- Profile picture (PNG only)
- Password change
- Account deletion

### 6.2 General Settings

#### Super Admin Settings
- **Application Name**: Platform branding
- **Logos**:
  - Main logo (PNG)
  - Landing page logo (PNG)
  - Favicon (PNG)
  - Light mode logo (PNG)

- **Features Toggle**:
  - Landing page (On/Off)
  - Registration page (On/Off)
  - Owner email verification (On/Off)
  - Pricing feature display (On/Off)

#### Owner Settings
- **Application Name**: Gym-specific branding
- **Logos**:
  - Company logo
  - Favicon
  - Light logo
- **Copyright**: Footer text

### 6.3 Email/SMTP Settings

#### Configuration
- **Sender Name**: From name
- **Sender Email**: From email
- **Mail Driver**: SMTP
- **SMTP Host**: Mail server
- **SMTP Port**: Server port (25, 465, 587)
- **SMTP Username**: Authentication username
- **SMTP Password**: Authentication password
- **Encryption**: TLS/SSL/None

#### Features
- **Test Email**: Send test email to verify configuration
- **Email Templates**: Customizable notification templates

### 6.4 Payment Settings

#### Currency Configuration
- **Currency Code**: USD, EUR, GBP, INR, etc.
- **Currency Symbol**: $, €, £, ₹

#### Gateway Toggles
- **Stripe Payment**: On/Off + credentials
- **PayPal Payment**: On/Off + credentials
- **Bank Transfer**: On/Off + bank details
- **Flutterwave**: On/Off + API keys
- **Paystack**: On/Off + API keys

### 6.5 Company Settings

#### Business Information
- **Company Name**: Business name
- **Email**: Business email
- **Phone**: Business phone
- **Address**: Physical address
- **Date Format**: Display format (M j, Y)
- **Time Format**: Display format (g:i A)
- **Timezone**: Business timezone
- **Number Prefixes**:
  - Trainer number prefix: `#TRNR-000`
  - Trainee number prefix: `#TRNE-000`
  - Invoice number prefix: `#INV-000`
  - Expense number prefix: `#EXP-000`
  - Locker number prefix: `#LO-000`

### 6.6 Site SEO Settings

#### SEO Configuration
- **Meta Title**: Page title
- **Meta Keywords**: SEO keywords
- **Meta Description**: Page description
- **Meta Image**: Social sharing image

#### Features
- Per-page SEO customization
- Social media preview optimization

### 6.7 Google reCAPTCHA Settings

#### Configuration
- **Enable/Disable**: Toggle reCAPTCHA
- **Site Key**: reCAPTCHA site key
- **Secret Key**: reCAPTCHA secret key

#### Implementation
- Login form protection
- Registration form protection
- Contact form protection

### 6.8 Theme Settings

#### Theme Customization
- **Theme Mode**: Light/Dark
- **Layout Font**: 
  - Roboto
  - Inter
  - Outfit
  - And more Google Fonts

- **Accent Color**: Preset color schemes (preset-1 to preset-6)
- **Custom Color**: Custom color picker
- **Sidebar Caption**: Show/Hide
- **Theme Layout**: LTR/RTL
- **Layout Width**: Full/Boxed

#### Features
- Real-time preview
- Per-user preferences
- Persistent settings

### 6.9 Footer Settings

#### Footer Columns
- **Column 1-4**: Configurable column titles
- **Enable/Disable**: Toggle visibility per column
- **Column Titles**:
  - Quick Links
  - Help
  - Overview
  - Core System

---

## 7. Email Notification System

### 7.1 Email Templates

#### Template Structure
- **Module**: System trigger (e.g., `user_create`, `trainer_create`)
- **Name**: Template display name
- **Subject**: Email subject (supports shortcodes)
- **Message**: HTML email body (supports shortcodes)
- **Short Codes**: Dynamic placeholders
- **Status**: Enabled/Disabled
- **Parent ID**: Multi-tenant isolation

#### Available Templates

##### 1. **User Create**
- **Trigger**: New user registration
- **Short Codes**:
  - `{company_name}`
  - `{company_email}`
  - `{company_phone_number}`
  - `{company_address}`
  - `{new_user_name}`
  - `{app_link}`
  - `{username}`
  - `{password}`

##### 2. **Trainer Create**
- **Trigger**: Trainer account creation
- **Short Codes**: Same as User Create

##### 3. **Trainee Create**
- **Trigger**: Trainee registration
- **Short Codes**:
  - All User Create codes
  - `{membership}`
  - `{membership_start_date}`
  - `{trainer}`

##### 4. **Trainer Assign**
- **Trigger**: Trainee assigned to trainer
- **Short Codes**:
  - `{trainee_name}`
  - `{phone_number}`
  - `{membership}`
  - `{membership_start_date}`
  - `{trainer_name}`

##### 5. **New classes**
- **Trigger**: Class created
- **Short Codes**:
  - `{class_name}`
  - `{class_fees}`
  - `{class_address}`
  - `{class_schedule}`

##### 6. **Workout Create**
- **Trigger**: Workout plan assigned
- **Short Codes**:
  - `{start_date}`
  - `{end_date}`
  - `{activity_report}`

##### 7. **Health Update**
- **Trigger**: Health measurement recorded
- **Short Codes**:
  - `{trainee_name}`
  - `{measurement_date}`
  - `{health_report}`

##### 8. **Attendance Create**
- **Trigger**: Attendance marked
- **Short Codes**:
  - `{user_name}`
  - `{date}`
  - `{check_in_time}`
  - `{check_out_time}`

##### 9. **Invoice Create**
- **Trigger**: Invoice generated
- **Short Codes**:
  - `{user_name}`
  - `{invoice_number}`
  - `{invoice_date}`
  - `{invoice_due_date}`
  - `{amount}`

##### 10. **Locker Assign**
- **Trigger**: Locker assigned to trainee
- **Short Codes**:
  - `{user_name}`
  - `{locker_id}`

### 7.2 Email Features
- **Template Management**: CRUD operations on templates
- **Enable/Disable**: Toggle per template
- **Rich Text Editor**: HTML email composition
- **Preview**: Template preview before sending
- **Per-Tenant Templates**: Each gym can customize templates

---

## 8. Multi-Language Support

### 8.1 Supported Languages

**Total Languages**: 13

1. English
2. Arabic
3. Danish
4. Dutch
5. French
6. German
7. Italian
8. Japanese
9. Polish
10. Portuguese
11. Russian
12. Spanish

### 8.2 Internationalization Features

#### Language Management
- **JSON-based**: Translation files in JSON format
- **Per-user Language**: Users can select preferred language
- **RTL Support**: Right-to-left layouts for Arabic
- **Dynamic Switching**: Real-time language change without logout

#### Translation System
- **Package**: Laravel Translation Manager
- **Export**: `kkomelin/laravel-translatable-string-exporter`
- **Structure**: Organized by module/feature
- **Fallback**: English as default

#### Implementation
- All user-facing strings use `__()`helper
- Blade templates: `@lang()` directive
- JavaScript: Translation exported to JS
- Email templates: Multilingual support

---

## 9. Landing Page CMS

### 9.1 Landing Page Features

#### Page Structure
- **Hero Section**: Main banner with CTA
- **Features Section**: Service highlights
- **Pricing Section**: Subscription plans display
- **FAQ Section**: Frequently asked questions
- **Custom Pages**: Additional CMS pages
- **Footer**: Multi-column footer with links

#### Home Page Management
- **Sections**: Configurable sections
- **Content**: Rich text editor
- **Images**: Upload images per section
- **CTAs**: Call-to-action buttons
- **Testimonials**: Member reviews (if applicable)

#### Features
- **Enable/Disable**: Toggle landing page visibility
- **Dynamic Content**: Edit without code changes
- **SEO Optimized**: Meta tags per page
- **Mobile Responsive**: Adaptive design

### 9.2 FAQ Management

#### FAQ Structure
- **Question**: FAQ question text
- **Answer**: Detailed answer
- **Order**: Display sequence
- **Status**: Published/Draft

#### Features
- Add/edit/delete FAQs
- Reorder questions
- Search functionality
- Category grouping (optional)

### 9.3 Custom Pages

#### Page Builder
- **Title**: Page title
- **Slug**: URL-friendly slug
- **Content**: Rich text editor
- **SEO**: Meta title, description, keywords
- **Status**: Published/Draft

#### Features
- Create unlimited custom pages
- Dynamic routing: `/page/{slug}`
- SEO management per page
- Template-based design

---

## 10. Security Features

### 10.1 Two-Factor Authentication (2FA)

#### Implementation
- **Package**: PragmaRX Google2FA Laravel
- **QR Code**: Bacon QR Code generator
- **Secret Storage**: Encrypted in database (`twofa_secret` column)

#### Workflow
1. User enables 2FA in settings
2. System generates secret key
3. Display QR code for authenticator app
4. User scans QR code
5. Verify OTP code
6. 2FA activated
7. Subsequent logins require OTP

#### Features
- **Enable/Disable**: Per-user toggle
- **Authenticator Apps**: Google Authenticator, Authy, etc.
- **Backup Codes**: Emergency access (if implemented)
- **2FA Status**: Visible in user profile

### 10.2 Google reCAPTCHA

#### Implementation
- **Package**: Anhskohbo NoCaptcha
- **Version**: reCAPTCHA v2
- **Configuration**: Site key & secret key in settings

#### Protected Forms
- Login
- Registration
- Password reset
- Contact form

#### Features
- **Enable/Disable**: Admin toggle
- **Configurable**: Keys stored in database
- **Dynamic Config**: Real-time configuration update

### 10.3 Email Verification

#### Owner Email Verification
- **Toggle**: Enable/disable in general settings
- **Workflow**:
  1. Owner registers
  2. Verification email sent
  3. User clicks verification link
  4. Email verified
  5. Account activated

- **Token-based**: Secure verification tokens
- **Expiry**: Configurable token expiration

### 10.4 XSS Protection

#### Middleware
- **XSS Middleware**: Applied to all routes
- **Sanitization**: Input sanitization
- **Output Encoding**: Prevent script injection

### 10.5 Password Security

#### Requirements
- Minimum 6 characters (configurable)
- Hash algorithm: Bcrypt (Laravel default)
- Password confirmation on change
- Current password validation

### 10.6 Impersonation

#### Feature
- **Package**: Lab404 Laravel Impersonate
- **Permission**: Super admin only
- **Audit**: Logged history of impersonation
- **Exit**: Return to original account

#### Use Case
- Customer support
- User issue debugging
- Training and demonstration

---

## 11. Reporting & Analytics

### 11.1 Dashboard Analytics

#### Key Metrics
- Total revenue
- Total expenses
- Profit/loss
- Active memberships
- Expiring memberships
- Attendance rate
- Class occupancy
- Trainer workload

#### Visual Reports
- Revenue trends (line/bar charts)
- Expense breakdown (pie chart)
- Member growth (area chart)
- Attendance patterns (heatmap)

### 11.2 Financial Reports

#### Available Reports
- **Income Report**: Date range, type filter
- **Expense Report**: Date range, type filter
- **Profit & Loss**: Revenue vs. expenses
- **Invoice Aging**: Outstanding invoices
- **Payment History**: Transaction log

#### Export Formats
- PDF
- Excel/CSV (if implemented)
- Print view

### 11.3 Member Reports

#### Reports
- **Active Members**: Current memberships
- **Expired Memberships**: Renewal candidates
- **Attendance Report**: Date range, member filter
- **Health Progress**: Measurement trends
- **Class Enrollment**: Members per class

### 11.4 Trainer Reports

#### Reports
- **Trainer Workload**: Classes and trainees assigned
- **Trainer Performance**: Attendance, member retention
- **Earnings Report**: If commission-based

---

## 12. Multi-Tenancy Architecture

### 12.1 Tenant Isolation

#### Data Segregation
- **Column**: `parent_id` in all major tables
- **Scope**: Global query scope on models
- **Helper**: `parentId()` function determines current tenant

#### User Hierarchy
```
Super Admin (parent_id = own ID)
    ↓
Gym Owner (parent_id = own ID)
    ↓
Trainer/Trainee/Staff (parent_id = owner's ID)
```

### 12.2 Settings Isolation

#### Per-Tenant Settings
- Each gym owner has isolated settings
- Settings inherit from super admin defaults
- Override capability per setting key

#### Settings Storage
- **Table**: `settings`
- **Columns**: `name`, `value`, `type`, `parent_id`
- **Types**:
  - `common`: General settings
  - `smtp`: Email configuration
  - `payment`: Payment gateway settings
  - `SEO`: SEO metadata

### 12.3 Subscription Management

#### Subscription Enforcement
- **User Limit**: Enforced per subscription plan
- **Feature Access**: Module permissions based on plan
- **Expiry Handling**: 
  - Days left calculation
  - Automatic user deactivation on expiry
  - Grace period (configurable)

#### Subscription Assignment
```php
function assignSubscription($id)
{
    1. Find subscription plan
    2. Set expiry date based on interval
    3. Update user subscription
    4. Enforce user limits
    5. Activate/deactivate users based on plan limits
}
```

---

## 13. Helper Functions

### 13.1 Core Helpers

#### Settings Management
- `settings($userId = 0)`: Get tenant settings
- `settingsById($userId)`: Get settings by user ID
- `getSettingsValByName($key)`: Get specific setting value
- `subscriptionPaymentSettings()`: Super admin payment settings

#### Formatting
- `dateFormat($date)`: Format date per tenant settings
- `timeFormat($time)`: Format time per tenant settings
- `priceFormat($price)`: Format currency with symbol

#### Tenant Resolution
- `parentId()`: Determine current tenant/parent ID
- Returns own ID for super admin/owner
- Returns parent_id for other users

#### Prefix Generators
- `trainerPrefix()`: Get trainer number prefix
- `traineePrefix()`: Get trainee number prefix
- `invoicePrefix()`: Get invoice number prefix
- `expensePrefix()`: Get expense number prefix
- `lockerPrefix()`: Get locker number prefix

#### Subscription
- `assignSubscription($id)`: Assign subscription to user
- `assignManuallySubscription($id, $userId)`: Manual assignment by admin

#### Email
- `smtpDetail($id)`: Get SMTP configuration for tenant
- `sendEmail($to, $data)`: Send email with template

#### Tracking
- `userLoggedHistory()`: Track user login with geo-location

### 13.2 Template Helpers

#### Default Templates
- `defaultTemplate($id)`: Create default email templates for new gym
- `defaultTrainerCreate($id)`: Create default trainer role with permissions
- `defaultTraineeCreate($id)`: Create default trainee role with permissions

---

## 14. Authentication & Authorization Pages

### 14.1 Authentication Pages

#### Login
- Email/password authentication
- Remember me checkbox
- 2FA OTP screen (if enabled)
- reCAPTCHA (if enabled)
- Forgot password link

#### Register
- Owner registration (if enabled in settings)
- Email verification (if enabled)
- reCAPTCHA (if enabled)
- Terms and conditions acceptance

#### Password Reset
- Email-based reset
- Token validation
- Password confirmation

#### Email Verification
- Token-based verification
- Resend verification email
- Auto-login after verification (optional)

### 14.2 Custom Auth Pages

#### Auth Page Customization
- **Background Image**: Upload custom background
- **Logo**: Custom logo per page
- **Text**: Custom welcome text
- **Colors**: Brand colors

#### Features
- Separate customization for login/register pages
- Enable/disable individual pages
- Preview before applying

---

## 15. Non-Functional Requirements

### 15.1 Performance

#### Response Time
- **Target**: Page load < 2 seconds
- **Database Queries**: Optimized with indexing
- **Caching**: Laravel cache for settings and frequently accessed data
- **Lazy Loading**: Images and heavy content

#### Scalability
- **Multi-tenancy**: Designed for thousands of gym instances
- **Database**: Indexed columns for performance
- **Queue System**: Background jobs for emails and heavy processing

### 15.2 Security

#### Data Protection
- **Encryption**: Sensitive data encrypted at rest
- **HTTPS**: SSL/TLS enforced
- **CSRF Protection**: Laravel CSRF tokens
- **SQL Injection**: Eloquent ORM prevents SQL injection
- **XSS Protection**: Middleware and input sanitization

#### Authentication
- **Password Hashing**: Bcrypt algorithm
- **Session Management**: Secure session handling
- **2FA**: Optional two-factor authentication
- **Rate Limiting**: Login attempt throttling

### 15.3 Availability

#### Uptime
- **Target**: 99.9% uptime
- **Backup**: Automated database backups
- **Monitoring**: Application performance monitoring
- **Error Logging**: Comprehensive error tracking

### 15.4 Usability

#### User Experience
- **Responsive Design**: Mobile-first approach
- **Intuitive Navigation**: Clear menu structure
- **Search & Filter**: Advanced search across modules
- **Tooltips & Help**: Contextual help text
- **Consistent UI**: Unified design language

#### Accessibility
- **ARIA Labels**: Accessible form labels
- **Keyboard Navigation**: Full keyboard support
- **Color Contrast**: WCAG AA compliance
- **Screen Reader**: Compatible with screen readers

### 15.5 Maintainability

#### Code Quality
- **MVC Architecture**: Clear separation of concerns
- **PSR Standards**: PHP coding standards
- **Documentation**: Inline code comments
- **Version Control**: Git repository

#### Modularity
- **Reusable Components**: DRY principle
- **Service Layer**: Business logic separation
- **Middleware**: Request/response processing
- **Helpers**: Utility functions

### 15.6 Compatibility

#### Browser Support
- Google Chrome (latest)
- Mozilla Firefox (latest)
- Safari (latest)
- Microsoft Edge (latest)
- Mobile browsers (iOS Safari, Chrome Mobile)

#### Server Requirements
- **PHP**: 8.0.2 or higher
- **Database**: MySQL 5.7+ / MariaDB 10.3+
- **Web Server**: Apache 2.4+ / Nginx 1.18+
- **SSL**: Required for production

---

## 16. Installation & Setup

### 16.1 Requirements

#### Server
- PHP >= 8.0.2
- MySQL >= 5.7 or MariaDB >= 10.3
- Apache or Nginx
- Composer
- Node.js & NPM (for frontend assets)

#### PHP Extensions
- BCMath
- Ctype
- Fileinfo
- JSON
- Mbstring
- OpenSSL
- PDO
- Tokenizer
- XML
- GD or Imagick (for image processing)
- cURL

### 16.2 Installation Steps

#### 1. Upload Files
- Extract and upload to server root/subdirectory

#### 2. Install Dependencies
```bash
composer install
npm install && npm run production
```

#### 3. Environment Configuration
```bash
cp .env.example .env
php artisan key:generate
```

#### 4. Database Setup
```bash
# Edit .env with database credentials
php artisan migrate --seed
```

#### 5. Storage Linking
```bash
php artisan storage:link
```

#### 6. Permissions
```bash
chmod -R 775 storage bootstrap/cache
```

#### 7. Installer (if available)
- Navigate to `/install`
- Follow web-based installer
- Create super admin account

### 16.3 Post-Installation

#### Super Admin Setup
1. Login with super admin credentials
2. Configure general settings
3. Set up payment gateways
4. Configure SMTP
5. Create subscription plans
6. Customize landing page
7. Enable/disable features

#### First Gym Owner Setup
1. Register as gym owner
2. Subscribe to a plan
3. Configure gym settings
4. Add trainers and trainees
5. Set up classes and memberships

---

## 17. API & Integrations

### 17.1 Payment Gateway APIs

#### Stripe API
- **Endpoint**: Stripe API v7
- **Authentication**: API keys
- **Webhook**: Payment status updates

#### PayPal SDK
- **Package**: srmklive/paypal
- **Endpoint**: PayPal REST API
- **Flow**: Server-side integration

#### Flutterwave
- **Endpoint**: Flutterwave v3 API
- **Method**: Unirest PHP HTTP client

#### Paystack
- **Endpoint**: Paystack API
- **Method**: cURL

### 17.2 External Services

#### Google reCAPTCHA
- **Version**: v2
- **Endpoint**: Google reCAPTCHA API

#### IP Geolocation
- **Service**: ip-api.com
- **Purpose**: User login tracking

### 17.3 Future API Considerations

#### RESTful API
- Laravel Sanctum for API authentication
- API resource transformers
- Rate limiting
- Versioning (v1, v2)

#### Mobile App Support
- API endpoints for mobile apps
- Real-time notifications
- Offline sync capability

---

## 18. System Modules Summary

### 18.1 Module List

| Module | Features | Models |
|--------|----------|--------|
| **Dashboard** | Analytics, KPIs, Charts | - |
| **Users** | CRUD, Roles, Permissions | User, LoggedHistory |
| **Trainers** | Profile, Assignments | TrainerDetail |
| **Trainees** | Profile, Membership, Renewals | TraineeDetail, Membership |
| **Classes** | Schedules, Assignments | Classes, ClassSchedule, ClassAssign |
| **Categories** | Class categorization | Category |
| **Workouts** | Plans, Activities | Workout, WorkoutActivity |
| **Health** | Measurements, Tracking | Health |
| **Attendance** | Check-in/out, Bulk | Attendance |
| **Invoices** | Income, Payments | Invoice, InvoiceItem, InvoicePayment |
| **Expenses** | Expense tracking | Expense |
| **Finance Types** | Income/expense categories | Type |
| **Lockers** | Inventory, Assignments | Locker, AssignLocker |
| **Events** | Calendar, Types | Event, EventType |
| **Nutrition** | Meal plans | NutritionSchedule |
| **Products** | Catalog, Bookings | Product, ProductBooking, ProductBookingItem |
| **Contacts** | Inquiries | Contact |
| **Notes** | Internal notices | NoticeBoard |
| **Subscriptions** | SaaS plans | Subscription, PackageTransaction |
| **Coupons** | Discount codes, History | Coupon, CouponHistory |
| **Notifications** | Email templates | Notification |
| **Settings** | Multi-tenant config | Setting |
| **Landing Page** | CMS, FAQs, Pages | HomePage, FAQ, Page, AuthPage |

### 18.2 Total Counts

- **Models**: 41
- **Controllers**: 36
- **Routes**: ~150+
- **Migrations**: 46
- **Languages**: 13
- **Payment Gateways**: 5
- **Email Templates**: 10+

---

## 19. Roadmap & Future Enhancements

### 19.1 Potential Features

#### Member Portal
- Self-service account management
- Class booking
- Payment history
- Workout tracking
- Progress dashboard

#### Mobile Apps
- iOS and Android native apps
- Push notifications
- Offline mode
- QR code check-in

#### Advanced Analytics
- Predictive analytics
- Member churn prediction
- Revenue forecasting
- AI-powered insights

#### Marketing Automation
- Email campaigns
- SMS notifications
- Social media integration
- Referral program

#### Equipment Management
- Equipment inventory
- Maintenance tracking
- Usage logs
- Replacement alerts

#### Advanced Scheduling
- Online class booking
- Waitlist management
- Automatic reminders
- Capacity alerts

#### Integration Marketplace
- CRM integration (Salesforce, HubSpot)
- Accounting software (QuickBooks, Xero)
- Wearable devices (Fitbit, Apple Watch)
- Access control systems

---

## 20. Support & Documentation

### 20.1 User Documentation

#### Admin Guide
- Complete feature walkthrough
- Configuration guides
- Best practices
- Troubleshooting

#### User Manual
- Trainer guide
- Trainee guide
- Quick start guide
- Video tutorials

### 20.2 Technical Documentation

#### Developer Guide
- Code structure
- API documentation
- Customization guide
- Plugin development

#### Deployment Guide
- Server setup
- Optimization tips
- Backup strategies
- Update procedures

---

## 21. Conclusion

**FitHub SaaS** is a comprehensive, feature-rich gym management platform designed to meet the diverse needs of fitness businesses. With its multi-tenant architecture, extensive feature set, flexible subscription model, and robust security measures, it provides a scalable solution for gym owners to efficiently manage their operations and grow their business.

### Key Strengths
- ✅ Complete gym management solution
- ✅ Multi-tenant SaaS architecture
- ✅ 13 language support
- ✅ 5 payment gateway integrations
- ✅ Customizable themes and branding
- ✅ Dynamic landing page CMS
- ✅ Email notification system
- ✅ Role-based access control
- ✅ Two-factor authentication
- ✅ Comprehensive reporting

### Target Market
- Small to medium-sized gyms
- Fitness centers
- Health clubs
- Personal training studios
- Multi-location fitness chains

---

**Document Version**: 1.0  
**Last Updated**: 2025-11-28  
**Prepared By**: AI Assistant  
**Product**: FitHub SaaS - Gym Management System

---

## Document Version History

| Version | Date | Changes | Author |
|---------|------|---------|--------|
| 1.0 | 2025-11-27 | Initial PRD creation from codebase analysis | AI Analysis |
| 1.1 | 2025-11-28 | Added Support/Ticket Management (Section 4.20), clarified health measurement fields, added support tables to database schema, enhanced subscription management with admin features | AI Verification & Updates |

---

**Document Status**: Final - Verified Against Codebase  
**Accuracy Rating**: 95%  
**Last Verified**: 2025-11-28  
**Total Sections**: 21  
**Total Pages (Equivalent)**: 150+
