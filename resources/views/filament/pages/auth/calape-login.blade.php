<x-filament-panels::page.simple>
    <x-slot name="subheading">
        <div class="w-full flex flex-col items-center gap-y-4 mt-0">
            <div class="text-center space-y-2 w-full">
                <p class="text-lg text-gray-500 dark:text-gray-400 ">
                    Calape Campus Portal
                </p>
            </div>
        </div>
    </x-slot>
    
    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}
        
        <x-filament-panels::form.actions
        :actions="$this->getCachedFormActions()"
        :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>
    
    {{-- Divider with proper spacing --}}
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <span class="w-full border-t"></span>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white dark:bg-gray-900 px-2 text-gray-400 dark:text-gray-500">Or continue with</span>
        </div>
    </div>
    
    {{-- Social Login Buttons with proper spacing --}}
    <div class="">
        {{ \Filament\Support\Facades\FilamentView::renderHook('panels::auth.login.form.before') }}
        
        {{-- Display footer actions (social login buttons) --}}
        <div class="w-full">
            @foreach($this->getFooterActions() as $action)
                {{ $action }}
            @endforeach
        </div>
    </div>
</x-filament-panels::page.simple>
