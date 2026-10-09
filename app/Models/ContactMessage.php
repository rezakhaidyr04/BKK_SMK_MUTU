<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ContactMessage extends Model
{
    use HasFactory;

    /**
     * status/replied_at hanya admin (via Admin\ContactController).
     * ContactController@store memaksa status=baru + user_id=auth()->id().
     */
    protected $fillable = [
        'user_id',
        'name',
        'email',
        'subject',
        'message',
        'status',
        'replied_at',
    ];

    protected $casts = [
        'replied_at' => 'datetime',
    ];

    public const STATUSES = ['baru', 'dibaca', 'dibalas'];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function scopeBaru($query)
    {
        return $query->where('status', 'baru');
    }

    public function isBaru(): bool
    {
        return $this->status === 'baru';
    }
}
