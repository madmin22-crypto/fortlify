<?php

namespace App\Http\Controllers;

use App\Models\Audit;
use App\Services\SeoAuditorService;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class AuditController extends Controller
{
    public function __construct(
        private SeoAuditorService $auditor
    ) {}

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

        $audit = Audit::create([
            'url' => $url,
            'status' => 'pending',
            'email' => $validated['email'] ?? null,
            'share_token' => Str::random(32),
        ]);

        try {
            $this->auditor->runAudit($audit);
        } catch (\Exception $e) {
            $audit->update([
                'status' => 'failed',
                'metadata' => [
                    'error' => $e->getMessage(),
                ],
            ]);

            return back()
                ->withErrors(['url' => 'Failed to audit the website: ' . $e->getMessage()])
                ->withInput();
        }

        return redirect()
            ->route('audits.show', $audit)
            ->with('success', 'Audit completed successfully!');
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
