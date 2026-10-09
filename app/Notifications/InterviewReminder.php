<?php

namespace App\Notifications;

use App\Models\Application;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// D2: pengingat H-1 wawancara untuk pelamar (dikirim command interview:remind).
class InterviewReminder extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Application $application) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $app = $this->application;
        $date = $app->interview_date
            ? $app->interview_date->locale('id')->translatedFormat('l, d F Y \p\u\k\u\l H:i')
            : 'jadwal terlampir';
        $isOnline = $app->interview_type === 'online';
        $place = $isOnline
            ? ($app->interview_link ?: 'tautan menyusul')
            : ($app->interview_location ?: 'lokasi menyusul');

        return (new MailMessage)
            ->subject('Pengingat: Wawancara Besok — '.($app->job?->title ?? 'Lowongan'))
            ->greeting('Halo '.$notifiable->name.'!')
            ->line('Jangan lupa, wawancara Anda untuk posisi **'.($app->job?->title ?? 'lowongan').'** ('.($app->job?->company_name ?? 'perusahaan').') dijadwalkan:')
            ->line('📅 '.$date)
            ->line(($isOnline ? '🔗 ' : '📍 ').$place)
            ->action('Konfirmasi Kehadiran', route('applications.show', $app))
            ->line('Hadir tepat waktu dan berpakaian rapi. Semoga sukses!');
    }

    public function toArray(object $notifiable): array
    {
        $date = $this->application->interview_date
            ? $this->application->interview_date->locale('id')->translatedFormat('l, d F Y H:i')
            : 'jadwal menyusul';

        return [
            'type' => 'interview_reminder',
            'application_id' => $this->application->id,
            'job_title' => $this->application->job?->title ?? 'Lowongan',
            'company_name' => $this->application->job?->company_name ?? 'Perusahaan',
            'interview_date' => $date,
            'message' => "Wawancara besok ({$date}). Konfirmasi kehadiran Anda sekarang.",
            'url' => route('applications.show', $this->application->id),
        ];
    }
}
