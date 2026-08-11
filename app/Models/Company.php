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
        "email",
        "phone",
        "address",
        "tax_number",
        "npwp_path",
        "business_license_path",
        "operating_license_path",
        "mou_path",
        "mou_number",
        "mou_signed_at",
        "mou_expires_at",
        "is_verified",
        "verification_status",
        "rejection_reason",
        "reviewed_by",
        "reviewed_at",
    ];

    protected $casts = [
        "is_verified" => "boolean",
        "mou_signed_at" => "date",
        "mou_expires_at" => "date",
        "reviewed_at" => "datetime",
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function reviewer()
    {
        return $this->belongsTo(User::class, 'reviewed_by');
    }

    public function jobs()
    {
        return $this->hasMany(Job::class);
    }

    // ── Helpers ──────────────────────────────────────────────────

    public function isApproved(): bool
    {
        return $this->verification_status === 'verified';
    }

    public function hasUserAccount(): bool
    {
        return $this->user_id !== null;
    }

    public function hasMou(): bool
    {
        return (bool) $this->mou_path;
    }
}
