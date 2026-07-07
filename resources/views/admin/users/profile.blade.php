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

            <!-- Top Header -->
            <div class="flex items-center gap-4 p-5 text-white bg-gradient-to-r from-blue-700 to-cyan-600">
                <img src="{{ $avatar }}" alt="avatar"
                     class="object-cover w-20 h-20 border-4 border-white shadow-md rounded-xl">

                <div class="min-w-0">
                    <h2 class="text-2xl font-bold truncate">{{ $user->name }}</h2>
                    <p class="text-sm text-blue-100">{{ $roleLabel }}</p>
                </div>
            </div>

            <!-- Body -->
            <div class="p-5">
                <div class="space-y-3 text-sm">
                    <div class="flex items-center justify-between pb-2 border-b">
                        <span class="text-gray-500">Full Name</span>
                        <span class="font-semibold text-gray-800">{{ $user->name }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b">
                        <span class="text-gray-500">Email</span>
                        <span class="font-semibold text-gray-800">{{ $user->email }}</span>
                    </div>

                    <div class="flex items-center justify-between pb-2 border-b">
                        <span class="text-gray-500">Role</span>
                        <span class="font-semibold text-gray-800">{{ $roleLabel }}</span>
                    </div>

                        <div class="flex items-center justify-between pb-2 border-b">
                            <span class="text-gray-500">Phone</span>
                            <span class="font-semibold text-gray-800">{{ $user->phone_number ?? 'N/A' }}</span>
                        </div>

                        <div class="flex items-start justify-between gap-4 pb-2 border-b">
                            <span class="text-gray-500">Address</span>
                            <span class="font-semibold text-right text-gray-800">
                                {{ $user->address ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="flex items-start justify-between gap-4 pb-2 border-b">
                            <span class="text-gray-500">Age</span>
                            <span class="font-semibold text-right text-gray-800">
                                {{ $user->age ?? 'N/A' }}
                            </span>
                        </div>

                        <div class="flex items-start justify-between gap-4 pb-2 border-b">
                            <span class="text-gray-500">Gender</span>
                            <span class="font-semibold text-right text-gray-800">
                                {{ $user->gender ?? 'N/A' }}
                            </span>
                        </div>

                    @if(str_contains($user->role, 'doctor'))
                        <div class="flex items-center justify-between pb-2 border-b">
                            <span class="text-gray-500">BIO</span>
                            <span class="font-semibold text-gray-800">
                                {{ $user->doctor->bio ?? 'N/A' }}
                            </span>
                        </div>
                    @endif
                </div>

                <div class="flex gap-3 mt-6">

                    <a href="{{ route('admin.users') }}"
                       class="px-4 py-2 ml-2 text-white bg-red-500 rounded">
                        Back
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
