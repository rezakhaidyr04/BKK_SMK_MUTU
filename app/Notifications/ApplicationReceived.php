<?php

namespace App\Notifications;

use App\Models\Application;
use App\Support\Label;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;

class ApplicationReceived extends Notification implements ShouldQueue
{
    use Queueable;

    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
        $this->onQueue('notifications');
    }

    public function via($notifiable)
    {
        return ['database', 'mail'];
    }

    public function toDatabase($notifiable)
    {
        $job = $this->application->job;
        $jobTitle = $job->title ?? 'lowongan';

        return [
            'application_id' => $this->application->id,
            'job_id' => $job->id ?? null,
            'job_title' => $job->title ?? null,
            'applicant_id' => $this->application->user_id,
            'applicant_name' => optional($this->application->user)->name,
            'message' => 'Lamaran baru diterima untuk ' . $jobTitle,
        ];
    }

    public function toMail($notifiable)
    {
        $job = $this->application->job;
        $jobTitle = $job->title ?? 'lowongan Anda';
        $applicantName = optional($this->application->user)->name ?? 'Pelamar';

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Lamaran baru untuk ' . $jobTitle)
            ->greeting('Halo ' . ($notifiable->name ?? ''))
            ->line('Anda menerima lamaran baru dari ' . $applicantName . '.')
            ->line('Lowongan: ' . ($job->title ?? __('bkk.fallback.not_available')))
            ->action('Lihat Pelamar', url(route('company.applicants.index')))
            ->line('Terima kasih telah menggunakan platform kami!');
    }
}
