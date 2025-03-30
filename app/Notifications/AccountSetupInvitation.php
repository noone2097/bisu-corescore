<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Str;

class AccountSetupInvitation extends Notification
{
    use Queueable;

    protected string $role;

    public function __construct(string $role = 'department')
    {
        $this->role = $role;
    }

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $setupRoute = match($this->role) {
            'faculty' => 'faculty.setup.password',
            'office' => 'office.setup.password',
            default => 'password.setup'
        };
        
        $url = URL::temporarySignedRoute(
            $setupRoute,
            now()->addHours(48),
            ['email' => $notifiable->email]
        );

        $roleTitle = Str::title($this->role);

        return (new MailMessage)
            ->subject("$roleTitle Account Setup Invitation")
            ->line("You have been invited to set up your {$this->role} account.")
            ->action('Set Up Account', $url)
            ->line('This setup link will expire in 48 hours.')
            ->line('If you did not expect this invitation, no further action is required.');
    }
}