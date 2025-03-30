<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class GenerateTestQr extends Command
{
    protected $signature = 'qr:test';
    protected $description = 'Generate a test QR code';

    public function handle()
    {
        try {
            // Create directory if it doesn't exist
            $qrDir = public_path('qr-codes');
            if (!file_exists($qrDir)) {
                mkdir($qrDir, 0777, true);
            }

            // Generate QR code
            $qrCode = QrCode::format('svg')
                ->encoding('UTF-8')
                ->size(300)
                ->margin(1)
                ->errorCorrection('H')
                ->generate('test');

            // Save with Windows path
            $path = str_replace('/', '\\', 'qr-codes\\test.svg');
            $fullPath = public_path($path);

            // Remove old file if exists
            if (file_exists($fullPath)) {
                unlink($fullPath);
            }

            // Save new QR code
            if (file_put_contents($fullPath, $qrCode) === false) {
                throw new \Exception('Failed to save QR code');
            }

            // Set permissions
            chmod($fullPath, 0777);

            $this->info('Test QR code generated successfully at: ' . $fullPath);
            return 0;

        } catch (\Exception $e) {
            $this->error('Failed to generate QR code: ' . $e->getMessage());
            return 1;
        }
    }
}