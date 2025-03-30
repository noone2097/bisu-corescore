<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class PreventBackHistory
{
    public function handle(Request $request, Closure $next)
    {
        // Process the request
        $response = $next($request);
        
        if (method_exists($response, 'header')) {
            // Prevent caching completely
            $response->header('Cache-Control', 'no-store, private, no-cache, must-revalidate, max-age=0, post-check=0, pre-check=0');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', 'Sat, 01 Jan 2000 00:00:00 GMT');
            
            // Add extra headers to prevent back/forward cache
            $response->header('X-Frame-Options', 'DENY');
            
            // Force revalidation
            $response->header('Last-Modified', gmdate('D, d M Y H:i:s').' GMT');
        }

        return $response;
    }
}