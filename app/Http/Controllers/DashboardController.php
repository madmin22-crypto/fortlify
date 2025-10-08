<?php

namespace App\Http\Controllers;

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
        
        return view('dashboard', [
            'user' => $user,
            'audits' => $audits,
            'subscription' => $subscription,
        ]);
    }
}
