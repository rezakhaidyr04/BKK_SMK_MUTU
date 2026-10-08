<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'title',
        'slug',
        'category',
        'content',
        'thumbnail',
        'is_published',
    ];

    protected $casts = [
        'is_published' => 'boolean',
    ];

    public function author()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * URL publik memakai slug yang ramah SEO (/news/{slug}).
     * Tanpa ini, route('news.show', $model) menghasilkan /news/{id}
     * tapi link admin memakai slug sehingga selalu 404.
     */
    public function getRouteKeyName(): string
    {
        return 'slug';
    }

    /**
     * Tetap dukung URL lama berbasis ID (/news/123) agar tidak 404
     * untuk link yang sudah terlanjur tersebar / terindeks.
     */
    public function resolveRouteBinding($value, $field = null)
    {
        if ($field) {
            return $this->where($field, $value)->firstOrFail();
        }

        return $this->where('slug', $value)
            ->orWhere('id', $value)
            ->firstOrFail();
    }
}
