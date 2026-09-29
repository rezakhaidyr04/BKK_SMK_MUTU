<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;

// Sinkron (tanpa ShouldQueue): dikirim langsung saat status diubah agar
// tidak nyangkut di antrean database tanpa worker.
class InterviewScheduled extends Notification
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
        // Null-safe: notifikasi antre diproses belakangan, jadwal bisa
        // sudah diubah/dihapus setelah job dibuat (ditemukan di failed_jobs).
        $date = $app->interview_date
            ? $app->interview_date->locale('id')->translatedFormat('l, d F Y \p\u\k\u\l H:i')
            : 'Jadwal menyusul — pantau halaman lamaran Anda';

        $jobTitle    = $job->title ?? 'Lowongan';
        $companyName = $job->company_name ?? 'Perusahaan';
        $isOnline    = $app->interview_type === 'online';

        return (new MailMessage)
            ->subject("Undangan Wawancara: {$jobTitle}")
            ->markdown('emails.applications.interview', [
                'name'        => $notifiable->name,
                'jobTitle'    => $jobTitle,
                'companyName' => $companyName,
                'date'        => $date,
                'typeLabel'   => $isOnline ? 'Online (Zoom/Meet)' : 'Tatap Muka (Offline)',
                'placeLine'   => $isOnline
                    ? ($app->interview_link ?: null)
                    : ($app->interview_location ?: null),
                'notes'       => $app->interview_notes,
                'url'         => route('applications.show', $app),
            ]);
    }

    public function toArray($notifiable): array
    {
        $app  = $this->application;
        $date = $app->interview_date
            ? $app->interview_date->locale('id')->translatedFormat('l, d F Y H:i')
            : 'Jadwal menyusul';

        return [
            'type'               => 'interview_scheduled',
            'application_id'     => $app->id,
            'job_id'             => $app->job_id,
            'job_title'          => $app->job->title ?? 'Lowongan',
            'company_name'       => $app->job->company_name ?? 'Perusahaan',
            'interview_date'     => $date,
            'interview_location' => $app->interview_location,
            'interview_type'     => $app->interview_type,
            'interview_link'     => $app->interview_link,
            'message'            => "Wawancara dijadwalkan pada {$date} di {$app->interview_location}",
        ];
    }
}
