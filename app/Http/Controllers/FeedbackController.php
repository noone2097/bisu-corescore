<?php

namespace App\Http\Controllers;

use App\Models\Office;
use App\Models\Visitor;
use App\Models\Feedback;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FeedbackController extends Controller
{
    public function index(Office $office = null)
    {
        $offices = Office::where('status', 'active')->get();
        $selectedOffice = $office;
        $isOfficeLocked = !is_null($office);

        return view('feedback.form', compact('offices', 'selectedOffice', 'isOfficeLocked'));
    }

    public function store(Request $request)
    {
        // Debug: Log the incoming request data
        \Log::info('Form Data:', $request->all());

        $messages = [
            'date_of_visit.required' => 'The date of visit is required.',
            'time_of_visit.required' => 'The time of visit is required.',
            'time_of_visit.date_format' => 'The time format is invalid.',
            'office_id.required' => 'Please select an office.',
            'client_type.required' => 'Please select your client type.',
            'sex.required' => 'Please select your sex.',
            'region_of_residence.required' => 'Please enter your region of residence.',
            'services_availed.required' => 'Please enter the services you availed.',
            'served_by.required' => 'Please enter who served you.',
            'first_name.required' => 'Please enter your first name.',
            'last_name.required' => 'Please enter your last name.',
            'signature.required' => 'Please provide your signature.',
            'cc1.required' => 'Please answer the first CC question.',
            'cc1.between' => 'Please select a valid answer for the first CC question.',
            'responsiveness.required' => 'Please rate the responsiveness.',
            'responsiveness.between' => 'Please select a valid rating for responsiveness.',
            'reliability.required' => 'Please rate the reliability.',
            'reliability.between' => 'Please select a valid rating for reliability.',
            'access_facilities.required' => 'Please rate the access and facilities.',
            'access_facilities.between' => 'Please select a valid rating for access and facilities.',
            'communication.required' => 'Please rate the communication.',
            'communication.between' => 'Please select a valid rating for communication.',
            'costs.required' => 'Please rate the costs.',
            'costs.between' => 'Please select a valid rating for costs.',
            'integrity.required' => 'Please rate the integrity.',
            'integrity.between' => 'Please select a valid rating for integrity.',
            'assurance.required' => 'Please rate the assurance.',
            'assurance.between' => 'Please select a valid rating for assurance.',
            'outcome.required' => 'Please rate the outcome.',
            'outcome.between' => 'Please select a valid rating for outcome.',
        ];

        try {
            // Format time properly
            if ($request->has('time_of_visit')) {
                $time = date('H:i', strtotime($request->time_of_visit));
                $request->merge(['time_of_visit' => $time]);
            }

            // Debug: Log the request data before validation
            \Log::info('Processing form submission:', [
                'data' => $request->all()
            ]);

            // All validation rules in one call
            $validated = $request->validate([
                'date_of_visit' => 'required|date',
                'time_of_visit' => 'required|date_format:H:i',
                'office_id' => 'required|exists:offices,id',
                'client_type' => 'required',
                'sex' => 'required',
                'region_of_residence' => 'required',
                'services_availed' => 'required',
                'served_by' => 'required',
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'signature' => 'required|string',
                'cc1' => 'required|integer|between:1,4',
                'cc2' => 'nullable|integer|between:1,5',
                'cc3' => 'nullable|integer|between:1,4',
                'responsiveness' => 'required|integer|between:0,5',
                'reliability' => 'required|integer|between:0,5',
                'access_facilities' => 'required|integer|between:0,5',
                'communication' => 'required|integer|between:0,5',
                'costs' => 'required|integer|between:0,5',
                'integrity' => 'required|integer|between:0,5',
                'assurance' => 'required|integer|between:0,5',
                'outcome' => 'required|integer|between:0,5',
                'commendations' => 'nullable|string',
                'suggestions' => 'nullable|string',
            ], $messages);

            // Debug: Log successful validation
            \Log::info('Validation passed:', ['validated_data' => $validated]);

            // Process and validate signature data
            $signature = $validated['signature'];
            if (!str_starts_with($signature, 'data:image/png;base64,')) {
                throw new \Exception('Invalid signature format');
            }

            // Create visitor first
            try {
                $visitor = Visitor::create([
                    'first_name' => $validated['first_name'],
                    'last_name' => $validated['last_name'],
                    'signature' => $signature,
                ]);
            } catch (\Exception $e) {
                \Log::error('Error creating visitor:', [
                    'error' => $e->getMessage(),
                    'trace' => $e->getTraceAsString(),
                    'signature_length' => strlen($signature)
                ]);
                throw $e;
            }

            // Remove visitor fields from feedback data
            unset($validated['first_name'], $validated['last_name'], $validated['signature']);
            
            // Add visitor_id to feedback data
            $validated['visitor_id'] = $visitor->id;

            // Create feedback
            $feedback = Feedback::create($validated);

            \Log::info('Successfully created feedback:', [
                'feedback_id' => $feedback->id,
                'visitor_id' => $visitor->id
            ]);

            return view('feedback.thank-you', [
                'office_id' => $validated['office_id']
            ]);

        } catch (\Illuminate\Validation\ValidationException $e) {
            \Log::error('Validation failed:', [
                'errors' => $e->errors(),
                'data' => $request->all()
            ]);
            throw $e;
        } catch (\Exception $e) {
            \Log::error('Error saving feedback:', [
                'message' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            
            return back()
                ->withInput()
                ->withErrors(['error' => 'There was an error saving your feedback. Please try again.']);
        }
    }

    public function thankYou(Office $office = null)
    {
        return view('feedback.thank-you')->with('office', $office);
    }

    public function generateQrPdf($qrCodePath)
    {
        try {
            // Clean the filename and ensure it's just the basename
            $filename = basename($qrCodePath);
            
            // Get the full path of the QR code
            $fullQrPath = public_path('qr-codes/' . $filename);
            
            if (!file_exists($fullQrPath)) {
                throw new \Exception("QR Code file not found: {$filename}");
            }

            // Read the file contents
            $qrCodeContents = file_get_contents($fullQrPath);
            if ($qrCodeContents === false) {
                throw new \Exception("Could not read QR Code file: {$filename}");
            }

            // Generate PDF
            $pdf = PDF::loadView('pdf.qr-code', [
                'qrCodePath' => $fullQrPath,
                'qrCodeData' => base64_encode($qrCodeContents)
            ]);

            // Set paper size to A4
            $pdf->setPaper('a4', 'portrait');

            // Return the PDF for download
            return $pdf->stream('feedback-qr-code.pdf');
            
        } catch (\Exception $e) {
            \Log::error('Error generating QR code PDF:', [
                'error' => $e->getMessage(),
                'qrCodePath' => $qrCodePath
            ]);
            abort(404, $e->getMessage());
        }
    }
}