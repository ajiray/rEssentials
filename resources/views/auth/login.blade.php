<style>
    /* Add this CSS to your stylesheet */
    .alert {
        transition: opacity 1s ease;
    }

    .fade-out {
        opacity: 0;
    }
</style>
<x-guest-layout>
    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />
    @if (Session::has('success'))
        <div id="alertMessage" role="alert" class="alert alert-success w-[50%] mx-auto absolute top-28">
            <svg xmlns="http://www.w3.org/2000/svg" class="stroke-current shrink-0 h-6 w-6" fill="none"
                viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            {{ Session::get('success') }}
        </div>
    @endif

    <div
        class="flex flex-col lg:flex-row justify-center items-center h-full w-full bg-blanc md:px-20 lg:px-40 xl:px-60">
        <!-- Login Form -->
        <form method="POST" action="{{ route('login') }}"
            class="w-full lg:w-[50%] lg:h-[510px] bg-white rounded-lg lg:rounded-none lg:rounded-tl-lg lg:rounded-bl-lg p-8 shadow-md relative">
            @csrf

            <div class="flex items-center justify-center lg:mt-20">
                <a href="/"><i
                        class="fa-solid fa-arrow-left text-xl absolute left-3 top-3 cursor-pointer"></i></a>
                <h2 class="text-4xl font-bold mb-6">{{ __('Log in') }}</h2>
            </div>



            <!-- Email Address -->
            <div class="mb-4">
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autofocus autocomplete="username" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Password -->
            <div class="mb-4" <x-input-label for="password" :value="__('Password')" />
            <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                autocomplete="current-password" />
            <x-input-error :messages="$errors->get('password')" class="mt-2" />
    </div>

    <div class="flex flex-col lg:flex-row items-center justify-between">
        <button
            class="w-full lg:w-auto bg-velvet text-white px-6 py-3 lg:py-1 lg:px-8 tracking-wide rounded-md hover:bg-slate-900 focus:outline-none focus:ring-2 focus:ring-slate-900">
            LOG IN
        </button>


        @if (Route::has('password.request'))
            <a class="text-sm text-gray-600 hover:text-gray-900 mt-2 lg:mt-0" href="{{ route('password.request') }}">
                {{ __('Forgot your password?') }}
            </a>
        @endif
    </div>
    </form>

    <!-- Logo Section -->
    <div
        class="lg:w-[50%] lg:h-[500px] hidden lg:flex justify-center items-center bg-gold rounded-tr-lg rounded-br-lg p-8 shadow-md">
        <div class="mx-auto max-w-lg">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-auto">
        </div>
    </div>
    </div>



    <script>
        // Add this script after the HTML content
        setTimeout(function() {
            const alertMessage = document.getElementById('alertMessage');
            alertMessage.classList.add('fade-out');
            // Wait for the transition to complete and then remove the alert message from the DOM
            setTimeout(function() {
                alertMessage.remove();
            }, 1000);
        }, 8000);
    </script>

</x-guest-layout>
