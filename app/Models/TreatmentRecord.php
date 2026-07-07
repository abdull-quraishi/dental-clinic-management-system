<?php
namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TreatmentRecord extends Model
{
    protected $primaryKey = 'treatment_id';
    protected $fillable = [
        'patient_id',
        'doctor_id',
        'diagnosis',
        'treatment_plan',
        'treatment_status',
        'treatment_date',
        ];

    public function patient()
    {
        return $this->belongsTo(Patient::class, 'patient_id', 'patient_id');
    }

    public function doctor()
    {
        return $this->belongsTo(Doctor::class, 'doctor_id', 'doctor_id');
    }
}
