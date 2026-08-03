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

    public $title = '';
    public $content = '';
    public $editingPostId = null;

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

    public function createPost()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:3',
        ]);

        Post::create($validated);

        session()->flash('success', 'Post created successfully.');
        $this->resetForm();
        $this->resetPage();
    }

    public function editPost(int $postId)
    {
        $post = Post::findOrFail($postId);

        $this->editingPostId = $post->id;
        $this->title = $post->title;
        $this->content = $post->content;
    }

    public function updatePost()
    {
        $validated = $this->validate([
            'title' => 'required|string|max:255',
            'content' => 'required|string|min:3',
        ]);

        $post = Post::findOrFail($this->editingPostId);
        $post->update($validated);

        session()->flash('success', 'Post updated successfully.');
        $this->resetForm();
        $this->resetPage();
    }

    // public function deletePost(int $postId)
    // {
    //     $post = Post::findOrFail($postId);
    //     $post->delete();

    //     session()->flash('success', 'Post deleted successfully.');
    //     $this->resetPage();
    // }

    public function deletePost(int $postId)
{
    $post = Post::findOrFail($postId);
    $post->delete();

    // SweetAlert2 success popup trigger karne ke liye dispatch add karein
    $this->dispatch('post-deleted');

    session()->flash('success', 'Post deleted successfully.');
    $this->resetPage();
}

    public function cancelEdit()
    {
        $this->resetForm();
    }

    public function resetForm()
    {
        $this->title = '';
        $this->content = '';
        $this->editingPostId = null;
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