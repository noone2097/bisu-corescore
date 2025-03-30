<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Set Up Your Password</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body class="bg-gray-50">
    <div class="min-h-screen flex items-center justify-center p-4">
        <div class="max-w-md w-full bg-white rounded-xl shadow-sm p-8 space-y-6">
            <div class="text-center">
                <svg class="h-14 w-14 mx-auto mb-4 text-blue-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
                <h2 class="text-xl font-medium text-gray-800">Set Up Your Password</h2>
                <p class="text-gray-500 mt-2 text-xs">Please create a secure password for your office account.</p>
            </div>

            @if ($errors->any())
                <div class="bg-red-50 text-red-500 p-4 rounded-lg">
                    <ul class="list-disc list-inside text-sm">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form method="POST" action="{{ route('office.password.setup.store') }}" class="space-y-6">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">

                <div class="space-y-4">
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700 mb-1">New Password</label>
                        <div class="relative">
                            <input type="password" name="password" id="password" required
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-colors text-base">
                            <button type="button" onclick="togglePassword('password')" class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                        <div class="mt-3">
                            <div class="h-1.5 rounded-full bg-gray-100">
                                <div id="strength-bar" class="h-1.5 rounded-full bg-red-400 transition-all duration-300" style="width: 0%"></div>
                            </div>
                            <p id="strength-text" class="text-xs text-gray-500 mt-2">Password strength: Too weak</p>
                        </div>
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Confirm Password</label>
                        <div class="relative">
                            <input type="password" name="password_confirmation" id="password_confirmation" required
                                class="block w-full px-4 py-2.5 rounded-lg border border-gray-200 focus:ring-2 focus:ring-blue-100 focus:border-blue-400 transition-colors text-base">
                            <button type="button" onclick="togglePassword('password_confirmation')" class="absolute right-0 top-0 h-full px-4 text-gray-400 hover:text-gray-600">
                                <i class="fas fa-eye text-lg"></i>
                            </button>
                        </div>
                    </div>
                </div>

                <div class="bg-blue-50 p-4 rounded-lg">
                    <h3 class="text-sm font-medium text-blue-700 mb-2">Password Requirements</h3>
                    <ul class="text-xs space-y-1.5 text-gray-600">
                        <li id="length-check" class="flex items-center">
                            <span class="mr-2">●</span>At least 8 characters long
                        </li>
                        <li id="uppercase-check" class="flex items-center">
                            <span class="mr-2">●</span>Contains uppercase letter
                        </li>
                        <li id="lowercase-check" class="flex items-center">
                            <span class="mr-2">●</span>Contains lowercase letter
                        </li>
                        <li id="number-check" class="flex items-center">
                            <span class="mr-2">●</span>Contains number
                        </li>
                        <li id="special-check" class="flex items-center">
                            <span class="mr-2">●</span>Contains special character
                        </li>
                    </ul>
                </div>

                <button type="submit"
                    class="w-full py-2.5 px-4 border-0 rounded-lg text-sm font-medium text-white bg-blue-500 hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 transition-colors">
                    Set Password
                </button>
            </form>
        </div>
    </div>

    <script>
        function togglePassword(inputId) {
            const input = document.getElementById(inputId);
            const icon = input.nextElementSibling.querySelector('i');
            if (input.type === 'password') {
                input.type = 'text';
                icon.classList.remove('fa-eye');
                icon.classList.add('fa-eye-slash');
            } else {
                input.type = 'password';
                icon.classList.remove('fa-eye-slash');
                icon.classList.add('fa-eye');
            }
        }

        function checkPasswordStrength(password) {
            let strength = 0;
            const checks = {
                length: password.length >= 8,
                uppercase: /[A-Z]/.test(password),
                lowercase: /[a-z]/.test(password),
                number: /[0-9]/.test(password),
                special: /[^A-Za-z0-9]/.test(password)
            };

            // Update requirement checks with dot colors
            document.getElementById('length-check').querySelector('span').style.color = checks.length ? '#059669' : '#9CA3AF';
            document.getElementById('uppercase-check').querySelector('span').style.color = checks.uppercase ? '#059669' : '#9CA3AF';
            document.getElementById('lowercase-check').querySelector('span').style.color = checks.lowercase ? '#059669' : '#9CA3AF';
            document.getElementById('number-check').querySelector('span').style.color = checks.number ? '#059669' : '#9CA3AF';
            document.getElementById('special-check').querySelector('span').style.color = checks.special ? '#059669' : '#9CA3AF';

            // Calculate strength
            strength += checks.length ? 20 : 0;
            strength += checks.uppercase ? 20 : 0;
            strength += checks.lowercase ? 20 : 0;
            strength += checks.number ? 20 : 0;
            strength += checks.special ? 20 : 0;

            // Update strength bar
            const strengthBar = document.getElementById('strength-bar');
            const strengthText = document.getElementById('strength-text');
            
            strengthBar.style.width = strength + '%';
            
            if (strength < 40) {
                strengthBar.classList.remove('bg-yellow-400', 'bg-green-400');
                strengthBar.classList.add('bg-red-400');
                strengthText.textContent = 'Password strength: Too weak';
            } else if (strength < 80) {
                strengthBar.classList.remove('bg-red-400', 'bg-green-400');
                strengthBar.classList.add('bg-yellow-400');
                strengthText.textContent = 'Password strength: Medium';
            } else {
                strengthBar.classList.remove('bg-red-400', 'bg-yellow-400');
                strengthBar.classList.add('bg-green-400');
                strengthText.textContent = 'Password strength: Strong';
            }
        }

        document.getElementById('password').addEventListener('input', (e) => {
            checkPasswordStrength(e.target.value);
        });
    </script>
</body>
</html>