<?php

namespace App\Http\Controllers\Patient;

use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
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


     public function create()
    {
        $doctors = Doctor::whereHas('user', function ($query) {
            $query->role('general_doctor');
        })->orderBy('first_name')->get();

        return view('patient.appointments.create', compact('doctors'));
    }

   public function store(Request $request)
  {
     $request->validate([
        'appointment_date' => 'required|date|after_or_equal:today',
        'appointment_time' => 'required',
        'notes' => 'nullable|string|max:2000',
        'priority' => 'nullable|in:Critical,Medium,Low',
        'doctor_id' => 'required|exists:doctors,doctor_id',
    ]);

    $user = $request->user();

    $patient = Patient::firstOrCreate(
        ['user_id' => $user->id],
        [
            'first_name' => $user->name ?? '',
            'last_name' => '',
            'email' => $user->email ?? null,
        ]
    );

    $datetime = Carbon::parse($request->appointment_date . ' ' . $request->appointment_time);

     $selectedDoctor = Doctor::where('doctor_id', $request->doctor_id)
    ->whereHas('user', function ($query) {
        $query->role('general_doctor');
    })
    ->first();

    if (!$selectedDoctor) {
        return back()->withErrors([
            'doctor_id' => 'Please select a valid general doctor.'
        ])->withInput();
    }

    // this code is for to show message if patient try to create appointment for same doctor same day more than one time
    $existingSameDayAppointment = Appointment::where('patient_id', $patient->patient_id)
    ->where('doctor_id', $selectedDoctor->doctor_id)
    ->whereDate('appointment_date', $datetime->toDateString())
    ->whereNotIn('status', ['Rejected', 'Cancelled'])
    ->exists();
    if ($existingSameDayAppointment) {
        return back()->withErrors([
            'doctor_id' => 'You already created an appointment with this doctor today.'
        ])->withInput();
    }

    // ✅ Clinic Working Hours: 8:00 AM to 8:00 PM
    $clinicStart = Carbon::parse($request->appointment_date . ' 08:00');
    $clinicEnd   = Carbon::parse($request->appointment_date . ' 20:00');

    // ✅ Lunch Break: 12:30 PM to 1:30 PM
    $breakStart = Carbon::parse($request->appointment_date . ' 12:30');
    $breakEnd   = Carbon::parse($request->appointment_date . ' 13:30');

    // ❌ Outside clinic hours
    if ($datetime->lt($clinicStart) || $datetime->gt($clinicEnd)) {
        return back()->withErrors([
            'appointment_time' => 'Clinic is open only from 8:00 AM to 8:00 PM.'
        ])->withInput();
    }

    // ❌ Lunch break block
    if ($datetime->between($breakStart, $breakEnd, true)) {
        return back()->withErrors([
            'appointment_time' => '12:30 PM to 1:30 PM is break time. Please choose another slot.'
        ])->withInput();
    }

    // ✅ 15-minute slot protection
    $bufferBefore = $datetime->copy()->subMinutes(15);
    $bufferAfter  = $datetime->copy()->addMinutes(15);

    $existingAppointment = Appointment::where(
        'doctor_id',
        $selectedDoctor->doctor_id
    )
    ->where('appointment_date', $datetime)
    ->whereNotIn('status', ['Rejected', 'Cancelled'])
    ->exists();

    if ($existingAppointment) {
        return back()->withErrors([
            'appointment_time' => 'This time slot is already booked. Please choose another time.'
        ])->withInput();
    }

    DB::beginTransaction();

    try {

        // this code is for do not create appointment for same doctor same day for same patient
        $existingSameDayAppointment = Appointment::where('patient_id', $patient->patient_id)
        ->where('doctor_id', $selectedDoctor->doctor_id)
        ->whereDate('appointment_date', $datetime->toDateString())
        ->whereNotIn('status', ['Rejected', 'Cancelled'])
        ->exists();

       if ($existingSameDayAppointment) {
           return back()->withErrors([
               'doctor_id' => 'You already have an appointment with this doctor on this date. Please choose another doctor or another day.'
           ])->withInput();
       }

        Appointment::create([
            'patient_id' => $patient->patient_id,
           'doctor_id' => $selectedDoctor->doctor_id,
            'appointment_date' => $datetime,
            'service_type' => 'General Checkup',
            'status' => 'Pending',
            'priority' => $request->priority ?? 'Low',
            'notes' => $request->notes,
        ]);

        DB::commit();

        return redirect()->route('patient.appointments.index')
            ->with('success', 'Appointment booked successfully.');

    } catch (\Throwable $e) {

        DB::rollBack();

        return back()
            ->withErrors(['error' => 'Something went wrong. Please try again.'])
            ->withInput();
    }
  }

      //  ----------  this method shows All Appointments----------
     public function appointmentsIndex()
   {
       $user = Auth::user();
       $patient = $this->resolvePatient();

       $appointments = Appointment::with(['doctor','referredByDoctor','referredToDoctor'])
           ->where('patient_id', $patient->patient_id)
           ->orderBy('appointment_date', 'desc')
           ->paginate(5);

            // ✅ مهمه برخه
      $adminViewing = false;
      $patientUser = null;

      if ($user->hasAnyRole(['admin', 'super_admin']) && session()->has('admin_viewing_patient_id')) {
          $adminViewing = true;
          $patientUser = User::find(session('admin_viewing_patient_id'));
      }

       return view('patient.appointments.index', compact('appointments', 'adminViewing', 'patientUser'));
   }

//    ---------- this method shows only upcoming Appointments----------
     public function upcomingAppointments()
   {

   $patient = $this->resolvePatient();

    $appointments = Appointment::with(['doctor','referredByDoctor','referredToDoctor'])
        ->where('patient_id', $patient->patient_id)

        // ✅ Show only today and future appointments
        ->where(function ($query) {
            $query->whereDate('appointment_date', '>', now()->toDateString())
                  ->orWhere(function ($q) {
                      $q->whereDate('appointment_date', now()->toDateString())
                        ->whereTime('appointment_date', '>=', now()->format('H:i:s'));
                  });
        })
        ->orderBy('appointment_date', 'asc')
        ->paginate(5);
       return view('patient.appointments.index', compact('appointments'));
   }

  public function recentappointments(){

      $patient = $this->resolvePatient();

      $recentAppointments = collect();

       $recentAppointments = Appointment::with(['doctor','referredToDoctor'])
              ->where('patient_id', $patient->patient_id)
              ->latest()
              ->paginate(6);

      return view('patient.appointments.recentappointments', compact(
          'recentAppointments',

      ));

  }

     // ----------  this method shows every Appointments details----------
    public function appointmentShow($id)
    {
       $patient = $this->resolvePatient();

        $appointment = Appointment::with('doctor')
            ->where('appointment_id', $id)
            ->where('patient_id', $patient->patient_id)
            ->firstOrFail();

        return view('patient.appointments.show', compact('appointment'));
    }


      //this method for cancel appointment
       public function appointmentCancel(Request $request, $id)
   {
       $patient = $this->resolvePatient();

       $appointment = Appointment::where('appointment_id', $id)
           ->where('patient_id', $patient->patient_id)
           ->firstOrFail();

       if (in_array($appointment->status, ['Pending', 'Approved'])) {
           $appointment->update([
               'status' => 'Rejected',
               'appointment_message' => 'Rejected by patient: ' . $patient->first_name . ' ' . $patient->last_name,
           ]);

           return redirect()
               ->route('patient.appointments.index')
               ->with('success', 'Appointment rejected successfully.');
       }

       return redirect()
           ->route('patient.appointments.index')
           ->with('error', 'Cannot reject this appointment.');
   }


//   this method is for hide selected time slot
    public function availableSlots(Request $request)
{
    $doctorId = $request->doctor_id;
    $date = $request->appointment_date;

    if (!$doctorId || !$date) {
        return response()->json([]);
    }

    $bookedSlots = Appointment::where('doctor_id', $doctorId)
        ->whereDate('appointment_date', $date)
        ->whereNotIn('status', ['Rejected', 'Cancelled'])
        ->pluck('appointment_date')
        ->map(function ($item) {
            return Carbon::parse($item)->format('H:i');
        })
        ->values();

    return response()->json($bookedSlots);
}

}
