<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use  Notifiable, HasRoles;

   protected $fillable = [
    'avatar',
    'name',
    'email',
    'phone_number',
    'password',
    'address',
    'age',
    'gender',
    'role',
    'admin_start_time',
    'admin_end_time',
   ];

    public function patient()
    {
        return $this->hasOne(Patient::class, 'user_id', 'id');
    }


    public function doctor()
    {
        return $this->hasOne(Doctor::class, 'user_id', 'id');
    }


//  this is a method to check if the user can access the admin panel based on their role and admin time
  public function canAccessAdminPanel(): bool
{
    // Seeder super admin = full access
    if ($this->hasRole('super_admin')) {
        return true;
    }

    // Only normal admin gets time check
    if (!$this->hasRole('admin')) {
        return false;
    }

    if (!$this->admin_start_time || !$this->admin_end_time) {
        return false;
    }

    $now = now()->format('H:i:s');
    $start = \Carbon\Carbon::parse($this->admin_start_time)->format('H:i:s');
    $end = \Carbon\Carbon::parse($this->admin_end_time)->format('H:i:s');

    return $now >= $start && $now <= $end;
}
}