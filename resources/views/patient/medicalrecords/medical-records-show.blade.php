@extends('layouts.app')
@section('content')
<div class="max-w-3xl p-6 mx-auto">
  <div class="flex items-center justify-between mb-4">
    <h2 class="text-2xl font-bold">
        {{ __('messages.record_details') }}
    </h2>

      <a href="{{ route('patient.medical.records') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">{{ __('messages.back_to_medical_records') }}</a>
  </div>

  <div class="p-6 bg-white rounded shadow">
    <div class="text-lg font-semibold">{{ $record->doctor ? ($record->doctor->first_name.' '.$record->doctor->last_name) : 'Doctor' }}</div>
    <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($record->treatment_date)->format('d M Y') }}</div>

    <div class="mt-4">
      <h4 class="font-semibold">{{ __('messages.diagnosis') }}</h4>
      <p class="mt-2 text-gray-700">{{ $record->diagnosis }}</p>
    </div>

     <div class="mt-4">
      <h4 class="font-semibold">{{ __('messages.treatment_plan') }}</h4>
      <p class="mt-2 text-gray-700">{{ $record->treatment_plan }}</p>
    </div>

    <div class="mt-4">
      <h4 class="font-semibold">{{ __('messages.treatment_status') }}</h4>
      <p class="mt-2 text-gray-700">{{ $record->treatment_status }}</p>
    </div>

    @if($record->notes)
      <div class="mt-4">
        <h4 class="font-semibold">{{ __('messages.notes') }}</h4>
        <p class="mt-2 text-gray-700">{{ $record->notes }}</p>
      </div>
    @endif
  </div>
</div>
@endsection
