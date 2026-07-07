   @extends('layouts.app')

   @section('content')

<!-- Recent Appointments title -->
            <div class="mt-2 mb-4">
                <h3 class="text-lg font-semibold text-gray-700">
                    {{ __('messages.recent_appointments_title') }}
                </h3>
                <p class="text-sm text-gray-500">
                    {{ __('messages.recent_appointments_description') }}
                </p>
            </div>

            <!-- Recent appointments list (cards) -->
            <div class="space-y-3">

                @foreach ($recentAppointments as $item)
                    <a href="#"
                        class="flex items-center justify-between transition bg-white rounded-lg shadow hover:shadow-lg">

                        <div class="flex items-center gap-3">

                            <div class="flex-shrink-0 w-12 h-12 p-2 overflow-hidden rounded-lg">
                             <img src="{{ asset('doctor_images/' . optional($item->doctor)->image) }}" class="object-cover rounded-lg">
                            </div>
                            <div>
                                <div class="font-semibold text-gray-700">
                                    {{ optional($item->doctor)->first_name }}
                                    <span class="text-sm text-gray-500">
                                        - {{ $item->service ?? 'Dental Service' }}
                                    </span>
                                </div>
                                <div class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($item->appointment_date)->format('d M Y h:i A') }}
                                </div>
                            </div>
                        </div>

                    </a>
                @endforeach

                {{-- Pagination --}}
               <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
                   {{ $recentAppointments->links() }}
               </div>
            </div>

   @endsection
