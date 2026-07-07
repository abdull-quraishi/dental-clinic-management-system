@extends('home.main')

@section('content')

@php

    $image = $doctor->image
        ? asset('doctor_images/'.$doctor->image)
        : 'https://ui-avatars.com/api/?name='.urlencode($doctor->first_name);

@endphp

<section class="min-h-screen py-10 bg-slate-50">

    <div class="max-w-2xl px-2 py-2 mx-auto">

        <div class="overflow-hidden bg-white shadow-xl rounded-3xl">

            {{-- TOP --}}
            <div class="p-10 text-center text-white bg-gradient-to-r from-blue-700 to-cyan-600">
                <img src="{{ $image }}" class="object-cover mx-auto border-4 border-white rounded-full shadow-lg" style="width: 300px; height:300px">
                <h1 class="mt-5 text-4xl font-bold">
                    Dr.
                    {{ $doctor->first_name }}
                    {{ $doctor->last_name }}
                </h1>
                <p class="mt-2 text-blue-100">

                    {{ ucfirst($doctor->role) }}

                </p>

            </div>

            {{-- BODY --}}
            <div class="p-8">

                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">

                    <div>

                        <p class="mb-1 text-sm text-gray-500">
                            Email
                        </p>

                        <div class="p-4 rounded-xl bg-slate-100">

                            {{ $doctor->email ?? 'N/A' }}

                        </div>

                    </div>

                    <div>

                        <p class="mb-1 text-sm text-gray-500">
                            Phone
                        </p>

                        <div class="p-4 rounded-xl bg-slate-100">

                            {{ $doctor->phone_number ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="md:col-span-2">

                        <p class="mb-1 text-sm text-gray-500">
                            Address
                        </p>

                        <div class="p-4 rounded-xl bg-slate-100">

                            {{ $doctor->address ?? 'N/A' }}

                        </div>

                    </div>

                    <div class="md:col-span-2">

                        <p class="mb-1 text-sm text-gray-500">
                            Biography
                        </p>

                        <div class="p-4 leading-7 rounded-xl bg-slate-100">

                            {{ $doctor->bio ?? 'No biography added yet.' }}

                        </div>

                    </div>

                </div>

                <div class="mt-8">

                    <a href="{{ route('home.doctors') }}"
                       class="inline-block px-6 py-3 text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">

                        ← Back to Doctors

                    </a>

                </div>

            </div>

        </div>

    </div>

</section>

@endsection
