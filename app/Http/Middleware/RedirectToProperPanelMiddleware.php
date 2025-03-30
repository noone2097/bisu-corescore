<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Filament\Pages\Dashboard;

class RedirectToProperPanelMiddleware
{
    public function handle(Request $request, Closure $next)
    {
        // First, check if user is authenticated
        if (auth()->check()) {
            $path = $request->path();
            
            // If trying to access any calape routes while authenticated
            if (str_starts_with($path, 'calape')) {
                return match(auth()->user()->role) {
                    'office-admin' => redirect()->to('/office-admin'),
                    'research-admin' => redirect()->to('/research-admin'),
                    'department' => redirect()->to('/department'),
                    'faculty' => redirect()->to('/faculty'),
                    'office' => redirect()->to('/office'),
                    default => redirect('/calape')
                };
            }

            // For non-calape panel paths, ensure user is accessing correct panel
            $firstSegment = explode('/', $path)[0];
            $panelPaths = [
                'office-admin',
                'research-admin',
                'department',
                'faculty',
                'office'
            ];

            if (in_array($firstSegment, $panelPaths) && $firstSegment !== auth()->user()->role) {
                return match(auth()->user()->role) {
                    'office-admin' => redirect()->to('/office-admin'),
                    'research-admin' => redirect()->to('/research-admin'),
                    'department' => redirect()->to('/department'),
                    'faculty' => redirect()->to('/faculty'),
                    'office' => redirect()->to('/office'),
                    default => redirect('/calape')
                };
            }
        }
        // If not authenticated and trying to access panel paths
        else {
            $firstSegment = explode('/', trim($request->path(), '/'))[0];
            $panelPaths = [
                'office-admin',
                'research-admin',
                'department',
                'faculty',
                'office'
            ];

            if (in_array($firstSegment, $panelPaths)) {
                return redirect('/calape');
            }
        }

        return $next($request);
    }
}