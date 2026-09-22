<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Review extends Model
{
    use HasFactory, SoftDeletes;

    /**
     * P0 H-14: status/featured/rejection_reason hanya moderasi server-side.
     * ReviewController@store memaksa status=pending, user_id=auth()->id().
     */
    protected $fillable = [
        'user_id',
        'rating',
        'comment',
        'job_title',
        'company_name',
        'name',
        'email',
        'phone',
        'status',
        'rejection_reason',
        'featured',
    ];

    protected $casts = [
        'rating' => 'integer',
        'featured' => 'boolean',
    ];

    /**
     * Get the user that owns the review.
     */
    public function user()
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope: Get only approved reviews
     */
    public function scopeApproved($query)
    {
        return $query->where('status', 'approved');
    }

    /**
     * Scope: Get featured reviews
     */
    public function scopeFeatured($query)
    {
        return $query->where('featured', true);
    }

    /**
     * Get average rating
     */
    public static function getAverageRating()
    {
        return self::approved()->avg('rating') ?? 0;
    }

    /**
     * Get total approved reviews
     */
    public static function getTotalReviews()
    {
        return self::approved()->count();
    }

    /**
     * Get satisfaction percentage
     */
    public static function getSatisfactionPercentage()
    {
        $approved = self::approved()->count();
        if ($approved === 0) return 0;

        $satisfied = self::approved()->whereIn('rating', [4, 5])->count();
        return round(($satisfied / $approved) * 100);
    }

    /**
     * Get rating distribution
     */
    public static function getRatingDistribution()
    {
        return self::approved()
            ->selectRaw('rating, COUNT(*) as count')
            ->groupBy('rating')
            ->orderBy('rating', 'desc')
            ->pluck('count', 'rating');
    }

    /**
     * Get display name
     */
    public function getDisplayNameAttribute()
    {
        // P0 H-06: null-safe — user bisa null (anonim / soft-deleted / set null).
        return $this->name ?? $this->user?->name ?? 'Anonymous';
    }
}
