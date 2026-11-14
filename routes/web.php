<?php

use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

// Admin UI: plain Blade + Tailwind, a thin client of /api/v1/admin/* (PRD §6 Frontend Strategy).
// Auth guard is client-side (AdminApi.isLoggedIn()) since the real authorization
// boundary is the Sanctum-protected API itself, not these view routes.
Route::prefix('admin')->group(function () {
    Route::get('login', fn () => view('admin.login'))->name('admin.login');
    Route::get('/', fn () => view('admin.dashboard'))->name('admin.dashboard');
});
