<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You - BISU</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-white min-h-screen flex items-center justify-center p-4">
    <div class="max-w-md w-full text-center space-y-6">
        <div class="mb-8">
            <img src="{{ asset('images/bisu_logo.png') }}" alt="BISU Logo" class="mx-auto h-20 w-auto">
        </div>
        
        <h2 class="text-2xl text-gray-800">Thank You for Your Feedback</h2>
        
        <p class="text-gray-600">
            Your evaluation has been successfully submitted. Your feedback helps us improve our services.
        </p>

        <div class="text-4xl opacity-90">
            ✓
        </div>

        <a href="{{ route('evaluations.form.office', ['office' => $office_id]) }}"
           class="inline-block px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
            Submit Another Evaluation
        </a>
    </div>
</body>
</html>