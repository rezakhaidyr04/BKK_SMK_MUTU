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
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['database'];
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            'application_id' => $this->application->id,
            'job_title' => $this->application->job->title,
            'company_name' => $this->application->job->company->name ?? 'Perusahaan',
            'status' => $this->application->status,
            'message' => "Status lamaran Anda untuk posisi {$this->application->job->title} diubah menjadi {$this->statusLabel}."
        ];
    }
}
