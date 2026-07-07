<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ config('app.name', 'Laravel') }}</title>


    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>

<body class="font-sans bg-white text-slate-800">

    <!-- Navbar -->
    <header class="fixed top-0 left-0 right-0 z-50 border-b shadow-sm bg-white/90 backdrop-blur border-slate-200">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="flex items-center justify-between h-16">
                <a href="{{ url('/') }}" class="flex items-center gap-2">
                    <!-- Dental Clinic Logo -->
                <div class="shrink-0">
                    <div class="flex items-center justify-center shadow-lg w-11 h-11 " style="border-radius:50%;">
                        <!-- Dental Clinic Logo -->
                    <img src="{{ asset('images/clinic-logo/logo.png') }}" alt="Clinic Logo" style="border-radius:50%; width:70px;height:65px; object-fit:contain;  ">

                    </div>
                </div>
                    <div>
                        <div class="text-lg font-bold leading-tight text-blue-700">{{__('messages.welcome_clinic')}}</div>
                    </div>
                </a>

                <nav class="items-center hidden gap-2 md:flex">

                    <a href="{{ url('/') }}" class="px-4 py-2 transition rounded-xl hover:bg-blue-50 hover:text-blue-700">{{ __('messages.home') }}</a>
                    <a href="{{ url('/services') }}" class="px-4 py-2 transition rounded-xl hover:bg-blue-50 hover:text-blue-700">{{ __('messages.services') }}</a>
                    <a href="{{ url('/doctors') }}" class="px-4 py-2 transition rounded-xl hover:bg-blue-50 hover:text-blue-700">{{ __('messages.doctors') }}</a>
                    <a href="{{ url('/contact') }}" class="px-4 py-2 transition rounded-xl hover:bg-blue-50 hover:text-blue-700">{{ __('messages.contact') }}</a>

                    @auth
                        <a href="{{ route('dashboard') }}" class="px-5 py-2 ml-2 text-white transition bg-blue-600 shadow-md rounded-xl hover:bg-blue-700">
                            {{ __('messages.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="px-5 py-2 ml-2 text-white transition bg-blue-600 shadow-md rounded-xl hover:bg-blue-700">
                            {{ __('messages.login') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="px-5 py-2 transition border rounded-xl border-slate-300 hover:bg-slate-50">
                                {{ __('messages.register') }}
                            </a>
                        @endif
                    @endauth

                     <div class="relative">
                             <select onchange="window.location.href=this.value"
                                 class="px-3 py-2 border rounded-xl">

                                 <option value="">
                                     {{ __('messages.select_language') }}
                                 </option>

                                 <option value="{{ route('lang.switch', 'en') }}">
                                     English
                                 </option>

                                 <option value="{{ route('lang.switch', 'ps') }}">
                                     پښتو
                                 </option>

                                 <option value="{{ route('lang.switch', 'fa') }}">
                                     فارسی
                                 </option>

                             </select>
                         </div>

                </nav>

                <button id="menuBtn" class="flex items-center justify-center w-10 h-10 text-2xl bg-white border md:hidden rounded-xl border-slate-300">
                    ☰
                </button>
            </div>
        </div>

        <!-- Mobile Menu -->
        <div id="mobileMenu" class="hidden bg-white border-t md:hidden border-slate-200">
            <div class="px-4 py-4 space-y-2">
                <a href="{{ url('/') }}" class="block px-4 py-3 rounded-xl hover:bg-blue-50">{{ __('messages.home') }}</a>
                <a href="{{ url('/services') }}" class="block px-4 py-3 rounded-xl hover:bg-blue-50">{{ __('messages.services') }}</a>
                <a href="{{ url('/doctors') }}" class="block px-4 py-3 rounded-xl hover:bg-blue-50">{{ __('messages.doctors') }}</a>
                <a href="{{ url('/contact') }}" class="block px-4 py-3 rounded-xl hover:bg-blue-50">{{ __('messages.contact') }}</a>
                  <div class="relative">
                             <select onchange="window.location.href=this.value"
                                 class="px-3 py-2 border rounded-xl">

                                 <option value="">
                                     {{ __('messages.select_language') }}
                                 </option>

                                 <option value="{{ route('lang.switch', 'en') }}">
                                     English
                                 </option>

                                 <option value="{{ route('lang.switch', 'ps') }}">
                                     پښتو
                                 </option>

                                 <option value="{{ route('lang.switch', 'fa') }}">
                                     فارسی
                                 </option>

                             </select>
                         </div>

                <div class="pt-2 space-y-2">
                    @auth
                        <a href="{{ route('dashboard') }}" class="block px-4 py-3 text-center text-white bg-blue-600 rounded-xl">
                            {{ __('messages.dashboard') }}
                        </a>
                    @else
                        <a href="{{ route('login') }}" class="block px-4 py-3 text-center text-white bg-blue-600 rounded-xl">
                            {{ __('messages.login') }}
                        </a>
                        @if (Route::has('register'))
                            <a href="{{ route('register') }}" class="block px-4 py-3 text-center border rounded-xl border-slate-300">
                                {{ __('messages.register') }}
                            </a>
                        @endif
                    @endauth
                </div>


            </div>
        </div>
    </header>

    <main class="pt-16">
        @yield('content')
    </main>

    <footer class="border-t border-slate-200 bg-slate-50">
        <div class="px-4 py-6 mx-auto text-sm text-center max-w-7xl text-slate-500">
            {{ __('messages.copyright') }}
        </div>
    </footer>

    <script>
        document.addEventListener('DOMContentLoaded', function () {
            const menuBtn = document.getElementById('menuBtn');
            const mobileMenu = document.getElementById('mobileMenu');

            if (menuBtn && mobileMenu) {
                menuBtn.addEventListener('click', function () {
                    mobileMenu.classList.toggle('hidden');
                });
            }

            const slides = document.querySelectorAll('.slide');
            let current = 0;

            function showSlide(index) {
                slides.forEach((slide, i) => {
                    slide.classList.toggle('opacity-0', i !== index);
                    slide.classList.toggle('opacity-100', i === index);
                });
            }

            if (slides.length > 0) {
                showSlide(0);

                const nextBtn = document.getElementById('next');
                const prevBtn = document.getElementById('prev');

                if (nextBtn) {
                    nextBtn.addEventListener('click', () => {
                        current = (current + 1) % slides.length;
                        showSlide(current);
                    });
                }

                if (prevBtn) {
                    prevBtn.addEventListener('click', () => {
                        current = (current - 1 + slides.length) % slides.length;
                        showSlide(current);
                    });
                }

                setInterval(() => {
                    current = (current + 1) % slides.length;
                    showSlide(current);
                }, 4000);
            }
        });
    </script>

</body>
</html>
