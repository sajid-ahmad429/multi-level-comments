<?php

namespace App\Livewire\Posts;

use App\Models\Post;
use Livewire\Component;

class PostShow extends Component
{
    public Post $post;

    public function mount(Post $post)
    {
        $this->post = $post;

        // Load comments with their replies and parent comments
        $this->post->load([
            'comments' => function ($query) {
                $query->with('replies')->orderBy('created_at', 'asc');
            },
        ]);
    }

    public function render()
    {
        // Ensure comments are loaded with their replies
        $this->post->comments->each(function ($comment) {
            $comment->load('replies');
        });
        // Return the view for the post show component
        // This view should display the post and its comments
        return view('livewire.posts.post-show', [
            'post' => $this->post,
            'comments' => $this->post->comments,
        ]);
    }

    public function refreshComments()
    {
        // Reload comments to ensure the latest data is displayed
        $this->post->load('comments.replies');
    }
}
