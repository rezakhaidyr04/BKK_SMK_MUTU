@component('mail::message')
# Undangan Wawancara!

Halo **{{ $name }}**, selamat! Anda lolos ke tahap **wawancara** untuk posisi **{{ $jobTitle }}** di **{{ $companyName }}**.

@component('mail::panel')
## {{ $date }}
@if(!empty($placeLine))
{{ $placeLine }}
@endif
@endcomponent

@component('mail::table')
| Detail | Info |
| :----- | :--- |
| Posisi | {{ $jobTitle }} |
| Perusahaan | {{ $companyName }} |
| Tanggal & Waktu | {{ $date }} |
| Tipe Wawancara | {{ $typeLabel }} |
@if(!empty($placeLine))
| Tempat / Link | {{ $placeLine }} |
@endif
@endcomponent

@if(!empty($notes))
**Catatan dari perusahaan:** {{ $notes }}
@endif

@component('mail::button', ['url' => $url])
Lihat Detail Lamaran
@endcomponent

**Tips persiapan:** datang 15 menit lebih awal, bawa CV/KTP/ijazah, dan berpakaian rapi.

Semangat dan tetap percaya diri!

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
