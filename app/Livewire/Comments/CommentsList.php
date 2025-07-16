<?php

namespace App\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;

class CommentsList extends Component
{
    public Post $post;
    public $comments = [];

    protected $listeners = ['commentAdded' => 'refreshComments'];

    public function mount(Post $post)
    {
        $this->post = $post;
        $this->loadComments();
    }

    public function loadComments()
    {
        // Ensure comments are loaded with their replies
        $this->post->comments->each(function ($comment) {
            $comment->load('replies');
        });

        $this->comments = $this->post->comments;
    }

    public function refreshComments()
    {
        // Reload comments to ensure the latest data is displayed
        $this->loadComments();
        $this->emit('commentsRefreshed');
    }


    public function render()
    {
        // Return the view for the comments list component
        // This view should display the comments and their replies
        $this->loadComments();
        return view('livewire.comments.comments-list', [
            'comments' => $this->comments,
            'post' => $this->post,
        ]);
    }
}
