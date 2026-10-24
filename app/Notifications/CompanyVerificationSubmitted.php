<?php

namespace App\Notifications;

use App\Models\Company;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;

// Pemberitahuan ke admin: perusahaan yang pernah ditolak telah memperbaiki
// dokumen dan mengajukan ulang verifikasi.
class CompanyVerificationSubmitted extends Notification implements ShouldQueue
{
    use Queueable;

    public function __construct(protected Company $company) {}

    public function via(object $notifiable): array
    {
        return ['mail', 'database'];
    }

    public function toMail(object $notifiable): MailMessage
    {
        $mail = (new MailMessage)
            ->subject('Pengajuan Ulang Verifikasi Perusahaan')
            ->greeting('Halo '.$notifiable->name.'!')
            ->line("\"{$this->company->name}\" telah memperbaiki dan mengajukan ulang verifikasi perusahaan.");

        if ($this->company->rejection_reason) {
            $mail->line('Sebelumnya ditolak: '.$this->company->rejection_reason);
        }

        return $mail
            ->action('Tinjau Perusahaan', route('admin.companies.show', $this->company))
            ->line('Silakan tinjau dokumen terbaru lalu setujui atau tolak kembali.');
    }

    public function toArray(object $notifiable): array
    {
        return [
            'type' => 'company_verification_submitted',
            'company_id' => $this->company->id,
            'rejection_reason' => $this->company->rejection_reason,
            'message' => "\"{$this->company->name}\" telah memperbaiki dan mengajukan ulang verifikasi.",
            'url' => route('admin.companies.show', $this->company),
        ];
    }
}
