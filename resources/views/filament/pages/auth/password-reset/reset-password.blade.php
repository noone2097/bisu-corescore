<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="reset">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    <x-slot name="footer">
        <div class="text-center">
            <a href="{{ route('filament.students.auth.login') }}" class="text-sm text-gray-600 hover:text-primary-500">
                {{ __('filament-panels::pages/auth/password-reset/reset-password.actions.login.label') }}
            </a>
        </div>
    </x-slot>
</x-filament-panels::page.simple>
