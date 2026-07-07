{{-- resources/views/doctor/todays-app.blade.php --}}
@extends('layouts.app')

@section('content')

<div class="p-5 bg-white shadow rounded-xl">

    <!-- Header -->
    <div class="flex items-center justify-between mb-4">
        <h2 class="text-lg font-semibold">
            {{ __('messages.todays_appointments_title') }}
        </h2>
        <span class="text-sm text-gray-500">
            {{ \Carbon\Carbon::today()->format('Y-m-d') }}
        </span>
    </div>

    @if($todayAppointments->isEmpty())
        <p class="text-gray-500">
            {{ __('messages.no_todays_appointments') }}
        </p>
    @else

     {{-- this  code for declaring variable if doctor is general the service type should show if not not show--}}
     @php
      $showServiceType = auth()->user()->hasRole('general_doctor');
     @endphp
    <div class="overflow-x-auto">
        <table class="w-full text-sm">
            <thead class="text-gray-600 bg-gray-50">
                <tr>
                    <th class="p-3 text-left"> {{ __('messages.patient') }} </th>
                    @if($showServiceType)
                     <th class="p-3 text-left">{{ __('messages.service') }}</th>
                     @endif
                    <th class="p-3 text-left">{{ __('messages.priority') }}</th>
                    <th class="p-3 text-left">{{ __('messages.time') }}</th>
                    <th class="p-3 text-left">{{ __('messages.status') }}</th>
                    <th class="p-3 text-right">{{ __('messages.actions') }}</th>
                </tr>
            </thead>

            <tbody>
            @foreach($todayAppointments as $app)
                <tr class="align-top border-t">

                    <!-- Patient -->
                    <td class="p-3">
                        <div class="font-medium">
                            {{ $app->patient->first_name ?? 'N/A' }}
                            {{ $app->patient->last_name ?? '' }}
                        </div>
                        <div class="text-xs text-gray-500">
                            {{ $app->patient->email ?? '' }}
                        </div>
                    </td>

                    <!-- Service -->
                     @if($showServiceType)
                    <td class="p-3">{{ $app->service_type }}</td>
                     @endif

                    <!-- Priority -->
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($app->priority === 'Critical') bg-red-100 text-red-700
                            @elseif($app->priority === 'Medium') bg-yellow-100 text-yellow-700
                            @else bg-green-100 text-green-700 @endif">
                            {{ $app->priority }}
                        </span>
                    </td>

                    <!-- Time -->
                    <td class="p-3">
                        {{ \Carbon\Carbon::parse($app->appointment_date)->format('H:i') }}
                    </td>

                    <!-- Status -->
                    <td class="p-3">
                        <span class="px-2 py-1 text-xs rounded
                            @if($app->status === 'Pending') bg-yellow-100 text-yellow-700
                            @elseif($app->status === 'Approved') bg-green-100 text-green-700
                            @elseif($app->status === 'Rejected') bg-red-100 text-red-700
                            @else bg-gray-100 text-gray-700 @endif">
                            {{ $app->status }}
                        </span>

                        @if($app->status === 'Rejected' && $app->appointment_message)
                        <div class="p-2 mt-2 text-xs leading-5 text-red-700 rounded bg-red-50">
                            {{ $app->appointment_message }}
                        </div>
                       @endif
                    </td>

                    <!-- Actions -->
                    <td class="p-3 space-y-2 text-right">

                        {{-- GENERAL DOCTOR: only approve/reject pending --}}
                        @if(
                            auth()->user()->hasRole('general_doctor') &&
                            $app->status === 'Pending' &&
                            !$app->referred_to_doctor_id
                        )

                            <div class="flex flex-wrap justify-end gap-2">
                                <form action="{{ route('doctor.appointments.approve', $app->appointment_id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1 text-xs text-white bg-green-600 rounded">
                                         {{ __('messages.approve') }}
                                    </button>
                                </form>

                                <form action="{{ route('doctor.appointments.reject', $app->appointment_id) }}" method="POST">
                                    @csrf
                                    <button class="px-3 py-1 text-xs text-white bg-red-600 rounded">
                                        {{ __('messages.reject') }}
                                    </button>
                                </form>
                            </div>

                        {{-- SPECIALIST: referred patient -> NO approve/reject, ONLY follow-up --}}
                        @elseif(
                            !auth()->user()->hasRole('general_doctor') &&
                            $app->referred_to_doctor_id == $doctor->doctor_id
                        )

                            <div class="text-xs text-blue-600">
                                Referred by Dr.
                                <strong>
                                    {{ $app->referredByDoctor->first_name ?? 'Doctor' }}
                                </strong>
                            </div>


                            @if($app->status === 'Approved')
                                <form action="{{ route('doctor.followup', $app->appointment_id) }}" method="POST" class="mt-2 space-y-1">
                                    @csrf
                                    <div class="grid grid-cols-2 gap-1">
                                        <input type="date" name="followup_date" class="p-1 text-xs border rounded" required>
                                        <input type="time" name="followup_time" class="p-1 text-xs border rounded" required>
                                    </div>
                                    <button class="w-full px-2 py-1 text-xs text-white bg-indigo-600 rounded">
                                        Set Follow-Up
                                    </button>
                                </form>
                            @endif

                        @else

                           

                            {{-- Referral Labels --}}
                            @if($app->referred_to_doctor_id)
                                <div class="text-xs text-blue-600">
                                    @if(auth()->user()->hasRole('general_doctor'))
                                        You referred this to
                                        <strong>
                                            {{ $app->referredToDoctor->first_name ?? 'Doctor' }}
                                        </strong>
                                    @else
                                        Referred by Dr.
                                        <strong>
                                            {{ $app->referredByDoctor->first_name ?? 'Doctor' }}
                                        </strong>
                                    @endif
                                </div>
                            @endif

                            {{-- General doctor refer option --}}
                            @if(
                                $app->status === 'Approved' &&
                                !$app->referred_to_doctor_id &&
                                auth()->user()->hasRole('general_doctor')
                            )

                                <form action="{{ route('doctor.refer', $app->appointment_id) }}" method="POST" class="mt-2">
                                    @csrf

                                    <div class="flex gap-1">
                                        <select name="doctor_id" class="w-full p-1 text-xs border rounded" required>

                                            @foreach(
                                                \App\Models\Doctor::where('user_id', '!=', auth()->id())
                                                    ->whereHas('user', function($q) {
                                                        $q->role('filler_specialist_doctor')
                                                          ->orWhere(function($sub) {
                                                              $sub->role('extractor_specialist_doctor');
                                                          })
                                                          ->orWhere(function($sub) {
                                                              $sub->role('cleaner_specialist_doctor');
                                                          })
                                                          ->orWhere(function($sub) {
                                                              $sub->role('root_canal_specialist_doctor');
                                                          });
                                                    })
                                                    ->get() as $doc
                                            )

                                                <option value="{{ $doc->doctor_id }}">
                                                    {{ $doc->first_name }} - {{ $doc->role }}
                                                </option>

                                            @endforeach

                                        </select>

                                        <button class="px-2 py-1 text-xs text-white bg-blue-600 rounded">
                                            Refer
                                        </button>
                                    </div>
                                </form>

                            @endif

                        @endif

                    </td>
                </tr>
            @endforeach
            </tbody>
        </table>
    </div>

    @endif
</div>

<div class="mt-2 text-sm text-gray-500">
    Total: {{ $todayAppointments->count() }} appointments
</div>

   {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $todayAppointments->links() }}
        </div>

@endsection
