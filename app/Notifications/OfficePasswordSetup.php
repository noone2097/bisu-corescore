<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class OfficePasswordSetup extends Notification
{
    use Queueable;

    protected $token;

    public function __construct($token)
    {
        $this->token = $token;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = route('office.password.setup', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('Office Account Password Setup')
            ->greeting('Hello!')
            ->line('You are receiving this email because an office account has been created for you.')
            ->action('Setup Password', $url)
            ->line('This password setup link will expire in 24 hours.')
            ->line('If you did not request an office account, no further action is required.');
    }
}