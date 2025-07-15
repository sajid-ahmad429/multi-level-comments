<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'content'];

    /**
     * Get the comments for the post.
     */
    public function comments(): HasMany 
    {
        return $this->hasMany(Comment::class)->whereNull('parent_comment_id')
            ->with('replies');
    }
}
