<div x-data>
    <button 
        type="button" 
        class="fi-btn fi-btn-size-md relative grid-flow-col items-center justify-center font-semibold outline-none transition duration-75 focus:ring-2 rounded-lg fi-btn-color-gray gap-1.5 px-3 py-2"
        x-on:click="
            const printContent = document.querySelector('#printable-table').innerHTML;
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <html>
                    <head>
                        <title>Print</title>
                        <style>
                            @media print {
                                @page {
                                    margin: 2cm;
                                    size: A4 landscape;
                                }
                                body {
                                    font-family: Arial, sans-serif;
                                    font-size: 12pt;
                                    line-height: 1.4;
                                    padding: 1rem;
                                }
                                .header h2 {
                                    font-size: 18pt;
                                    font-weight: bold;
                                    margin-bottom: 0.5rem;
                                    text-align: center;
                                }
                                .header h3 {
                                    font-size: 16pt;
                                    font-weight: 600;
                                    margin-bottom: 1.5rem;
                                    text-align: center;
                                    color: #374151;
                                }
                                table {
                                    width: 100%;
                                    border-collapse: separate;
                                    border-spacing: 0;
                                    margin: 1.5rem 0;
                                }
                                th, td {
                                    padding: 12px 16px;
                                    border: 1px solid #000;
                                    text-align: left;
                                }
                                th {
                                    background-color: #f3f4f6 !important;
                                    font-weight: bold;
                                }
                                td {
                                    padding: 10px 16px;
                                }
                                .footer {
                                    text-align: right;
                                    font-size: 10pt;
                                    margin-top: 1.5rem;
                                    color: #666;
                                }
                            }
                        </style>
                    </head>
                    <body>
                        ${printContent}
                    </body>
                </html>
            `);
            printWindow.document.close();
            printWindow.focus();
            setTimeout(() => {
                printWindow.print();
                printWindow.close();
            }, 250);
        "
    >
        Print Report
    </button>

    <style>
        /* Modal view styles */
        #printable-table {
            padding: 1rem;
        }
        .header h2 {
            font-size: 1.5rem;
            font-weight: 600;
            margin-bottom: 1.5rem;
            text-align: center;
        }
        table {
            width: 100%;
            border-collapse: separate;
            border-spacing: 0;
            margin-bottom: 1.5rem;
        }
        th, td {
            padding: 1rem;
            border: 1px solid #e5e7eb;
            text-align: left;
        }
        th {
            background-color: #f3f4f6;
            font-weight: 600;
        }
        td {
            padding: 0.875rem 1rem;
        }
        tr:hover td {
            background-color: #f9fafb;
        }
        .footer {
            text-align: right;
            color: #6b7280;
            font-size: 0.875rem;
            margin-top: 1.5rem;
        }
    </style>

    <div id="printable-table">
        <div class="header">
            <h2>Faculty Evaluation Summary Report</h2>
            <h3>{{ $departmentName }}</h3>
        </div>

        <table>
            <thead>
                <tr>
                    <th>Faculty Name</th>
                    <th>Commitment</th>
                    <th>Knowledge</th>
                    <th>Teaching</th>
                    <th>Management</th>
                    <th>Overall</th>
                </tr>
            </thead>
            <tbody>
                @foreach($records as $record)
                    <tr>
                        <td>{{ $record->facultyCourse?->faculty?->name ?? 'Unknown Faculty' }}</td>
                        <td>{{ number_format($record->commitment_average, 2) }}</td>
                        <td>{{ number_format($record->knowledge_average, 2) }}</td>
                        <td>{{ number_format($record->teaching_average, 2) }}</td>
                        <td>{{ number_format($record->management_average, 2) }}</td>
                        <td>{{ number_format($record->overall_average, 2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <div class="footer">
            Generated on {{ now()->format('F d, Y h:i A') }}
        </div>
    </div>
</div>