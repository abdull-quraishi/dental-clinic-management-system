{{-- ============================= --}}
{{-- admin/doctors/edit.blade.php --}}
{{-- ============================= --}}

@extends('layouts.app')

@section('content')
<div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">
    <h2 class="mb-4 text-xl font-bold">{{ __('messages.edit_doctor') }}</h2>

    @if ($errors->any())
      <div class="p-3 mb-4 text-red-700 rounded bg-red-50">
        <ul class="pl-5 list-disc">
          @foreach ($errors->all() as $e)
            <li>{{ $e }}</li>
          @endforeach
        </ul>
      </div>
    @endif

    <form action="{{ route('admin.doctors.update', $doctor->doctor_id) }}"
          method="POST"
          enctype="multipart/form-data"
          class="space-y-4">
        @csrf
        @method('PUT')

        {{-- First Name --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.first_name') }}</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $doctor->first_name) }}"
                   class="w-full p-2 border rounded"
                   >
        </div>
        {{-- last name --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.last_name') }}</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $doctor->last_name) }}"
                   class="w-full p-2 border rounded"
                   >
        </div>

        {{-- Email --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.email') }}</label>
            <input type="email"
                   name="email"
                   value="{{ old('email', $doctor->email) }}"
                   class="w-full p-2 border rounded"
                   required>
        </div>

        {{-- Password --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 font-medium">{{ __('messages.password') }} ({{ __('messages.leave_password_blank') }})</label>
                <input type="password"
                       name="password"
                       class="w-full p-2 border rounded">
            </div>

            <div>
                <label class="block mb-2 font-medium">{{ __('messages.confirm_password') }}</label>
                <input type="password"
                       name="password_confirmation"
                       class="w-full p-2 border rounded">
            </div>
        </div>

        {{-- Doctor Role --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.role') }}</label>
            <select name="role" required class="w-full p-2 border rounded">
                @php
                    $currentRole = $doctor->user->getRoleNames()->first();
                @endphp
                <option value="">{{ __('messages.select_role') }}</option>
                <option value="general_doctor" {{ $currentRole == 'general_doctor' ? 'selected' : '' }}>{{ __('messages.general_doctor') }}</option>
                <option value="filler_specialist_doctor" {{ $currentRole == 'filler_specialist_doctor' ? 'selected' : '' }}>{{ __('messages.filler_specialist_doctor') }}</option>
                <option value="extractor_specialist_doctor" {{ $currentRole == 'extractor_specialist_doctor' ? 'selected' : '' }}>{{ __('messages.extractor_specialist_doctor') }}</option>
                <option value="cleaner_specialist_doctor" {{ $currentRole == 'cleaner_specialist_doctor' ? 'selected' : '' }}>{{__('messages.cleaner_specialist_doctor')}}</option>
                <option value="root_canal_specialist_doctor" {{ $currentRole == 'root_canal_specialist_doctor' ? 'selected' : '' }}>{{ __('messages.root_canal_specialist_doctor') }}</option>
            </select>
        </div>

        {{-- Phone --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.phone') }}</label>
            <input type="text"
                   name="phone_number"
                   value="{{ old('phone_number', $doctor->phone_number) }}"
                   class="w-full p-2 border rounded">
        </div>
            {{-- Address --}}
         <div>
            <label class="block mb-2 font-medium">{{ __('messages.address') }}</label>
            <input type="text"
                   name="address"
                   value="{{ old('address', $doctor->address) }}"
                   class="w-full p-2 border rounded">
        </div>

        {{-- Photo --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.photo') }}</label>
            <input type="file" name="image" accept="image/*" class="w-full">
        </div>

        {{-- Bio --}}
        <div>
            <label class="block mb-2 font-medium">{{ __('messages.bio') }}</label>
            <textarea name="bio" rows="4"
                class="w-full p-2 border rounded">{{ old('bio', $doctor->bio) }}</textarea>
        </div>

        {{-- Buttons --}}
        <div>
            <button type="submit"
                class="px-4 py-2 text-white bg-blue-600 rounded hover:bg-blue-700">
                {{ __('messages.update_doctor') }}
            </button>

            <a href="{{ route('admin.doctors.index') }}"
               class="px-4 py-2 ml-2 text-white bg-red-500 rounded hover:bg-red-600">
               {{ __('messages.cancel') }}
            </a>
        </div>
    </form>
</div>
@endsection
