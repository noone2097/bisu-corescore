<?php

namespace App\Filament\Office\Pages;

use Filament\Pages\Dashboard as BasePage;
use Filament\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;

class Dashboard extends BasePage
{
    public function getActions(): array
    {
        $office = Auth::guard('office')->user();
        $evaluationUrl = route('evaluations.form.office', ['office' => $office->id]);
        
        // Generate QR code
        $qrCode = QrCode::format('svg')
                        ->size(300)
                        ->generate($evaluationUrl);
        
        $qrCodePath = "qr-codes/office-{$office->id}.svg";
        if (!file_exists(public_path('qr-codes'))) {
            mkdir(public_path('qr-codes'), 0777, true);
        }
        file_put_contents(public_path($qrCodePath), $qrCode);

        return [
            Action::make('generateQR')
                ->label('Evaluation QR Code')
                ->color('success')
                ->icon('heroicon-o-qr-code')
                ->modalContent(view('filament.office.pages.qr-modal', [
                    'qrCodePath' => asset($qrCodePath),
                    'evaluationUrl' => $evaluationUrl,
                ]))
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
        ];
    }
}