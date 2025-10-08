<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-800 dark:text-gray-200 leading-tight">
            {{ __('Dashboard') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8 space-y-6">
            
            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-4">
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Page Usage This Month</h3>
                            <p class="text-sm text-gray-600 dark:text-gray-400 mt-1">{{ $pagesUsed }} of {{ $pageLimit }} pages scanned</p>
                        </div>
                        @if(!$subscription)
                        <a href="{{ route('pricing') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            Upgrade Plan →
                        </a>
                        @else
                        <a href="{{ route('billing.portal') }}" class="text-indigo-600 hover:text-indigo-800 font-medium text-sm">
                            Manage Billing →
                        </a>
                        @endif
                    </div>
                    
                    <div class="w-full bg-gray-200 rounded-full h-3 overflow-hidden">
                        @php
                            $percentage = $pageLimit > 0 ? min(($pagesUsed / $pageLimit) * 100, 100) : 0;
                            $color = $percentage >= 90 ? 'bg-red-500' : ($percentage >= 70 ? 'bg-yellow-500' : 'bg-green-500');
                        @endphp
                        <div class="{{ $color }} h-3 transition-all duration-300" style="width: {{ $percentage }}%"></div>
                    </div>
                    
                    @if($pagesUsed >= $pageLimit)
                    <div class="mt-4 bg-red-50 border border-red-200 rounded-lg p-4">
                        <p class="text-sm text-red-800">
                            <strong>Limit reached!</strong> You've used all {{ $pageLimit }} pages this month. 
                            <a href="{{ route('pricing') }}" class="underline hover:text-red-900">Upgrade your plan</a> to continue scanning.
                        </p>
                    </div>
                    @elseif($pagesUsed >= $pageLimit * 0.8)
                    <div class="mt-4 bg-yellow-50 border border-yellow-200 rounded-lg p-4">
                        <p class="text-sm text-yellow-800">
                            <strong>Almost at limit!</strong> You've used {{ $pagesUsed }} of {{ $pageLimit }} pages. 
                            Consider <a href="{{ route('pricing') }}" class="underline hover:text-yellow-900">upgrading</a> for more capacity.
                        </p>
                    </div>
                    @endif
                </div>
            </div>

            @if(!$subscription)
            <div class="bg-gradient-to-r from-indigo-500 to-purple-600 rounded-lg shadow-lg p-6 text-white">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-2xl font-bold mb-2">Unlock More Pages</h3>
                        <p class="text-indigo-100">You're on the Free plan ({{ $pageLimit }} pages/month). Upgrade to Starter (200 pages) or Growth (500 pages) for deeper site analysis.</p>
                    </div>
                    <a href="{{ route('pricing') }}" class="bg-white text-indigo-600 px-6 py-3 rounded-lg font-semibold hover:bg-indigo-50 transition">
                        View Plans
                    </a>
                </div>
            </div>
            @else
            <div class="bg-green-50 border border-green-200 rounded-lg p-6">
                <div class="flex items-center justify-between">
                    <div>
                        <h3 class="text-lg font-semibold text-green-900">{{ ucfirst($subscription->stripe_price) }} Plan Active</h3>
                        <p class="text-green-700 text-sm">Next billing date: {{ $subscription->asStripeSubscription()->current_period_end->format('M d, Y') }}</p>
                    </div>
                    <a href="{{ route('billing.portal') }}" class="text-green-700 hover:text-green-900 font-medium">
                        Manage Subscription →
                    </a>
                </div>
            </div>
            @endif

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <div class="flex items-center justify-between mb-6">
                        <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Run New Audit</h3>
                    </div>
                    
                    <form action="{{ route('audits.store') }}" method="POST" class="space-y-4">
                        @csrf
                        <div>
                            <label for="url" class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                                Website URL
                            </label>
                            <input 
                                type="url" 
                                name="url" 
                                id="url" 
                                required
                                placeholder="https://example.com"
                                class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent"
                            >
                            @error('url')
                                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>
                        
                        <button 
                            type="submit"
                            class="bg-indigo-600 text-white px-6 py-2 rounded-lg font-semibold hover:bg-indigo-700 transition"
                        >
                            Start Audit
                        </button>
                    </form>
                </div>
            </div>

            <div class="bg-white dark:bg-gray-800 overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6">
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100 mb-4">Recent Audits</h3>
                    
                    @if($audits->count() > 0)
                        <div class="space-y-4">
                            @foreach($audits as $audit)
                                <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                                    <div class="flex items-center justify-between">
                                        <div class="flex-1">
                                            <a href="{{ route('audits.show', $audit) }}" class="text-indigo-600 hover:text-indigo-800 font-medium">
                                                {{ $audit->url }}
                                            </a>
                                            <div class="flex items-center gap-4 mt-2 text-sm text-gray-600 dark:text-gray-400">
                                                <span class="flex items-center gap-1">
                                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                    </svg>
                                                    {{ $audit->created_at->diffForHumans() }}
                                                </span>
                                                @if($audit->score)
                                                    <span class="flex items-center gap-1">
                                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                                        </svg>
                                                        SEO Score: {{ $audit->score }}/100
                                                    </span>
                                                @endif
                                            </div>
                                        </div>
                                        <div>
                                            @if($audit->status === 'completed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                    Completed
                                                </span>
                                            @elseif($audit->status === 'failed')
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                                    Failed
                                                </span>
                                            @else
                                                <span class="inline-flex items-center px-3 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                                    {{ ucfirst($audit->status) }}
                                                </span>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @else
                        <div class="text-center py-12">
                            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                            </svg>
                            <h3 class="mt-2 text-sm font-medium text-gray-900 dark:text-gray-100">No audits yet</h3>
                            <p class="mt-1 text-sm text-gray-500 dark:text-gray-400">Get started by running your first audit above.</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
