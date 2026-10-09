@component('mail::message')
# Job Alert Mingguan 🔔

Halo **{{ $name }}**, ada **{{ $jobs->count() }} lowongan cocok** dengan langganan Anda ({{ $criteria }}):

@foreach($jobs as $job)
@component('mail::panel')
## {{ $job->title }}
{{ $job->company_name ?? 'Perusahaan' }} · {{ $job->location ?? '-' }} · {{ \App\Support\Label::jobType($job->job_type) }}
@endcomponent

@component('mail::button', ['url' => route('jobs.show', $job)])
Lihat {{ \Illuminate\Support\Str::limit($job->title, 30) }}
@endcomponent
@endforeach

@component('mail::button', ['url' => $url, 'color' => 'secondary'])
Kelola Langganan Saya
@endcomponent

Tidak tertarik lagi? Jeda atau hapus langganan dari halaman di atas.

Terima kasih,<br>
**Tim {{ config('app.name') }}**
@endcomponent
