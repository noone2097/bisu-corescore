<?php

namespace App\Notifications;

use App\Models\EvaluationPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EvaluationPeriodCompletedEmail extends Notification implements ShouldQueue
{
    use Queueable;

    protected EvaluationPeriod $evaluationPeriod;
    protected string $recipientType;

    /**
     * Create a new notification instance.
     */
    public function __construct(EvaluationPeriod $evaluationPeriod, string $recipientType)
    {
        $this->evaluationPeriod = $evaluationPeriod;
        $this->recipientType = $recipientType;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        $startDate = date('F d, Y', strtotime($this->evaluationPeriod->start_date));
        $endDate = date('F d, Y', strtotime($this->evaluationPeriod->end_date));
        $academicYear = $this->evaluationPeriod->academic_year;
        $semester = $this->evaluationPeriod->type;
        
        $mail = (new MailMessage)
            ->from(config('mail.from.address'), config('mail.from.name'))
            ->subject('Faculty Evaluation Period Completed')
            ->greeting("Dear {$this->recipientType},")
            ->line("We would like to inform you that the Faculty Evaluation Period for **{$academicYear} {$semester} Semester** has been completed.")
            ->line(new HtmlString("<strong>Period Details:</strong>"))
            ->line("- Start Date: {$startDate}")
            ->line("- End Date: {$endDate}");

        // Add recipient-specific content
        if ($this->recipientType === 'Student') {
            $mail->line('Thank you for participating in the faculty evaluation process. Your feedback is valuable and helps us improve the quality of education.')
                ->action('Visit Student Dashboard', url('/student/dashboard'));
        } elseif ($this->recipientType === 'Faculty') {
            $mail->line('The evaluation results will be processed and made available to you soon. Thank you for your cooperation during this evaluation period.')
                ->action('View Faculty Dashboard', url('/faculty/dashboard'));
        } else {
            $mail->line('The evaluation results for your department will be processed and available for review soon. Thank you for overseeing this evaluation period.')
                ->action('View Evaluation Dashboard', url('/dashboard'));
        }

        return $mail->salutation(new HtmlString('Best regards,<br>BISU CoreScore System'));
    }
}
