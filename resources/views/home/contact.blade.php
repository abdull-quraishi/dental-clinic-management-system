@extends('home.main')

@section('content')
<section class="py-8 bg-white">
    <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:py-0 ">

        <div class="mb-8 text-center">
            <h2 class="text-4xl font-bold text-slate-900">
                {{ __('messages.contact_title') }}
            </h2>
            <p class="mt-3 text-slate-600">
                {{ __('messages.contact_description') }}
            </p>
        </div>
            @if(session('success'))
                 <div class="p-3 text-green-700 bg-green-100 rounded-xl">
                     {{ session('success') }}
                 </div>
                @endif

        <div class="grid grid-cols-1 gap-8 lg:grid-cols-2">

            <!-- Info -->
            <div class="p-8 text-white shadow-xl bg-gradient-to-br from-blue-600 to-cyan-500 rounded-3xl">

            <h3 class="mb-6 text-2xl font-bold">
                {{ __('messages.contact_info') }}
            </h3>

            <div class="space-y-6">

                <!-- Phone -->
                <div class="flex items-start gap-4">

                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-2xl">
                        📞
                    </div>

                    <div>
                        <p class="text-sm text-blue-100">
                            {{ __('messages.phone') }}
                        </p>

                        <p class="font-semibold">
                            +1 (555) 123-4567
                        </p>
                    </div>

                </div>

                <!-- Email -->
                <div class="flex items-start gap-4">

                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-2xl">
                        ✉️
                    </div>

                    <div>
                        <p class="text-sm text-blue-100">
                            {{ __('messages.email') }}
                        </p>

                        <p class="font-semibold">
                             mydentalclinic009@gmail.com
                        </p>
                    </div>

                </div>

                <!-- Address -->
                <div class="flex items-start gap-4">

                    <div class="flex items-center justify-center w-12 h-12 bg-white/20 rounded-2xl">
                        📍
                    </div>

                    <div>
                        <p class="text-sm text-blue-100">
                            {{ __('messages.address') }}
                        </p>

                        <p class="font-semibold">
                            Nangarhar, Jalalabad
                        </p>
                    </div>

                </div>

            </div>

            <!-- Bottom Box -->
            <div class="p-5 mt-8 border bg-white/10 border-white/20 rounded-2xl">

                <p class="text-sm leading-6 text-blue-50">

                    {{ __('messages.contact_bottom') }}

                </p>

            </div>

        </div>

            <!-- Form -->
        <form action="{{ route('home.contact.store') }}" method="POST"class="p-6 space-y-4 bg-white border shadow-sm rounded-3xl">
               @csrf
               <input type="text"   name="name"  placeholder="Your Name"
                      class="w-full p-3 border outline-none rounded-xl focus:ring-2 focus:ring-blue-500">
                <input type="email"name="email" placeholder="Your Email"
                       class="w-full p-3 border outline-none rounded-xl focus:ring-2 focus:ring-blue-500">
                <textarea rows="5" name="message"placeholder="Your Message"
                       class="w-full p-3 border outline-none rounded-xl focus:ring-2 focus:ring-blue-500"></textarea>

                <!-- Button -->
               <button type="submit"
                       class="w-full py-4 text-lg font-semibold text-white transition duration-300 shadow-lg bg-gradient-to-r from-blue-600 to-cyan-500 rounded-2xl hover:scale-[1.02] hover:shadow-xl">
                     {{ __('messages.send_message') }}
               </button>

            </form>

        </div>
    </div>
</section>
@endsection
