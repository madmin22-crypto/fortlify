<?php

use App\Http\Controllers\AuditController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\SubscriptionController;
use Illuminate\Support\Facades\Route;

Route::get('/', [HomeController::class, 'index'])->name('home');

Route::middleware('marketing.context')->group(function () {
    Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
    Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('how-it-works');
    Route::get('/privacy', [MarketingController::class, 'privacy'])->name('privacy');
    Route::get('/terms', [MarketingController::class, 'terms'])->name('terms');
    Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');
});

Route::middleware(['auth', 'verified'])->group(function () {
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
    
    Route::post('/audits', [AuditController::class, 'store'])->name('audits.store');
    Route::get('/audits/{audit}/processing', [AuditController::class, 'processing'])->name('audits.processing');
    Route::get('/audits/{audit}', [AuditController::class, 'show'])->name('audits.show');
    
    Route::post('/subscribe', [SubscriptionController::class, 'checkout'])->name('subscribe.checkout');
    Route::get('/billing/portal', [SubscriptionController::class, 'billingPortal'])->name('billing.portal');
    
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
