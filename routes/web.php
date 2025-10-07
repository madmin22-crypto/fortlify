<?php

use App\Http\Controllers\MarketingController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;

Route::get('/', [MarketingController::class, 'home'])->name('home');
Route::get('/pricing', [MarketingController::class, 'pricing'])->name('pricing');
Route::get('/how-it-works', [MarketingController::class, 'howItWorks'])->name('how-it-works');
Route::get('/privacy', [MarketingController::class, 'privacy'])->name('privacy');
Route::get('/terms', [MarketingController::class, 'terms'])->name('terms');
Route::get('/contact', [MarketingController::class, 'contact'])->name('contact');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
