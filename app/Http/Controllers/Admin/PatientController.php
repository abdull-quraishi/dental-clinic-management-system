<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Patient;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\TreatmentRecord;
use App\Models\Billing;
use Illuminate\Support\Facades\Hash;

class PatientController extends Controller
{
    // this method for showing all patients to admin in one place
    public function patients()
     {
         $search = request('search');

         $patients = User::where('role', 'patient')

             ->when($search, function ($query) use ($search) {

                 $query->where(function ($q) use ($search) {

                     $q->where('name', 'like', "%{$search}%")
                       ->orWhere('email', 'like', "%{$search}%")
                       ->orWhere('phone_number', 'like', "%{$search}%")
                       ->orWhere('address', 'like', "%{$search}%");

                 });
             })
             ->latest()
             ->paginate(10)
             ->withQueryString();

         return view('admin.patients.index', compact('patients'));
     }

    public function patientHistory($user_id)
    {

        $patients = User::where('role', 'patient')->with('patient')->paginate(10);
        $patient = Patient::where('user_id', $user_id)->firstOrFail();

        // Appointments
        $appointments = Appointment::with(['doctor', 'referredByDoctor', 'referredToDoctor'])
            ->where('patient_id', $patient->patient_id)
            ->orderBy('appointment_date', 'desc')
            ->get();

        // Diagnosis / Treatment
        $treatments = TreatmentRecord::with('doctor')
            ->where('patient_id', $patient->patient_id)
            ->orderBy('treatment_date', 'desc')
            ->get();

        // Prescriptions
        $prescriptions = Prescription::with([
            'doctor',
            'service',
            'prescriptionItems.medicine',
            'billing'
        ])
        ->where('patient_id', $patient->patient_id)
        ->orderBy('prescription_date', 'desc')
        ->get();

        // Billing
        $billings = Billing::where('patient_id', $patient->patient_id)
            ->orderBy('created_at', 'desc')
            ->get();

        /*
|--------------------------------------------------------------------------
| BUILD TIMELINE ARRAY
|--------------------------------------------------------------------------
*/
        $timeline = collect();

        // Appointment Timeline
        foreach ($appointments as $app) {
            $timeline[] = [
                'type' => 'Appointment',

                'title' => 'Appointment with Dr. ' . (
                    $app->doctor
                    ? $app->doctor->first_name . ' ' . $app->doctor->last_name
                    : 'Doctor'
                ),

                'date' => $app->appointment_date,

                'doctor' => $app->doctor
                    ? $app->doctor->first_name . ' ' . $app->doctor->last_name
                    : 'Doctor',

                'service_type' => $app->service_type,
                'status' => $app->status,
                'notes' => $app->notes,
                'appointment_message' => $app->appointment_message,

                'referred_to_doctor' => $app->referredToDoctor
                    ? $app->referredToDoctor->first_name . ' ' . $app->referredToDoctor->last_name
                    : null,
            ];
        }

        // Diagnosis Timeline
        foreach ($treatments as $treatment) {
            $timeline->push([
                'type' => 'Diagnosis',
                'title' => 'Diagnosis by Dr. ' .
                    ($treatment->doctor->first_name ?? 'Doctor') . ' ' .
                    ($treatment->doctor->last_name ?? ''),
                'date' => $treatment->treatment_date,
                'diagnosis' => $treatment->diagnosis,
                'treatment_status' => $treatment->treatment_status,
                'notes' => $treatment->notes,
            ]);
        }

       // Prescription + Billing Timeline
       foreach ($prescriptions as $prescription) {

           $bill = $prescription->billing;

           $timeline->push([

               'type' => 'Prescription',

               'title' => 'Prescription by Dr. ' .
                   ($prescription->doctor->first_name ?? 'Doctor') . ' ' .
                   ($prescription->doctor->last_name ?? ''),

               'date' => $prescription->prescription_date,

               // Service
               'service_name' => $prescription->service->name ?? 'General Checkup',

               // Medicines
               'medicines' => $prescription->prescriptionItems,

               // Instructions
               'instructions' => $prescription->instructions,

               // Billing
               'service_total' => $bill->service_total ?? 0,

               'appointment_fee' => $bill->appointment_fee ?? 0,

               'medicine_total' => $bill->medicine_total ?? 0,

               'total_amount' => $bill->total_amount ?? 0,

               'bill_status' => $bill->status ?? 'unpaid',
           ]);
       }


        // Sort newest first
        $timeline = $timeline->sortByDesc('date');

        return view('admin.patients.history', compact(
            'patient',
            'appointments',
            'treatments',
            'prescriptions',
            'billings',
            'timeline'
        ));
    }

//  this is methode is for print patient history
    public function printPatientHistory($user_id)
{
    $patient = Patient::where('user_id', $user_id)->firstOrFail();

    $appointments = Appointment::with(['doctor'])
        ->where('patient_id', $patient->patient_id)
        ->latest('appointment_date')
        ->get();

    $treatments = TreatmentRecord::with('doctor')
        ->where('patient_id', $patient->patient_id)
        ->latest('treatment_date')
        ->get();

    $prescriptions = Prescription::with([
        'doctor',
        'service',
        'prescriptionItems.medicine',
        'billing'
    ])
    ->where('patient_id', $patient->patient_id)
    ->latest('prescription_date')
    ->get();

    return view(
        'admin.patients.print-history',
        compact(
            'patient',
            'appointments',
            'treatments',
            'prescriptions'
        )
    );
}
}
