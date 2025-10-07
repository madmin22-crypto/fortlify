<x-layouts.marketing>
    <x-slot name="title">Terms of Service</x-slot>

    <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8 py-24">
        <h1 class="text-4xl font-extrabold text-gray-900 dark:text-white mb-8">Terms of Service</h1>
        
        <div class="prose dark:prose-invert max-w-none">
            <p class="text-gray-600 dark:text-gray-400 mb-4">Last updated: {{ date('F d, Y') }}</p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">1. Acceptance of Terms</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                By accessing and using Fortlify, you accept and agree to be bound by these Terms of Service.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">2. Service Description</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Fortlify provides SEO and conversion audit services. We analyze websites you submit and provide recommendations for improvement.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">3. User Responsibilities</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                You agree to:
            </p>
            <ul class="list-disc list-inside text-gray-600 dark:text-gray-400 mb-4 ml-4">
                <li>Provide accurate information</li>
                <li>Only audit websites you own or have permission to audit</li>
                <li>Not abuse or overload our services</li>
                <li>Maintain the security of your account</li>
            </ul>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">4. Subscription and Payments</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Paid plans are billed monthly. You can cancel anytime. Refunds are provided on a case-by-case basis within 14 days of purchase.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">5. Audit Limitations</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Audit quotas reset monthly. Free accounts: 1 audit/month. Starter: 10 audits/month. Growth: unlimited.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">6. Disclaimer</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                Fortlify provides recommendations based on best practices, but we cannot guarantee specific SEO results or rankings. Actual results depend on many factors beyond our control.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">7. Termination</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We may terminate or suspend access to our service immediately, without prior notice, for conduct that we believe violates these Terms.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">8. Changes to Terms</h2>
            <p class="text-gray-600 dark:text-gray-400 mb-4">
                We reserve the right to modify these terms at any time. Continued use of the service after changes constitutes acceptance of the new terms.
            </p>

            <h2 class="text-2xl font-bold text-gray-900 dark:text-white mt-8 mb-4">9. Contact</h2>
            <p class="text-gray-600 dark:text-gray-400">
                Questions about these Terms? Contact us at <a href="{{ route('contact') }}" class="text-indigo-600 dark:text-indigo-400 hover:underline">our contact page</a>.
            </p>
        </div>
    </div>
</x-layouts.marketing>
