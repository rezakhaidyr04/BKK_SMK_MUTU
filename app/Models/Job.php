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
        'job_type',
        'salary_min',
        'salary_max',
        'description',
        'qualifications',
        'benefits',
        'deadline',
        'status',
    ];

    protected $casts = [
        'deadline' => 'date',
    ];

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function company()
    {
        return $this->belongsTo(Company::class);
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
