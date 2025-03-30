<?php

namespace App\Livewire\Auth;

use Filament\Forms\Form;
use Filament\Pages\Auth\Login as BaseLogin;
use Illuminate\Routing\RedirectResponse;

class Login extends BaseLogin
{
    public function mount(): void
    {
        if (! auth()->check()) {
            redirect('/calape')->send();
            return;
        }

        parent::mount();
    }

    public function authenticate(): RedirectResponse
    {
        return redirect('/calape');
    }
}