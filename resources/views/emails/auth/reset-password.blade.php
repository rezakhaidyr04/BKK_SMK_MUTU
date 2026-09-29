@component('mail::message')
# Reset Password Akunmu

Halo **{{ $name }}**, kami menerima permintaan reset password untuk akunmu di **{{ config('app.name') }}**. Klik tombol di bawah untuk membuat password baru:

@component('mail::button', ['url' => $url])
Reset Password
@endcomponent

Tautan reset berlaku selama **{{ $expire }} menit**. Jika tombol di atas tidak berfungsi, salin dan tempel tautan berikut ke browser:

{{ $url }}

Jika kamu tidak meminta reset password, abaikan saja email ini — akunmu tetap aman.

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
