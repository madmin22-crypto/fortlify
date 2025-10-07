<x-layouts.marketing>
    <x-slot name="title">Audit Results - {{ $audit->url }}</x-slot>

    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <div class="mb-8">
            <a href="{{ route('home') }}" class="text-indigo-600 hover:text-indigo-700 dark:text-indigo-400 dark:hover:text-indigo-300">
                ← Back to Home
            </a>
        </div>

        <div class="bg-white dark:bg-gray-800 shadow sm:rounded-lg p-6 mb-8">
            <h1 class="text-3xl font-bold text-gray-900 dark:text-white mb-6">SEO Audit Results</h1>
            
            @if($audit->score !== null)
                <div class="mb-8 text-center py-6 bg-gradient-to-r from-indigo-50 to-purple-50 dark:from-indigo-900/20 dark:to-purple-900/20 rounded-lg">
                    <p class="text-lg text-gray-600 dark:text-gray-400 mb-2">Overall SEO Health</p>
                    <div class="flex items-center justify-center gap-3">
                        <span class="text-6xl font-bold 
                            @if($audit->getScoreColor() === 'green') text-green-600 dark:text-green-400
                            @elseif($audit->getScoreColor() === 'yellow') text-yellow-600 dark:text-yellow-400
                            @elseif($audit->getScoreColor() === 'orange') text-orange-600 dark:text-orange-400
                            @else text-red-600 dark:text-red-400
                            @endif">
                            {{ $audit->score }}
                        </span>
                        <span class="text-2xl text-gray-400 dark:text-gray-500">/100</span>
                    </div>
                    <p class="text-sm font-medium mt-2
                        @if($audit->getScoreColor() === 'green') text-green-700 dark:text-green-300
                        @elseif($audit->getScoreColor() === 'yellow') text-yellow-700 dark:text-yellow-300
                        @elseif($audit->getScoreColor() === 'orange') text-orange-700 dark:text-orange-300
                        @else text-red-700 dark:text-red-300
                        @endif">
                        {{ $audit->getScoreLabel() }}
                    </p>
                </div>
            @endif
            
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Website</p>
                    <p class="font-medium text-gray-900 dark:text-white break-all">{{ $audit->url }}</p>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Status</p>
                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                        @if($audit->status === 'completed') bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200
                        @elseif($audit->status === 'failed') bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200
                        @elseif($audit->status === 'processing') bg-yellow-100 text-yellow-800 dark:bg-yellow-900 dark:text-yellow-200
                        @else bg-gray-100 text-gray-800 dark:bg-gray-900 dark:text-gray-200
                        @endif">
                        {{ ucfirst($audit->status) }}
                    </span>
                </div>
                <div>
                    <p class="text-sm text-gray-500 dark:text-gray-400">Audited</p>
                    <p class="font-medium text-gray-900 dark:text-white">{{ $audit->completed_at?->diffForHumans() ?? 'In progress' }}</p>
                </div>
            </div>

            @if($audit->lighthouse_score_mobile || $audit->lighthouse_score_desktop)
                <div class="border-t border-gray-200 dark:border-gray-700 pt-6">
                    <h2 class="text-lg font-semibold text-gray-900 dark:text-white mb-4">Lighthouse Performance Scores</h2>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        @if($audit->lighthouse_score_mobile)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Mobile</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $audit->lighthouse_score_mobile }}/100</p>
                            </div>
                        @endif
                        @if($audit->lighthouse_score_desktop)
                            <div class="bg-gray-50 dark:bg-gray-900 p-4 rounded-lg">
                                <p class="text-sm text-gray-500 dark:text-gray-400 mb-2">Desktop</p>
                                <p class="text-3xl font-bold text-gray-900 dark:text-white">{{ $audit->lighthouse_score_desktop }}/100</p>
                            </div>
                        @endif
                    </div>
                </div>
            @endif
        </div>

        @if($audit->recommendations->isEmpty())
            <div class="bg-green-50 dark:bg-green-900/50 border border-green-200 dark:border-green-800 rounded-lg p-6 text-center">
                <h3 class="text-lg font-semibold text-green-900 dark:text-green-100 mb-2">Great job! No major issues found.</h3>
                <p class="text-green-700 dark:text-green-200">Your website follows SEO best practices.</p>
            </div>
        @else
            <div class="space-y-8">
                @if($recommendationsByPriority->has('fix_first'))
                    <div>
                        <h2 class="text-2xl font-bold text-red-600 dark:text-red-400 mb-4 flex items-center">
                            <span class="bg-red-100 dark:bg-red-900 text-red-600 dark:text-red-400 px-3 py-1 rounded-full text-sm font-semibold mr-3">Fix First</span>
                            Critical Issues
                        </h2>
                        <div class="space-y-4">
                            @foreach($recommendationsByPriority['fix_first'] as $rec)
                                <div class="bg-white dark:bg-gray-800 border-l-4 border-red-500 shadow sm:rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $rec->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $rec->description }}</p>
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">How to fix:</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rec->how_to_fix }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">Impact Score: {{ $rec->impact_score }}/10</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($recommendationsByPriority->has('next'))
                    <div>
                        <h2 class="text-2xl font-bold text-yellow-600 dark:text-yellow-400 mb-4 flex items-center">
                            <span class="bg-yellow-100 dark:bg-yellow-900 text-yellow-600 dark:text-yellow-400 px-3 py-1 rounded-full text-sm font-semibold mr-3">Next</span>
                            Important Improvements
                        </h2>
                        <div class="space-y-4">
                            @foreach($recommendationsByPriority['next'] as $rec)
                                <div class="bg-white dark:bg-gray-800 border-l-4 border-yellow-500 shadow sm:rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $rec->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $rec->description }}</p>
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">How to fix:</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rec->how_to_fix }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">Impact Score: {{ $rec->impact_score }}/10</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif

                @if($recommendationsByPriority->has('nice_to_have'))
                    <div>
                        <h2 class="text-2xl font-bold text-blue-600 dark:text-blue-400 mb-4 flex items-center">
                            <span class="bg-blue-100 dark:bg-blue-900 text-blue-600 dark:text-blue-400 px-3 py-1 rounded-full text-sm font-semibold mr-3">Nice to Have</span>
                            Optional Enhancements
                        </h2>
                        <div class="space-y-4">
                            @foreach($recommendationsByPriority['nice_to_have'] as $rec)
                                <div class="bg-white dark:bg-gray-800 border-l-4 border-blue-500 shadow sm:rounded-lg p-6">
                                    <h3 class="text-lg font-semibold text-gray-900 dark:text-white mb-2">{{ $rec->title }}</h3>
                                    <p class="text-gray-600 dark:text-gray-400 mb-3">{{ $rec->description }}</p>
                                    <div class="bg-gray-50 dark:bg-gray-900 rounded p-4">
                                        <p class="text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">How to fix:</p>
                                        <p class="text-sm text-gray-600 dark:text-gray-400">{{ $rec->how_to_fix }}</p>
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-500 mt-3">Impact Score: {{ $rec->impact_score }}/10</p>
                                </div>
                            @endforeach
                        </div>
                    </div>
                @endif
            </div>
        @endif

        <div class="mt-8 bg-indigo-50 dark:bg-indigo-900/20 border border-indigo-200 dark:border-indigo-800 rounded-lg p-6">
            <h3 class="text-lg font-semibold text-indigo-900 dark:text-indigo-100 mb-2">Want more insights?</h3>
            <p class="text-indigo-700 dark:text-indigo-200 mb-4">Sign up for unlimited audits, Google Search Console integration, and keyword tracking.</p>
            <a href="{{ route('register') }}" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700">
                Sign Up Free
            </a>
        </div>
    </div>
</x-layouts.marketing>
