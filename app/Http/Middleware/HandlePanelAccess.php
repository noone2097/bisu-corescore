<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;

class HandlePanelAccess
{
    protected $panelPaths = [
        'research-admin',
        'office-admin',
        'faculty',
        'department',
        'office'
    ];

    public function handle(Request $request, Closure $next)
    {
        $path = trim($request->path(), '/');
        $firstSegment = explode('/', $path)[0];

        // If trying to access any panel path without being authenticated, redirect to calape
        if (in_array($firstSegment, $this->panelPaths) && !auth()->check()) {
            return redirect('/calape');
        }

        return $next($request);
    }
}