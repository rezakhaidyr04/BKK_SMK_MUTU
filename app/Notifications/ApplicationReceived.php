<?php

namespace App\Notifications;

use App\Models\Application;
use App\Support\Label;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// Sinkron (tanpa ShouldQueue): dikirim langsung saat melamar agar tidak
// nyangkut di antrean database tanpa worker.
class ApplicationReceived extends Notification
{
    use Queueable;

    protected Application $application;

    public function __construct(Application $application)
    {
        $this->application = $application;
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
            'type' => 'application_received',
            'application_id' => $this->application->id,
            'job_id' => $job->id ?? null,
            'job_title' => $job->title ?? null,
            'applicant_id' => $this->application->user_id,
            'applicant_name' => optional($this->application->user)->name,
            'message' => 'Lamaran baru diterima untuk ' . $jobTitle,
            'url' => route('company.applicants.show', $this->application->id),
        ];
    }

    public function toMail($notifiable)
    {
        $application = $this->application;
        $job = $application->job;
        $jobTitle = $job->title ?? 'lowongan Anda';
        $applicant = $application->user;

        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('Lamaran baru dari ' . ($applicant->name ?? 'Pelamar') . ' — ' . $jobTitle)
            ->markdown('emails.applications.received', [
                'companyName'    => $notifiable->name ?? $job->company_name ?? 'Perusahaan',
                'applicantName'  => $applicant->name ?? 'Pelamar',
                'applicantEmail' => $applicant->email ?? '-',
                'jobTitle'       => $jobTitle,
                'company'        => $job->company_name ?? 'Perusahaan',
                'appliedAt'      => $application->created_at
                    ? $application->created_at->translatedFormat('d F Y, H:i')
                    : '-',
                'url'            => route('company.applicants.show', $application->id),
            ]);
    }
}
