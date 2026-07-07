<nav class="fixed top-0 left-0 right-0 z-50 border-b shadow-sm border-cyan-100 bg-white/90 backdrop-blur-lg">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between h-16">

            <!-- LEFT SIDE -->
            <div class="flex items-center gap-4 sm:gap-10">

                <!-- Dental Clinic Logo -->
                <div class="shrink-0">
                    <div class="flex items-center justify-center shadow-lg w-11 h-11 " style="border-radius:50%;">
                        <!-- Dental Clinic Logo -->
                    <img src="{{ asset('images/clinic-logo/logo.png') }}" alt="Clinic Logo" style="border-radius:50%; width:70px;height:65px; object-fit:contain;  ">

                    </div>
                </div>

                <div class="gap-1 sm:flex sm:items-center">
                    <h2 class="text-2xl font-extrabold leading-tight text-blue-700">{{ __('messages.dental_clinic') }}</h2>
                    <h3 class="-mt-1 text-2xl font-medium text-blue-600">{{ __('messages.management_system') }}</h3>
                </div>
            </div>

            <!-- RIGHT SIDE -->
            <div class="items-center hidden gap-4 sm:flex">
                <div class="text-right">
                    <h4 class="text-sm font-bold text-gray-800">{{ Auth::user()->name }}</h4>
                    <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                </div>

                <x-dropdown align="right" width="56">
                    <x-slot name="trigger">
                        <button class="flex items-center gap-3 px-3 py-2 transition border border-gray-200 bg-gray-50 rounded-2xl hover:bg-cyan-50">
                            @php
                                $avatar = Auth::user()->avatar
                                    ? asset('storage/' . Auth::user()->avatar)
                                    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&background=0D8ABC&color=fff';
                            @endphp

                            <img src="{{ $avatar }}" class="object-cover w-10 h-10 border-2 rounded-xl border-cyan-200">

                            <svg class="w-4 h-4 text-gray-500"
                                 xmlns="http://www.w3.org/2000/svg"
                                 viewBox="0 0 20 20"
                                 fill="currentColor">
                                <path fill-rule="evenodd"
                                      d="M5.23 7.21a.75.75 0 011.06.02L10 11.168l3.71-3.938a.75.75 0 111.08 1.04l-4.25 4.5a.75.75 0 01-1.08 0l-4.25-4.5a.75.75 0 01.02-1.06z"
                                      clip-rule="evenodd" />
                            </svg>
                        </button>
                    </x-slot>

                    <x-slot name="content">
                        <div class="px-4 py-3 border-b">
                            <p class="font-semibold text-gray-800">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">{{ Auth::user()->email }}</p>
                        </div>

                        <x-dropdown-link :href="route('profile.show')">
                            👤 {{ __('messages.profile') }}
                        </x-dropdown-link>

                        <form method="POST" action="{{ route('logout') }}">
                            @csrf
                            <x-dropdown-link :href="route('logout')"
                                onclick="event.preventDefault(); this.closest('form').submit();">
                                🚪 {{ __('messages.logout') }}
                            </x-dropdown-link>
                        </form>
                    </x-slot>
                </x-dropdown>
            </div>

            <!-- Mobile Menu Button -->
            <div class="flex items-center gap-3 sm:hidden">

                <button
                    @click="sidebarOpen = !sidebarOpen"
                    class="inline-flex items-center justify-center p-2 text-gray-600 transition rounded-xl hover:bg-cyan-50">
                    <svg class="w-7 h-7" stroke="currentColor" fill="none" viewBox="0 0 24 24">
                        <path :class="{ 'hidden': sidebarOpen, 'inline-flex': !sidebarOpen }"
                              class="inline-flex"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M4 6h16M4 12h16M4 18h16" />
                        <path :class="{ 'hidden': !sidebarOpen, 'inline-flex': sidebarOpen }"
                              class="hidden"
                              stroke-linecap="round"
                              stroke-linejoin="round"
                              stroke-width="2"
                              d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>

        </div>
    </div>
</nav>
