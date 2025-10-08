# Stripe Billing Setup Guide

## Overview
Your Fortlify Stripe integration is ready! This guide will help you create products and configure price IDs so users can subscribe to paid plans.

## What's Already Configured ✅
- Laravel Cashier installed and configured
- Stripe API keys (STRIPE_KEY, STRIPE_SECRET) are set in secrets
- Checkout routes created at `/subscribe` (POST)
- Billing portal route at `/billing/portal`
- Pricing page updated with subscribe buttons for authenticated users

## Step 1: Create Products in Stripe Dashboard

1. **Log in to Stripe Dashboard**: https://dashboard.stripe.com/test/products

2. **Create Starter Plan Product**:
   - Click "Add product"
   - Product name: `Fortlify Starter`
   - Description: `10 SEO audits per month with team collaboration`
   - Pricing model: `Standard pricing`
   - Price: `$29.00 USD`
   - Billing period: `Monthly`
   - Click "Save product"
   - **Copy the Price ID** (starts with `price_...`)

3. **Create Growth Plan Product**:
   - Click "Add product"
   - Product name: `Fortlify Growth`
   - Description: `Unlimited SEO audits with Google Search Console integration`
   - Pricing model: `Standard pricing`
   - Price: `$79.00 USD`
   - Billing period: `Monthly`
   - Click "Save product"
   - **Copy the Price ID** (starts with `price_...`)

## Step 2: Add Price IDs to Environment

Add these two environment variables to your Replit Secrets:

```
STRIPE_PRICE_STARTER=price_xxxxxxxxxxxxx  (from Step 1.2)
STRIPE_PRICE_GROWTH=price_xxxxxxxxxxxxx   (from Step 1.3)
```

**How to add secrets in Replit:**
1. Open the "Secrets" tab (🔒 icon in left sidebar)
2. Click "New Secret"
3. Add `STRIPE_PRICE_STARTER` with the Starter plan price ID
4. Add `STRIPE_PRICE_GROWTH` with the Growth plan price ID

## Step 3: Test the Checkout Flow

1. **Log in** to your app (test@fortlify.com / password123)
2. Go to `/pricing`
3. Click "Subscribe Now" on Starter or Growth plan
4. You'll be redirected to Stripe Checkout (test mode)
5. Use test card: `4242 4242 4242 4242` (any future expiry, any CVC)
6. Complete checkout
7. You'll be redirected to dashboard with success message

## Step 4: Configure Stripe Webhooks (Important!)

Laravel Cashier needs webhooks to handle subscription events:

1. Go to https://dashboard.stripe.com/test/webhooks
2. Click "Add endpoint"
3. Endpoint URL: `https://your-replit-url.replit.dev/stripe/webhook`
   - Replace `your-replit-url` with your actual Replit URL
4. Select events to listen to:
   - `customer.subscription.created`
   - `customer.subscription.updated`
   - `customer.subscription.deleted`
   - `invoice.payment_succeeded`
   - `invoice.payment_failed`
5. Click "Add endpoint"
6. **Copy the Signing Secret** (starts with `whsec_...`)
7. Add to Replit Secrets as `STRIPE_WEBHOOK_SECRET`

## Step 5: Test Mode vs Live Mode

Currently configured for **Test Mode** (notice the STRIPE_KEY/SECRET start with `pk_test_` and `sk_test_`):
- Use test cards only
- No real money charged
- Perfect for development

When ready for production:
1. Switch to Live Mode in Stripe Dashboard
2. Create same products in Live Mode
3. Update secrets with Live Mode keys (start with `pk_live_` and `sk_live_`)
4. Add Live Mode webhook endpoint

## How It Works

### For Authenticated Users:
1. User clicks "Subscribe Now" on pricing page
2. Form submits to `/subscribe` with plan name (starter/growth)
3. `SubscriptionController@checkout` creates Stripe Checkout Session
4. User redirected to Stripe payment page
5. After payment, redirected to dashboard with success message
6. Subscription stored in database with Cashier

### Billing Portal Access:
- Users can manage their subscription at `/billing/portal`
- Can update payment method, cancel subscription, view invoices
- Link will be added to dashboard UI

## Next Steps After Setup
1. Add subscription status badge to dashboard
2. Enforce audit limits based on plan (1/month free, 10/month starter, unlimited growth)
3. Add "Manage Billing" button to dashboard linking to `/billing/portal`
4. Show upgrade prompts when users hit audit limits
5. Add webhooks to handle failed payments and subscription cancellations

## Troubleshooting

**"Invalid plan selected" error:**
- Check that STRIPE_PRICE_STARTER and STRIPE_PRICE_GROWTH are set in secrets
- Verify the price IDs are correct (copy from Stripe Dashboard)

**Checkout page shows error:**
- Verify Stripe API keys are correct
- Check that you're using Test Mode keys with test cards
- Look for errors in Laravel logs

**Subscription not showing after payment:**
- Configure webhooks (Step 4)
- Check webhook delivery in Stripe Dashboard
- Verify STRIPE_WEBHOOK_SECRET is set correctly

## Test Cards (Test Mode Only)
- Success: `4242 4242 4242 4242`
- Decline: `4000 0000 0000 0002`
- Requires authentication: `4000 0025 0000 3155`
- Expiry: Any future date
- CVC: Any 3 digits
