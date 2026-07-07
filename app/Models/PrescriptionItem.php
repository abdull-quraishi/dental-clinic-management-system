<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PrescriptionItem extends Model
{
    protected $fillable = [
        'prescription_id',
        'medicine_id',
        'quantity',
        'unit_price',
        'subtotal',
    ];

    public function prescription()
    {
        return $this->belongsTo(
            Prescription::class,
            'prescription_id',
            'prescription_id'
        );
    }

    public function medicine()
    {
        return $this->belongsTo(Medicine::class);
    }
}
