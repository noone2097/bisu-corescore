<x-filament-panels::page>
    <div class="space-y-6">
        {{-- Greeting Section --}}
        <div class="p-6 bg-white rounded-lg shadow">
            <h2 class="text-2xl font-bold text-primary-600">
                Hello Bisuan, {{ auth()->user()->name }}!
            </h2>
            <p class="mt-2 text-gray-600">
                Welcome to your student dashboard. Here you can:
            </p>
            <ul class="mt-4 space-y-2 text-gray-600 list-disc list-inside">
                <li>View and manage your faculty evaluation forms</li>
                <li>Check your course information</li>
                <li>Update your profile</li>
                <li>Monitor your academic progress</li>
            </ul>
        </div>

        {{-- Quick Links Section --}}
        <div class="grid gap-4 md:grid-cols-2 lg:grid-cols-3">
            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-primary-600">Faculty Evaluation</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Evaluate your instructors and provide valuable feedback to help improve the quality of education.
                </p>
            </div>

            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-primary-600">My Course</h3>
                <p class="mt-2 text-sm text-gray-600">
                    View details about your current course and academic requirements.
                </p>
            </div>

            <div class="p-4 bg-white rounded-lg shadow">
                <h3 class="text-lg font-semibold text-primary-600">Profile Settings</h3>
                <p class="mt-2 text-sm text-gray-600">
                    Update your personal information and manage your account settings.
                </p>
            </div>
        </div>
    </div>
</x-filament-panels::page>