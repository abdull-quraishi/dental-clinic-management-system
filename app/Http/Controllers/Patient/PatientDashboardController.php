<?php

namespace App\Http\Controllers\Patient;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Billing;
use App\Models\TreatmentRecord;
use Illuminate\View\View;

 // Billing may not exist; we'll handle that conditionally.

class PatientDashboardController extends Controller
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


     public function dashboard()
  {
      $user = Auth::user();
      $patient = $this->resolvePatient();

      $appointments = 0;
      $prescriptionsCount = 0;
      $medicalRecordsCount = 0;
      $billingCount = 0;

      if ($patient) {

          $appointments = Appointment::where('patient_id', $patient->patient_id)->count();

          $prescriptionsCount = Prescription::where('patient_id', $patient->patient_id)->count();

          $medicalRecordsCount = TreatmentRecord::where('patient_id', $patient->patient_id)->count();

          if (class_exists(\App\Models\Billing::class)) {
              $billingCount = \App\Models\Billing::where('patient_id', $patient->patient_id)->count();
          } else {
              $billingCount = 0;
          }

      }

      // ✅ مهمه برخه
      $adminViewing = false;
      $patientUser = null;

      if ($user->hasAnyRole(['admin', 'super_admin']) && session()->has('admin_viewing_patient_id')) {
        $adminViewing = true;
        $patientUser = User::find(session('admin_viewing_patient_id'));
        }

      return view('patient.dashboard', compact(
          'appointments',
          'prescriptionsCount',
          'medicalRecordsCount',
          'billingCount',
          'adminViewing',
          'patientUser'
      ));
  }


}
