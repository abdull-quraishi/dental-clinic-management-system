@extends('layouts.app')
@section('content')
<div class="max-w-3xl p-6 mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">
        {{ __('messages.record_details') }}
    </h2>

       @php
           $isAdminViewing = session()->has('admin_viewing_patient_id');
       @endphp
       <a href="{{ $isAdminViewing ? route('patient.prescriptions') : route('patient.prescriptions') }}"class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back_to_prescriptions') }}
       </a>

  </div>

  <div class="p-6 bg-white rounded shadow">
    <div class="font-semibold">{{ $prescription->doctor ? ($prescription->doctor->first_name.' '.$prescription->doctor->last_name) : 'Doctor' }}</div>
    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($prescription->prescription_date)->format('d M Y') }}</div>

    <div class="mt-4">
      <h4 class="font-semibold">{{ __('messages.services') }}</h4>

    {{-- Service --}}
    <div class="font-semibold text-blue-700">
        {{ $prescription->service->name ?? 'General Prescription' }}
    </div>

    </div>

    <div class="mt-4">
      <h4 class="font-semibold">{{ __('messages.instructions') }}</h4>
      <div class="mt-2 text-gray-700 whitespace-pre-line">{{ $prescription->instructions }}</div>
    </div>
  </div>
</div>
@endsection
