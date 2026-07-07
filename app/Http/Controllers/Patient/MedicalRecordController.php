<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\Patient;
use App\Models\TreatmentRecord;
use Illuminate\View\View;

 // Billing may not exist; we'll handle that conditionally.

class MedicalRecordController extends Controller
{

     //  this method for admin to view patient dashboard with all details
    private function resolvePatient()
  {
     if ((auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')) &&
         session()->has('admin_viewing_patient_id')
     ) {
         return Patient::where('user_id', session('admin_viewing_patient_id'))->firstOrFail();
     }

     return Patient::where('user_id', Auth::id())->firstOrFail();
   }

    // ---------- Medical Records ----------
    public function medicalRecords()
    {
       $patient = $this->resolvePatient();

        $records = TreatmentRecord::with('doctor')
            ->where('patient_id', $patient->patient_id)
            ->orderBy('treatment_date', 'desc')
            ->paginate(3);

        return view('patient.medicalrecords.medical-records', compact('records'));
    }

    public function medicalRecordShow($id)
    {
       $patient = $this->resolvePatient();

        $record = TreatmentRecord::with('doctor')
            ->where('treatment_id', $id)
            ->where('patient_id', $patient->patient_id)
            ->firstOrFail();

        return view('patient.medicalrecords.medical-records-show', compact('record'));
    }



}