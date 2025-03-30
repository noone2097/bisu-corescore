<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Setup Password - {{ config('app.name') }}</title>
    <link href="https://fonts.googleapis.com/css?family=Roboto&display=swap" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <script defer src="https://unpkg.com/alpinejs@3.10.5/dist/cdn.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
        }
        .input-group {
            position: relative;
            margin-bottom: 1.5rem;
        }
        .input-minimal {
            font-size: 0.95rem;
            width: 100%;
            outline: none;
            border: 1px solid #E5E7EB;
            border-radius: 4px;
            padding: 0.6rem 2.5rem 0.6rem 0.75rem;
            color: #4B5563;
            transition: 0.15s ease-out;
            background-color: white;
        }
        .input-minimal:focus {
            border-color: #6366F1;
        }
        .input-label {
            display: block;
            font-size: 0.85rem;
            color: #6B7280;
            margin-bottom: 0.5rem;
        }
        .eye-icon {
            position: absolute;
            right: 0.7rem;
            top: 50%;
            transform: translateY(-50%);
            color: #9CA3AF;
            cursor: pointer;
            transition: color 0.1s ease-out;
            z-index: 2;
            background: transparent;
            border: none;
            padding: 0.25rem;
        }
        .eye-icon:hover {
            color: #6366F1;
        }
        .password-wrapper {
            margin-top: 0.5rem;
        }
        .password-info {
            display: flex;
            align-items: center;
            gap: 0.5rem;
            margin-top: 0.35rem;
        }
        .strength-label {
            font-size: 0.7rem;
            color: #9CA3AF;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        .strength-text {
            font-size: 0.7rem;
            text-transform: uppercase;
            letter-spacing: 0.05em;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-gray-50 to-gray-100">
    <div class="min-h-screen flex flex-col">
        <div class="flex-grow flex items-center justify-center py-6">
            <div class="max-w-md w-full bg-white rounded-xl shadow-sm p-12">
                <div class="flex justify-center mb-6">
                    @include('filament.components.logo')
                </div>

                <div class="text-center mb-8">
                    <h2 class="text-2xl font-thin text-gray-700 mb-1">Welcome, {{ $name }}</h2>
                    <p class="text-sm text-gray-400">Secure your {{ $role }} account</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-8 text-sm">
                        <ul class="list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form method="POST" action="{{ route('password.setup.save') }}" class="space-y-8" x-data="{
                    submitting: false,
                    showPassword: false,
                    showConfirmPassword: false,
                    password: '',
                    strengthText: '',
                    strengthWidth: 0,
                    strengthGradient: '#e5e7eb',
                    checkStrength() {
                        let score = 0;
                        if (this.password.length >= 8) score++;
                        if (this.password.match(/[A-Z]/)) score++;
                        if (this.password.match(/[a-z]/)) score++;
                        if (this.password.match(/[0-9]/)) score++;
                        if (this.password.match(/[^A-Za-z0-9]/)) score++;
                        
                        if (this.password.length === 0) {
                            this.strengthText = '';
                            this.strengthWidth = 0;
                            return;
                        }
                        
                        this.strengthWidth = (score/5) * 100;
                        
                        if (score <= 1) {
                            this.strengthText = 'weak';
                            this.strengthGradient = '#dc2626';
                        } else if (score <= 3) {
                            this.strengthText = 'moderate';
                            this.strengthGradient = '#ca8a04';
                        } else {
                            this.strengthText = 'strong';
                            this.strengthGradient = '#16a34a';
                        }
                    }
                }" @submit="submitting = true">
                    @csrf
                    <input type="hidden" name="email" value="{{ $email }}">
                    <input type="hidden" name="role" value="{{ $role }}">
                    
                    <div class="space-y-6">
                        <div class="space-y-5">
                            <div class="input-group">
                                <label for="password" class="input-label">Password</label>
                                <div class="relative">
                                    <input
                                        :type="showPassword ? 'text' : 'password'"
                                        name="password"
                                        id="password"
                                        x-model="password"
                                        @input="checkStrength()"
                                        required
                                        class="input-minimal"
                                    >
                                    <button
                                        type="button"
                                        @click="showPassword = !showPassword"
                                        class="eye-icon"
                                    >
                                        <i class="fas" :class="showPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                                <div class="password-wrapper">
                                    <div class="password-info">
                                        <span class="strength-label">Strength</span>
                                        <div class="flex-1 h-1.5 bg-gray-200 rounded-full overflow-hidden">
                                            <div 
                                                class="h-full transition-all duration-500"
                                                :style="{ 
                                                    width: strengthWidth + '%',
                                                    backgroundColor: strengthGradient
                                                }"
                                            ></div>
                                        </div>
                                        <template x-if="strengthText === 'weak'">
                                            <span class="strength-text font-medium text-red-600" x-text="strengthText"></span>
                                        </template>
                                        <template x-if="strengthText === 'moderate'">
                                            <span class="strength-text font-medium text-yellow-600" x-text="strengthText"></span>
                                        </template>
                                        <template x-if="strengthText === 'strong'">
                                            <span class="strength-text font-medium text-green-600" x-text="strengthText"></span>
                                        </template>
                                    </div>
                                </div>
                            </div>

                            <div class="input-group">
                                <label for="password_confirmation" class="input-label">Confirm Password</label>
                                <div class="relative">
                                    <input
                                        :type="showConfirmPassword ? 'text' : 'password'"
                                        name="password_confirmation"
                                        id="password_confirmation"
                                        required
                                        class="input-minimal"
                                    >
                                    <button
                                        type="button"
                                        @click="showConfirmPassword = !showConfirmPassword"
                                        class="eye-icon"
                                    >
                                        <i class="fas" :class="showConfirmPassword ? 'fa-eye-slash' : 'fa-eye'"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-12">
                        <button
                            type="submit"
                            class="w-full flex justify-center py-3 px-4 border-0 rounded-lg text-sm font-medium text-white bg-gradient-to-r from-indigo-500 to-indigo-600 hover:from-indigo-600 hover:to-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 transition-all duration-200 transform hover:scale-[1.02] hover:shadow-lg"
                            :disabled="submitting"
                        >
                            <span x-show="!submitting">Set Password</span>
                            <span x-show="submitting" class="flex items-center">
                                <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                                </svg>
                                Setting Password...
                            </span>
                        </button>
                        
                        <div class="mt-4 text-center text-sm text-gray-500">
                            <p class="mb-2 font-medium">Tips for a strong password:</p>
                            <div class="space-y-1 text-left">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs text-green-500"></i>
                                    <span>At least 8 characters long</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs text-green-500"></i>
                                    <span>Include uppercase & lowercase letters</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs text-green-500"></i>
                                    <span>Include numbers (0-9)</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-check text-xs text-green-500"></i>
                                    <span>Include special characters (!@#$%^&*)</span>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <footer class="py-4 text-center text-sm text-gray-400 mt-auto">
            &copy; {{ date('Y') }} <span class="font-medium text-gray-600">{{ config('app.name') }}</span>. All rights reserved.
        </footer>
    </div>
</body>
</html>