@extends('layouts.app')

@section('content')
    <div class="max-w-4xl p-6 mx-auto">

        <div class="flex items-center justify-between mb-4">
            <h2 class="text-2xl font-semibold">
                {{ __('messages.prescriptions_title') }}
            </h2>

           <a href="{{ route('patient.dashboard') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">Back</a>

        </div>

        <div class="p-4 bg-white rounded shadow">
            @if ($prescriptions->isEmpty())
                <div class="text-gray-500">
                    {{ __('messages.no_prescriptions') }}
                </div>
            @else
                <table class="w-full text-left">
                    <thead class="text-sm text-gray-500 border-b">
                        <tr>
                            <th class="py-2">
                                {{ __('messages.date') }}
                            </th>
                            <th>
                                {{ __('messages.doctor') }}
                            </th>
                            <th>
                                {{ __('messages.services') }}
                            </th>
                            <th>
                                {{ __('messages.actions') }}
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($prescriptions as $pres)
                            <tr class="border-b">
                                <td class="py-3 text-sm">
                                    {{ \Carbon\Carbon::parse($pres->prescription_date)->format('d M Y') }}</td>
                                <td class="py-3">Dr.
                                    {{ $pres->doctor->first_name ?? ($pres->doctor->user->name ?? 'Doctor') }}</td>
                               <td class="py-3">
                                   {{-- Service --}}
                                   <div class="font-semibold text-blue-700">
                                       {{ $pres->service->name ?? 'General Prescription' }}
                                   </div>
                               </td>
                                <td class="py-3 text-right">
                                    <!-- could link to detail modal or print -->
                                    <a href="{{ route('patient.prescriptions.show', ['id' => $pres->prescription_id]) }}"  class="mr-2  bg-blue-600 text-white px-3 py-1 rounded text-sm">
                                        {{ __('messages.view_prescription') }}
                                    </a>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        {{-- Pagination --}}
     <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
         {{ $prescriptions->links() }}
     </div>
    </div>
@endsection
