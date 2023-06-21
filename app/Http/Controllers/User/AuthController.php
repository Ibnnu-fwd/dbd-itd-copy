<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;

class AuthController extends Controller
{
    public function checkEmail(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();
            return redirect()->route('admin.user.reset-password-form')->with('success', 'Email ditemukan');
        } catch (\Throwable $th) {
            return redirect()->route('password.request')->with('error', 'Email tidak ditemukan');
        }
    }

    public function resetPasswordForm()
    {
        return view('auth.reset-password');
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => ['required', 'email'],
            'new_password' => ['required', 'confirmed'],
        ]);

        try {
            $user = User::where('email', $request->email)->firstOrFail();
            $user->update([
                'password' => bcrypt($request->new_password),
            ]);
            return redirect()->route('login')->with('success', 'Password berhasil diubah');
        } catch (\Throwable $th) {
            return redirect()->route('admin.user.reset-password-form')->with('error', 'Email tidak ditemukan');
        }
    }
}
