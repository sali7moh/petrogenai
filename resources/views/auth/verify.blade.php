@extends('layouts.app')

@section('title', 'Verify Email - PetrogenAI')

@section('content')
<div class="min-h-screen flex items-center justify-center px-4 sm:px-6 lg:px-8 bg-gradient-to-br from-blue-50 to-indigo-100">
    <div class="max-w-md w-full space-y-8">
        <!-- Logo and Title -->
        <div class="text-center">
            <div class="flex justify-center">
                <img src="{{ asset('logo.svg') }}" alt="PetrogenAI" class="h-20 w-auto">
            </div>
            <h2 class="mt-6 text-3xl font-bold text-gray-900">Verify Your Email</h2>
            <p class="mt-2 text-sm text-gray-600">We've sent a 6-digit code to</p>
            <p class="mt-1 text-sm font-medium text-blue-600">{{ session('email') }}</p>
        </div>

        <!-- Verification Form -->
        <div class="bg-white rounded-2xl shadow-xl p-8">
            @if(session('success'))
                <div class="mb-4 p-3 bg-green-50 border border-green-200 text-green-700 rounded-lg text-sm">
                    {{ session('success') }}
                </div>
            @endif

            <form method="POST" action="{{ route('verify') }}" class="space-y-6">
                @csrf

                <!-- OTP Field -->
                <div>
                    <label for="otp_code" class="block text-sm font-medium text-gray-700 mb-2">Verification Code</label>
                    <input id="otp_code" name="otp_code" type="text" required autofocus maxlength="6"
                           pattern="[0-9]{6}"
                           class="appearance-none block w-full px-4 py-3 border border-gray-300 rounded-lg placeholder-gray-400 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition duration-150 text-center text-2xl font-mono tracking-widest"
                           placeholder="000000">
                    @error('otp_code')
                        <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                    <p class="mt-1 text-xs text-gray-500">Enter the 6-digit code sent to your email</p>
                </div>

                <!-- Submit Button -->
                <button type="submit"
                        class="w-full flex justify-center py-3 px-4 border border-transparent rounded-lg shadow-sm text-sm font-medium text-white bg-gradient-to-r from-blue-600 to-indigo-700 hover:from-blue-700 hover:to-indigo-800 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition duration-150">
                    Verify Email
                </button>
            </form>

            <!-- Resend OTP -->
            <div class="mt-6 text-center">
                <form method="POST" action="{{ route('verify.resend') }}" class="inline">
                    @csrf
                    <p class="text-sm text-gray-600">
                        Didn't receive the code?
                        <button type="submit" class="font-medium text-blue-600 hover:text-blue-500">
                            Resend Code
                        </button>
                    </p>
                </form>
            </div>

            <!-- Back to Register -->
            <div class="mt-4 text-center">
                <p class="text-sm text-gray-600">
                    <a href="{{ route('register') }}" class="font-medium text-gray-700 hover:text-gray-900">
                        &larr; Back to Registration
                    </a>
                </p>
            </div>
        </div>

        <!-- Footer -->
        <p class="text-center text-xs text-gray-500">
            &copy; {{ date('Y') }} Petrogen. All rights reserved.
        </p>
    </div>
</div>
@endsection
