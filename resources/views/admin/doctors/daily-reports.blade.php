@extends('layouts.app')

@section('content')
<div class="p-6">
    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
        <div>
            <h2 class="text-2xl font-bold text-gray-800">{{ __('messages.doctor_daily_reports') }}</h2>
            <p class="text-sm text-gray-500 my-3">{{ __('messages.report_description') }}</p>

            <div class="flex flex-col gap-2 sm:flex-row">

           <form method="GET"
                 action="{{ route('admin.doctor.reports') }}"
                 class="flex flex-col sm:flex-row">

               <input type="text"
                      name="search"
                      value="{{ request('search') }}"
                      placeholder="Search doctor..."
                      class="px-3 py-2 border rounded">

               <button type="submit"
                       class="px-4 py-2 text-white bg-green-600 rounded">
                   {{ __('messages.search') }}
               </button>

           </form>

           <a href="{{ route('admin.doctor.reports') }}"
              class="px-4 py-2 text-center text-white bg-gray-700 rounded">
                              {{ __('messages.refresh') }}
           </a>

      </div>
       </div>
    </div>

    <div class="overflow-x-auto bg-white rounded-lg shadow">
        <table class="w-full text-sm">
            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 text-left">{{ __('messages.doctor') }}</th>
                    <th class="p-3 text-left">{{ __('messages.appointment') }}</th>
                    <th class="p-3 text-left">{{ __('messages.patient') }}</th>
                    <th class="p-3 text-left">{{ __('messages.prescriptions') }}</th>
                    <th class="p-3 text-left">{{ __('messages.bills') }}</th>
                    <th class="p-3 text-left">{{ __('messages.amount') }}</th>
                    <th class="p-3 text-left">{{ __('messages.actions') }}</th>
                </tr>
            </thead>
            <tbody>
                @foreach($doctors as $row)
                    <tr class="border-t">
                        <td class="p-3">
                            Dr. {{ $row['doctor']->first_name }} {{ $row['doctor']->last_name }}
                        </td>
                        <td class="p-3">{{ $row['appointments_today'] }}</td>
                        <td class="p-3">{{ $row['patients_today'] }}</td>
                        <td class="p-3">{{ $row['prescriptions_today'] }}</td>
                        <td class="p-3">{{ $row['bills_today'] }}</td>
                        <td class="p-3">${{ number_format($row['total_amount_today'], 2) }}</td>
                        <td class="p-3">
                            <a href="{{ route('admin.doctor.reports.show', $row['doctor']->doctor_id) }}"
                               class="text-blue-600 hover:underline">
                                View Report
                            </a>
                        </td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $doctors->links() }}
        </div>
</div>
@endsection
