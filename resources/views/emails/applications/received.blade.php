@component('mail::message')
# Lamaran Baru Diterima!

Halo **{{ $companyName }}**, kabar baik — ada kandidat baru yang melamar ke perusahaan Anda:

@component('mail::panel')
## {{ $applicantName }}
Melamar posisi **{{ $jobTitle }}** pada {{ $appliedAt }}
@endcomponent

@component('mail::table')
| Detail | Info |
| :----- | :--- |
| Nama Pelamar | {{ $applicantName }} |
| Email | {{ $applicantEmail }} |
| Posisi Dilamar | {{ $jobTitle }} |
| Perusahaan | {{ $companyName }} |
| Tanggal Melamar | {{ $appliedAt }} |
@endcomponent

@component('mail::button', ['url' => $url, 'color' => 'success'])
Lihat Profil Pelamar
@endcomponent

Segera tinjau lamaran ini agar kandidat terbaik tidak keburu diambil perusahaan lain.

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
