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

class DoctorDashboardController extends Controller
{

/*
|--------------------------------------------------------------------------
| DASHBOARD
|--------------------------------------------------------------------------
*/
public function dashboard()
{
    $doctor = Doctor::where('user_id', auth()->id())->first();

    if (!$doctor) {
        return view('doctor.dashboard', [
            'doctor' => null,
            'todayAppointments' => collect(),
            'pendingAppointments' => collect(),
            'latestPatients' => collect(),
            'todayCount' => 0,
            'pendingCount' => 0,
            'totalPatients' => 0,
            'billCount' => 0,
            'notifications' => collect(),
            'notificationCount' => 0,
        ]);
    }

    $today = now()->toDateString();

    $baseAppointments = Appointment::with(['patient', 'referredByDoctor', 'referredToDoctor'])
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        });

    $todayAppointments = (clone $baseAppointments)
        ->whereDate('appointment_date', $today)
        ->get();

    $pendingAppointments = (clone $baseAppointments)
        ->where('status', 'Pending')
        ->get();

    $pendingCount = $pendingAppointments->count();
    $todayCount = $todayAppointments->count();

    $latestPatients = Patient::whereHas('appointments', function ($q) use ($doctor) {
        $q->where('doctor_id', $doctor->doctor_id)
          ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
    })->latest()->take(5)->get();

    $totalPatients = Patient::whereHas('appointments', function ($q) use ($doctor) {
        $q->where('doctor_id', $doctor->doctor_id)
          ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
    })->count();

    $billCount = Billing::where('doctor_id', $doctor->doctor_id)->count();

    $notifications = Appointment::with(['patient', 'referredByDoctor', 'referredToDoctor'])
        ->where(function ($q) use ($doctor) {
            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
        })
        ->whereIn('status', ['Pending', 'Approved', 'Rejected'])
        ->latest()
        ->take(4)
        ->get()
        ->map(function ($app) use ($doctor) {
            $message = 'New appointment from patient';

            if ($app->status === 'Pending') {
                $message = 'New pending appointment assigned';
            } elseif ($app->status === 'Approved' && $app->referred_to_doctor_id == $doctor->doctor_id) {
                $message = 'A referred appointment is approved for you';
            } elseif ($app->status === 'Rejected') {
                $message = 'An appointment was rejected';
            }

            return [
                'title' => $message,
                'patient' => $app->patient->first_name ?? 'Patient',
                'date' => $app->appointment_date,
                'status' => $app->status,
                'appointment_id' => $app->appointment_id,
            ];
        });

    $notificationCount = $notifications->count();

    return view('doctor.dashboard', compact(
        'doctor',
        'todayCount',
        'pendingCount',
        'totalPatients',
        'latestPatients',
        'billCount',
        'notifications',
        'notificationCount'
    ));
}


}
