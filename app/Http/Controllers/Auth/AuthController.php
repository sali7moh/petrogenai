<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;

class AuthController extends Controller
{
    public function showLogin()
    {
        return view('auth.login');
    }

    public function login(Request $request)
    {
        $credentials = $request->validate([
            'email' => 'required|email|ends_with:@petrogen.sa',
            'password' => 'required',
        ], [
            'email.ends_with' => 'Only @petrogen.sa email addresses are allowed.',
        ]);

        // Find user
        $user = User::where('email', $credentials['email'])->first();

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            return back()->withErrors([
                'email' => 'The provided credentials do not match our records.',
            ])->onlyInput('email');
        }

        // Check if email is verified
        if (!$user->email_verified) {
            return back()->withErrors([
                'email' => 'Please verify your email address first.',
            ])->onlyInput('email');
        }

        // Generate OTP
        $otpCode = sprintf('%06d', random_int(0, 999999));
        
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::raw(
                "Your PetrogenAI login verification code is: {$otpCode}\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this code, please secure your account immediately.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->from('no-replay@petrogen.ai', 'PetrogenAI')
                        ->subject('Login Verification Code - PetrogenAI');
                }
            );
        } catch (\Exception $e) {
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        // Store email in session and redirect to OTP verification
        return redirect()->route('login.verify.show')->with('email', $credentials['email']);
    }

    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users|ends_with:@petrogen.sa',
            'password' => 'required|string|min:8|confirmed',
            'department' => 'nullable|string|max:255',
        ], [
            'email.ends_with' => 'Only @petrogen.sa email addresses are allowed.',
        ]);

        // Generate 6-digit OTP
        $otpCode = sprintf('%06d', random_int(0, 999999));
        
        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'department' => $validated['department'] ?? null,
            'role' => 'employee',
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
            'email_verified' => false,
        ]);

        // Send OTP email
        try {
            Mail::raw(
                "Your PetrogenAI verification code is: {$otpCode}\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this code, please ignore this email.",
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->from('noreply@petrogen.ai', 'PetrogenAI')
                        ->subject('Verify Your Email - PetrogenAI');
                }
            );
        } catch (\Exception $e) {
            // Log error but continue
            \Log::error('Failed to send OTP email: ' . $e->getMessage());
        }

        return redirect()->route('verify.show')->with([
            'email' => $validated['email'],
            'message' => 'Please check your email for the verification code.'
        ]);
    }

    public function showVerify()
    {
        if (!session('email')) {
            return redirect()->route('register');
        }
        return view('auth.verify');
    }

    public function verify(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
            'otp_code' => 'required|string|size:6',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('otp_code', $validated['otp_code'])
            ->where('otp_expires_at', '>', now())
            ->first();

        if (!$user) {
            return back()->withErrors([
                'otp_code' => 'Invalid or expired verification code.',
            ]);
        }

        // Mark email as verified
        $user->update([
            'email_verified' => true,
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);

        return redirect('/chat')->with('success', 'Email verified successfully!');
    }

    public function resendOtp(Request $request)
    {
        $validated = $request->validate([
            'email' => 'required|email',
        ]);

        $user = User::where('email', $validated['email'])
            ->where('email_verified', false)
            ->first();

        if (!$user) {
            return back()->withErrors([
                'email' => 'User not found or already verified.',
            ]);
        }

        // Generate new OTP
        $otpCode = sprintf('%06d', random_int(0, 999999));
        
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::raw(
                "Your PetrogenAI verification code is: {$otpCode}\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this code, please ignore this email.",
                function ($message) use ($validated) {
                    $message->to($validated['email'])
                        ->from('noreply@petrogen.ai', 'PetrogenAI')
                        ->subject('Verify Your Email - PetrogenAI');
                }
            );

            return back()->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send verification code. Please try again.',
            ]);
        }
    }

    public function showLoginVerify()
    {
        if (!session()->has('email')) {
            return redirect()->route('login');
        }

        return view('auth.login-verify');
    }

    public function verifyLogin(Request $request)
    {
        $validated = $request->validate([
            'otp_code' => 'required|string|size:6',
        ]);

        $email = session('email');
        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please login again.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return back()->withErrors([
                'otp_code' => 'Invalid session. Please login again.',
            ]);
        }

        // Verify OTP
        if ($user->otp_code !== $validated['otp_code']) {
            return back()->withErrors([
                'otp_code' => 'Invalid verification code.',
            ]);
        }

        // Check expiration
        if ($user->otp_expires_at < now()) {
            return back()->withErrors([
                'otp_code' => 'Verification code has expired. Please request a new one.',
            ]);
        }

        // Clear OTP and log in
        $user->update([
            'otp_code' => null,
            'otp_expires_at' => null,
        ]);

        Auth::login($user);
        $request->session()->forget('email');

        return redirect()->intended('/chat')->with('success', 'Login successful!');
    }

    public function resendLoginOtp(Request $request)
    {
        $email = session('email');
        
        if (!$email) {
            return redirect()->route('login')->withErrors([
                'email' => 'Session expired. Please login again.',
            ]);
        }

        $user = User::where('email', $email)->first();

        if (!$user) {
            return redirect()->route('login')->withErrors([
                'email' => 'User not found.',
            ]);
        }

        // Generate new OTP
        $otpCode = sprintf('%06d', random_int(0, 999999));
        
        $user->update([
            'otp_code' => $otpCode,
            'otp_expires_at' => now()->addMinutes(10),
        ]);

        // Send OTP email
        try {
            Mail::raw(
                "Your PetrogenAI login verification code is: {$otpCode}\n\nThis code will expire in 10 minutes.\n\nIf you didn't request this code, please secure your account immediately.",
                function ($message) use ($user) {
                    $message->to($user->email)
                        ->from('no-replay@petrogen.ai', 'PetrogenAI')
                        ->subject('Login Verification Code - PetrogenAI');
                }
            );

            return back()->with('success', 'A new verification code has been sent to your email.');
        } catch (\Exception $e) {
            return back()->withErrors([
                'email' => 'Failed to send verification code. Please try again.',
            ]);
        }
    }

    public function logout(Request $request)
    {
        Auth::logout();
        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return redirect('/login');
    }
}
