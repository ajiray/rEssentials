<x-guest-layout>
    <div
        class="flex flex-col lg:flex-row justify-center items-center shadow-lg h-auto w-full lg:w-[90%] lg:max-w-6xl mx-auto relative bg-gold">
        <!-- Sign Up Form -->
        <form method="POST" action="{{ route('register') }}" class="w-full lg:w-[50%] h-full bg-white space-y-4 p-8">
            @csrf
            <div class="flex items-center justify-center">
                <a href="/"><i
                        class="fa-solid fa-arrow-left text-xl absolute top-14 left-6 lg:left-3 lg:top-5 cursor-pointer"></i></a>
                <h2 class="text-4xl font-bold mb-6">{{ __('Sign Up') }}</h2>
            </div>

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block mt-1 w-full" type="text" name="name" :value="old('name')"
                    required autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" class="block mt-1 w-full" type="tel" name="phone_number"
                    :value="old('phone_number')" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Address Section -->
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                <!-- Street Address -->
                <div>
                    <x-input-label for="street_address" :value="__('Street Address')" />
                    <x-text-input id="street_address" class="block mt-1 w-full" type="text" name="street_address"
                        :value="old('street_address')" required />
                    <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
                </div>

                <!-- City -->
                <div>
                    <x-input-label for="city" :value="__('City')" />
                    <x-text-input id="city" class="block mt-1 w-full" type="text" name="city"
                        :value="old('city')" required />
                    <x-input-error :messages="$errors->get('city')" class="mt-2" />
                </div>

                <!-- Postal Code -->
                <div>
                    <x-input-label for="postal_code" :value="__('Postal Code')" />
                    <x-text-input id="postal_code" class="block mt-1 w-full" type="text" name="postal_code"
                        :value="old('postal_code')" required />
                    <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
                </div>

                <!-- Province -->
                <div>
                    <x-input-label for="province" :value="__('Province')" />
                    <x-text-input id="province" class="block mt-1 w-full" type="text" name="province"
                        :value="old('province')" required />
                    <x-input-error :messages="$errors->get('province')" class="mt-2" />
                </div>
            </div>

            <!-- Password Section -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block mt-1 w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block mt-1 w-full" type="password"
                    name="password_confirmation" required />
            </div>

            <div class="flex w-full justify-between items-center">
                <!-- Register Button -->
                <div class="flex items-center justify-center">
                    <x-primary-button>
                        {{ __('Register') }}
                    </x-primary-button>
                </div>

                <!-- Already registered? -->
                <div class="text-center">
                    <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                        {{ __('Already registered?') }}
                    </a>
                </div>
            </div>

        </form>

        <!-- Logo Section -->
        <div class="w-full lg:w-[50%] h-full hidden lg:flex justify-center items-center">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="w-full h-auto">
        </div>
    </div>
</x-guest-layout>
