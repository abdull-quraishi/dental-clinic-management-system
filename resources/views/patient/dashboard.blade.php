@extends('layouts.app')
@section('content')
    <div class="px-4 pt-1 space-y-2">

        <!-- patient/dashboard.blade.php -->

        <div class="flex items-center justify-between px-6">
            <div>
                <h2 class="text-2xl font-bold text-gray-800">
                    {{ __('messages.dashboard_title') }}
                </h2>
                <p class="mt-1 text-gray-500">
                    {{ __('messages.dashboard_welcome', ['name' => auth()->user()->name ?? 'adil jan']) }}
                </p>
            </div>
            @if(isset($adminViewing) && $adminViewing)
             <div class="p-3 mb-4 text-blue-800 bg-blue-100 border border-blue-300 rounded">
                {{ __('messages.admin_viewing') }}
             </div>
            @endif
            <div class="text-sm text-gray-500">
               {{ __('messages.registration_date') }}
                 {{ auth()->user()->created_at ? auth()->user()->created_at->format('Y') : 'N/A' }}
            </div>

        </div>

        {{-- create appointment button --}}
       @if(isset($adminViewing) && $adminViewing)
           <div class="flex gap-3 ms-8">
               <a href="{{ route('admin.patients.appointments.create', $patientUser->id) }}"
                  class="inline-block px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                   {{ __('messages.create_appointment') }}
               </a>

               <a href="{{ route('admin.patients.index.exit-view') }}"
                  class="inline-block px-4 py-2 text-white bg-gray-700 rounded shadow ">
                   {{ __('messages.back_to_admin') }}
               </a>
           </div>
       @else
           <div class="ms-8">
               <a href="{{ route('patient.appointments.create') }}"
                  class="inline-block px-4 py-2 text-white bg-blue-600 rounded shadow hover:bg-blue-700">
                   {{ __('messages.create_appointment') }}
               </a>
           </div>
       @endif

        <!-- top card container (rounded panel like image) -->
    <div class="p-4 border border-gray-100 shadow-sm bg-gray-50 rounded-2xl">

         <!-- GRID: 4 colorful boxes -->
        <div class="grid grid-cols-1 gap-4 mb-4 sm:grid-cols-2" >
          <!-- todays appointments -->
    <a href="{{ route('patient.appointments.index') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
        <div class="relative pt-2 overflow-hidden rounded-lg shadow-md"style="background: linear-gradient(90deg,#1967d2 0%,#2a8ef7 100%);">
        <div class="p-5 text-white">{{ __('messages.manage_appointments') }} <div class="text-lg font-semibold"></div>
        <div class="mt-6 text-3xl font-bold">{{ $appointments }}</div>
        </div>
            <!-- decorative icon -->
        <div class="absolute mt-8 opacity-100 right-4 bottom-4">
                <!-- calendar icon -->
          <img src="{{ asset('images/patdashimg/appointment.png') }}" alt=""style=" width:200px; height:110px;">
        </div>
        </div>
     </a>


          <!-- medical records -->
       <a href="{{ route('patient.medical.records') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
       <div class="relative pt-2 overflow-hidden rounded-lg shadow-md"
           style="background: linear-gradient(90deg,#10b981 0%,#34d399 100%);">

           <div class="p-5 text-white">
               <div class="text-lg font-semibold">{{ __('messages.medical_records') }}</div>

               <div class="mt-6 text-3xl font-bold">
                   {{ $medicalRecordsCount }}
               </div>
           </div>
           <!-- decorative icon -->
           <div class="absolute mt-8 opacity-100 right-4 bottom-4 ">
        <img src="{{ asset('images/patdashimg/health-report.png') }}" style="width:200px; height:120px;">
           </div>

       </div>
     </a>



            {{-- total prescriptions --}}
            <a href="{{ route('patient.prescriptions') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
                <div class="relative overflow-hidden rounded-lg shadow-md"
                 style="background: linear-gradient(90deg,#ef4444 0%,#f97316 100%);">
                 <div class="p-5 text-white">
                     <div class="text-lg font-semibold">{{ __('messages.prescriptions') }}</div>
                     <div class="mt-6 text-3xl font-bold">{{ $prescriptionsCount}}</div>
                 </div>
                 <!-- decorative icon -->
                 <div class="absolute opacity-100 right-4 bottom-4">
                     <!-- calendar icon -->
                   <img src="{{ asset('images/patdashimg/prescription.Png') }}" alt="" style=" width:200px; height:130px; padding-top: 20px;">
                 </div>
               </div>
              </a>


             <!--total Billing -->
       <a href="{{ route('patient.billings') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
             <div class="relative overflow-hidden rounded-lg shadow-md"
                    style="background: linear-gradient(90deg,#0ea5e9 0%,#0284c7 100%);">
                    <div class="p-5 text-white">
                        <div class="text-lg font-semibold">{{ __('messages.billing') }}</div>
                        <div class="mt-6 text-3xl font-bold">{{ $billingCount }}</div>
                    </div>
                    <!-- decorative icon -->
                    <div class="absolute opacity-100 right-4 bottom-4">
                        <!-- calendar icon -->
                        <img src="{{ asset('images/patdashimg/bills.Png') }}" alt=""
                            style=" width:200px; height:130px; padding-top: 20px;">

                    </div>
                </div>
          </a>

            </div>


            <!-- Health Tips -->
        <div class="pt-8 mt-10 border-t border-gray-200">

            <h4 class="mb-4 text-lg font-bold text-gray-800">
                {{ __('messages.health_tips') }}
            </h4>

            <div class="flex items-center gap-4 p-2 border border-green-100 shadow-sm bg-green-50 rounded-2xl">

                <div class="flex items-center justify-center w-12 h-12 bg-white shadow rounded-xl">
                    <img src="{{ asset('images/patdashimg/check.Png') }}"
                         class="object-contain w-7 h-7">
                </div>

                <div class="flex gap-2">
                    <p class="font-semibold text-green-700">
                        {{ __('messages.health_tips_description1') }}
                    </p>

                    <p class="text-lg text-green-600">
                        {{ __('messages.health_tips_description2') }}
                    </p>
                </div>

            </div>

        </div>

        </div> <!-- panel end -->
     
    </div>
@endsection()
