<?php

namespace App\Notifications;

use App\Models\EvaluationPeriod;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\HtmlString;

class EvaluationPeriodActivatedEmail extends Notification implements ShouldQueue
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
            ->subject('Faculty Evaluation Period Now Active')
            ->greeting("Dear {$this->recipientType},")
            ->line("We would like to inform you that the Faculty Evaluation Period for **{$academicYear} {$semester} Semester** is now active.")
            ->line(new HtmlString("<strong>Period Details:</strong>"))
            ->line("- Start Date: {$startDate}")
            ->line("- End Date: {$endDate}");

        // Add recipient-specific content
        if ($this->recipientType === 'Student') {
            $mail->line('Please take a moment to evaluate your faculty members during this period. Your feedback is valuable and helps us improve the quality of education.')
                ->action('Go to Evaluation Dashboard', url('/student/dashboard'));
        } elseif ($this->recipientType === 'Faculty') {
            $mail->line('Please be informed that students will be evaluating your performance during this period.')
                ->action('View Faculty Dashboard', url('/faculty/dashboard'));
        } else {
            $mail->line('Please monitor the evaluation process for your department during this period.')
                ->action('View Evaluation Dashboard', url('/dashboard'));
        }

        return $mail->line('Thank you for your participation and cooperation.')
            ->salutation(new HtmlString('Best regards,<br>BISU CoreScore System'));
    }
}
