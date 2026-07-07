@extends('home.main')

@section('content')

<section class="py-20 bg-gradient-to-b from-white to-blue-50">

    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

        <div class="text-center mb-14">

            <h2 class="text-4xl font-bold text-slate-900">
                {{ __('messages.services_title') }}
            </h2>

            <p class="mt-3 text-slate-600">
                {{ __('messages.services_description') }}
            </p>

        </div>

        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">

            @forelse($services as $service)

                <div class="p-5 transition bg-white border shadow-sm group rounded-3xl hover:shadow-xl">

                    <img
                        src="{{ $service->image
                                ? asset('service_images/'.$service->image)
                                : asset('images/default-service.jpg') }}"
                        class="object-cover w-full h-48 mb-4 rounded-2xl">

                    <h3 class="text-lg font-semibold group-hover:text-blue-600">

                        {{ $service->name }}

                    </h3>

                    <p class="mt-2 text-sm text-slate-600">

                        {{ Str::limit($service->description, 80) }}

                    </p>

                    <div class="mt-4">

                        <span class="font-bold text-blue-600">

                            ${{ $service->price }}

                        </span>

                    </div>

                </div>

            @empty

                <div class="col-span-4 text-center text-gray-500">

                    {{ __('messages.if_no_services') }}

                </div>

            @endforelse

        </div>

    </div>

</section>

@endsection
