@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">

        <h2 class="text-2xl font-bold">
            {{ __('messages.medicines_title') }}
        </h2>

        <div class="flex flex-col gap-2 sm:flex-row">

            <form method="GET"
                  action="{{ route('admin.medicines.index') }}"
                  class="flex flex-col  sm:flex-row">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search medicine..."
                       class="px-3 py-2 border rounded">

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-600 rounded">
                    {{ __('messages.search') }}
                </button>
            </form>

            <a href="{{ route('admin.medicines.index') }}"
               class="px-4 py-2 text-center text-white bg-gray-700 rounded">
                                {{ __('messages.refresh') }}
            </a>

            <a href="{{ route('admin.medicines.create') }}"
               class="px-4 py-2 text-center text-white bg-blue-600 rounded">
                {{ __('messages.add_medicine') }}
            </a>

        </div>


    </div>

    <div class="bg-white rounded shadow">

        <table class="min-w-full text-left">

            <thead class="text-white bg-blue-600">

                <tr>
                    <th class="p-3">{{ __('messages.name') }}</th>
                    <th class="p-3">{{ __('messages.price') }}</th>
                    <th class="p-3">{{__('messages.stock')}}</th>
                    <th class="p-3">{{ __('messages.dosage') }}</th>
                    <th class="p-3">{{ __('messages.status') }}</th>
                    <th class="p-3">{{ __('messages.actions') }}</th>
                </tr>

            </thead>

            <tbody>

                @foreach($medicines as $medicine)

                <tr class="border-t">

                    <td class="p-3">
                        {{ $medicine->name }}
                    </td>

                    <td class="p-3">
                        {{ $medicine->price }} afg
                    </td>

                    <td class="p-3">
                        {{ $medicine->stock }}
                    </td>

                    <td class="p-3">
                        {{ $medicine->dosage }}mg
                    </td>

                    <td class="p-3">

                        {{ $medicine->status ? 'Active' : 'Inactive' }}

                    </td>

                    <td class="flex gap-2 p-3">

                        <a href="{{ route('admin.medicines.edit', $medicine->id) }}"
                           class="px-3 py-1 text-white bg-green-600 rounded">

                            {{ __('messages.edit_medicine') }}

                        </a>

                        <form action="{{ route('admin.medicines.destroy', $medicine->id) }}"
                              method="POST">
                            @csrf
                            @method('DELETE')

                            <button class="px-3 py-1 text-white bg-red-600 rounded"
                                      onsubmit="return confirm('{{ __('messages.delete_user_confirm') }}')">

                            {{ __('messages.delete_medicine') }}

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
            {{ $medicines->links() }}
        </div>

</div>

@endsection
