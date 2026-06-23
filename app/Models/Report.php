<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Report extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'type',
        'data',
        'generated_by',
    ];

    protected $casts = [
        'data' => 'array',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'generated_by');
    }
}
