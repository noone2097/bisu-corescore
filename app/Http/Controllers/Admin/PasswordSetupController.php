<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\AdminAccounts;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordSetupController extends Controller
{
    public function showSetupForm(string $token)
    {
        $admin = AdminAccounts::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$admin) {
            abort(404, 'This password setup link is invalid or has expired.');
        }

        return view('admin.password-setup', ['token' => $token]);
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $admin = AdminAccounts::where('password_reset_token', $request->token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$admin) {
            abort(404, 'This password setup link is invalid or has expired.');
        }

        $admin->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('filament.admin.auth.login')
            ->with('status', 'Your password has been set successfully. You can now log in.');
    }
}
