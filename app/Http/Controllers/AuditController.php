<?php

namespace App\Http\Controllers;

use App\Jobs\ProcessAudit;
use App\Models\Audit;
use Illuminate\Http\Request;
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

        $audit = Audit::create([
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
