<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\FeedbackController;
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

// Feedback Form Routes
Route::controller(FeedbackController::class)->group(function () {
    Route::get('/feedback', 'index')->name('feedback.form');
    Route::get('/feedback/office/{office}', 'index')->name('feedback.form.office');
    Route::post('/feedback', 'store')->name('feedback.store');
    Route::get('/feedback/thank-you/{office?}', 'thankYou')->name('thank-you');
    Route::get('/feedback/qr-pdf/{qrCodePath}', 'generateQrPdf')->name('feedback.qr.pdf')->where('qrCodePath', '.*');
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
