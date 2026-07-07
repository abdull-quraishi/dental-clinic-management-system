@extends('layouts.app')

@section('content')
<div class="p-4">

    <div class="flex flex-col gap-3 mb-6 lg:flex-row lg:items-center lg:justify-between">
       <div class="flex justify-start">
          <h2 class="text-4xl font-semibold">
            {{ __('messages.manage_users') }}
        </h2>
       </div>

        <div class="flex flex-col gap-2 sm:flex-row">
        {{-- this si for search bar --}}
         <form method="GET"action="{{ route('admin.users') }}"
                     class="flex flex-col  sm:flex-row">
            <input type="text"
                   name="search"
                   value="{{ request('search') }}"
                   placeholder="Search name, email, phone, address..."
                   class="px-3 py-2 border rounded">

            <button type="submit"
                    class="px-4 py-2 text-white text-center bg-green-600 rounded">
                {{ __('messages.search') }}
            </button>

        </form>
         {{-- this button for refresh the page --}}
          <a href="{{ route('admin.users') }}"
                 class="px-4 py-2 text-white text-center bg-gray-700 rounded">
               {{ __('messages.refresh') }}
           </a>

           <a href="{{ route('admin.dashboard') }}"
              class="px-4 py-2 text-white text-center bg-green-600 rounded">
               {{ __('messages.go_to_dashboard') }}
           </a>

           <a href="{{ route('admin.users.create') }}"
              class="px-4 py-2 text-white text-center bg-blue-600 rounded">
               {{ __('messages.add_user') }}
           </a>
        </div>
    </div>

    {{-- Success Message --}}
    @if(session('success'))
        <div class="p-3 mb-4 text-green-800 bg-green-100 border border-green-300 rounded">
            {{ session('success') }}
        </div>
    @endif

    {{-- Error Message --}}
    @if ($errors->any())
        <div class="p-3 mb-4 text-red-800 bg-red-100 border border-red-300 rounded">
            <ul class="pl-5 list-disc">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full border-collapse table-auto">

            <thead class="bg-gray-100 ">
                <tr>
                    <th class="px-2 py-3 text-center border">{{ __('messages.id') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.name') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.email') }}</th>
                    <th class="px-2 py-3 text-sm text-center border">{{ __('messages.phone') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.address') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.age') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.gender') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.current_role') }}</th>
                    <th class="px-2 py-3 text-sm text-center border">{{ __('messages.admin_access_time') }}</th>
                    <th class="px-2 py-3 text-center border">{{ __('messages.actions') }}</th>
                </tr>
            </thead>

            <tbody>
                @forelse($users as $user)
                   @php
                   $currentRole = $user->getRoleNames()->first();
                  @endphp

                    <tr class="border-t">
                        <td class="px-3 border">{{ $user->id }}</td>
                        <td class="px-3 border">{{ $user->name }}</td>
                        <td class="px-3 border">{{ $user->email }}</td>
                        <td class="px-3 border">{{ $user->phone_number }}</td>
                        <td class="px-1 text-sm border">{{ $user->address }}</td>
                        <td class="px-3 border">{{ $user->age }}</td>
                        <td class="px-3 border">{{ $user->gender }}</td>
                        <td class="px-1 text-sm ">{{ $user->role }}</td>

                                   {{-- Admin Time --}}
                              <td class="px-3 border">
                                   @php
                                       $role = $user->getRoleNames()->first();
                                   @endphp

                                   @if($role === 'super_admin')
                                       <span class="font-medium text-green-700">
                                         {{ __('messages.full_access_time') }}
                                       </span>
                                   @elseif($role === 'admin')
                                       <span class="font-medium text-blue-700">
                                         {{ __('messages.has_access_from') }}
                                           {{ $user->admin_start_time ? \Carbon\Carbon::parse($user->admin_start_time)->format('h:i A') : '--' }}
                                         {{ __('messages.to') }}
                                           {{ $user->admin_end_time ? \Carbon\Carbon::parse($user->admin_end_time)->format('h:i A') : '--' }}
                                       </span>
                                   @else
                                       <span class="text-gray-400">
                                         {{ __('messages.not_applicable') }}
                                       </span>
                                   @endif
                               </td>

                        {{-- Actions --}}

                        <td class="px-3 border">


                            <div class="flex gap-2 mt-1">

                               <a href="{{ route('admin.users.profile', $user->id) }}"
                                   class="text-white rounded-2xl hover:underline">
                                 <img src="{{ asset('images/bladeicon/view.png') }}" style="width: 30px; height:30px;">

                                </a>

                                <a href="{{ route('admin.users.edit', $user->id) }}"
                                   class="text-white hover:underline rounded-2xl">
                                 <img src="{{ asset('images/bladeicon/edit.png') }}" style="width: 30px; height:30px">

                                </a>

                                <form action="{{ route('admin.users.destroy', $user->id) }}"
                                      method="POST"
                                      class="inline-block"
                                      onsubmit="return confirm('{{ __('messages.delete_user_confirm') }}')">
                                    @csrf
                                    @method('DELETE')

                                    <button type="submit" class="text-white hover:underline rounded-2xl" {{ $user->role === 'super_admin' ? 'disabled' : '' }}>
                                   <img src="{{ asset('images/bladeicon/delete.png') }}" style="width: 30px; height:30px">
                                    </button>
                                </form>
                            </div>
                        </td>
                    </tr>

                @empty
                    <tr>
                        <td colspan="7" class="p-6 text-center text-gray-500">
                            {{ __('messages.no_users_found') }}
                        </td>
                    </tr>
                @endforelse
            </tbody>

        </table>
    </div>

    {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $users->links() }}
        </div>

</div>


{{-- JS --}}
<script>
document.addEventListener('DOMContentLoaded', function () {

    document.querySelectorAll('.roleForm').forEach(form => {

        const roleSelect = form.querySelector('.roleSelect');
        const adminFields = form.closest('tr').querySelector('.adminTimeFields');

        function toggleAdminFields() {
            if (roleSelect.value === 'admin') {
                adminFields.classList.remove('hidden');
            } else {
                adminFields.classList.add('hidden');
            }
        }

        roleSelect.addEventListener('change', toggleAdminFields);

        form.addEventListener('submit', function(e) {

            if (roleSelect.value === 'admin') {

                const start = form.querySelector('input[name="admin_start_time"]').value;
                const end = form.querySelector('input[name="admin_end_time"]').value;

                if (!start || !end) {
                    e.preventDefault();
                    alert('{{ __("messages.admin_time_required") }}');
                }
            }

        });

        toggleAdminFields();

    });

});
</script>

@endsection
