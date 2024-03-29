<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class UserAccessDashboardMiddleware
{
    public function handle(Request $request, Closure $next): Response
    {
        if(auth()->user()->isAdmin()){
            return $next($request);
        }
        
        return redirect('/');
    }
}
