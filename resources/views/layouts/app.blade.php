<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>{{ config('app.name', 'Laravel') }}</title>

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <style>
        [x-cloak] { display: none !important; }
    </style>
</head>

<body x-data="{ sidebarOpen: false }" class="overflow-hidden font-sans antialiased bg-gray-100">
    <div class="h-screen overflow-hidden bg-gray-100">
        @include('layouts.navigation')

        @isset($header)
            <header class="bg-white shadow">
                <div class="px-4 py-6 mx-auto max-w-7xl sm:px-6 lg:px-8">
                    {{ $header }}
                </div>
            </header>
        @endisset

        <div class="flex pt-16 h-[calc(100vh-4rem)] overflow-hidden">

            <!-- Mobile overlay -->
            <div
                x-cloak
                x-show="sidebarOpen"
                @click="sidebarOpen = false"
                class="fixed inset-0 z-30 bg-black/40 lg:hidden">
            </div>

            <!-- Sidebar -->
            <div
                x-cloak
                :class="sidebarOpen ? 'translate-x-0' : '-translate-x-full'"
                class="fixed top-16 left-0 z-40 w-64 h-[calc(100vh-4rem)] overflow-y-auto text-white bg-gray-800 shadow-xl transform transition-transform duration-300 lg:translate-x-0 lg:static lg:block">

                <div class="p-2">
              @php
                $isAdminViewingPatient = auth()->check()
                    && (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin'))
                    && session()->has('admin_viewing_patient_id');

                $isAdmin = auth()->check()
                    && (auth()->user()->hasRole('super_admin') || auth()->user()->hasRole('admin'));

                $isDoctor = auth()->check() && (
                    auth()->user()->hasRole('general_doctor') ||
                    auth()->user()->hasRole('filler_specialist_doctor') ||
                    auth()->user()->hasRole('extractor_specialist_doctor') ||
                    auth()->user()->hasRole('cleaner_specialist_doctor') ||
                    auth()->user()->hasRole('root_canal_specialist_doctor')
                );

                $isPatient = auth()->check() && auth()->user()->hasRole('patient');
                @endphp

                @if($isAdminViewingPatient)
                    @include('layouts.sidebars.patient')
                @elseif($isAdmin)
                    @include('layouts.sidebars.admin')
                @elseif($isDoctor)
                    @include('layouts.sidebars.doctor')
                @elseif($isPatient)
                    @include('layouts.sidebars.patient')
                @endif
                </div>
            </div>

    {{-- this code is for dynamic messages shows when something add,update and delete in every page --}}
      @if(session('success'))
        <div id="success-alert"
        class="fixed z-50 px-6 py-3 text-white bg-green-600 rounded shadow-lg top-5 right-5">
            {{ session('success') }}
        </div>
        @endif

        @if(session('error'))
        <div id="error-alert"
             class="fixed z-50 px-6 py-3 text-white bg-red-600 rounded shadow-lg top-5 right-5">
            {{ session('error') }}
        </div>
        @endif

            <!-- Main Area -->
            <main class="flex-1 w-full min-w-0 p-4 pt-0 overflow-x-hidden sm:p-6 lg:ml-64">
                @yield('content')
            </main>
        </div>
    </div>

    {{-- this js code is for set time for dynamic messages shows when something add,update and delete in every page --}}
    <script>

  setTimeout(() => {

    const alerts = document.querySelectorAll('#success-alert,#error-alert');

    alerts.forEach(alert => {

        alert.style.transition = "opacity 1s";

        alert.style.opacity = "0";

        setTimeout(() => {
            alert.remove();
        }, 1000);

    });

}, 5000);

</script>
</body>
</html>
