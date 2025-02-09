<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Customer Satisfaction Feedback Form - BISU</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    @include('evaluations.partials.styles')
</head>
<body class="bg-gray-100 min-h-screen py-8" x-data="{ step: 1 }">
    <div class="bg-white p-4 md:p-8 rounded-lg shadow-md max-w-4xl mx-auto">
        <h2 class="text-xl md:text-2xl font-medium text-gray-800 mb-6 text-center">CUSTOMER SATISFACTION FEEDBACK FORM</h2>
        
        <!-- Progress Bar -->
        <div class="mb-8">
            <div class="flex justify-between mb-2">
                <template x-for="i in 6" :key="i">
                    <div class="flex-1 mx-1">
                        <div class="h-2 rounded-full transition-all duration-300"
                             :class="step >= i ? 'bg-blue-500' : 'bg-gray-200'"></div>
                    </div>
                </template>
            </div>
            <div class="text-center text-sm text-gray-600">
                Step <span x-text="step"></span> of 6
            </div>
        </div>

        <form action="{{ route('evaluations.store') }}" method="POST" class="space-y-6" id="evaluationForm">
            @csrf

            <!-- Step 1: Visit Information -->
            <div x-show="step === 1">
                @include('evaluations.partials.visit-info')
            </div>

            <!-- Step 2: Personal Information -->
            <div x-show="step === 2">
                @include('evaluations.partials.personal-info')
            </div>

            <!-- Step 3: CC Questions -->
            <div x-show="step === 3">
                @include('evaluations.partials.cc-questions')
            </div>

            <!-- Step 4: Satisfaction Ratings -->
            <div x-show="step === 4">
                @include('evaluations.partials.satisfaction-ratings')
            </div>

            <!-- Step 5: Comments -->
            <div x-show="step === 5">
                @include('evaluations.partials.comments')
            </div>

            <!-- Step 6: Visitor Information and Signature -->
            <div x-show="step === 6">
                @include('evaluations.partials.visitor-info')
            </div>

            <!-- Navigation Buttons -->
            <div class="flex justify-between mt-8">
                <button type="button" 
                        x-show="step > 1" 
                        @click="step--"
                        class="px-6 py-2 text-gray-600 border border-gray-300 rounded hover:bg-gray-50">
                    Previous
                </button>
                <button type="button" 
                        x-show="step < 6" 
                        @click="step++"
                        class="px-6 py-2 bg-blue-600 text-white rounded hover:bg-blue-700 ml-auto">
                    Next
                </button>
                <button type="submit" 
                        x-show="step === 6" 
                        class="px-6 py-2 bg-green-600 text-white rounded hover:bg-green-700 ml-auto">
                    Submit Feedback
                </button>
            </div>
        </form>
    </div>
</body>
</html>