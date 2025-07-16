<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use LogicException;

class Comment extends Model
{
    use HasFactory;

    protected $fillable = [
        'content',
        'post_id',
        'parent_comment_id',
        'depth',
    ];

    public const MAX_DEPTH = 3;

    /**
     * Get the post that owns the comment.
     */
    public function post(): BelongsTo
    {
        return $this->belongsTo(Post::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    /**
     * Get the parent comment of this comment.
     */
    public function parent(): BelongsTo
    {
        return $this->belongsTo(Comment::class, 'parent_comment_id');
    }

    /**
     * Get the replies to this comment.
     */
    public function replies(): HasMany
    {
        return $this->hasMany(Comment::class, 'parent_comment_id')
            ->orderBy('created_at', 'asc');
    }

    /**
     * Calculate the depth of this comment based on parent.
     * Returns integer (1-based depth).
     */
    public function calculateDepth(): int
    {
        if ($this->parent_comment_id === null) {
            return 1; // Root comments are depth 1
        }

        $parent = $this->relationLoaded('parent')
            ? $this->parent
            : $this->parent()->first();

        return $parent ? $parent->depth + 1 : 1;
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
}