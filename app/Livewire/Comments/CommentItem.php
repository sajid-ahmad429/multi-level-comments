<?php

namespace App\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;

class CommentItem extends Component
{
    public Comment $comment;
    public Post $post;
    public $replies = [];
    public $showReplies = false;
    public $showReplyForm = false;

    protected $listeners = [
        'refreshReplies' => 'loadReplies',
        'replyAdded' => 'handleReplyAdded'
    ];

    public function mount(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
        $this->loadReplies();
    }

    public function toggleReplies()
    {
        $this->showReplies = !$this->showReplies;
    }

    public function toggleReplyForm()
    {
        $this->showReplyForm = !$this->showReplyForm;
    }

    public function loadReplies()
    {
        $this->replies = $this->comment->replies()->with('replies')->latest()->get();
    }

    public function handleReplyAdded($parentCommentId)
    {
        if ($parentCommentId == $this->comment->id) {
            $this->loadReplies();
            $this->showReplies = true;
        }
    }

    public function render()
    {
        return view('livewire.comments.comment-item', [
            'comment' => $this->comment,
            'replies' => $this->replies,
            'showReplies' => $this->showReplies,
            'showReplyForm' => $this->showReplyForm,
            'author' => $this->comment->user ?? null,
        ]);
    }
}