<?php

namespace App\Livewire\Posts;

use Livewire\Component;
use App\Models\Post;
/**
 * Class PostIndex
 *
 * This Livewire component handles the display of posts in the index view.
 */

class PostIndex extends Component
{
    /**
     * The posts to be displayed in the index.
     *
     * @var \Illuminate\Database\Eloquent\Collection
     */
    public $posts;
    /**
     * Mount the component with the latest posts.
     */
    public function mount()
    {
        // Fetch all posts, ordered by latest
        $this->posts = Post::latest()->get();
    }
    /**
     * Render the component view.
     *
     * @return \Illuminate\View\View
     */
    // This method returns the view for the post index component
    public function render()
    {
        
        return view('livewire.posts.post-index');
    }
}
