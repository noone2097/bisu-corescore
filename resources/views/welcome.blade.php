<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>{{ config('app.name', 'BISU Core Score') }}</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.googleapis.com">
        <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
        <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;500;600&display=swap" rel="stylesheet">

        <!-- Preload Images -->
        <link rel="preload" href="{{ asset('images/bisu_logo.png') }}" as="image" type="image/png">
        <link rel="preload" href="/images/bagong-pilipinas-logo.png" as="image" type="image/png">
        
        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script></script>
        <style>
            :root {
                --background: 0 0% 100%;
                --foreground: 240 10% 3.9%;
                --font-family: 'Poppins', sans-serif;
                --card: 0 0% 100%;
                --card-foreground: 240 10% 3.9%;
                --muted: 240 4.8% 95.9%;
                --muted-foreground: 240 3.8% 46.1%;
                --border: 240 5.9% 90%;
                --primary-rgb: 59, 130, 246;
                --primary-hover-rgb: 37, 99, 235;
                --primary-foreground: 0 0% 98%;
                --ring: 240 5.9% 10%;
            }

            .text-muted-foreground {
                color: hsl(var(--muted-foreground));
            }

            .bg-card {
                background-color: hsl(var(--card) / 0.5);
                border: 1px solid hsl(var(--border) / 0.2);
            }

            .text-card-foreground {
                color: hsl(var(--card-foreground));
            }

            .text-foreground {
                color: hsl(var(--foreground));
            }

            .bg-background {
                background-color: hsl(var(--background));
            }

            .bg-primary {
                background-color: hsl(var(--primary));
            }

            .hover\:bg-primary-hover:hover {
                background-color: hsl(var(--primary-hover));
            }

            .text-primary-foreground {
                color: hsl(var(--primary-foreground));
            }

            .reveal-section {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .reveal-visible {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }

            .goals-item {
                position: relative;
                padding: 0.25rem;
                transition: all 0.3s ease;
            }

            .goals-item::before {
                content: "";
                position: absolute;
                inset: 0;
                border-radius: 0.5rem;
                background: linear-gradient(
                    to right,
                    transparent,
                    rgba(255, 255, 255, 0.1),
                    transparent
                );
                transform: scaleX(0);
                transition: transform 0.3s ease;
            }

            .goals-item:hover::before {
                transform: scaleX(1);
            }

            .filament-button {
                display: inline-flex;
                align-items: center;
                justify-content: center;
                gap: 0.25rem;
                border-radius: 0.25rem;
                font-size: 0.875rem;
                font-weight: 400;
                line-height: 1.25rem;
                padding: 0.5rem 1.5rem;
                transition: opacity 150ms ease;
                width: 100%;
            }

            .filament-button-primary {
                background-color: rgb(var(--primary-rgb));
                color: white;
                cursor: pointer;
            }

            .filament-button-primary:hover {
                opacity: 0.9;
            }

            .filament-button-primary:focus {
                outline: none;
            }

            .filament-button-outlined {
                border: 1px solid currentColor;
                background-color: transparent;
                color: rgb(var(--primary-rgb));
            }

            .filament-button-outlined:hover {
                background-color: rgb(243 244 246);
                border-color: rgb(var(--primary-rgb));
            }

            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .bg-gradient-animate {
                background-size: 200% 200%;
                animation: gradient 20s ease infinite;
            }

            @media (max-width: 768px) {
                .vision-mission-grid {
                    display: grid;
                    grid-template-columns: 1fr;
                    gap: 1.5rem;
                    padding: 0 1rem;
                }
                
                .goals-grid {
                    grid-template-columns: 1fr;
                    gap: 0.75rem;
                    padding: 0 1rem;
                }

                .text-lg {
                    font-size: 1.125rem;
                    line-height: 1.75rem;
                }

                .text-xl {
                    font-size: 1.25rem;
                    line-height: 1.75rem;
                }

                .bg-gradient-animate {
                    min-height: 100vh;
                }

                .space-y-4 > :not([hidden]) ~ :not([hidden]) {
                    margin-top: 1rem;
                }

                .p-4 {
                    padding: 0.75rem;
                }

                .p-6 {
                    padding: 1rem;
                }

                .mt-4 {
                    margin-top: 0.75rem;
                }

                .goals-item {
                    padding: 0.5rem;
                    font-size: 0.875rem;
                    line-height: 1.25rem;
                }
            }

            @media (max-width: 640px) {
                .fixed.inset-0 {
                    position: relative;
                }

                .grid {
                    min-height: 100vh;
                    gap: 0 !important;
                }

                .h-full {
                    height: auto;
                }

                .order-2.lg\:order-1 {
                    min-height: 100vh !important;
                    margin-top: 0 !important;
                }

                .order-1.lg\:order-2 {
                    min-height: 100vh !important;
                    margin: 0 !important;
                }
                .order-1.lg\:order-2 .absolute.inset-0 {
                    height: 100% !important;
                    background-size: auto 100% !important;
                    left: -1px !important;
                    right: -1px !important;
                }
            }
        </style>
    </head>
    <body class="antialiased font-[Poppins]">
        <div class="w-full lg:h-screen lg:overflow-hidden">
            <div class="grid grid-cols-1 lg:grid-cols-2 lg:h-full">
                <!-- Left Column (hidden on mobile) -->
                <div class="order-2 lg:order-1 flex bg-gradient-animate bg-gradient-to-b from-blue-900/90 to-indigo-900/90 text-white p-2 md:p-6 flex-col items-center justify-between min-h-screen lg:min-h-full gap-1 md:gap-4 mt-0 lg:mt-0">
                    <!-- Logo and Title -->
                    <div class="reveal-section flex flex-col items-center opacity-0 mt-8 md:mt-0">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-blue-500/20 to-transparent rounded-full blur-xl"></div>
                            <img
                                src="/images/bisu_logo.png"
                                alt="BISU Logo"
                                class="w-20 md:w-24 h-20 md:h-24 object-contain relative z-10"
                                width="96"
                                height="96"
                                fetchpriority="high" />
                        </div>
                        <div class="text-center mt-4">
                            <h1 class="text-lg md:text-xl font-medium tracking-wider mb-1">
                                BOHOL ISLAND STATE UNIVERSITY
                            </h1>
                            <p class="text-white/70 text-sm mb-8 lg:mb-0">Calape Campus</p>
                        </div>
                    </div>

                    <!-- Mission and Vision -->
                    <div class="flex flex-col md:grid md:grid-cols-2 gap-4 md:gap-8 w-full px-1 md:px-2 mt-0 md:-mt-8 vision-mission-grid">
                        <div class="reveal-section opacity-0 translate-y-8">
                            <div class="relative text-center">
                                <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-5xl md:text-6xl font-bold text-white/5 select-none">V</span>
                                <h2 class="text-xs font-medium mb-2 uppercase tracking-widest text-white/90">Vision</h2>
                                <p class="text-xs text-white/70 leading-relaxed text-justify goals-item">
                                    A premier Science and Technology university for the formation of world class and virtuous human resource for sustainable development in Bohol and the Country.
                                </p>
                            </div>
                        </div>

                        <div class="reveal-section opacity-0 translate-y-8">
                            <div class="relative text-center">
                                <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-5xl md:text-6xl font-bold text-white/5 select-none">M</span>
                                <h2 class="text-xs font-medium mb-2 uppercase tracking-widest text-white/90">Mission</h2>
                                <p class="text-xs text-white/70 leading-relaxed text-justify goals-item">
                                    BISU is committed to provide quality higher education in the arts and sciences, as well as in the professional and technological fields; undertake research and development and extension services for the sustainable development of Bohol and the country.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Goals -->
                    <div class="reveal-section opacity-0 translate-y-8 text-center w-full px-1 md:px-2 mt-4 md:-mt-4">
                        <div class="relative">
                            <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-4xl md:text-6xl font-bold text-white/5 select-none">G</span>
                            <h2 class="text-xs font-medium mb-2 md:mb-4 uppercase tracking-widest text-white/90">Goals</h2>
                            <div class="flex flex-col md:grid md:grid-cols-2 gap-4 md:gap-2 text-xs text-white/70 goals-grid">
                                <div class="goals-item">Excellence in Instruction and Learning</div>
                                <div class="goals-item">Excellence in Research and Innovation</div>
                                <div class="goals-item">Excellence in Community Engagement</div>
                                <div class="goals-item">Excellence in Institutional Development</div>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright Footer -->
                    <div class="text-center text-xs text-white/50 mb-2 md:mb-4">
                        <p>&copy; 2025 BISU CoreScore. All rights reserved.</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="order-1 lg:order-2 w-full bg-background h-[90vh] lg:h-screen lg:min-h-full col-span-1 lg:col-auto p-0 lg:p-8 relative">
                    <div class="absolute inset-0 bg-[url('/images/campus.jpg')] bg-cover opacity-20" style="background-position: 7% 10%;"></div>
                    <div class="relative z-10">
                    <div class="flex justify-between items-center px-4 pt-1 pb-0 lg:pt-2">
                        <div class="flex items-center gap-2">
                           <img
                                src="/images/tuv-logo.png"
                                alt="TUV Logo"
                                class="w-12 h-12 lg:w-14 lg:h-14 object-contain"
                                width="56"
                                height="56"
                                loading="lazy" />
                            <div class="flex flex-col text-[8px] sm:text-[8px] md:text-[9px] lg:text-[10px] leading-tight text-gray-600">
                                <p>Management System</p>
                                <p>ISO 9001:2015</p>
                                <p class="mt-0.5">www.tuv.com</p>
                                <p>ID: 9108658239</p>
                            </div>
                        </div>
                        <img
                            src="/images/bagong-pilipinas-logo.png"
                            alt="Bagong Pilipinas Logo"
                            class="w-14 h-14 lg:w-16 lg:h-16 object-contain scale-125"
                            width="64"
                            height="64"
                            fetchpriority="high" />
                    </div>
                    <div class="flex items-center justify-center h-[80vh]">
                       <div class="reveal-section opacity-0 reveal-visible max-w-md w-full space-y-6 md:space-y-8 text-center lg:mt-0">
                            <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-lg"></div>
                            <div class="relative bg-card text-card-foreground shadow-sm backdrop-blur-sm rounded-lg px-4 md:px-6 py-4 md:py-6 space-y-4">
                                <div class="flex justify-center items-center w-full mb-4">
                                    @include('logo')
                                </div>
                                <h3 class="text-base font-medium">Student Portal</h3>
                                <p class="text-sm text-muted-foreground leading-relaxed">
                                    Participate in faculty evaluations, provide valuable feedback on teaching performance, and contribute to academic excellence through our modern and efficient CoreScore system.
                                </p>
                                <div class="space-y-4">
                            @auth('students')
                                <a href="{{ route('filament.students.pages.dashboard') }}" class="filament-button filament-button-primary group overflow-hidden relative">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2V6zM14 6a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2V6zM4 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2H6a2 2 0 01-2-2v-2zM14 16a2 2 0 012-2h2a2 2 0 012 2v2a2 2 0 01-2 2h-2a2 2 0 01-2-2v-2z" />
                                        </svg>
                                        Go to Dashboard
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </a>
                            @else
                                <a href="{{ route('filament.students.auth.login') }}" class="filament-button filament-button-primary group overflow-hidden relative">
                                    <span class="flex items-center justify-center gap-2">
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition transform group-hover:-translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z" />
                                        </svg>
                                        Get Started
                                        <svg xmlns="http://www.w3.org/2000/svg" class="w-5 h-5 transition transform group-hover:translate-x-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 5l7 7m0 0l-7 7m7-7H3" />
                                        </svg>
                                    </span>
                                </a>
                            @endauth
                               </div>
                           </div>
                       </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                const leftSections = document.querySelectorAll('.grid > div:first-child .reveal-section');
                const rightSections = document.querySelectorAll('.grid > div:last-child .reveal-section');

                const revealSide = (sections, delay = 0) => {
                    sections.forEach((section, index) => {
                        setTimeout(() => {
                            section.classList.add('reveal-visible');
                        }, delay + (index * 200));
                    });
                };

                const initialDelay = 200;
                revealSide(leftSections, initialDelay);
                revealSide(rightSections, initialDelay);
            });
        </script>
    </body>
</html>
