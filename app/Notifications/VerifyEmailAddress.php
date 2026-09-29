<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

// Sinkron (tanpa ShouldQueue): konsisten dengan notifikasi lain agar
// langsung terkirim tanpa tergantung queue worker.
class VerifyEmailAddress extends Notification
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $url = URL::temporarySignedRoute(
            'verification.verify',
            Carbon::now()->addMinutes(Config::get('auth.verification.expire', 60)),
            [
                'id'   => $notifiable->getKey(),
                'hash' => sha1($notifiable->getEmailForVerification()),
            ]
        );

        return (new MailMessage)
            ->subject('Verifikasi Email Akun ' . config('app.name'))
            ->markdown('emails.auth.verify-email', [
                'name' => $notifiable->name,
                'url'  => $url,
                'expire' => Config::get('auth.verification.expire', 60),
            ]);
    }
}
