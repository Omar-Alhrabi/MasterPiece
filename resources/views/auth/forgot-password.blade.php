<!-- <x-guest-layout>
    <div class="mb-4 text-sm text-gray-600">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" class="block mt-1 w-full" type="email" name="email" :value="old('email')" required autofocus />
            <x-input-error :messages="$errors->get('email')" class="mt-2" />
        </div>

        <div class="flex items-center justify-end mt-4">
            <x-primary-button>
                {{ __('Email Password Reset Link') }}
            </x-primary-button>
        </div>
    </form>
</x-guest-layout>
 -->
 <x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Forgot Password</h1>
        <p class="text-white text-opacity-80 mt-2">Please enter your email to reset your password</p>
    </div>

    <!-- Session Status -->
    <div class="mb-4 text-sm text-white text-opacity-90">
        {{ __('Forgot your password? No problem. Just let us know your email address and we will email you a password reset link that will allow you to choose a new one.') }}
    </div>

    <!-- Session Status -->
    <x-auth-session-status class="mb-4" :status="session('status')" />

    <form method="POST" action="{{ route('password.email') }}">
        @csrf

        <!-- Email Address -->
        <div class="mb-4">
            <div class="relative flex items-center">
                <span class="absolute left-4 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z" />
                        <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z" />
                    </svg>
                </span>
                <x-text-input id="email" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800" 
                    type="email" name="email" :value="old('email')" placeholder="Email Address" required autofocus />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <div class="mt-8">
            <button type="submit" class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition duration-300 shadow-md">
                {{ __('Email Password Reset Link') }}
            </button>
        </div>
        
        <div class="mt-8 text-center">
            <a href="{{ route('login') }}" class="text-white font-medium hover:underline">
                {{ __('Back to Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>