@extends('layouts.app')
@section('content')
    <!-- Recent Appointments -->
    <div class="p-5 bg-white rounded-lg shadow">

        <div class="flex items-center justify-between mb-4">
            <h3 class="text-lg font-semibold text-gray-700">
                {{ __('messages.pending_appointment') }}
            </h3>
        </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-left">

                <thead class="text-sm text-gray-500 border-b">
                    <tr class="py-3">
                        <th>{{ __('messages.patient') }}</th>
                        <th>{{ __('messages.doctor') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($pendingappointments as $a)

                    <tr class="border-b">

                        <td class="py-3">
                            {{ $a->patient->first_name ?? 'Unknown' }}
                        </td>

                        <td>
                            {{ $a->doctor->first_name ?? 'Not Assigned' }}
                        </td>

                        <td>
                            {{ $a->appointment_date }}
                        </td>

                        <td>
                                <span class="px-2 py-1 text-sm text-yellow-800 bg-yellow-100 rounded">
                                    Pending
                                </span>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

       {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $pendingappointments->links() }}
        </div>
@endsection

