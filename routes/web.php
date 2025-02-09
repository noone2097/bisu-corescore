<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PasswordSetupController;
use App\Http\Controllers\Office\PasswordSetupController as OfficePasswordSetupController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/password/setup/{token}', [PasswordSetupController::class, 'showSetupForm'])
        ->name('admin.password.setup.form');
    Route::post('/password/setup', [PasswordSetupController::class, 'setupPassword'])
        ->name('admin.password.setup');
});

Route::prefix('office')->group(function () {
    Route::get('/password/setup/{token}', [OfficePasswordSetupController::class, 'showSetupForm'])
        ->name('office.password.setup.form');
    Route::post('/password/setup', [OfficePasswordSetupController::class, 'setupPassword'])
        ->name('office.password.setup.post');
});
