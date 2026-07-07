@extends('layouts.app')

@section('content')

    <div class="max-w-3xl p-6 mx-auto">

        <div class="flex items-center justify-between mb-6">

            <h2 class="text-2xl font-bold text-gray-800">
                {{ __('messages.create_diagnosis') }}
            </h2>

        </div>


        @if (session('success'))
            <div class="p-3 mb-4 text-green-700 bg-green-100 rounded">
                {{ session('success') }}
            </div>
        @endif

        @if ($errors->any())
            <div class="p-3 mb-4 text-red-700 bg-red-100 rounded">

                <ul>

                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>

            </div>
        @endif

        <div class="p-6 bg-white shadow rounded-xl">

            <h3 class="mb-4 text-lg font-semibold">
                {{ __('messages.diagnosis_and_treatment') }}
            </h3>

            <form method="POST" action="{{ route('doctor.diagnosis.store') }}" class="space-y-4">

                @csrf

                <input type="hidden" name="patient_id"value="{{ $patient_id }}">
                <div>

                    <label class="block text-sm font-medium text-gray-700">
                    {{ __('messages.diagnosis') }}
                    </label>

           <textarea name="diagnosis" rows="4" required class="w-full px-3 py-2 mt-1 border rounded">{{old('diagnosis')}}</textarea>
                </div>

                <div>
                    <label class="block text-sm font-medium text-gray-700">
                   {{ __('messages.treatment') }}
                    </label>

                    <textarea name="treatment_plan" rows="3" class="block w-full px-3 py-2 mt-1 border rounded">{{ old('treatment_plan') }}</textarea>
                </div>

                <div>

                    <label class="block text-sm font-medium text-gray-700">
                {{ __('messages.treatment_status') }}
                    </label>

                    <select name="treatment_status" class="block w-full px-3 py-2 mt-1 border rounded" required>
                        <option value="Waiting">{{ __('messages.waiting') }}</option>
                        <option value="In Treatment">{{ __('messages.intreatment') }}</option>
                        <option value="Healed">{{ __('messages.healed') }} </option>
                    </select>
                </div>

                <div class="flex items-center gap-3">
                    <button type="submit" class="px-4 py-2 text-white bg-green-600 rounded">
                       {{ __('messages.save') }}
                    </button>
                    <a href="{{ route('doctor.patients') }}" class="px-3 py-2  text-white bg-red-500 rounded">
                       {{ __('messages.cancel') }}
                    </a>
                </div>
            </form>
        </div>
    </div>

@endsection
