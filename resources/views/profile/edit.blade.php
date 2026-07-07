@extends('layouts.app')

@section('content')
@php
    $roleLabels = [
        'super_admin' => 'Super Admin',
        'admin' => 'Admin',
        'general_doctor' => 'General Doctor',
        'filler_specialist_doctor' => 'Filler Specialist Doctor',
        'extractor_specialist_doctor' => 'Extractor Specialist Doctor',
        'cleaner_specialist_doctor' => 'Cleaner Specialist Doctor',
        'root_canal_specialist_doctor' => 'Root Canal Specialist Doctor',
        'patient' => 'Patient',
    ];

    $roleLabel = $roleLabels[$user->role] ?? ucfirst(str_replace('_', ' ', $user->role));
    $avatar = $user->avatar
        ? asset('storage/'.$user->avatar)
        : 'https://ui-avatars.com/api/?name='.urlencode($user->name).'&background=0D8ABC&color=fff';
@endphp

<div class="min-h-screen px-4 py-8 bg-gray-100">
    <div class="max-w-2xl mx-auto">
        <div class="overflow-hidden bg-white shadow-lg rounded-2xl">

            <div class="p-5 text-white bg-gradient-to-r from-blue-700 to-cyan-600">
                <h2 class="text-2xl font-bold">Edit Profile</h2>
                <p class="text-sm text-blue-100">{{ $roleLabel }}</p>
            </div>

            <form method="POST" action="{{ route('profile.update') }}" enctype="multipart/form-data" class="p-5 space-y-5">
                @csrf
                @method('PATCH')

                <div class="flex items-center gap-4">
                    <img src="{{ $avatar }}" alt="avatar"
                         class="object-cover w-16 h-16 border-2 border-gray-200 rounded-xl">

                    <div class="flex-1">
                        <label class="block mb-1 text-sm font-medium text-gray-700">Profile Image</label>
                        <input type="file" name="avatar"
                               class="block w-full p-2 text-sm border border-gray-300 rounded-lg">
                    </div>
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Name</label>
                    <input type="text" name="name" value="{{ old('name', $user->name) }}"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">
                </div>

                <div>
                    <label class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email', $user->email) }}"
                           class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">
                </div>

                 <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Phone</label>
                        <input type="text" name="phone_number" value="{{ old('phone_number', $user->phone_number ?? '') }}"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">
                    </div>

                    <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" rows="3"
                          class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">{{ old('address', $user->address ?? '') }}</textarea>
                    </div>

                     <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Age</label>
                        <input type="text" name="age" value="{{ old('age', $user->age ?? '') }}"
                               class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">
                    </div>

                     <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">Gender</label>
                        <select name="gender" id="gender"
                                class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">
                            <option value="">Select Gender</option>
                            <option value="male" {{ old('gender', $user->gender ?? '') === 'male' ? 'selected' : '' }}>
                                Male
                            </option>
                            <option value="female" {{ old('gender', $user->gender ?? '') === 'female' ? 'selected' : '' }}>
                                Female
                            </option>
                             <option value="other" {{ old('other', $user->gender ?? '') === 'other' ? 'selected' : '' }}>
                                Other
                            </option>
                        </select>
                    </div>

                @if($user->role === 'general_doctor' || $user->role === 'filler_specialist_doctor' || $user->role === 'extractor_specialist_doctor' || $user->role === 'cleaner_specialist_doctor' || $user->role === 'root_canal_specialist_doctor')
                     <div>
                        <label class="block mb-1 text-sm font-medium text-gray-700">BIO</label>
                        <textarea name="bio" rows="1"
                          class="w-full p-3 border border-gray-300 rounded-lg focus:border-blue-500 focus:ring-0">{{ old('bio', $user->doctor->bio ?? '') }}</textarea>
                    </div>
                @endif

                <div class="flex gap-3 pt-2">
                    <button type="submit"
                            class="rounded-lg bg-green-600 px-5 py-2.5 text-white hover:bg-green-700">
                        Update Profile
                    </button>

                    <a href="{{ route('profile.show') }}"
                       class="rounded-lg bg-gray-700 px-5 py-2.5 text-white hover:bg-gray-800">
                        Cancel
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
