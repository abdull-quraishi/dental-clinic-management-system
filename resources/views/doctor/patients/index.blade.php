@extends('layouts.app')

@section('content')

<div class="max-w-6xl p-6 mx-auto">

<div class="flex items-center justify-between mb-6">
<h2 class="text-2xl font-bold text-gray-800">
    {{ __('messages.patients_title') }}
</h2>

<a href="{{ route('doctor.dashboard') }}"
class="px-4 py-2 text-sm text-white bg-red-500 rounded">{{ __('messages.back') }}</a>

</div>


<div class="p-6 bg-white shadow rounded-xl">

<div class="flex items-center justify-between mb-4">

<h3 class="text-lg font-semibold">
    {{ __('messages.patients_list') }}
</h3>

{{-- this is for search bar --}}
<form method="GET"
             action="{{ route('doctor.patients') }}"
             class="flex flex-col  sm:flex-row">

           <input type="text"
                  name="search"
                  value="{{ request('search') }}"
                  placeholder="Search by name or phone"
                  class="px-3 py-2 border rounded">

           <button type="submit"
                   class="px-4 py-2 text-white bg-green-600 rounded">
               {{ __('messages.search') }}
           </button>
</form>

</div>
<div class="overflow-x-auto">
<table class="min-w-full text-left">
<thead class="text-sm text-gray-500 border-b">

<tr>
<th class="py-3">{{ __('messages.patient') }}</th>
<th>{{ __('messages.phone') }}</th>
<th>{{ __('messages.last_visit') }}</th>
<th>{{ __('messages.status') }}</th>
<th class='text-center'>{{ __('messages.actions') }}</th>
</tr>

</thead>

<tbody>

@forelse($patients as $p)

@php

$lastAppointment = $p->appointments->first();

$lastVisit = $lastAppointment
? \Carbon\Carbon::parse($lastAppointment->appointment_date)->format('Y-m-d')
: 'No Visit';

$statusRecord = \App\Models\TreatmentRecord::where('patient_id',$p->patient_id)
->latest()
->first();

$status = $statusRecord->treatment_status ?? 'Waiting';

@endphp

<tr class="border-b">
 <td class="py-3"><div class="font-medium">{{ $p->first_name }} {{ $p->last_name }}</div></td>
 <td>{{ $p->phone_number }}</td>
 <td>{{ $lastVisit }}</td>
<td>

@if($status == 'Healed')
<span class="px-2 py-1 text-sm text-red-700 bg-red-100 rounded">{{ __('messages.healed') }}</span>
@elseif($status == 'In Treatment')
<span class="px-2 py-1 text-sm text-yellow-800 bg-yellow-100 rounded">{{ __('messages.intreatment') }}</span>
 @else
<span class="px-2 py-1 text-sm text-green-800 bg-green-100 rounded">{{ __('messages.waiting') }}</span>
 @endif
</td>

<td class="space-x-3 text-right">
    <a href="{{ route('doctor.patient.history', ['patient_id' => $p->patient_id]) }}"
       class="text-sm text-purple-600">
    {{ __('messages.history') }}
    </a>

    <a href="{{ route('doctor.prescription.form', ['patient_id' => $p->patient_id]) }}"
       class="text-sm text-green-600">
    {{ __('messages.prescribe') }}
    </a>

    <a href="{{ route('doctor.diagnosis.form', ['patient_id' => $p->patient_id]) }}"
       class="text-sm text-blue-600">
    {{ __('messages.diag_and_treat') }}
    </a>
</td>
</tr>

@empty

<tr>
 <td colspan="5" class="py-6 text-center text-gray-400">
    {{ __('messages.no_patients') }}
 </td>
</tr>

@endforelse

</tbody>

</table>

</div>

  <div class="mt-4 text-sm text-gray-500">

   Total Patients: {{ count($patients) }}

  </div>

  </div>

  {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $patients->links() }}
        </div>
</div>

@endsection
