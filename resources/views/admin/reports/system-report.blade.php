@extends('layouts.app')

@section('content')


<div class="p-4">

    {{-- Header --}}
    <div class="flex flex-col items-start justify-between gap-3 mb-6 md:flex-row md:items-center">

        <div>
            <h1 class="text-3xl font-bold text-gray-800">
             📈 {{ __('messages.system_report') }}
            </h1>

            <p class="text-gray-500">
            {{ __('messages.clinic_daily_report') }}

            </p>
        </div>

        <a href="{{ route('admin.reports.daily.print', request()->all()) }}"
         target="_blank"
         class="px-4 py-2 text-white bg-green-600 rounded-lg">
         🖨 {{ __('messages.print_report') }}
         </a>

    </div>

    {{-- Date Filter --}}
    <div class="p-4 mb-6 bg-white shadow rounded-xl">

        <form method="GET"
            action="{{ route('admin.reports.daily') }}"
            class="grid grid-cols-1 gap-4 md:grid-cols-4">

            <div>
                <label class="block mb-1 text-sm font-medium">
                    {{ __('messages.from_date') }}
                </label>

                <input
                    type="date"
                    name="from_date"
                    value="{{ request('from_date') }}"
                    class="w-full border rounded-lg">
            </div>

            <div>
                <label class="block mb-1 text-sm font-medium">
                     {{ __('messages.to_date') }}
                </label>

                <input
                    type="date"
                    name="to_date"
                    value="{{ request('to_date') }}"
                    class="w-full border rounded-lg">
            </div>

            <div class="flex items-end">
                <button
                    class="w-full px-4 py-2 text-white bg-blue-600 rounded-lg hover:bg-blue-700 ">
                      {{ __('messages.filter') }}
                </button>
            </div>

        </form>

    </div>

    {{-- Summary Cards --}}
    <div class="grid grid-cols-1 gap-4 mb-6 md:grid-cols-4">

        <div class="p-5 text-white bg-blue-600 shadow rounded-xl">
            <div>
               {{ __('messages.total_patients') }}
            </div>
            <div class="mt-2 text-3xl font-bold">
                {{ $totalPatients }}
            </div>
        </div>

        <div class="p-5 text-white bg-green-600 shadow rounded-xl">
            <div>
                {{ __('messages.new_patients') }}
            </div>
            <div class="mt-2 text-3xl font-bold">
                {{ $newPatients }}
            </div>
        </div>

        <div class="p-5 text-white bg-purple-600 shadow rounded-xl">
            <div>
                  {{ __('messages.total_appointments') }}
            </div>
            <div class="mt-2 text-3xl font-bold">
                {{ $totalAppointments }}
            </div>
        </div>

        <div class="p-5 text-white bg-red-600 shadow rounded-xl">
            <div>
               {{ __('messages.revenue') }}
            </div>
            <div class="mt-2 text-3xl font-bold">
             {{ number_format($revenue,2) }} afg
            </div>
        </div>

    </div>

    {{-- Appointments --}}
    <div class="p-5 mb-6 bg-white shadow rounded-xl">

        <h2 class="mb-4 text-xl font-bold">
            📅 {{ __('messages.appointment_statistics') }}
        </h2>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-5">

            <div>
                <div class="text-gray-500">{{ __('messages.pending') }}</div>
                <div class="text-2xl font-bold text-yellow-600">
                    {{ $pendingAppointments }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">{{ __('messages.approved') }}</div>
                <div class="text-2xl font-bold text-blue-600">
                    {{ $approvedAppointments }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">{{ __('messages.completed') }}</div>
                <div class="text-2xl font-bold text-green-600">
                    {{ $completedAppointments }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">{{ __('messages.rejected') }}</div>
                <div class="text-2xl font-bold text-red-600">
                    {{ $rejectedAppointments }}
                </div>
            </div>

        </div>

    </div>

    {{-- Medical --}}
    <div class="p-5 mb-6 bg-white shadow rounded-xl">

        <h2 class="mb-4 text-xl font-bold">
            🩺 {{ __('messages.medical_statistics') }}
        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
                <div class="text-gray-500">
                    {{ __('messages.diagnosis') }}
                </div>

                <div class="text-3xl font-bold text-indigo-600">
                    {{ $diagnosesCount }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                    {{ __('messages.prescriptions') }}
                </div>

                <div class="text-3xl font-bold text-purple-600">
                    {{ $prescriptionsCount }}
                </div>
            </div>

        </div>

    </div>

    {{-- Billing --}}
    <div class="p-5 mb-6 bg-white shadow rounded-xl">

        <h2 class="mb-4 text-xl font-bold">
            💰 {{ __('messages.financial_statistics') }}
        </h2>

        <div class="grid grid-cols-2 gap-4 md:grid-cols-4">

            <div>
                <div class="text-gray-500">
                    {{ __('messages.total_bills') }}
                </div>

                <div class="text-2xl font-bold">
                    {{ $totalBills }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                    {{ __('messages.paid_bills') }}
                </div>

                <div class="text-2xl font-bold text-green-600">
                    {{ $paidBills }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                     {{ __('messages.unpaid_bills') }}
                </div>

                <div class="text-2xl font-bold text-red-600">
                    {{ $unpaidBills }}
                </div>
            </div>

            <div>
                <div class="text-gray-500">
                    {{ __('messages.revenue') }}
                </div>

                <div class="text-2xl font-bold text-blue-600">
                 {{ number_format($revenue,2) }} afg
                </div>
            </div>

        </div>

    </div>

    {{-- Doctor Statistics --}}
    <div class="p-5 bg-white shadow rounded-xl">

        <h2 class="mb-4 text-xl font-bold">
            👨‍⚕️ {{ __('messages.doctor_statistics') }}

        </h2>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">

            <div>
                <div class="text-gray-500">
                    {{ __('messages.active_doctors') }}
                </div>

                <div class="text-3xl font-bold text-green-600">
                    {{ $activeDoctors }}
                </div>
            </div>

            <div>

                <div class="text-gray-500">
                    {{ __('messages.top_doctor') }}
                </div>

                @if($topDoctor)

                    <div class="mt-2 text-xl font-bold">

                        Dr.
                        {{ $topDoctor->first_name }}
                        {{ $topDoctor->last_name }}

                    </div>

                    <div class="text-blue-600">

                        {{ __('messages.appointments_count') }}:
                         {{ $topDoctor->appointments_count }}

                    </div>

                @else

                    <div>{{ __('messages.no_data') }}</div>

                @endif

            </div>

        </div>

    </div>

</div>

@endsection
