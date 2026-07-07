@extends('home.main')

@section('content')
<section id="home" class="relative overflow-hidden bg-gradient-to-br from-blue-50 via-white to-cyan-50">
    <div class="px-4 pt-10 pb-16 mx-auto max-w-7xl sm:px-6 lg:px-8 lg:pt-16 lg:pb-28">
        <div class="grid items-center gap-10 lg:grid-cols-2">
            <div class="space-y-6">
                <span class="inline-flex items-center gap-2 px-4 py-2 text-sm font-medium text-blue-700 bg-blue-100 rounded-full">
                   {{ __('messages.tip1') }}
                </span>

                <h1 class="text-4xl font-bold leading-tight tracking-tight sm:text-5xl text-slate-900">
                    {{ __('messages.tip2') }}
                </h1>

                <p class="max-w-xl text-base sm:text-lg text-slate-600">
                    {{ __('messages.tip3') }}
                </p>

                <div class="flex flex-col gap-3 sm:flex-row">
                    <a href="{{ url('/register') }}" class="inline-flex justify-center px-6 py-3 font-semibold text-white transition bg-blue-600 shadow-lg rounded-2xl hover:bg-blue-700">
                        {{ __('messages.ragister') }}
                    </a>
                    <a href="{{ url('/services') }}" class="inline-flex justify-center px-6 py-3 font-semibold transition bg-white border rounded-2xl border-slate-300 hover:bg-slate-50">
                        {{ __('messages.our_services') }}
                    </a>
                </div>
            </div>

            <div class="relative">
                <div class="overflow-hidden bg-white border border-white shadow-2xl rounded-3xl">
                    <div id="slides" class="relative h-[280px] sm:h-[420px]">
                        <div class="absolute inset-0 transition-opacity duration-700 bg-center bg-cover opacity-100 slide" style="background-image:url('/images/3.jpg')"></div>
                        <div class="absolute inset-0 transition-opacity duration-700 bg-center bg-cover opacity-0 slide" style="background-image:url('/images/1.jpg')"></div>
                        <div class="absolute inset-0 transition-opacity duration-700 bg-center bg-cover opacity-0 slide" style="background-image:url('/images/doctor2.jpg')"></div>
                    </div>
                </div>

                <button id="prev" class="absolute px-4 py-2 text-white -translate-y-1/2 rounded-full left-3 top-1/2 bg-black/40 hover:bg-black/60">❮</button>
                <button id="next" class="absolute px-4 py-2 text-white -translate-y-1/2 rounded-full right-3 top-1/2 bg-black/40 hover:bg-black/60">❯</button>
            </div>
        </div>
    </div>
</section>

@endsection
