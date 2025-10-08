<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAudit;
use App\Models\Audit;
use App\Models\PlanLimit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;

class AuditController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'url' => ['required', 'url', 'max:2048'],
            'email' => ['nullable', 'email', 'max:255'],
        ]);

        $url = $validated['url'];
        
        if (!filter_var($url, FILTER_VALIDATE_URL)) {
            return back()->withErrors(['url' => 'Please enter a valid URL.'])->withInput();
        }

        $workspace = null;
        $user = Auth::user();
        
        if ($user && $user->ownedWorkspaces()->exists()) {
            $workspace = $user->ownedWorkspaces()->first();
            
            $this->resetMonthlyLimitIfNeeded($workspace);
            
            $pageLimit = $this->getWorkspacePageLimit($workspace);
            $pagesUsed = $workspace->pages_scanned_this_month;
            
            if ($pagesUsed >= $pageLimit) {
                return back()->withErrors([
                    'url' => "You've reached your monthly page limit ({$pageLimit} pages). Please upgrade your plan to continue."
                ])->withInput();
            }
        }

        $audit = Audit::create([
            'workspace_id' => $workspace?->id,
            'user_id' => $user?->id,
            'url' => $url,
            'status' => 'pending',
            'email' => $validated['email'] ?? null,
            'share_token' => Str::random(32),
        ]);

        ProcessAudit::dispatch($audit);

        return redirect()
            ->route('audits.processing', $audit)
            ->with('success', 'Your audit is being processed...');
    }

    private function resetMonthlyLimitIfNeeded($workspace): void
    {
        if (!$workspace->last_reset_at || $workspace->last_reset_at->diffInMonths(now()) >= 1) {
            $workspace->update([
                'pages_scanned_this_month' => 0,
                'last_reset_at' => now(),
            ]);
        }
    }

    private function getWorkspacePageLimit($workspace): int
    {
        $planName = $workspace->stripe_price ?? 'free';
        
        $priceIdMap = [
            config('services.stripe.prices.starter') => 'starter',
            config('services.stripe.prices.growth') => 'growth',
            config('services.stripe.prices.onetime') => 'onetime',
        ];
        
        $plan = $priceIdMap[$planName] ?? 'free';
        
        $planLimit = PlanLimit::where('plan_name', $plan)->first();
        
        return $planLimit ? $planLimit->pages_per_month : 10;
    }

    public function processing(Audit $audit)
    {
        if ($audit->status === 'completed') {
            return redirect()->route('audits.show', $audit);
        }

        if ($audit->status === 'failed') {
            return redirect()
                ->route('home')
                ->withErrors(['url' => 'Audit failed: ' . ($audit->metadata['error'] ?? 'Unknown error')]);
        }

        return view('audits.processing', [
            'audit' => $audit,
        ]);
    }

    public function show(Audit $audit)
    {
        $audit->load('recommendations');

        $recommendationsByPriority = $audit->recommendations->groupBy('priority');

        return view('audits.show', [
            'audit' => $audit,
            'recommendationsByPriority' => $recommendationsByPriority,
        ]);
    }
}
