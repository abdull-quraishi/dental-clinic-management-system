@extends('layouts.app')

@section('content')

<div class="max-w-2xl px-6 py-4 mx-auto bg-white rounded shadow">

    <h2 class="mb-4 text-2xl font-bold">
        {{ __('messages.edit_service') }}
    </h2>

    <form action="{{ route('admin.services.update', $service->id) }}"
          method="POST" enctype="multipart/form-data">

        @csrf
        @method('PUT')

        <div class="mb-4">

            <label>{{ __('messages.name') }}</label>

            <input type="text"
                   name="name"
                   value="{{ $service->name }}"
                   class="w-full p-2 border rounded">

        </div>

        <div class="mb-4">

            <label>{{ __('messages.price') }}</label>

            <input type="number"
                   step="0.01"
                   name="price"
                   value="{{ $service->price }}"
                   class="w-full p-2 border rounded">

        </div>

        <div>

          <label>{{ __('messages.photo') }}</label>

          <input type="file"
                 name="image"
                 class="w-full p-2 border rounded">

          @if($service->image)

              <img src="{{ asset('service_images/'.$service->image) }}"
                   alt="service image"
                   class="object-cover w-10 h-10 mt-3 rounded">

          @endif

         </div>

         <div class="mb-4">

            <label>{{ __('messages.description') }}</label>

            <textarea name="description"
                      class="w-full p-2 border rounded">{{ $service->description }}</textarea>
        </div>

        <div class="mb-4">

            <label>{{ __('messages.service') }}</label>

            <select name="status"
                    class="w-full p-2 border rounded">

                <option value="1" {{ $service->status ? 'selected' : '' }}>
                    {{ __('messages.active') }}
                </option>

                <option value="0" {{ !$service->status ? 'selected' : '' }}>
                    {{ __('messages.inactive') }}
                </option>

            </select>

        </div>

        <button class="px-4 py-2 text-white bg-blue-600 rounded">
            {{ __('messages.update_service') }}
        </button>
      <a href="{{ route('admin.services.index') }}" class="px-6 py-2 ml-4 text-white bg-red-500 rounded">
        {{ __('messages.back') }}
      </a>


    </form>

</div>

@endsection
