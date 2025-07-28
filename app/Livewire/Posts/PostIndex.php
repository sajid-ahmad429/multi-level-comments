<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;
use Illuminate\Support\Facades\Validator;

/**
 * Class PostIndex
 *
 * This Livewire component handles displaying, sorting, and paginating posts.
 */
class PostIndex extends Component
{
    use WithPagination;

    public $perPage = 9;
    public $sortField = 'created_at';
    public $sortDirection = 'desc';
    public $searchTerm = '';

    /**
     * Reset pagination when searchTerm is updated.
     */
    public function updatedSearchTerm()
    {
        $this->resetPage();
    }

    /**
     * Handle sorting logic.
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
    }

    /**
     * Render the component with sorted and paginated posts.
     */
    public function render()
    {
        $validator = Validator::make(['searchTerm' => $this->searchTerm], [
            'searchTerm' => 'string|max:255',
        ]);

        if ($validator->fails()) {
            // Handle validation error
            return view('livewire.posts.post-index', ['error' => 'Invalid search term']);
        }

        $searchTerm = '%' . $this->searchTerm . '%';

        $posts = Post::query()
            ->when($this->searchTerm, function ($query) use ($searchTerm) {
                $query->where('title', 'like', $searchTerm)
                ->orWhere('content', 'like', $searchTerm);
            })
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.posts.post-index', compact('posts'));
    }
}