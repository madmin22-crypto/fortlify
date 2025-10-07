<x-layouts.marketing>
    <x-slot name="title">How It Works</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                How Fortlify Works
            </h1>
            <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                Get clear, actionable SEO recommendations in minutes
            </p>
        </div>

        <div class="mt-16 space-y-12">
            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white text-xl font-bold">
                        1
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Enter Your Website URL</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Just paste your website URL. No signup required for your first free audit.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white text-xl font-bold">
                        2
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">We Audit Your Site</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Our crawler analyzes your pages for technical SEO issues: title tags, meta descriptions, H1s, canonical tags, robots.txt, sitemaps, schema markup, broken links, and more. We also run Google Lighthouse to measure performance on mobile and desktop.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white text-xl font-bold">
                        3
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Get Prioritized Recommendations</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Every issue is labeled with a priority: <strong>Fix First</strong> (high impact, easy to fix), <strong>Next</strong> (important but takes more work), or <strong>Nice to Have</strong> (lower impact improvements). No more guesswork—you know exactly what to tackle first.
                    </p>
                </div>
            </div>

            <div class="flex items-start">
                <div class="flex-shrink-0">
                    <div class="flex items-center justify-center h-12 w-12 rounded-md bg-indigo-600 text-white text-xl font-bold">
                        4
                    </div>
                </div>
                <div class="ml-4">
                    <h3 class="text-lg font-medium text-gray-900 dark:text-white">Take Action</h3>
                    <p class="mt-2 text-gray-600 dark:text-gray-400">
                        Each recommendation includes a clear explanation of what's wrong and how to fix it. No jargon, no SEO-speak—just plain English instructions.
                    </p>
                </div>
            </div>
        </div>

        <div class="mt-16 bg-indigo-50 dark:bg-indigo-900/20 rounded-lg p-8">
            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">No Third-Party SEO Tools Required</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Unlike other platforms that require expensive Semrush or Ahrefs subscriptions, Fortlify includes everything you need:
            </p>
            <ul class="space-y-2 text-gray-600 dark:text-gray-400">
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Built-in web crawler to analyze your pages
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Google Lighthouse for performance scores
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Optional Google Search Console integration (on Growth plan)
                </li>
                <li class="flex items-start">
                    <svg class="flex-shrink-0 h-6 w-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                    </svg>
                    Optional keyword ranking snapshots (on Growth plan)
                </li>
            </ul>
        </div>

        <div class="mt-12 text-center">
            <a href="{{ route('home') }}#free-audit" class="inline-flex items-center px-6 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Try Free Audit Now
            </a>
        </div>
    </div>
</x-layouts.marketing>
