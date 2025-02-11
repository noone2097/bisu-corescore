<?php

namespace App\Http\Controllers\Department;

use App\Http\Controllers\Controller;
use App\Models\DepartmentAccount;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class PasswordSetupController extends Controller
{
    public function showSetupForm(string $token)
    {
        return view('department.password-setup', ['token' => $token]);
    }

    public function setupPassword(Request $request)
    {
        $request->validate([
            'token' => 'required',
            'password' => 'required|min:8|confirmed',
        ]);

        $account = DepartmentAccount::where('password_reset_token', $request->token)
            ->where('password_reset_expires_at', '>', now())
            ->first();

        if (!$account) {
            return back()
                ->withErrors(['token' => 'This password setup link is invalid or has expired.']);
        }

        $account->update([
            'password' => Hash::make($request->password),
            'password_reset_token' => null,
            'password_reset_expires_at' => null,
            'email_verified_at' => now(),
            'status' => 'active',
        ]);

        return redirect()
            ->route('filament.department.auth.login')
            ->with('status', 'Your password has been set successfully. You can now log in.');
    }
}