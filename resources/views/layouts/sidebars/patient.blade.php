<div class="p-3 pt-6 space-y-2">

       {{-- this session adminview patient appointment as admin role --}}
       @php
        $isAdminViewing = session()->has('admin_viewing_patient_id');
       @endphp

    <h2 class="mt-4 mb-4 text-lg font-bold text-center text-white pe-4">
        {{ __('messages.patient_panel') }}
    </h2>

    <a  href="{{ $isAdminViewing ? route('patient.dashboard') : route('patient.dashboard') }}"
       class="block px-4 py-3 rounded-lg hover:bg-gray-700">
       🏠 {{ __('messages.dashboard') }}
    </a>

   <a href="{{ route('patient.appointments.index') }}"class="block px-4 py-3 rounded-lg hover:bg-gray-700">
    📅 {{ __('messages.patient_appointments') }}
   </a>

   <a href="{{ route('patient.recentappointments') }}" class="block px-4 py-3 rounded-lg hover:bg-gray-700">
       🕒 {{ __('messages.patient_recent_appointments') }}
   </a>

    <a href="{{ $isAdminViewing ? route('patient.medical.records') : route('patient.medical.records') }}"
       class="block px-4 py-3 rounded-lg hover:bg-gray-700">
        🩺 {{ __('messages.patient_medical_records') }}
    </a>

    <a href="{{ $isAdminViewing ? route('patient.prescriptions') : route('patient.prescriptions') }}"
       class="block px-4 py-3 rounded-lg hover:bg-gray-700">
        💊 {{ __('messages.patient_prescriptions') }}
    </a>

    <a href="{{ $isAdminViewing ? route('patient.billings') : route('patient.billings') }}"
       class="block px-4 py-3 rounded-lg hover:bg-gray-700">
        💳 {{ __('messages.patient_billing') }}
    </a>

      {{-- this is for profile and login button when the used in mobile --}}
    <div class="pt-4 mt-4 border-t border-white/10 lg:hidden">
        <a href="{{ $isAdminViewing ? route('profile.show') : route('profile.show') }}" class="block px-3 py-2 rounded hover:bg-gray-700">
            👤 {{ __('messages.patient_profile')}}
        </a>

        <form method="POST" action="{{ route('logout') }}">
            @csrf
            <button type="submit" class="w-full px-3 py-2 text-left text-red-300 rounded hover:bg-red-500/20">
                🚪 {{ __('messages.patient_logout')}}
            </button>
        </form>
    </div>

</div>
