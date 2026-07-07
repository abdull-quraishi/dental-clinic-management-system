<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen px-4 py-6 bg-gradient-to-br from-sky-50 via-white to-blue-100">
        <div class="w-full max-w-md">
            <div class="overflow-hidden border shadow-2xl border-white/60 rounded-3xl bg-white/90 backdrop-blur-md">

                <div class="p-4 pb-4 text-center text-white bg-gradient-to-r from-blue-700 to-cyan-600">
                    <div class="flex justify-center mb-4">
                           <img src="{{ asset('images/lock/registration1.png') }}" alt=""style=" width:90px; height:90px;">
                    </div>

                    <h2 class="text-3xl font-bold tracking-tight">
                        {{ __('messages.register_title') }}
                    </h2>
                    <p class="mt-2 text-sm text-blue-100">
                        {{ __('messages.register_description') }}
                    </p>
                </div>

                <div class="p-6">
                    <form method="POST" action="{{ route('register') }}" class="space-y-3">
                        @csrf

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.name') }}
                            </label>
                            <input type="text" name="name" value="{{ old('name') }}" required
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('name')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.email') }}
                            </label>
                            <input type="email" name="email" value="{{ old('email') }}" required
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('email')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.password') }}
                            </label>
                            <input type="password" name="password" required
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                            @error('password')
                                <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        <div>
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.confirm_password') }}
                            </label>
                            <input type="password" name="password_confirmation" required
                                class="w-full px-4 py-2 bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:outline-none focus:ring-2 focus:ring-blue-500">
                        </div>

                        <button type="submit"
                            class="w-full py-2 font-semibold text-white transition duration-300 shadow-lg bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl hover:from-blue-700 hover:to-cyan-700 shadow-blue-200">
                            {{ __('messages.register') }}
                        </button>

                        <p class="text-sm text-center text-gray-600 ">
                            {{ __('messages.already_have_account') }}
                            <a href="{{ route('login') }}" class="font-semibold text-blue-600 hover:text-blue-700 hover:underline">
                                {{ __('messages.login_now') }}
                            </a>
                        </p>
                    </form>
                </div>
            </div>
        </div>
    </div>
</x-guest-layout>
