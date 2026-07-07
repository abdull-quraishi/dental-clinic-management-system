  @extends('layouts.app')

  @section('content')
<!-- Latest patients -->
    <div class="p-4 bg-white rounded-lg shadow">
       <div class="flex items-center justify-between mb-6">
        <h2 class="text-2xl font-bold text-gray-800">
            {{ __('messages.latest_patients') }}
        </h2>
        <a href="{{ route('doctor.dashboard') }}"
           class="px-4 py-2 text-sm text-white bg-red-500 rounded">{{ __('messages.back') }}</a>
    </div>

      @if($latestPatients->isEmpty())
        <div class="text-gray-500">
            {{ __('messages.if_no_patients') }}
        </div>
      @else
        <div class="space-y-3">
          @foreach($latestPatients as $pt)
            <div class="flex items-center justify-between p-2 border rounded">
              <div>
                <div class="font-medium">{{ $pt->first_name }} {{ $pt->last_name }}</div>
                <div class="text-sm text-gray-500">{{ $pt->email }}</div>
              </div>
              <div class="text-sm text-gray-500">Joined: {{ $pt->created_at->format('Y-m-d') }}</div>
            </div>
          @endforeach
        </div>
      @endif

      {{-- Pagination --}}
        <div class="px-4 py-3 mt-4 bg-white shadow-sm rounded-2xl ring-1 ring-slate-200">
            {{ $latestPatients->links() }}
        </div>
    </div>



    @endsection
