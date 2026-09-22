<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Application extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * P0 H-14: status/interview_* hanya company pemilik (via policy) / admin.
     * JobController@apply memaksa status=submitted server-side.
     */
    protected $fillable = [
        'job_id',
        'user_id',
        'cover_letter',
        'attachment_path',
        'attachment_name',
        'attachment_mime',
        'attachment_size',
        'status',
        'interview_date',
        'interview_location',
        'interview_type',
        'interview_link',
        'interview_notes',
    ];

    protected $casts = [
        'interview_date' => 'datetime',
    ];

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * P2.7: query-level scopes — mirror where('status', ...) existing
     * (ApplicationController stats, ReportService, DashboardController).
     */
    public function scopeSubmitted($query)
    {
        return $query->where('status', 'submitted');
    }

    public function scopeUnderReview($query)
    {
        return $query->where('status', 'under_review');
    }

    public function scopeInterviewed($query)
    {
        return $query->where('status', 'interviewed');
    }

    public function scopeAccepted($query)
    {
        return $query->where('status', 'accepted');
    }

    public function scopeRejected($query)
    {
        return $query->where('status', 'rejected');
    }

    /**
     * P2.7: instance-level helpers — mirror perbandingan === existing
     * (Company\ApplicantController, ApplicationController timeline).
     */
    public function isSubmitted(): bool
    {
        return $this->status === 'submitted';
    }

    public function isUnderReview(): bool
    {
        return $this->status === 'under_review';
    }

    public function isInterviewed(): bool
    {
        return $this->status === 'interviewed';
    }

    public function isAccepted(): bool
    {
        return $this->status === 'accepted';
    }

    public function isRejected(): bool
    {
        return $this->status === 'rejected';
    }
}
