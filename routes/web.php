<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingRequestController;
use Illuminate\Support\Facades\Route;

// Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Public Form Routes
Route::get('/submit-request', [PostingRequestController::class, 'create'])->name('public.request.form');
Route::post('/submit-request', [PostingRequestController::class, 'store'])->name('public.request.store');

// Authenticated (Admin) Routes
Route::middleware(['auth', 'verified'])->group(function () {
    
    // Dashboard - Only one definition needed
    Route::get('/dashboard', [PostingRequestController::class, 'index'])->name('dashboard');

    // PDF Generation
    Route::get('/posting-request/pdf/{postingRequest}', [PostingRequestController::class, 'downloadPdf'])
        ->name('posting-request.pdf');

    // Status Updates
    Route::patch('/posting-request/{postingRequest}/status', [PostingRequestController::class, 'updateStatus'])
        ->name('posting-request.update-status');

    // Profile Management (Breeze Default)
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';