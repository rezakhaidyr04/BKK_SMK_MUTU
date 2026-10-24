<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Hasil tinjauan verifikasi perusahaan: disetujui atau ditolak (beserta alasan).
class CompanyVerificationResult extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(
        protected Company $company,
        protected bool $approved,
    ) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject($this->approved ? 'Verifikasi Perusahaan Disetujui' : 'Verifikasi Perusahaan Ditolak')
            ->greeting('Halo '.$notifiable->name.'!')
            ->line($this->approved
                ? "Selamat! Verifikasi \"{$this->company->name}\" telah disetujui. Akun Anda kini aktif untuk merekrut kandidat."
                : "Maaf, verifikasi \"{$this->company->name}\" kami tolak.");

        if (! $this->approved) {
            $mail->line('Alasan: '.($this->company->rejection_reason ?? '—'))
                ->line('Perbarui profil dan dokumen legal, lalu ajukan kembali verifikasi.');
        }

        return $mail
            ->action('Buka Profil Perusahaan', route('company.profile.edit'))
            ->line('Terima kasih atas kerja sama Anda!');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'company_verification',
            'company_id' => $this->company->id,
            'approved' => $this->approved,
            'rejection_reason' => $this->approved ? null : $this->company->rejection_reason,
            'message' => $this->approved
                ? 'Verifikasi perusahaan disetujui. Akun Anda aktif untuk rekrutmen.'
                : 'Verifikasi ditolak: '.($this->company->rejection_reason ?? '—').'. Perbarui profil lalu ajukan kembali.',
            'url' => route('company.profile.edit'),
        ];
    }
}
