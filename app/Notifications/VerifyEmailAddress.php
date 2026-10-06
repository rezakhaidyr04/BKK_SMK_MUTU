<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\URL;

// L7: queued (ShouldQueue) agar SMTP down/lambat TIDAK pernah membuat
// request registrasi 500/gantung. Worker mengirim via antrean database
// (retry/backoff standar); batas SMTP via MAIL_TIMEOUT.
class VerifyEmailAddress extends Notification implements \Illuminate\Contracts\Queue\ShouldQueue
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
