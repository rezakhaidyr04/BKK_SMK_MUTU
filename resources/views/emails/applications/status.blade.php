@component('mail::message')
# {{ $heading }}

Halo **{{ $name }}**, ada kabar terbaru untuk lamaran Anda pada posisi **{{ $jobTitle }}** di **{{ $companyName }}**.

@component('mail::panel')
## Status: {{ $statusLabel }}
@endcomponent

{{ $intro }}

@component('mail::button', ['url' => $url])
Lihat Detail Lamaran
@endcomponent

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
