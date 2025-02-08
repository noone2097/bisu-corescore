<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\PasswordSetupController;

Route::get('/', function () {
    return view('welcome');
});

Route::prefix('admin')->group(function () {
    Route::get('/password/setup/{token}', [PasswordSetupController::class, 'showSetupForm'])
        ->name('admin.password.setup.form');
    Route::post('/password/setup', [PasswordSetupController::class, 'setupPassword'])
        ->name('admin.password.setup');
});
