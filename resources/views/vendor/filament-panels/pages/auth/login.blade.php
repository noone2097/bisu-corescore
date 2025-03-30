<x-filament-panels::page.simple>
    @if (filament()->hasRegistration())
        <x-slot name="subheading">
            {{ __('filament-panels::pages/auth/login.actions.register.before') }}

            {{ $this->registerAction }}
        </x-slot>
    @else
        <x-slot name="subheading">
            <div class="text-center">
                <p class="text-lg text-gray-500 dark:text-gray-400">
                    Calape Campus Portal
                </p>
            </div>
        </x-slot>
    @endif

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_BEFORE, scopes: $this->getRenderHookScopes()) }}

    <x-filament-panels::form wire:submit="authenticate">
        {{ $this->form }}

        <x-filament-panels::form.actions
            :actions="$this->getCachedFormActions()"
            :full-width="$this->hasFullWidthFormActions()"
        />
    </x-filament-panels::form>

    {{ \Filament\Support\Facades\FilamentView::renderHook(\Filament\View\PanelsRenderHook::AUTH_LOGIN_FORM_AFTER, scopes: $this->getRenderHookScopes()) }}
    
    {{-- Divider with proper spacing --}}
    <div class="relative my-6">
        <div class="absolute inset-0 flex items-center">
            <span class="w-full border-t"></span>
        </div>
        <div class="relative flex justify-center text-xs uppercase">
            <span class="bg-white dark:bg-gray-900 px-2 text-gray-400 dark:text-gray-500">Or continue with</span>
        </div>
    </div>
    
    {{-- Social Login Buttons --}}
    <div>
        {{-- Google Sign-in Button --}}
        <div class="w-full">
            <a href="/calape/oauth/google" class="w-full inline-flex items-center justify-center py-2 gap-x-2 rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 dark:focus:ring-offset-gray-800">
                @if(view()->exists('icons.google'))
                    @include('icons.google', ['class' => 'w-5 h-5'])
                @else
                    <svg class="w-5 h-5" viewBox="0 0 24 24">
                        <path fill="currentColor" d="M21.35,11.1H12.18V13.83H18.69C18.36,17.64 15.19,19.27 12.19,19.27C8.36,19.27 5,16.25 5,12C5,7.9 8.2,4.73 12.2,4.73C15.29,4.73 17.1,6.7 17.1,6.7L19,4.72C19,4.72 16.56,2 12.1,2C6.42,2 2.03,6.8 2.03,12C2.03,17.05 6.16,22 12.25,22C17.6,22 21.5,18.33 21.5,12.91C21.5,11.76 21.35,11.1 21.35,11.1V11.1Z" />
                    </svg>
                @endif
                <span>Sign in with Google</span>
            </a>
        </div>
        
        {{-- Forgot Password --}}
        <div class="w-full mt-4">
            <a href="{{ route('filament.calape.auth.password-reset.request') }}" class="w-full inline-flex items-center justify-center py-2 gap-x-2 rounded-lg border border-gray-300 bg-white dark:border-gray-600 dark:bg-gray-700 text-sm font-medium text-gray-700 dark:text-gray-200 shadow-sm hover:bg-gray-50 dark:hover:bg-gray-600 focus:outline-none focus:ring-2 focus:ring-primary-500 focus:ring-offset-1 dark:focus:ring-offset-gray-800">
                <span>Forgot your password?</span>
            </a>
        </div>
    </div>
</x-filament-panels::page.simple>
