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
        'is_paid',
        'price',
        'quota',
        'payment_instructions',
    ];

    protected $casts = [
        'start_time' => 'datetime',
        'end_time' => 'datetime',
        'is_paid' => 'boolean',
        'price' => 'decimal:2',
    ];

    public function isPaid(): bool
    {
        return (bool) $this->is_paid && $this->price > 0;
    }

    public function isFull(): bool
    {
        if (!$this->quota) return false;
        return $this->registrations()->where('status', 'registered')->count() >= $this->quota;
    }

    public function formattedPrice(): string
    {
        if (!$this->isPaid()) return 'Gratis';
        return 'Rp ' . number_format($this->price, 0, ',', '.');
    }

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
