<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class DepartmentPasswordSetup extends Notification
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
        $url = route('department.password.setup.form', ['token' => $this->token]);

        return (new MailMessage)
            ->subject('Department Account Password Setup')
            ->greeting('Hello!')
            ->line('You are receiving this email because a department account has been created for you.')
            ->action('Setup Password', $url)
            ->line('This password setup link will expire in 24 hours.')
            ->line('If you did not request a department account, no further action is required.');
    }
}