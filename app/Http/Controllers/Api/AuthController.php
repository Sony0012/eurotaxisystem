<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Validator;
use App\Models\User;

class AuthController extends Controller
{
    /**
     * Handle an authentication attempt via email OR phone number.
     */
    public function login(Request $request)
    {
        $request->validate([
            'login'       => 'required|string',
            'password'    => 'required|string',
            'device_name' => 'required|string',
        ]);

        $user = User::where(function ($query) use ($request) {
                $query->where('email', $request->login)
                      ->orWhere('phone', $request->login)
                      ->orWhere('phone_number', $request->login);
            })
            ->where('is_active', 1)
            ->first();

        if (! $user) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials or account inactive.',
            ], 401);
        }

        // Support both 'password' and legacy 'password_hash' column names
        $storedHash = $user->password ?? $user->password_hash ?? null;

        if (! $storedHash ||
            ! (Hash::check($request->password, $storedHash) ||
               password_verify($request->password, $storedHash))
        ) {
            return response()->json([
                'success' => false,
                'message' => 'Invalid credentials.',
            ], 401);
        }

        $token = $user->createToken($request->device_name)->plainTextToken;

        return response()->json([
            'success' => true,
            'message' => 'Login successful',
            'token'   => $token,
            'user'    => [
                'id'    => $user->id,
                'name'  => $user->full_name ?? $user->name,
                'email' => $user->email,
                'role'  => $user->role,
            ],
        ]);
    }

    /**
     * Send Reset OTP via Email or SMS
     */
    public function sendResetOtp(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'identifier' => 'required|string',
            'method'     => 'required|in:email,phone'
        ]);

        if ($validator->fails()) {
            return response()->json(['success' => false, 'message' => $validator->errors()->first()], 422);
        }

        $identifier = $request->identifier;
        $method = $request->method;

        $user = User::where(function($q) use ($identifier) {
            $q->where('email', $identifier)
              ->orWhere('phone', $identifier)
              ->orWhere('phone_number', $identifier);
        })->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'No account found with this information.'], 404);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        if ($method === 'email') {
            require_once app_path('Helpers/MailerHelper.php');
            $body = "<h2>Password Reset</h2><p>Your OTP code is: <b>{$otp}</b></p>";
            if (!send_custom_email($user->email, "Eurotaxi - Password Reset OTP", $body)) {
                return response()->json(['success' => false, 'message' => 'Failed to send email.'], 500);
            }
        } else {
            $phone = $user->phone_number ?? $user->phone;
            $message = "Your Euro Taxi reset code is: {$otp}. Valid for 10 mins.";
            if (!send_sms_otp($phone, $message, $otp)) {
                return response()->json(['success' => false, 'message' => 'Failed to send SMS.'], 500);
            }
        }

        return response()->json(['success' => true, 'message' => 'OTP sent successfully!']);
    }

    /**
     * Verify OTP
     */
    public function verifyOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp'        => 'required|string|size:6'
        ]);

        $user = User::where(function($q) use ($request) {
            $q->where('email', $request->identifier)
              ->orWhere('phone', $request->identifier)
              ->orWhere('phone_number', $request->identifier);
        })
        ->where('otp_code', $request->otp)
        ->where('otp_expires_at', '>', now())
        ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'OTP verified.']);
    }

    /**
     * Reset Password
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp'        => 'required|string|size:6',
            'password'   => 'required|string|min:6|confirmed'
        ]);

        $user = User::where(function($q) use ($request) {
            $q->where('email', $request->identifier)
              ->orWhere('phone', $request->identifier)
              ->orWhere('phone_number', $request->identifier);
        })
        ->where('otp_code', $request->otp)
        ->where('otp_expires_at', '>', now())
        ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        $user->update([
            'password' => Hash::make($request->password),
            'password_hash' => Hash::make($request->password),
            'otp_code' => null,
            'otp_expires_at' => null
        ]);

        return response()->json(['success' => true, 'message' => 'Password reset successfully!']);
    }

    /**
     * Log the user out (revoke token).
     */
    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json([
            'success' => true,
            'message' => 'Logged out successfully',
        ]);
    }
}
