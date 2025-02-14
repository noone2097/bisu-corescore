<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Customer Satisfaction Feedback Form - BISU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400&display=swap" rel="stylesheet">
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('evaluations.partials.styles')
    <style>
        .form-step {
            width: 100%;
            opacity: 0;
            transform: translateX(50px);
            transition: all 0.4s ease-in-out;
            visibility: hidden;
            height: 0;
            overflow: hidden;
        }
        .form-step.active {
            opacity: 1;
            transform: translateX(0);
            visibility: visible;
            height: auto;
            overflow: visible;
        }
        .form-step.previous {
            opacity: 0;
            transform: translateX(-50px);
            visibility: hidden;
            height: 0;
            overflow: hidden;
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
            padding: 0 10px;
        }
        .progress-step {
            position: relative;
            flex: 1;
            text-align: center;
            padding: 0 4px;
        }
        .progress-dot {
            width: 10px;
            height: 10px;
            @media (min-width: 640px) {
                width: 12px;
                height: 12px;
            }
            border-radius: 50%;
            background: #E2E8F0;
            transition: transform 0.3s ease, background-color 0.3s ease, box-shadow 0.3s ease;
            margin: 0 auto;
            position: relative;
            z-index: 1;
        }
        .progress-dot.active {
            background: #3B82F6;
            transform: scale(1.2);
            box-shadow: 0 0 10px rgba(59, 130, 246, 0.5);
        }
        .progress-dot.completed {
            background: #3B82F6;
            transform: scale(1);
            box-shadow: none;
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
                transform: translate(-50%, -50%) scale(0);
                opacity: 1;
            }
            50% {
                transform: translate(-50%, -50%) scale(1.5);
                opacity: 0.5;
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
          previousStep: 1,
          formSubmitting: false,
          updateStepClasses(stepNumber) {
              document.querySelectorAll('.form-step').forEach(el => {
                  if (parseInt(el.dataset.step) === stepNumber) {
                      el.classList.add('active');
                      el.classList.remove('previous');
                  } else if (parseInt(el.dataset.step) === this.previousStep) {
                      el.classList.add('previous');
                      el.classList.remove('active');
                  } else {
                      el.classList.remove('active', 'previous');
                  }
              });
          },
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
                  this.previousStep = this.step;
                  this.step++;
                  this.updateStepClasses(this.step);
                  // Wait for the DOM to update with the new active dot
                  requestAnimationFrame(() => {
                      const newDot = document.querySelector(`.progress-dot[class*='active']`);
                      this.createParticles(newDot);
                  });
              }
          },
          prevStep() {
              if (this.step > 1) {
                  this.previousStep = this.step;
                  this.step--;
                  this.updateStepClasses(this.step);
              }
          }
      }">
    <div class="max-w-5xl mx-auto px-3 sm:px-4 py-4 sm:py-12">
        <!-- Logo -->
        <style>
            .logo-image {
                opacity: 0;
                transition: opacity 0.3s ease-in;
            }
            .logo-image.loaded {
                opacity: 1;
            }
            .header-text {
                font-size: 7px;
                line-height: 1.2;
                @media (min-width: 640px) {
                    font-size: 8px;
                }
            }
            .motto-text {
                font-size: 6px;
                line-height: 1.1;
                @media (min-width: 640px) {
                    font-size: 7px;
                }
            }
        </style>

        <div class="flex mb-8 items-start justify-between">
            <!-- Left Section -->
            <div class="flex items-start gap-2">
                <!-- Logo -->
                <div class="flex flex-col items-start w-14">
                    <img src="{{ asset('images/bisu_logo.png') }}"
                         alt="BISU Logo"
                         class="h-14 w-auto logo-image"
                         loading="lazy"
                         decoding="async"
                         width="90"
                         height="90"
                         onload="this.classList.add('loaded')" />
                </div>
                
                <!-- Institution Details -->
                <div class="flex flex-col justify-start mt-2">
                    <p class="header-text text-gray-600">Republic of the Philippines</p>
                    <p class="header-text font-medium text-gray-700">BOHOL ISLAND STATE UNIVERSITY</p>
                    <p class="header-text text-gray-600">San Isidro, Calape, 6328 Bohol, Philippines</p>
                    <p class="header-text text-gray-600">Office of the Administration</p>
                    <p class="motto-text text-gray-500 italic mt-0.5">Balance | Integrity | Stewardship | Uprightness</p>
                </div>
            </div>

            <!-- Additional Logos -->
            <div class="flex items-center gap-2 mt-1">
                <div class="w-11">
                    <img src="{{ asset('images/bagong-pilipinas-logo.png') }}"
                         alt="Bagong Pilipinas Logo"
                         class="w-full h-auto logo-image"
                         loading="lazy"
                         decoding="async"
                         onload="this.classList.add('loaded')" />
                </div>
                <div class="flex items-start gap-2">
                    <div class="w-8">
                        <img src="{{ asset('images/tuv-logo.png') }}"
                             alt="TUV Logo"
                             class="w-full h-auto logo-image"
                             loading="lazy"
                             decoding="async"
                             onload="this.classList.add('loaded')" />
                    </div>
                    <div class="flex flex-col text-[5px] leading-tight text-gray-600 mt-1">
                        <p>Management System</p>
                        <p>ISO 9001:2015</p>
                        <p class="mt-0.5">www.tuv.com</p>
                        <p>ID: 9108658239</p>
                    </div>
                </div>
            </div>
        </div>

        <h2 class="text-[1.15rem] mb-4 text-center text-gray-700 tracking-wide font-['Inter']">
            <span class="font-light">Customer </span>
            <span class="font-light">Satisfaction </span>
            <span class="font-light">Feedback Form</span>
        </h2>
        
        <!-- Progress Indicator and Navigation -->
        <div class="mb-8">
            <!-- Progress Dots -->
            <div class="progress-container mb-6">
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
            
            <!-- Fixed Navigation Buttons -->
            <div class="fixed bottom-0 left-0 right-0 bg-white border-t border-gray-200 z-50">
                <div class="max-w-5xl mx-auto px-3 sm:px-4 py-2 sm:py-3 flex justify-between">
                    <button type="button"
                            x-show="step > 1"
                            @click="prevStep()"
                            :disabled="formSubmitting"
                            class="px-2 sm:px-4 py-2 sm:py-2.5 text-gray-600 hover:text-gray-900 transition-colors text-xs sm:text-base touch-manipulation disabled:opacity-50">
                        ← Back
                    </button>
                    <!-- Only show Next button, Submit is in the visitor-info section -->
                    <button type="button"
                            x-show="step < 6"
                            @click="nextStep()"
                            :disabled="formSubmitting"
                            class="px-2 sm:px-4 py-2 sm:py-2.5 text-blue-600 hover:text-blue-800 transition-colors ml-auto text-xs sm:text-base touch-manipulation disabled:opacity-50">
                        Next →
                    </button>
                </div>
            </div>
        </div>

        <!-- Main Form -->
        <div class="bg-white rounded-lg shadow-sm border border-gray-100 mb-20">
            <form x-data="{ formSubmitting: false }"
                  action="{{ route('evaluations.store') }}"
                  method="POST"
                  id="evaluationForm"
                  class="p-3 sm:p-6 h-full"
                  x-on:submit="
                       $event.preventDefault();
                       if (step !== 6) {
                           nextStep();
                           return;
                       }
                       // Check if all required fields are filled
                       if (!$event.target.checkValidity()) {
                           $event.target.reportValidity();
                           return;
                       }
                       if (formSubmitting) return;
                       formSubmitting = true;
                       $event.target.submit();
                   "
                  novalidate>
                @csrf
                <!-- Display Validation Errors -->
                @if ($errors->any())
                    <div class="bg-red-50 text-red-600 p-4 rounded-lg mb-6">
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd"/>
                            </svg>
                            <p class="font-medium">Please correct the following errors:</p>
                        </div>
                        <ul class="mt-2 list-disc list-inside space-y-1">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                        <!-- Debug Info -->
                        @if (config('app.debug'))
                            <div class="mt-4 p-2 bg-gray-100 rounded text-sm">
                                <strong>Debug Info:</strong>
                                <pre class="mt-1 whitespace-pre-wrap">{{ print_r($errors->toArray(), true) }}</pre>
                            </div>
                        @endif
                    </div>
                @endif
<!-- Form Steps Container -->
<div class="relative">
                    <!-- Step 1: Visit Information -->
                    <div class="form-step" data-step="1" :class="{ 'active': step === 1, 'previous': step > 1 }">
                        @include('evaluations.partials.visit-info')
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div class="form-step" data-step="2" x-cloak :class="{ 'active': step === 2, 'previous': step > 2 }">
                        @include('evaluations.partials.personal-info')
                    </div>

                    <!-- Step 3: CC Questions -->
                    <div class="form-step" data-step="3" x-cloak :class="{ 'active': step === 3, 'previous': step > 3 }">
                        @include('evaluations.partials.cc-questions')
                    </div>

                    <!-- Step 4: Satisfaction Ratings -->
                    <div class="form-step" data-step="4" x-cloak :class="{ 'active': step === 4, 'previous': step > 4 }">
                        @include('evaluations.partials.satisfaction-ratings')
                    </div>

                    <!-- Step 5: Comments -->
                    <div class="form-step [&>div>*]:my-2 [&>div>div]:mb-3" data-step="5" x-cloak :class="{ 'active': step === 5, 'previous': step > 5 }">
                        @include('evaluations.partials.comments')
                    </div>

                    <!-- Step 6: Visitor Information and Signature -->
                    <div class="form-step [&>div>*]:my-2 [&>div>div]:mb-3" data-step="6" x-cloak :class="{ 'active': step === 6 }">
                        @include('evaluations.partials.visitor-info')
                    </div>
                </div>

                <!-- End of form steps -->
            </form>
        </div>
    </div>
</body>
</html>