<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - BISU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400;500;600&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://unpkg.com/aos@2.3.1/dist/aos.css" rel="stylesheet">
    <script src="https://unpkg.com/aos@2.3.1/dist/aos.js"></script>
    @include('feedback.partials.styles')
</head>
<body class="bg-gradient-to-br from-white to-blue-50 min-h-screen font-inter">
    <div class="max-w-5xl mx-auto px-3 sm:px-4 py-4 sm:py-12">
        <!-- Header Section -->
        <div class="flex mb-8 items-start justify-between">
            <!-- Left Section -->
            <div class="flex items-start gap-1.5 sm:gap-3">
                <!-- Logo -->
                <div class="flex flex-col items-start w-10 sm:w-16 md:w-20 mt-3 sm:mt-6">
                    <img src="{{ asset('images/bisu_logo.png') }}"
                         alt="BISU Logo"
                         class="w-full h-auto logo-image"
                         loading="lazy"
                         decoding="async"
                         width="90"
                         height="90"
                         onload="this.classList.add('loaded')" />
                </div>
                
                <!-- Institution Details -->
                <div class="flex flex-col justify-start mt-0.5 sm:mt-2">
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">Republic of the Philippines</p>
                    <p class="text-[0.55rem] sm:text-base md:text-base font-medium text-gray-700">BOHOL ISLAND STATE UNIVERSITY</p>
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">San Isidro, Calape, 6328 Bohol, Philippines</p>
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">Office of the Administration</p>
                    <p class="text-[0.45rem] sm:text-xs md:text-xs text-gray-500 italic mt-0.5">Balance | Integrity | Stewardship | Uprightness</p>
                </div>
            </div>

            <!-- Additional Logos -->
            <div class="flex items-center gap-2 sm:gap-3 mt-1">
                <div class="w-12 sm:w-16 md:w-20">
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}"
                         alt="Bagong Pilipinas Logo"
                         class="w-full h-auto logo-image"
                         loading="lazy"
                         decoding="async"
                         onload="this.classList.add('loaded')" />
                </div>
                <div class="flex items-center gap-1.5 sm:gap-2">
                    <div class="w-9 sm:w-12 md:w-14">
                        <img src="{{ asset('images/tuv-logo.png') }}"
                             alt="TUV Logo"
                             class="w-full h-auto logo-image"
                             loading="lazy"
                             decoding="async"
                             onload="this.classList.add('loaded')" />
                    </div>
                    <div class="flex flex-col text-[6px] sm:text-[7px] md:text-[9px] lg:text-[10px] leading-tight text-gray-600">
                        <p>Management System</p>
                        <p>ISO 9001:2015</p>
                        <p class="mt-0.5">www.tuv.com</p>
                        <p>ID: 9108658239</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Thank You Content -->
        <div class="flex items-center justify-center min-h-[60vh]">
            <div class="w-full max-w-lg mx-auto bg-white rounded-2xl shadow-xl border border-blue-100/50 p-8 sm:p-12 m-4 backdrop-blur-sm" data-aos="fade-up">
                <div class="space-y-8 text-center">
                    <!-- Animated Checkmark -->
                    <div class="flex justify-center">
                        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gradient-to-br from-blue-50 to-blue-100 rounded-full flex items-center justify-center transform transition-all duration-500 hover:scale-110 hover:rotate-3 hover:shadow-lg">
                            <svg class="w-8 h-8 sm:w-10 sm:h-10 text-blue-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2.5" d="M5 13l4 4L19 7"></path>
                            </svg>
                        </div>
                    </div>

                    <!-- Thank You Message -->
                    <div class="space-y-4">
                        <h2 class="text-3xl sm:text-4xl font-semibold text-gray-800 tracking-tight">
                            <span class="bg-gradient-to-r from-blue-600 to-blue-500 bg-clip-text text-transparent">Thank You!</span>
                        </h2>
                        <p class="text-base sm:text-lg text-gray-600 mx-auto px-4 leading-relaxed">
                            Your feedback has been successfully submitted. 
                            <span class="block mt-2 text-gray-600/80 text-sm sm:text-base font-medium">
            We appreciate your time and valuable contribution to our improvement process.
        </span>
                        </p>
                    </div>

                    <!-- Submit Another Evaluation Button -->
                    <div class="pt-4">
                        <a href="{{ route('feedback.form.office', ['office' => $office->id]) }}"
                           class="inline-block px-8 py-3.5 text-sm font-semibold text-white bg-gradient-to-r from-blue-600 to-blue-500 rounded-xl transition-all duration-300 hover:from-blue-700 hover:to-blue-600 hover:shadow-lg hover:-translate-y-0.5">
                            Submit Another Evaluation
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Confetti Animation Script -->
    <script src="https://cdn.jsdelivr.net/npm/canvas-confetti@1.4.0/dist/confetti.browser.min.js"></script>
    <script>
        // Trigger confetti animation
        confetti({
            particleCount: 100,
            spread: 70,
            origin: { y: 0.6 }
        });

        // Initialize AOS
        AOS.init({
            duration: 800,
            once: true
        });
    </script>
</body>
</html>