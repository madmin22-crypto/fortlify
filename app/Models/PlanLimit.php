<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PlanLimit extends Model
{
    protected $fillable = [
        'plan_name',
        'pages_per_month',
    ];

    protected $casts = [
        'pages_per_month' => 'integer',
    ];
}
