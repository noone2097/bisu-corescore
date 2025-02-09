<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - BISU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100 min-h-screen flex items-center justify-center py-12 px-4 sm:px-6 lg:px-8">
    <div class="bg-white p-8 rounded-lg shadow-md max-w-md w-full text-center">
        <div class="mb-8">
            <img src="{{ asset('images/bisu_logo.png') }}" alt="BISU Logo" class="mx-auto h-24 w-auto">
        </div>
        
        <h2 class="text-2xl font-bold text-gray-900 mb-4">Thank You for Your Feedback!</h2>
        
        <p class="text-gray-600 mb-8">
            Your evaluation has been successfully submitted. Your feedback helps us improve our services to serve you better.
        </p>

        <div class="animate-bounce text-5xl mb-8">
            👍
        </div>

        <a href="{{ route('evaluations.form') }}" 
           class="inline-block bg-blue-600 text-white px-6 py-3 rounded-md font-medium hover:bg-blue-700 transition-colors">
            Submit Another Evaluation
        </a>
    </div>
</body>
</html>