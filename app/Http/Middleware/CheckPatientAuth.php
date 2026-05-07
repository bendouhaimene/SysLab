<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class CheckPatientAuth
{
    public function handle(Request $request, Closure $next): mixed
    {
        if (!auth('patient')->check()) {
            return redirect()->route('patient.login');
        }

        return $next($request);
    }
}