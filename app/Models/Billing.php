<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Billing extends Model
{
    protected  $table='billings';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'prescription_id',
        'treatment',
        'amount',
        'appointment_fee',
         'service_total',
         'medicine_total',
         'total_amount',
          'status',
         'bill_date',
         'paid_date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }
   public function prescription()
    {
        return $this->belongsTo(Prescription::class, 'prescription_id', 'prescription_id');
    }


}
