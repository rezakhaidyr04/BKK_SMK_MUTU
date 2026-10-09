<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// F3: pengingat MoU akan/sudah kedaluwarsa untuk akun perusahaan.
class MouExpiring extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Company $company) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $expired = $this->company->mouExpired();
        $date = $this->company->mou_expires_at?->translatedFormat('d F Y') ?? '-';

        return (new MailMessage)
            ->subject($expired ? 'MoU Kerja Sama Telah Kedaluwarsa' : 'MoU Kerja Sama Segera Kedaluwarsa')
            ->greeting('Halo '.$notifiable->name.'!')
            ->line($expired
                ? "MoU kerja sama {$this->company->name} (No: ".($this->company->mou_number ?? '-').") telah kedaluwarsa pada {$date}."
                : "MoU kerja sama {$this->company->name} (No: ".($this->company->mou_number ?? '-').") kedaluwarsa pada {$date}.")
            ->line('Segera hubungi tim BKK SMK TI Muhammadiyah Cikampek untuk perpanjangan agar lowongan tetap tayang tanpa gangguan.')
            ->action('Buka Profil Perusahaan', route('company.profile.edit'))
            ->line('Terima kasih atas kerja sama Anda!');
    }

    public function toArray(object $notifiable): array
    {
        $expired = $this->company->mouExpired();

        return [
            'type' => 'mou_expiring',
            'company_id' => $this->company->id,
            'mou_expires_at' => $this->company->mou_expires_at?->format('Y-m-d'),
            'message' => $expired
                ? 'MoU kerja sama telah kedaluwarsa. Hubungi admin untuk perpanjangan.'
                : 'MoU kedaluwarsa '.($this->company->mou_expires_at?->translatedFormat('d F Y') ?? 'segera').'. Hubungi admin untuk perpanjangan.',
            'url' => route('company.profile.edit'),
        ];
    }
}
