@extends('layouts.app')

@section('content')
<div class="p-6">

    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">

        <h2 class="text-2xl font-semibold">
            {{ __('messages.all_patients') }}
        </h2>

        <div class="flex flex-col gap-2 sm:flex-row">

            <form method="GET"
                  action="{{ route('admin.patients.index') }}"
                  class="flex flex-col sm:flex-row">

                <input type="text"
                       name="search"
                       value="{{ request('search') }}"
                       placeholder="Search patient..."
                       class="px-3 py-2 border rounded">

                <button type="submit"
                        class="px-4 py-2 text-white bg-green-600 rounded">
                    {{ __('messages.search') }}
                </button>

            </form>

            <a href="{{ route('admin.patients.index') }}"
               class="px-4 py-2 text-center text-white bg-gray-700 rounded">
                {{ __('messages.refresh') }}
            </a>

        </div>

    </div>

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full">

            <thead class="bg-gray-100">
                <tr>
                    <th class="p-3 border">{{ __('messages.id') }}</th>
                    <th class="p-3 border">{{ __('messages.name') }}</th>
                    <th class="p-3 border">{{ __('messages.email') }}</th>
                    <th class="p-3 border">{{ __('messages.phone') }}</th>
                    <th class="p-3 border">{{ __('messages.address') }}</th>
                    <th class="p-3 border">{{ __('messages.actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @foreach($patients as $patient)
                <tr class="border-t">
                    <td class="p-3 border">{{ $patient->id }}</td>
                    <td class="p-3 border">{{ $patient->name }}</td>
                    <td class="p-3 border">{{ $patient->email }}</td>
                    <td class="p-3 border">{{ $patient->phone_number }}</td>
                    <td class="p-3 border">{{ $patient->address }}</td>

                    <td class="p-3 pl-6 border">

                        <a href="{{ route('admin.patients.dashboard', $patient->id) }}"
                           class="p-2 ml-4 mr-2 text-white bg-blue-700 rounded-xl hover:underline">
                            {{ __('messages.open_dashboard') }}
                        </a>

                        |

                       @if($patient->patient)
                      <a href="{{ route('admin.patients.history', $patient->id) }}"
                         class="p-2 ml-1 text-white bg-green-600 hover:underline rounded-xl">
                            {{ __('messages.view_history') }}
                        </a>
                      @else
                          <span class="text-gray-400">{{ __('messages.no_history') }}</span>
                      @endif
                </tr>
                @endforeach
            </tbody>

        </table>
    </div>

    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $patients->links() }}
        </div>

</div>
@endsection
