<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Workspace extends Model
{
    protected $fillable = [
        'name',
        'slug',
        'owner_id',
        'subscription_status',
        'trial_ends_at',
        'monthly_audit_limit',
        'audits_used_this_month',
        'quota_reset_date',
    ];

    protected $casts = [
        'trial_ends_at' => 'datetime',
        'quota_reset_date' => 'date',
        'monthly_audit_limit' => 'integer',
        'audits_used_this_month' => 'integer',
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($workspace) {
            if (empty($workspace->slug)) {
                $workspace->slug = Str::slug($workspace->name);
            }
        });
    }

    public function owner(): BelongsTo
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

    public function members(): HasMany
    {
        return $this->hasMany(WorkspaceMember::class);
    }

    public function users(): BelongsToMany
    {
        return $this->belongsToMany(User::class, 'workspace_members')
            ->withPivot('role', 'invitation_status')
            ->withTimestamps();
    }

    public function audits(): HasMany
    {
        return $this->hasMany(Audit::class);
    }

    public function canRunAudit(): bool
    {
        return $this->audits_used_this_month < $this->monthly_audit_limit || $this->subscription_status === 'growth';
    }

    public function incrementAuditUsage(): void
    {
        $this->increment('audits_used_this_month');
    }
}
