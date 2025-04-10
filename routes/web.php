<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmployeeController;

// Authentication Routes
Route::get('/', [AuthController::class, 'showLogin']);
Route::post('/login', [AuthController::class, 'login'])->name('login');
Route::get('/logout', [AuthController::class, 'logout'])->name('logout');

// Protected Routes (after login)
Route::middleware('web')->group(function () {
    // Admin Dashboard & Actions
    Route::get('/admin/dashboard', [EmployeeController::class, 'admin'])->name('admin.dashboard');
    Route::post('/employee/store', [EmployeeController::class, 'store'])->name('employee.store');
    // Route::get('/employee/edit/{id}', [EmployeeController::class, 'edit'])->name('employee.edit');
    Route::put('/employee/update/{id}', [EmployeeController::class, 'update'])->name('employee.update');
    Route::delete('/employee/delete/{id}', [EmployeeController::class, 'destroy'])->name('employee.delete');

    // User Dashboard (view only)
    Route::get('/user/dashboard', [EmployeeController::class, 'index'])->name('user.dashboard');
});
