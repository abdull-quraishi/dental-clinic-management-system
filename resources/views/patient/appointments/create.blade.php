@extends('layouts.app')

@section('content')

    <div class="max-w-4xl mx-auto">

        <!-- Header -->
        <div class="mb-1 overflow-hidden shadow-xl rounded-3xl bg-gradient-to-r from-blue-600 to-blue-800">
            <div class="p-2 sm:p-4">
                <div class="flex flex-col gap-5 sm:flex-row sm:items-center sm:justify-between">

                    <div class="flex items-center gap-2">
                        <div class="flex items-center justify-center flex-shrink-0 h-26 w-26 bg-white/15 rounded-2xl backdrop-blur-md">
                            <svg width="64" height="64" viewBox="0 0 24 24" fill="none" xmlns="http://www.w3.org/2000/svg">
                                <path d="M12 2L3 6.5V12.5C3 17.75 6.64 22.68 12 23.95C17.36 22.68 21 17.75 21 12.5V6.5L12 2Z" fill="white"/>
                                <path d="M12 6.2C12.55 6.2 13 6.65 13 7.2V10H15.8C16.35 10 16.8 10.45 16.8 11V13C16.8 13.55 16.35 14 15.8 14H13V16.8C13 17.35 12.55 17.8 12 17.8H10C9.45 17.8 9 17.35 9 16.8V14H6.2C5.65 14 5.2 13.55 5.2 13V11C5.2 10.45 5.65 10 6.2 10H9V7.2C9 6.65 9.45 6.2 10 6.2H12Z" fill="#0284C7"/>
                            </svg>
                        </div>

                        <div class="text-white-700">
                            <h3 class="text-3xl font-bold sm:text-xl">
                                {{ __('messages.create_appointment_title') }}
                            </h3>
                            <p class="text-sm text-cyan-100 sm:text-base">
                                {{ __('messages.create_appointment_description') }}
                            </p>
                        </div>
                    </div>

                    <div class="flex items-center gap-3 text-xs text-white/90">
                        <div class="py-2 rounded-lg bg-white/10 backdrop-blur-md">
                            {{ __('messages.clinic_hours') }}
                        </div>
                    </div>

                </div>
            </div>
        </div>

        <!-- Form Card -->
        <div class="overflow-hidden bg-white border border-gray-100 shadow-2xl rounded-3xl">
            <div class="p-2 sm:p-6">

                @if ($errors->any())
                    <div class="p-4 mb-6 border border-red-200 rounded-2xl bg-red-50">
                        <div class="flex items-center gap-2 mb-2">
                            <div class="flex items-center justify-center w-8 h-8 text-red-600 bg-red-100 rounded-full">!</div>
                         <ul class="pl-5 space-y-1 text-sm text-red-700 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        </div>

                    </div>
                @endif

                <form action="{{ route('patient.appointments.store') }}" method="POST" enctype="multipart/form-data" class="space-y-3">
                    @csrf

                    <!-- Doctor Selection -->
                    <div class="px-2 py-1 border border-gray-200 shadow-sm bg-gray-50 rounded-2xl">
                        <label class="block mb-1 text-sm font-semibold text-gray-700">
                            {{ __('messages.select_doctor') }}
                        </label>

                        <select
                         id="doctor_id"
                            name="doctor_id"
                            required
                            class="w-full px-2 py-2 transition bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            <option value=""> {{ __('messages.select_general_doctor') }} </option>

                            @forelse($doctors as $doctor)
                                <option value="{{ $doctor->doctor_id }}" {{ old('doctor_id') == $doctor->doctor_id ? 'selected' : '' }}>
                                    Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->specialty }}
                                </option>
                            @empty
                                <option value=""> {{ __('messages.if-no-general_doctor') }} </option>
                            @endforelse
                        </select>

                        @error('doctor_id')
                            <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>


                    <!-- Date & Time -->
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div class="px-2 py-1 border border-gray-200 shadow-sm bg-gray-50 rounded-2xl">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.date_label') }}
                            </label>
                            <input
                                type="date"
                                 id="appointment_date"
                                name="appointment_date"
                                required
                                value="{{ old('appointment_date') }}"
                                class="w-full px-2 py-2 transition bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            >
                        </div>

                        <div class="px-2 py-1 border border-gray-200 shadow-sm bg-gray-50 rounded-2xl">
                            <label class="block mb-2 text-sm font-semibold text-gray-700">
                                {{ __('messages.time_label') }}
                            </label>

                            <select
                               id="appointment_time"
                                name="appointment_time"
                                required
                                class="w-full px-2 py-2 transition bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                                <option value=""> {{ __('messages.select_available_slot') }} </option>

                                @php
                                    $start = \Carbon\Carbon::createFromTime(8, 0);
                                    $end = \Carbon\Carbon::createFromTime(20, 0);
                                @endphp

                                @while($start < $end)
                                    @if(!($start->between(
                                        \Carbon\Carbon::createFromTime(12,30),
                                        \Carbon\Carbon::createFromTime(13,30)
                                    )))
                                        <option value="{{ $start->format('H:i') }}" {{ old('appointment_time') == $start->format('H:i') ? 'selected' : '' }}>
                                            {{ $start->format('h:i A') }}
                                        </option>
                                    @endif

                                    @php
                                        $start->addMinutes(15);
                                    @endphp
                                @endwhile
                            </select>

                            {{-- this js code is for hide the selected time slot --}}
                             <script>
                               function loadAvailableSlots() {

                                   let doctorId = document.getElementById('doctor_id').value;
                                   let date = document.getElementById('appointment_date').value;

                                   if (!doctorId || !date) {
                                       return;
                                   }

                                   fetch(
                                       "{{ route('patient.available.slots') }}?doctor_id="
                                       + doctorId +
                                       "&appointment_date="
                                       + date
                                   )
                                   .then(response => response.json())
                                   .then(bookedSlots => {

                                       let select = document.getElementById('appointment_time');

                                       let allSlots = [];

                                       for(let h = 8; h < 20; h++) {

                                           for(let m = 0; m < 60; m += 15) {

                                               let hour = String(h).padStart(2,'0');
                                               let minute = String(m).padStart(2,'0');

                                               let slot = hour + ':' + minute;

                                               if(slot >= '12:30' && slot < '13:30'){
                                                   continue;
                                               }

                                               allSlots.push(slot);
                                           }
                                       }

                                       select.innerHTML =`<option value="">{{ __("messages.select_available_slot") }}</option>`;

                                       allSlots.forEach(slot => {

                                           if(!bookedSlots.includes(slot)) {

                                               let option = document.createElement('option');

                                               option.value = slot;

                                               let d = new Date(
                                                   '2000-01-01 ' + slot
                                               );

                                               option.text =
                                                   d.toLocaleTimeString([], {
                                                       hour:'2-digit',
                                                       minute:'2-digit'
                                                   });

                                               select.appendChild(option);
                                           }
                                       });

                                   });

                               }

                               document.getElementById('doctor_id')
                                   .addEventListener('change', loadAvailableSlots);

                               document.getElementById('appointment_date')
                                   .addEventListener('change', loadAvailableSlots);

                               </script>

                            <p class="mt-2 text-xs text-gray-500">
                                {{ __('messages.select_empty_time') }}
                            </p>
                        </div>
                    </div>


                    <!-- Priority -->
                    <div class="px-2 py-1 border border-gray-200 shadow-sm bg-gray-50 rounded-2xl">
                        <label class="block mb-1 text-sm font-semibold text-gray-700">
                            {{ __('messages.priority') }}
                        </label>
                        <select
                            name="priority"
                            class="w-full px-2 py-2 transition bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none">

                            <option value="Low" {{ old('priority', 'Low') == 'Low' ? 'selected' : '' }}>
                                {{ __('messages.priority_low') }}
                            </option>
                            <option value="Medium" {{ old('priority') == 'Medium' ? 'selected' : '' }}>
                                {{ __('messages.priority_medium') }}
                            </option>
                            <option value="Critical" {{ old('priority') == 'Critical' ? 'selected' : '' }}>
                                {{ __('messages.priority_high') }}
                            </option>
                        </select>
                    </div>

                    <!-- Notes -->
                    <div class="px-2 py-1 border border-gray-200 shadow-sm bg-gray-50 rounded-2xl">
                        <label class="block mb-1 text-sm font-semibold text-gray-700">
                            {{ __('messages.describe_issue') }}
                        </label>

                        <textarea
                            name="notes"
                            rows="3"
                            class="w-full px-2 py-2 transition bg-white border border-gray-300 rounded-xl focus:border-blue-500 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                            placeholder="{{ __('messages.describe_issue_placeholder') }}">{{ old('notes') }}</textarea>
                    </div>

                    <!-- Buttons -->
                    <div class="flex flex-col gap-3 sm:flex-row sm:justify-end">

                         <button type="submit"
                            class="inline-flex items-center justify-center px-4 py-2 font-semibold text-white transition shadow-lg bg-gradient-to-r from-blue-600 to-cyan-600 rounded-xl hover:from-blue-700 hover:to-cyan-700">
                            {{ __('messages.submit_appointment') }}
                        </button>

                        <a href="{{ route('patient.dashboard') }}"
                           class="inline-flex items-center justify-center px-4 font-semibold text-white transition bg-red-500 shadow-lg rounded-xl hover:bg-gray-800">
                            {{ __('messages.back') }}
                        </a>

                    </div>

                </form>
            </div>
        </div>
    </div>
</div>

@endsection
