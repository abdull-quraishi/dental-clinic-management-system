@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
          {{ __('messages.dr') }} {{ $doctor->first_name }} {{ $doctor->last_name }}
          {{ __('messages.daily_report') }}
      </h2>
        <p class="text-sm text-gray-500">{{ today()->format('d M Y') }}</p>
      <a href="{{ route('admin.doctor.reports') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back') }}
      </a>

    </div>


    <div class="grid grid-cols-1 gap-4 mb-6 sm:grid-cols-2 xl:grid-cols-5">
        <div class="p-4 bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">{{ __('messages.today_appointment') }}</div>
            <div class="mt-2 text-2xl font-bold text-blue-600">{{ $appointmentsToday }}</div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">{{ __('messages.today_patients') }}</div>
            <div class="mt-2 text-2xl font-bold text-green-600">{{ $patientsToday }}</div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">{{ __('messages.today_prescription') }}</div>
            <div class="mt-2 text-2xl font-bold text-purple-600">{{ $prescriptionsToday }}</div>
        </div>

        <div class="p-4 bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">{{ __('messages.today_bill') }}</div>
            <div class="mt-2 text-2xl font-bold text-yellow-600">{{ $billsToday }}</div>
        </div>
    </div>
           <div class="p-4 mt-2 mb-6 bg-white rounded-lg shadow">
            <div class="text-sm text-gray-500">{{ __('messages.amount') }}</div>
            <div class="mt-2 text-2xl font-bold text-red-600">${{ number_format($totalAmountToday, 2) }}</div>
           </div>


    <div class="grid grid-cols-1 gap-6 lg:grid-cols-3">
        <div class="p-4 bg-white rounded-lg shadow lg:col-span-1">
            <h3 class="mb-3 font-semibold">{{ __('messages.today_appointment') }}</h3>
            @forelse($appointments as $app)
                <div class="py-2 border-b">
                    <div class="font-medium">{{ $app->patient->first_name ?? 'Patient' }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($app->appointment_date)->format('h:i A') }}</div>
                </div>
            @empty
                <p class="text-gray-500">{{ __('messages.if_no_appointment_today') }}</p>
            @endforelse
        </div>

        <div class="p-4 bg-white rounded-lg shadow lg:col-span-1">
            <h3 class="mb-3 font-semibold">{{ __('messages.today_prescription') }}</h3>
            @forelse($prescriptions as $prescription)
                <div class="py-2 border-b">
                    <div class="font-medium">{{ $prescription->patient->first_name ?? 'Patient' }}</div>
                    <div class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($prescription->prescription_date)->format('h:i A') }}</div>
                </div>
            @empty
                <p class="text-gray-500">{{ __('messages.if_no_prescription_today') }}</p>
            @endforelse
        </div>

        <div class="p-4 bg-white rounded-lg shadow lg:col-span-1">
            <h3 class="mb-3 font-semibold">{{ __('messages.today_bill') }}</h3>
            @forelse($billings as $bill)
                <div class="py-2 border-b">
                    <div class="font-medium">{{ $bill->patient->first_name ?? 'Patient' }}</div>
                    <div class="text-xs text-gray-500">${{ number_format($bill->amount, 2) }}</div>
                </div>
            @empty
                <p class="text-gray-500">{{ __('messages.if_no_bill_today') }}</p>
            @endforelse
        </div>
    </div>
</div>
@endsection
