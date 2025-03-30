<?php

namespace App\Console\Commands;

use App\Models\User;
use App\Models\Students;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Log;
use Filament\Notifications\Notification as FilamentNotification;
use Illuminate\Support\Facades\DB;

class TestFilamentNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:notifications {--user-id= : Specific user ID to send notification to} {--debug : Enable more detailed debug output}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test sending Filament notifications to users';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing Filament database notifications...');
        
        $debug = $this->option('debug');
        
        try {
            $success = 0;
            $failure = 0;
            
            // If user-id is specified, only send to that user
            if ($userId = $this->option('user-id')) {
                $user = User::find($userId);
                
                if (!$user) {
                    $this->error("User with ID {$userId} not found");
                    return 1;
                }
                
                $this->info("Sending test notification to user {$user->name} (ID: {$user->id})");
                
                // Use Filament Notification facade directly
                FilamentNotification::make()
                    ->title('Test Notification')
                    ->body('This is a test notification sent via the Filament Notification facade.')
                    ->success()
                    ->icon('heroicon-o-bell')
                    ->sendToDatabase($user);
                    
                $success++;
                $this->line("  - Created notification for {$user->name} ({$user->email})");
                
                if ($debug) {
                    $this->line("DEBUG: User information:");
                    $this->line("  - ID: {$user->id}");
                    $this->line("  - Name: {$user->name}");
                    $this->line("  - Email: {$user->email}");
                    $this->line("  - Role: {$user->role}");
                }
            } else {
                // Send to all active users
                $users = User::where('is_active', true)->get();
                $students = Students::where('is_active', true)->get();
                
                $this->info("Sending test notifications to {$users->count()} users and {$students->count()} students...");
                
                foreach ($users as $user) {
                    try {
                        // Use Filament Notification facade directly
                        FilamentNotification::make()
                            ->title('Test Notification')
                            ->body('This is a test notification sent via the Filament Notification facade.')
                            ->success()
                            ->icon('heroicon-o-bell')
                            ->sendToDatabase($user);
                            
                        $success++;
                        $this->line("  - Created notification for {$user->name} ({$user->email})");
                    } catch (\Exception $e) {
                        $failure++;
                        $this->error("  - Failed to create notification for {$user->name}: {$e->getMessage()}");
                        if ($debug) {
                            $this->line("    {$e->getTraceAsString()}");
                        }
                    }
                }
                
                foreach ($students as $student) {
                    try {
                        // Use Filament Notification facade directly
                        FilamentNotification::make()
                            ->title('Test Notification')
                            ->body('This is a test notification sent via the Filament Notification facade.')
                            ->success()
                            ->icon('heroicon-o-bell')
                            ->sendToDatabase($student);
                            
                        $success++;
                        $this->line("  - Created notification for {$student->name} ({$student->email})");
                    } catch (\Exception $e) {
                        $failure++;
                        $this->error("  - Failed to create notification for {$student->name}: {$e->getMessage()}");
                        if ($debug) {
                            $this->line("    {$e->getTraceAsString()}");
                        }
                    }
                }
            }
            
            $this->newLine();
            $this->info("Test completed with {$success} successful notifications and {$failure} failures");
            $this->info("Please check your Laravel log at storage/logs/laravel.log for details");
            $this->info("Login to the system with different user accounts to check if notifications appear in the bell icon");
            
            if ($debug) {
                // Display the latest notification in the database
                $latestNotification = DB::table('notifications')->orderBy('created_at', 'desc')->first();
                if ($latestNotification) {
                    $this->newLine();
                    $this->line("DEBUG: Latest notification in database:");
                    $this->line("  - ID: {$latestNotification->id}");
                    $this->line("  - Type: {$latestNotification->type}");
                    $this->line("  - Data: " . json_encode(json_decode($latestNotification->data), JSON_PRETTY_PRINT));
                }
            }
            
            return 0;
            
        } catch (\Exception $e) {
            $this->error("Error executing test: {$e->getMessage()}");
            if ($debug) {
                $this->line($e->getTraceAsString());
            }
            return 1;
        }
    }
}
