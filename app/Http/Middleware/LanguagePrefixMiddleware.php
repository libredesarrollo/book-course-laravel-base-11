<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class LanguagePrefixMiddleware
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {

        $language = $request->segment(1);
        
        if(!in_array($language,['es','en'])){
            return redirect('/es/blog');
        }

        app()->setLocale($language);

        return $next($request);
    }
}
