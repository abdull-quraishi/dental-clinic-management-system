@extends('layouts.app')

@section('content')

{{-- PRINT STYLE --}}
<style>
@media print {

    .no-print,
    nav,
    aside,
    header,
    footer {
        display: none !important;
    }

    html,
    body,
    .overflow-y-auto {
        overflow: visible !important;
        height: auto !important;
        background: white !important;
    }

    /* Hide scrollbar */
    * {
        scrollbar-width: none !important; /* Firefox */
        -ms-overflow-style: none !important; /* IE */
    }

    *::-webkit-scrollbar {
        display: none !important; /* Chrome */
    }

    .print-area {
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
}
</style>

<div class="h-full overflow-y-auto bg-gradient-to-br from-slate-100 via-blue-50 to-cyan-50">

    <div class="max-w-6xl px-4 py-4 mx-auto">

        {{-- HEADER --}}
        <div class="flex flex-col items-start justify-between gap-4 mb-4 md:flex-row md:items-center">

            <div>
                <h1 class="text-3xl font-extrabold tracking-tight text-gray-800">
                  {{ __('messages.patient_history_title') }}
                </h1>

                <p class="mt-1 text-gray-500">
                    {{ __('messages.patient_history_desc') }}

                    <span class="font-semibold text-blue-700">
                        {{ $patient->first_name }}
                        {{ $patient->last_name }}
                    </span>
                </p>
            </div>

            <div class="flex gap-2 no-print">

                <a href="{{ route('admin.patients.history.print', $patient->user_id) }}"
                   target="_blank"
                   class="px-4 py-2 text-white transition bg-green-600 shadow rounded-xl hover:bg-green-700">
                    🖨 {{ __('messages.print_history') }}

                 </a>


                <a href="{{ route('admin.patients.index') }}"
                   class="px-4 py-2 text-white transition bg-blue-600 shadow rounded-xl hover:bg-blue-700">

                ←{{ __('messages.back_to_patients') }}

                </a>

            </div>
        </div>

        {{-- PATIENT SUMMARY --}}
        <div class="p-5 mb-2 bg-white border shadow-lg print-area rounded-2xl border-slate-200">

            <div class="grid grid-cols-1 gap-4 md:grid-cols-4">

                <div>
                    <p class="text-sm text-gray-500">
                    {{ __('messages.patient') }}
                    </p>

                    <h3 class="text-lg font-bold text-gray-800">
                        {{ $patient->first_name }}
                        {{ $patient->last_name }}
                    </h3>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                    {{ __('messages.phone') }}
                    </p>

                    <h3 class="font-semibold text-gray-800">
                        {{ $patient->phone ?? 'N/A' }}
                    </h3>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                    {{ __('messages.email') }}
                    </p>

                    <h3 class="font-semibold text-gray-800">
                        {{ $patient->email ?? 'N/A' }}
                    </h3>
                </div>

                <div>
                    <p class="text-sm text-gray-500">
                    {{ __('messages.total_visits') }}
                    </p>

                    <h3 class="text-2xl font-extrabold text-blue-600">
                        {{ $appointments->count() }}
                    </h3>
                </div>

            </div>
        </div>

        {{-- TIMELINE --}}
        <div class="relative">

            @forelse($timeline as $item)

                <div class="relative pl-12 mb-5">

                    {{-- LINE --}}
                    <div class="absolute top-0 left-4 w-[3px] h-full bg-blue-200 rounded-full"></div>

                    {{-- ICON --}}
                    <div class="absolute left-0 z-10 flex items-center justify-center w-8 h-8 text-sm text-white bg-blue-600 rounded-full shadow-lg">

                        @if($item['type'] == 'Appointment')
                            📅
                        @elseif($item['type'] == 'Diagnosis')
                            🩺
                        @elseif($item['type'] == 'Prescription')
                            💊
                        @elseif($item['type'] == 'Billing')
                            💳
                        @endif

                    </div>

                    {{-- CARD --}}
                    <div class="p-5 transition-all duration-300 bg-white border shadow-lg rounded-3xl border-slate-200 hover:shadow-2xl">

                        {{-- TOP --}}
                        <div class="flex flex-col justify-between gap-3 mb-4 md:flex-row md:items-center">

                            <div>

                                <h2 class="text-xl font-bold text-gray-800">
                                    {{ $item['title'] }}
                                </h2>

                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item['date'])->format('d M Y - h:i A') }}
                                </p>
                            </div>

                            <span class="px-4 py-2 text-sm font-semibold rounded-full

                                @if($item['type'] == 'Appointment')
                                    bg-blue-100 text-blue-700
                                @elseif($item['type'] == 'Diagnosis')
                                    bg-green-100 text-green-700
                                @elseif($item['type'] == 'Prescription')
                                    bg-purple-100 text-purple-700
                                @elseif($item['type'] == 'Billing')
                                    bg-yellow-100 text-yellow-700
                                @endif
                            ">
                                {{ $item['type'] }}
                            </span>

                        </div>

                        {{-- APPOINTMENT --}}
                        @if($item['type'] == 'Appointment')

                            <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

                                <div>
                                    <p class="text-sm text-gray-500">
                                   {{ __('messages.service') }}
                                    </p>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item['service_type'] }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">
                                  {{ __('messages.status') }}
                                    </p>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item['status'] }}
                                    </p>
                                </div>

                                @if($item['notes'])

                                <div class="md:col-span-2">

                                    <p class="text-sm text-gray-500">
                                  {{ __('messages.note') }}
                                    </p>

                                    <div class="p-3 mt-1 text-gray-700 rounded-xl bg-slate-50">
                                        {{ $item['notes'] }}
                                    </div>
                                </div>

                                @endif

                                @if(!empty($item['referred_to_doctor']))

                                <div class="md:col-span-2">

                                    <p class="font-medium text-indigo-700">
                                 {{ __('messages.refared_to_another_doctor') }}
                                        {{ $item['referred_to_doctor'] }}
                                    </p>
                                </div>

                                @endif

                            </div>

                        @endif

                        {{-- DIAGNOSIS --}}
                        @if($item['type'] == 'Diagnosis')

                            <div class="space-y-3">

                                <div>
                                    <p class="text-sm text-gray-500">
                                 {{ __('messages.diagnosis') }}
                                    </p>

                                    <p class="font-semibold text-gray-800">
                                        {{ $item['diagnosis'] }}
                                    </p>
                                </div>

                                <div>
                                    <p class="text-sm text-gray-500">
                                 {{ __('messages.treatment_status') }}
                                    </p>

                                    <p class="font-semibold text-green-700">
                                        {{ $item['treatment_status'] }}
                                    </p>
                                </div>

                            </div>

                        @endif

                                            <!-- Prescription & Billing -->
                     @if($item['type'] == 'Prescription')

                     <div class="space-y-5">

                         {{-- Service --}}
                         <div>
                             <p class="text-sm text-gray-500">
                                 {{ __('messages.service') }}
                             </p>

                             <div class="p-3 font-semibold text-blue-700 bg-blue-50 rounded-xl">
                                 {{ $item['service_name'] }}
                             </div>
                         </div>

                         {{-- Medicines --}}
                         <div>
                             <p class="mb-2 text-sm text-gray-500">
                                 {{ __('messages.medicines') }}
                             </p>

                             <div class="space-y-2">

                                 @forelse($item['medicines'] as $medicine)

                                     <div class="flex items-center justify-between p-3 rounded-xl bg-slate-50">

                                         <div>
                                             <span class="font-semibold text-gray-800">
                                                 {{ $medicine->medicine->name ?? 'Medicine' }}
                                             </span>
                                         </div>

                                         <div class="text-sm text-gray-600">
                                       {{ __('messages.qty') }}
                                             : {{ $medicine->quantity }}
                                         </div>

                                     </div>

                                 @empty

                                     <div class="p-3 text-gray-500 rounded-xl bg-slate-50">
                                 {{ __('messages.no_medicines') }}
                                     </div>

                                 @endforelse

                             </div>
                         </div>

                         {{-- Instructions --}}
                         @if($item['instructions'])
                         <div>
                             <p class="text-sm text-gray-500">
                                 {{ __('messages.instructions') }}
                             </p>

                             <div class="p-3 rounded-xl bg-yellow-50">
                                 {{ $item['instructions'] }}
                             </div>
                         </div>
                         @endif

                         {{-- Billing --}}
                         <div class="grid grid-cols-1 gap-4 pt-4 border-t md:grid-cols-4">

                             <div>
                                 <p class="text-sm text-gray-500">
                                 {{ __('messages.service_fee') }}
                                 </p>

                                 <p class="font-bold text-blue-700">
                                     ${{ number_format($item['service_total'],2) }}
                                 </p>
                             </div>

                             <div>
                                 <p class="text-sm text-gray-500">
                                 {{ __('messages.appointment_fee') }}
                                 </p>

                                 <p class="font-bold text-indigo-700">
                                     ${{ number_format($item['appointment_fee'],2) }}
                                 </p>
                             </div>

                             <div>
                                 <p class="text-sm text-gray-500">
                                    {{ __('messages.total_medicine_cost') }}
                                 </p>

                                 <p class="font-bold text-purple-700">
                                     ${{ number_format($item['medicine_total'],2) }}
                                 </p>
                             </div>

                             <div>
                                 <p class="text-sm text-gray-500">
                                {{ __('messages.total_bill_amount') }}
                                 </p>

                                 <p class="text-xl font-extrabold text-green-600">
                                     ${{ number_format($item['total_amount'],2) }}
                                 </p>
                             </div>

                         </div>

                         {{-- Bill Status --}}
                         <div>

                             <span class="px-4 py-2 text-sm font-bold rounded-full
                                 {{ $item['bill_status'] == 'paid'
                                     ? 'bg-green-100 text-green-700'
                                     : 'bg-red-100 text-red-700' }}
                             ">

                                  {{ __('messages.bill_status') }}:
                                 {{ ucfirst($item['bill_status']) }}

                             </span>

                         </div>

                     </div>

                     @endif

                    </div>
                </div>

            @empty

                <div class="p-10 text-center bg-white shadow rounded-3xl">

                    <div class="mb-3 text-5xl">
                        📂
                    </div>

                    <h3 class="text-2xl font-bold text-gray-700">
                      {{ __('messages.no_history_title1') }}
                    </h3>

                    <p class="mt-2 text-gray-500">
                      {{ __('messages.no_history_title2') }}
                    </p>

                </div>

            @endforelse

        </div>

    </div>
</div>

@endsection
