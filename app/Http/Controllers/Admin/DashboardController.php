<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Doctor;
use App\Models\Appointment;
use App\Models\Prescription;
use App\Models\Billing;
use Carbon\Carbon;

class DashboardController extends Controller
{

public function index()
{
    return view('admin.dashboard',[
        'totalUsers' => User::count(),
        'totalDoctors' => Doctor::count(),
        'appointmentsToday' => Appointment::whereDate('appointment_date',today())->count(),
        'pendingCount' => Appointment::where('status','Pending')->count(),

        'users' => User::latest()->take(5)->get(),

        'appointments' => Appointment::with(['patient','doctor'])
            ->latest()->take(5)->get(),

        'newPrescriptions' => Prescription::where('status','pending')->count(),

        'todayPrescriptions' => Billing::whereDate('paid_date',today())->count(),
    ]);
}



}