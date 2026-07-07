<?php

namespace App\Http\Controllers\Doctor;
use Carbon\Carbon;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Doctor;
use App\Models\Appointment;


class AppointmentController extends Controller
{


// this method for showing today's appointment to doctor
public function todaysAppointments()
{
    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $today = now()->toDateString();

    $todayAppointments = Appointment::with(['patient', 'referredByDoctor', 'referredToDoctor'])
        ->whereDate('appointment_date', $today)
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        })
        ->latest()
        ->paginate(8);
    $todayCount = $todayAppointments->count();

    return view('doctor.appointments.todays-app', compact('todayAppointments', 'todayCount','doctor','todayCount'));
}

// this method for showing pending appointment to doctor
  public function pendingAppointments()
{
    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();


    $pendingAppointments = Appointment::with(['patient', 'referredByDoctor', 'referredToDoctor'])
        ->where('status', 'Pending')
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        })
        ->latest()
        ->paginate();
        $pendingCount = $pendingAppointments->count();
    return view('doctor.appointments.pending-app', compact('pendingAppointments','pendingCount','doctor'));
}


/*
|--------------------------------------------------------------------------
| APPROVE / REJECT
|--------------------------------------------------------------------------
*/
public function approve($id)
{
    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('appointment_id', $id)
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        })
        ->firstOrFail();

    $appointment->update(['status' => 'Approved']);

    return back()->with('success', 'Appointment Approved Successfully');
}

 public function reject($id)
 {
     $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

     $appointment = Appointment::where('appointment_id', $id)
         ->where(function ($q) use ($doctor) {
             $q->where('doctor_id', $doctor->doctor_id)
               ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
         })
         ->firstOrFail();

     $appointment->update([
         'status' => 'Rejected',
         'appointment_message' => 'Rejected by Dr. ' . $doctor->first_name . ' ' . ($doctor->last_name ?? ''),
     ]);

     return back()->with('success', 'Appointment Rejected Successfully');
 }


    /*
|--------------------------------------------------------------------------
  this is refer methode for doctor to refer patient to specialist doctor
|--------------------------------------------------------------------------
*/
public function refer(Request $request, $id)
{
    if (!auth()->user()->hasRole('general_doctor')) {
             abort(403);
       }

    $request->validate([
        'doctor_id' => 'required|exists:doctors,doctor_id',
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('appointment_id', $id)
        ->where('doctor_id', $doctor->doctor_id)
        ->firstOrFail();

    $specialist = Doctor::findOrFail($request->doctor_id);

    $oldMessage = $appointment->appointment_message
        ? $appointment->appointment_message . "\n"
        : '';

    $appointment->update([
        'referred_by_doctor_id' => $doctor->doctor_id,
        'referred_to_doctor_id' => $specialist->doctor_id,

        'appointment_message' => $oldMessage .
            'You have been referred to Dr. ' .
            $specialist->first_name . ' ' . ($specialist->last_name ?? '') .
            ' by Dr. ' . $doctor->first_name . ' ' . ($doctor->last_name ?? '') . '.',
    ]);

    return back()->with('success', 'Referred to specialist');
}

/*
|--------------------------------------------------------------------------
| this is methde for updating the appointment time
|--------------------------------------------------------------------------
*/
 public function followUp(Request $request, $id)
{
    $request->validate([
        'followup_date' => 'required|date',
        'followup_time' => 'required',
    ]);

    $doctor = Doctor::where('user_id', auth()->id())->firstOrFail();

    $appointment = Appointment::where('appointment_id', $id)
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        })
        ->firstOrFail();

    $newDateTime = Carbon::parse(
        $request->followup_date . ' ' . $request->followup_time
    );

    // Create NEW follow-up appointment
    Appointment::create([
        'patient_id' => $appointment->patient_id,

        // Specialist becomes main doctor now
        'doctor_id' => $doctor->doctor_id,

        'referred_by_doctor_id' => $appointment->doctor_id,

        'referred_to_doctor_id' => null,

        'appointment_date' => $newDateTime,

        'service_type' => $appointment->service_type,

        'status' => 'Pending',

        'priority' => $appointment->priority,

        'notes' => 'Follow-up appointment created by Dr. ' .
                   $doctor->first_name . ' ' . ($doctor->last_name ?? ''),

        'appointment_message' =>
            'Follow-up appointment scheduled on ' .
            $newDateTime->format('Y-m-d h:i A') .
            ' with Dr. ' .
            $doctor->first_name . ' ' . ($doctor->last_name ?? ''),
    ]);

    return back()->with('success', 'Follow-up appointment created successfully.');
}



}
