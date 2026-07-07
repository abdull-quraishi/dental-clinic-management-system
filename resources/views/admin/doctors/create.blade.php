{{-- ============================= --}}
{{-- admin/doctors/create.blade.php --}}
{{-- ============================= --}}

@extends('layouts.app')

@section('content')
<div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">
    <h2 class="mb-4 text-xl font-bold">
        {{ __('messages.add_doctor') }}
    </h2>

    @if ($errors->any())
      <div class="p-3 mb-4 text-red-700 rounded bg-red-50">
        <ul class="pl-5 list-disc">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.doctors.store') }}" method="POST" enctype="multipart/form-data" class="space-y-4">
        @csrf

        {{-- First Name --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.first_name') }}
            </label>
            <input type="text" name="first_name" value="{{ old('first_name') }}"
                class="w-full p-2 border rounded" required>
        </div>

        {{-- Last Name --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.last_name') }}
            </label>
            <input type="text" name="last_name" value="{{ old('last_name') }}"
                class="w-full p-2 border rounded">
        </div>

        {{-- Email --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.email') }}
            </label>
            <input type="email" name="email" value="{{ old('email') }}"
                class="w-full p-2 border rounded" required>
        </div>

        {{-- Password --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 font-medium">
                {{ __('messages.password') }}
                </label>
                <input type="password" name="password"
                    class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">
                {{ __('messages.confirm_password') }}
                </label>
                <input type="password" name="password_confirmation"
                    class="w-full p-2 border rounded" required>
            </div>
        </div>

         {{-- Phone --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.phone') }}
            </label>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}"
                class="w-full p-2 border rounded">
        </div>
            {{-- Address --}}
         <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.address') }}
            </label>
            <input type="text" name="address" value="{{ old('address') }}"
                class="w-full p-2 border rounded">
        </div>


        {{-- Doctor Role --}}
     <div>
          <label class="block mb-2 font-medium">
                {{ __('messages.role') }}
          </label>
          <select name="role" id="roleSelect" class="w-full p-2 border rounded" required>
              <option value="">
                {{ __('messages.select_role') }}
              </option>
              <option value="general_doctor">
                {{ __('messages.general_doctor') }}
              </option>
              <option value="filler_specialist_doctor">
                {{ __('messages.filler_specialist_doctor') }}
              </option>
              <option value="extractor_specialist_doctor">
                {{ __('messages.extractor_specialist_doctor') }}
              </option>
              <option value="cleaner_specialist_doctor">
                {{ __('messages.cleaner_specialist_doctor') }}
              </option>
              <option value="root_canal_specialist_doctor">
                {{ __('messages.root_canal_specialist_doctor') }}
              </option>
          </select>
    </div>

        {{-- Image --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.photo') }}
            </label>
            <input type="file" name="image" accept="image/*" class="w-full">
            <p class="mt-1 text-xs text-gray-400">jpg, jpeg, png — max 2MB</p>
        </div>

        {{-- Bio --}}
        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.bio') }}
            </label>
            <textarea name="bio" rows="4"
                class="w-full p-2 border rounded">{{ old('bio') }}</textarea>
        </div>

        {{-- Buttons --}}
        <div>
            <button type="submit"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                {{ __('messages.save') }}
            </button>

            <a href="{{ route('admin.doctors.index') }}"
                class="px-4 py-2 ml-2 text-white bg-red-500 rounded hover:bg-red-600">
                {{ __('messages.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
