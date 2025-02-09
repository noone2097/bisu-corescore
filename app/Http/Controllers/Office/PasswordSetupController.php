<?php

namespace App\Http\Controllers\Office;

use App\Http\Controllers\Controller;
use App\Models\Office;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordSetupController extends Controller
{
    public function showSetupForm(string $token)
    {
        $office = Office::where('password_reset_token', $token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$office) {
            abort(404, 'This password setup link is invalid or has expired.');
        }

        return view('office.password-setup', ['token' => $token]);
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => ['required'],
            'password' => ['required', 'min:8', 'confirmed'],
        ]);

        $office = Office::where('password_reset_token', $request->token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$office) {
            abort(404, 'This password setup link is invalid or has expired.');
        }

        $office->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        return redirect()->route('filament.office.auth.login')
            ->with('status', 'Your password has been set successfully. You can now log in.');
    }
}