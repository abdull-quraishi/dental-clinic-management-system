@extends('layouts.app')
@section('content')

@php
use Illuminate\Support\Str;
@endphp

<div class="p-6">

  <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:justify-between lg:items-center">

    <h2 class="text-2xl font-semibold">

        @if(request('type') == 'today')
            {{ __('messages.todays_prescriptions_bills') }}
        @else
            {{ __('messages.new_prescriptions_bills') }}
        @endif

    </h2>

    <div class="flex flex-col gap-2 sm:flex-row">

         <form method="GET"
                 action="{{ route('admin.prescriptions-bills') }}"
                 class="flex flex-col sm:flex-row">

               <input type="hidden"
                      name="type"
                      value="{{ request('type') }}">

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

           <a href="{{ route('admin.prescriptions-bills',['type'=>request('type')]) }}"
              class="px-4 py-2 text-center text-white bg-gray-700 rounded">
                               {{ __('messages.refresh') }}
           </a>

           <a href="{{ route('admin.dashboard') }}"
              class="px-4 py-2 text-center text-white bg-blue-600 rounded">
               {{ __('messages.go_to_dashboard') }}
           </a>

       </div>

   </div>

  <div class="p-4 mb-6 bg-white rounded shadow ">
    <h3 class="mb-2 font-semibold">
        @if(request('type') == 'today')
       {{ __('messages.prescriptions_today') }}
       @else
       {{ __('messages.prescriptions_latest') }}
       @endif
    </h3>

    <table class="min-w-full text-left">
      <thead class="text-sm text-gray-500">
        <tr>
          <th>{{ __('messages.id') }}</th>
          <th>{{ __('messages.patient') }}</th>
          <th>{{ __('messages.doctor') }}</th>
          <th>{{ __('messages.medicines') }}</th>
          <th>{{ __('messages.prescription_date') }}</th>
          <th>{{ __('messages.service') }}</th>
          <th>{{ __('messages.appointment') }}</th>
          <th>{{ __('messages.total_medicine') }}</th>
          <th>{{ __('messages.total') }}</th>
          <th>{{ __('messages.status') }}</th>
          <th>{{ __('messages.actions') }}</th>
        </tr>
      </thead>
      <tbody>
        @foreach($prescriptions as $p)
        <tr class="border-t">
          <td class="py-2">{{ $p->prescription_id }}</td>
          <td>{{ optional($p->patient)->first_name }} {{ optional($p->patient)->last_name }}</td>
          <td>{{ optional($p->doctor)->first_name }} {{ optional($p->doctor)->last_name }}</td>
          <td>
            @foreach($p->prescriptionItems as $item)
                <div class="mb-1">

                    {{ $item->medicine->name ?? '' }}

                    ({{ $item->quantity }})

                </div>
            @endforeach
         </td>
          <td>{{ \Carbon\Carbon::parse($p->prescription_date)->format('d M Y') }}</td>

          <td>
         @if($p->billing)
             {{ number_format($p->billing->service_total,2) }} afg
         @endif
         </td>

         <td>
         @if($p->billing)
             {{ number_format($p->billing->appointment_fee,2) }} afg
         @endif
         </td>

         <td>
         @if($p->billing)
             {{ number_format($p->billing->medicine_total,2) }} afg
         @endif
         </td>

         <td class="font-bold text-green-700">
         @if($p->billing)
             {{ number_format($p->billing->total_amount,2) }} afg
         @endif
         </td>

          <td>
            <div class="text-sm">
              Prescription: <span class="font-medium">{{ ucfirst($p->status) }}</span><br>
              Bill: <span class="font-medium">{{ $p->billing ? ucfirst($p->billing->status) : '—' }}</span>
            </div>
          </td>

          <td class="text-right">
               @if($p->billing && $p->billing->status !== 'paid')

               <form action="{{ route('admin.billings.markPaid', $p->billing->id) }}"
                     method="POST"
                     class="inline ms-2">

                   @csrf

                   <button class="px-2 py-1 text-sm text-white bg-purple-600 rounded">

                     {{ __('messages.mark_paid') }}

                   </button>

               </form>

               @endif
          </td>

           <td>
            <a href="{{ route('admin.prescription.bill.print',$p->prescription_id) }}"
               target="_blank"class="px-3 py-1 text-white bg-blue-600 rounded">
                {{ __('messages.print') }}
            </a>
           </td>
        </tr>
        @endforeach
      </tbody>
    </table>

  </div>
    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $prescriptions->links() }}
        </div>

</div>
@endsection
