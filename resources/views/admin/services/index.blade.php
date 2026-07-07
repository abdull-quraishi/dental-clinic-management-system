@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">

        <h2 class="text-2xl font-bold">
            {{ __('messages.services_title') }}
        </h2>

      <form method="GET"
           action="{{ route('admin.services.index') }}"
           class="flex flex-col sm:flex-row">

         <input type="text"
                name="search"
                value="{{ request('search') }}"
                placeholder="Search service..."
                class="px-3 py-2 border rounded">

         <button type="submit"
                 class="px-4 py-2 text-white bg-green-600 rounded ">
             {{ __('messages.search') }}
         </button>

         <a href="{{ route('admin.services.index') }}"
            class="px-4 py-2 text-center text-white bg-gray-700 rounded ms-2">
             {{ __('messages.refresh') }}
         </a>

          <a href="{{ route('admin.services.create') }}"
           class="px-4 py-2 text-white bg-blue-600 rounded ms-2">
           {{ __('messages.add_service') }}
        </a>
     </form>



    </div>

    <div class="bg-white rounded shadow">

        <table class="min-w-full text-left">

            <thead class="text-white bg-blue-600">

                <tr>
                    <th class="p-3">{{ __('messages.name') }}</th>
                    <th class="p-3">{{ __('messages.price') }}</th>
                    <th class="p-3">{{ __('messages.status') }}</th>
                    <th class="p-3">{{ __('messages.description') }}</th>
                    <th class="p-3">{{ __('messages.actions') }}</th>
                </tr>

            </thead>

            <tbody>

                @foreach($services as $service)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $service->name }}
                    </td>

                    <td class="p-3">
                        {{ $service->price }} afg
                    </td>

                    <td class="p-3">

                   {{ $service->status ? __('messages.active') : __('messages.inactive') }}

                    </td>

                     <td class="p-3">
                        {{ $service->description }}
                    </td>

                    <td class="flex gap-2 p-3">

                        <a href="{{ route('admin.services.edit', $service->id) }}"
                           class="px-3 py-1 mr-8 text-white bg-green-600 rounded">

                         {{ __('messages.edit_service') }}

                        </a>

                        <form action="{{ route('admin.services.destroy', $service->id) }}"
                              method="POST">

                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 text-white bg-red-600 rounded">

                         {{ __('messages.delete_service') }}

                            </button>

                        </form>

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $services->links() }}
        </div>

</div>

@endsection
