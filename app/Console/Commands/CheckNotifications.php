<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:check {--type= : Filter by notification type} {--limit=10 : Number of notifications to show}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Check existing database notifications';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $type = $this->option('type');
        $limit = (int)$this->option('limit');
        
        $query = DB::table('notifications');
        
        if ($type) {
            $query->where('type', 'like', "%{$type}%");
        }
        
        $notifications = $query->orderBy('created_at', 'desc')
            ->limit($limit)
            ->get();
        
        $this->info("Found {$notifications->count()} notifications");
        
        if ($notifications->isEmpty()) {
            $this->warn('No notifications found');
            return 0;
        }
        
        $headers = ['ID', 'Type', 'Notifiable', 'Read', 'Created'];
        
        $rows = [];
        foreach ($notifications as $notification) {
            $data = json_decode($notification->data, true);
            $rows[] = [
                'id' => substr($notification->id, 0, 8) . '...',
                'type' => class_basename($notification->type),
                'notifiable' => "{$notification->notifiable_type} #{$notification->notifiable_id}",
                'read' => $notification->read_at ? 'Yes' : 'No',
                'created' => $notification->created_at,
            ];
            
            // Show notification data
            $this->line("\nNotification {$notification->id}:");
            $this->table(['Key', 'Value'], collect($data)->map(function ($value, $key) {
                return ['key' => $key, 'value' => is_array($value) ? json_encode($value) : (string)$value];
            })->toArray());
        }
        
        $this->table($headers, $rows);
        
        return 0;
    }
}
