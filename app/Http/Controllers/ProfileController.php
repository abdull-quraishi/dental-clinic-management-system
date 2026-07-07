<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\View\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;

class ProfileController extends Controller
{
    /*
    |---------------------------------------
    | SHOW PROFILE (ALL USERS)
    |---------------------------------------
    */
    public function show(): View
    {
        $user = auth()->user();

        $patient = null;
        $doctor = null;

        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
        }

        if (str_contains($user->role, 'doctor')) {
            $doctor = Doctor::where('user_id', $user->id)->first();
        }

        return view('profile.show', compact('user', 'patient', 'doctor'));
    }

    /*
    |---------------------------------------
    | EDIT PAGE
    |---------------------------------------
    */
    public function edit(): View
    {
        $user = auth()->user();

        $patient = null;
        $doctor = null;

        if ($user->role === 'patient') {
            $patient = Patient::where('user_id', $user->id)->first();
        }

        if (str_contains($user->role, 'doctor')) {
            $doctor = Doctor::where('user_id', $user->id)->first();
        }

        return view('profile.edit', compact('user', 'patient', 'doctor'));
    }

    /*
    |---------------------------------------
    | UPDATE PROFILE (ALL USERS)
    |---------------------------------------
    */

public function update(Request $request)
{
    $user = auth()->user();

    $request->validate([
        'name' => 'required|string|max:255',
        'email' => 'required|email',
        'avatar' => 'nullable|image|mimes:jpg,jpeg,png,webp|max:2048',

        'phone_number' => 'nullable|string|max:20',
        'address' => 'nullable|string|max:255',
        'age' => 'nullable|integer',
        'gender' => 'nullable|in:male,female,other',
        'bio' => 'nullable|string',
    ]);

    // upload avatar
    if ($request->hasFile('avatar')) {
        // old avatar delete (optional)
        if ($user->avatar && Storage::disk('public')->exists($user->avatar)) {
            Storage::disk('public')->delete($user->avatar);
        }

        $user->avatar = $request->file('avatar')->store('avatars', 'public');
    }

    $user->name = $request->name;
    $user->email = $request->email;
    $user->phone_number = $request->phone_number;
    $user->address = $request->address;
    $user->age = $request->age;
    $user->gender = $request->gender;
    $user->save();

    if ($user->role === 'patient') {
        $patient = Patient::firstOrCreate(['user_id' => $user->id]);

        $patient->update([
            'phone_number' => $request->phone_number,
            'address' => $request->address,
        ]);
    }

    if (str_contains($user->role, 'doctor')) {
        $doctor = Doctor::where('user_id', $user->id)->first();

        if ($doctor) {
            $doctor->update([
                'bio' => $request->bio,
            ]);
        }
    }

    return redirect()
        ->route('profile.show')
        ->with('success', 'Profile updated successfully');
}

    /*
    |---------------------------------------
    | DELETE ACCOUNT
    |---------------------------------------
    */
    public function destroy(Request $request)
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
