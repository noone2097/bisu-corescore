<x-filament-panels::page.simple>
    <x-filament-panels::form wire:submit="resendNotification">
        <div class="flex flex-col items-center justify-center space-y-6 max-w-md mx-auto">

            <!-- Student Info -->
            <div class="text-center space-y-1">
                <p class="text-base font-medium text-gray-900 dark:text-white">
                    {{ auth('students')->user()->name }}
                </p>
                <p class="text-sm text-gray-500 dark:text-gray-400">
                    {{ auth('students')->user()->email }}
                </p>
            </div>

            <!-- Divider -->
            <div class="w-full border-t border-gray-200 dark:border-gray-700"></div>

            <!-- Verification Message -->
            <div class="text-center">
                <p class="text-sm text-gray-600 dark:text-gray-400">
                    Thanks for registering! Before getting started, please verify your email address by clicking on the link we just emailed to you. If you didn't receive the email, click below to request another one.
                </p>
            </div>

            {{ $this->form }}

            <!-- Resend Button -->
            <div class="w-full">
                <x-filament-panels::form.actions
                    :actions="$this->getFormActions()"
                    :full-width="true"
                />
            </div>
        </div>
    </x-filament-panels::form>
</x-filament-panels::page.simple>