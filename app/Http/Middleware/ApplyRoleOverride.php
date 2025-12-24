<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Support\Facades\Auth;

class ApplyRoleOverride
{
    /**
     * Handle an incoming request.
     */
    public function handle($request, Closure $next)
    {
        if (Auth::check() && $request->session()->has('role_id_override')) {
            $user = Auth::user();
            $override = $request->session()->get('role_id_override');
            // Apply the override to the user instance for this request lifecycle
            $user->id_role = (int) $override;
            Auth::setUser($user);
        }

        return $next($request);
    }
}
