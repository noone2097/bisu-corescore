<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\EvaluationPeriod;
use Illuminate\Support\Facades\Log;

class TestEvaluationNotify extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:evaluation-notify {evaluation_id : The ID of the evaluation period to test}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test the notification method for an evaluation period';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $evaluationId = $this->argument('evaluation_id');
        $this->info("Testing notification for evaluation period ID: {$evaluationId}");
        
        try {
            $evaluation = EvaluationPeriod::find($evaluationId);
            
            if (!$evaluation) {
                $this->error("Evaluation period with ID {$evaluationId} not found");
                return 1;
            }
            
            $this->info("Found evaluation period: {$evaluation->name}");
            
            // Directly call the notification method
            $this->info("Calling notifyStatusChange() method directly...");
            Log::info("Manual test of notification method", [
                'evaluation_id' => $evaluation->id,
                'evaluation_name' => $evaluation->name
            ]);
            
            $evaluation->notifyStatusChange();
            
            $this->info("Notification method called successfully");
            $this->info("Check the logs at storage/logs/laravel.log for details");
            
            return 0;
        } catch (\Exception $e) {
            $this->error("Error: {$e->getMessage()}");
            $this->error($e->getTraceAsString());
            return 1;
        }
    }
}
