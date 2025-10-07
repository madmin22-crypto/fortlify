# Fortlify - SEO Audit SaaS Platform

## Overview
Fortlify is a Laravel 11-based SaaS that delivers clear, actionable SEO and conversion audits for small businesses. The platform focuses on simplicity and transparency with prioritized recommendations (Fix First / Next / Nice to Have), runs without third-party SEO subscriptions, and includes a working audit engine (crawler + Lighthouse), Stripe billing, and modular integrations.

**Current Status**: Foundation complete - Authentication, billing setup, database schema, and marketing pages are live. Core audit engine is next.

**Last Updated**: October 7, 2025

## Recent Changes
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
- `app/Http/Controllers/MarketingController.php` - Public marketing pages
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

## Pending Backend Implementation
- Contact form submission handler (will integrate with MailerLite)
- Free audit creation endpoint (no login required)
- Email capture and audit result sharing links
- Dashboard authenticated routes
- Stripe checkout and subscription management

## Next Steps (Priority Order)
1. **Free Audit Feature**: Build public audit submission (no login) with email capture
2. **SEO Crawler**: Implement page crawler for technical SEO checks
3. **Lighthouse Integration**: Integrate Google PageSpeed Insights API
4. **Recommendation Engine**: Build prioritization logic (Fix First/Next/Nice to Have)
5. **Audit Detail Page**: Display findings and recommendations
6. **Multi-tenancy**: Implement workspace switching and team management
7. **App Dashboard**: Build authenticated dashboard UI
8. **Stripe Billing**: Complete checkout flow and subscription gates
9. **Audit Limits**: Enforce plan-based limits
10. **Optional Integrations**: Google Search Console OAuth, SERP API

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
