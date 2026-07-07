@extends('layouts.app')
@section('content')
<div class="max-w-4xl p-6 mx-auto">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold">
        {{ __('messages.medical_records_title') }}
    </h2>

      @php
           $isAdminViewing = session()->has('admin_viewing_patient_id');
       @endphp
       <a href="{{ $isAdminViewing ? route('patient.dashboard') : route('patient.dashboard') }}"class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back_to_dashboard') }}
       </a>

  </div>

  <div class="space-y-4">
    @forelse($records as $r)
      <div class="p-4 bg-white rounded shadow">
        <div class="flex items-center justify-between">
          <div>
            <div class="font-semibold">{{ $r->doctor ? ($r->doctor->first_name.' '.$r->doctor->last_name) : 'Doctor' }}</div>
            <div class="text-sm text-gray-500">{{ \Carbon\Carbon::parse($r->treatment_date)->format('d M Y') }}</div>
          </div>
          <a href="{{ route('patient.medical.records.show', $r->treatment_id) }}" class="mr-2  bg-blue-600 text-white px-3 py-1 rounded text-sm">
            {{ __('messages.view_prescription') }}
          </a>
        </div>
        <div class="mt-3 text-gray-700">
          <strong>{{ __('messages.diagnosis') }}:</strong> {{ $r->diagnosis }}
        </div>
      </div>
    @empty
      <div class="p-6 text-center text-gray-400 bg-white rounded shadow">
        {{ __('messages.no_medical_records') }}
      </div>
    @endforelse
  </div>

    {{-- Pagination --}}
     <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
         {{ $records->links() }}
     </div>
</div>
@endsection
