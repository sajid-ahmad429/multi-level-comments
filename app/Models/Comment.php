<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Builder;
use LogicException;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'post_id',
        'user_id',
        'parent_comment_id',
        'depth',
    ];

    protected $casts = [
        'depth' => 'integer',
    ];

    // Add indexes for better query performance
    protected $indexColumns = [
        'post_id',
        'parent_comment_id',
        'user_id',
        'created_at',
        'depth'
    ];

    public const MAX_DEPTH = 3;

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    /**
     * Get the user that owns the comment.
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    /**
     * Get the parent comment of this comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    /**
     * Get the replies to this comment with optimized loading.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Get all descendants of this comment (replies and their replies).
     */
    public function descendants(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id')
            ->with('descendants');
    }

    /**
     * Scope for root comments (no parent).
     */
    public function scopeRoot(Builder $query): Builder
    {
        return $query->whereNull('parent_comment_id');
    }

    /**
     * Scope for comments by depth level.
     */
    public function scopeByDepth(Builder $query, int $depth): Builder
    {
        return $query->where('depth', $depth);
    }

    /**
     * Scope for recent comments.
     */
    public function scopeRecent(Builder $query): Builder
    {
        return $query->orderBy('created_at', 'desc');
    }

    /**
     * Calculate the depth of this comment based on parent with caching.
     * Returns integer (1-based depth).
     */
    public function calculateDepth(): int
    {
        if ($this->parent_comment_id === null) {
            return 1; // Root comments are depth 1
        }

        // Use cached parent if available
        if ($this->relationLoaded('parent') && $this->parent) {
            return $this->parent->depth + 1;
        }

        // Otherwise, query for parent depth
        $parentDepth = static::where('id', $this->parent_comment_id)
            ->value('depth');

        return $parentDepth ? $parentDepth + 1 : 1;
    }

    /**
     * Booted method to handle model events.
     */
    protected static function booted(): void
    {
        static::creating(function (Comment $comment) {
            $comment->depth = $comment->calculateDepth();

            if ($comment->depth > self::MAX_DEPTH) {
                throw new LogicException('Maximum comment depth reached.');
            }
        });

        static::updating(function (Comment $comment) {
            if ($comment->isDirty('parent_comment_id')) {
                $comment->depth = $comment->calculateDepth();

                if ($comment->depth > self::MAX_DEPTH) {
                    throw new LogicException('Maximum comment depth reached.');
                }
            }
        });
    }

    /**
     * Check if the comment can have replies.
     */
    public function canHaveReplies(): bool
    {
        return $this->depth < self::MAX_DEPTH;
    }

    /**
     * Get the total reply count for this comment.
     */
    public function getReplyCountAttribute(): int
    {
        return $this->replies()->count();
    }

    /**
     * Get the path to the comment (useful for threading).
     */
    public function getThreadPathAttribute(): string
    {
        if (!$this->parent_comment_id) {
            return (string) $this->id;
        }

        $parent = $this->parent;
        return $parent->thread_path . '.' . $this->id;
    }
}