<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Mail;

class AdminPasswordSetup extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    protected string $token;

    public function __construct(string $token)
    {
        $this->token = $token;
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
        $url = url("/admin/password/setup/{$this->token}");
        
        Mail::send([], [], function ($message) use ($notifiable, $url) {
            $message
                ->to($notifiable->admin_email)
                ->subject('Set Up Your Admin Account Password')
                ->html(
                    "Hello {$notifiable->admin_name}!<br><br>" .
                    "You have been added as an administrator. Please set up your password to access your account.<br><br>" .
                    "<a href=\"{$url}\">Click here to set your password</a><br><br>" .
                    "This password setup link will expire in 24 hours.<br><br>" .
                    "If you did not expect to receive this email, please ignore it."
                );
        });
        
        // We still need to return a MailMessage object to satisfy the interface
        return (new MailMessage)
                ->subject('Set Up Your Admin Account Password');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
