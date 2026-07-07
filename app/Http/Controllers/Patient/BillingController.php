<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\Patient;
use App\Models\Billing;

 // Billing may not exist; we'll handle that conditionally.

class BillingController extends Controller
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

      // ---------- Billing ----------
    public function billing()
    {

       $patient = $this->resolvePatient();

        // If Billing model exists, use it:
        if (class_exists(Billing::class)) {
            $bills = Billing::with(['doctor','prescription.prescriptionItems.medicine'])
            ->where('patient_id', $patient->patient_id)
                ->orderBy('created_at', 'desc')
                ->paginate(4);
            return view('patient.bills.billing', compact('bills'));

       }


  }
}