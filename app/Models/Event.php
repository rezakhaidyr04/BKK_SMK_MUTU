<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Event extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'description',
        'start_time',
        'end_time',
        'location',
        'poster',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
    ];

    public function registrations()
    {
        return $this->hasMany(EventRegistration::class);
    }

    public function registeredUsers()
    {
        return $this->belongsToMany(User::class, 'event_registrations')
            ->withPivot('status', 'notes', 'registered_at')
            ->withTimestamps();
    }

    public function isRegisteredBy($userId): bool
    {
        return $this->registrations()->where('user_id', $userId)->exists();
    }
}
