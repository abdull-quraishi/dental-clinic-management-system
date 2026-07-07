<?php

namespace App\Http\Controllers\Admin;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Billing;
use Carbon\Carbon;

class DailyDoctorReportController extends Controller
{

  public function doctorReports(Request $request)
{
    $today = today()->toDateString();

    $doctors = Doctor::with('user')

        ->when($request->search, function ($query) use ($request) {

            $query->where('first_name', 'like', '%' . $request->search . '%')
                  ->orWhere('last_name', 'like', '%' . $request->search . '%')
                  ->orWhere('email', 'like', '%' . $request->search . '%');

        })

        ->paginate(8)

        ->withQueryString();

    $doctors->getCollection()->transform(function ($doctor) use ($today) {

        $appointmentsToday = Appointment::whereDate('appointment_date', $today)
            ->where(function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->doctor_id)
                  ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
            })->count();

        $patientsToday = Appointment::whereDate('appointment_date', $today)
            ->where(function ($q) use ($doctor) {
                $q->where('doctor_id', $doctor->doctor_id)
                  ->orWhere('referred_to_doctor_id', $doctor->doctor_id);
            })
            ->distinct('patient_id')
            ->count('patient_id');

        $prescriptionsToday = Prescription::whereDate('prescription_date', $today)
            ->where('doctor_id', $doctor->doctor_id)
            ->count();

        $billsToday = Billing::whereDate('bill_date', $today)
            ->where('doctor_id', $doctor->doctor_id)
            ->count();

        $totalAmountToday = Billing::whereDate('bill_date', $today)
            ->where('doctor_id', $doctor->doctor_id)
            ->sum('amount');

        return [
            'doctor' => $doctor,
            'appointments_today' => $appointmentsToday,
            'patients_today' => $patientsToday,
            'prescriptions_today' => $prescriptionsToday,
            'bills_today' => $billsToday,
            'total_amount_today' => $totalAmountToday,
        ];
    });

    return view('admin.doctors.daily-reports', compact('doctors'));
}

public function doctorReportShow($doctor_id)
{
    $today = today()->toDateString();

    $doctor = Doctor::with('user')->findOrFail($doctor_id);

    // appointments including referred patients
    $appointments = Appointment::with([
            'patient',
            'doctor',
            'referredByDoctor'
        ])
        ->whereDate('appointment_date', $today)
        ->where(function ($q) use ($doctor) {

            $q->where('doctor_id', $doctor->doctor_id)
              ->orWhere('referred_to_doctor_id', $doctor->doctor_id);

        })
        ->latest()
        ->get();

    // prescriptions
    $prescriptions = Prescription::with('patient')
        ->whereDate('prescription_date', $today)
        ->where('doctor_id', $doctor->doctor_id)
        ->latest()
        ->get();

    // billings
    $billings = Billing::with('patient')
        ->whereDate('bill_date', $today)
        ->where('doctor_id', $doctor->doctor_id)
        ->latest()
        ->get();

    // summary counts
    $appointmentsToday = $appointments->count();

    $patientsToday = $appointments
        ->pluck('patient_id')
        ->unique()
        ->count();

    $prescriptionsToday = $prescriptions->count();

    $billsToday = $billings->count();

    $totalAmountToday = $billings->sum('amount');

    return view('admin.doctors.daily-report-show', compact(
        'doctor',
        'appointments',
        'prescriptions',
        'billings',
        'appointmentsToday',
        'patientsToday',
        'prescriptionsToday',
        'billsToday',
        'totalAmountToday'
    ));
}


}