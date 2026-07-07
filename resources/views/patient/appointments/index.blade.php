@extends('layouts.app')
@section('content')
<div class="max-w-4xl p-6 mx-auto">
 <div class="flex flex-wrap items-center justify-between gap-3 mb-6">

    <div class="flex justify-between gap-2 text-sm">
       <h2 class="text-xl font-bold me-4">{{ __('messages.appointments_title') }}</h2>
        <a href="{{ route('patient.appointments.index') }}"class="px-4 py-2 text-white bg-indigo-600 rounded ms-4">
            {{ __('messages.all_appointments') }}
        </a>
        <a href="{{ route('patient.appointments.upcoming') }}" class="px-4 py-2 text-white bg-green-600 rounded">
            {{ __('messages.upcoming_appointments') }}
        </a>
        {{-- create appointment button --}}
       @if(isset($adminViewing) && $adminViewing)
           <div class="flex gap-3 ">
               <a href="{{ route('admin.patients.appointments.create', $patientUser->id) }}"
                  class="inline-block px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                  {{ __('messages.create_appointment_if_admin', ['patient' => $patientUser->name ?? 'Patient']) }}
               </a>
           </div>
       @else
           <div class="ms-8">
               <a href="{{ route('patient.appointments.create') }}"
                  class="inline-block px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700 me-2">
                     {{ __('messages.create_appointment') }}
               </a>
           </div>
       @endif

        @php
           $isAdminViewing = session()->has('admin_viewing_patient_id');
       @endphp
       <a href="{{ $isAdminViewing ? route('patient.dashboard') : route('patient.dashboard') }}"class="px-6 py-2 text-white bg-red-500 rounded ">
          {{ __('messages.back_to_dashboard') }}
       </a>
    </div>
</div>

  <div class="bg-white rounded-lg shadow">
    <table class="min-w-full text-left">
      <thead class="text-sm text-gray-500 border-b">
        <tr>
          <th class="px-4 py-3">{{ __('messages.doctor') }}</th>
          <th>{{ __('messages.date_and_time') }}</th>
          <th>{{ __('messages.status') }}</th>
          <th class="pr-4 text-right">{{ __('messages.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @forelse($appointments as $app)
          <tr class="border-b">
            <td class="px-4 py-3">
              <div class="flex items-center gap-3">
                <div class="w-10 h-10 overflow-hidden rounded-md">
                  <img src="{{ $app->doctor && $app->doctor->image ? asset('doctor_images/'.$app->doctor->image) : asset('images/default-doctor.png') }}" class="object-cover w-full h-full">
                </div>
                <div>
                 <div class="font-medium">
                   @if($app->referred_by_doctor_id && !$app->referred_to_doctor_id)
                     Dr.{{ __('messages.Follow-up_app') }}
                      {{ $app->doctor->first_name ?? 'Doctor' }} {{ $app->doctor->last_name ?? '' }}
                   @else
                      Dr.{{ $app->doctor->first_name ?? 'Doctor' }} {{ $app->doctor->last_name ?? '' }}
                   @endif
                 </div>
                  <div class="text-sm text-gray-500">{{ $app->doctor->specialty ?? '' }}</div>
                </div>
              </div>
            </td>
            <td>{{ \Carbon\Carbon::parse($app->appointment_date)->format('d M Y, h:i A') }}</td>
            <td>
              <span class="px-2 py-1 rounded text-sm
                {{ $app->status == 'Pending' ? 'bg-yellow-100 text-yellow-800' : '' }}
                {{ $app->status == 'Approved' ? 'bg-green-100 text-green-800' : '' }}
                {{ $app->status == 'Rejected' ? 'bg-red-100 text-red-800' : '' }}
                {{ $app->status == 'Cancelled' ? 'bg-gray-100 text-gray-800' : '' }}
              ">
                {{ $app->status }}
              </span>

               @if($app->appointment_message)
                <div class="p-2 mt-2 text-xs leading-5 text-blue-600 whitespace-pre-line rounded bg-blue-50">
                   {{ $app->appointment_message }}
                </div>
               @endif

            </td>
            <td class="pr-4 text-right">
              <a href="{{ route('patient.appointments.show', $app->appointment_id) }}" class="mr-2  bg-blue-600 text-white px-3 py-1 rounded text-sm">
                {{ __('messages.view_app') }}
              </a>

              @if(in_array($app->status, ['Pending']))
              <form action="{{ route('patient.appointments.cancel', $app->appointment_id) }}" method="POST" class="inline">
                @csrf

                <button type="submit" class="px-3 py-1 mt-1 text-sm text-white bg-red-600 rounded">
                    {{ __('messages.reject_app') }}
                </button>
              </form>
              @endif
            </td>
          </tr>
        @empty
          <tr>
            <td colspan="5" class="py-6 text-center text-gray-400">
                {{ __('messages.no_appointments') }}
            </td>
          </tr>
        @endforelse
      </tbody>
    </table>
  </div>
   {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $appointments->links() }}
        </div>

</div>

@endsection
