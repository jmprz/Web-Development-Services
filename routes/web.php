<?php

use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\PostingRequestController;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

Route::get('/submit-request', [PostingRequestController::class, 'create'])->name('public.request.form');
Route::post('/submit-request', [PostingRequestController::class, 'store'])->name('public.request.store');

Route::middleware(['auth'])->group(function () {
    Route::get('/dashboard', [PostingRequestController::class, 'index'])->name('dashboard');
    Route::patch('/posting-request/{postingRequest}/status', [PostingRequestController::class, 'updateStatus'])->name('posting-request.update-status');
});

require __DIR__.'/auth.php';
