<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Prescription;
use App\Models\Patient;

 // Billing may not exist; we'll handle that conditionally.

class PrescriptionController extends Controller
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

      // ---------- Prescriptions ----------
    public function prescriptions()
    {
        $patient = $this->resolvePatient();

       $prescriptions = Prescription::with([
       'doctor',
       'service',
       'prescriptionItems.medicine'
        ])
            ->where('patient_id', $patient->patient_id)
            ->orderBy('prescription_date', 'desc')
            ->paginate(4);

        return view('patient.prescriptions.prescriptions', compact('prescriptions'));
    }

    public function prescriptionShow($id)
    {
       $patient = $this->resolvePatient();

       $prescription = Prescription::with([
          'doctor',
          'service',
          'prescriptionItems.medicine'
         ])
            ->where('prescription_id', $id)
            ->where('patient_id', $patient->patient_id)
            ->firstOrFail();

        return view('patient.prescriptions.prescriptions-show', compact('prescription'));
    }


}