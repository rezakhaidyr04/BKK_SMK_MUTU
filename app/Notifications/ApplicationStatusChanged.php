<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Messages\BroadcastMessage;
use App\Models\Application;
use App\Support\Label;

class ApplicationStatusChanged extends Notification implements ShouldQueue
{
    use Queueable;

    protected $application;
    protected $oldStatus;

    public function __construct(Application $application, $oldStatus = null)
    {
        $this->application = $application;
        $this->oldStatus = $oldStatus;
    }

    public function via($notifiable)
    {
        return ['mail', 'database'];
    }

    public function toMail($notifiable)
    {
        $job = $this->application->job;
        $status = Label::applicationStatus($this->application->status);

        $mail = (new MailMessage)
                    ->subject("Status Aplikasi: {$job->title} - {$status}")
                    ->greeting("Halo {$notifiable->name},")
                    ->line("Status aplikasi Anda untuk posisi \"{$job->title}\" telah berubah: {$status}.")
                    ->line('Terima kasih telah menggunakan platform kami.');

        if ($this->application->status === 'accepted') {
            $mail->line('Selamat! Anda diterima. Perusahaan akan menghubungi Anda melalui kontak yang tersedia.')
                 ->action('Lihat Aplikasi', url(route('applications.show', $this->application)));
        } elseif ($this->application->status === 'rejected') {
            $mail->line('Maaf, aplikasi Anda belum berhasil kali ini. Silakan coba lowongan lain yang sesuai.')
                 ->action('Lihat Aplikasi', url(route('applications.show', $this->application)));
        } else {
            $mail->action('Lihat Aplikasi', url(route('applications.show', $this->application)));
        }

        return $mail;
    }

    public function toArray($notifiable)
    {
        return [
            'application_id' => $this->application->id,
            'job_id' => $this->application->job_id,
            'job_title' => $this->application->job->title,
            'status' => $this->application->status,
            'old_status' => $this->oldStatus,
        ];
    }
}
