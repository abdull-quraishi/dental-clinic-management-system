<?php

namespace App\Http\Controllers\Doctor;

use App\Http\Controllers\Controller;
use App\Models\Billing;
use App\Models\Doctor;

class BillingController extends Controller
{
    public function bills()
   {
     $doctor = Doctor::where('user_id', auth()->id())->first();

     $search = request('search');

     $bills = Billing::with('patient')

         ->where('doctor_id', $doctor->doctor_id)

         ->when($search, function ($query) use ($search) {

             $query->where(function ($q) use ($search) {

                 $q->where('treatment', 'like', "%{$search}%")
                   ->orWhere('status', 'like', "%{$search}%")

                   ->orWhereHas('patient', function ($patient) use ($search) {

                       $patient->where('first_name', 'like', "%{$search}%")
                               ->orWhere('last_name', 'like', "%{$search}%");

                   });

             });

         })

         ->latest()
         ->paginate(5)
         ->withQueryString();

     return view('doctor.billings.all-bills', compact('bills'));
  }

    public function todayBills()
   {
       $doctor = Doctor::where('user_id', auth()->id())->first();
   
       $search = request('search');
   
       $bills = Billing::with('patient')
           ->where('doctor_id', $doctor->doctor_id)
           ->whereDate('created_at', today())
   
           ->when($search, function ($query) use ($search) {
   
               $query->where(function ($q) use ($search) {
   
                   $q->where('treatment', 'like', "%{$search}%")
                     ->orWhere('status', 'like', "%{$search}%")
   
                     ->orWhereHas('patient', function ($patient) use ($search) {
   
                         $patient->where('first_name', 'like', "%{$search}%")
                                 ->orWhere('last_name', 'like', "%{$search}%");
   
                     });
   
               });
   
           })
   
           ->latest()
           ->paginate(5)
           ->withQueryString();
   
       return view('doctor.billings.today-bills', compact('bills'));
   }

}