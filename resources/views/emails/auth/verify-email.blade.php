@component('mail::message')
# Verifikasi Email Kamu!

Halo **{{ $name }}**, terima kasih sudah mendaftar di **{{ config('app.name') }}**. Satu langkah lagi — verifikasi alamat email ini agar akunmu aktif sepenuhnya:

@component('mail::button', ['url' => $url])
Verifikasi Email Saya
@endcomponent

Tautan verifikasi berlaku selama **{{ $expire }} menit**. Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser:

{{ $url }}

Jika kamu tidak merasa mendaftar, abaikan saja email ini.

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
