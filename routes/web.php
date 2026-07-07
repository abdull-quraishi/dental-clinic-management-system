<?php

use Illuminate\Support\Facades\Route;
use App\Models\Service;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\RegisteredUserController;
use App\Http\Controllers\ContactController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\DoctorController as AdminDoctorController;
use App\Http\Controllers\Admin\AppointmentController as AdminAppointmentController;
use App\Http\Controllers\Admin\PatientController as AdminPatientController;
use App\Http\Controllers\Admin\PrescriptionBillingController;
use App\Http\Controllers\Admin\DailyDoctorReportController;
use App\Http\Controllers\Admin\SystemReportController;
use App\Http\Controllers\Admin\BillingController as AdminBillingController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\ServiceController;
use App\Http\Controllers\Admin\MedicineController;
use App\Http\Controllers\Admin\ContactMessageController;

use App\Http\Controllers\Doctor\DoctorDashboardController;
use App\Http\Controllers\Doctor\PrescriptionController as DrPrescriptionController;
use App\Http\Controllers\Doctor\DiagnosisController as DrDiagnosisController;
use App\Http\Controllers\Doctor\BillingController;
use App\Http\Controllers\Doctor\PatientsController;
use App\Http\Controllers\Doctor\AppointmentController as DrAppointmentController;
use App\Http\Controllers\HomeDoctorController;

use App\Http\Controllers\Patient\AppointmentController;
use App\Http\Controllers\Patient\MedicalRecordController;
use App\Http\Controllers\Patient\PatientDashboardController;
use App\Http\Controllers\Patient\PrescriptionController as PtPrescriptionController;
use App\Http\Controllers\Patient\BillingController as PtBillingController;

use Illuminate\Support\Facades\App;

Route::middleware('setLocale')->group(function () {

    Route::get('/lang/{locale}', function ($locale) {
        if (in_array($locale, ['en', 'ps', 'fa'])) {

            session(['locale' => $locale]);
            app()->setLocale($locale);
        }
        return back();

    })->name('lang.switch');

});


/*
|--------------------------------------------------------------------------
| Public Routes  for Home Page and other Pages
|--------------------------------------------------------------------------
*/
 Route::middleware('setLocale')
    ->prefix('/')
    ->name('home.')
    ->group(function () {

    Route::view('/', 'home.home')->name('index');
    //this routes for home page services and details to see for all users without login
    Route::get('/services', function () {
    $services = Service::where('status', 1)->latest()->get();
    return view('home.services', compact('services'));
    })->name('services');

     //this routes for home page to show doctors list and doctor details to all users without login
    Route::get('/doctors', [HomeDoctorController::class, 'index'])->name('doctors');
    Route::get('/doctors/{id}', [HomeDoctorController::class, 'show'])->name('doctors.show');
    // this route for contact form to store messages in database
    Route::view('/contact', 'home.contact')->name('contact');
    Route::post('/contact/store', [ContactController::class, 'store'])->name('contact.store');
});



    // Public registration = patient only
    Route::get('/register', [RegisteredUserController::class, 'create'])->name('register');
    Route::post('/register', [RegisteredUserController::class, 'store']);

    // this route for profile show for every user
    Route::middleware('auth')->group(function () {

    Route::get('/profile', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});


/*
|--------------------------------------------------------------------------
| Admin Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth', 'role:admin|super_admin', 'admin.time'])
    ->prefix('admin')
    ->name('admin.')
    ->group(function () {

     // Dashboard
     Route::get('/dashboard', [AdminDashboardController::class, 'index'])->name('dashboard');

        // User Management
         Route::get('/users', [UserController::class, 'index'])->name('users');
        Route::get('/users/create', [UserController::class, 'create'])->name('users.create');
        Route::post('/users/store', [UserController::class, 'store'])->name('users.store');
        Route::get('/users/{id}/edit', [UserController::class, 'edit'])->name('users.edit');
        Route::put('/users/{id}', [UserController::class, 'update'])->name('users.update');
        Route::delete('/users/{id}', [UserController::class,'destroy'])->name('users.destroy');
        Route::get('/users/{id}/profile', [UserController::class, 'profile'])->name('users.profile');

        // Doctors Management
        Route::resource('doctors', AdminDoctorController::class);

        //    this is for admin to create appointment for patient from admin panel
        Route::get('/patients/{user_id}/appointments/create', [AdminAppointmentController::class, 'adminCreate'])
       ->name('patients.appointments.create');
    //   this is for admin to store appointment for patient from admin panel
       Route::post('/patients/{user_id}/appointments/store', [AdminAppointmentController::class, 'adminStore'])
       ->name('patients.appointments.store');

           // Today's Appointments
        Route::get('/today-appointment', [AdminAppointmentController::class, 'todayAppointments'])
            ->name('todayappointment');
        // Pending Appointments
        Route::get('/pending-appointment', [AdminAppointmentController::class, 'pendingAppointments'])
            ->name('pending-appointments');

      //    this route for admin to view patient dashboard with all details
        Route::get('/patients/{user_id}/dashboard', [AdminAppointmentController::class, 'adminDashboard'])->name('patients.dashboard');
    //    this is for admin to exit patient view and return to admin dashboard
       Route::get('/patients/exit-view', [AdminAppointmentController::class, 'exitAdminView'])
    ->name('patients.index.exit-view');

        //this route for admin to manage all patients in one place
        Route::get('/patients', [AdminPatientController::class, 'patients'])->name('patients.index');
       Route::get('/patients/{user_id}/history', [AdminPatientController::class, 'patientHistory'])
       ->middleware('role:admin|super_admin|general_doctor|filler_specialist_doctor|extractor_specialist_doctor|cleaner_specialist_doctor|root_canal_specialist_doctor')
       ->name('patients.history');
       Route::get('/admin/patient-history/{user_id}/print',[AdminPatientController::class, 'printPatientHistory'])
       ->name('patients.history.print');

        // Prescription + Billing
        Route::get('/prescriptions-bills', [PrescriptionBillingController::class, 'index'])->name('prescriptions-bills');

        Route::post('/billings/{id}/mark-paid', [AdminBillingController::class, 'markPaid']) ->name('billings.markPaid');
        Route::get('/admin/prescription-bill/{id}/print',[PrescriptionBillingController::class, 'printPrescriptionBill'])->name('prescription.bill.print');

        // Doctor Reports
        Route::get('/doctor-reports', [DailyDoctorReportController::class, 'doctorReports'])->name('doctor.reports');
        Route::get('/doctor-reports/{doctor_id}', [DailyDoctorReportController::class, 'doctorReportShow'])->name('doctor.reports.show');

        // this is for system reports based on date
        Route::get('/reports/daily-system',[SystemReportController::class,'dailyReport'])->name('reports.daily');
        Route::get('/reports/daily/print',[SystemReportController::class,'printDailyReport'])->name('reports.daily.print');

      // Services
          Route::resource('services', ServiceController::class);

          // Medicines
          Route::resource('medicines', MedicineController::class);

          Route::get('/contact-messages', [ContactMessageController::class, 'index'])
          ->name('contact.messages');

          Route::delete('/contact-messages/{id}', [ContactMessageController::class, 'destroy'])
          ->name('contact.messages.destroy');
    });


/*
|--------------------------------------------------------------------------
| Patient Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'role:patient|admin|super_admin'])
    ->prefix('patient')
    ->name('patient.')
    ->group(function () {

        // Dashboard
        Route::get('/dashboard', [PatientDashboardController::class, 'dashboard'])->name('dashboard');

        // Appointments
        Route::get('/appointments/create', [AppointmentController::class, 'create'])->name('appointments.create');
        Route::post('/appointments', [AppointmentController::class, 'store'])->name('appointments.store');
        Route::get('/available-slots', [AppointmentController::class, 'availableSlots'])->name('available.slots');

        Route::post('/appointments/{id}/cancel', [AppointmentController::class, 'appointmentCancel'])->name('appointments.cancel');
        Route::get('/appointments', [AppointmentController::class, 'appointmentsIndex'])->name('appointments.index');

        Route::get('/appointments/upcoming', [AppointmentController::class, 'upcomingAppointments'])->name('appointments.upcoming');

        Route::get('/appointments/{id}', [AppointmentController::class, 'appointmentShow'])->name('appointments.show');
               // recent appointments
       Route::get('/recentappointments', [AppointmentController::class, 'recentappointments'])->name('recentappointments');

        // Medical Records
        Route::get('/medical-records', [MedicalRecordController::class, 'medicalRecords'])->name('medical.records');
        Route::get('/medical-records/{id}', [MedicalRecordController::class, 'medicalRecordShow'])->name('medical.records.show');

        // Prescriptions
        Route::get('/prescriptions', [PtPrescriptionController::class, 'prescriptions'])->name('prescriptions');
        Route::get('/prescriptions/{id}', [PtPrescriptionController::class, 'prescriptionShow'])->name('prescriptions.show');

        // Billing
        Route::get('/billings', [PtBillingController::class, 'billing'])
            ->name('billings');



    });


/*
|--------------------------------------------------------------------------
| Doctor Routes
|--------------------------------------------------------------------------
*/
Route::middleware(['auth','role:general_doctor|filler_specialist_doctor|extractor_specialist_doctor|
cleaner_specialist_doctor|root_canal_specialist_doctor'
])->prefix('doctor')
->name('doctor.')
->group(function () {

        // Dashboard
        Route::get('/dashboard', [DoctorDashboardController::class, 'dashboard'])->name('dashboard');

        // Patients
        Route::get('/patients', [PatientsController::class, 'patients'])->name('patients');
        Route::get('/latest-patients', [PatientsController::class, 'latestPatients'])->name('latest-patients');
        Route::get('/patient-history/{patient_id}', [PatientsController::class, 'patientHistory'])
            ->name('patient.history');

        // Appointments
        Route::get('/todays-app', [DrAppointmentController::class, 'todaysAppointments'])->name('todays-app');
        Route::get('/pending-app', [DrAppointmentController::class, 'pendingAppointments'])->name('pending-app');
        Route::post('/appointments/{id}/approve', [DrAppointmentController::class, 'approve'])
            ->name('appointments.approve');
        Route::post('/appointments/{id}/reject', [DrAppointmentController::class, 'reject'])
            ->name('appointments.reject');
        Route::post('/appointments/{id}/refer', [DrAppointmentController::class, 'refer'])
            ->name('refer');
        Route::post('/appointments/{id}/follow-up', [DrAppointmentController::class, 'followUp'])
            ->name('followup');

        // Diagnosis
        Route::get('/diagnosis/{patient_id}', [DrDiagnosisController::class, 'diagnosisForm'])
            ->name('diagnosis.form');

        Route::post('/diagnosis', [DrDiagnosisController::class, 'storeDiagnosis'])
            ->name('diagnosis.store');

        // Prescription
        Route::get('/prescription/{patient_id?}', [DrPrescriptionController::class, 'prescriptionForm'])
            ->name('prescription.form');

        Route::post('/prescription', [DrPrescriptionController::class, 'storePrescription'])
            ->name('prescription.store');

        // Billing
       Route::get('/billings', [BillingController::class, 'bills'])
           ->name('all-bills');
       Route::get('/billings/today', [BillingController::class, 'todayBills'])
           ->name('today.bills');



    });


/*
|--------------------------------------------------------------------------
| General Dashboard Redirect
|--------------------------------------------------------------------------
*/
 Route::get('/dashboard', function () {

     $user = auth()->user();

     if ($user->hasRole('super_admin')||$user->hasRole('admin')) {
         return redirect()->route('admin.dashboard');
     }

     if ($user->hasRole('patient')) {
         return redirect()->route('patient.dashboard');
     }

     if (
         $user->hasRole('general_doctor') ||
         $user->hasRole('filler_specialist_doctor') ||
         $user->hasRole('extractor_specialist_doctor') ||
         $user->hasRole('cleaner_specialist_doctor') ||
         $user->hasRole('root_canal_specialist_doctor')
     ) {
         return redirect()->route('doctor.dashboard');
     }

     abort(403);

 })->middleware(['auth'])->name('dashboard');


/*
|--------------------------------------------------------------------------
| Profile Routes
|--------------------------------------------------------------------------
*/
Route::middleware('auth')->group(function () {
    Route::get('/profile/edit', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});


/*
|--------------------------------------------------------------------------
| Breeze Auth Routes
|--------------------------------------------------------------------------
*/

require __DIR__.'/auth.php';
