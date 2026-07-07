@extends('layouts.app')

@section('content')
    <div class="max-w-2xl p-6 mx-auto bg-white rounded shadow">

        <h2 class="mb-4 text-2xl font-bold">
            {{ __('messages.add_medicine') }}
        </h2>

        <form action="{{ route('admin.medicines.store') }}" method="POST">

            @csrf

            <div class="mb-4">

                <label>{{ __('messages.name') }}</label>

                <input type="text" name="name" class="w-full p-2 border rounded">

            </div>

            <div class="mb-4">

                <label>{{ __('messages.price') }}</label>

                <input type="number" step="0.01" name="price" class="w-full p-2 border rounded">

            </div>

            <div class="mb-4">

                <label>{{ __('messages.stock') }}</label>

                <input type="number" name="stock" class="w-full p-2 border rounded">

            </div>

             <div class="mb-4">

                <label>{{ __('messages.dosage') }}</label>

                <input type="number" name="dosage" class="w-full p-2 border rounded">

            </div>
             <div class="mb-4">

                <label>{{ __('messages.status') }}</label>
              <select name="status" id="" class="w-full p-2 border rounded">
                    <option value="1">{{ __('messages.active') }}</option>
                    <option value="0">{{ __('messages.inactive') }}</option>
                </select>

            </div>

            <button class="px-4 py-2 text-white bg-blue-600 rounded">
                {{ __('messages.save') }}
            </button>
      <a href="{{ route('admin.medicines.index') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back') }}
      </a>

        </form>

    </div>
@endsection
