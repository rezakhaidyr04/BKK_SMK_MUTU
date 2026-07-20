<style>
    .email-body { font-family: Arial, sans-serif; }
</style>
<div class="email-body">
    <h2>Surat Penawaran Pekerjaan: {{ $application->job->title }}</h2>
    <p>Halo {{ $application->user->name }},</p>
    <p>Selamat! Kami dengan senang hati menawarkan Anda posisi <strong>{{ $application->job->title }}</strong> di perusahaan kami.</p>
    <p>Silakan balas email ini atau hubungi perusahaan untuk konfirmasi lebih lanjut.</p>
    <p>Terima kasih,</p>
    <p>{{ $application->job->company->name }}</p>
</div>
