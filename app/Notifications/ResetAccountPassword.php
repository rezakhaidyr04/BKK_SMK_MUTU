<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Sinkron (tanpa ShouldQueue): konsisten dengan notifikasi lain agar
// langsung terkirim tanpa tergantung queue worker.
class ResetAccountPassword extends Notification
{
    use Queueable;

    public function __construct(public string $token) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = url(route('password.reset', [
            'token' => $this->token,
            'email' => $notifiable->getEmailForPasswordReset(),
        ], false));

        return (new MailMessage)
            ->subject('Reset Password Akun ' . config('app.name'))
            ->markdown('emails.auth.reset-password', [
                'name'   => $notifiable->name,
                'url'    => $url,
                'expire' => config('auth.passwords.users.expire', 60),
            ]);
    }
}
