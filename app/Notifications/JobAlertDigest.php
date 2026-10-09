<?php

namespace App\Notifications;

use App\Models\JobAlert;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// E1: ringkasan mingguan lowongan cocok. Mail SAJA (tanpa database)
// agar lonceng aplikasi tidak kebanjiran tiap minggu.
class JobAlertDigest extends Notification implements ShouldQueue
{
    use Queueable;

    /**
     * @param  \Illuminate\Support\Collection<int, \App\Models\Job>  $jobs
     */
    public function __construct(protected JobAlert $alert, protected $jobs) {}

    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $count = $this->jobs->count();

        return (new MailMessage)
            ->subject("Job Alert: {$count} lowongan cocok untuk Anda")
            ->markdown('emails.jobs.alert-digest', [
                'name' => $notifiable->name,
                'criteria' => $this->alert->criteriaLabel(),
                'jobs' => $this->jobs,
                'url' => route('job-alerts.index'),
            ]);
    }
}
