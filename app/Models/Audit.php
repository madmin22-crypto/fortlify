<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class Audit extends Model
{
    use SoftDeletes;
    protected $fillable = [
        'workspace_id',
        'user_id',
        'url',
        'share_token',
        'email',
        'status',
        'score',
        'lighthouse_score_mobile',
        'lighthouse_score_desktop',
        'findings',
        'metadata',
        'completed_at',
    ];

    protected $casts = [
        'findings' => 'array',
        'metadata' => 'array',
        'completed_at' => 'datetime',
        'score' => 'integer',
        'lighthouse_score_mobile' => 'integer',
        'lighthouse_score_desktop' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($audit) {
            if (empty($audit->share_token)) {
                $audit->share_token = Str::random(32);
            }
        });
    }

    public function workspace(): BelongsTo
    {
        return $this->belongsTo(Workspace::class);
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function recommendations(): HasMany
    {
        return $this->hasMany(Recommendation::class);
    }

    public function isPending(): bool
    {
        return $this->status === 'pending';
    }

    public function isProcessing(): bool
    {
        return $this->status === 'processing';
    }

    public function isCompleted(): bool
    {
        return $this->status === 'completed';
    }

    public function isFailed(): bool
    {
        return $this->status === 'failed';
    }

    public function getShareUrl(): string
    {
        return route('audits.share', $this->share_token);
    }
}
