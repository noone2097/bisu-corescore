<?php

namespace App\Notifications;

use App\Models\EvaluationPeriod;
use Filament\Notifications\Actions\Action;
use Filament\Notifications\Notification;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\DatabaseNotification;

class EvaluationPeriodActivated extends DatabaseNotification implements ShouldQueue
{
    use Queueable;

    protected $evaluationPeriod;

    /**
     * Create a new notification instance.
     */
    public function __construct(EvaluationPeriod $evaluationPeriod)
    {
        $this->evaluationPeriod = $evaluationPeriod;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toDatabase(object $notifiable): array
    {
        return Notification::make()
            ->title('New Evaluation Period')
            ->icon('heroicon-o-clipboard-document-check')
            ->body("A new evaluation period has been activated for {$this->evaluationPeriod->academic_year}.")
            ->actions([
                Action::make('view')
                    ->button()
                    ->markAsRead()
            ])
            ->getDatabaseMessage();
    }
}
