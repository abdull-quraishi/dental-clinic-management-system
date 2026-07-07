<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen px-4 py-10 bg-gradient-to-br from-sky-50 via-white to-blue-100">
        <div class="w-full max-w-md">
            <div class="overflow-hidden border shadow-2xl bg-white/90 backdrop-blur-md rounded-3xl border-white/60">

                <div class="p-4 pb-4 text-center text-white bg-gradient-to-r from-blue-700 to-cyan-600">
                    <!-- Hospital Icon -->
                    <div class="flex justify-center mb-4">
                        <div class="flex items-center justify-center w-20 h-20 bg-blue-700 rounded-full shadow-lg">
                           <img src="{{ asset('images/lock/lock3.png') }}" alt=""style=" width:80px; height:80px;">

                        </div>
                    </div>

                    <h2 class="text-3xl font-bold tracking-tight">
                        {{ __('messages.login_title') }}
                    </h2>
                    <p class="mt-2 text-sm text-blue-100">
                        {{ __('messages.login_description') }}
                    </p>
                </div>

                <div class="p-8">
                    <!-- Session Status -->
                    @if (session('status'))
                        <div class="p-3 mb-5 text-sm text-green-700 bg-green-100 border border-green-200 rounded-xl">
                            {{ session('status') }}
                        </div>
                    @endif

                    <form method="POST" action="{{ route('login') }}" class="space-y-4">
                        @csrf

                        <!-- Email -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">{{ __('messages.email') }}</label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Password -->
                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">{{ __('messages.password') }}</label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-3 bg-white border border-gray-300 rounded-xl focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-blue-500">

                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <!-- Forgot Password -->
                        <div class="text-right">
                            <a href="{{ route('password.request') }}"
                               class="text-sm font-medium text-blue-600 hover:text-blue-700 hover:underline">
                                {{ __('messages.forgot_password') }}
                            </a>
                        </div>

                        <!-- Login Button -->
                        <button type="submit"
                            class="w-full py-3 font-semibold text-white transition duration-300 shadow-lg bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl hover:from-blue-700 hover:to-cyan-700 shadow-blue-200">
                            {{ __('messages.login_now') }}
                        </button>

                        <!-- Register -->
                        <p class="pt-2 text-sm text-center text-gray-600">
                            {{ __('messages.dont_have_account') }}
                            <a href="{{ route('register') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                {{ __('messages.register_now') }}
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
