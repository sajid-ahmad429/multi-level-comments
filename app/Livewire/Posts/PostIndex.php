<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Post;

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

    /**
     * Handle sorting logic.
     *
     * @param string $field
     */
    public function sortBy($field)
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
        $posts = Post::orderBy($this->sortField, $this->sortDirection)
            ->paginate($this->perPage);

        return view('livewire.posts.post-index', compact('posts'));
    }
}