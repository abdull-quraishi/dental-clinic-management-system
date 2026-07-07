<?php

namespace App\Http\Controllers\Doctor;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\TreatmentRecord;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class DiagnosisController extends Controller
{

/*
|--------------------------------------------------------------------------
| THIS METHODE FOR SHHOWING DIAGNOSIS & TREATMENT FORM
|--------------------------------------------------------------------------
*/
public function diagnosisForm($patient_id)
{
    return view('doctor.diagnosis.create', compact('patient_id'));
}

public function storeDiagnosis(Request $request)
{
    $request->validate([
        'patient_id' => 'required',
        'diagnosis' => 'required'
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->first();

    TreatmentRecord::create([
        'patient_id' => $request->patient_id,
        'doctor_id' => $doctor->doctor_id ?? null,
        'diagnosis' => $request->diagnosis,
        'treatment_plan' => $request->treatment_plan,
        'treatment_status' => $request->treatment_status,
        'treatment_date' => now(),
     ]);

    return redirect()->route('doctor.patients')->with('success', 'Diagnosis Record Saved Successfully');
}

 

}