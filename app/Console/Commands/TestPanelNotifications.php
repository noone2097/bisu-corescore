<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Students;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification;
use Filament\Facades\Filament;

class TestPanelNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:panel-notifications {--panel= : Specific panel to test (faculty, department, students, research-admin)}'; 

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending notifications to specific panels';

    protected $panelMapping = [
        'faculty' => [
            'guard' => 'web',
            'panel' => 'faculty',
            'model' => User::class,
            'role' => 'faculty'
        ],
        'department' => [
            'guard' => 'web',
            'panel' => 'department',
            'model' => User::class,
            'role' => 'department'
        ],
        'students' => [
            'guard' => 'students',
            'panel' => 'students',
            'model' => Students::class,
            'role' => null
        ],
        'research-admin' => [
            'guard' => 'web',
            'panel' => 'research-admin',
            'model' => User::class,
            'role' => 'research-admin'
        ],
    ];

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing multi-panel notifications...');
        
        $targetPanel = $this->option('panel');
        
        if ($targetPanel && !isset($this->panelMapping[$targetPanel])) {
            $this->error("Invalid panel: {$targetPanel}");
            $this->info("Available panels: " . implode(', ', array_keys($this->panelMapping)));
            return 1;
        }
        
        try {
            if ($targetPanel) {
                // Test specific panel
                $this->testPanel($targetPanel);
            } else {
                // Test all panels
                foreach (array_keys($this->panelMapping) as $panel) {
                    $this->testPanel($panel);
                }
            }
            
            $this->info('\nTest completed successfully.');
            $this->info('Please check your Laravel log at storage/logs/laravel.log for details');
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error executing test: {$e->getMessage()}");
            Log::error('Panel notification test error', [
                'error' => $e->getMessage(),
                'trace' => $e->getTraceAsString()
            ]);
            return 1;
        }
    }
    
    /**
     * Test sending notifications to a specific panel
     */
    protected function testPanel(string $panelName): void
    {
        $config = $this->panelMapping[$panelName];
        $this->info("\nTesting notifications for {$panelName} panel...");
        
        // Get users for this panel
        $query = $config['model']::query();
        if ($config['role']) {
            $query->where('role', $config['role']);
        }
        $query->where('is_active', true);
        $users = $query->get();
        
        $this->info("Found {$users->count()} users in {$panelName} panel");
        
        $success = 0;
        foreach ($users as $user) {
            try {
                Notification::make()
                    ->title("Test {$panelName} Panel Notification")
                    ->body("This is a test notification for the {$panelName} panel sent at " . now()->format('Y-m-d H:i:s'))
                    ->success()
                    ->icon('heroicon-o-bell')
                    ->sendToDatabase($user);
                
                $success++;
                $this->info("  - Sent notification to {$user->name} ({$user->email})");
                
                // Log the notification
                Log::info("Sent test panel notification", [
                    'panel' => $panelName,
                    'user_id' => $user->id,
                    'user_email' => $user->email,
                    'notification_type' => 'Filament\\Notifications\\DatabaseNotification'
                ]);
            } catch (\Exception $e) {
                $this->error("  - Failed to send notification to {$user->name}: {$e->getMessage()}");
            }
        }
        
        $this->info("Successfully sent {$success} of {$users->count()} notifications for {$panelName} panel");
    }
}
