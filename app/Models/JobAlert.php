<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Collection;

class JobAlert extends Model
{
    use HasFactory;

    /**
     * user_id/is_active/last_sent_at diisi server-side (controller/command).
     * Request hanya boleh mengirim kriteria (keyword/job_type/wilayah).
     */
    protected $fillable = [
        'user_id',
        'keyword',
        'job_type',
        'province',
        'city',
        'district',
        'is_active',
        'last_sent_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_sent_at' => 'datetime',
    ];

    public const MAX_PER_USER = 5;

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', true);
    }

    public function hasCriteria(): bool
    {
        return $this->keyword || $this->job_type || $this->province || $this->city || $this->district;
    }

    public function criteriaLabel(): string
    {
        $parts = array_filter([
            $this->keyword ? "\"{$this->keyword}\"" : null,
            $this->job_type ? \App\Support\Label::jobType($this->job_type) : null,
            $this->district,
            $this->city,
            $this->province,
        ]);

        return $parts ? implode(' · ', $parts) : 'Semua lowongan';
    }

    /**
     * Lowongan cocok: aktif + dibuat sejak $since, semantik filter
     * IDENTIK dengan JobController@index (keyword, job_type, fallback
     * location untuk baris legacy province/city/district NULL).
     */
    public function matchingJobs(\DateTimeInterface $since): Collection
    {
        $query = Job::with('company')->active()->where('created_at', '>=', $since);

        if ($this->keyword) {
            $keyword = $this->keyword;
            $query->where(function ($q) use ($keyword) {
                $q->where('title', 'like', "%{$keyword}%")
                    ->orWhere('position', 'like', "%{$keyword}%")
                    ->orWhere('description', 'like', "%{$keyword}%")
                    ->orWhere('location', 'like', "%{$keyword}%")
                    ->orWhere('company_name', 'like', "%{$keyword}%");
            });
        }

        if ($this->job_type) {
            $query->where('job_type', $this->job_type);
        }

        if ($this->province) {
            $province = $this->province;
            $query->where(function ($q) use ($province) {
                $q->where('province', $province)
                    ->orWhere(fn ($qq) => $qq->whereNull('province')->where('location', 'like', "%{$province}%"));
            });
        }

        if ($this->city) {
            $city = $this->city;
            $short = \App\Support\IndonesiaRegions::shortName($city);
            $query->where(function ($q) use ($city, $short) {
                $q->where('city', $city)
                    ->orWhere(fn ($qq) => $qq->whereNull('city')->where(function ($qqq) use ($city, $short) {
                        $qqq->where('location', 'like', "%{$city}%");
                        if ($short !== $city) {
                            $qqq->orWhere('location', 'like', "%{$short}%");
                        }
                    }));
            });
        }

        if ($this->district) {
            $district = $this->district;
            $query->where(function ($q) use ($district) {
                $q->where('district', $district)
                    ->orWhere(fn ($qq) => $qq->whereNull('district')->where('location', 'like', "%{$district}%"));
            });
        }

        return $query->latest()->take(10)->get();
    }
}
