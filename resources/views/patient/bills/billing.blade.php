@extends('layouts.app')
@section('content')
<div class="max-w-4xl p-6 mx-auto">
  <div class="flex items-center justify-between mb-6">
    <h2 class="text-2xl font-bold">
        {{ __('messages.billing_title') }}
    </h2>

     <a href="{{ route('patient.dashboard') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back_to_dashboard') }}
     </a>
  </div>

  @if(isset($bills) && count($bills))

    <div class="p-4 space-y-4 bg-white rounded shadow">
      @foreach($bills as $b)

        <div class="flex items-center justify-between pb-3 border-b">
          <div>
            <div class="font-medium">{{ $b->service }}</div>
             <div class="text-sm text-gray-500">
                Doctor: {{optional($b->doctor)->first_name }} {{ optional($b->doctor)->last_name }}• {{ \Carbon\Carbon::parse($b->bill_date)->format('d M Y') }}</div>
          </div>

           <div class="text-right">
            <div class="font-semibold">{{$b->treatment}}</div>
             <div class="text-sm text-gray-500">Service: afg {{ number_format($b->service_total,2) }}</div>
          </div>

            {{-- Medicines --}}
           @if($b->prescription && $b->prescription->prescriptionItems->count())
               <div class="mt-2 space-y-1">
                   @foreach($b->prescription->prescriptionItems as $item)
                       <div class="text-sm text-gray-600">
                           💊 {{ $item->medicine->name ?? 'Medicine' }}
                           (x{{ $item->quantity }})
                           {{ number_format($item->subtotal,2) }} afg
                       </div>
                   @endforeach
               </div>
           @endif

          <div class="text-right">
            <div class="font-semibold">{{ number_format($b->amount,2) }} afg</div>
            <div class="text-sm text-gray-500">{{ $b->status }}</div>
          </div>

        </div>


      @endforeach


    </div>
  @else
    <div class="p-6 text-center text-gray-400 bg-white rounded shadow">
        {{ __('messages.no_billing_info') }}
    </div>
  @endif

  {{-- Pagination --}}
     <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
         {{ $bills->links() }}
     </div>
</div>
@endsection
