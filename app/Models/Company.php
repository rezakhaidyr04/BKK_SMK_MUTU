<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Company extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * P0 H-14: user_id/is_verified/verification_status/reviewed_* adalah
     * privileged — hanya admin/server-side. Company update publik memakai
     * validated allowlist tanpa field tersebut.
     */
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

    /**
     * P0 C-01: Defense-in-depth — jangan pernah serialize field sensitif
     * ke JSON API secara tidak sengaja. Public view memakai
     * CompanyPublicResource, tapi $hidden ini mencegah kebocoran
     * jika ada kode memanggil $company->toArray()/toJson() langsung.
     */
    protected $hidden = [
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
        "user_id",
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

    /**
     * P1 H-09A: verification_status adalah CANONICAL source of truth
     * (pending/verified/rejected). is_verified adalah turunan boolean
     * (true hanya jika verified) yang disinkronkan otomatis di saving().
     * Seluruh business logic WAJIB memakai isApproved(), bukan is_verified
     * langsung, agar tidak ada dual-truth.
     */
    protected static function booted(): void
    {
        static::saving(function (Company $company) {
            $company->is_verified = $company->verification_status === 'verified';
        });
    }

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
