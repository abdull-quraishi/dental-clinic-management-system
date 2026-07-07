@extends('home.main')

@section('content')

<section class="py-20 bg-slate-50">

    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8 lg:pt-16 lg:pb-26">

        <div class="text-center mb-14">

            <h2 class="text-4xl font-bold text-slate-900">
                {{ __('messages.doctors_title') }}
            </h2>

            <p class="mt-3 text-slate-600">
                {{ __('messages.doctors_description') }}
            </p>

        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            @forelse($doctors as $doctor)

            @php

                $image = $doctor->image
                    ? asset('doctor_images/'.$doctor->image)
                    : 'https://ui-avatars.com/api/?name='.urlencode($doctor->first_name);

                $role = ucfirst(str_replace('_', ' ', $doctor->role));

            @endphp

            <div class="p-6 text-center transition bg-white shadow-sm rounded-3xl hover:shadow-xl">

                <img
                    src="{{ $image }}"
                    class="object-cover w-32 h-32 mx-auto border-4 border-blue-100 rounded-full">

                <h3 class="mt-4 text-lg font-semibold text-slate-800">

                    Dr.
                    {{ $doctor->first_name }}
                    {{ $doctor->last_name }}

                </h3>

                <p class="text-sm text-slate-600">
                    {{ $role }}
                </p>

                <a href="{{ route('home.doctors.show', $doctor->doctor_id) }}"
                   class="inline-block px-5 py-2 mt-5 text-sm font-medium text-white transition bg-blue-600 rounded-xl hover:bg-blue-700">

                    {{ __('messages.profile') }}

                </a>

            </div>

            @empty

            <div class="col-span-4">

                <div class="p-10 text-center bg-white rounded-3xl">

                    <h3 class="text-2xl font-bold text-gray-700">
                        {{ __('messages.if_no_doctors') }}
                    </h3>

                </div>

            </div>

            @endforelse

        </div>

    </div>

</section>

@endsection
