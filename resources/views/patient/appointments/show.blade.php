@extends('layouts.app')
@section('content')
<div class="max-w-3xl p-6 mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">
        {{ __('messages.appointment_details') }}
    </h2>
    <a href="{{ route('patient.appointments.index') }}" class="text-sm px-4 py-2 text-white bg-red-500 rounded ">{{ __('messages.back_to_appointments') }}</a>
  </div>

  <div class="p-6 bg-white rounded-lg shadow">
    <div class="flex items-start gap-6">
      <div class="w-24 h-24 overflow-hidden rounded-md">
        <img src="{{ $appointment->doctor && $appointment->doctor->image ? asset('doctor_images/'.$appointment->doctor->image) : asset('images/default-doctor.png') }}" class="object-cover w-full h-full">
      </div>
      <div>
        <h3 class="text-xl font-semibold">{{ $appointment->doctor->first_name ?? 'Doctor' }} {{ $appointment->doctor->last_name ?? '' }}</h3>
        <div class="text-sm text-gray-500">{{ $appointment->doctor->specialty ?? '' }}</div>
        <div class="mt-3">
          <strong>Service:</strong> {{ $appointment->service_type }}<br>
          <strong>Date:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('d M Y') }}<br>
          <strong>Time:</strong> {{ \Carbon\Carbon::parse($appointment->appointment_date)->format('h:i A') }}<br>
          <strong>Status:</strong> {{ $appointment->status }}
        </div>
      </div>
    </div>

    @if($appointment->notes)
      <div class="mt-6">
        <h4 class="font-semibold">Notes</h4>
        <p class="text-gray-700">{{ $appointment->notes }}</p>
      </div>
    @endif

  </div>
</div>
@endsection
