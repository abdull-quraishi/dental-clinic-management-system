@extends('layouts.app')

@section('content')
    <div class="p-6">
      <div class="flex flex-col gap-4 mb-4 lg:flex-row lg:justify-between lg:items-center">

         <h2 class="text-2xl font-bold">
            {{ __('messages.Patients_Bills') }}
         </h2>

            <div class="flex flex-col gap-2 sm:flex-row">
         {{-- this is for search bar --}}
          <form method="GET"
             action="{{ route('doctor.all-bills') }}"
             class="flex flex-col sm:flex-row">

           <input type="text"
                  name="search"
                  value="{{ request('search') }}"
                  placeholder="Search patient, treatment, status..."
                  class="px-3 py-2 border rounded">

           <button type="submit"
                   class="px-4 py-2 text-white bg-green-600 rounded">
               {{ __('messages.search') }}
           </button>
       </form>
         {{-- this button for refresh the page --}}
         <a href="{{ route('doctor.all-bills') }}"
            class="px-4 py-2 text-white bg-gray-700 rounded">
             Refresh
         </a>

       <div class="flex flex-wrap gap-2">
        <a href="{{ route('doctor.today.bills') }}" class="px-4 py-2 text-white bg-blue-600 rounded">
            {{ __('messages.view_today_bills') }}
        </a>
        <a href="{{ route('doctor.dashboard') }}" class="px-6 py-2 text-white bg-red-600 rounded">
            {{ __('messages.dashboard') }}
        </a>
      </div>
      </div>
    </div>
      <div class="overflow-x-auto">
        <table class="w-full mt-6 bg-white rounded shadow">
            <thead class="text-white bg-blue-600">

                <tr>
                    <th class="p-3">
                     {{ __('messages.patient') }}
                    </th>
                    <th class="p-3">
                     {{ __('messages.treatment') }}
                    </th>
                    <th class="p-3">
                    {{ __('messages.amount') }}
                    </th>
                    <th class="p-3">
                   {{ __('messages.status') }}
                    </th>
                    <th class="p-3">
                   {{ __('messages.date') }}
                    </th>
                </tr>

            </thead>
            <tbody class="text-center">

                @foreach ($bills as $bill)
                    <tr class="border-b">

                        <td class="p-3">
                            {{ $bill->patient->first_name ?? '' }}
                        </td>

                        <td class="p-3">
                            {{ $bill->treatment }}
                        </td>

                        <td class="p-3">
                            ${{ $bill->amount }}
                        </td>

                        <td class="p-3">
                            {{ $bill->status }}
                        </td>

                        <td class="p-3">
                            {{ $bill->bill_date }}
                        </td>

                    </tr>
                @endforeach

            </tbody>

        </table>
      </div>
        {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $bills->links() }}
        </div>
    </div>
@endsection
