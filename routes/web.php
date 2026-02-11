<?php

use App\Http\Controllers\ProfileController;
use App\Http\Controllers\PostingRequestController;
use Illuminate\Support\Facades\Route;

// Truly Public Routes
Route::get('/', function () {
    return view('welcome');
});

// Authenticated (Admin Only) Routes
Route::middleware(['auth', 'verified'])->group(function () {

    // Dashboard
    Route::get('/dashboard', [PostingRequestController::class, 'index'])->name('dashboard');

    // Internal Form Routes (Now protected)
    // Changing the name to 'admin.request.form' makes it clearer
    Route::get('/submit-request', [PostingRequestController::class, 'create'])->name('posting-request-form');
    Route::post('/submit-request', [PostingRequestController::class, 'store'])->name('public.request.store');

    // PDF Generation
    Route::get('/posting-request/pdf/{postingRequest}', [PostingRequestController::class, 'downloadPdf'])
        ->name('posting-request.pdf');

    // Status Updates
    Route::patch('/posting-request/{postingRequest}/status', [PostingRequestController::class, 'updateStatus'])
        ->name('posting-request.update-status');

    // CRUD Operations
    Route::get('/admin/request/{id}/edit', [PostingRequestController::class, 'edit'])->name('posting-request.edit');
    Route::put('/admin/request/{id}', [PostingRequestController::class, 'update'])->name('posting-request.update');
    Route::delete('/admin/request/{id}', [PostingRequestController::class, 'destroy'])->name('posting-request.destroy');

    // Profile Management
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

    // Reminder Routes
    Route::post('/admin/reminders', [App\Http\Controllers\ReminderController::class, 'store'])->name('reminders.store');
    Route::delete('/admin/reminders/{reminder}', [App\Http\Controllers\ReminderController::class, 'destroy'])->name('reminders.destroy');
    Route::patch('/admin/reminders/{reminder}', [App\Http\Controllers\ReminderController::class, 'update'])->name('reminders.update');

    });

require __DIR__ . '/auth.php';