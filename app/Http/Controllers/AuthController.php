<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Models\User;

class AuthController extends Controller
{
    public function showLogin()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string',
        ]);

        $user = User::where(function($query) use ($request) {
                $query->where('email', $request->email)
                    ->orWhere('username', $request->email);
            })
            ->where('is_active', 1)
            ->first();

        if ($user) {
            // Support both 'password' and legacy 'password_hash' column names
            $storedHash = $user->password ?? $user->password_hash ?? null;

            if (
                $storedHash && (
                    Hash::check($request->password, $storedHash) ||
                    password_verify($request->password, $storedHash)
                )
            ) {
                Auth::login($user, $request->boolean('remember'));
                $request->session()->regenerate();
                return redirect()->intended(route('dashboard'))
                    ->with('success', 'Welcome back, ' . ($user->full_name ?? $user->name) . '!');
            }
        }

        return back()->withErrors([
            'email' => 'Invalid email or password.',
        ])->onlyInput('email');
    }

    public function register(Request $request)
    {
        $request->validate([
            'first_name'    => ['required', 'string', 'max:25', 'regex:/^[a-zA-ZñÑ]+$/'],
            'middle_name'   => ['nullable', 'string', 'max:25', 'regex:/^[a-zA-ZñÑ]+$/'],
            'last_name'     => ['required', 'string', 'max:25', 'regex:/^[a-zA-ZñÑ]+( [a-zA-ZñÑ]+)?$/'],
            'suffix'        => ['nullable', 'in:,N/A,Jr.,Sr.,II,III,IV,V'],
            'phone_number'  => ['required', 'string', 'regex:/^9[0-9]{9}$/'],
            'email'         => ['required', 'email', 'unique:users,email', 'regex:/^(?!.*\.{2})[a-zA-Z][a-zA-Z0-9.]{4,28}[a-zA-Z0-9]@gmail\.com$/i'],
            'password'      => ['required', 'string', 'min:6', 'confirmed', 'regex:/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)(?=.*[^A-Za-z0-9])[A-Za-z\d\D]{6,}$/'],
            'role'          => 'required|in:staff,secretary,manager,dispatcher',
        ], [
            'first_name.regex'      => 'First name must contain letters only (no spaces or numbers).',
            'first_name.max'        => 'First name must not exceed 25 characters.',
            'middle_name.regex'     => 'Middle name must contain letters only.',
            'middle_name.max'       => 'Middle name must not exceed 25 characters.',
            'last_name.regex'       => 'Last name must contain letters only. A single space is permitted.',
            'last_name.max'         => 'Last name must not exceed 25 characters.',
            'phone_number.regex'    => 'Phone number must be a valid Philippine number starting with 9 followed by 9 digits.',
            'phone_number.required' => 'Phone number is required.',
            'email.regex'           => 'Only Gmail addresses are accepted (e.g. yourname@gmail.com).',
            'email.unique'          => 'This email already has an existing account.',
            'password.regex'        => 'Password must contain at least one uppercase letter, one lowercase letter, one number, and one symbol.',
            'password.min'          => 'Password must be at least 6 characters long.',
            'password.confirmed'    => 'Passwords do not match.',
        ]);

        // Generate username based on role and first name
        $rolePrefix = $request->role;
        $firstName = strtolower(str_replace(' ', '', $request->first_name));
        $username = $rolePrefix . '-' . $firstName;
        
        // Ensure unique username
        $originalUsername = $username;
        $counter = 1;
        while (User::where('username', $username)->exists()) {
            $username = $originalUsername . '-' . $counter;
            $counter++;
        }

        // Build the full display name
        $middleInitial = $request->middle_name ? ' ' . strtoupper(substr($request->middle_name, 0, 1)) . '.' : '';
        $suffixPart = $request->suffix ? ' ' . $request->suffix : '';
        $fullName = $request->first_name . $middleInitial . ' ' . $request->last_name . $suffixPart;

        $user = User::create([
            'full_name'    => $fullName,
            'first_name'   => $request->first_name,
            'middle_name'  => $request->middle_name,
            'last_name'    => $request->last_name,
            'suffix'       => $request->suffix,
            'phone_number' => '0' . ltrim($request->phone_number, '0'),
            'email'        => $request->email,
            'username'     => $username,
            'password'     => Hash::make($request->password),
            'password_hash' => Hash::make($request->password),
            'role'         => $request->role,
            'is_active'    => true,
        ]);

        Auth::login($user);
        $request->session()->regenerate();

        return redirect()->route('dashboard')
            ->with('success', 'Account created successfully!');
    }

    public function showRegister()
    {
        if (Auth::check()) {
            return redirect()->route('dashboard');
        }
        return view('auth.register');
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();
        return redirect()->route('login');
    }

    /**
     * Send Reset OTP via Email
     */
    public function sendResetOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'email' => 'required|email|exists:users,email'
        ], [
            'email.exists' => 'No account found with this email address.',
            'email.email' => 'Please enter a valid email address.',
            'email.required' => 'Email address is required.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => $validator->errors()->first('email')
            ], 422);
        }
        
        $user = User::where('email', $request->email)->first();
        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        $body = "<h2>Password Reset</h2>
                 <p>Your OTP code for password reset is: <b>{$otp}</b></p>
                 <p>This code will expire in 10 minutes.</p>";
        
        if (send_custom_email($request->email, "Password Reset OTP - Euro Taxi System", $body)) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your email.']);
        }

        return response()->json(['success' => false, 'message' => 'Service unavailable. Please try again later.'], 500);
    }

    /**
     * Send Reset OTP via SMS
     */
    public function sendSmsResetOtp(Request $request)
    {
        $validator = \Validator::make($request->all(), [
            'phone' => 'required|string|min:10|exists:users,phone_number'
        ], [
            'phone.exists' => 'This phone number is not registered in our system.',
            'phone.required' => 'Phone number is required.',
            'phone.min' => 'Please enter a valid phone number.'
        ]);

        if ($validator->fails()) {
            return response()->json([
                'success' => false, 
                'message' => $validator->errors()->first('phone')
            ], 422);
        }
        
        // Sanitize for DB search (09...)
        $searchPhone = $request->phone;
        if (str_starts_with($searchPhone, '+63')) {
            $searchPhone = '0' . ltrim($searchPhone, '+63');
        } elseif (!str_starts_with($searchPhone, '0')) {
            $searchPhone = '0' . $searchPhone;
        }

        $user = User::where('phone_number', $searchPhone)->first();
        if (!$user) {
            return response()->json(['success' => false, 'message' => 'This phone number is not registered in our system.'], 422);
        }

        $otp = sprintf("%06d", mt_rand(1, 999999));
        
        $user->update([
            'otp_code' => $otp,
            'otp_expires_at' => now()->addMinutes(10)
        ]);

        $message = "Your Euro Taxi reset code is: {$otp}. Valid for 10 mins.";
        
        // Sanitize for Semaphore (+63...)
        $smsPhone = $searchPhone;
        if (str_starts_with($smsPhone, '0')) {
            $smsPhone = '+63' . ltrim($smsPhone, '0');
        }
        
        if (send_sms_otp($smsPhone, $message, $otp)) {
            return response()->json(['success' => true, 'message' => 'OTP sent to your phone.']);
        }

        return response()->json(['success' => false, 'message' => 'SMS service temporarily unavailable.'], 500);
    }

    /**
     * Verify Reset OTP (Unified for Email/Phone)
     */
    public function verifyResetOtp(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6'
        ]);

        $identifier = $request->identifier;
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            // Sanitize phone for DB lookup
            $identifier = ltrim($identifier, '+63');
            if (!str_starts_with($identifier, '0')) $identifier = '0' . $identifier;
        }

        $user = User::where(function($q) use ($identifier) {
                        $q->where('email', $identifier)
                          ->orWhere('phone_number', $identifier);
                    })
                    ->where('otp_code', $request->otp)
                    ->where('otp_expires_at', '>', now())
                    ->first();

        if (!$user) {
            return response()->json(['success' => false, 'message' => 'Invalid or expired OTP.'], 400);
        }

        return response()->json(['success' => true, 'message' => 'OTP verified successfully.']);
    }

    /**
     * Reset Password (Unified for Email/Phone)
     */
    public function resetPassword(Request $request)
    {
        $request->validate([
            'identifier' => 'required|string',
            'otp' => 'required|string|size:6',
            'password' => 'required|string|min:6|confirmed'
        ]);

        $identifier = $request->identifier;
        if (!filter_var($identifier, FILTER_VALIDATE_EMAIL)) {
            $identifier = ltrim($identifier, '+63');
            if (!str_starts_with($identifier, '0')) $identifier = '0' . $identifier;
        }

        $user = User::where(function($q) use ($identifier) {
                        $q->where('email', $identifier)
                          ->orWhere('phone_number', $identifier);
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
     * Check if email or phone is already registered (AJAX)
     */
    public function checkAvailability(Request $request)
    {
        if ($request->has('email')) {
            $exists = User::where('email', $request->email)->exists();
            return response()->json(['available' => !$exists, 'message' => $exists ? 'This email is already registered.' : '']);
        }

        if ($request->has('phone')) {
            $phone = $request->phone;
            // standard sanitization for lookup
            $phone = ltrim($phone, '+63');
            if (!str_starts_with($phone, '0')) $phone = '0' . $phone;

            $exists = User::where('phone_number', $phone)->exists();
            return response()->json(['available' => !$exists, 'message' => $exists ? 'This phone number is already registered.' : '']);
        }

        if ($request->has('first_name')) {
            $exists = User::where('first_name', $request->first_name)->exists();
            return response()->json(['available' => !$exists, 'message' => $exists ? 'This first name is already taken.' : '']);
        }

        return response()->json(['error' => 'Invalid request'], 400);
    }
}
