<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request ;
use Illuminate\Support\Facades\Auth;

class UserController extends Controller
{
     public function patient(Request $request)
  {
      $role = $request->user()->role;

      if($role === 'user') {
          return view('patient.dashboard'); // user dashboard
      } elseif ($role === 'admin') {
          return redirect()->route('admin.dashboard'); // admin dashboard
      } elseif ($role === 'doctor') {
          return redirect()->route('doctor.dashboard'); // doctor dashboard
      } else {
          abort(403, 'Unauthorized Access');
      }
  }

    public function admin(Request $request)
    {
        $role = $request->user()->role;

      if($role === 'admin') {
          return view('admin.dashboard'); // admin dashboard
      } elseif ($role === 'user') {
          return redirect()->route('patient.dashboard'); // user dashboard
      } elseif ($role === 'doctor') {
          return redirect()->route('doctor.dashboard'); // doctor dashboard
      } else {
          abort(403, 'Unauthorized Access');
      }

    }

     public function doctor(Request $request)
    {
        $role = $request->user()->role;

      if($role === 'doctor') {
          return view('doctor.dashboard'); // doctor dashboard
      } elseif ($role === 'user') {
          return redirect()->route('patient.dashboard'); // user dashboard
      } elseif ($role === 'admin') {
          return redirect()->route('admin.dashboard'); // admin dashboard
      } else {
          abort(403, 'Unauthorized Access');
      }

    }

}
