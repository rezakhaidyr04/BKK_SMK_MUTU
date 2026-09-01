<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Database\Eloquent\SoftDeletes;
use Spatie\Permission\Traits\HasRoles;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes, HasRoles;

    protected $fillable = [
        "name",
        "email",
        "password",
        "avatar",
        "phone",
        "bio",
        "role",
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
