<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class JobReport extends Model
{
    use HasFactory;

    /**
     * status hanya admin (via Admin\JobReportController).
     * JobReportController@store memaksa status=menunggu + user_id/job_id server-side.
     */
    protected $fillable = [
        'job_id',
        'user_id',
        'reason',
        'detail',
        'status',
    ];

    public const REASONS = ['penipuan', 'pungutan', 'info_palsu', 'diskriminasi', 'lainnya'];

    public const STATUSES = ['menunggu', 'ditindak', 'ditolak'];

    public static function reasonLabel(string $reason): string
    {
        return match ($reason) {
            'penipuan' => 'Penipuan / lowongan fiktif',
            'pungutan' => 'Meminta uang / pungutan',
            'info_palsu' => 'Informasi palsu / menyesatkan',
            'diskriminasi' => 'Diskriminasi SARA',
            default => 'Lainnya',
        };
    }

    public function job()
    {
        return $this->belongsTo(Job::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeMenunggu($query)
    {
        return $query->where('status', 'menunggu');
    }
}
