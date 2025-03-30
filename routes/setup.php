<?php

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Route;

Route::get('/setup-password/{email}', function (Request $request) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }
    $user = User::where('email', $request->email)->firstOrFail();
    return view('auth.setup-password', [
        'email' => $request->email,
        'role' => 'department',
        'name' => $user->name
    ]);
})->middleware(['setup'])->name('password.setup');

Route::get('/faculty/setup-password/{email}', function (Request $request) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }

    $user = User::where('email', $request->email)->firstOrFail();
    return view('auth.setup-password', [
        'email' => $request->email,
        'role' => 'faculty',
        'name' => $user->name
    ]);
})->middleware(['setup'])->name('faculty.setup.password');

Route::get('/office/setup-password/{email}', function (Request $request) {
    if (!$request->hasValidSignature()) {
        abort(401);
    }

    $user = User::where('email', $request->email)->firstOrFail();
    return view('auth.setup-password', [
        'email' => $request->email,
        'role' => 'office',
        'name' => $user->name
    ]);
})->middleware(['setup'])->name('office.setup.password');

Route::post('/setup-password', function (Request $request) {
    $request->validate([
        'email' => ['required', 'email'],
        'password' => ['required', 'min:8', 'confirmed'],
        'role' => ['required', 'in:department,faculty,office']
    ]);

    $user = User::where('email', $request->email)->first();
    
    if (!$user || $user->role !== $request->role) {
        abort(404);
    }

    $user->password = Hash::make($request->password);
    $user->email_verified_at = now();
    $user->is_active = true;
    $user->save();

    // Login the user
    auth()->login($user);

    // Redirect based on role
    switch ($user->role) {
        case 'faculty':
            $redirectPath = '/faculty';
            break;
        case 'office':
            $redirectPath = '/office';
            break;
        default:
            $redirectPath = '/department';
    }
    
    return redirect($redirectPath)->with('status', 'Your password has been set successfully.');
})->middleware(['setup'])->name('password.setup.save');