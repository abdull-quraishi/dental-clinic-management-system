<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    protected $table = 'doctors';
    protected $primaryKey = 'doctor_id'; // که مو id وي، دا کرښه حذف کړه

    protected $fillable = [
     'user_id',
     'first_name',
     'last_name',
     'email',
     'role',
     'password',
     'phone_number',
     'address',
     'image',
     'bio'
     ];

    public function user()
    {
        return $this->belongsTo(\App\Models\User::class, 'user_id');
    }

    public function appointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'doctor_id', 'doctor_id');
    }

    public function prescriptions()
    {
        return $this->hasMany(\App\Models\Prescription::class, 'doctor_id', 'doctor_id');
    }

    public function treatmentRecords()
    {
        return $this->hasMany(\App\Models\TreatmentRecord::class, 'doctor_id', 'doctor_id');
    }

    public function referredAppointments()
    {
        return $this->hasMany(\App\Models\Appointment::class, 'referred_to_doctor_id', 'doctor_id');
    }

    public function billings()
    {
        return $this->hasMany(Billing::class);
    }
}
