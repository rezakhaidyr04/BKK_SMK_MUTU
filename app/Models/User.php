<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable implements MustVerifyEmail
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    /**
     * P0 H-14: role/is_active/must_change_password/password_changed_at adalah
     * privileged — HANYA boleh diisi server-side / admin (role:admin).
     * Public controller (register/profile) memakai allowlist eksplisit +
     * hardcode, TIDAK boleh memakai $request->all() / validated bebas.
     */
    protected $fillable = [
        "name",
        "email",
        "password",
        "avatar",
        "phone",
        "bio",
        "role",
        "google_id",
        "is_active",
        "must_change_password",
        "password_changed_at",
        "address",
        "preferred_position",
        "education_history",
        "experience_organization",
        "birth_place",
        "birth_date",
        "gender",
        "linkedin_url",
        "portfolio_url",
        "portfolio_type",
    ];

    protected $hidden = ["password", "remember_token"];

    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed",
        "is_active" => "boolean",
        "must_change_password" => "boolean",
        "password_changed_at" => "datetime",
        "birth_date" => "date",
    ];

    public function company()
    {
        return $this->hasOne(Company::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class);
    }

    public function certificates()
    {
        return $this->hasMany(Certificate::class);
    }

    public function cvFiles()
    {
        return $this->hasMany(CvFile::class);
    }

    public function bookmarks()
    {
        return $this->belongsToMany(Job::class, "bookmarks")->withTimestamps();
    }

    public function eventRegistrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function skills()
    {
        return $this->belongsToMany(Skill::class, "user_skills")
            ->withPivot("proficiency")
            ->withTimestamps();
    }

    public function documents()
    {
        return $this->hasMany(UserDocument::class);
    }

    public function conversations()
    {
        return $this->belongsToMany(Conversation::class, 'conversation_user')->withTimestamps();
    }

    public function tracerStudy()
    {
        return $this->hasOne(TracerStudy::class);
    }

    /**
     * Sinkron keahlian dari array nama (dipakai ProfileController +
     * CareerController — satu implementasi agar aturan identik).
     * P0 H-03: max 20, tiap item max 50, non-string/kosong diabaikan.
     */
    public function syncSkillsFromNames(mixed $submittedSkills): void
    {
        if (! is_array($submittedSkills)) {
            $submittedSkills = [];
        }
        $submittedSkills = array_slice($submittedSkills, 0, 20);
        $skillIds = [];
        foreach ($submittedSkills as $skillName) {
            if (! is_string($skillName)) {
                continue;
            }
            $skillName = trim($skillName);
            if ($skillName === '' || strlen($skillName) > 50) {
                continue;
            }
            $skill = Skill::firstOrCreate(['name' => $skillName]);
            $skillIds[$skill->id] = ['proficiency' => 3];
        }
        $this->skills()->sync($skillIds);
    }

    /**
     * Simpan data karier (sumber tunggal untuk form profil, form CV,
     * dan gabungan CV-build). Normalisasi newline + fill + save + skills.
     */
    public function updateCareer(array $validated, mixed $skillsInput): void
    {
        foreach (["bio", "education_history", "experience_organization"] as $multilineField) {
            if (! empty($validated[$multilineField]) && is_string($validated[$multilineField])) {
                $validated[$multilineField] = str_replace(['\\r\\n', '\\n', '\\r'], "\n", $validated[$multilineField]);
            }
        }

        foreach (["preferred_position", "bio", "education_history", "experience_organization", "linkedin_url", "portfolio_url", "portfolio_type"] as $field) {
            $this->$field = $validated[$field] ?? null;
        }
        $this->save();

        $this->syncSkillsFromNames($skillsInput);
    }

    /**
     * Pakai template email branded BKKMu (bukan bawaan Laravel).
     */
    public function sendEmailVerificationNotification()
    {
        $this->notify(new \App\Notifications\VerifyEmailAddress);
    }

    /**
     * Pakai template email branded BKKMu (bukan bawaan Laravel).
     */
    public function sendPasswordResetNotification($token)
    {
        $this->notify(new \App\Notifications\ResetAccountPassword($token));
    }

    public function isUmum(): bool
    {
        return $this->role === 'umum';
    }

    public function isCompany(): bool
    {
        return $this->role === 'company';
    }

    public function isAdmin(): bool
    {
        return $this->role === 'admin';
    }
}
