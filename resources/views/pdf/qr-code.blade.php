<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Feedback QR Code</title>
    <style>
        @page {
            margin: 20px;
            size: A4;
        }
        @media screen {
            body {
                background: #f8f9fa;
            }
        }
        @media print {
            body {
                margin: 0;
                padding: 20px;
                -webkit-print-color-adjust: exact;
                print-color-adjust: exact;
                background: white !important;
            }
            * {
                -webkit-print-color-adjust: exact !important;
                print-color-adjust: exact !important;
            }
        }

        /* Tailwind utility classes */
        .text-gray-500 { color: #6B7280; }
        .text-gray-600 { color: #4B5563; }
        .font-medium { font-weight: 500; }
        .text-center { text-align: center; }

        html, body {
            height: 100%;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            box-sizing: border-box;
            text-align: center;
            display: flex;
            flex-direction: column;
            min-height: 100vh;
        }

        /* Header styles */
        .report-header {
            padding: 20px 10px;
            width: 100%;
            max-width: 100%;
            box-sizing: border-box;
            margin-bottom: 80px; /* Increased from 40px to 80px */
        }

        .header-container {
            display: flex;
            justify-content: center;
            width: 100%;
        }

        .bisu-section {
            display: flex;
            align-items: flex-start;
            gap: 3px;
            height: 100px;
        }

        .bisu-logo {
            width: 65px;
            height: auto;
            object-fit: contain;
            margin-top: 8px;
        }

        .institution-details {
            display: flex;
            flex-direction: column;
            height: 85px;
            padding: 8px 0;
            letter-spacing: -0.02em;
            margin: auto 0;
        }
        
        .institution-details p {
            margin: 0;
            padding: 0;
            white-space: nowrap;
            line-height: 1.6;
            margin-bottom: 2px;
        }

        /* Content styles */
        .main-content {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: flex-start; /* Changed from center to flex-start */
            align-items: center;
            padding-top: 120px; /* Increased from 60px to 120px */
            margin-bottom: 80px; /* Increased from 40px to 80px */
        }

        /* QR Code specific styles */
        .qr-container {
            margin: 20px auto;
            width: 300px;
            page-break-inside: avoid;
        }
        .title {
            font-size: 24px;
            margin-bottom: 20px;
            color: #333;
            font-weight: 700;
            page-break-before: avoid;
        }
        .subtitle {
            font-size: 16px;
            color: #666;
            margin-bottom: 30px;
        }
        .direct-link {
            margin-top: 20px;
            font-size: 14px;
            color: #1a56db;
            text-decoration: none;
            word-break: break-all;
        }
        @media print {
            .direct-link {
                color: #1a56db !important;
            }
        }
    </style>
</head>
<body>
    <div class="report-header">
        <div class="header-container">
            <!-- BISU Logo and Institution Details Section -->
            <div class="bisu-section">
                <img class="bisu-logo" 
                     src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/bisu_logo.png'))) }}"
                     alt="BISU Logo">
                <div class="institution-details">
                    <p class="text-[0.4rem] text-gray-600" style="transform: scale(0.9, 1);">Republic of the Philippines</p>
                    <p class="text-[0.45rem] font-bold text-gray-600" style="transform: scale(0.9, 1);">BOHOL ISLAND STATE UNIVERSITY</p>
                    <p class="text-[0.4rem] text-gray-600" style="transform: scale(0.9, 1);">San Isidro, Calape, Bohol</p>
                    <p class="text-[0.35rem] sm:text-xs md:text-xs text-gray-500 italic font-italic" style="transform: scale(0.9, 1); font-style: italic;"><span class="font-bold">B</span>alance | <span class="font-bold">I</span>ntegrity | <span class="font-bold">S</span>tewardship | <span class="font-bold">U</span>prightness</p>
                </div>
            </div>
        </div>
    </div>

    <div class="main-content">
        <div class="title">Official Customer Service Feedback Form</div>
        <div class="subtitle">Scan this QR code to access the feedback form</div>
        <div class="qr-container">
            <img src="data:image/svg+xml;base64,{{ $qrCodeContent }}" style="width: 100%;">
        </div>
        @if(isset($feedbackUrl))
        <div class="direct-link">
            {{ $feedbackUrl }}
        </div>
        @endif
    </div>
</body>
</html>