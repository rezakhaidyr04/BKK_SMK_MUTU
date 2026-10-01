<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

class ApplicationStatusUpdated extends Notification
{
    use Queueable;

    public $application;
    public $statusLabel;

    /**
     * Create a new notification instance.
     */
    public function __construct($application)
    {
        $this->application = $application;
        $this->statusLabel = \App\Support\Label::applicationStatus($application->status);
    }

    /**
     * Get the notification's delivery channels.
     * Email + database: setiap perubahan tahap (termasuk wawancara via
     * InterviewScheduled) otomatis masuk ke email dan lonceng aplikasi.
     * Sinkron (tanpa ShouldQueue) agar tidak nyangkut tanpa worker;
     * controller sudah membungkus notify() dengan try/catch.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $app = $this->application;
        $jobTitle = $app->job->title ?? 'Lowongan';
        $companyName = $app->job->company_name ?? 'Perusahaan';

        return (new MailMessage)
            ->subject("Update Lamaran: {$jobTitle} — {$this->statusLabel}")
            ->markdown('emails.applications.status', [
                'name' => $notifiable->name,
                'heading' => "Lamaran {$this->statusLabel}",
                'jobTitle' => $jobTitle,
                'companyName' => $companyName,
                'statusLabel' => $this->statusLabel,
                'intro' => "Status lamaran Anda untuk posisi {$jobTitle} diubah menjadi {$this->statusLabel}. Buka halaman lamaran untuk detail selanjutnya.",
                'url' => route('applications.show', $app),
            ]);
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'application_status',
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'company_name' => $this->application->job->company_name ?? 'Perusahaan',
            'status' => $this->application->status,
            'message' => "Status lamaran Anda untuk posisi {$this->application->job->title} diubah menjadi {$this->statusLabel}.",
            'url' => route('applications.show', $this->application->id),
        ];
    }
}
