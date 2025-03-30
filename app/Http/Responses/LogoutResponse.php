<?php

namespace App\Http\Responses;

use Filament\Facades\Filament;
use Illuminate\Http\RedirectResponse;
use Filament\Http\Responses\Auth\LogoutResponse as BaseLogoutResponse;
 
class LogoutResponse extends BaseLogoutResponse
{
    public function toResponse($request): RedirectResponse
    {
        if (Filament::getCurrentPanel()->getId() === 'office-admin') {
            return redirect()->to('/calape');
        }
        if (Filament::getCurrentPanel()->getId() === 'research-admin') {
            return redirect()->to('/calape');
        }
        if (Filament::getCurrentPanel()->getId() === 'department') {
            return redirect()->to('/calape');
        }
        if (Filament::getCurrentPanel()->getId() === 'faculty') {
            return redirect()->to('/calape');
        }
        if (Filament::getCurrentPanel()->getId() === 'office') {
            return redirect()->to('/calape');
        }
        if (Filament::getCurrentPanel()->getId() === 'students') {
            return redirect()->to('/');
        }
 
        return parent::toResponse($request);
    }
}