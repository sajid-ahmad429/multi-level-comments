<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use Livewire\Attributes\Computed;
use Livewire\Attributes\Url;
use App\Models\Post;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Validator;
use Illuminate\Contracts\Pagination\LengthAwarePaginator;

/**
 * Class PostIndex
 *
 * This Livewire component handles displaying, sorting, and paginating posts with optimizations.
 */
class PostIndex extends Component
{
    use WithPagination;

    #[Url(as: 'page')]
    public $currentPage = 1;

    #[Url(as: 'per_page')]
    public $perPage = 12;

    #[Url(as: 'sort')]
    public $sortField = 'created_at';

    #[Url(as: 'direction')]
    public $sortDirection = 'desc';

    #[Url(as: 'search')]
    public $searchTerm = '';

    // Cache key for posts
    protected string $cacheKey;

    public function mount()
    {
        $this->updateCacheKey();
    }

    /**
     * Reset pagination when searchTerm is updated.
     */
    public function updatedSearchTerm()
    {
        $this->resetPage();
        $this->updateCacheKey();
    }

    /**
     * Update cache key when sort parameters change.
     */
    public function updatedSortField()
    {
        $this->updateCacheKey();
    }

    public function updatedSortDirection()
    {
        $this->updateCacheKey();
    }

    public function updatedPerPage()
    {
        $this->resetPage();
        $this->updateCacheKey();
    }

    /**
     * Update the cache key based on current parameters.
     */
    protected function updateCacheKey(): void
    {
        $this->cacheKey = sprintf(
            'posts_index_%s_%s_%s_%d_%d',
            md5($this->searchTerm),
            $this->sortField,
            $this->sortDirection,
            $this->perPage,
            $this->currentPage
        );
    }

    /**
     * Handle sorting logic with optimization.
     *
     * @param string $field
     */
    public function sortBy(string $field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }

        $this->resetPage();
        $this->updateCacheKey();
    }

    /**
     * Get cached posts with computed property for better performance.
     */
    #[Computed]
    public function posts(): LengthAwarePaginator
    {
        return Cache::remember($this->cacheKey, 300, function () {
            return $this->buildPostsQuery()->paginate($this->perPage);
        });
    }

    /**
     * Build the optimized posts query.
     */
    protected function buildPostsQuery()
    {
        $query = Post::query()
            ->with(['user:id,name', 'comments' => function ($query) {
                $query->limit(3); // Only load first 3 comments for preview
            }])
            ->withCount('allComments as comment_count');

        // Apply search filter efficiently
        if (!empty($this->searchTerm)) {
            $query->search($this->searchTerm);
        }

        // Apply sorting with validation
        $allowedSortFields = ['title', 'created_at', 'updated_at', 'comment_count'];
        if (in_array($this->sortField, $allowedSortFields)) {
            if ($this->sortField === 'comment_count') {
                $query->orderBy('comment_count', $this->sortDirection);
            } else {
                $query->orderBy($this->sortField, $this->sortDirection);
            }
        } else {
            $query->recent(); // Default to recent posts
        }

        return $query;
    }

    /**
     * Clear the posts cache.
     */
    public function clearCache()
    {
        Cache::forget($this->cacheKey);
        $this->dispatch('posts-cache-cleared');
    }

    /**
     * Get available sort options for the view.
     */
    public function getSortOptionsProperty(): array
    {
        return [
            'created_at' => 'Date Created',
            'updated_at' => 'Date Updated',
            'title' => 'Title',
            'comment_count' => 'Comment Count'
        ];
    }

    /**
     * Get available per-page options.
     */
    public function getPerPageOptionsProperty(): array
    {
        return [6, 12, 24, 48];
    }

    /**
     * Render the component with optimized posts loading.
     */
    public function render()
    {
        // Validate search term
        $validator = Validator::make(['searchTerm' => $this->searchTerm], [
            'searchTerm' => 'nullable|string|max:255',
        ]);

        if ($validator->fails()) {
            return view('livewire.posts.post-index', [
                'posts' => collect(),
                'error' => 'Invalid search term'
            ]);
        }

        return view('livewire.posts.post-index', [
            'posts' => $this->posts,
            'sortOptions' => $this->sortOptions,
            'perPageOptions' => $this->perPageOptions,
        ]);
    }

    /**
     * Placeholder method for loading states.
     */
    public function placeholder()
    {
        return view('livewire.posts.post-index-placeholder');
    }
}