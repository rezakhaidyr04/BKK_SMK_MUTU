<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Conversation extends Model
{
    use HasFactory;

    /**
     * P1 H-07: allowlist eksplisit. Tabel conversations hanya id+timestamps,
     * tidak ada atribut mass-assignable. $fillable=[] mengizinkan
     * Conversation::create() kosong (dipakai MessageController, MessageFactory,
     * tests) tanpa membuka field internal. JANGAN ganti ke guarded=[].
     */
    protected $fillable = [];

    public function users()
    {
        return $this->belongsToMany(User::class, 'conversation_user')->withTimestamps();
    }

    public function messages()
    {
        return $this->hasMany(Message::class);
    }
}
