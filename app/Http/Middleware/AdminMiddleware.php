<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
use Illuminate\Support\Facades\Auth;

class AdminMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        //here we use admin because same guard apply for both admin and user but this middleware only apply to admin auth
        if (Auth::guard('custom_web')->check()) {
            $user = Auth::guard('custom_web')->user();
            if ($user->role === 'admin') {
                return $next($request);
            }
        }
        return redirect()->route('signin');
    }
}
