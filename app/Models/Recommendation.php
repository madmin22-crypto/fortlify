<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Recommendation extends Model
{
    protected $fillable = [
        'audit_id',
        'category',
        'priority',
        'impact_score',
        'effort_score',
        'title',
        'description',
        'how_to_fix',
        'technical_details',
    ];

    protected $casts = [
        'technical_details' => 'array',
        'impact_score' => 'integer',
        'effort_score' => 'integer',
    ];

    public function audit(): BelongsTo
    {
        return $this->belongsTo(Audit::class);
    }

    public function isFixFirst(): bool
    {
        return $this->priority === 'fix_first';
    }

    public function isNext(): bool
    {
        return $this->priority === 'next';
    }

    public function isNiceToHave(): bool
    {
        return $this->priority === 'nice_to_have';
    }

    public function getPriorityLabel(): string
    {
        return match($this->priority) {
            'fix_first' => 'Fix First',
            'next' => 'Next',
            'nice_to_have' => 'Nice to Have',
            default => $this->priority,
        };
    }

    public function getCategoryLabel(): string
    {
        return match($this->category) {
            'technical' => 'Technical SEO',
            'content' => 'Content',
            'performance' => 'Performance',
            'accessibility' => 'Accessibility',
            default => ucfirst($this->category),
        };
    }
}
