<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

class CustomVerifyEmail extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     */
    public function __construct()
    {
        //
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
    public function toMail($notifiable)
    {
        $verificationUrl = $this->buildVerificationUrl($notifiable);

        if (config('app.debug')) {
            \Log::info("Email verification link for {$notifiable->email}: $verificationUrl");

            return (new \Illuminate\Notifications\Messages\MailMessage)
                ->subject('Email Verification (Debug Mode)')
                ->line('Check your log file for the verification link.');
        }

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Verify Your Email Address')
            ->line('Please click the button below to verify your email address.')
            ->action('Verify Email', $verificationUrl)
            ->line('If you did not create an account, no further action is required.');
    }

    protected function buildVerificationUrl($notifiable)
    {
        return \URL::temporarySignedRoute(
            'verification.verify',
            \Carbon\Carbon::now()->addMinutes(config('auth.verification.expire', 60)),
            [
                'id' => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );
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
