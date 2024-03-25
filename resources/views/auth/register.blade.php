<x-guest-layout>
    <div class="max-w-md mx-auto py-6 px-4">
        <h2 class="text-2xl font-semibold text-center mb-6">{{ __('Sign Up') }}</h2>
        <form method="POST" action="{{ route('register') }}" class="space-y-4">
            @csrf

            <!-- Name -->
            <div>
                <x-input-label for="name" :value="__('Name')" />
                <x-text-input id="name" class="block w-full" type="text" name="name" :value="old('name')" required
                    autofocus autocomplete="name" />
                <x-input-error :messages="$errors->get('name')" class="mt-2" />
            </div>

            <!-- Email Address -->
            <div>
                <x-input-label for="email" :value="__('Email')" />
                <x-text-input id="email" class="block w-full" type="email" name="email" :value="old('email')"
                    required autocomplete="email" />
                <x-input-error :messages="$errors->get('email')" class="mt-2" />
            </div>

            <!-- Phone Number -->
            <div>
                <x-input-label for="phone_number" :value="__('Phone Number')" />
                <x-text-input id="phone_number" class="block w-full" type="tel" name="phone_number"
                    :value="old('phone_number')" required />
                <x-input-error :messages="$errors->get('phone_number')" class="mt-2" />
            </div>

            <!-- Street Address -->
            <div>
                <x-input-label for="street_address" :value="__('Street Address')" />
                <x-text-input id="street_address" class="block w-full" type="text" name="street_address"
                    :value="old('street_address')" required />
                <x-input-error :messages="$errors->get('street_address')" class="mt-2" />
            </div>

            <!-- City -->
            <div>
                <x-input-label for="city" :value="__('City')" />
                <x-text-input id="city" class="block w-full" type="text" name="city" :value="old('city')"
                    required />
                <x-input-error :messages="$errors->get('city')" class="mt-2" />
            </div>

            <!-- Postal Code -->
            <div>
                <x-input-label for="postal_code" :value="__('Postal Code')" />
                <x-text-input id="postal_code" class="block w-full" type="text" name="postal_code" :value="old('postal_code')"
                    required />
                <x-input-error :messages="$errors->get('postal_code')" class="mt-2" />
            </div>

            <!-- Province -->
            <div>
                <x-input-label for="province" :value="__('Province')" />
                <x-text-input id="province" class="block w-full" type="text" name="province" :value="old('province')"
                    required />
                <x-input-error :messages="$errors->get('province')" class="mt-2" />
            </div>

            <!-- Password -->
            <div>
                <x-input-label for="password" :value="__('Password')" />
                <x-text-input id="password" class="block w-full" type="password" name="password" required
                    autocomplete="new-password" />
                <x-input-error :messages="$errors->get('password')" class="mt-2" />
            </div>

            <!-- Confirm Password -->
            <div>
                <x-input-label for="password_confirmation" :value="__('Confirm Password')" />
                <x-text-input id="password_confirmation" class="block w-full" type="password"
                    name="password_confirmation" required />
            </div>

            <div class="flex items-center justify-between mt-4">
                <a class="text-sm text-gray-600 hover:text-gray-900" href="{{ route('login') }}">
                    {{ __('Already registered?') }}
                </a>
                <x-primary-button>
                    {{ __('Register') }}
                </x-primary-button>
            </div>
        </form>
    </div>
</x-guest-layout>
