<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class EmailVerificationOtpController extends Controller
{
    public function verify(Request $request)
    {
        $request->validate([
            'code' => ['required', 'digits:6'],
        ]);

        $user = $request->user();

        if ($user->hasVerifiedEmail()) {
            return redirect()->route('landing');
        }

        if (
            !$user->email_verification_otp_hash ||
            !$user->email_verification_otp_expires_at ||
            now()->greaterThan($user->email_verification_otp_expires_at)
        ) {
            return back()->withErrors(['code' => 'This verification code has expired. Request a new code.']);
        }

        if ($user->email_verification_otp_attempts >= 5) {
            return back()->withErrors(['code' => 'Too many incorrect attempts. Request a new code.']);
        }

        if (!Hash::check($request->string('code')->toString(), $user->email_verification_otp_hash)) {
            $user->increment('email_verification_otp_attempts');

            return back()->withErrors(['code' => 'The verification code is invalid.']);
        }

        $user->forceFill([
            'email_verified_at' => now(),
            'email_verification_otp_hash' => null,
            'email_verification_otp_expires_at' => null,
            'email_verification_otp_attempts' => 0,
        ])->save();

        return redirect()->route('landing')
            ->with('status', 'email-verified');
    }
}