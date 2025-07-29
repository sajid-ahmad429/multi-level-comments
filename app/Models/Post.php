<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content', 'user_id', 'published_at'];

    protected $casts = [
        'published_at' => 'datetime',
    ];

    // Add indexes for better query performance
    protected $indexColumns = [
        'title',
        'created_at',
        'published_at',
        'user_id'
    ];

    /**
     * Get the comments for the post with optimized eager loading.
     */
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class)
            ->whereNull('parent_comment_id')
            ->with(['replies' => function ($query) {
                $query->limit(10); // Limit initial replies for performance
            }])
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get all comments (including replies) for the post.
     */
    public function allComments(): HasMany
    {
        return $this->hasMany(Comment::class);
    }

    /**
     * Get the user that owns the post.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Scope for searching posts efficiently.
     */
    public function scopeSearch(Builder $query, string $term): Builder
    {
        return $query->where(function ($query) use ($term) {
            $query->where('title', 'like', "%{$term}%")
                  ->orWhere('content', 'like', "%{$term}%");
        });
    }

    /**
     * Scope for published posts.
     */
    public function scopePublished(Builder $query): Builder
    {
        return $query->whereNotNull('published_at')
                    ->where('published_at', '<=', now());
    }

    /**
     * Scope for recent posts.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Get the excerpt of the post content.
     */
    public function getExcerptAttribute(int $length = 150): string
    {
        return str_limit(strip_tags($this->content), $length);
    }

    /**
     * Get the comment count efficiently.
     */
    public function getCommentCountAttribute(): int
    {
        return $this->allComments()->count();
    }
}
