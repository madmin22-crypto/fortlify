<?php

namespace App\Jobs;

use App\Models\Audit;
use App\Services\SeoAuditorService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Log;

class ProcessAudit implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

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
