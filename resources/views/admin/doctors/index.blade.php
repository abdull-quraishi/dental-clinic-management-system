@extends('layouts.app')

@section('content')
<div class="p-2">

    <div class="flex flex-col gap-3 mb-4 lg:flex-row lg:items-center lg:justify-between">
        <h2 class="text-3xl font-semibold">
            {{ __('messages.doctors_title') }}
        </h2>

    <div class="flex flex-col gap-2 sm:flex-row">
        <form method="GET"
                 action="{{ route('admin.doctors.index') }}"
                 class="flex flex-col  sm:flex-row">

               <input type="text"
                      name="search"
                      value="{{ request('search') }}"
                      placeholder="Search doctor..."
                      class="px-3 py-2 border rounded">

               <button type="submit"
                       class="px-4 py-2 text-white bg-green-600 rounded">
                   {{ __('messages.search') }}
               </button>

           </form>

           <a href="{{ route('admin.doctors.index') }}"
              class="px-4 py-2 text-center text-white bg-gray-700 rounded">
               {{ __('messages.refresh') }}
           </a>

           <a href="{{ route('admin.dashboard') }}"
          class="px-4 py-2 text-center text-white bg-green-600 rounded">
          {{ __('messages.go_to_dashboard') }}
       </a>
       <a href="{{ route('admin.doctors.create') }}"
          class="px-4 py-2 text-center text-white bg-blue-600 rounded">
          {{ __('messages.add_doctor') }}
       </a>
       </div>

    </div>

    @if(session('success'))
      <div class="p-3 mb-4 text-green-800 rounded bg-green-50">{{ session('success') }}</div>
    @endif

    <div class="overflow-x-auto bg-white rounded shadow">
        <table class="w-full border-collapse table-auto">
            <thead class="bg-gray-100">
                <tr class="text-left">
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.id') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.photo') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.name') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.email') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.phone') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.address') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.role') }}
                    </th>
                    <th class="px-2 py-3 text-center border">
                        {{ __('messages.actions') }}
                    </th>
                </tr>
            </thead>
            <tbody>
                @forelse($doctors as $d)
                <tr class="border-t">
                    <td class="p-2">{{ $d->doctor_id }}</td>

                    <td class="p-2">
                    @if($d->image)
                    <img src="{{ asset('doctor_images/'.$d->image) }}" style="width:45px;height:50px">
                    @else
                    <img src="{{ asset('images/default-doctor.png') }}" style="width:45px;height:50px">
                    @endif
                    </td>
                    <td class="p-2">{{ $d->first_name }} {{ $d->last_name }}</td>
                    <td class="p-2">{{ $d->email }}</td>
                    <td class="p-2">{{ $d->phone_number }}</td>
                    <td class="p-2">{{ $d->address }}</td>
                    <td class="p-2">{{ $d->role }}</td>
                    <td class="flex gap-2 p-2 pl-6">

                        <a href="{{ route('admin.users.profile', $d->user_id) }}"
                            class="text-white rounded-2xl hover:underline">
                          <img src="{{ asset('images/bladeicon/view.png') }}" style="width: 30px; height:30px"
                        </a>

                        <a href="{{ route('admin.doctors.edit', $d->doctor_id) }}" class="text-blue-600">
                          <img src="{{ asset('images/bladeicon/edit.png') }}" style="width: 30px; height:30px">
                        </a>

                        <form action="{{ route('admin.doctors.destroy', $d->doctor_id) }}" method="POST" style="display:inline-block" onsubmit="return confirm('Are you sure?')">
                            @csrf
                            @method('DELETE')
                        <button class="text-red-600">
                           <img src="{{ asset('images/bladeicon/delete.png') }}" style="width: 30px; height:30px">
                       </button>
                        </form>
                    </td>
                </tr>
                @empty
                <tr>
                    <td colspan="6" class="p-4 text-center text-gray-500">
                        {{ __('messages.no_doctors') }}
                    </td>
                </tr>
                @endforelse
            </tbody>
        </table>

    </div>

        {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $doctors->links() }}
        </div>
</div>
@endsection
