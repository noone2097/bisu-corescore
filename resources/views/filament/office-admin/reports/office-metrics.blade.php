<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Office Metrics Report</title>
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
        /* Print styles */
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
            
            table {
                page-break-inside: auto;
            }
            
            tr {
                page-break-inside: avoid;
                page-break-after: auto;
            }

            .text-gray-500, .text-gray-600, .text-gray-700 {
                color: #000 !important;
            }
        }

        /* Tailwind utility classes */
        .flex { display: flex; }
        .flex-col { flex-direction: column; }
        .items-center { align-items: center; }
        .justify-between { justify-content: space-between; }
        .gap-2 { gap: 0.5rem; }
        .gap-3 { gap: 0.75rem; }
        .text-center { text-align: center; }
        .leading-none { line-height: 1; }
        .text-gray-600 { color: #4B5563; }
        .text-gray-700 { color: #374151; }
        .font-medium { font-weight: 500; }
        .font-bold { font-weight: 700; }

        body {
            font-family: Arial, sans-serif;
            color: #333;
            line-height: 1.4;
            padding: 20px;
            max-width: 100%;
            box-sizing: border-box;
        }

        /* Header styles */
        .report-header {
            margin-bottom: 20px;
            padding: 0 10px;
        }

        .header-container {
            display: grid;
            grid-template-columns: 1fr auto; /* Left and right sections */
            align-items: center;
            gap: 10px;
            margin-bottom: 10px;
        }

        .bisu-section {
            display: flex;
            align-items: center;
            gap: 3px;
            height: 80px;
        }

        .bisu-logo {
            width: 65px;
            height: auto;
        }

        .institution-details {
            display: flex;
            flex-direction: column;
            justify-content: space-between;
            height: 65px;
            padding: 8px 0;
            line-height: 0.8;
            letter-spacing: -0.02em;
            margin: auto 0;
        }
        
        .institution-details p {
            margin: 0;
            padding: 0;
            white-space: nowrap;
        }

        .badges-section {
            display: flex;
            align-items: center;
            gap: 3px;
            height: 80px;
        }

        .badge-logo {
            width: 80px;
            height: auto;
        }
        
        .tuv-logo {
            width: 65px;
            height: auto;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
            margin-bottom: 30px;
            font-size: 11px;
        }
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }
        th {
            background-color: #f5f5f5;
            font-weight: bold;
        }
        tr:nth-child(even) {
            background-color: #f9f9f9;
        }
        .section-title {
            font-size: 14px;
            font-weight: bold;
            margin-top: 30px;
            margin-bottom: 10px;
            color: #1a5fb4;
        }
        .legend {
            font-size: 10px;
            margin-top: 30px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 20px;
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

            <!-- Badges Section -->
            <div class="badges-section">
                <img class="badge-logo"
                     src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/bagong-pilipinas-logo.png'))) }}"
                     alt="Bagong Pilipinas Logo">
                <img class="badge-logo tuv-logo"
                     src="data:image/png;base64,{{ base64_encode(file_get_contents(public_path('images/tuv-logo.png'))) }}"
                     alt="TUV Logo">
                <div class="text-[0.35rem] text-gray-600" style="height: 65px; display: flex; flex-direction: column; justify-content: space-between; padding: 8px 0; margin: auto 0;">
                        <p style="margin: 0; padding: 0; white-space: nowrap; transform: scale(0.85, 1); line-height: 0.8; letter-spacing: -0.03em;">Management System</p>
                        <p style="margin: 0; padding: 0; white-space: nowrap; transform: scale(0.85, 1); line-height: 0.8; letter-spacing: -0.03em;">ISO 9001:2015</p>
                        <p style="margin: 0; padding: 0; white-space: nowrap; transform: scale(0.85, 1); line-height: 0.8; letter-spacing: -0.03em;">www.tuv.com</p>
                        <p style="margin: 0; padding: 0; white-space: nowrap; transform: scale(0.85, 1); line-height: 0.8; letter-spacing: -0.03em;">ID: 9108658239</p>
                </div>
            </div>
        </div>

        <!-- Report Title and Timestamp -->
        <div style="border-top: 1px solid #eee; padding-top: 4px; text-align: center;">
            <div class="text-[1.1rem]"><strong style="font-weight: 700 !important;">Office Performance Metrics Report</strong></div>
            <p class="text-[0.6rem] text-gray-600">Generated on {{ $generated_at }}</p>
        </div>
    </div>
    
    <div class="section-title">Overview</div>
    <table>
        <thead>
            <tr>
                <th>Office</th>
                <th>Overall Rating</th>
                <th>Citizens Charter Rating</th>
                <th>Total Feedback Count</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record['office_name'] }}</td>
                <td>{{ $record['avg_rating'] }}</td>
                <td>{{ $record['cc_rating'] }}</td>
                <td>{{ $record['feedback_count'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="section-title">Service Quality Metrics</div>
    <table>
        <thead>
            <tr>
                <th>Office</th>
                <th>Responsiveness</th>
                <th>Reliability</th>
                <th>Access & Facilities</th>
                <th>Communication</th>
                <th>Costs</th>
                <th>Integrity</th>
                <th>Assurance</th>
                <th>Outcome</th>
            </tr>
        </thead>
        <tbody>
            @foreach($records as $record)
            <tr>
                <td>{{ $record['office_name'] }}</td>
                <td>{{ $record['responsiveness'] }}</td>
                <td>{{ $record['reliability'] }}</td>
                <td>{{ $record['access_facilities'] }}</td>
                <td>{{ $record['communication'] }}</td>
                <td>{{ $record['costs'] }}</td>
                <td>{{ $record['integrity'] }}</td>
                <td>{{ $record['assurance'] }}</td>
                <td>{{ $record['outcome'] }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

    <div class="legend">
        <p><strong>Rating Scale:</strong> All metrics are rated from 1 to 5, where 5 represents the highest level of satisfaction.</p>
        <p><strong>Overall Rating:</strong> Average of all service quality metrics</p>
        <p><strong>Citizens Charter Rating:</strong> Measures awareness, visibility, and helpfulness of the Citizens Charter</p>
        <p><strong>Service Quality Metrics:</strong></p>
        <ul>
            <li>Responsiveness: Speed and willingness to help clients</li>
            <li>Reliability: Consistency and dependability of service</li>
            <li>Access & Facilities: Ease of access and quality of facilities</li>
            <li>Communication: Clarity and effectiveness of information delivery</li>
            <li>Costs: Reasonableness of service fees</li>
            <li>Integrity: Honesty and transparency in service delivery</li>
            <li>Assurance: Knowledge and courtesy of staff</li>
            <li>Outcome: Overall satisfaction with service results</li>
        </ul>
    </div>
    <script>
        window.onload = function() {
            window.print();
        }
    </script>
</body>
</html>