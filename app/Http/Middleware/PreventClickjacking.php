<?php
namespace App\Http\Middleware;

use Closure;

class PreventClickjacking
{
    public function handle($request, Closure $next)
    {
        // Add the X-Frame-Options header to prevent clickjacking
        $response = $next($request);

        // Set the header to DENY to prevent all framing
        $response->headers->set('X-Frame-Options', 'DENY');
        // Alternatively, use Content-Security-Policy with frame-ancestors
        $response->headers->set('Content-Security-Policy', "frame-ancestors 'none'");
        return $response;
    }
}
