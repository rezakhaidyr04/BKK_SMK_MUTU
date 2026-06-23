<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        "user_id",
        "name",
        "industry",
        "description",
        "logo",
        "website",
        "address",
        "is_verified",
        "verification_status",
        "rejection_reason",
    ];

    protected $casts = [
        "is_verified" => "boolean",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }
}
