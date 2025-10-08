<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class SubscriptionController extends Controller
{
    public function checkout(Request $request)
    {
        $plan = $request->input('plan');
        
        $prices = [
            'starter' => config('services.stripe.prices.starter'),
            'growth' => config('services.stripe.prices.growth'),
            'onetime' => config('services.stripe.prices.onetime'),
        ];
        
        \Log::info('Checkout attempt', ['plan' => $plan, 'prices' => $prices]);
        
        if (!isset($prices[$plan])) {
            \Log::error('Plan not in array', ['plan' => $plan, 'available' => array_keys($prices)]);
            return redirect()->route('pricing')->withErrors(['plan' => 'Invalid plan selected']);
        }
        
        if (!$prices[$plan]) {
            return redirect()->route('pricing')->withErrors([
                'config' => 'Stripe billing is not configured yet. Please contact support or check back later.'
            ]);
        }
        
        $user = Auth::user();
        
        // Handle one-time payment differently
        if ($plan === 'onetime') {
            return $user->checkout([$prices[$plan] => 1], [
                'success_url' => route('dashboard') . '?checkout=success',
                'cancel_url' => route('pricing'),
            ]);
        }
        
        // Handle recurring subscriptions
        return $user->newSubscription('default', $prices[$plan])
            ->checkout([
                'success_url' => route('dashboard') . '?checkout=success',
                'cancel_url' => route('pricing'),
            ]);
    }
    
    public function billingPortal()
    {
        return Auth::user()->redirectToBillingPortal(route('dashboard'));
    }
}
