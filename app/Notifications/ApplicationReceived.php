<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\DatabaseMessage;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\Application;

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
        return [
            'application_id' => $this->application->id,
            'job_id' => $job->id ?? null,
            'job_title' => $job->title ?? null,
            'applicant_id' => $this->application->user_id,
            'applicant_name' => optional($this->application->user)->name,
            'message' => 'New application received for ' . ($job->title ?? 'a job')
        ];
    }

    public function toMail($notifiable)
    {
        $job = $this->application->job;
        return (new \Illuminate\Notifications\Messages\MailMessage)
            ->subject('New application for ' . ($job->title ?? 'your job'))
            ->greeting('Hello ' . ($notifiable->name ?? ''))
            ->line('You have received a new application from ' . optional($this->application->user)->name . '.')
            ->line('Job: ' . ($job->title ?? 'N/A'))
            ->action('View Application', url('/admin/applications/' . $this->application->id))
            ->line('Thank you for using our application!');
    }
}
