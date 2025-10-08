<?php

namespace App\Http\Controllers;

use App\Models\PlanLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        
        $audits = \App\Models\Audit::where('email', $user->email)
            ->orWhereHas('workspace', function($query) use ($user) {
                $query->where('owner_id', $user->id);
            })
            ->orderBy('created_at', 'desc')
            ->take(10)
            ->get();

        $subscription = $user->subscription('default');
        
        $workspace = $user->ownedWorkspaces()->first();
        $pageLimit = 10;
        $pagesUsed = 0;
        
        if ($workspace) {
            $planName = $workspace->stripe_price ?? 'free';
            
            $priceIdMap = [
                config('services.stripe.prices.starter') => 'starter',
                config('services.stripe.prices.growth') => 'growth',
                config('services.stripe.prices.onetime') => 'onetime',
            ];
            
            $plan = $priceIdMap[$planName] ?? 'free';
            $planLimit = PlanLimit::where('plan_name', $plan)->first();
            $pageLimit = $planLimit ? $planLimit->pages_per_month : 10;
            $pagesUsed = $workspace->pages_scanned_this_month;
        }
        
        return view('dashboard', [
            'user' => $user,
            'audits' => $audits,
            'subscription' => $subscription,
            'pageLimit' => $pageLimit,
            'pagesUsed' => $pagesUsed,
        ]);
    }
}
