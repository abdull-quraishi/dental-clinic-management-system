<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Patient;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Billing;
use App\Models\TreatmentRecord;
use Carbon\Carbon;

class SystemReportController extends Controller
{
    public function dailyReport(Request $request)
    {
        $from = $request->from_date
            ? Carbon::parse($request->from_date)->startOfDay()
            : today()->startOfDay();

        $to = $request->to_date
            ? Carbon::parse($request->to_date)->endOfDay()
            : today()->endOfDay();

        // Patients
        $newPatients = Patient::whereBetween('created_at', [$from, $to])->count();
        $totalPatients = Patient::count();

        // Appointments
        $appointments = Appointment::whereBetween('appointment_date', [$from, $to]);

        $totalAppointments = $appointments->count();

        $pendingAppointments = Appointment::whereBetween('appointment_date', [$from, $to])
            ->where('status', 'Pending')
            ->count();

        $approvedAppointments = Appointment::whereBetween('appointment_date', [$from, $to])
            ->where('status', 'Approved')
            ->count();

        $completedAppointments = Appointment::whereBetween('appointment_date', [$from, $to])
            ->where('status', 'Completed')
            ->count();

        $rejectedAppointments = Appointment::whereBetween('appointment_date', [$from, $to])
            ->where('status', 'Rejected')
            ->count();

        // Diagnosis
        $diagnosesCount = TreatmentRecord::whereBetween('created_at', [$from, $to])
            ->count();

        // Prescriptions
        $prescriptionsCount = Prescription::whereBetween('prescription_date', [$from, $to])
            ->count();

        // Billing
        $totalBills = Billing::whereBetween('bill_date', [
            $from->toDateString(),
            $to->toDateString()
        ])->count();

        $paidBills = Billing::whereBetween('bill_date', [
            $from->toDateString(),
            $to->toDateString()
        ])
        ->where('status', 'paid')
        ->count();

        $unpaidBills = Billing::whereBetween('bill_date', [
            $from->toDateString(),
            $to->toDateString()
        ])
        ->where('status', 'unpaid')
        ->count();

        $revenue = Billing::whereBetween('bill_date', [
            $from->toDateString(),
            $to->toDateString()
        ])
        ->sum('total_amount');

        // Active Doctors
        $activeDoctors = Appointment::whereBetween('appointment_date', [$from, $to])
            ->distinct('doctor_id')
            ->count('doctor_id');

        // Top Doctor
        $topDoctor = Doctor::withCount([
            'appointments' => function ($query) use ($from, $to) {
                $query->whereBetween('appointment_date', [$from, $to]);
            }
        ])
        ->orderByDesc('appointments_count')
        ->first();

        return view('admin.reports.system-report', compact(
            'from',
            'to',

            'newPatients',
            'totalPatients',

            'totalAppointments',
            'pendingAppointments',
            'approvedAppointments',
            'completedAppointments',
            'rejectedAppointments',

            'diagnosesCount',
            'prescriptionsCount',

            'totalBills',
            'paidBills',
            'unpaidBills',
            'revenue',

            'activeDoctors',
            'topDoctor'
        ));
    }

    public function printDailyReport(Request $request)
{
    $fromDate = $request->from_date ?? today()->toDateString();
    $toDate   = $request->to_date ?? today()->toDateString();

    // Patients
    $totalPatients = \App\Models\Patient::count();

    $newPatients = \App\Models\Patient::whereBetween(
        'created_at',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->count();

    // Appointments
    $totalAppointments = \App\Models\Appointment::whereBetween(
        'appointment_date',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->count();

    $pendingAppointments = \App\Models\Appointment::whereBetween(
        'appointment_date',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->where('status', 'Pending')->count();

    $approvedAppointments = \App\Models\Appointment::whereBetween(
        'appointment_date',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->where('status', 'Approved')->count();

    $completedAppointments = \App\Models\Appointment::whereBetween(
        'appointment_date',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->where('status', 'Completed')->count();

    $rejectedAppointments = \App\Models\Appointment::whereBetween(
        'appointment_date',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->where('status', 'Rejected')->count();

    // Diagnosis
    $diagnosesCount = \App\Models\TreatmentRecord::whereBetween(
        'created_at',
        [
            $fromDate . ' 00:00:00',
            $toDate . ' 23:59:59'
        ]
    )->count();

    // Prescriptions
    $prescriptionsCount = \App\Models\Prescription::whereBetween(
        'prescription_date',
        [
            $fromDate,
            $toDate
        ]
    )->count();

    // Bills
    $totalBills = \App\Models\Billing::whereBetween(
        'bill_date',
        [
            $fromDate,
            $toDate
        ]
    )->count();

    $paidBills = \App\Models\Billing::whereBetween(
        'bill_date',
        [
            $fromDate,
            $toDate
        ]
    )->where('status', 'paid')->count();

    $unpaidBills = \App\Models\Billing::whereBetween(
        'bill_date',
        [
            $fromDate,
            $toDate
        ]
    )->where('status', 'unpaid')->count();

    $revenue = \App\Models\Billing::whereBetween(
        'bill_date',
        [
            $fromDate,
            $toDate
        ]
    )->where('status', 'paid')
     ->sum('total_amount');

    // Doctors
    $activeDoctors = \App\Models\Doctor::count();

    $topDoctor = \App\Models\Doctor::withCount([
        'appointments' => function ($query) use ($fromDate, $toDate) {
            $query->whereBetween(
                'appointment_date',
                [
                    $fromDate . ' 00:00:00',
                    $toDate . ' 23:59:59'
                ]
            );
        }
    ])
    ->orderByDesc('appointments_count')
    ->first();

    return view(
        'admin.reports.print-report',
        compact(
            'fromDate',
            'toDate',

            'totalPatients',
            'newPatients',

            'totalAppointments',
            'pendingAppointments',
            'approvedAppointments',
            'completedAppointments',
            'rejectedAppointments',

            'diagnosesCount',
            'prescriptionsCount',

            'totalBills',
            'paidBills',
            'unpaidBills',
            'revenue',

            'activeDoctors',
            'topDoctor'
        )
    );
}
}