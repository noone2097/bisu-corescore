<?php

namespace App\Providers\Filament\Traits;

use Filament\Panel;

trait PreventBackNavigationTrait
{
    public function configurePanel(Panel $panel): Panel
    {
        return $panel
            ->renderHook(
                'panels::head.end',
                function (): string {
                    $script = "
                        <script>
                            window.addEventListener('DOMContentLoaded', function() {
                                if (!window.preventBackInitialized) {
                                    window.preventBackInitialized = true;
                                    history.pushState(null, document.title, location.href);
                                    window.addEventListener('popstate', function() {
                                        history.pushState(null, document.title, location.href);
                                    });
                                }
                            });
                        </script>
                    ";
                    return '<meta name="user-role" content="' . auth()->user()?->role . '">' . $script;
                }
            )
            ->renderHook(
                'panels::body.end',
                fn (): string => view('filament.components.prevent-back')->render()
            );
    }
}