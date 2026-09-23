<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EventRegistration extends Model
{
    use HasFactory;

    protected $fillable = [
        'event_id',
        'user_id',
        'status',
        'notes',
        'registered_at',
        'payment_status',
        'payment_proof',
        'paid_at',
    ];

    protected $casts = [
        'registered_at' => 'datetime',
        'paid_at' => 'datetime',
    ];

    public function isPaidVerified(): bool
    {
        return $this->payment_status === 'verified';
    }

    public function event()
    {
        return $this->belongsTo(Event::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
