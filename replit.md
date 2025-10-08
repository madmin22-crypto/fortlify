# Fortlify - SEO Audit SaaS Platform

## Overview
Fortlify is a Laravel 11 SaaS platform providing clear, actionable SEO and conversion audits for small businesses. It offers prioritized recommendations (Fix First / Next / Nice to Have), operates without third-party SEO subscriptions, and includes an audit engine (crawler + Lighthouse), Stripe billing, and modular integrations. The platform enforces page-based usage limits with atomic reservation and supports multi-page crawls. Its vision is to democratize SEO insights, making powerful analytics accessible and understandable for small business owners, fostering growth through improved online visibility and conversion rates.

## User Preferences
- No third-party SEO subscriptions: All features must run without requiring Semrush/Ahrefs subscriptions (optional integrations only)
- Prioritized insights: All audit findings must be labeled Fix First / Next / Nice to Have
- Simplicity first: Focus on clear, actionable recommendations over data overload
- Multi-tenancy: Support workspaces/teams for agency use cases
- Modular integrations: Google Search Console and SERP API should be pluggable/optional

## System Architecture

### Tech Stack
- **Backend**: Laravel 11.46.1, PHP 8.2.23
- **Frontend**: Blade templates, Alpine.js, Tailwind CSS 3.x
- **Database**: SQLite (dev), production-ready for MySQL/PostgreSQL
- **Billing**: Laravel Cashier + Stripe

### UI/UX Decisions
- **Marketing Website**: Comprehensive public-facing pages (Home, Pricing, How It Works, Privacy, Terms, Contact)
- **Authenticated Dashboard**: Displays audit history, subscription status, and page usage with a progress bar and warnings.
- **Audit Processing Page**: Features a loading spinner and 3-second auto-refresh for a seamless user experience during background processing.
- **Audit Results Page**: Presents prioritized recommendations with color-coded sections and effort scores for clarity.
- **Component-based Blade views**: Utilizes reusable marketing layouts with consistent navigation and footers for a unified design.

### Core Features & Implementations
- **Page-Based Limiting System**: Audits scan multiple pages within the same domain based on plan limits (e.g., Free: 10, Starter: 200, Growth: 500 pages/month). Implemented with atomic page reservation using database locking to prevent race conditions, and a monthly reset logic.
- **Multi-Page Crawler**: Breadth-first traversal with parallel HTTP requests using Laravel's Http::pool(). Processes up to 5 pages concurrently per batch for 7-8x speed improvement (10 pages in ~8 seconds). Features safe redirect handling using Guzzle's RFC 3986-compliant URI resolver, flexible domain matching (handles www variations), and extracts links filtering to same domain only. Crawls up to the remaining page allowance. All SSRF protections maintained with validateUrl() and DNS pinning for each pooled request.
- **Asynchronous Audit Processing**: Utilizes Laravel's Job Queue with `ProcessAudit` jobs for background processing, ensuring a responsive user interface.
- **SEO Audit Engine**:
    - **Technical Checks**: Analyzes title tags, meta descriptions, H1s, canonical tags, image optimization, internal/external links, and `robots.txt`.
    - **Lighthouse Integration**: Integrates with Google PageSpeed Insights API for real Lighthouse performance scores.
    - **Recommendation Engine**: Categorizes findings into "Fix First," "Next," and "Nice to Have" with effort scores.
    - **Overall SEO Health Score**: Calculates and displays a 0-100 score with color-coded indicators.
- **SSRF Protections**: Comprehensive measures including DNS validation, CNAME resolution, IP range blocking (private, reserved, link-local), IPv4-mapped IPv6 decoding, DNS pinning, and safe redirect handling.
- **Stripe Billing Integration**: Implemented with Laravel Cashier for subscription management, including checkout flow, pricing page integration, and a customer billing portal.
- **Multi-tenancy**: Supports workspaces with `Workspaces` and `WorkspaceMembers` tables, allowing for team collaboration.
- **Database Schema**: Designed with `Users`, `Workspaces`, `PlanLimits`, `Audits`, and `Recommendations` tables. JSON columns are used in `Audits` for flexible storage of crawl and Lighthouse data.
- **Soft Deletes**: Enabled for `Workspaces` and `Audits` to preserve historical data.

### Development Workflow
- **Laravel Server**: Runs on port 5000.
- **Queue Worker**: Continuously processes background jobs with 3 retries and a 5-minute timeout.
- **Assets**: Vite handles Tailwind CSS compilation and Alpine.js bundling.
- **Migrations**: Standard Laravel migration usage.

## External Dependencies
- **Stripe**: For subscription billing, payment processing, and customer portal integration.
- **Google PageSpeed Insights API**: Used to fetch Lighthouse performance scores for audits.
- **MailerLite**: Planned integration for contact form submissions and email delivery of audit results.