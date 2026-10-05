<?php

namespace App\Models;

use App\Models\Company;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Job extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * P0 H-14: company_id/status/company_name diisi server-side dari auth company.
     * Request 'status' dari client diabaikan (Company\JobController memaksa active
     * setelah cek verified; admin via role:admin saja).
     */
    protected $fillable = [
        'company_id',
        'company_name',
        'title',
        'position',
        'location',
        'province',
        'city',
        'district',
        'job_type',
        'salary_min',
        'salary_max',
        'description',
        'qualifications',
        'education',
        'experience',
        'gender',
        'age_range',
        'work_hours',
        'benefits',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    /**
     * Benefit otomatis dinormalisasi jadi daftar bernomor saat disimpan:
     * dipisah per koma / titik-koma / baris baru (sesuai petunjuk form),
     * lalu disusun "1. ... 2. ..." seperti gaya kualifikasi.
     * Satu item dibiarkan polos tanpa nomor.
     */
    public function setBenefitsAttribute($value): void
    {
        if ($value === null || trim((string) $value) === '') {
            $this->attributes['benefits'] = $value;

            return;
        }

        $items = collect(preg_split('/[\r\n,;]+/', (string) $value))
            ->map(fn ($b) => trim((string) $b))
            ->filter()
            // Buang penomoran/bullet lama agar tidak dobel ("1. 1. ...").
            // Huruf tidak diubah — kata-kata milik perusahaan dibiarkan apa adanya.
            ->map(fn ($b) => preg_replace('/^(\d+[.)\-:]|[-•*])\s*/u', '', $b))
            ->values();

        $this->attributes['benefits'] = $items->count() > 1
            ? $items->map(fn ($b, $i) => ($i + 1) . '. ' . $b)->implode("\n")
            : $items->first();
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
    }

    /**
     * Label lokasi nasional: "Kabupaten X, Provinsi Y" bila struktur
     * province/city tersedia, fallback ke kolom location legacy.
     */
    public function locationLabel(): string
    {
        if ($this->city && $this->province) {
            $label = $this->city.', '.$this->province;

            return $this->district ? $this->district.', '.$label : $label;
        }

        return $this->location ?? '-';
    }

    /**
     * P2.6: query-level "active" — mirror pola listing publik
     * (JobController@index, HomeController): status active DAN
     * deadline belum lewat.
     */
    public function scopeActive($query)
    {
        return $query->where('status', 'active')->where('deadline', '>=', now());
    }

    /**
     * P2.6: query-level "expired" — terjemahan query dari guard
     * instance existing ($job->deadline && $job->deadline->lt(...)).
     * Job tanpa deadline TIDAK dianggap expired.
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('deadline')->where('deadline', '<', now()->startOfDay());
    }

    /**
     * P2.6: instance-level "active" — mirror guard show()/apply()
     * (JobController@show, JobController@apply): status active DAN
     * tidak expired.
     */
    public function isActive(): bool
    {
        return $this->status === 'active' && ! $this->isExpired();
    }

    /**
     * P2.6: instance-level "expired" — mirror guard existing:
     * punya deadline DAN deadline < awal hari ini. Job tanpa
     * deadline TIDAK dianggap expired.
     */
    public function isExpired(): bool
    {
        return ! is_null($this->deadline) && $this->deadline->lt(now()->startOfDay());
    }
}
