<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen px-4 py-10 bg-gradient-to-br from-sky-50 via-white to-blue-100">
        <div class="w-full max-w-md">
            <div class="overflow-hidden border shadow-2xl border-white/60 rounded-3xl bg-white/90 backdrop-blur-md">

                <!-- Header -->
                <div class="p-4 pb-4 text-center text-white bg-gradient-to-r from-blue-700 to-cyan-600">

                    <!-- Hospital Icon -->
                    <div class="flex justify-center mb-2">
                        <div class="flex items-center justify-center w-20 h-20 bg-white rounded-full shadow-lg">
                            <svg width="44" height="44" viewBox="0 0 24 24" fill="none"
                                xmlns="http://www.w3.org/2000/svg">
                                <path
                                    d="M12 2L3 6.5V12.5C3 17.75 6.64 22.68 12 23.95C17.36 22.68 21 17.75 21 12.5V6.5L12 2Z"
                                    fill="#2563EB" />
                                <path
                                    d="M12 6.2C12.55 6.2 13 6.65 13 7.2V10H15.8C16.35 10 16.8 10.45 16.8 11V13C16.8 13.55 16.35 14 15.8 14H13V16.8C13 17.35 12.55 17.8 12 17.8H10C9.45 17.8 9 17.35 9 16.8V14H6.2C5.65 14 5.2 13.55 5.2 13V11C5.2 10.45 5.65 10 6.2 10H9V7.2C9 6.65 9.45 6.2 10 6.2H12Z"
                                    fill="white" />
                            </svg>
                        </div>
                    </div>

                    <h2 class="text-xl font-bold tracking-tight">
                        {{ __('messages.reset_password_title') }}
                    </h2>

                    <p class="mt-1 text-sm text-blue-100">
                        {{ __('messages.reset_password_description') }}
                    </p>
                </div>

                <!-- Body -->
                <div class="p-6">

                    <form method="POST" action="{{ route('password.store') }}" class="space-y-3">
                        @csrf

                        <!-- Password Reset Token -->
                        <input type="hidden" name="token" value="{{ $request->route('token') }}">

                        <!-- Email -->
                        <div>
                            <label for="email"
                                class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.email') }}
                            </label>

                            <input
                                id="email"
                                type="email"
                                name="email"
                                value="{{ old('email', $request->email) }}"
                                required
                                autofocus
                                autocomplete="username"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                            <x-input-error :messages="$errors->get('email')" class="mt-2" />
                        </div>

                        <!-- Password -->
                        <div>
                            <label for="password"
                                class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.password') }}
                            </label>

                            <input
                                id="password"
                                type="password"
                                name="password"
                                required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                            <x-input-error :messages="$errors->get('password')" class="mt-2" />
                        </div>

                        <!-- Confirm Password -->
                        <div>
                            <label for="password_confirmation"
                                class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.confirm_password') }}
                            </label>

                            <input
                                id="password_confirmation"
                                type="password"
                                name="password_confirmation"
                                required
                                autocomplete="new-password"
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500"
                            >

                            <x-input-error :messages="$errors->get('password_confirmation')" class="mt-2" />
                        </div>

                        <!-- Submit Button -->
                        <button type="submit"
                            class="w-full py-3 font-semibold text-white transition duration-300 shadow-lg bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl hover:from-blue-700 hover:to-cyan-700 shadow-blue-200">
                            {{ __('messages.reset_password_button') }}
                        </button>

                        <!-- Back Login -->
                        <p class="pt-2 text-sm text-center text-gray-600">
                            {{ __('messages.remember_password') }}
                             <a href="{{ route('login') }}"
                                class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                {{ __('messages.login_now') }}
                            </a>
                        </p>

                    </form>

                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
