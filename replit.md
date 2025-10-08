# Fortlify - SEO Audit SaaS Platform

## Overview
Fortlify is a Laravel 11-based SaaS that delivers clear, actionable SEO and conversion audits for small businesses. The platform focuses on simplicity and transparency with prioritized recommendations (Fix First / Next / Nice to Have), runs without third-party SEO subscriptions, and includes a working audit engine (crawler + Lighthouse), Stripe billing, and modular integrations.

**Current Status**: Free Audit feature fully functional with async job queue processing, real Google Lighthouse scores, email capture, and comprehensive SSRF protection. SEO crawler, recommendation engine, and results display complete. Stripe billing checkout flow implemented - ready for product setup.

**Last Updated**: October 8, 2025

## Recent Changes
- **Oct 8, 2025**: Completed Stripe checkout flow with SubscriptionController and billing portal
- **Oct 8, 2025**: Updated pricing page with subscribe buttons for authenticated users
- **Oct 8, 2025**: Created STRIPE_SETUP.md guide for product creation and webhook configuration
- **Oct 8, 2025**: Added subscription routes: /subscribe (POST) and /billing/portal
- **Oct 7, 2025**: Implemented async Job Queue with ProcessAudit job for background audit processing
- **Oct 7, 2025**: Created processing page with loading spinner and 3-second auto-refresh
- **Oct 7, 2025**: Added Queue Worker workflow for continuous job processing (3 retries, 5-min timeout)
- **Oct 7, 2025**: Integrated Google PageSpeed Insights API for real Lighthouse performance scores
- **Oct 7, 2025**: Added optional email capture with shareable audit result links
- **Oct 7, 2025**: Implemented Overall SEO Health score (0-100) with color-coded display
- **Oct 7, 2025**: Built Free Audit feature with public form submission and results display
- **Oct 7, 2025**: Implemented SEO crawler with title tags, meta descriptions, H1s, canonical tags, robots.txt analysis
- **Oct 7, 2025**: Created recommendation engine with Fix First/Next/Nice to Have prioritization and effort scores
- **Oct 7, 2025**: Added comprehensive SSRF protections: DNS validation, CNAME resolution, IPv4-mapped IPv6 decoding, DNS pinning, redirect blocking
- **Oct 7, 2025**: Created complete marketing website (Home, Pricing, How It Works, Privacy, Terms, Contact)
- **Oct 7, 2025**: Built database schema with workspaces, audits, and recommendations tables
- **Oct 7, 2025**: Configured Laravel Cashier for Stripe billing integration
- **Oct 7, 2025**: Installed Laravel Breeze with Blade, Alpine.js, and Tailwind CSS
- **Oct 7, 2025**: Bootstrapped Laravel 11 application with workflow on port 5000

## User Preferences
- **No third-party SEO subscriptions**: All features must run without requiring Semrush/Ahrefs subscriptions (optional integrations only)
- **Prioritized insights**: All audit findings must be labeled Fix First / Next / Nice to Have
- **Simplicity first**: Focus on clear, actionable recommendations over data overload
- **Multi-tenancy**: Support workspaces/teams for agency use cases
- **Modular integrations**: Google Search Console and SERP API should be pluggable/optional

## Project Architecture

### Tech Stack
- **Backend**: Laravel 11.46.1, PHP 8.2.23
- **Frontend**: Blade templates, Alpine.js, Tailwind CSS 3.x
- **Database**: SQLite (dev), production-ready for MySQL/PostgreSQL
- **Billing**: Laravel Cashier + Stripe
- **Testing**: Pest (to be configured)
- **Code Quality**: Laravel Pint, PHPStan (to be configured)

### Database Schema
**Users**
- Standard Laravel auth users table
- Uses Billable trait from Cashier for Stripe integration

**Workspaces** (Multi-tenancy)
- `id`, `name`, `slug`, `owner_id`, `stripe_customer_id`
- `audit_limit` (monthly limit based on plan)
- Soft deletes enabled
- Relationships: belongsTo User (owner), belongsToMany Users (members), hasMany Audits

**Workspace Members** (Pivot table)
- `workspace_id`, `user_id`, `role` (owner/member)
- Timestamps for join tracking

**Audits**
- `id`, `workspace_id`, `url`, `status` (pending/running/completed/failed)
- `lighthouse_score_mobile`, `lighthouse_score_desktop`
- `crawled_at`, `completed_at`
- JSON columns: `crawl_data`, `lighthouse_data`, `metadata`
- Soft deletes for historical preservation
- Indexes on workspace_id, status, created_at

**Recommendations**
- `id`, `audit_id`, `category` (seo/performance/accessibility/best-practices)
- `priority` (fix_first/next/nice_to_have)
- `title`, `description`, `how_to_fix`
- `impact_score` (1-10)
- Index on (audit_id, priority) for fast sorting

### Routes Structure
```
/ - Home (marketing)
/pricing - Pricing page
/how-it-works - How it works page
/contact - Contact form
/privacy - Privacy policy
/terms - Terms of service
/audits - POST - Create new audit (dispatches ProcessAudit job)
/audits/{audit}/processing - Processing status page with auto-refresh
/audits/{audit} - Audit results page
/login - Breeze login
/register - Breeze registration
/dashboard - Authenticated dashboard with audit history
/subscribe - POST - Stripe checkout (requires auth)
/billing/portal - Stripe billing portal (requires auth)
```

### Key Files
- `app/Models/Workspace.php` - Workspace model with relationships
- `app/Models/Audit.php` - Audit model with soft deletes and SEO score calculation
- `app/Models/Recommendation.php` - Recommendation model
- `app/Jobs/ProcessAudit.php` - Background job for async audit processing
- `app/Http/Controllers/AuditController.php` - Audit submission, processing status, and results display
- `app/Http/Controllers/DashboardController.php` - Authenticated dashboard with audit history
- `app/Http/Controllers/SubscriptionController.php` - Stripe checkout and billing portal
- `app/Services/SeoAuditorService.php` - SEO crawler with SSRF protection, Lighthouse API integration, and recommendation engine
- `app/Http/Controllers/MarketingController.php` - Public marketing pages
- `resources/views/audits/processing.blade.php` - Processing page with loading spinner
- `resources/views/audits/show.blade.php` - Audit results page with prioritized recommendations
- `resources/views/dashboard.blade.php` - Dashboard view with audit history and subscription status
- `resources/views/marketing/home.blade.php` - Home page with free audit form
- `resources/views/marketing/pricing.blade.php` - Pricing page with subscribe buttons
- `resources/views/components/layouts/marketing.blade.php` - Marketing layout
- `routes/web.php` - Application routes
- `STRIPE_SETUP.md` - Complete guide for Stripe product creation and webhook configuration
- `.env.example` - Environment configuration template

### Pricing Tiers
1. **Free**: $0/month - 1 audit/month, all SEO checks, Lighthouse scores, prioritized recommendations
2. **Starter**: $29/month - 10 audits/month, everything in Free, team collaboration, email support
3. **Growth**: $79/month - Unlimited audits, Google Search Console integration, keyword ranking snapshots, priority support
4. **One-Time Audit**: $24 one-time - 1 complete audit, all SEO checks, Lighthouse scores, no recurring charges

## Architecture Decisions
- **SQLite for development**: Fast setup, zero config, easy migrations
- **Soft deletes on audits**: Preserve historical data for trend analysis
- **Many-to-many workspace relationships**: Support team/agency multi-tenancy
- **JSON columns for flexibility**: Store raw crawl/Lighthouse data for future analysis
- **Component-based Blade views**: Reusable marketing layout with consistent nav/footer
- **Named routes**: All routes use named routes for maintainability

### Free Audit Implementation
The free audit feature allows users to submit any URL for SEO analysis without authentication:

**Async Workflow:**
1. User submits URL and optional email via form on homepage
2. AuditController creates audit record with 'pending' status and generates share token
3. ProcessAudit job dispatched to queue for background processing
4. User instantly redirected to processing page with loading spinner
5. Processing page auto-refreshes every 3 seconds to check status
6. Queue Worker picks up job and updates audit status to 'running'
7. SeoAuditorService crawls URL with SSRF protection and calls Google PageSpeed Insights API
8. Recommendation engine analyzes findings and assigns priorities with effort scores
9. SEO Health score (0-100) calculated based on recommendations
10. On completion, processing page auto-redirects to results
11. If email provided, shareable link displayed with copy-to-clipboard button

**SEO Crawler Checks:**
- Title tags (presence, length, uniqueness)
- Meta descriptions (presence, length, keywords)
- H1 tags (presence, count, hierarchy)
- Canonical tags (proper implementation)
- Image optimization (alt text, file sizes)
- Internal/external links (counts, broken links)
- Robots.txt analysis
- Mobile-friendliness indicators

**SSRF Security Protections:**
- Recursive CNAME chain resolution with circular reference detection
- DNS validation for both IPv4 and IPv6 addresses
- Private/reserved/link-local IP range blocking (10.x, 127.x, 169.254.x, 172.16-31.x, 192.168.x)
- IPv6 private range blocking (fe80:, fc00:, fd00:, ::1)
- IPv4-mapped IPv6 address decoding using inet_pton/inet_ntop (blocks ::ffff:169.254.x in all formats)
- DNS pinning via CURLOPT_RESOLVE to prevent DNS rebinding attacks
- **Safe redirect handling**: Validates each redirect destination (max 5 redirects, circular detection)
- Dangerous hostname blocking (localhost, 127.*, 169.254.*, etc.)
- Known limitations: Zero-padded IPv6 addresses (e.g., 0000:...:0001) are documented edge cases for MVP

**Recommendation Prioritization:**
- **Fix First** (High impact, critical issues): Missing title tags, broken canonical, no meta description
- **Next** (Medium impact): Suboptimal title length, missing H1, image optimization needed
- **Nice to Have** (Low impact, enhancements): Additional meta tags, advanced optimizations

## Completed Features ✅
- ✅ Free Audit form with URL validation and submission
- ✅ Async Job Queue with ProcessAudit job for background processing
- ✅ Processing page with loading spinner and auto-refresh
- ✅ Queue Worker workflow for continuous job execution
- ✅ Google PageSpeed Insights API integration for real Lighthouse scores
- ✅ Optional email capture with shareable audit result links
- ✅ Overall SEO Health score (0-100) with color-coded display (Excellent/Good/Needs Work/Critical)
- ✅ SEO crawler with technical checks (title, meta, H1s, canonical, images, links, robots.txt)
- ✅ Recommendation engine with Fix First/Next/Nice to Have prioritization
- ✅ Audit results page with color-coded priority sections and effort scores
- ✅ Comprehensive SSRF security protections
- ✅ Marketing website (Home, Pricing, How It Works, Privacy, Terms, Contact)
- ✅ Database schema (workspaces, audits, recommendations)
- ✅ Authentication (Laravel Breeze)
- ✅ Stripe billing setup (Laravel Cashier)
- ✅ Stripe checkout flow with SubscriptionController
- ✅ Billing portal integration
- ✅ Pricing page with dynamic subscribe buttons for authenticated users
- ✅ Dashboard with audit history display

## Pending Backend Implementation
- Contact form submission handler (will integrate with MailerLite)
- Email delivery for audit results (email capture implemented, sending pending)
- Stripe product creation and price ID configuration (see STRIPE_SETUP.md)
- Workspace switching and team management
- Audit limits enforcement based on plan

## Next Steps (Priority Order)
1. **Stripe Product Setup**: Create products in Stripe Dashboard and add price IDs (see STRIPE_SETUP.md)
2. **Workspace Management**: Implement workspace switching and team member invitation
3. **Audit Limits**: Enforce plan-based limits (1/month free, 10/month starter, unlimited growth)
4. **Contact Form**: Implement backend handler for contact submissions
5. **Email Delivery**: Send audit results via email when address is provided
6. **Optional Integrations**: Google Search Console OAuth, SERP API

## Environment Variables
See `.env.example` for full configuration. Key variables:
- `APP_URL` - Application URL
- `DB_CONNECTION` - Database type (sqlite/mysql/pgsql)
- `STRIPE_KEY`, `STRIPE_SECRET` - Stripe credentials
- `STRIPE_WEBHOOK_SECRET` - Stripe webhook signing secret
- `MAILERLITE_API_KEY` - Email marketing integration (to be added)

## Development Workflow
1. **Laravel Server**: Running on port 5000 with `php artisan serve --host=0.0.0.0 --port=5000`
2. **Queue Worker**: Running continuously with `php artisan queue:work --tries=3 --timeout=300` for background job processing
3. **Assets**: Vite handles Tailwind CSS compilation and Alpine.js bundling
4. **Migrations**: Run `php artisan migrate` for schema changes
5. **Testing**: Use Pest for feature and unit tests (to be configured)

## Notes
- Contact form has placeholder action (needs backend implementation)
- All authentication handled by Laravel Breeze (login, register, password reset, email verification)
- Email capture implemented but actual email delivery pending (requires mail service like MailerLite)
