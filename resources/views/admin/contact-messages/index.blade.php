@extends('layouts.app')

@section('content')

<div class="p-6">

    <div class="flex items-center justify-between mb-6">

        <h2 class="text-2xl font-bold">
            {{ __('messages.contact_message') }}
        </h2>

    </div>

    <div class="overflow-x-auto bg-white rounded shadow">

        <table class="w-full">

            <thead class="text-white bg-blue-600">

                <tr>

                    <th class="p-3 text-left">{{ __('messages.name') }}</th>

                    <th class="p-3 text-left">{{ __('messages.email') }}</th>

                    <th class="p-3 text-left">{{ __('messages.message') }}</th>

                    <th class="p-3 text-left">{{ __('messages.date') }}</th>

                    <th class="p-3 text-left">{{ __('messages.actions') }}</th>

                </tr>

            </thead>

            <tbody>

                @forelse($messages as $message)

                    <tr class="border-b">

                        <td class="p-3">
                            {{ $message->name }}
                        </td>

                        <td class="p-3">
                            {{ $message->email }}
                        </td>

                        <td class="max-w-xs p-3">
                            {{ $message->message }}
                        </td>

                        <td class="p-3">
                            {{ $message->created_at->format('Y-m-d') }}
                        </td>

                        <td class="p-3">

                            <form action="{{ route('admin.contact.messages.destroy', $message->id) }}"
                                  method="POST">

                                @csrf
                                @method('DELETE')

                                <button class="px-3 py-1 text-white bg-red-600 rounded">

                                    {{ __('messages.delete') }}

                                </button>

                            </form>

                        </td>

                    </tr>

                @empty

                    <tr>

                        <td colspan="5"
                            class="p-4 text-center text-gray-500">

                          {{ __('messages.if_no_messages') }}

                        </td>

                    </tr>

                @endforelse

            </tbody>

        </table>

    </div>

    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $messages->links() }}
        </div>

</div>

@endsection
