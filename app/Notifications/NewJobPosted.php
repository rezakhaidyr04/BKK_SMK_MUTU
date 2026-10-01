<?php

namespace App\Notifications;

use App\Models\Job;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;

// Notifikasi otomatis saat lowongan baru dipublikasikan (admin approve).
// Channel database saja: pengiriman sinkron ke banyak pengguna sekaligus,
// sehingga tidak menggantung request (blast email massal tetap lewat
// tombol Broadcast manual di halaman admin).
class NewJobPosted extends Notification
{
    use Queueable;

    public function __construct(protected Job $job) {}

    public function via($notifiable): array
    {
        return ['database'];
    }

    public function toArray($notifiable): array
    {
        $job = $this->job;

        return [
            'type' => 'new_job',
            'job_id' => $job->id,
            'job_title' => $job->title ?? 'Lowongan baru',
            'company_name' => $job->company_name ?? 'Perusahaan',
            'location' => $job->location ?? null,
            'message' => 'Lowongan baru: ' . ($job->title ?? 'posisi baru') . ' di ' . ($job->company_name ?? 'perusahaan mitra') . '.',
            'url' => route('jobs.show', $job->id),
        ];
    }
}
