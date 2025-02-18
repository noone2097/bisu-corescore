<!DOCTYPE html>
<html
    lang="{{ str_replace('_', '-', app()->getLocale()) }}"
    x-data="{ isDark: localStorage.theme === 'dark' || (!localStorage.theme && window.matchMedia('(prefers-color-scheme: dark)').matches) }"
    :class="{ 'dark': isDark }"
>
    <head>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <title>Welcome</title>

        <!-- Fonts -->
        <link rel="preconnect" href="https://fonts.bunny.net">
        <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

        <!-- Styles -->
        <script src="https://cdn.tailwindcss.com"></script>
        <script defer src="https://unpkg.com/alpinejs@3.x.x/dist/cdn.min.js"></script>
        <script>
            // Check for saved theme preference, otherwise use system preference
            if (localStorage.theme === 'dark' || (!('theme' in localStorage) && window.matchMedia('(prefers-color-scheme: dark)').matches)) {
                document.documentElement.classList.add('dark')
            } else {
                document.documentElement.classList.remove('dark')
            }
        </script>
        <style>
            :root {
                --background: 0 0% 100%;
                --foreground: 240 10% 3.9%;
                --card: 0 0% 100%;
                --card-foreground: 240 10% 3.9%;
                --muted: 240 4.8% 95.9%;
                --muted-foreground: 240 3.8% 46.1%;
                --border: 240 5.9% 90%;
                --primary: 221 83% 53%;    /* Changed primary color to blue-500 */
                --primary-hover: 221 83% 45%;    /* blue-600 for hover */
                --primary-foreground: 0 0% 98%;
                --ring: 240 5.9% 10%;
            }

            .dark {
                --background: 240 10% 3.9%;
                --foreground: 0 0% 98%;
                --card: 240 10% 3.9%;
                --card-foreground: 0 0% 98%;
                --muted: 240 3.7% 15.9%;
                --muted-foreground: 240 5% 64.9%;
                --border: 240 3.7% 15.9%;
                --primary: 221 83% 53%;    /* Keep primary color consistent */
                --primary-hover: 221 83% 45%;    /* Keep hover color consistent */
                --primary-foreground: 0 0% 98%;
                --ring: 240 4.9% 83.9%;
            }

            [x-cloak] { display: none !important; }

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

            /* Reveal animations */
            .reveal-section {
                opacity: 0;
                transform: translateY(20px);
                transition: all 0.8s cubic-bezier(0.4, 0, 0.2, 1);
            }

            .reveal-visible {
                opacity: 1 !important;
                transform: translateY(0) !important;
            }

            /* Goals items hover effect */
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

            /* Login button hover effect */
            .login-button {
                position: relative;
                overflow: hidden;
                background-color: hsl(var(--primary));
                color: hsl(var(--primary-foreground));
                font-weight: 600;
                padding: 0.75rem 2rem;
                border-radius: 0.5rem;
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
                transition: all 0.3s ease;
                border: 2px solid transparent;
            }

            .login-button:hover {
                background-color: hsl(var(--primary-hover));
                transform: translateY(-1px);
                box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.1), 0 3px 6px -1px rgba(0, 0, 0, 0.06);
            }

            .dark .login-button {
                box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.2), 0 2px 4px -1px rgba(0, 0, 0, 0.1);
            }

            .dark .login-button:hover {
                box-shadow: 0 6px 8px -1px rgba(0, 0, 0, 0.2), 0 3px 6px -1px rgba(0, 0, 0, 0.1);
                border-color: rgba(255, 255, 255, 0.1);
            }

            .login-button::before {
                content: "";
                position: absolute;
                top: 0;
                left: -100%;
                width: 100%;
                height: 100%;
                background: linear-gradient(
                    to right,
                    transparent,
                    rgba(255, 255, 255, 0.2),
                    transparent
                );
                transition: 0.5s;
            }

            .login-button:hover::before {
                left: 100%;
            }

            /* Theme toggle button enhancement */
            .theme-toggle {
                border: 2px solid hsl(var(--border));
                background-color: hsl(var(--card));
                box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
                padding: 0.5rem;
                border-radius: 0.5rem;
                transition: all 0.3s ease;
            }

            .theme-toggle:hover {
                transform: scale(1.05);
                border-color: hsl(var(--primary));
                background-color: hsl(var(--card));
            }

            .theme-toggle svg {
                width: 1.5rem;
                height: 1.5rem;
                color: hsl(var(--foreground));
            }

            /* Gradient animation */
            @keyframes gradient {
                0% { background-position: 0% 50%; }
                50% { background-position: 100% 50%; }
                100% { background-position: 0% 50%; }
            }

            .bg-gradient-animate {
                background-size: 200% 200%;
                animation: gradient 20s ease infinite;
            }

            /* Mobile optimizations */
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
                }

                .h-full {
                    height: auto;
                }
            }
        </style>
    </head>
    <body class="antialiased">
        <div class="relative lg:fixed lg:inset-0">
            <div class="min-h-screen grid grid-cols-1 lg:grid-cols-2">
                <!-- Left Column (hidden on mobile) -->
                <div class="hidden lg:flex bg-gradient-animate bg-gradient-to-b from-blue-900/90 to-indigo-900/90 text-white p-3 md:p-6 flex-col items-center justify-between min-h-screen lg:min-h-full gap-3 md:gap-4">
                    <!-- Logo and Title -->
                    <div class="reveal-section flex flex-col items-center opacity-0">
                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-b from-blue-500/20 to-transparent rounded-full blur-xl"></div>
                            <img src="/images/bisu_logo.png" alt="BISU Logo" class="w-20 md:w-24 h-20 md:h-24 object-contain relative z-10" />
                        </div>
                        <div class="text-center mt-4">
                            <h1 class="text-lg md:text-xl font-medium tracking-wider mb-1">
                                BOHOL ISLAND STATE UNIVERSITY
                            </h1>
                            <p class="text-white/70 text-sm">Calape Campus</p>
                        </div>
                    </div>

                    <!-- Mission and Vision -->
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-3 md:gap-8 w-full px-1 md:px-2 mt-2 md:-mt-8 vision-mission-grid">
                        <div class="reveal-section opacity-0 translate-y-8 px-2">
                            <div class="relative text-center">
                                <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-5xl md:text-6xl font-bold text-white/5 select-none">V</span>
                                <h2 class="text-xs font-medium mb-3 uppercase tracking-widest text-white/90">Vision</h2>
                                <p class="text-xs text-white/70 leading-relaxed text-justify goals-item">
                                    A premier Science and Technology university for the formation of world class and virtuous human resource for sustainable development in Bohol and the Country.
                                </p>
                            </div>
                        </div>

                        <div class="reveal-section opacity-0 translate-y-8">
                            <div class="relative text-center">
                                <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-5xl md:text-6xl font-bold text-white/5 select-none">M</span>
                                <h2 class="text-xs font-medium mb-3 uppercase tracking-widest text-white/90">Mission</h2>
                                <p class="text-xs text-white/70 leading-relaxed text-justify goals-item">
                                    BISU is committed to provide quality higher education in the arts and sciences, as well as in the professional and technological fields; undertake research and development and extension services for the sustainable development of Bohol and the country.
                                </p>
                            </div>
                        </div>
                    </div>

                    <!-- Goals -->
                    <div class="reveal-section opacity-0 translate-y-8 text-center w-full px-1 md:px-2 mt-1 md:-mt-4">
                        <div class="relative">
                            <span class="absolute -top-4 left-1/2 -translate-x-1/2 text-4xl md:text-6xl font-bold text-white/5 select-none">G</span>
                            <h2 class="text-xs font-medium mb-2 md:mb-4 uppercase tracking-widest text-white/90">Goals</h2>
                            <div class="grid md:grid-cols-2 gap-1.5 md:gap-2 text-xs text-white/70 goals-grid">
                                <div class="goals-item">Excellence in Instruction and Learning</div>
                                <div class="goals-item">Excellence in Research and Innovation</div>
                                <div class="goals-item">Excellence in Community Engagement</div>
                                <div class="goals-item">Excellence in Institutional Development</div>
                            </div>
                        </div>
                    </div>

                    <!-- Copyright Footer -->
                    <div class="text-center text-xs text-white/50 mt-4 md:mt-6">
                        <p>&copy; 2025 BISU CoreScore. All rights reserved.</p>
                    </div>
                </div>

                <!-- Right Column -->
                <div class="w-full p-4 md:p-8 flex flex-col items-center justify-center bg-background min-h-screen lg:min-h-full col-span-1 lg:col-auto">
                    <!-- Theme Toggle -->
                    <div class="absolute top-4 right-4 z-10">
                        <button 
                            class="theme-toggle" 
                            @click="isDark = !isDark;
                                   localStorage.theme = isDark ? 'dark' : 'light';
                                   if (isDark) { document.documentElement.classList.add('dark') }
                                   else { document.documentElement.classList.remove('dark') }"
                            aria-label="Toggle dark mode"
                        >
                            <!-- Sun icon -->
                            <svg 
                                x-cloak 
                                x-show="!isDark" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-6 h-6" 
                                viewBox="0 0 24 24" 
                                stroke-width="1.5" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M12 3v2.25m6.364.386l-1.591 1.591M21 12h-2.25m-.386 6.364l-1.591-1.591M12 18.75V21m-4.773-4.227l-1.591 1.591M5.25 12H3m4.227-4.773L5.636 5.636M15.75 12a3.75 3.75 0 11-7.5 0 3.75 3.75 0 017.5 0z" />
                            </svg>
                            <!-- Moon icon -->
                            <svg 
                                x-cloak 
                                x-show="isDark" 
                                xmlns="http://www.w3.org/2000/svg" 
                                class="w-6 h-6" 
                                viewBox="0 0 24 24" 
                                stroke-width="1.5" 
                                stroke="currentColor"
                            >
                                <path stroke-linecap="round" stroke-linejoin="round" d="M21.752 15.002A9.718 9.718 0 0118 15.75c-5.385 0-9.75-4.365-9.75-9.75 0-1.33.266-2.597.748-3.752A9.753 9.753 0 003 11.25C3 16.635 7.365 21 12.75 21a9.753 9.753 0 009.002-5.998z" />
                            </svg>
                        </button>
                    </div>

                    <div class="reveal-section opacity-0 max-w-md w-full space-y-6 md:space-y-8 text-center">
                        <div class="space-y-2">
                            <div class="relative inline-block">
                                <span class="absolute inset-0 bg-gradient-to-r from-primary/20 to-transparent blur-xl"></span>
                                <h2 class="relative text-xl md:text-2xl font-medium tracking-wide text-foreground">
                                    BISU CoreScore
                                </h2>
                            </div>
                            <p class="text-sm text-muted-foreground">Innovation Through Excellence</p>
                        </div>

                        <div class="relative">
                            <div class="absolute inset-0 bg-gradient-to-br from-primary/5 to-transparent rounded-lg"></div>
                            <div class="relative bg-card text-card-foreground shadow-sm backdrop-blur-sm rounded-lg px-4 md:px-6 py-4 md:py-6 space-y-3">
                                <h3 class="text-base font-medium">About CoreScore</h3>
                                <p class="text-sm text-muted-foreground leading-relaxed">
                                    BISU CoreScore is an innovative performance evaluation system designed to streamline and enhance the assessment process for our faculty and staff. Experience a modern, efficient, and transparent way of managing evaluations.
                                </p>
                            </div>
                        </div>

                        <div>
                            @auth
                                <a href="{{ url('/admin') }}" class="login-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-8 py-2 w-full">
                                    Go to Dashboard
                                </a>
                            @else
                                <a href="{{ url('/admin/login') }}" class="login-button inline-flex items-center justify-center rounded-md text-sm font-medium ring-offset-background transition-all focus-visible:outline-none focus-visible:ring-2 focus-visible:ring-ring focus-visible:ring-offset-2 disabled:pointer-events-none disabled:opacity-50 h-10 px-8 py-2 w-full">
                                    Login to CoreScore
                                </a>
                            @endauth
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <script>
            document.addEventListener('DOMContentLoaded', () => {
                // Reveal animations
                const leftSections = document.querySelectorAll('.grid > div:first-child .reveal-section');
                const rightSections = document.querySelectorAll('.grid > div:last-child .reveal-section');

                const revealSide = (sections, delay = 0) => {
                    sections.forEach((section, index) => {
                        setTimeout(() => {
                            section.classList.add('reveal-visible');
                        }, delay + (index * 200));
                    });
                };

                // Start both sides at the same time with a small initial delay
                const initialDelay = 200;
                revealSide(leftSections, initialDelay);
                revealSide(rightSections, initialDelay);
            });
        </script>
    </body>
</html>
