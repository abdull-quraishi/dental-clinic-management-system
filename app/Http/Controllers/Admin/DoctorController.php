<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Doctor;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;

class DoctorController extends Controller
{
    public function index()
      {
          $search = request('search');

          $doctors = Doctor::with('user')

              ->when($search, function ($query) use ($search) {

                  $query->where('first_name', 'like', "%{$search}%")
                        ->orWhere('last_name', 'like', "%{$search}%")
                        ->orWhere('email', 'like', "%{$search}%")
                        ->orWhere('phone_number', 'like', "%{$search}%")
                        ->orWhere('address', 'like', "%{$search}%")
                        ->orWhere('role', 'like', "%{$search}%");
              })
              ->latest()
              ->paginate(5)
              ->withQueryString();

          return view('admin.doctors.index', compact('doctors'));
      }

    public function create()
    {
        return view('admin.doctors.create');
    }


public function store(Request $request)
{

$request->validate([
'first_name' => 'required|string|max:255',
'last_name' => 'nullable',
'email' => 'required|email|unique:users,email',
'password' => 'required|min:6|confirmed',
'phone_number' => 'nullable|string|max:20',
'address' => 'nullable|string|max:255',
'role' => 'required|in:general_doctor,filler_specialist_doctor,extractor_specialist_doctor,cleaner_specialist_doctor,root_canal_specialist_doctor,patient',
'image'=>'nullable|image|mimes:jpg,jpeg,png|max:2048'
]);

 $imageName = null;

 if($request->hasFile('image')){

 $image = $request->file('image');

 $imageName = time().'.'.$image->getClientOriginalExtension();

 $image->move(public_path('doctor_images'),$imageName);

}

// Create user
$user = User::create([
'name' => $request->first_name.' '.$request->last_name,
'email' => $request->email,
'phone_number'=>$request->phone_number,
'address'=>$request->address,
'password' => Hash::make($request->password),
'role' =>$request->role,
]);

// Assign doctor role
$user->assignRole($request->role); // or use $request->role if you want to assign based on input

$specialtyMap = [
        'general_doctor' => 'general',
        'filler_specialist_doctor' => 'filler',
        'extractor_specialist_doctor' => 'extractor',
        'cleaner_specialist_doctor' => 'cleaner',
        'root_canal_specialist_doctor' => 'root canal',
    ];

// Create doctor profile
Doctor::create([
'user_id' => $user->id,
'first_name' => $request->first_name,
'last_name' => $request->last_name,
'email' => $request->email,
'role' => $specialtyMap[$request->role],
'phone_number' => $request->phone_number,
'address'=>$request->address,
'image' => $imageName,
'bio' => $request->bio
]);

return redirect()->route('admin.doctors.index')->with('success','Doctor added successfully');
}


    public function edit($id)
    {
        $doctor = Doctor::where('doctor_id', $id)->with('user')->firstOrFail();
        return view('admin.doctors.edit', compact('doctor'));
    }

    public function update(Request $request, $id)
    {
        $doctor = Doctor::where('doctor_id', $id)->firstOrFail();

        $request->validate([
            'first_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,'.$doctor->user_id,
            'password' => 'nullable|string|min:6|confirmed',
            'role' => 'nullable|string|max:255',
            'phone_number' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'image'=>'nullable|image|mimes:jpg,jpeg,png|max:2048',
            'bio' => 'nullable|string|max:2000',
        ]);

       // handle image upload
       $imageName = $doctor->image;

       if ($request->hasFile('image')) {

           if ($doctor->image && file_exists(public_path('doctor_images/'.$doctor->image))) {
               unlink(public_path('doctor_images/'.$doctor->image));
           }

           $image = $request->file('image');
           $imageName = time().'.'.$image->getClientOriginalExtension();
           $image->move(public_path('doctor_images'), $imageName);
       }

        DB::beginTransaction();
        try {
            // update user
            $user = User::find($doctor->user_id);
            $user->name = $request->name;
            $user->email = $request->email;
            if ($request->filled('password')) {
                $user->password = Hash::make($request->password);
            }
            $user->save();


            // update doctor profile
            $names = explode(' ', $request->name, 2);
            $doctor->first_name = $names[0] ?? $request->name;
            $doctor->last_name = $names[1] ?? '';
            $doctor->email = $request->email;
            $specialtyMap = [
            'general_doctor' => 'general',
            'filler_specialist_doctor' => 'filler',
            'extractor_specialist_doctor' => 'extractor',
            'cleaner_specialist_doctor' => 'cleaner',
            'root_canal_specialist_doctor' => 'root canal',
             ];
            $doctor->role = $specialtyMap[$request->role] ?? $request->role;
            $doctor->phone_number = $request->phone_number;
            $doctor->address = $request->address;
            $doctor->image = $imageName;
            $doctor->bio = $request->bio;
            $doctor->save();

            DB::commit();

            return redirect()->route('admin.doctors.index')->with('success','Doctor updated Successfully');
        } catch (\Throwable $e) {
            DB::rollBack();
            return back()->withInput()->withErrors(['error' => $e->getMessage()]);
        }
    }

    // this method for deleting doctor and its user account if exists
   public function destroy($id)
 {
    $doctor = Doctor::where('doctor_id', $id)->firstOrFail();

    DB::beginTransaction();

    try {
        if ($doctor->user_id) {

            $user = User::find($doctor->user_id);

            if ($user) {

                // ټول ممکن doctor roles remove کړه
                $doctorRoles = [
                    'general_doctor',
                    'filler_specialist_doctor',
                    'extractor_specialist_doctor',
                    'cleaner_specialist_doctor',
                    'root_canal_specialist_doctor',
                ];

                foreach ($doctorRoles as $role) {
                    if ($user->hasRole($role)) {
                        $user->removeRole($role);
                    }
                }

                // که غواړې patient ته واوړي:
                // $user->assignRole('patient');
                // $user->role = 'patient';

                // یا مکمل delete:
                $user->syncRoles([]);
                $user->delete();
            }
        }

        // doctor profile delete
        $doctor->delete();

        DB::commit();

        return redirect()
            ->route('admin.doctors.index')
            ->with('success', 'Doctor deleted successfully.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()->withErrors([
            'error' => $e->getMessage()
        ]);
    }
  }
}