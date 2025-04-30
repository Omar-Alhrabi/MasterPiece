<x-guest-layout>
    <div class="text-center mb-8">
        <h1 class="text-3xl font-bold text-white">Register</h1>
        <p class="text-white text-opacity-80 mt-2">Create your account to get started</p>
    </div>

    <form method="POST" action="{{ route('register') }}">
        @csrf

        <!-- Name Fields -->
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mb-4">
            <!-- First Name -->
            <div>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <x-text-input id="first_name" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800" 
                        type="text" name="first_name" :value="old('first_name')" placeholder="First Name" required autofocus autocomplete="first_name" />
                </div>
                <x-input-error :messages="$errors->get('first_name')" class="mt-2 text-red-300" />
            </div>

            <!-- Last Name -->
            <div>
                <div class="relative flex items-center">
                    <span class="absolute left-4 text-blue-900">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                        </svg>
                    </span>
                    <x-text-input id="last_name" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800" 
                        type="text" name="last_name" :value="old('last_name')" placeholder="Last Name" required autocomplete="last_name" />
                </div>
                <x-input-error :messages="$errors->get('last_name')" class="mt-2 text-red-300" />
            </div>
        </div>

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
                    type="email" name="email" :value="old('email')" placeholder="Email Address" required autocomplete="username" />
            </div>
            <x-input-error :messages="$errors->get('email')" class="mt-2 text-red-300" />
        </div>

        <!-- Password -->
        <div class="mb-4">
            <div class="relative flex items-center">
                <span class="absolute left-4 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <x-text-input id="password" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800"
                    type="password"
                    name="password"
                    placeholder="Password"
                    required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password')" class="mt-2 text-red-300" />
        </div>

        <!-- Confirm Password -->
        <div class="mb-6">
            <div class="relative flex items-center">
                <span class="absolute left-4 text-blue-900">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 9V7a5 5 0 0110 0v2a2 2 0 012 2v5a2 2 0 01-2 2H5a2 2 0 01-2-2v-5a2 2 0 012-2zm8-2v2H7V7a3 3 0 016 0z" clip-rule="evenodd" />
                    </svg>
                </span>
                <x-text-input id="password_confirmation" class="pl-10 py-3 block w-full rounded-lg border-none shadow-sm text-gray-800"
                    type="password"
                    name="password_confirmation" 
                    placeholder="Confirm Password"
                    required autocomplete="new-password" />
            </div>
            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2 text-red-300" />
        </div>

        <!-- Register Button -->
        <div class="mt-6">
            <button type="submit" class="w-full py-3 bg-blue-500 hover:bg-blue-600 text-white font-medium rounded-lg transition duration-300 shadow-md">
                {{ __('Register') }}
            </button>
        </div>

        <!-- Google Register -->
        <div class="mt-4">
            <a href="{{ route('register') }}" class="flex items-center justify-center w-full py-3 bg-gray-800 hover:bg-gray-900 text-white font-medium rounded-lg transition duration-300 shadow-md">
                <span class="mr-2">G</span>
                Or register with Google
            </a>
        </div>

        <!-- Login Link -->
        <div class="mt-8 text-center text-white">
            <span class="text-white text-opacity-90">Already have an account?</span>
            <a href="{{ route('login') }}" class="text-white font-medium ml-1 hover:underline">
                {{ __('Login') }}
            </a>
        </div>
    </form>
</x-guest-layout>