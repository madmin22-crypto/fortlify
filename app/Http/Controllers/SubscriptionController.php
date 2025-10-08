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
            'starter' => env('STRIPE_PRICE_STARTER'),
            'growth' => env('STRIPE_PRICE_GROWTH'),
        ];
        
        if (!isset($prices[$plan])) {
            return redirect()->route('pricing')->withErrors(['plan' => 'Invalid plan selected']);
        }
        
        if (!$prices[$plan]) {
            return redirect()->route('pricing')->withErrors([
                'config' => 'Stripe billing is not configured yet. Please contact support or check back later.'
            ]);
        }
        
        $user = Auth::user();
        
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
