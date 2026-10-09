<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class TracerReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
            ->subject('Pengingat: Isi Tracer Study BKKMu')
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Kami belum menerima data Tracer Study Anda. Data ini penting untuk pemetaan alumni dan laporan penyaluran kerja BKK SMK MUTU.')
            ->line('Pengisian cukup 1 menit dan bisa diperbarui kapan saja saat status berubah.')
            ->action('Isi Tracer Study', route('tracer.index'))
            ->line('Terima kasih atas partisipasi Anda!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'tracer_reminder',
            'message' => 'Belum isi Tracer Study. Bantu BKK memetakan kabar alumni — cukup 1 menit.',
            'url' => route('tracer.index'),
        ];
    }
}
