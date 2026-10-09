<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TracerStudy extends Model
{
    use HasFactory;

    /**
     * P0: user_id diisi server-side dari auth()->id(), JANGAN dari request.
     */
    protected $fillable = [
        'user_id',
        'status_kerja',
        'company_name',
        'position',
        'salary_range',
        'is_relevant',
        'tahun_lulus',
        'jurusan',
        'no_wa',
        'filled_at',
    ];

    protected $casts = [
        'is_relevant' => 'boolean',
        'tahun_lulus' => 'integer',
        'filled_at' => 'datetime',
    ];

    public const STATUSES = ['bekerja', 'kuliah', 'wirausaha', 'menganggur'];

    public const SALARY_RANGES = ['<3jt', '3-5jt', '5-10jt', '>10jt', 'rahasia'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeFilled($query)
    {
        return $query->whereNotNull('filled_at');
    }

    public function scopeByStatus($query, string $status)
    {
        return $query->where('status_kerja', $status);
    }

    public function isWorking(): bool
    {
        return $this->status_kerja === 'bekerja';
    }
}
