<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $table = 'appointments';
    protected $primaryKey = 'appointment_id';
    public $timestamps = true;

    protected $fillable = [
        'patient_id',
        'doctor_id',
        'referred_by_doctor_id',
        'referred_to_doctor_id',
        'appointment_date',
        'service_type',
        'status',
        'priority',
        'notes',
        'appointment_message',
    ];

    protected $casts = [
        'appointment_date' => 'datetime',
    ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }

    public function referredByDoctor()
    {
        return $this->belongsTo(Doctor::class, 'referred_by_doctor_id', 'doctor_id');
    }

    public function referredToDoctor()
    {
        return $this->belongsTo(Doctor::class, 'referred_to_doctor_id', 'doctor_id');
    }

     public function referredDoctor()
    {
       return $this->belongsTo(Doctor::class, 'referred_to');
    }

    
}