<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckRole
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next, $roles): Response
    {
        // If no user is logged in, redirect to dashboard
        if (!$request->user()) {
            return redirect()->route('dashboard')
                ->with('error', 'You must be logged in to access this page.');
        }
        
        // Convert comma-separated roles into an array
        $rolesArray = explode(',', $roles);
        
        // Check if the user's role is in the allowed roles array
        if (in_array($request->user()->role, $rolesArray)) {
            return $next($request);
        }
        
        // If the user's role isn't allowed, redirect to dashboard
        return redirect()->route('dashboard')
            ->with('error', 'You do not have permission to access this page.');
    }
}