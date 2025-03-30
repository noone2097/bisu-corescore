<?php

namespace App\Http\Responses;

use Filament\Pages\Dashboard;
use Illuminate\Http\RedirectResponse;
use Livewire\Features\SupportRedirects\Redirector;
use Filament\Http\Responses\Auth\LoginResponse as BaseLoginResponse;
 
class LoginResponse extends BaseLoginResponse
{
    public function toResponse($request): RedirectResponse|Redirector
    {
        $response = null;

        if (auth()->user()?->role === 'office-admin') {
            $response = redirect()->to(Dashboard::getUrl(panel: 'office-admin'));
        }
        else if (auth()->user()?->role === 'research-admin') {
            $response = redirect()->to(Dashboard::getUrl(panel: 'research-admin'));
        }
        else if (auth()->user()?->role === 'department') {
            $response = redirect()->to(Dashboard::getUrl(panel: 'department'));
        }
        else if (auth()->user()?->role === 'faculty') {
            $response = redirect()->to(Dashboard::getUrl(panel: 'faculty'));
        }
        else if (auth()->user()?->role === 'office') {
            $response = redirect()->to(Dashboard::getUrl(panel: 'office'));
        }
        else {
            $response = parent::toResponse($request);
        }

        // Add headers to prevent caching
        if (method_exists($response, 'header')) {
            $response->header('Cache-Control', 'no-store, private, no-cache, must-revalidate');
            $response->header('Pragma', 'no-cache');
            $response->header('Expires', 'Thu, 19 Nov 1981 08:52:00 GMT');
        }

        // Add script to clear history after login
        $script = '<script>
            sessionStorage.clear();
            localStorage.removeItem("filament.redirect");
            history.replaceState({}, "", window.location.href);
            history.pushState({}, "", window.location.href);
        </script>';

        if (method_exists($response, 'withHeaders')) {
            $response->withHeaders([
                'X-Clear-History' => 'true',
                'X-Inject-Script' => base64_encode($script)
            ]);
        }

        return $response;
    }
}