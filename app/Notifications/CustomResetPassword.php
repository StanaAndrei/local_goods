<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Auth\Notifications\ResetPassword as BaseResetPassword;
use Illuminate\Support\Facades\Log;

class CustomResetPassword extends BaseResetPassword
{
    use Queueable;

    public function __construct($token)
    {
        $this->token = $token;
    }

    // FIXED: No type hint, no return type
    public function via($notifiable)
    {
        return ['mail'];
    }

    public function toMail($notifiable)
    {
        $resetUrl = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        if (config('app.debug')) {
            Log::info("Password reset link for {$notifiable->email}: $resetUrl");
            return (new MailMessage)
                ->subject('Password Reset (Debug Mode)')
                ->line('Check your log file for the password reset link.');
        }

        return parent::toMail($notifiable);
    }

    // FIXED: No type hint, no return type
    public function toArray($notifiable)
    {
        return [
            //
        ];
    }
}