<div class="pt-4 text-sm sm:text-base">


    <h2 class="mt-4 mb-4 text-base font-bold text-center sm:text-xl pe-4">
         {{ __('messages.admin_panel') }}
    </h2>


    <a href="{{ route('admin.dashboard') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.dashboard') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        📊 {{ __('messages.dashboard') }}
    </a>

    <a href="{{ route('admin.users') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        👤 {{ __('messages.manage_users') }}
    </a>

    <a href="{{ route('admin.doctors.index') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.doctors.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        👨‍⚕️ {{ __('messages.manage_doctors') }}
    </a>

    <a href="{{ route('admin.patients.index') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.patients.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        🧑‍🤝‍🧑 {{ __('messages.manage_patients') }}
    </a>

    <a href="{{ route('admin.prescriptions-bills',['type'=>'today']) }}"
       class="flex items-center justify-between gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.prescriptions-bills') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        <span>💊{{ __('messages.todays_prescrips_and_Bills') }}</span>
        <span class="px-1 text-sm bg-green-500 rounded">
            {{ \App\Models\Prescription::whereDate('prescription_date', now()->toDateString())->count() }}
        </span>
    </a>

    <a href="{{ route('admin.prescriptions-bills',['type'=>'new']) }}"
       class="flex items-center justify-between gap-2 px-3 py-2 rounded hover:bg-gray-700">
        <span>🆕{{ __('messages.new_prescrips_and_Bills') }}</span>
        <span class="px-1 text-sm bg-red-500 rounded">
            {{ \App\Models\Prescription::where('status','pending')->count() }}
        </span>
    </a>

     <a href="{{ route('admin.todayappointment') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.todayappointment') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        🕒 {{ __('messages.Todays_Appointments') }}
    </a>

    <a href="{{ route('admin.pending-appointments') }}"
       class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.pendingappointments') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
        👥{{ __('messages.pending_appointment') }}
    </a>

    <a href="{{ route('admin.doctor.reports') }}"
     class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.doctor.reports*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
      📋 {{ __('messages.doctor_daily_report') }}
    </a>

    <a href="{{ route('admin.reports.daily') }}"
       class="flex items-center gap-2 px-3 py-2 rounded hover:bg-gray-700">
        📈 {{ __('messages.system_reports') }}
   </a>

   <a href="{{ route('admin.services.index') }}"
    class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.services.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
     🛠 {{ __('messages.services') }}
   </a>

   <a href="{{ route('admin.medicines.index') }}"
      class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.medicines.*') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
       💊 {{ __('messages.medicine') }}
   </a>

   <a href="{{ route('admin.contact.messages') }}"
          class="flex items-center gap-2 px-3 py-2 rounded {{ request()->routeIs('admin.contact.messages') ? 'bg-gray-700' : 'hover:bg-gray-700' }}">
    📞 {{ __('messages.contact_messages') }}
   </a>


      {{-- this is for profile and login button when the used in mobile --}}
    <div class="pt-4 mt-4 border-t border-white/10 lg:hidden">
        <a href="{{ route('profile.show') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
            👤 {{ __('messages.profile') }}
        </a>

        <form method="POST" action="{{ route('logout') }}">
          @csrf
        <button type="submit" class="w-full px-3 py-2 text-left text-red-300 rounded hover:bg-red-500/20">
             🚪 {{ __('messages.logout') }}
        </button>
        </form>
    </div>
</div>
