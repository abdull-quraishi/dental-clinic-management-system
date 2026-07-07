<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Http\Requests\Auth\LoginRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

use Illuminate\View\View;

class AuthenticatedSessionController extends Controller
{
    /**
     * Display the login view.
     */
    public function create(): View
    {
        return view('auth.login');
    }

    /**
     * Handle an incoming authentication request.
     */
    public function store(LoginRequest $request): RedirectResponse
    {
         $request->authenticate();

       $request->session()->regenerate();

       $user = Auth::user();

       if($user->hasRole('super_admin') || $user->hasRole('admin')){
        return redirect()->route('admin.dashboard');
        }

        else if(
            $user->hasRole('general_doctor') ||
            $user->hasRole('filler_specialist_doctor') ||
            $user->hasRole('extractor_specialist_doctor') ||
            $user->hasRole('cleaner_specialist_doctor') ||
            $user->hasRole('root_canal_specialist_doctor')
        ){
            return redirect()->route('doctor.dashboard');
        }

        else{
            return redirect()->route('patient.dashboard');
        }
    }

    /**
     * Destroy an authenticated session.
     */
    public function destroy(Request $request): RedirectResponse
    {
        Auth::guard('web')->logout();

        $request->session()->invalidate();

        $request->session()->regenerateToken();

        return redirect('/');
    }
}
