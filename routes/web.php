<?php

use App\Http\Controllers\AdminController;
use App\Http\Controllers\DepartmentController;
use App\Http\Controllers\DoctorController;
use App\Http\Controllers\PatientController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QueueController;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::middleware('auth')->group(function () {
    Route::get('/dashboard', function () {
        return match (Auth::user()->role) {
            'doctor' => redirect()->route('doctor.dashboard'),
            'admin' => redirect()->route('admin.dashboard'),
            default => redirect()->route('patient.dashboard'),
        };
    })->name('dashboard');

    // Patient
    Route::get('/patient', [PatientController::class, 'index'])
        ->name('patient.dashboard');

    // Queue
    Route::get('/queue/confirm/{department}', [QueueController::class, 'confirm'])
        ->name('queue.confirm');

    Route::post('/queue/join/{department}', [QueueController::class, 'join'])
        ->name('queue.join');

    Route::get('/queue/{queue}', [QueueController::class, 'show'])
        ->name('queue.show');

    // Doctor
    Route::get('/doctor', [DoctorController::class, 'index'])
        ->name('doctor.dashboard');
    Route::post('/doctor/call/{queue}', [DoctorController::class, 'callNext'])
        ->name('doctor.call');
    Route::post('/doctor/skip/{queue}', [DoctorController::class, 'skip'])
        ->name('doctor.skip');
    Route::post('/doctor/complete/{queue}', [DoctorController::class, 'complete'])
        ->name('doctor.complete');

    // Admin
    Route::get('/admin', [AdminController::class, 'index'])
        ->name('admin.dashboard');
    Route::resource('/admin/departments', DepartmentController::class)
        ->except(['show']);

    // Breeze Profile
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';