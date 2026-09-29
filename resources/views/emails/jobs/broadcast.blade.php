@component('mail::message')
# Lowongan Baru Untukmu!

Halo **{{ $name }}**, ada lowongan baru yang mungkin cocok denganmu:

@component('mail::panel')
## {{ $title }}
{{ $company }}
@endcomponent

@component('mail::table')
| Detail | Info |
| :----- | :--- |
@foreach($rows as $row)
| {{ $row['label'] }} | {{ $row['value'] }} |
@endforeach
@endcomponent

@if(!empty($benefits))
**Benefit:** {{ $benefits }}
@endif

@if(!empty($description))
{{ $description }}
@endif

@component('mail::button', ['url' => $url])
Lihat & Lamar Sekarang
@endcomponent

Lowongan populer biasanya cepat terisi — jangan sampai kelewatan!

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
