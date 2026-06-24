<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

class InterviewScheduled extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Application $application) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $app  = $this->application;
        $job  = $app->job;
        $date = $app->interview_date->locale('id')->translatedFormat('l, d F Y \p\u\k\u\l H:i');

        $mail = (new MailMessage)
            ->subject("📅 Undangan Wawancara: {$job->title}")
            ->greeting("Halo {$notifiable->name},")
            ->line("Selamat! Anda diundang untuk mengikuti **wawancara** pada lamaran posisi **{$job->title}** di **{$job->company->name}**.")
            ->line("---")
            ->line("**📅 Tanggal & Waktu:** {$date}")
            ->line("**📍 Tempat:** {$app->interview_location}");

        if ($app->interview_type === 'online' && $app->interview_link) {
            $mail->line("**💻 Tipe:** Wawancara Online")
                 ->line("**🔗 Link:** {$app->interview_link}");
        } else {
            $mail->line("**🏢 Tipe:** Wawancara Tatap Muka");
        }

        if ($app->interview_notes) {
            $mail->line("**📝 Catatan:** {$app->interview_notes}");
        }

        return $mail
            ->line("---")
            ->line("Harap datang tepat waktu dan berpakaian rapi. Bawa dokumen pendukung seperti CV, KTP, dan ijazah.")
            ->action('Lihat Detail Lamaran', url(route('applications.show', $app)))
            ->line("Semangat dan tetap percaya diri!");
    }

    public function toArray($notifiable): array
    {
        $app  = $this->application;
        $date = $app->interview_date->locale('id')->translatedFormat('l, d F Y H:i');

        return [
            'type'               => 'interview_scheduled',
            'application_id'     => $app->id,
            'job_id'             => $app->job_id,
            'job_title'          => $app->job->title,
            'company_name'       => $app->job->company->name,
            'interview_date'     => $date,
            'interview_location' => $app->interview_location,
            'interview_type'     => $app->interview_type,
            'interview_link'     => $app->interview_link,
            'message'            => "Wawancara dijadwalkan pada {$date} di {$app->interview_location}",
        ];
    }
}
