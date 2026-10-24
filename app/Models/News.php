<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class News extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'slug',
        'category',
        'content',
        'thumbnail',
        'is_published',
        'published_at',
    ];

    protected $casts = [
        'is_published' => 'boolean',
        'published_at' => 'datetime',
    ];

    protected static function booted(): void
    {
        static::creating(function ($news) {
            if (blank($news->slug)) {
                $news->slug = Str::slug($news->title);
            }
        });
    }

    public function scopePublished($query)
    {
        return $query->where('is_published', true)
            ->where(function ($q) {
                $q->whereNull('published_at')
                    ->orWhere('published_at', '<=', now());
            });
    }

    public function scopeCategory($query, string $category)
    {
        return $query->where('category', $category);
    }

    /**
     * URL publik memakai slug yang ramah SEO (/news/{slug}).
     * Tanpa ini, route('news.show', $model) menghasilkan /news/{id}.
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

    public function getExcerptAttribute(): string
    {
        return Str::limit(strip_tags($this->content), 160);
    }

    public function getUrlAttribute(): string
    {
        return route('news.show', $this->slug);
    }
}
