<x-layouts.marketing>
    <x-slot name="title">Home</x-slot>

    <div class="relative overflow-hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
            <div class="text-center">
                <h1 class="text-4xl tracking-tight font-extrabold text-gray-900 dark:text-white sm:text-5xl md:text-6xl">
                    <span class="block">Clear, Actionable SEO Audits</span>
                    <span class="block text-indigo-600 dark:text-indigo-400">for Small Businesses</span>
                </h1>
                <p class="mt-3 max-w-md mx-auto text-base text-gray-500 dark:text-gray-400 sm:text-lg md:mt-5 md:text-xl md:max-w-3xl">
                    Stop drowning in data. Get prioritized SEO recommendations that tell you exactly what to fix first, what to do next, and what can wait.
                </p>
                <div class="mt-5 max-w-md mx-auto sm:flex sm:justify-center md:mt-8">
                    <div class="rounded-md shadow">
                        <a href="#free-audit" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 md:py-4 md:text-lg md:px-10">
                            Get Free Audit
                        </a>
                    </div>
                    <div class="mt-3 rounded-md shadow sm:mt-0 sm:ml-3">
                        <a href="{{ route('how-it-works') }}" class="w-full flex items-center justify-center px-8 py-3 border border-transparent text-base font-medium rounded-md text-indigo-600 bg-white hover:bg-gray-50 dark:bg-gray-800 dark:text-indigo-400 dark:hover:bg-gray-700 md:py-4 md:text-lg md:px-10">
                            How It Works
                        </a>
                    </div>
                </div>
            </div>
        </div>

        <div class="bg-gray-50 dark:bg-gray-800 py-16">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="text-center">
                    <h2 class="text-3xl font-extrabold text-gray-900 dark:text-white sm:text-4xl">
                        No SEO Subscriptions Required
                    </h2>
                    <p class="mt-4 text-xl text-gray-500 dark:text-gray-400">
                        Built-in crawler + Lighthouse performance analysis. Optional integrations for Google Search Console and keyword tracking.
                    </p>
                </div>
                
                <div class="mt-16 grid grid-cols-1 gap-8 sm:grid-cols-2 lg:grid-cols-3">
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Technical SEO</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Title tags, meta descriptions, H1s, canonical tags, robots.txt, sitemaps, and schema markup.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Performance</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Lighthouse scores for mobile and desktop. Real performance metrics that impact rankings.</p>
                    </div>
                    
                    <div class="bg-white dark:bg-gray-900 p-6 rounded-lg shadow">
                        <h3 class="text-lg font-medium text-gray-900 dark:text-white">Prioritized Fixes</h3>
                        <p class="mt-2 text-gray-600 dark:text-gray-400">Every issue labeled Fix First, Next, or Nice to Have based on impact and effort.</p>
                    </div>
                </div>
            </div>
        </div>

        <div id="free-audit" class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
            <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-8">
                <h2 class="text-2xl font-bold text-gray-900 dark:text-white mb-4">Try a Free Audit</h2>
                <p class="text-gray-600 dark:text-gray-400 mb-6">Enter your website URL below. No signup required - get instant SEO insights.</p>
                
                @if (session('success'))
                    <div class="mb-4 rounded-md bg-green-50 dark:bg-green-900/50 p-4">
                        <p class="text-sm font-medium text-green-800 dark:text-green-200">{{ session('success') }}</p>
                    </div>
                @endif

                @if ($errors->any())
                    <div class="mb-4 rounded-md bg-red-50 dark:bg-red-900/50 p-4">
                        <ul class="list-disc list-inside text-sm text-red-800 dark:text-red-200">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('audits.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300">Website URL</label>
                        <input type="url" name="url" id="url" placeholder="https://example.com" required 
                            value="{{ old('url') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700 dark:text-gray-300">
                            Email (optional)
                        </label>
                        <p class="text-xs text-gray-500 dark:text-gray-400 mt-1 mb-2">We'll save your audit results and send you a shareable link</p>
                        <input type="email" name="email" id="email" placeholder="you@example.com" 
                            value="{{ old('email') }}"
                            class="mt-1 block w-full rounded-md border-gray-300 dark:border-gray-600 dark:bg-gray-700 dark:text-white shadow-sm focus:border-indigo-500 focus:ring-indigo-500">
                    </div>
                    <button type="submit" class="w-full flex justify-center py-3 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Start Free Audit
                    </button>
                </form>
            </div>
        </div>
    </div>
</x-layouts.marketing>
