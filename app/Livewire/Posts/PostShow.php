<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use App\Models\Comment;
use Livewire\Component;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Lazy;
use Illuminate\Support\Facades\Cache;

#[Lazy]
class PostShow extends Component
{
    public Post $post;
    public bool $commentsLoaded = false;
    public int $commentsPerPage = 10;
    public int $currentCommentPage = 1;

    public function mount(Post $post)
    {
        $this->post = $post;
        
        // Load only the essential post data initially
        $this->post->loadMissing(['user:id,name']);
    }

    /**
     * Load comments lazily when requested.
     */
    public function loadComments()
    {
        if (!$this->commentsLoaded) {
            $this->commentsLoaded = true;
            $this->loadCommentsData();
        }
    }

    /**
     * Load more comments for pagination.
     */
    public function loadMoreComments()
    {
        $this->currentCommentPage++;
        $this->loadCommentsData();
    }

    /**
     * Load comments data with caching.
     */
    protected function loadCommentsData()
    {
        $cacheKey = "post_{$this->post->id}_comments_page_{$this->currentCommentPage}";
        
        $comments = Cache::remember($cacheKey, 600, function () {
            return $this->post->comments()
                ->with([
                    'user:id,name',
                    'replies' => function ($query) {
                        $query->with('user:id,name')
                              ->orderBy('created_at', 'asc')
                              ->limit(5); // Limit initial replies shown
                    }
                ])
                ->orderBy('created_at', 'asc')
                ->skip(($this->currentCommentPage - 1) * $this->commentsPerPage)
                ->take($this->commentsPerPage)
                ->get();
        });

        // If this is the first page, replace the collection
        if ($this->currentCommentPage === 1) {
            $this->post->setRelation('comments', $comments);
        } else {
            // Merge with existing comments for pagination
            $existingComments = $this->post->comments;
            $this->post->setRelation('comments', $existingComments->merge($comments));
        }
    }

    /**
     * Get post statistics with caching.
     */
    #[Computed]
    public function postStats(): array
    {
        $cacheKey = "post_{$this->post->id}_stats";
        
        return Cache::remember($cacheKey, 300, function () {
            return [
                'total_comments' => $this->post->allComments()->count(),
                'total_replies' => $this->post->allComments()->whereNotNull('parent_comment_id')->count(),
                'latest_comment_date' => $this->post->allComments()->latest()->value('created_at'),
            ];
        });
    }

    /**
     * Check if there are more comments to load.
     */
    #[Computed]
    public function hasMoreComments(): bool
    {
        $totalComments = $this->postStats['total_comments'];
        $loadedComments = $this->currentCommentPage * $this->commentsPerPage;
        
        return $loadedComments < $totalComments;
    }

    /**
     * Refresh comments and clear cache.
     */
    public function refreshComments()
    {
        // Clear related caches
        $this->clearPostCaches();
        
        // Reset pagination
        $this->currentCommentPage = 1;
        $this->commentsLoaded = false;
        
        // Reload comments
        $this->loadComments();
        
        $this->dispatch('comments-refreshed');
    }

    /**
     * Clear all caches related to this post.
     */
    protected function clearPostCaches()
    {
        $cacheKeys = [
            "post_{$this->post->id}_stats",
        ];

        // Clear paginated comment caches
        for ($page = 1; $page <= $this->currentCommentPage; $page++) {
            $cacheKeys[] = "post_{$this->post->id}_comments_page_{$page}";
        }

        foreach ($cacheKeys as $key) {
            Cache::forget($key);
        }
    }

    /**
     * Load specific comment replies on demand.
     */
    public function loadCommentReplies(int $commentId)
    {
        $cacheKey = "comment_{$commentId}_replies";
        
        $replies = Cache::remember($cacheKey, 300, function () use ($commentId) {
            return Comment::where('parent_comment_id', $commentId)
                ->with('user:id,name')
                ->orderBy('created_at', 'asc')
                ->get();
        });

        $this->dispatch('comment-replies-loaded', [
            'commentId' => $commentId,
            'replies' => $replies->toArray()
        ]);
    }

    /**
     * Increment view count (can be cached/queued for performance).
     */
    public function incrementViewCount()
    {
        // This could be queued for better performance
        $this->post->increment('view_count');
        
        // Clear stats cache since view count changed
        Cache::forget("post_{$this->post->id}_stats");
    }

    /**
     * Render the component.
     */
    public function render()
    {
        return view('livewire.posts.post-show', [
            'post' => $this->post,
            'postStats' => $this->postStats,
            'hasMoreComments' => $this->hasMoreComments,
            'commentsLoaded' => $this->commentsLoaded,
        ]);
    }

    /**
     * Placeholder method for lazy loading.
     */
    public function placeholder()
    {
        return view('livewire.posts.post-show-placeholder');
    }
}
