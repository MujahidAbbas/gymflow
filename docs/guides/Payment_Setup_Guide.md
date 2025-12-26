# Payment Gateway Setup Guide

## 🔐 Stripe Integration

### Step 1: Create Stripe Account
1. Go to [stripe.com](https://stripe.com) and create an account
2. Navigate to **Developers → API Keys**
3. Copy your keys:
   - Publishable key (pk_test_... for testing, pk_live_... for production)
   - Secret key (sk_test_... for testing, sk_live_... for production)

### Step 2: Configure .env
```env
STRIPE_KEY=pk_test_51JxYz... # Your publishable key
STRIPE_SECRET=sk_test_51JxYz... # Your secret key
STRIPE_WEBHOOK_SECRET=whsec_... # From webhook setup below
```

### Step 3: Setup Webhooks
1. Go to **Developers → Webhooks**
2. Click "Add endpoint"
3. Enter URL: `https://yourdomain.com/webhooks/stripe`
4. Select events:
   - `checkout.session.completed`
   - `payment_intent.succeeded`
   - `payment_intent.payment_failed`
5. Copy "Signing secret" → add to `.env` as `STRIPE_WEBHOOK_SECRET`

### Step 4: Test with Stripe CLI (Optional)
```bash
# Install Stripe CLI
brew install stripe/stripe-brew/stripe

# Login
stripe login

# Forward webhooks to local
stripe listen --forward-to localhost:8000/webhooks/stripe

# Test payment
stripe trigger checkout.session.completed
```

### Test Credit Cards
```
Success: 4242 4242 4242 4242 (any future date, any CVC)
Decline: 4000 0000 0000 0002
Insufficient funds: 4000 0000 0000 9995
```

---

## 💳 PayPal Integration

### Step 1: Create PayPal Business Account
1. Go to [developer.paypal.com](https://developer.paypal.com)
2. Log in or create account
3. Navigate to **My Apps & Credentials**

### Step 2: Create App
1. Click "Create App"
2. Enter app name: "FitHub SaaS"
3. Choose "Merchant" as app type
4. Copy credentials:
   - **Client ID**
   - **Secret**

### Step 3: Configure .env
```env
PAYPAL_CLIENT_ID=AZXfT... # Your client ID
PAYPAL_SECRET=EL9W... # Your secret
PAYPAL_MODE=sandbox # Use 'live' for production
```

### Step 4: Setup Webhooks
1. In PayPal Dashboard → **Webhooks**
2. Click "Add Webhook"
3. Enter URL: `https://yourdomain.com/webhooks/paypal`
4. Select events:
   - `PAYMENT.SALE.COMPLETED`
   - `PAYMENT.SALE.REFUNDED`

### Sandbox Test Accounts
PayPal provides test buyer/seller accounts in sandbox mode.
- Buyer: sb-buyer@business.example.com
- Password: Available in sandbox accounts section

---

## 🔄 Switching to Production

### Stripe Production
1. Navigate to **Stripe Dashboard → Developers → API Keys**
2. Toggle from "Test mode" to "Live mode"
3. Copy live keys (pk_live_... and sk_live_...)
4. Update `.env` with live keys
5. Create new webhook endpoint with live webhook secret

### PayPal Production
1. Submit app for review in PayPal Dashboard
2. Once approved, switch to "Live" environment
3. Copy live credentials
4. Update `.env`:
   ```env
   PAYPAL_MODE=live
   ```

---

## 🛡️ Security Best Practices

### 1. Webhook Verification
**Stripe**: Automatically verified using `STRIPE_WEBHOOK_SECRET`
```php
// Already implemented in StripePaymentGateway
$event = Webhook::constructEvent(
    json_encode($payload),
    request()->header('Stripe-Signature'),
    config('services.stripe.webhook_secret')
);
```

**PayPal**: Verify using PayPal SDK
```php
// Implemented in PayPalPaymentGateway
$gateway->handleWebhook($payload);
```

### 2. HTTPS Only
```nginx
# Force HTTPS
server {
    listen 80;
    server_name yourdomain.com;
    return 301 https://$server_name$request_uri;
}
```

### 3. Rate Limiting
```php
// Applied automatically to payment routes
Route::middleware('throttle:10,1')->group(function () {
    // Payment routes
});
```

### 4. Environment Variables
- **Never commit** `.env` file
- Use different keys for development/staging/production
- Rotate keys if compromised

### 5. Transaction Logging
All transactions are logged in `payment_transactions` table with:
- Transaction IDs
- Gateway responses (encrypted)
- IP addresses
- Timestamps

---

## 🧪 Testing Payments

### Test Subscription Purchase

1. **Navigate to Subscriptions**
   ```
   https://yourdomain.com/subscriptions
   ```

2. **Select a Plan** and click "Get Started"

3. **Choose Payment Gateway** (Stripe or PayPal)

4. **For Stripe Testing:**
   - Use card: `4242 4242 4242 4242`
   - Expiry: Any future date
   - CVC: Any 3 digits
   - ZIP: Any 5 digits

5. **For PayPal Testing:**
   - Use sandbox buyer  account
   - Complete checkout in PayPal popup

6. **Verify Success:**
   - Check database: `subscriptions` table
   - Check `payment_transactions` table
   - Verify email sent
   - Check activity logs

---

## ❌ Common Issues & Solutions

### Error: "No such customer"
**Cause**: Customer doesn't exist in Stripe
**Solution**: Ensure customer is created before payment

### Error: "Webhook signature verification failed"
**Cause**: Wrong webhook secret
**Solution**: Copy exact secret from Stripe dashboard → `.env`

### Error: "Payment failed silently"
**Cause**: Webhook not received
**Solution**:
- Verify webhook URL is publicly accessible
- Check webhook logs in payment gateway dashboard
- Ensure no firewall blocking

### Error: "Subscription created but payment pending"
**Cause**: Webhook delay or failure
**Solution**:
- Webhooks may take 1-5 seconds
- Check webhook delivery in dashboard
- Manually mark payment as complete if needed

---

## 📊 Monitoring

### View Transactions
```sql
SELECT * FROM payment_transactions 
WHERE status = 'failed' 
ORDER BY created_at DESC 
LIMIT 10;
```

### Check Webhook Deliveries
- **Stripe**: Dashboard → Developers → Webhooks → Click endpoint → View logs
- **PayPal**: Dashboard → Webhooks → Click webhook → View events

### Monitor Queue Jobs
```bash
php artisan queue:work --once
php artisan queue:failed
```

---

## 💰 Fees & Pricing

### Stripe Fees
- **2.9% + $0.30** per successful card charge (US)
- International cards: +1%
- Currency conversion: +1%

### PayPal Fees
- **2.9% + $0.30** per transaction (US)
- International: 4.4% + fixed fee
- Currency conversion: 3-4%

---

## 📞 Support Resources

- **Stripe Docs**: https://stripe.com/docs
- **Stripe Support**: https://support.stripe.com
- **PayPal Docs**: https://developer.paypal.com/docs
- **PayPal Support**: https://www.paypal.com/businesshelp

---

**Setup Complete! 💳**

Your payment gateways are configured and ready to process real transactions.
