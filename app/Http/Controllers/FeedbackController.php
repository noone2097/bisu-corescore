<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Feedback;
use App\Models\Visitor;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;

class FeedbackController extends Controller
{
    public function showForm(Request $request, User $office = null)
    {
        $offices = User::where('role', 'office')->where('is_active', true)->get();
        $isOfficeLocked = !is_null($office);

        return view('feedback.form', [
            'office' => $office,
            'offices' => $offices,
            'isOfficeLocked' => $isOfficeLocked
        ]);
    }

    public function store(Request $request)
    {
        try {
            // Validate visitor information first
            $visitorData = $request->validate([
                'first_name' => 'required|string',
                'last_name' => 'required|string',
                'signature' => 'required|string',
            ]);

            // Create visitor first
            $visitor = Visitor::create($visitorData);

            // Validate feedback data
            $feedbackData = $request->validate([
                'office_id' => 'required|exists:users,id',
                'date_of_visit' => 'required|date',
                'time_of_visit' => 'required',
                'client_type' => 'required|string',
                'sex' => 'required|string',
                'region_of_residence' => 'required|string',
                'services_availed' => 'required|string',
                'served_by' => 'required|string',
                'cc1' => 'required|integer',
                'cc2' => 'nullable|integer',
                'cc3' => 'nullable|integer',
                'responsiveness' => 'required|integer',
                'reliability' => 'required|integer',
                'access_facilities' => 'required|integer',
                'communication' => 'required|integer',
                'costs' => 'required|integer',
                'integrity' => 'required|integer',
                'assurance' => 'required|integer',
                'outcome' => 'required|integer',
                'commendations' => 'nullable|string',
                'suggestions' => 'nullable|string',
            ]);

            // Add visitor_id to feedback data
            $feedbackData['visitor_id'] = $visitor->id;

            // Create feedback
            $feedback = Feedback::create($feedbackData);

            // Get the office from users table
            $office = User::where('id', $feedbackData['office_id'])
                         ->where('role', 'office')
                         ->firstOrFail();

            return redirect()->route('thank-you', $office);
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

    public function thankYou(User $office)
    {
        return view('feedback.thank-you', [
            'office' => $office
        ]);
    }

    public function generateQrPdf($qrCodePath)
    {
        try {
            $fullPath = public_path($qrCodePath);
            
            if (!file_exists($fullPath)) {
                \Log::error('QR Code file not found: ' . $fullPath);
                abort(404, 'QR Code file not found');
            }

            $qrCodeContent = file_get_contents($fullPath);
            if (!$qrCodeContent) {
                \Log::error('Failed to read QR Code file: ' . $fullPath);
                abort(500, 'Failed to read QR Code file');
            }

            // Extract office ID from the QR code path
            preg_match('/office-(\d+)\.svg/', basename($qrCodePath), $matches);
            $officeId = $matches[1] ?? null;

            if (!$officeId) {
                \Log::error('Failed to extract office ID from path: ' . $qrCodePath);
                abort(500, 'Invalid QR code path');
            }

            $feedbackUrl = route('feedback.form.office', ['office' => $officeId]);

            $pdf = PDF::loadView('pdf.qr-code', [
                'qrCodeContent' => base64_encode($qrCodeContent),
                'feedbackUrl' => $feedbackUrl
            ]);

            $pdf->setPaper('a4');
            return $pdf->stream('feedback-qr-code.pdf');

        } catch (\Exception $e) {
            \Log::error('PDF Generation Error: ' . $e->getMessage());
            abort(500, 'Failed to generate PDF');
        }
    }
}