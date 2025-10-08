<x-layouts.marketing>
    <div class="min-h-screen bg-gray-50 py-12">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="mb-8">
                <a href="{{ route('home') }}" class="inline-flex items-center text-indigo-600 hover:text-indigo-800">
                    ← Back to Home
                </a>
            </div>

            <div class="bg-white rounded-lg shadow-sm p-8">
                <div class="text-center">
                    <div class="mb-6">
                        <svg class="animate-spin h-8 w-8 mx-auto text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                            <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                            <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                        </svg>
                    </div>

                    <h1 class="text-3xl font-bold text-gray-900 mb-4">Analyzing Your Website</h1>
                    
                    <p class="text-lg text-gray-600 mb-2">
                        We're crawling <span class="font-semibold text-indigo-600">{{ $audit->url }}</span>
                    </p>
                    
                    <p class="text-gray-500 mb-8">
                        This usually takes 10-20 seconds...
                    </p>

                    <div class="bg-indigo-50 rounded-lg p-6 max-w-md mx-auto">
                        <h3 class="font-semibold text-indigo-900 mb-3">What We're Checking:</h3>
                        <ul class="text-left text-sm text-indigo-700 space-y-2">
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Technical SEO (title tags, meta descriptions, canonical tags)</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Page structure and content hierarchy</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Mobile & desktop performance with Google Lighthouse</span>
                            </li>
                            <li class="flex items-start">
                                <svg class="h-4 w-4 mr-2 flex-shrink-0 text-indigo-500" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm3.707-9.293a1 1 0 00-1.414-1.414L9 10.586 7.707 9.293a1 1 0 00-1.414 1.414l2 2a1 1 0 001.414 0l4-4z" clip-rule="evenodd"/>
                                </svg>
                                <span>Image optimization and link structure</span>
                            </li>
                        </ul>
                    </div>

                    <p class="text-sm text-gray-500 mt-6">
                        Status: <span class="font-semibold capitalize">{{ $audit->status }}</span>
                    </p>
                </div>
            </div>
        </div>
    </div>

    <script>
        setTimeout(function() {
            window.location.reload();
        }, 3000);
    </script>
</x-layouts.marketing>
