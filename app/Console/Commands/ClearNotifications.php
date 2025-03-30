<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class ClearNotifications extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'notifications:clear';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Clear all notifications from the database';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Clearing all notifications from database...');
        
        try {
            $count = DB::table('notifications')->count();
            DB::table('notifications')->truncate();
            
            $this->info("Successfully deleted {$count} notifications!");
            return 0;
        } catch (\Exception $e) {
            $this->error('Failed to clear notifications: ' . $e->getMessage());
            return 1;
        }
    }
}
