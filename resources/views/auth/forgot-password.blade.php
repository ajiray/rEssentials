<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.2.0/css/all.css">
    <title>{{ config('app.name', 'Laravel') }}</title>
    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<div class="w-full h-full flex justify-center items-center">
    <div class="max-w-md mx-auto py-8 px-4 bg-white shadow-lg rounded-lg relative">
        <div class="flex items-center justify-center lg:mt-10">
            <a href="/login"><i class="fa-solid fa-arrow-left text-xl absolute left-3 top-3 cursor-pointer"></i></a>
        </div>

        <h2 class="text-2xl font-semibold text-center mb-6">{{ __('Forgot Your Password?') }}</h2>

        <!-- Description -->
        <div class="mb-6 text-sm text-gray-600">
            {{ __('No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
        </div>

        <!-- Session Status -->
        <x-auth-session-status class="mb-4" :status="session('status')" />

        <!-- Password Reset Form -->
        <form method="POST" action="{{ route('password.email') }}">
            @csrf


            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Submit Button -->
            <div class="flex items-center justify-center">
                <x-primary-button>
                    {{ __('Email Password Reset Link') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</div>
