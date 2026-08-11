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
    ];

    protected $hidden = ["password", "remember_token"];

    protected $casts = [
        "email_verified_at" => "datetime",
        "password" => "hashed",
        "is_active" => "boolean",
        "must_change_password" => "boolean",
        "password_changed_at" => "datetime",
    ];

    public function student()
    {
        return $this->hasOne(Student::class);
    }

    public function alumni()
    {
        return $this->hasOne(Alumni::class);
    }

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
}
