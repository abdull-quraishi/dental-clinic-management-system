<?php

namespace App\Http\Controllers;

use App\Models\Doctor;

class HomeDoctorController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | SHOW ALL DOCTORS
    |--------------------------------------------------------------------------
    */
    public function index()
    {
        $doctors = Doctor::with('user')
            ->latest()
            ->get();

        return view('home.doctors', compact('doctors'));
    }

    /*
    |--------------------------------------------------------------------------
    | SHOW SINGLE DOCTOR PROFILE
    |--------------------------------------------------------------------------
    */
    public function show($id)
    {
        $doctor = Doctor::with('user')
            ->where('doctor_id', $id)
            ->firstOrFail();

        return view('home.doctor-profile', compact('doctor'));
    }
}