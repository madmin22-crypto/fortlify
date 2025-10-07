<x-layouts.marketing>
    <x-slot name="title">Pricing</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <div class="text-center">
            <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white sm:text-5xl">
                Simple, Transparent Pricing
            </h1>
            <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                Start free, upgrade when you need more audits
            </p>
        </div>

        <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Free</h3>
                <p class="mt-4 text-gray-600 dark:text-gray-400">Perfect for trying out Fortlify</p>
                <p class="mt-8">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">$0</span>
                    <span class="text-base font-medium text-gray-500 dark:text-gray-400">/month</span>
                </p>
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">1 audit per month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">All SEO checks</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Lighthouse performance scores</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Prioritized recommendations</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full bg-gray-800 dark:bg-gray-700 text-white rounded-md py-2 text-center font-medium hover:bg-gray-900 dark:hover:bg-gray-600">
                    Get Started
                </a>
            </div>

            <div class="bg-indigo-600 rounded-lg shadow-lg p-8 relative">
                <div class="absolute top-0 right-0 -mr-1 -mt-4">
                    <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-green-100 text-green-800">
                        Most Popular
                    </span>
                </div>
                <h3 class="text-2xl font-bold text-white">Starter</h3>
                <p class="mt-4 text-indigo-100">For growing businesses</p>
                <p class="mt-8">
                    <span class="text-4xl font-extrabold text-white">$29</span>
                    <span class="text-base font-medium text-indigo-100">/month</span>
                </p>
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-white">10 audits per month</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-white">Everything in Free</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-white">Team collaboration</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-white" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-white">Email support</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full bg-white text-indigo-600 rounded-md py-2 text-center font-medium hover:bg-gray-100">
                    Start 14-day Trial
                </a>
            </div>

            <div class="bg-white dark:bg-gray-800 rounded-lg shadow-lg p-8">
                <h3 class="text-2xl font-bold text-gray-900 dark:text-white">Growth</h3>
                <p class="mt-4 text-gray-600 dark:text-gray-400">For agencies and power users</p>
                <p class="mt-8">
                    <span class="text-4xl font-extrabold text-gray-900 dark:text-white">$79</span>
                    <span class="text-base font-medium text-gray-500 dark:text-gray-400">/month</span>
                </p>
                <ul class="mt-8 space-y-4">
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Unlimited audits</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Everything in Starter</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Google Search Console integration</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Keyword ranking snapshots</span>
                    </li>
                    <li class="flex items-start">
                        <svg class="flex-shrink-0 h-6 w-6 text-green-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path>
                        </svg>
                        <span class="ml-3 text-gray-700 dark:text-gray-300">Priority support</span>
                    </li>
                </ul>
                <a href="{{ route('register') }}" class="mt-8 block w-full bg-gray-800 dark:bg-gray-700 text-white rounded-md py-2 text-center font-medium hover:bg-gray-900 dark:hover:bg-gray-600">
                    Start 14-day Trial
                </a>
            </div>
        </div>
    </div>
</x-layouts.marketing>
