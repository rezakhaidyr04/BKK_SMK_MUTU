<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CvFile extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'file_path',
        'is_ats_friendly',
    ];

    protected $casts = [
        'is_ats_friendly' => 'boolean',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
