@extends('layouts.app')

@section('content')
<div class="max-w-2xl p-6 mx-auto bg-white rounded shadow">

    <h2 class="mb-4 text-xl font-semibold">{{ __('messages.create_appointment') }}</h2>

    @if ($errors->any())
      <div class="p-3 mb-4 text-red-700 rounded bg-red-50">
        <ul class="pl-5 list-disc">
          @foreach ($errors->all() as $error)
            <li>{{ $error }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.patients.appointments.store', $user->id) }}" method="POST" class="space-y-4">
        @csrf

        <!-- Date & Time -->
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block text-sm font-medium">{{ __('messages.date') }}</label>
                <input type="date" name="appointment_date" required class="w-full p-2 border rounded">
            </div>

            <div>
       <label class="block text-sm font-medium">{{ __('messages.time') }}</label>

       <select name="appointment_time" required class="w-full p-2 border rounded">
           <option value="">{{ __('messages.select_available_slot') }}</option>

           @php
               $start = \Carbon\Carbon::createFromTime(8, 0);
               $end = \Carbon\Carbon::createFromTime(20, 0);
           @endphp

           @while($start < $end)

               {{-- Skip lunch break --}}
               @if(!($start->between(
                   \Carbon\Carbon::createFromTime(12,30),
                   \Carbon\Carbon::createFromTime(13,30)
               )))
                   <option value="{{ $start->format('H:i') }}">
                       {{ $start->format('h:i A') }}
                   </option>
               @endif

               @php
                   $start->addMinutes(15);
               @endphp

           @endwhile

       </select>

       <p class="mt-1 text-xs text-gray-500">
            {{ __('messages.select_empty_time') }}
       </p>
    </div>
        </div>

            <!-- Doctor Selection -->
        <div>
          <label class="block text-sm font-medium">{{ __('messages.select_doctor') }}</label>

          <select name="doctor_id" required class="w-full p-2 border rounded">
              <option value="">{{ __('messages.select_general_doctor') }}</option>

              @forelse($doctors as $doctor)
                  <option value="{{ $doctor->doctor_id }}" {{ old('doctor_id') == $doctor->doctor_id ? 'selected' : '' }}>
                      Dr. {{ $doctor->first_name }} {{ $doctor->last_name }} - {{ $doctor->specialty }}
                  </option>
              @empty
                  <option value="">{{ __('messages.if-no-general_doctor') }}</option>
              @endforelse
          </select>

          @error('doctor_id')
              <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
          @enderror
       </div>

        <!-- Priority -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.priority') }}</label>
            <select name="priority" class="w-full p-2 border rounded">
                <option value="Low">{{ __('messages.priority_low') }}</option>
                <option value="Medium">{{ __('messages.priority_medium') }}</option>
                <option value="Critical">{{ __('messages.priority_high') }}</option>
            </select>
        </div>

        <!-- Notes -->
        <div>
            <label class="block text-sm font-medium">{{ __('messages.describe_issue') }}</label>
            <textarea name="notes" rows="4" class="w-full p-2 border rounded"
                placeholder="{{ __('messages.describe_issue_placeholder') }}"></textarea>
        </div>

        <!-- Buttons -->
        <div>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">
                {{ __('messages.submit_appointment') }}
            </button>

            <a href="{{ route('admin.patients.dashboard', $user->id) }}"
               class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
               {{ __('messages.back') }}
            </a>
        </div>

    </form>
</div>
@endsection
