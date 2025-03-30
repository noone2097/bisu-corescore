<?php

namespace App\Filament\Office\Pages;

use Filament\Pages\Dashboard as DashboardPage;
use Filament\Actions\Action;
use SimpleSoftwareIO\QrCode\Facades\QrCode;
use Illuminate\Support\Facades\Auth;
use App\Filament\Office\Widgets\FeedbackStatsOverview;

class Dashboard extends DashboardPage
{
    protected static ?string $navigationIcon = 'heroicon-o-home';
    protected static ?string $title = 'Dashboard';
    protected static ?string $navigationLabel = 'Dashboard';
    protected static ?int $navigationSort = -2;

    public function getActions(): array
    {
        $office = auth()->user();
        $feedbackUrl = route('feedback.form.office', ['office' => $office->id]);
        
        // Generate QR code
        $qrCode = QrCode::format('svg')
                        ->size(300)
                        ->generate($feedbackUrl);
        
        $qrCodePath = "qr-codes/office-{$office->id}.svg";
        if (!file_exists(public_path('qr-codes'))) {
            mkdir(public_path('qr-codes'), 0777, true);
        }
        file_put_contents(public_path($qrCodePath), $qrCode);

        return [
            Action::make('generateQR')
                ->label('Feedback Form QR Code')
                ->color('primary')
                ->icon('heroicon-o-qr-code')
                ->modalContent(view('filament.office.pages.qr-modal', [
                    'qrCodePath' => asset($qrCodePath),
                    'feedbackUrl' => $feedbackUrl,
                ]))
                ->modalWidth('md')
                ->closeModalByClickingAway()
                ->modalSubmitAction(false)
                ->modalCancelAction(false)
                ->modalFooterActions([])
        ];
    }

    public function getHeaderWidgets(): array
    {
        return [
            FeedbackStatsOverview::class,
        ];
    }

    public function getWidgets(): array
    {
        return [
            \App\Filament\Office\Widgets\SentimentDistributionChart::class,
            \App\Filament\Office\Widgets\CitizensCharterChart::class,
            \App\Filament\Office\Widgets\ServiceQualityChart::class,
        ];
    }

    public function getColumns(): int | array
    {
        return 2;
    }

    public function getHeaderWidgetsColumns(): int | array
    {
        return 2;
    }
}