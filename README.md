<div align="center">

# 🏋️ GymFlow

### Enterprise Gym Management & Multi-Tenant SaaS Platform

A comprehensive, production-ready gym management system built with Laravel 12, featuring multi-tenant architecture, payment processing, and complete business operations management.

[![Laravel](https://img.shields.io/badge/Laravel-12.x-red.svg)](https://laravel.com)
[![PHP](https://img.shields.io/badge/PHP-8.3-blue.svg)](https://php.net)
[![Tailwind CSS](https://img.shields.io/badge/Tailwind_CSS-4.x-38B2AC.svg)](https://tailwindcss.com)
[![License](https://img.shields.io/badge/License-MIT-green.svg)](LICENSE)
[![PRs Welcome](https://img.shields.io/badge/PRs-welcome-brightgreen.svg)](CONTRIBUTING.md)

[Features](#-features) • [Tech Stack](#-tech-stack) • [Installation](#-installation) • [Demo](#-demo-credentials) • [Contributing](#-contributing)

</div>

---

## 📋 About The Project

**GymFlow** is a full-featured, multi-tenant gym management platform designed to handle all aspects of fitness center operations. Built with modern Laravel practices, this project demonstrates enterprise-level application architecture, payment integration, security best practices, and comprehensive testing.

### 🎯 Purpose

This is an **open-source portfolio project** showcasing:
- Multi-tenant SaaS architecture
- Payment gateway integration (Stripe & PayPal)
- Role-based access control with advanced security
- RESTful API design
- Comprehensive testing practices
- Modern frontend development (Tailwind CSS, Alpine.js)
- Professional documentation

---

## ✨ Features

### 🔐 Authentication & Security
- ✅ Email verification with secure token generation
- ✅ Two-Factor Authentication (2FA) with Google Authenticator
- ✅ Role-based access control (RBAC) using Spatie Permissions
- ✅ Activity logging and audit trails
- ✅ User impersonation (Super Admin feature)
- ✅ Secure password reset flow
- ✅ Rate limiting and XSS/CSRF protection

### 👥 Member Management
- ✅ Complete member profiles with unique IDs
- ✅ Health & body metrics tracking (weight, BMI, measurements)
- ✅ Emergency contact management
- ✅ Membership plan assignment with auto-expiry
- ✅ Member attendance tracking (check-in/out)
- ✅ Workout logging with exercise activities

### 🏋️ Gym Operations
- ✅ Trainer management with specializations
- ✅ Class scheduling with capacity management
- ✅ Class categories and assignments
- ✅ Locker inventory and assignments
- ✅ Event management with participant tracking
- ✅ Notice board with priority announcements

### 💰 Financial Management
- ✅ Invoice generation with line items
- ✅ Payment tracking and history
- ✅ Expense management with categories
- ✅ Subscription billing (Stripe & PayPal)
- ✅ Payment webhooks for automated processing
- ✅ Revenue and financial reporting

### 🎫 Subscription & Billing
- ✅ Multiple subscription tiers
- ✅ Automated recurring billing
- ✅ Stripe integration (cards, wallets)
- ✅ PayPal integration (alternative gateway)
- ✅ Payment transaction logging
- ✅ Subscription analytics

### 🛍️ Product & Inventory
- ✅ Product catalog with categories
- ✅ Product sales tracking
- ✅ Inventory management
- ✅ Sales reporting

### 🔔 Notifications & Communication
- ✅ Email notifications (SMTP)
- ✅ In-app database notifications
- ✅ Membership expiry reminders
- ✅ Payment confirmations
- ✅ Class reminders

### 🎨 CMS & Support
- ✅ Dynamic page creation (CMS)
- ✅ SEO-friendly content management
- ✅ Contact form with submissions
- ✅ Support ticket system with replies

### 🏢 Multi-Tenant Platform (Super Admin)
- ✅ Tenant (customer) management
- ✅ Platform-level subscription tiers
- ✅ Revenue analytics dashboard
- ✅ Customer analytics
- ✅ Subscription analytics
- ✅ Global platform settings
- ✅ Tenant isolation with database scoping

### 📊 Analytics & Reporting
- ✅ Dashboard with key metrics and charts
- ✅ Attendance reports
- ✅ Financial reports
- ✅ Member progress tracking
- ✅ Subscription analytics (MRR, churn rate)
- ✅ Revenue forecasting

---

## 🛠 Tech Stack

### Backend
- **Framework:** Laravel 12.x (PHP 8.3)
- **Authentication:** Laravel Sanctum 4.x
- **Authorization:** Spatie Laravel Permission 6.x
- **Database:** MySQL 8.0 / PostgreSQL
- **Queue:** Database driver (supports Redis)
- **Storage:** Local / S3-compatible

### Frontend
- **Template:** Velzon Admin Dashboard (Bootstrap 5)
- **CSS Framework:** Tailwind CSS 4.x + Bootstrap 5
- **JavaScript:** Alpine.js 2.x (bundled)
- **Data Tables:** Yajra DataTables
- **Charts:** ApexCharts & Chart.js
- **Calendar:** FullCalendar
- **Rich Text:** CKEditor
- **File Upload:** Filepond

### Payment Gateways
- **Stripe:** Stripe PHP SDK
- **PayPal:** PayPal REST API SDK

### Development Tools
- **Testing:** PHPUnit 11.x
- **Code Style:** Laravel Pint
- **Containerization:** Docker & Docker Compose
- **Build Tools:** Vite (Laravel Mix alternative)

### Additional Packages
- **2FA:** PragmaRX/Google2FA
- **Impersonation:** Lab404/Laravel Impersonate
- **DataTables:** Yajra/Laravel-DataTables
- **UI Scaffolding:** Laravel/UI

---

## 🚀 Installation

### Prerequisites

- PHP >= 8.3
- Composer
- Node.js >= 18.x & NPM
- MySQL 8.0+ or PostgreSQL 13+
- Git

### Step-by-Step Setup

1. **Clone the repository**
   ```bash
   git clone https://github.com/MujahidAbbas/gymflow.git
   cd gymflow
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install JavaScript dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

5. **Configure database in `.env`**
   ```env
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=gymflow
   DB_USERNAME=root
   DB_PASSWORD=
   ```

6. **Configure mail settings** (for email verification & notifications)
   ```env
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_username
   MAIL_PASSWORD=your_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS=noreply@gymflow.com
   MAIL_FROM_NAME="${APP_NAME}"
   ```

7. **Configure payment gateways** (optional for testing)
   ```env
   # Stripe
   STRIPE_KEY=your_stripe_publishable_key
   STRIPE_SECRET=your_stripe_secret_key
   STRIPE_WEBHOOK_SECRET=your_webhook_secret

   # PayPal
   PAYPAL_CLIENT_ID=your_paypal_client_id
   PAYPAL_SECRET=your_paypal_secret
   PAYPAL_MODE=sandbox  # or 'live' for production
   ```

8. **Run database migrations & seeders**
   ```bash
   php artisan migrate --seed
   ```

   This will create all 44 tables and seed:
   - Default roles and permissions
   - Super admin user
   - Sample members, trainers, classes
   - Demo subscription plans
   - Test data for all modules

9. **Build frontend assets**
   ```bash
   npm run build

   # For development with hot reload
   npm run dev
   ```

10. **Link storage** (for file uploads)
    ```bash
    php artisan storage:link
    ```

11. **Serve the application**
    ```bash
    php artisan serve
    ```

    Visit: `http://localhost:8000`

### 🐳 Docker Setup (Alternative)

```bash
# Start all services
docker-compose up -d

# Install dependencies
docker-compose exec app composer install
docker-compose exec app npm install && npm run build

# Run migrations
docker-compose exec app php artisan migrate --seed
```

---

## 🎭 Demo Credentials

After running `php artisan migrate --seed`, use these credentials:

### Super Admin (Platform Owner)
- **Email:** superadmin@gymflow.com
- **Password:** password
- **Access:** Full platform control, all tenants, analytics

### Gym Admin (Tenant)
- **Email:** admin@gymflow.com
- **Password:** password
- **Access:** Gym management, members, classes, billing

### Trainer
- **Email:** trainer@gymflow.com
- **Password:** password
- **Access:** Assigned classes, member workouts

### Member
- **Email:** member@gymflow.com
- **Password:** password
- **Access:** Personal profile, workouts, attendance

> **Note:** 2FA is optional. To enable, scan the QR code with Google Authenticator after login.

---

## 🧪 Testing

### Run All Tests
```bash
php artisan test
```

### Run Specific Test Suite
```bash
# Feature tests
php artisan test tests/Feature

# Unit tests
php artisan test tests/Unit

# Specific test file
php artisan test tests/Feature/AuthenticationTest.php

# Filter by test name
php artisan test --filter=testUserCanLogin
```

### Test Coverage (28 Tests)
- ✅ Authentication (login, registration, 2FA, email verification)
- ✅ Member management (CRUD, filtering)
- ✅ Gym classes & scheduling
- ✅ Workout tracking
- ✅ Attendance system
- ✅ Invoice & expense management
- ✅ Subscription purchasing
- ✅ Super admin analytics
- ✅ Support tickets
- ✅ Multi-tenant scoping

### Generate Coverage Report
```bash
php artisan test --coverage
```

---

## 🤝 Contributing

Contributions are welcome! Please read our [Contributing Guidelines](CONTRIBUTING.md) before submitting a Pull Request.

---

## 📝 License

This project is open-source and available under the [MIT License](LICENSE).

---

<div align="center">

**⭐ If you find this project helpful, please consider giving it a star!**

Made with ❤️ by [Mujahid Abbas](https://github.com/MujahidAbbas)

</div>
