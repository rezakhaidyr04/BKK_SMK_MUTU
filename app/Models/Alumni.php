<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Alumni extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'alumni';

    protected $fillable = [
        'user_id',
        'graduation_year',
        'employment_status',
        'current_company',
        'experience',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
