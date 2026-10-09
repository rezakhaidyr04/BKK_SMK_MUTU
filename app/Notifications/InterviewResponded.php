<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// D2: jawaban kehadiran pelamar untuk perusahaan.
// Sinkron (tanpa ShouldQueue) seperti InterviewScheduled agar tidak
// nyangkut tanpa worker; controller membungkus notify() dengan try/catch.
class InterviewResponded extends Notification
{
    use Queueable;

    public function __construct(protected Application $application) {}

    public function via($notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable): MailMessage
    {
        $app = $this->application;
        $confirmed = $app->interview_status === 'dikonfirmasi';
        $label = $confirmed ? 'Hadir' : 'Berhalangan';
        $applicant = $app->user?->name ?? 'Pelamar';

        return (new MailMessage)
            ->subject("Konfirmasi Wawancara {$label}: {$applicant}")
            ->greeting('Halo '.$notifiable->name.'!')
            ->line("{$applicant} menyatakan **{$label}** untuk wawancara posisi ".($app->job?->title ?? 'lowongan').'.')
            ->action('Lihat Detail Pelamar', route('company.applicants.show', $app))
            ->line($confirmed
                ? 'Pelamar akan hadir sesuai jadwal. Persiapkan proses wawancara.'
                : 'Pertimbangkan menjadwalkan ulang atau melanjutkan ke kandidat lain.');
    }

    public function toArray($notifiable): array
    {
        $confirmed = $this->application->interview_status === 'dikonfirmasi';

        return [
            'type' => 'interview_responded',
            'application_id' => $this->application->id,
            'job_title' => $this->application->job?->title ?? 'Lowongan',
            'applicant_name' => $this->application->user?->name ?? 'Pelamar',
            'interview_status' => $this->application->interview_status,
            'message' => ($this->application->user?->name ?? 'Pelamar').' menyatakan '.($confirmed ? 'hadir' : 'berhalangan').' untuk wawancara.',
            'url' => route('company.applicants.show', $this->application->id),
        ];
    }
}
