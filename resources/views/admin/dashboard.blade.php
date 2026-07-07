@extends('layouts.app')

@section('content')
<div class="px-4 space-y-2 overflow-x-hidden">

    <!-- Header -->
    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
            <h2 class="text-xl font-bold text-gray-800 sm:text-2xl">
                {{ __('messages.admin_title') }}
            </h2>
            <p class="mt-1 text-sm text-gray-500 sm:text-base">
                {{ __('messages.admin_description') }}
            </p>
    </div>


      <!-- top card container (rounded panel like image) -->
    <div class="p-4 border border-gray-100 shadow-sm bg-gray-50 rounded-2xl">
       <!-- GRID: 4 colorful boxes -->
         <div class="grid grid-cols-1 gap-3 pb-2 sm:grid-cols-2" >
  <!-- total users -->
     <a href="{{ route('admin.users') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
        <div class="relative overflow-hidden rounded-lg shadow-md"style="background: linear-gradient(90deg,#1967d2 0%,#2a8ef7 100%);">
        <div class="p-5 text-white">
        <div class="text-lg font-semibold">
                {{ __('messages.total_user') }}
         </div>
        <div class="mt-6 text-3xl font-bold">{{ $totalUsers }}</div>
        </div>
            <!-- decorative icon -->
        <div class="absolute opacity-100 right-4 bottom-4">
                <!-- calendar icon -->
          <img src="{{ asset('images/admindashimg/users.png') }}" alt=""style=" width:200px; height:110px;">
        </div>
        </div>
     </a>

          <!-- total doctors -->
      <a href="{{ route('admin.doctors.index') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
       <div class="relative pt-2 overflow-hidden rounded-lg shadow-md"
           style="background: linear-gradient(90deg,#10b981 0%,#34d399 100%);">

           <div class="p-5 text-white">
               <div class="text-lg font-semibold">
                {{ __('messages.total_doctor') }}
               </div>

               <div class="mt-6 text-3xl font-bold">
                   {{ $totalDoctors }}
               </div>
           </div>
           <!-- decorative icon -->
           <div class="absolute mt-8 opacity-100 right-4 bottom-4 ">
        <img src="{{ asset('images/admindashimg/doctors.png') }}" style="width:200px; height:120px;">
           </div>

       </div>
     </a>

       {{--  Todays appointments --}}
            <a href="{{ route('admin.todayappointment') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
                <div class="relative overflow-hidden rounded-lg shadow-md"
                 style="background: linear-gradient(90deg,#ef4444 0%,#f97316 100%);">
                 <div class="p-5 text-white">
                     <div class="text-lg font-semibold">
                          {{ __('messages.todays_appointments') }}
                     </div>
                     <div class="mt-6 text-3xl font-bold">{{ $appointmentsToday}}</div>
                 </div>
                 <!-- decorative icon -->
                 <div class="absolute opacity-100 right-4 bottom-4">
                     <!-- calendar icon -->
                   <img src="{{ asset('images/admindashimg/todayapp.Png') }}" alt="" style=" width:200px; height:130px; padding-top: 20px;">
                 </div>
               </div>
              </a>

             <!--total pending appontments -->
         <a href="{{ route('admin.pending-appointments') }}"  class="block px-4 py-2 text-sm rounded sm:text-base hover:bg-gray-700">
             <div class="relative overflow-hidden rounded-lg shadow-md"
                    style="background: linear-gradient(90deg,#0ea5e9 0%,#0284c7 100%);">
                    <div class="p-5 text-white">
                        <div class="text-lg font-semibold">
                          {{ __('messages.pending_appointments') }}
                        </div>
                        <div class="mt-6 text-3xl font-bold">{{ $pendingCount }}</div>
                    </div>
                    <!-- decorative icon -->
                    <div class="absolute opacity-100 right-4 bottom-4">
                        <!-- calendar icon -->
                        <img src="{{ asset('images/admindashimg/pendapp.Png') }}" alt=""
                            style=" width:200px; height:140px; padding-top: 20px;">

                    </div>
                </div>
          </a>

     </div>
     </div>


    <!-- RECENT USERS -->
    <div class="px-4 py-2 overflow-hidden bg-white rounded-lg shadow">
        <h3 class="mb-3 font-semibold text-gray-800">
                          {{ __('messages.recent_user') }}
        </h3>

        <div class="w-full overflow-x-auto">
            <table class="w-full text-sm table-fixed">
                <thead>
                    <tr class="text-left text-gray-500 border-b">
                        <th class="pb-2 pr-4">
                          {{ __('messages.name') }}
                        </th>
                        <th class="pb-2 pr-4">
                          {{ __('messages.email') }}
                        </th>
                        <th class="pb-2">
                          {{ __('messages.user_role') }}
                        </th>
                    </tr>
                </thead>

                <tbody>
                    @foreach($users as $u)
                        <tr class="border-t">
                            <td class="py-2 pr-4 truncate">{{ $u->name }}</td>
                            <td class="py-2 pr-4 truncate">{{ $u->email }}</td>
                            <td class="py-2 truncate">{{ $u->roles->first()->name ?? 'N/A' }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

</div>
@endsection
