{{-- resources/views/doctor/pending-app.blade.php --}}
@extends('layouts.app')

@section('content')
<div class="p-5 bg-white shadow rounded-xl">

    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('messages.pending_appointments_title') }}
        </h2>
        <a href="{{ route('doctor.dashboard') }}"
           class="px-4 py-2 text-sm text-white bg-blue-600 rounded">{{ __('messages.back') }}</a>
    </div>

    @if($pendingAppointments->isEmpty())
        <p class="text-gray-500">
            {{ __('messages.no_pending_appointments') }}
        </p>
    @else

   {{-- this  code for declaring variable if doctor is general the service type should show if not not show--}}
     @php
      $showServiceType = auth()->user()->hasRole('general_doctor');
     @endphp
    <div class="space-y-3">
        @foreach($pendingAppointments as $p)

        <div class="flex flex-col gap-2 p-3 border rounded-lg">

            <!-- Patient Info -->
            <div>
                <div class="font-medium">
                    {{ $p->patient->first_name ?? 'Patient' }}
                    {{ $p->patient->last_name ?? '' }}
                </div>

                <div class="flex justify-between text-xs text-gray-500">
                     <span>
                        {{ \Carbon\Carbon::parse($p->appointment_date)->format('Y-m-d H:i') }}
                     </span>
                    @if($showServiceType)
                    <span>
                        • {{ $p->service_type }}
                    </span>
                    @endif


                    {{-- ONLY General Doctor can approve/reject --}}
                    @if(
                        auth()->user()->hasRole('general_doctor') &&
                        $p->status === 'Pending' &&
                        !$p->referred_to_doctor_id
                    )
                        <div class="flex flex-wrap gap-2">
                            <form action="{{ route('doctor.appointments.approve', $p->appointment_id) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1 text-xs text-white bg-green-600 rounded">
                                    {{ __('messages.approve') }}
                                </button>
                            </form>

                            <form action="{{ route('doctor.appointments.reject', $p->appointment_id) }}" method="POST">
                                @csrf
                                <button class="px-3 py-1 text-xs text-white bg-red-600 rounded">
                                    {{ __('messages.reject') }}
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Specialist only sees referral label --}}
            @if(!auth()->user()->hasRole('general_doctor') && $p->referred_to_doctor_id)
                <div class="text-xs text-blue-600">
                    Referred by Dr.
                    <strong>{{ $p->referredByDoctor->first_name ?? 'Doctor' }}</strong>
                </div>
            @endif

        </div>

        @endforeach
    </div>

    @endif

    <div class="mt-4 text-sm text-gray-500">
        Total: {{ $pendingAppointments->count() }} Pending Appointments
    </div>

    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $pendingAppointments->links() }}
        </div>
</div>
@endsection
