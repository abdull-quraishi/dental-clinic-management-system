<?php
namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Billing;
use App\Models\Prescription;
use App\Models\Appointment;


class BillingController extends Controller
{

   public function markPaid(Request $r, $id)
  {
      $bill = Billing::findOrFail($id);

      $bill->status = 'paid';
      $bill->paid_date = now();
      $bill->save();

      // update prescription
      if($bill->prescription_id){

          $prescription = Prescription::find($bill->prescription_id);

          $prescription->status = 'dispensed';

          $prescription->save();
      }

      // Complete Appointment
      Appointment::where('patient_id', $bill->patient_id)
          ->where('doctor_id', $bill->doctor_id)
          ->whereIn('status', ['Approved'])
          ->latest('appointment_date')
          ->first()?->update([
              'status' => 'Completed'
          ]);

      return redirect()->back()
          ->with('success','Bill marked paid.');
  }

}