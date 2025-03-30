<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=no">
    <title>Customer Satisfaction Feedback Form - BISU</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@200;300;400&display=swap" rel="stylesheet">
    @vite(['resources/css/app.css','resources/js/app.js'])
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('feedback.partials.styles')
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
            max-width: 500px;
            width: 100%;
            margin: 0 auto;
        }
        .progress-track {
            position: relative;
            display: flex;
            justify-content: space-between;
            margin: 1rem auto;
            max-width: 500px;
            width: calc(100% - 2rem);
            padding: 0;
        }
        @media (min-width: 640px) {
            .progress-track {
                width: 100%;
                padding: 0 10px;
            }
        }
        .progress-step {
            position: relative;
            flex: 1;
            text-align: center;
        }
        .progress-step::before {
            content: '';
            position: absolute;
            top: 50%;
            left: -50%;
            width: 100%;
            height: 2px;
            background: #E2E8F0;
            transform: translateY(-50%);
            z-index: 1;
            transition: none;
        }
        @media (min-width: 640px) {
            .progress-step::before {
                height: 3px;
            }
        }
        .progress-step:first-child::before {
            content: none;
        }
        .progress-step[class*="completed"]::before,
        .progress-step.active::before {
            background: #3B82F6;
        }
        .progress-indicator {
            width: 20px;
            height: 20px;
            background: #fff;
            border: 2px solid #E2E8F0;
            border-radius: 50%;
            margin: 0 auto;
            position: relative;
            z-index: 2;
            transition: all 0.3s ease;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 10px;
            color: #64748B;
            font-weight: 500;
        }
        @media (min-width: 640px) {
            .progress-indicator {
                width: 24px;
                height: 24px;
                font-size: 12px;
            }
        }
        .progress-step.active .progress-indicator {
            border-color: #3B82F6;
            background: #3B82F6;
            color: white;
        }
        .progress-step.completed .progress-indicator {
            border-color: #3B82F6;
            background: #3B82F6;
            color: white;
        }
        .step-label {
            position: absolute;
            top: 32px;
            left: 50%;
            transform: translateX(-50%);
            font-size: 0.6rem;
            @media (min-width: 640px) {
                font-size: 0.7rem;
            }
            color: #64748B;
            white-space: nowrap;
            transition: all 0.3s ease;
            width: max-content;
            font-weight: 400;
            letter-spacing: -0.01em;
        }
        .step-label.active {
            color: #3B82F6;
            font-weight: 500;
        }
        [x-cloak] {
            display: none !important;
        }
    </style>
</head>
<body class="bg-gradient-to-br from-white to-blue-50 min-h-screen" x-init="init()"
      x-data="{
         init() {
             requestAnimationFrame(() => {
                 document.documentElement.style.setProperty('--step-count', this.step);
             });
         },
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
         nextStep() {
             if (this.step < 6) {
                 this.previousStep = this.step;
                 this.step++;
                 this.updateStepClasses(this.step);
                 document.documentElement.style.setProperty('--step-count', this.step);
             }
         },
         prevStep() {
             if (this.step > 1) {
                 this.previousStep = this.step;
                 this.step--;
                 this.updateStepClasses(this.step);
                 document.documentElement.style.setProperty('--step-count', this.step);
             }
         }
      }">
    <div class="max-w-5xl mx-auto px-3 sm:px-4 py-4 sm:py-12">
        <!-- Logo -->
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
                <div class="flex flex-col justify-start mt-0.3 sm:mt-2">
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">Republic of the Philippines</p>
                    <p class="text-[0.55rem] sm:text-base md:text-base font-bold text-gray-700">BOHOL ISLAND STATE UNIVERSITY</p>
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">San Isidro, Calape, 6328 Bohol, Philippines</p>
                    <p class="text-[0.5rem] sm:text-sm md:text-sm text-gray-600">Office of the Administration</p>
                    <p class="text-[0.45rem] sm:text-xs md:text-xs text-gray-500 italic"><span class="font-bold">B</span>alance | <span class="font-bold">I</span>ntegrity | <span class="font-bold">S</span>tewardship | <span class="font-bold">U</span>prightness</p>
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

        <h2 class="text-[1.15rem] mb-4 text-center text-gray-700 tracking-wide font-['Inter']">
            <span class="font-light">Customer </span>
            <span class="font-light">Satisfaction </span>
            <span class="font-light">Feedback Form</span>
        </h2>
        
        <!-- Progress Indicator and Navigation -->
        <div class="mb-8 px-4 sm:px-0">
            <!-- Progress Dots -->
            <div class="progress-container mb-8 relative z-10">
                <div class="progress-track mx-auto">
                    <div class="progress-step" :class="{ 'active': step === 1, 'completed': step > 1 }">
                        <div class="progress-indicator">1</div>
                        <div class="step-label" :class="{ 'active': step === 1 }"><span class="sm:hidden">Visit<br>Info</span><span class="hidden sm:inline">Visit Info</span></div>
                    </div>
                    <div class="progress-step" :class="{ 'active': step === 2, 'completed': step > 2 }">
                        <div class="progress-indicator">2</div>
                        <div class="step-label" :class="{ 'active': step === 2 }"><span class="sm:hidden">Personal<br>Info</span><span class="hidden sm:inline">Personal Info</span></div>
                    </div>
                    <div class="progress-step" :class="{ 'active': step === 3, 'completed': step > 3 }">
                        <div class="progress-indicator">3</div>
                        <div class="step-label" :class="{ 'active': step === 3 }"><span class="sm:hidden">CC<br>Questions</span><span class="hidden sm:inline">CC Questions</span></div>
                    </div>
                    <div class="progress-step" :class="{ 'active': step === 4, 'completed': step > 4 }">
                        <div class="progress-indicator">4</div>
                        <div class="step-label" :class="{ 'active': step === 4 }"><span class="sm:hidden">Ratings</span><span class="hidden sm:inline">Ratings</span></div>
                    </div>
                    <div class="progress-step" :class="{ 'active': step === 5, 'completed': step > 5 }">
                        <div class="progress-indicator">5</div>
                        <div class="step-label" :class="{ 'active': step === 5 }"><span class="sm:hidden">Comments</span><span class="hidden sm:inline">Comments</span></div>
                    </div>
                    <div class="progress-step" :class="{ 'active': step === 6 }">
                        <div class="progress-indicator">6</div>
                        <div class="step-label" :class="{ 'active': step === 6 }"><span class="sm:hidden">Signature</span><span class="hidden sm:inline">Signature</span></div>
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
                            @click="
                                (step === 1 && !document.querySelector('[name=client_type]').value) ? document.querySelector('[name=client_type]').reportValidity() :
                                (step === 2 && (!document.querySelector('[name=sex]').value || !document.querySelector('[name=region_of_residence]').value || !document.querySelector('[name=services_availed]').value || !document.querySelector('[name=served_by]').value)) ? document.querySelector('form .form-step[data-step=\'2\'] [required]:invalid').reportValidity() :
                                (step === 3 && (!document.querySelector('[name=cc1]:checked') || !document.querySelector('[name=cc2]:checked') || !document.querySelector('[name=cc3]:checked'))) ? (() => {
                                    if (!document.querySelector('[name=cc1]:checked')) document.querySelector('[name=cc1]').setCustomValidity('Please select your answer for CC1');
                                    if (!document.querySelector('[name=cc2]:checked')) document.querySelector('[name=cc2]').setCustomValidity('Please select your answer for CC2');
                                    if (!document.querySelector('[name=cc3]:checked')) document.querySelector('[name=cc3]').setCustomValidity('Please select your answer for CC3');
                                    document.querySelector('[name=cc1], [name=cc2], [name=cc3]').reportValidity();
                                    document.querySelectorAll('[name=cc1], [name=cc2], [name=cc3]').forEach(el => el.setCustomValidity(''));
                                    return true;
                                })() :
                                (step === 4 && (!document.querySelector('[name=responsiveness]:checked') || !document.querySelector('[name=reliability]:checked') || !document.querySelector('[name=access_facilities]:checked') || !document.querySelector('[name=communication]:checked') || !document.querySelector('[name=costs]:checked') || !document.querySelector('[name=integrity]:checked') || !document.querySelector('[name=assurance]:checked') || !document.querySelector('[name=outcome]:checked'))) ? (() => {
                                    const ratings = ['responsiveness', 'reliability', 'access_facilities', 'communication', 'costs', 'integrity', 'assurance', 'outcome'];
                                    for (const rating of ratings) {
                                        if (!document.querySelector(`[name=${rating}]:checked`)) {
                                            document.querySelector(`[name=${rating}]`).setCustomValidity(`Please rate the ${rating.replace('_', ' ')}`);
                                            document.querySelector(`[name=${rating}]`).reportValidity();
                                            document.querySelector(`[name=${rating}]`).setCustomValidity('');
                                            return true;
                                        }
                                    }
                                    return false;
                                })() :
                                (step === 6 && (!document.querySelector('[name=first_name]').value || !document.querySelector('[name=last_name]').value || document.querySelector('#signatureCanvas') && !document.querySelector('#signature').value)) ?
                                    (() => {
                                        if (!document.querySelector('[name=first_name]').value || !document.querySelector('[name=last_name]').value) {
                                            document.querySelector('form .form-step[data-step=\'6\'] [required]:invalid').reportValidity();
                                            return true;
                                        }
                                        if (!document.querySelector('#signature').value) {
                                            const errorMessage = document.createElement('p');
                                            errorMessage.className = 'mt-2 text-sm text-red-600';
                                            errorMessage.id = 'signature-error';
                                            errorMessage.textContent = 'Please provide your signature before proceeding.';
                                            
                                            const existingError = document.querySelector('#signature-error');
                                            if (existingError) {
                                                existingError.remove();
                                            }
                                            
                                            document.getElementById('signature-pad').appendChild(errorMessage);
                                            document.getElementById('signature-pad').classList.add('border-red-500', 'bg-red-50');
                                            return true;
                                        }
                                        return false;
                                    })() :
                                nextStep()
                            "
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
                  action="{{ route('feedback.store', ['office' => $office]) }}"
                  method="POST"
                  id="evaluationForm"
                  class="p-3 sm:p-6 h-full"
                  x-on:submit="
                       $event.preventDefault();
                       if (step !== 6) {
                           nextStep();
                           return;
                       }

                       // Check signature first
                       if (!document.getElementById('signature').value) {
                           const signaturePad = document.getElementById('signature-pad');
                           const errorMessage = document.createElement('p');
                           errorMessage.className = 'mt-2 text-sm text-red-600';
                           errorMessage.id = 'signature-error';
                           errorMessage.textContent = 'Please provide your signature before submitting.';
                           
                           // Remove any existing error message
                           const existingError = document.querySelector('#signature-error');
                           if (existingError) {
                               existingError.remove();
                           }
                           
                           // Insert error message before the signature pad
                           signaturePad.parentNode.insertBefore(errorMessage, signaturePad);
                           
                           // Highlight the signature pad
                           signaturePad.classList.add('border-red-500', 'bg-red-50');
                           
                           // Scroll to signature pad
                           signaturePad.scrollIntoView({ behavior: 'smooth', block: 'center' });
                           return;
                       }

                       // Check if all other required fields are filled
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
                        @include('feedback.partials.visit-info')
                    </div>

                    <!-- Step 2: Personal Information -->
                    <div class="form-step" data-step="2" x-cloak :class="{ 'active': step === 2, 'previous': step > 2 }">
                        @include('feedback.partials.personal-info')
                    </div>

                    <!-- Step 3: CC Questions -->
                    <div class="form-step" data-step="3" x-cloak :class="{ 'active': step === 3, 'previous': step > 3 }">
                        @include('feedback.partials.cc-questions')
                    </div>

                    <!-- Step 4: Satisfaction Ratings -->
                    <div class="form-step" data-step="4" x-cloak :class="{ 'active': step === 4, 'previous': step > 4 }">
                        @include('feedback.partials.satisfaction-ratings')
                    </div>

                    <!-- Step 5: Comments -->
                    <div class="form-step [&>div>*]:my-2 [&>div>div]:mb-3" data-step="5" x-cloak :class="{ 'active': step === 5, 'previous': step > 5 }">
                        @include('feedback.partials.comments')
                    </div>

                    <!-- Step 6: Visitor Information and Signature -->
                    <div class="form-step [&>div>*]:my-2 [&>div>div]:mb-3" data-step="6" x-cloak :class="{ 'active': step === 6 }">
                        @include('feedback.partials.visitor-info')
                    </div>
                </div>
            </form>
        </div>
    </div>
</body>
</html>
