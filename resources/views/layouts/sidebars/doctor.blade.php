<div class="h-full p-2 pt-4 space-y-2 text-sm sm:text-base">

    {{-- Doctor Panel --}}

        <h2 class="mt-4 mb-6 text-base font-bold text-center sm:text-xl pe-4">
            {{ __('messages.doctor_dashboard_title') }}
        </h2>

        <a href="{{ route('doctor.dashboard') }}"class="flex items-center justify-between px-3 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
         <span>📊 {{ __('messages.doctor_dashboard') }}</span>
         @if(isset($notificationCount) && $notificationCount > 0)
             <span class="px-2 py-1 text-xs bg-red-500 rounded-full">
                 {{ $notificationCount }}
             </span>
         @endif
        </a>

        <a href="{{ route('doctor.todays-app') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            ⏰ {{ __('messages.todays_app') }}
        </a>

        <a href="{{ route('doctor.pending-app') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            ⏳ {{ __('messages.pending_app') }}
        </a>

        <a href="{{ route('doctor.patients') }}"class="block px-3 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            👥 {{ __('messages.total_patients') }}
        </a>

        <a href="{{ route('doctor.latest-patients') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            👥 {{ __('messages.latest_patients') }}
        </a>

         <a href="{{ route('doctor.all-bills') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            💳 {{ __('messages.all_doctor_bills') }}
        </a>
         <a href="{{ route('doctor.today.bills') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
            ⚱ {{ __('messages.today_bills') }}
        </a>

    {{-- this is for profile and login button when the used in mobile --}}
    <div class="pt-4 mt-4 border-t border-white/10 lg:hidden">
        <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
            👤 {{ __('messages.profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-3 py-2 text-left text-red-300 rounded hover:bg-red-500/20">
                🚪 {{ __('messages.doctor_logout') }}
            </button>
        </form>
    </div>
</div>
