@extends('layouts.app')

@section('content')
<div class="p-4 pt-1 space-y-4 ">

  <!-- Header -->
  <div class="flex flex-col gap-4 md:flex-row md:items-center md:justify-between">
    <div>
      <h1 class="text-xl font-bold text-gray-800 sm:text-2xl">
        {{ __('messages.doctor_dashboard') }}
      </h1>
     <p class="mt-1 text-sm text-gray-500 sm:text-base">
     {{ __('messages.welcome_back') }}, {{ $doctor ? ($doctor->first_name . ' ' . $doctor->last_name) : auth()->user()->name }}!
     </p>
    </div>

  <div class="flex flex-wrap items-center gap-2">
    <a href="{{ route('doctor.dashboard') }}"class="px-3 py-2 text-sm bg-white border rounded sm:px-4">
        {{ __('messages.refresh') }}
    </a>
  </div>
  </div>


  <!-- top card container (rounded panel like image) -->
    <div class="p-4 border border-gray-100 shadow-sm bg-gray-50 rounded-2xl">

  <!-- GRID: 4 colorful boxes -->
         <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2">
          <!-- todays appointments -->
      <a href="{{ route('doctor.todays-app') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
        <div class="relative overflow-hidden rounded-lg shadow-md"style="background: linear-gradient(90deg,#1967d2 0%,#2a8ef7 100%);">
        <div class="p-5 text-white">
        <div class="text-lg font-semibold">{{ __('messages.todays_appointments') }}</div>
        <div class="mt-6 text-3xl font-bold">{{ $todayCount }}</div>
        </div>
            <!-- decorative icon -->
        <div class="absolute opacity-100 right-4 bottom-4">
                <!-- calendar icon -->
          <img src="{{ asset('images/docdashimg/todaysapp.png') }}" alt=""style=" width:200px; height:110px;"> </div>
        </div>
     </a>

          <!-- pending appointments -->
        <a href="{{ route('doctor.pending-app') }}" class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
       <div class="relative pt-2 overflow-hidden rounded-lg shadow-md"
           style="background: linear-gradient(90deg,#10b981 0%,#34d399 100%);">

           <div class="p-5 text-white">
               <div class="text-lg font-semibold">{{ __('messages.pending_appointments') }}</div>

               <div class="mt-6 text-3xl font-bold">
                   {{ $pendingCount }}
               </div>
           </div>
           <!-- decorative icon -->
           <div class="absolute mt-8 opacity-100 right-4 bottom-4 ">
        <img src="{{ asset('images/docdashimg/pendapp.png') }}" style="width:200px; height:120px;">
           </div>

       </div>
     </a>


            {{-- total patients --}}
        <a href="{{ route('doctor.patients') }}"class="block px-3 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
                <div class="relative overflow-hidden rounded-lg shadow-md"
                 style="background: linear-gradient(90deg,#ef4444 0%,#f97316 100%);">
                 <div class="p-5 text-white">
                     <div class="text-lg font-semibold">{{ __('messages.total_patients') }}</div>
                     <div class="mt-6 text-3xl font-bold">{{ $totalPatients}}</div>
                 </div>
                 <!-- decorative icon -->
                 <div class="absolute opacity-100 right-4 bottom-4">
                     <!-- calendar icon -->
                   <img src="{{ asset('images/docdashimg/patient2.Png') }}" alt="" style=" width:200px; height:130px; padding-top: 20px;">
                 </div>
               </div>
        </a>


             <!--total Billing -->
        <a href="{{ route('doctor.patients') }}"class="block px-3 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
             <div class="relative overflow-hidden rounded-lg shadow-md"
                    style="background: linear-gradient(90deg,#0ea5e9 0%,#0284c7 100%);">
                    <div class="p-5 text-white">
                        <div class="text-lg font-semibold">{{ __('messages.billing') }}</div>
                        <div class="mt-6 text-3xl font-bold">{{ $billCount }}</div>
                    </div>
                    <!-- decorative icon -->
                    <div class="absolute opacity-100 right-4 bottom-4">
                        <!-- calendar icon -->
                        <img src="{{ asset('images/docdashimg/bill.Png') }}" alt=""
                            style=" width:200px; height:130px; padding-top: 20px;">

                    </div>
                </div>
           </a>

      </div>
     </div>


  {{-- Notifications --}}
   @if(isset($notifications) && $notifications->count())
    <div class="p-2 bg-white rounded-lg shadow">
        <div class="flex items-center justify-between">
            <h3 class="text-lg font-semibold text-gray-800">
                {{ __('messages.notifications') }}
            </h3>
            <span class="px-2 py-1 text-xs text-white bg-red-500 rounded">
                {{ $notificationCount ?? 0 }}
            </span>
        </div>

        <div class="space-y-1">
            @foreach($notifications as $note)
                <div class="flex justify-between p-2 border rounded-lg bg-gray-50 ">
                    <div class="font-medium text-gray-800">
                        {{ $note['title'] }}
                    </div>
                    <div class="text-sm text-gray-500">
                        Patient: {{ $note['patient'] }}
                    </div>
                    <div class="text-xs text-gray-400">
                        {{ \Carbon\Carbon::parse($note['date'])->format('d M Y, h:i A') }}
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endif

  </div>

</div>


@endsection
