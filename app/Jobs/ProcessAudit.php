<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Services\SeoAuditorService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Log;

class ProcessAudit implements ShouldQueue
{
    use Queueable;

    public function __construct(
        public Audit $audit
    ) {}

    public function handle(SeoAuditorService $auditorService): void
    {
        try {
            $this->audit->update(['status' => 'running']);
            
            $auditorService->runAudit($this->audit);
            
        } catch (\Exception $e) {
            Log::error('Audit processing failed', [
                'audit_id' => $this->audit->id,
                'url' => $this->audit->url,
                'error' => $e->getMessage()
            ]);
            
            $this->audit->update([
                'status' => 'failed',
                'metadata' => array_merge($this->audit->metadata ?? [], [
                    'error' => $e->getMessage()
                ])
            ]);
            
            throw $e;
        }
    }
}
