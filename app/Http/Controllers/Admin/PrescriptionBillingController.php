<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Billing;

class PrescriptionBillingController extends Controller
{

    public function index(Request $request)
      {
           $type = $request->type;
           $search = $request->search;
           if ($type == 'today') {
       $prescriptions = Prescription::whereHas('billing', function ($q) {

           $q->where('status', 'paid')
             ->whereDate('paid_date', today());

       })
       ->when($search, function ($query) use ($search) {

           $query->where(function ($q) use ($search) {

               $q->whereHas('patient', function ($patient) use ($search) {
                   $patient->where('first_name', 'like', "%{$search}%")
                           ->orWhere('last_name', 'like', "%{$search}%");
               })

               ->orWhereHas('doctor', function ($doctor) use ($search) {
                   $doctor->where('first_name', 'like', "%{$search}%")
                          ->orWhere('last_name', 'like', "%{$search}%");
               });

           });

      })
            ->with([
                'doctor',
                'patient',
                'billing',
                'prescriptionItems.medicine'
            ])
            ->latest()
            ->paginate(20)->withQueryString();

        } else {

            // new prescriptions
         $prescriptions = Prescription::whereHas('billing', function ($q) {

            $q->where('status', 'unpaid');

          })
          ->when($search, function ($query) use ($search) {

              $query->where(function ($q) use ($search) {

                  $q->whereHas('patient', function ($patient) use ($search) {
                      $patient->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%");
                  })

                  ->orWhereHas('doctor', function ($doctor) use ($search) {
                      $doctor->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%");
                  });

              });

          })
            ->with([
                'doctor',
                'patient',
                'billing',
                'prescriptionItems.medicine'
            ])
            ->latest()
            ->paginate(20)->withQueryString();
        }

        return view('admin.prescriptions-bills.index', compact('prescriptions'));
    }

    public function printPrescriptionBill($id)
   {
       $prescription = Prescription::with([
           'patient',
           'doctor',
           'service',
           'billing',
           'prescriptionItems.medicine'
       ])->findOrFail($id);

       return view('admin.prescriptions-bills.print',compact('prescription'));
   }
}
