<x-filament-panels::page.simple>
    <x-slot name="subheading">
        <div class="w-full flex flex-col items-center gap-y-4">
            <div class="flex flex-col items-center gap-y-2">
                
                <div class="text-center space-y-1">
                    <p class="text-sm text-gray-500 dark:text-gray-400">
                        Student Registration
                    </p>
                </div>
            </div>
        </div>
    </x-slot>
    
    <x-filament-panels::form wire:submit="register">
        {{ $this->form }}
        
        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    
    <div class="mt-6 text-center">
        <p class="text-sm text-gray-600 dark:text-gray-400">
            Already have an account?
            <a href="{{ route('filament.students.auth.login') }}" class="text-primary-600 hover:text-primary-500 dark:text-primary-500 dark:hover:text-primary-400 font-medium">
                Sign in
            </a>
        </p>
    </div>
</x-filament-panels::page.simple>
