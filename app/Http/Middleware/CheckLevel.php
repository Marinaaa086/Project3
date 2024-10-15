<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class CheckLevel
{
    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure(\Illuminate\Http\Request): \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse $next
     * @param mixed ...$levels
     * @return \Illuminate\Http\Response|\Illuminate\Http\RedirectResponse
     */
    public function handle(Request $request, Closure $next, ...$levels)
    {
        // Check if the user is authenticated
        if (!Auth::check()) {
            // Redirect to login page if not authenticated
            return redirect()->route('auth.login'); // Perbaiki nama rutenya disini
        }

        // Check if the user's level is in the allowed levels
        if (in_array($request->user()->level, $levels)) {
            return $next($request);
        }

        // Redirect back if the user level is not authorized
        return back()->with('failed', 'You do not have permission to access this page.');
    }
}
