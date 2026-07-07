<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Prescription;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Service;
use App\Models\Medicine;
use App\Models\PrescriptionItem;
use App\Models\Billing;
use App\Models\Appointment;
use Illuminate\Support\Facades\Auth;

class PrescriptionController extends Controller
{

/*
|--------------------------------------------------------------------------
| PRESCRIPTION
|--------------------------------------------------------------------------
*/
public function prescriptionForm($patient_id = null)
{
    $services = Service::where('status', 1)->get();

    $medicines = Medicine::where('status', 1)->get();

    if ($patient_id) {

        $patient = Patient::findOrFail($patient_id);

        return view(
            'doctor.prescriptions.create',
            compact('patient', 'services', 'medicines')
        );
    }

    $patients = Patient::all();

    return view(
        'doctor.prescriptions.create',
        compact('patients', 'services', 'medicines')
    );
}

 public function storePrescription(Request $r)
{
    $r->validate([

        'patient_id' => 'required',

        'service_id' => 'nullable|exists:services,id',

        'appointment_fee' => 'nullable|integer|min:0',

        'medicine_id.*' => 'nullable|exists:medicines,id',

        'quantity.*' => 'nullable|integer|min:1',
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->first();

    $service = null;
    if($r->service_id){
        $service = Service::find($r->service_id);
    }

    /*
    |--------------------------------------------------------------------------
    | CREATE PRESCRIPTION
    |--------------------------------------------------------------------------
    */

    $prescription = Prescription::create([

        'patient_id' => $r->patient_id,

        'doctor_id' => $doctor->doctor_id,

        'service_id' => $service?->id,

        'appointment_fee' => $r->appointment_fee,

        'prescription_date' => now(),

        'instructions' => $r->instructions,

        'status' => 'pending'
    ]);

    /*
    |--------------------------------------------------------------------------
    | MEDICINE TOTAL
    |--------------------------------------------------------------------------
    */

    $medicineTotal = 0;

    if ($r->medicine_id) {

        foreach ($r->medicine_id as $key => $medicineId) {

            if (!$medicineId) {
                continue;
            }

            $medicine = Medicine::find($medicineId);

            $qty = $r->quantity[$key] ?? 1;

            $subtotal = $medicine->price * $qty;

            $medicineTotal += $subtotal;

            PrescriptionItem::create([

                'prescription_id' => $prescription->prescription_id,

                'medicine_id' => $medicine->id,

                'quantity' => $qty,

                'unit_price' => $medicine->price,

                'subtotal' => $subtotal,
            ]);
        }
    }

    /*
    |--------------------------------------------------------------------------
    | BILL TOTALS
    |--------------------------------------------------------------------------
    */

    $serviceTotal = $service ? $service->price : 0;

    $appointmentFee = $r->appointment_fee;

    $grandTotal =
        $serviceTotal
        + $medicineTotal
        + $appointmentFee;

    /*
    |--------------------------------------------------------------------------
    | CREATE BILL AUTOMATICALLY
    |--------------------------------------------------------------------------
    */

    Billing::create([

    'prescription_id' => $prescription->prescription_id,

    'patient_id' => $r->patient_id,

    'doctor_id' => $doctor->doctor_id,

    'treatment' => $service ? $service->name : 'General Prescription',

    'amount' => $grandTotal,

    'appointment_fee' => $appointmentFee,

    'service_total' => $serviceTotal,

    'medicine_total' => $medicineTotal,

    'total_amount' => $grandTotal,

    'status' => 'unpaid',

    'bill_date' => now(),
   ]);


    return redirect()
        ->route('doctor.patients')
        ->with('success', 'Prescription & Bill Created Successfully.');
}

}
