# Fortlify - SEO Audit SaaS Platform

## Overview
Fortlify is a Laravel 11-based SaaS that delivers clear, actionable SEO and conversion audits for small businesses. The platform focuses on simplicity and transparency with prioritized recommendations (Fix First / Next / Nice to Have), runs without third-party SEO subscriptions, and includes a working audit engine (crawler + Lighthouse), Stripe billing, and modular integrations.

**Current Status**: Free Audit feature functional with comprehensive SSRF protection. SEO crawler, recommendation engine, and results display complete. Lighthouse API integration and email capture pending.

**Last Updated**: October 7, 2025

## Recent Changes
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
/login - Breeze login
/register - Breeze registration
/dashboard - (To be built) Authenticated dashboard
```

### Key Files
- `app/Models/Workspace.php` - Workspace model with relationships
- `app/Models/Audit.php` - Audit model with soft deletes
- `app/Models/Recommendation.php` - Recommendation model
- `app/Http/Controllers/AuditController.php` - Free audit submission and results display
- `app/Services/SeoAuditorService.php` - SEO crawler with SSRF protection and recommendation engine
- `app/Http/Controllers/MarketingController.php` - Public marketing pages
- `resources/views/audits/show.blade.php` - Audit results page with prioritized recommendations
- `resources/views/marketing/home.blade.php` - Home page with free audit form
- `resources/views/components/layouts/marketing.blade.php` - Marketing layout
- `routes/web.php` - Application routes
- `.env.example` - Environment configuration template

### Pricing Tiers
1. **Free**: $0/month - 1 audit/month, all SEO checks, Lighthouse scores, prioritized recommendations
2. **Starter**: $29/month - 10 audits/month, everything in Free
3. **Growth**: $79/month - Unlimited audits, Google Search Console integration, keyword ranking snapshots, priority support

## Architecture Decisions
- **SQLite for development**: Fast setup, zero config, easy migrations
- **Soft deletes on audits**: Preserve historical data for trend analysis
- **Many-to-many workspace relationships**: Support team/agency multi-tenancy
- **JSON columns for flexibility**: Store raw crawl/Lighthouse data for future analysis
- **Component-based Blade views**: Reusable marketing layout with consistent nav/footer
- **Named routes**: All routes use named routes for maintainability

### Free Audit Implementation
The free audit feature allows users to submit any URL for SEO analysis without authentication:

**Workflow:**
1. User submits URL via form on homepage
2. AuditController creates audit record with 'pending' status and generates share token
3. SeoAuditorService crawls the URL with comprehensive SSRF protection
4. Recommendation engine analyzes findings and assigns priorities (Fix First/Next/Nice to Have) with effort scores
5. User redirected to results page showing color-coded prioritized recommendations

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
- ✅ SEO crawler with technical checks (title, meta, H1s, canonical, images, links, robots.txt)
- ✅ Recommendation engine with Fix First/Next/Nice to Have prioritization
- ✅ Audit results page with color-coded priority sections and effort scores
- ✅ Comprehensive SSRF security protections
- ✅ Marketing website (Home, Pricing, How It Works, Privacy, Terms, Contact)
- ✅ Database schema (workspaces, audits, recommendations)
- ✅ Authentication (Laravel Breeze)
- ✅ Stripe billing setup (Laravel Cashier)

## Pending Backend Implementation
- Contact form submission handler (will integrate with MailerLite)
- Email capture for free audit results (optional user email after audit completes)
- Shareable audit result links (currently using share_token, needs UI)
- Job queue for async audit processing (currently synchronous)
- Lighthouse Integration (Google PageSpeed Insights API - currently using mock scores)
- Dashboard authenticated routes
- Stripe checkout and subscription management
- Workspace switching and team management
- Audit limits enforcement based on plan

## Next Steps (Priority Order)
1. **Lighthouse Integration**: Integrate Google PageSpeed Insights API for real performance scores
2. **Email Capture**: Optional email collection after audit completes for result sharing
3. **Job Queue**: Move audit processing to background jobs for better UX
4. **App Dashboard**: Build authenticated dashboard UI showing audit history
5. **Stripe Billing**: Complete checkout flow and subscription gates
6. **Workspace Management**: Implement workspace switching and team member invitation
7. **Audit Limits**: Enforce plan-based limits (1/month free, 10/month starter, unlimited growth)
8. **Contact Form**: Implement backend handler for contact submissions
9. **Optional Integrations**: Google Search Console OAuth, SERP API

## Environment Variables
See `.env.example` for full configuration. Key variables:
- `APP_URL` - Application URL
- `DB_CONNECTION` - Database type (sqlite/mysql/pgsql)
- `STRIPE_KEY`, `STRIPE_SECRET` - Stripe credentials
- `STRIPE_WEBHOOK_SECRET` - Stripe webhook signing secret
- `MAILERLITE_API_KEY` - Email marketing integration (to be added)

## Development Workflow
1. **Workflow**: Laravel Server running on port 5000 with `php artisan serve --host=0.0.0.0 --port=5000`
2. **Assets**: Vite handles Tailwind CSS compilation and Alpine.js bundling
3. **Migrations**: Run `php artisan migrate` for schema changes
4. **Testing**: Use Pest for feature and unit tests (to be configured)

## Notes
- Marketing pages link to registration instead of free audit form (audit engine not yet built)
- Contact form has placeholder action (needs backend implementation)
- Stripe integration configured but checkout flow not built
- All authentication handled by Laravel Breeze (login, register, password reset, email verification)
