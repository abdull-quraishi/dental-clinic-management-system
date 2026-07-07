<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Carbon\Carbon;
use Symfony\Component\HttpFoundation\Response;

class AdminAccessTime
{
    public function handle(Request $request, Closure $next): Response
    {
        $user = auth()->user();

        // Not logged in
        if (!$user) {
            return redirect('/login');
        }

        // Super Admin = always allowed
        if ($user->hasRole('super_admin')) {
            return $next($request);
        }

        // Only admin gets time restriction
        if ($user->hasRole('admin')) {

            // If time not set
            if (!$user->admin_start_time || !$user->admin_end_time) {
                auth()->logout();

                return redirect('/login')->withErrors([
                    'email' => 'Admin access time is not configured.',
                ]);
            }

            // Use Afghanistan timezone
            $timezone = 'Asia/Kabul';

            // Current time
            $now = Carbon::now($timezone);

            // Start & End time (today date + saved time)
            $start = Carbon::today($timezone)->setTimeFromTimeString($user->admin_start_time);
            $end   = Carbon::today($timezone)->setTimeFromTimeString($user->admin_end_time);

            // 🔥 IMPORTANT: handle overnight time (مثلاً 10PM → 6AM)
            if ($end->lessThan($start)) {
                $end->addDay();
            }

            // Check access
            if (!$now->between($start, $end)) {

                auth()->logout();

                return redirect('/login')->withErrors([
                    'email' => 'Access denied. Allowed time: '
                        . $start->format('h:i A')
                        . ' → '
                        . $end->format('h:i A'),
                ]);
            }
        }

        return $next($request);
    }
}