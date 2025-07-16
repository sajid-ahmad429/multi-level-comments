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
        'replyAdded' => 'onReplyAdded'
    ];

    public function mount(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
        $this->loadReplies();
    }

    public function onReplyAdded($parentCommentId)
    {
        if ($this->comment->id === $parentCommentId) {
            $this->loadReplies();
            $this->showReplies = true;
            $this->showReplyForm = false;
        }
    }

    public function handleReplySubmitted()
    {
        $this->loadReplies();
        $this->showReplies = true;
        $this->showReplyForm = false;
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
        $this->replies = $this->comment->replies()
            ->with('user')
            ->with('replies')
            ->latest()
            ->get();
    }

    public function render()
    {
        return view('livewire.comments.comment-item', [
            'comment' => $this->comment,
            'replies' => $this->replies,
            'author' => $this->comment->user ?? null,
        ]);
    }
}
