@extends('layouts.app')

@section('content')
<div class="max-w-3xl p-6 mx-auto bg-white rounded shadow">
    <h2 class="mb-4 text-xl font-bold">
        {{ __('messages.create_user') }}
    </h2>

    @if ($errors->any())
        <div class="p-3 mb-4 text-red-700 rounded bg-red-50">
            <ul class="pl-5 list-disc">
                @foreach ($errors->all() as $e)
                    <li>{{ $e }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-4">
        @csrf

        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.name') }}
            </label>
            <input type="text" name="name" value="{{ old('name') }}" class="w-full p-2 border rounded" required>
        </div>

        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.email') }}
            </label>
            <input type="email" name="email" value="{{ old('email') }}" class="w-full p-2 border rounded">
        </div>

        <div>
            <label class="block mb-2 font-medium">
                {{ __('messages.phone') }}
            </label>
            <input type="text" name="phone_number" value="{{ old('phone_number') }}" class="w-full p-2 border rounded" >
        </div>

        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label class="block mb-2 font-medium">
                {{ __('messages.password') }}
                </label>
                <input type="password" name="password" class="w-full p-2 border rounded" required>
            </div>

            <div>
                <label class="block mb-2 font-medium">
                {{ __('messages.confirm_password') }}
                </label>
                <input type="password" name="password_confirmation" class="w-full p-2 border rounded" required>
            </div>
        </div>

         <div>
              <label class="block mb-2 font-medium">
                {{ __('messages.address') }}
              </label>
              <input type="text" name="address" value="{{ old('address') }}" class="w-full p-2 border rounded">
        </div>

        <div>
              <label class="block mb-2 font-medium">
                {{ __('messages.age') }}
              </label>
              <input type="text" name="age" value="{{ old('age') }}" class="w-full p-2 border rounded">
        </div>
         <div>
              <label class="block mb-2 font-medium">
                {{ __('messages.gender') }}
              </label>
              <select name="gender" id="" value="{{ old('gender') }}" class="w-full p-2 border rounded">
                    <option value="">
                {{ __('messages.select_gender') }}
                    </option>
                    <option value="male">
                {{ __('messages.male') }}
                    </option>
                    <option value="female">
                {{ __('messages.female') }}
                    </option>
                    <option value="other">
                {{ __('messages.other') }}
                    </option>
              </select>
         </div>

       <div>
          <label class="block mb-2 font-medium">
                {{ __('messages.role') }}
          </label>
          <select name="role" id="roleSelect" class="w-full p-2 border rounded" required>
              <option value="">
                {{ __('messages.select_role') }}
              </option>
              <option value="admin">
                {{ __('messages.admin') }}
              </option>
              <option value="general_doctor">
                {{ __('messages.general_doctor') }}
              </option>
              <option value="filler_specialist_doctor">
                {{ __('messages.filler_specialist_doctor') }}
              </option>
              <option value="extractor_specialist_doctor">
                {{ __('messages.extractor_specialist_doctor') }}
              </option>
              <option value="cleaner_specialist_doctor">
                {{ __('messages.cleaner_specialist_doctor') }}
              </option>
              <option value="root_canal_specialist_doctor">
                {{ __('messages.root_canal_specialist_doctor') }}
              </option>
              <option value="patient">
                {{ __('messages.patient') }}
              </option>
          </select>
    </div>

       <div id="adminTimeBox" class="grid hidden grid-cols-1 gap-4 md:grid-cols-2">
          <div>
              <label class="block mb-2 font-medium">
                {{ __('messages.admin_start_time') }}
              </label>
              <input type="time" name="admin_start_time" id="adminStartTime" class="w-full p-2 border rounded">
            </div>

          <div>
              <label class="block mb-2 font-medium">
                {{ __('messages.admin_end_time') }}
              </label>
            <input type="time" name="admin_end_time" id="adminEndTime" class="w-full p-2 border rounded">
          </div>
     </div>

        <p class="text-xs text-gray-500">
             {{ __('messages.admin_time_note') }}
        </p>

        <div>
            <button type="submit" class="px-4 py-2 text-white bg-blue-600 rounded">
                {{ __('messages.save') }}
            </button>
            <a href="{{ route('admin.users') }}" class="px-4 py-2 ml-2 text-white bg-red-500 rounded">
                {{ __('messages.cancel') }}
            </a>

        </div>
    </form>
</div>

{{-- this is a simple script to toggle admin time fields based on role selection --}}
<script>
document.addEventListener('DOMContentLoaded', function () {
    const roleSelect = document.getElementById('roleSelect');
    const adminTimeBox = document.getElementById('adminTimeBox');
    const adminStartTime = document.getElementById('adminStartTime');
    const adminEndTime = document.getElementById('adminEndTime');

    function toggleAdminFields() {
        if (roleSelect.value === 'admin') {
            adminTimeBox.classList.remove('hidden');
            adminStartTime.required = true;
            adminEndTime.required = true;
        } else {
            adminTimeBox.classList.add('hidden');
            adminStartTime.required = false;
            adminEndTime.required = false;
            adminStartTime.value = '';
            adminEndTime.value = '';
        }
    }

    roleSelect.addEventListener('change', toggleAdminFields);
    toggleAdminFields();
});
</script>

@endsection
