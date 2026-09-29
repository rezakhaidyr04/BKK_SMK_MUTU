<?php

namespace App\Mail;

use App\Models\Job;
use App\Models\User;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Mail\Mailables\Content;
use Illuminate\Mail\Mailables\Envelope;
use Illuminate\Mail\Mailables\Headers;
use Illuminate\Queue\SerializesModels;

/**
 * Email broadcast lowongan — dikirim sinkron langsung ke Gmail.
 * Dilengkapi header List-Unsubscribe agar Gmail tidak menganggapnya
 * spam massal dan penerima punya opsi berhenti selain tombol spam.
 */
class JobBroadcastMail extends Mailable
{
    use Queueable, SerializesModels;

    public function __construct(
        public Job $job,
        public ?User $recipient = null,
    ) {}

    public function envelope(): Envelope
    {
        return new Envelope(
            subject: 'Lowongan Kerja Baru: ' . $this->job->title,
        );
    }

    public function headers(): Headers
    {
        return new Headers(
            text: [
                'List-Unsubscribe' => '<mailto:bkksmkmutu3@gmail.com?subject=Berhenti%20info%20lowongan>',
            ],
        );
    }

    public function content(): Content
    {
        $job = $this->job;
        $company = $job->company_name ?? $job->company->name ?? 'Perusahaan';

        // Hanya baris yang ada datanya yang tampil (tanpa tanda "-").
        $rows = [
            ['label' => 'Perusahaan', 'value' => $company],
            ['label' => 'Posisi', 'value' => $job->position ?? $job->title],
            ['label' => 'Lokasi', 'value' => $job->location],
            ['label' => 'Tipe Kerja', 'value' => \App\Support\Label::jobType($job->job_type ?? 'full_time')],
            ['label' => 'Gaji', 'value' => $this->salaryLine($job)],
            ['label' => 'Pendidikan', 'value' => $job->education],
            ['label' => 'Pengalaman', 'value' => $job->experience],
            ['label' => 'Jam Kerja', 'value' => $job->work_hours],
            ['label' => 'Deadline', 'value' => $job->deadline ? $job->deadline->translatedFormat('d F Y') : null],
        ];
        $rows = array_values(array_filter($rows, fn ($r) => filled($r['value'])));

        return new Content(
            markdown: 'emails.jobs.broadcast',
            with: [
                'name'        => $this->recipient->name ?? 'Pencari Kerja',
                'title'       => $job->title,
                'company'     => $company,
                'rows'        => $rows,
                'benefits'    => $job->benefits,
                'description' => $job->description ? \Illuminate\Support\Str::limit($job->description, 220) : null,
                'url'         => route('jobs.show', $job->id),
            ],
        );
    }

    /**
     * Baris gaji satu baris: "Rp 4.000.000 – Rp 7.000.000".
     * Pipe dihilangkan agar tidak merusak tabel markdown.
     */
    private function salaryLine(Job $job): ?string
    {
        $fmt = fn ($v) => 'Rp ' . number_format($v, 0, ',', '.');

        if ($job->salary_min && $job->salary_max) {
            return $fmt($job->salary_min) . ' – ' . $fmt($job->salary_max);
        }

        if ($job->salary_min) {
            return 'Mulai ' . $fmt($job->salary_min);
        }

        if ($job->salary_max) {
            return 'Hingga ' . $fmt($job->salary_max);
        }

        return null;
    }
}
