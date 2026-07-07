<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Service extends Model
{
    protected $fillable = [
        'name',
        'price',
        'description',
         'image',
        'status',
    ];

    public function prescriptions()
    {
        return $this->hasMany(Prescription::class);
    }
}