# FitHub SaaS - Deployment Guide

## 🚀 Production Deployment

### Prerequisites
- PHP 8.3+ with required extensions
- MySQL 8.0+ or MariaDB
- Composer
- Node.js & NPM
- Web server (Nginx/Apache)

---

## Step 1: Server Setup

### Install Dependencies
```bash
# PHP Extensions Required
php -m | grep -E "pdo|mysql|mbstring|xml|curl|zip|gd|bcmath"

# Install Composer
curl -sS https://getcomposer.com/installer | php
sudo mv composer.phar /usr/local/bin/composer
```

---

## Step 2: Clone & Install

```bash
# Clone repository
cd /var/www
git clone https://github.com/your-repo/fithub-saas.git
cd fithub-saas

# Install dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build
```

---

## Step 3: Environment Configuration

```bash
# Copy environment file
cp .env.example .env

# Generate application key
php artisan key:generate
```

### Configure .env File

```env
APP_NAME="FitHub SaaS"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

# Database
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=fithub
DB_USERNAME=root
DB_PASSWORD=your_password

# Mail Configuration
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your@email.com
MAIL_PASSWORD=your_app_password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=your@email.com
MAIL_FROM_NAME="${APP_NAME}"

# Payment Gateways
STRIPE_KEY=pk_live_your_key
STRIPE_SECRET=sk_live_your_secret
STRIPE_WEBHOOK_SECRET=whsec_your_webhook_secret

PAYPAL_CLIENT_ID=your_client_id
PAYPAL_SECRET=your_secret
PAYPAL_MODE=live

# Queue
QUEUE_CONNECTION=database
```

---

## Step 4: Database Migration

```bash
# Run migrations
php artisan migrate --force

# Seed initial data (optional)
php artisan db:seed --class=SettingsSeeder
php artisan db:seed --class=PermissionSeeder
```

---

## Step 5: File Permissions

```bash
# Set ownership
sudo chown -R www-data:www-data /var/www/fithub-saas
sudo chmod -R 755 /var/www/fithub-saas

# Storage and cache
sudo chmod -R 775 storage bootstrap/cache
```

---

## Step 6: Optimize for Production

```bash
# Cache configuration
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Optimize composer
composer dump-autoload --optimize
```

---

## Step 7: Web Server Configuration

### Nginx Configuration

```nginx
server {
    listen 80;
    server_name yourdomain.com;
    root /var/www/fithub-saas/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.3-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

---

## Step 8: SSL Certificate (Let's Encrypt)

```bash
sudo apt install certbot python3-certbot-nginx
sudo certbot --nginx -d yourdomain.com
```

---

## Step 9: Queue Worker & Scheduler

### Setup Queue Worker (Supervisor)

```bash
sudo apt install supervisor

# Create config: /etc/supervisor/conf.d/fithub-worker.conf
[program:fithub-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/fithub-saas/artisan queue:work --sleep=3 --tries=3
autostart=true
autorestart=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/fithub-saas/storage/logs/worker.log

# Start supervisor
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start fithub-worker:*
```

### Setup Cron Jobs

```bash
crontab -e

# Add this line:
* * * * * cd /var/www/fithub-saas && php artisan schedule:run >> /dev/null 2>&1
```

---

## Step 10: Payment Gateway Webhooks

### Stripe Webhook
- URL: `https://yourdomain.com/webhooks/stripe`
- Events: `checkout.session.completed`, `payment_intent.succeeded`
- Copy webhook secret to `.env` as `STRIPE_WEBHOOK_SECRET`

### PayPal Webhook
- URL: `https://yourdomain.com/webhooks/paypal`
- Events: `PAYMENT.SALE.COMPLETED`

---

##  11: Security Checklist

- [ ] Set `APP_DEBUG=false`
- [ ] Use strong `APP_KEY`
- [ ] Enable HTTPS only
- [ ] Configure firewall (UFW)
- [ ] Set proper file permissions (755/644)
- [ ] Enable rate limiting
- [ ] Regular backups
- [ ] Keep dependencies updated

---

## 🔄 Updating Application

```bash
# Pull latest changes
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install && npm run build

# Run migrations
php artisan migrate --force

# Clear & rebuild cache
php artisan config:clear
php artisan cache:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Restart queue workers
sudo supervisorctl restart fithub-worker:*
```

---

## 📊 Monitoring

### Check Queue Status
```bash
php artisan queue:work --once
php artisan queue:failed
```

### View Logs
```bash
tail -f storage/logs/laravel.log
```

### Database Backup
```bash
# Daily backup script
#!/bin/bash
mysqldump -u root -p fithub > /backups/fithub-$(date +%F).sql
```

---

## 🆘 Troubleshooting

### Issue: 500 Error
```bash
# Check logs
tail -f storage/logs/laravel.log

# Verify permissions
sudo chown -R www-data:www-data storage bootstrap/cache
```

### Issue: Stripe Payments Failing
- Verify webhook endpoint is accessible
- Check `STRIPE_WEBHOOK_SECRET` matches Stripe dashboard
- Test with Stripe CLI: `stripe listen --forward-to localhost/webhooks/stripe`

### Issue: Queue Jobs Not Processing
```bash
# Check supervisor status
sudo supervisorctl status

# Restart workers
sudo supervisorctl restart fithub-worker:*
```

---

**Deployment Complete! 🎉**

Your FitHub SaaS application is now live and ready to manage gyms worldwide.
