<?php

namespace App\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;
/**
 * Class CommentItem
 * Represents a single comment item in the Livewire component.
 */

class CommentItem extends Component
{
    public Comment $comment;
    public Post $post;
    public $replies = [];
    public $showReplies = false;

    protected $listeners = ['refreshReplies' => 'loadReplies'];
    /**
     * Toggle the visibility of replies.
     */
    public function toggleReplies()
    {
        $this->showReplies = !$this->showReplies;
        if ($this->showReplies) {
            $this->loadReplies();
        }
    }

    /**
     * Load replies for the comment.
     * This method fetches replies and their nested replies recursively.
     */
    public function loadReplies()
    {
        $this->replies = $this->comment->replies()->with('replies')->get();
    }

    /**
     * Refresh the replies for the comment.
     * This method is called to refresh the replies when a new reply is added.
     */
    public function refreshReplies()
    {
        $this->loadReplies();
        $this->emit('repliesRefreshed', $this->comment->id);
    }
    /**
     * Get the depth of the comment.
     * This method returns the depth of the comment for display purposes.
     */
    public function getDepthAttribute()
    {
        return $this->comment->calculateDepth();
    }
    /**
     * Get the formatted content of the comment.
     * This method returns the content of the comment formatted for display.
     */
    public function getFormattedContentAttribute()
    {
        return nl2br(e($this->comment->content));
    }
    /**
     * Get the author of the comment.
     * This method returns the user who authored the comment.
     */
    public function getAuthorAttribute()
    {
        return $this->comment->user ?? null;
    }
    /**
     * Mount the component with the comment and post.
     * This method initializes the component with the provided comment and post.
     *
     * @param Comment $comment
     * @param Post $post
     */
    public function mount(Comment $comment, Post $post)
    {
        $this->comment = $comment;
        $this->post = $post;
        $this->loadReplies();
    }
    public function render()
    {
        // Return the view for the comment item component
        // This view should display the comment and its replies
        $this->loadReplies();
        return view('livewire.comments.comment-item', [
            'comment' => $this->comment,
            'post' => $this->post,
            'replies' => $this->replies,
            'showReplies' => $this->showReplies,
            'depth' => $this->getDepthAttribute(),
            'formattedContent' => $this->getFormattedContentAttribute(),
            'author' => $this->getAuthorAttribute(),
        ]);
    }
}
