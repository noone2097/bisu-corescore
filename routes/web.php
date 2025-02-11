<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\EvaluationController;
use App\Http\Controllers\Admin\PasswordSetupController as AdminPasswordSetupController;
use App\Http\Controllers\Office\PasswordSetupController as OfficePasswordSetupController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

Route::get('/', function () {
    return view('welcome');
});

// Evaluation Form Routes
Route::controller(EvaluationController::class)->group(function () {
    Route::get('/evaluation', 'index')->name('evaluations.form');
    Route::get('/evaluation/office/{office}', 'index')->name('evaluations.form.office');
    Route::post('/evaluation', 'store')->name('evaluations.store');
    Route::get('/evaluation/thank-you', 'thankYou')->name('thank-you');
});

// Admin Password Setup Routes
Route::get('/admin/password/setup/{token}', [AdminPasswordSetupController::class, 'showSetupForm'])
    ->name('admin.password.setup');
Route::post('/admin/password/setup', [AdminPasswordSetupController::class, 'setupPassword'])
    ->name('admin.password.setup.store');

// Office Password Setup Routes
Route::get('/office/password/setup/{token}', [OfficePasswordSetupController::class, 'showSetupForm'])
    ->name('office.password.setup');
Route::post('/office/password/setup', [OfficePasswordSetupController::class, 'setupPassword'])
    ->name('office.password.setup.store');

// Department Password Setup Routes
Route::get('/department/password/setup/{token}', [App\Http\Controllers\Department\PasswordSetupController::class, 'showSetupForm'])
    ->name('department.password.setup');
Route::post('/department/password/setup', [App\Http\Controllers\Department\PasswordSetupController::class, 'setupPassword'])
    ->name('department.password.setup.store');
