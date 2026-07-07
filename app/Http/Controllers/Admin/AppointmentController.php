<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\TreatmentRecord;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AppointmentController extends Controller
{

   // this method for admin to view patient dashboard with all details
  public function adminDashboard($user_id)
  {
      $user = User::findOrFail($user_id);
      if (auth()->user()->hasRole('admin') || auth()->user()->hasRole('super_admin')) {
    session(['admin_viewing_patient_id' => $user->id]);
       } else {
          abort(403, 'Unauthorized');
      }

      $patient = Patient::firstOrCreate(
          ['user_id' => $user->id],
          [
              'first_name' => $user->name ?? '',
              'last_name' => '',
              'email' => $user->email ?? null,
          ]
      );

      $appointments = Appointment::where('patient_id', $patient->patient_id)->count();
      $prescriptionsCount = Prescription::where('patient_id', $patient->patient_id)->count();
      $medicalRecordsCount = TreatmentRecord::where('patient_id', $patient->patient_id)->count();

      if (class_exists(\App\Models\Billing::class)) {
          $billingCount = \App\Models\Billing::where('patient_id', $patient->patient_id)->count();
      } else {
          $billingCount = 0;
      }

      $recentAppointments = Appointment::with(['doctor', 'referredToDoctor'])
          ->where('patient_id', $patient->patient_id)
          ->latest()
          ->take(5)
          ->get();

      return view('patient.dashboard', compact(
          'appointments',
          'prescriptionsCount',
          'medicalRecordsCount',
          'billingCount',
          'recentAppointments'
      ))->with('adminViewing', true)->with('patientUser', $user);
  }

//   this method for admin to exit patient dashboard view and return to admin users list
     public function exitAdminView()
    {
        session()->forget('admin_viewing_patient_id');

        return redirect()->route('admin.patients.index');
    }


//   this method for admin to create appointment for patient from admin panel
    public function adminCreate($user_id)
  {
      $user = User::findOrFail($user_id);

      $patient = Patient::firstOrCreate(
          ['user_id' => $user->id],
          [
              'first_name' => $user->name,
              'last_name' => '',
              'email' => $user->email,
          ]
      );

      $doctors = Doctor::whereHas('user', function ($q) {
          $q->role('general_doctor');
      })->get();

      return view('admin.appointments.create', compact('patient', 'doctors', 'user'));
  }

  //  this method for admin to store appointment for patient from admin panel
    public function adminStore(Request $request, $user_id)
  {
      $user = User::findOrFail($user_id);

      $patient = Patient::where('user_id', $user->id)->firstOrFail();

      $request->validate([
          'doctor_id' => 'required|exists:doctors,doctor_id',
          'service_type' => 'nullable|string|max:255',
          'appointment_date' => 'required|date|after_or_equal:today',
          'appointment_time' => 'required',
          'priority' => 'required|in:Low,Medium,Critical',
          'notes' => 'nullable|string|max:1000',
      ]);

      // ✅ Combine date + time
      $datetime = Carbon::parse(
          $request->appointment_date . ' ' . $request->appointment_time
      );

    //   this code is for to show message if patient try to create appointment for same doctor same day more than one time by admin
    $existingSameDayAppointment = Appointment::where('patient_id', $patient->patient_id)
    ->where('doctor_id', $request->doctor_id)
    ->whereDate('appointment_date', $datetime->toDateString())
    ->whereNotIn('status', ['Rejected', 'Cancelled'])
    ->exists();
     if ($existingSameDayAppointment) {
         return back()->withErrors([
             'doctor_id' => 'This patient already has an appointment with this doctor today.'
         ])->withInput();
     }

      // ✅ Clinic hours
      $clinicStart = Carbon::parse($request->appointment_date . ' 08:00');
      $clinicEnd   = Carbon::parse($request->appointment_date . ' 20:00');

      // ✅ Break
      $breakStart = Carbon::parse($request->appointment_date . ' 12:30');
      $breakEnd   = Carbon::parse($request->appointment_date . ' 13:30');

      if ($datetime->lt($clinicStart) || $datetime->gt($clinicEnd)) {
          return back()->withErrors([
              'appointment_time' => 'Clinic is open only from 8:00 AM to 8:00 PM.'
          ])->withInput();
      }

      if ($datetime->between($breakStart, $breakEnd, true)) {
          return back()->withErrors([
              'appointment_time' => '12:30 PM to 1:30 PM is break time.'
          ])->withInput();
      }

      // ✅ Only same doctor same slot blocked
      $existingAppointment = Appointment::where('doctor_id', $request->doctor_id)
          ->where('appointment_date', $datetime)
          ->whereNotIn('status', ['Rejected'])
          ->exists();

      if ($existingAppointment) {
          return back()->withErrors([
              'appointment_time' => 'This doctor already has an appointment at this time.'
          ])->withInput();
      }

    //  this code is for do not create appointment for same doctor same day for same patient by admin
      $existingSameDayAppointment = Appointment::where('patient_id', $patient->patient_id)
    ->where('doctor_id', $request->doctor_id)
    ->whereDate('appointment_date', $datetime->toDateString())
    ->whereNotIn('status', ['Rejected', 'Cancelled'])
    ->exists();

      if ($existingSameDayAppointment) {
          return back()->withErrors([
              'doctor_id' => 'This patient already has an appointment with this doctor on the same day.'
          ])->withInput();
      }

      Appointment::create([
          'patient_id' => $patient->patient_id,
          'doctor_id' => $request->doctor_id,
          'service_type' => $request->service_type ?? 'General Checkup',
          'appointment_date' => $datetime,
          'priority' => $request->priority,
          'notes' => $request->notes,
          'status' => 'Pending',
      ]);

      return redirect()
          ->route('patient.appointments.index', $user->id)
          ->with('success', 'Appointment created successfully for patient.');
  }



     /*
 |--------------------------------------------------------------------------
 | TODAYS APPOINTMENTS (FIX)
 |--------------------------------------------------------------------------
 */
 public function todayAppointments()
{
    $search = request('search');

    $todayappointments = Appointment::with(['patient','doctor'])

       ->whereDate('appointment_date', Carbon::today())
          ->when($search, function ($query) use ($search) {
              $query->where(function ($q) use ($search) {

                  $q->whereHas('patient', function ($patient) use ($search) {

                      $patient->where('first_name', 'like', "%{$search}%")
                              ->orWhere('last_name', 'like', "%{$search}%");

                  })

                  ->orWhereHas('doctor', function ($doctor) use ($search) {

                      $doctor->where('first_name', 'like', "%{$search}%")
                             ->orWhere('last_name', 'like', "%{$search}%");

                  })

                  ->orWhere('status', 'like', "%{$search}%");

              });

          })

          ->latest()
          ->paginate(8)
          ->withQueryString();

      return view('admin.appointments.today-apointments', compact('todayappointments'));
  }
   /*
 |--------------------------------------------------------------------------
 | PENDING APPOINTMENTS (FIX)
 |--------------------------------------------------------------------------
 */
 public function pendingAppointments()
 {
     $pendingappointments = Appointment::with(['patient','doctor'])->where('status', 'Pending')
         ->latest()
         ->paginate(8);

     return view('admin.appointments.pending-apointments', compact('pendingappointments'));
 }

}