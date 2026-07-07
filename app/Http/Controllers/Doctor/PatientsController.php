<?php

namespace App\Http\Controllers\Doctor;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\User;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\TreatmentRecord;
use App\Models\Billing;
use Illuminate\Support\Facades\Auth;

class PatientsController extends Controller
{

/*
|--------------------------------------------------------------------------
| PATIENTS OF THIS DOCTOR
|--------------------------------------------------------------------------
*/
  public function patients()
  {
      $doctor = Doctor::where('user_id', auth()->id())->first();

      if (!$doctor) {
          return view('doctor.patients', ['patients' => collect()]);
      }

      $search = request('search');
     $patients = Patient::query()

   ->when($search, function ($query) use ($search) {

     $query->where(function ($q) use ($search) {

         $q->where('first_name', 'like', "%{$search}%")
           ->orWhere('last_name', 'like', "%{$search}%")
           ->orWhere('phone_number', 'like', "%{$search}%");

     });

 })->whereHas('appointments', function ($q) use ($doctor) {

        // ✅ Show both:
        // 1) Original doctor appointments
        // 2) Referred appointments sent to this specialist doctor
        $q->where('doctor_id', $doctor->doctor_id)
          ->orWhere('referred_to_doctor_id', $doctor->doctor_id);

    })
    ->with([
        'appointments' => function ($q) use ($doctor) {
            $q->where(function ($subQ) use ($doctor) {
                $subQ->where('doctor_id', $doctor->doctor_id)
                     ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
            })
            ->with(['doctor', 'referredByDoctor', 'referredToDoctor'])
            ->latest();
        }
    ])->distinct()->paginate(5)->withQueryString();

    return view('doctor.patients.index', compact('patients'));
}


// this method for showing patient history to doctor
  public function patientHistory($patient_id)
{

    $currentDoctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $patients = User::where('role', 'patient')->with('patient')->paginate(10);
    $patient = Patient::where('patient_id', $patient_id)->firstOrFail();

    // Appointments
    $appointments = Appointment::with(['doctor', 'referredByDoctor', 'referredToDoctor'])
        ->where('patient_id', $patient->patient_id)
         ->where(function($query) use ($currentDoctor) {
             $query->where('doctor_id', $currentDoctor->doctor_id)
                   ->orWhere('referred_by_doctor_id', $currentDoctor->doctor_id)
                   ->orWhere('referred_to_doctor_id', $currentDoctor->doctor_id);

         })
        ->orderBy('appointment_date', 'desc')
        ->get();

    // Diagnosis / Treatment
   $treatments = TreatmentRecord::with('doctor')
    ->where('patient_id', $patient->patient_id)
    ->where('doctor_id', $currentDoctor->doctor_id)
    ->orderBy('treatment_date', 'desc')
    ->get();

    // Prescriptions
     $prescriptions = Prescription::with([
         'doctor',
         'service',
         'billing',
         'prescriptionItems.medicine'
     ])
     ->where('patient_id', $patient->patient_id)
     ->where('doctor_id', $currentDoctor->doctor_id)
     ->orderBy('prescription_date', 'desc')
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

    // Prescription Timeline
   // Prescription + Billing Timeline
foreach ($prescriptions as $prescription) {

    $timeline->push([

        'type' => 'Prescription',

        'title' => 'Prescription & Bill by Dr. ' .
            ($prescription->doctor->first_name ?? 'Doctor') . ' ' .
            ($prescription->doctor->last_name ?? ''),

        'date' => $prescription->prescription_date,

        'instructions' => $prescription->instructions,

        // service
        'service_name' => $prescription->service->name ?? 'No Service',

        // medicines
        'medicines' => $prescription->prescriptionItems,

        // billing
        'appointment_fee' => $prescription->billing->appointment_fee ?? 0,

        'service_total' => $prescription->billing->service_total ?? 0,

        'medicine_total' => $prescription->billing->medicine_total ?? 0,

        'total_amount' => $prescription->billing->total_amount ?? 0,

        'bill_status' => $prescription->billing->status ?? 'unpaid',
     ]);
  }


    // Sort newest first
    $timeline = $timeline->sortByDesc('date');

    return view('doctor.patients.patient-history', compact(
        'patient',
        'patients',
        'appointments',
        'treatments',
        'prescriptions',
        'timeline'
    ));
}


// this method for showing latest patients to doctor
public function latestPatients()
{
    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $latestPatients = Patient::whereHas('appointments', function ($q) use ($doctor) {
        $q->where('doctor_id', $doctor->doctor_id)
          ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
    })->latest()->paginate(5);


    return view('doctor.patients.latest-patients', compact('latestPatients','doctor'));
}

}
