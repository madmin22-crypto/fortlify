<x-layouts.marketing>
    <x-slot name="title">Privacy Policy</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-8">Privacy Policy</h1>
        
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-400 mb-4">Last updated: {{ date('F d, Y') }}</p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. Information We Collect</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We collect information you provide directly to us, including:
            </p>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 ml-4">
                <li>Account information (name, email address)</li>
                <li>Website URLs you submit for auditing</li>
                <li>Payment information (processed securely through Stripe)</li>
                <li>Usage data and analytics</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. How We Use Your Information</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We use the information we collect to:
            </p>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 ml-4">
                <li>Provide and improve our SEO audit services</li>
                <li>Process your payments and subscriptions</li>
                <li>Send you audit results and service updates</li>
                <li>Respond to your requests and support needs</li>
                <li>Analyze usage to improve our product</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. Data Security</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We implement appropriate security measures to protect your personal information. All payment processing is handled securely through Stripe.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">4. Data Sharing</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We do not sell your personal information. We may share data with:
            </p>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 ml-4">
                <li>Service providers (Stripe for payments, Google for Lighthouse audits)</li>
                <li>Law enforcement when required by law</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">5. Your Rights</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                You have the right to:
            </p>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 ml-4">
                <li>Access your personal data</li>
                <li>Request data deletion</li>
                <li>Opt-out of marketing communications</li>
                <li>Export your data</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">6. Contact Us</h2>
            <p class="text-gray-600 dark:text-gray-400">
                If you have questions about this Privacy Policy, please contact us at <a href="{{ route('contact') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">our contact page</a>.
            </p>
        </div>
    </div>
</x-layouts.marketing>
