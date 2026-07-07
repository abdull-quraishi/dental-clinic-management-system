@extends('layouts.app')

@section('content')

<div class="max-w-4xl p-6 mx-auto bg-white rounded shadow">

    <h2 class="mb-6 text-2xl font-bold">
           {{ __('messages.create_prescription') }}
    </h2>

    <form action="{{ route('doctor.prescription.store') }}"
          method="POST">

        @csrf

        <input type="hidden"
               name="patient_id"
               value="{{ $patient->patient_id }}">

        {{-- PATIENT --}}
        <div class="mb-4">

            <label class="block mb-1 font-medium">
           {{ __('messages.patient') }}
            </label>

            <input type="text"
                   readonly
                   value="{{ $patient->first_name }} {{ $patient->last_name }}"
                   class="w-full p-2 bg-gray-100 border rounded">

        </div>

        {{-- SERVICE --}}
        @php
          $user = auth()->user();

          $isGeneralDoctor = $user->hasRole('general_doctor');
         @endphp

         {{-- SERVICE --}}
         @if(!$isGeneralDoctor)

        <div class="mb-4">

            <label class="block mb-1 font-medium">
             {{ __('messages.services') }}
            </label>

            <select name="service_id"
                    class="w-full p-2 border rounded"
                    required>

                <option value="">
                    Select Service
                </option>

                @foreach($services as $service)

                    <option value="{{ $service->id }}">

                        {{ $service->name }}
                        -
                        ${{ $service->price }}

                    </option>

                @endforeach

            </select>

        </div>
        @endif

        {{-- APPOINTMENT FEE --}}
         @if($isGeneralDoctor)
        <div class="mb-4">

            <label class="block mb-1 font-medium">
             {{ __('messages.appointment_fee') }}
            </label>

            <input type="number"
                   name="appointment_fee"
                   value="10"
                   class="w-full p-2 border rounded">
         </div>
         @endif

        {{-- MEDICINES --}}
        <div class="mb-4">

            <label class="block mb-2 font-medium">
             {{ __('messages.medicines') }}
            </label>

            <div id="medicine-wrapper">

                <div class="grid grid-cols-2 gap-3 mb-3 medicine-row">

                    <select name="medicine_id[]"
                            class="p-2 border rounded">

                        <option value="">
                         {{ __('messages.select_medicine') }}
                        </option>

                        @foreach($medicines as $medicine)

                            <option value="{{ $medicine->id }}">

                                {{ $medicine->name }}
                                -
                                ${{ $medicine->price }}

                            </option>

                        @endforeach

                    </select>

                    <input type="number"
                           name="quantity[]"
                           placeholder="Quantity"
                           value="1"
                           class="p-2 border rounded">

                </div>

            </div>

            <button type="button"
                    onclick="addMedicineRow()"
                    class="px-3 py-2 mt-2 text-white bg-green-600 rounded">

               + {{ __('messages.add_medicine') }}

            </button>

        </div>

        {{-- INSTRUCTIONS --}}
        <div class="mb-4">

            <label class="block mb-1 font-medium">
             {{ __('messages.instructions') }}
            </label>

            <textarea name="instructions"
                      rows="3"
                      class="w-full p-2 border rounded"></textarea>

        </div>

        <button class="px-5 py-2 text-white bg-blue-600 rounded">

             {{ __('messages.save_prescription') }}
        </button>
      <a href="{{ route('doctor.patients') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
             {{ __('messages.cancel') }}
      </a>


    </form>

</div>

<script>

function addMedicineRow()
{
    let row = document.querySelector('.medicine-row');

    let clone = row.cloneNode(true);

    document
        .getElementById('medicine-wrapper')
        .appendChild(clone);
}

</script>

@endsection
