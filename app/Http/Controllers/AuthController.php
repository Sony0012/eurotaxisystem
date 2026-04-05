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
            'phone_number' => '+63' . $request->phone_number,
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
}
