@extends('layouts.app')
@section('content')
    <!-- Recent Appointments -->
    <div class="p-5 bg-white rounded-lg shadow">

        <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">

       <h3 class="text-lg font-semibold text-gray-700">
           {{ __('messages.today_appointment') }}
       </h3>

       <div class="flex flex-col gap-2 sm:flex-row">

        <form method="GET"
                  action="{{ route('admin.todayappointment') }}"
                  class="flex flex-col sm:flex-row">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search patient or doctor..."
                       class="px-3 py-2 border rounded">

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-600 rounded">
                    {{ __('messages.search') }}
                </button>

            </form>

            <a href="{{ route('admin.todayappointment') }}"
               class="px-4 py-2 text-center text-white bg-gray-700 rounded">
                {{ __('messages.refresh') }}
            </a>

            <a href="{{ route('admin.dashboard') }}"
               class="px-4 py-2 text-center text-white bg-blue-600 rounded">
                {{ __('messages.dashboard') }}
            </a>

        </div>

    </div>

        <div class="overflow-x-auto">

            <table class="min-w-full text-left">

                <thead class="text-sm text-gray-500 border-b">
                    <tr>
                        <th class="py-3">{{ __('messages.patient') }}</th>
                        <th>{{ __('messages.doctor') }}</th>
                        <th>{{ __('messages.date') }}</th>
                        <th>{{ __('messages.status') }}</th>
                    </tr>
                </thead>

                <tbody>

                    @foreach($todayappointments as $a)

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
                            @if($a->status == 'Pending')
                                <span class="px-2 py-1 text-sm text-yellow-800 bg-yellow-100 rounded">
                                    Pending
                                </span>
                            @elseif($a->status == 'Approved')
                                <span class="px-2 py-1 text-sm text-green-800 bg-green-100 rounded">
                                    Approved
                                </span>
                            @elseif($a->status == 'Rejected')
                                <span class="px-2 py-1 text-sm text-red-800 bg-red-100 rounded">
                                    Rejected
                                </span>
                             @elseif($a->status == 'Completed')
                                <span class="px-2 py-1 text-sm text-gray-700 bg-gray-100 rounded">
                                    Completed
                                </span>
                            @endif
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

        </div>

    </div>

       {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $todayappointments->links() }}
        </div>
@endsection

