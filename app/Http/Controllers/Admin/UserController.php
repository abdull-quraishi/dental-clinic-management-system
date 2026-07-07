<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\TreatmentRecord;
use App\Models\Billing;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    // this method for showing all users with pagination
   public function index(Request $request)
   {
       $search = $request->search;

       $users = User::query()

           ->when($search, function ($query) use ($search) {

               $query->where('name', 'like', "%{$search}%")
                     ->orWhere('email', 'like', "%{$search}%")
                     ->orWhere('phone_number', 'like', "%{$search}%")
                     ->orWhere('address', 'like', "%{$search}%");
           })
           ->latest()
           ->paginate(5)
           ->withQueryString();

       return view('admin.users.index', compact('users'));
   }

    // this method for showing create user form
    public function create()
    {
        return view('admin.users.create');
    }

    // this method for storing new user with role and doctor profile if doctor-type
    public function store(Request $request)
  {
    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email',
        'phone_number' => 'nullable|string|max:20',
        'password' => 'required|min:6|confirmed',
        'address' => 'nullable|string',
        'age' => 'nullable|integer|min:0',
        'gender' => 'nullable|string',
        'role' => 'required|in:super_admin,admin,general_doctor,filler_specialist_doctor,extractor_specialist_doctor,cleaner_specialist_doctor,root_canal_specialist_doctor,patient',
       'admin_start_time' => 'required_if:role,admin|nullable|date_format:H:i',
        'admin_end_time' => 'required_if:role,admin|nullable|date_format:H:i|after:admin_start_time',
    ]);

    $user = User::create([
        'name' => $request->name,
        'email' => $request->email,
        'phone_number' => $request->phone_number,
        'password' => Hash::make($request->password),
        'address' => $request->address,
        'age' => $request->age,
        'gender' => $request->gender,
        'role' => $request->role,
        'admin_start_time' => $request->role === 'admin' ? $request->admin_start_time : null,
        'admin_end_time' => $request->role === 'admin' ? $request->admin_end_time : null,
    ]);

    $user->syncRoles([$request->role]);

    $doctorRoles = [
        'general_doctor',
        'filler_specialist_doctor',
        'extractor_specialist_doctor',
        'cleaner_specialist_doctor',
        'root_canal_specialist_doctor',
    ];

    $specialtyMap = [
        'general_doctor' => 'general',
        'filler_specialist_doctor' => 'filler',
        'extractor_specialist_doctor' => 'extractor',
        'cleaner_specialist_doctor' => 'cleaner',
        'root_canal_specialist_doctor' => 'root canal',
    ];

    if (in_array($request->role, $doctorRoles)) {
        $names = explode(' ', $request->name, 2);

        Doctor::create([
            'user_id' => $user->id,
            'first_name' => $names[0] ?? $request->name,
            'last_name' => $names[1] ?? '',
            'email' => $request->email,
            'phone_number' => $request->phone_number,
            'role' => $specialtyMap[$request->role],
            'bio' => null,
        ]);
    }

       if ($request->role === 'patient') {

       $names = explode(' ', $request->name, 2);

       Patient::create([
           'user_id' => $user->id,
           'first_name' => $names[0] ?? $request->name,
           'last_name' => $names[1] ?? '',
           'email' => $request->email,
           'phone_number' => $request->phone_number,
           'address' => $request->address,
       ]);
     }

    return redirect()->route('admin.users')
        ->with('success', 'User created successfully.');
}

    public function edit($id)
    {
        $user = User::findOrFail($id);

        return view('admin.users.edit', compact('user'));
    }

    // this method for updating user info and role
   public function update(Request $request, $id)
 {
    $user = User::findOrFail($id);

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'nullable|email|unique:users,email,' . $user->id,
        'password' => 'nullable|min:6|confirmed',
        'address' => 'nullable|string',
        'phone_number' => 'nullable|string|min:10|max:20',
        'age' => 'nullable|integer|min:0',
        'gender' => 'nullable|string',
        'role' => 'required|in:super_admin,admin,general_doctor,filler_specialist_doctor,extractor_specialist_doctor,cleaner_specialist_doctor,root_canal_specialist_doctor,patient',
        'admin_start_time' => 'required_if:role,admin|nullable|date_format:H:i',
        'admin_end_time' => 'required_if:role,admin|nullable|date_format:H:i|after:admin_start_time',
    ]);

    $user->name = $request->name;
    $user->email = $request->email;
    $user->role = $request->role;

    if ($request->filled('password')) {
        $user->password = Hash::make($request->password);
    }

    $user->address = $request->address;
    $user->phone_number = $request->phone_number;
    $user->age = $request->age;
    $user->gender = $request->gender;

    if ($request->role === 'admin') {
        $user->admin_start_time = $request->admin_start_time;
        $user->admin_end_time = $request->admin_end_time;
    } else {
        $user->admin_start_time = null;
        $user->admin_end_time = null;
    }

    $user->save();
    $user->syncRoles([$request->role]);

    $doctorRoles = [
        'general_doctor',
        'filler_specialist_doctor',
        'extractor_specialist_doctor',
        'cleaner_specialist_doctor',
        'root_canal_specialist_doctor',
    ];

    $specialtyMap = [
        'general_doctor' => 'general',
        'filler_specialist_doctor' => 'filler',
        'extractor_specialist_doctor' => 'extractor',
        'cleaner_specialist_doctor' => 'cleaner',
        'root_canal_specialist_doctor' => 'root canal',
    ];

    if (in_array($request->role, $doctorRoles)) {
        if (!$user->doctor) {
            $names = explode(' ', $user->name, 2);

            Doctor::create([
                'user_id' => $user->id,
                'first_name' => $names[0] ?? $user->name,
                'last_name' => $names[1] ?? '',
                'email' => $user->email,
                'specialty' => $specialtyMap[$request->role],
                'phone_number' => $user->phone_number,
                'bio' => null,
            ]);
        } else {
            $user->doctor->update([
                'first_name' => explode(' ', $user->name, 2)[0] ?? $user->name,
                'last_name' => explode(' ', $user->name, 2)[1] ?? '',
                'email' => $user->email,
                'specialty' => $specialtyMap[$request->role],
            ]);
        }
    } else {
        if ($user->doctor) {
            $user->doctor->delete();
        }
    }

    return redirect()->route('admin.users')
        ->with('success', 'User updated successfully.');
 }


// this method for deleting user
   public function destroy($id)
{
    $user = User::findOrFail($id);

    // doctor profile هم delete کړه
    if ($user->doctor) {
        $user->doctor->delete();
    }

    // patient profile هم delete کړه
    if ($user->patient) {
        $user->patient->delete();
    }

    // roles remove
    $user->syncRoles([]);

    // user delete
    $user->delete();

    return redirect()->route('admin.users')
        ->with('success', 'User deleted successfully.');
}

// this method for showing user profile with doctor or patient info if exist
public function profile($id)
{
    $user = User::with(['patient', 'doctor'])->findOrFail($id);

    return view('admin.users.profile', compact('user'));
}

}