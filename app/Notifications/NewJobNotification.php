<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Job;

class NewJobNotification extends Notification implements ShouldQueue
{
    use Queueable;

    public $job;

    /**
     * Create a new notification instance.
     */
    public function __construct(Job $job)
    {
        $this->job = $job;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @return array<int, string>
     */
    public function via(object $notifiable): array
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     */
    public function toMail(object $notifiable): MailMessage
    {
        return (new MailMessage)
                    ->subject('Lowongan Kerja Baru: ' . $this->job->title)
                    ->greeting('Halo, ' . $notifiable->name . '!')
                    ->line('Ada lowongan pekerjaan baru yang mungkin cocok untuk Anda.')
                    ->line('**Posisi:** ' . ($this->job->position ?? $this->job->title))
                    ->line('**Perusahaan:** ' . ($this->job->company_name ?? $this->job->company->name ?? 'Perusahaan'))
                    ->line('**Tipe:** ' . \App\Support\Label::jobType($this->job->job_type ?? 'full_time'))
                    ->line('**Lokasi:** ' . $this->job->location)
                    ->line('**Gaji:** Rp ' . number_format($this->job->salary_min ?? 0, 0, ',', '.') . ' - Rp ' . number_format($this->job->salary_max ?? 0, 0, ',', '.'))
                    ->action('Lihat Detail Lowongan', route('jobs.show', $this->job->id))
                    ->line('Terima kasih telah menggunakan aplikasi BKK SMK MUTU!');
    }

    /**
     * Get the array representation of the notification.
     *
     * @return array<string, mixed>
     */
    public function toArray(object $notifiable): array
    {
        return [
            //
        ];
    }
}
