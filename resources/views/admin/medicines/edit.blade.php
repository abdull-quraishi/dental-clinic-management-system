@extends('layouts.app')

@section('content')

<div class="max-w-2xl p-6 mx-auto bg-white rounded shadow">

    <h2 class="mb-4 text-2xl font-bold">
        {{ __('messages.edit_medicine') }}
    </h2>

    <form action="{{ route('admin.medicines.update', $medicine->id) }}"
          method="POST">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label>{{ __('messages.name') }}</label>

            <input type="text"
                   name="name"
                   value="{{ $medicine->name }}"
                   class="w-full p-2 border rounded">

        </div>

        <div class="mb-4">
            <label>{{ __('messages.price') }}</label>

            <input type="number"
                   step="0.01"
                   name="price"
                   value="{{ $medicine->price }}"
                   class="w-full p-2 border rounded">
        </div>
         <div class="mb-4">
            <label>{{ __('messages.stock') }}</label>

            <input type="number"
                   step="0.01"
                   name="stock"
                   value="{{ $medicine->stock }}"
                   class="w-full p-2 border rounded">
        </div>
         <div class="mb-4">
            <label>{{ __('messages.dosage') }}</label>

            <input type="number"
                   step="0.01"
                   name="dosage"
                   value="{{ $medicine->dosage }}"
                   class="w-full p-2 border rounded">
        </div>

        <div class="mb-4">

            <label>{{ __('messages.status') }}</label>

            <select name="status"
                    class="w-full p-2 border rounded">

                <option value="1" {{ $medicine->status ? 'selected' : '' }}>
                    {{ __('messages.active') }}
                </option>

                <option value="0" {{ !$medicine->status ? 'selected' : '' }}>
                    {{ __('messages.inactive') }}
                </option>

            </select>

        </div>

        <button class="px-4 py-2 text-white bg-blue-600 rounded">
         {{ __('messages.update_medicine') }}
        </button>
      <a href="{{ route('admin.medicines.index') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
         {{ __('messages.back') }}
      </a>

    </form>

</div>

@endsection
