<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    protected $primaryKey = 'prescription_id';
    protected $fillable = [
        'patient_id',
        'doctor_id',
         'service_id',
        'appointment_fee',
        'prescription_date',
        'instructions',
        'status',



        ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

     // relation to billing
    public function billing() {
        return $this->hasOne(Billing::class, 'prescription_id', 'prescription_id');
    }

      public function service()
      {
          return $this->belongsTo(Service::class);
      }

      public function prescriptionItems()
      {
          return $this->hasMany(
              PrescriptionItem::class,
              'prescription_id',
              'prescription_id'
          );
      }
}
