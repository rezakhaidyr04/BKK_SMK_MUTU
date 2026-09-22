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
}
