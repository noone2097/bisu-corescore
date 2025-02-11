<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Satisfaction Feedback Form - BISU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('evaluations.partials.styles')
    <style>
        .form-step {
            display: none;
        }
        .form-step.active {
            display: block;
        }
        .progress-container {
            position: relative;
            padding: 0.5rem 0 2rem;
        }
        .progress-track {
            position: relative;
            height: 2px;
            background: #E2E8F0;
            margin: 1rem 0;
        }
        .progress-dots {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin-top: -6px;
            max-width: 600px;
            margin-left: auto;
            margin-right: auto;
            padding: 0 20px;
        }
        .progress-step {
            position: relative;
            flex: 1;
            text-align: center;
            padding: 0 10px;
        }
        .progress-dot {
            width: 12px;
            height: 12px;
            border-radius: 50%;
            background: #E2E8F0;
            transition: all 0.3s ease;
            margin: 0 auto;
        }
        .progress-dot.active {
            background: #3B82F6;
            transform: scale(1.2);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        .progress-dot.completed {
            background: #3B82F6;
        }
        .step-label {
            position: absolute;
            top: 20px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.65rem;
            @media (min-width: 640px) {
                font-size: 0.75rem;
            }
            color: #64748B;
            white-space: nowrap;
            transition: all 0.3s ease;
            width: max-content;
        }
        .step-label.active {
            color: #3B82F6;
            font-weight: 500;
        }
        .particle {
            position: absolute;
            pointer-events: none;
            animation: particle-animation 0.6s ease-out forwards;
        }

        @keyframes particle-animation {
            0% {
                transform: translate(-50%, -50%) scale(1);
                opacity: 1;
            }
            100% {
                transform: translate(-50%, -50%) scale(2);
                opacity: 0;
            }
        }

        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-white to-blue-50 min-h-screen" 
      x-data="{
          step: 1,
          createParticles(dot) {
              const colors = ['#3B82F6', '#60A5FA', '#93C5FD'];
              const numParticles = 8;
              
              const rect = dot.getBoundingClientRect();
              const centerX = rect.left + rect.width / 2;
              const centerY = rect.top + rect.height / 2;

              for (let i = 0; i < numParticles; i++) {
                  const particle = document.createElement('div');
                  particle.className = 'particle';
                  particle.style.left = `${centerX}px`;
                  particle.style.top = `${centerY}px`;
                  particle.style.backgroundColor = colors[Math.floor(Math.random() * colors.length)];
                  particle.style.width = '4px';
                  particle.style.height = '4px';
                  particle.style.borderRadius = '50%';
                  document.body.appendChild(particle);

                  setTimeout(() => particle.remove(), 600);
              }
          },
          nextStep() {
              if (this.step < 6) {
                  const currentDot = document.querySelector(`.progress-dot[class*='active']`);
                  this.createParticles(currentDot);
                  setTimeout(() => {
                      this.step++;
                  }, 100);
              }
          },
          prevStep() {
              if (this.step > 1) {
                  this.step--;
              }
          }
      }">
    <div class="max-w-5xl mx-auto px-4 py-12">
        <!-- Logo -->
        <style>
            .logo-image {
                opacity: 0;
                transition: opacity 0.3s ease-in;
            }
            .logo-image.loaded {
                opacity: 1;
            }
        </style>

        <div class="flex flex-col items-center mb-8">
            <img src="{{ asset('images/bisu_logo.png') }}"
                 alt="BISU Logo"
                 class="h-16 w-auto logo-image"
                 loading="lazy"
                 decoding="async"
                 width="90"
                 height="90"
                 onload="this.classList.add('loaded')" />
            <p class="text-sm text-gray-600 mt-1">BISU - Calape Campus</p>
        </div>

        <h2 class="text-[1.65rem] mb-4 text-center text-gray-700 tracking-wide font-['Inter']">
            <span class="font-light">Customer </span>
            <span class="font-light">Satisfaction </span>
            <span class="font-light">Feedback Form</span>
        </h2>
        
        <!-- Progress Indicator -->
        <div class="progress-container">
            <div class="progress-track">
                <div class="progress-dots">
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 1, 'completed': step > 1 }"></div>
                        <div class="step-label" :class="{ 'active': step === 1 }"><span class="sm:hidden">Visit<br>Info</span><span class="hidden sm:inline">Visit Info</span></div>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 2, 'completed': step > 2 }"></div>
                        <div class="step-label" :class="{ 'active': step === 2 }"><span class="sm:hidden">Personal<br>Info</span><span class="hidden sm:inline">Personal Info</span></div>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 3, 'completed': step > 3 }"></div>
                        <div class="step-label" :class="{ 'active': step === 3 }"><span class="sm:hidden">CC<br>Questions</span><span class="hidden sm:inline">CC Questions</span></div>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 4, 'completed': step > 4 }"></div>
                        <div class="step-label" :class="{ 'active': step === 4 }"><span class="sm:hidden">Ratings</span><span class="hidden sm:inline">Ratings</span></div>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 5, 'completed': step > 5 }"></div>
                        <div class="step-label" :class="{ 'active': step === 5 }"><span class="sm:hidden">Comments</span><span class="hidden sm:inline">Comments</span></div>
                    </div>
                    <div class="progress-step">
                        <div class="progress-dot" :class="{ 'active': step === 6 }"></div>
                        <div class="step-label" :class="{ 'active': step === 6 }"><span class="sm:hidden">Signature</span><span class="hidden sm:inline">Signature</span></div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100">
            <form action="{{ route('evaluations.store') }}" method="POST" id="evaluationForm" class="p-6">
                @csrf

                <!-- Form Steps Container -->
                <div class="relative">
                    <!-- Step 1: Visit Information -->
                    <div :class="{ 'block': step === 1, 'hidden': step !== 1 }">
                        @include('evaluations.partials.visit-info')
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div x-cloak :class="{ 'block': step === 2, 'hidden': step !== 2 }">
                        @include('evaluations.partials.personal-info')
                    </div>

                    <!-- Step 3: CC Questions -->
                    <div x-cloak :class="{ 'block': step === 3, 'hidden': step !== 3 }">
                        @include('evaluations.partials.cc-questions')
                    </div>

                    <!-- Step 4: Satisfaction Ratings -->
                    <div x-cloak :class="{ 'block': step === 4, 'hidden': step !== 4 }">
                        @include('evaluations.partials.satisfaction-ratings')
                    </div>

                    <!-- Step 5: Comments -->
                    <div x-cloak :class="{ 'block': step === 5, 'hidden': step !== 5 }">
                        @include('evaluations.partials.comments')
                    </div>

                    <!-- Step 6: Visitor Information and Signature -->
                    <div x-cloak :class="{ 'block': step === 6, 'hidden': step !== 6 }">
                        @include('evaluations.partials.visitor-info')
                    </div>
                </div>

                <!-- Navigation Buttons -->
                <div class="flex justify-between mt-12 pt-6 border-t border-gray-100">
                    <button type="button" 
                            x-show="step > 1" 
                            @click="prevStep()"
                            class="px-4 py-2 text-gray-600 hover:text-gray-900 transition-colors">
                        ← Previous
                    </button>
                    <button type="button" 
                            x-show="step < 6" 
                            @click="nextStep()"
                            class="px-4 py-2 text-blue-600 hover:text-blue-800 transition-colors ml-auto">
                        Next →
                    </button>
                    <button type="submit" 
                            x-show="step === 6" 
                            class="bg-blue-500 text-white px-6 py-2 rounded-lg hover:bg-blue-600 transition-colors ml-auto">
                        Submit Feedback
                    </button>
                </div>
            </form>
        </div>
    </div>
</body>
</html>