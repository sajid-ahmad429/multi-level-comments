<?php

namespace App\Livewire\Comments;

use Livewire\Component;
use App\Models\Comment;
use App\Models\Post;
use Exception;

class CommentForm extends Component
{
    public $postId;
    public $content = '';
    public $parentCommentId = null;
    public $depth = 1;
    public $maxDepth = Comment::MAX_DEPTH;
    public $replyingTo = null; 

    protected $rules = [
        'content' => 'required|string|max:1000',
    ];

    public function mount($postId, $parentCommentId = null)
    {
        $this->postId = $postId;
        $this->parentCommentId = $parentCommentId;

        if ($parentCommentId) {
            $parentComment = Comment::find($parentCommentId);
            if ($parentComment) {
                $this->depth = $parentComment->depth + 1;
                $this->replyingTo = $parentComment->content;
            }
        }
    }

    public function submit()
    {
        if ($this->depth > $this->maxDepth) {
            session()->flash('error', 'Maximum comment depth exceeded');
            return;
        }

        if (!$this->postId) {
            session()->flash('error', 'Post not found');
            return;
        }

        // Limit replies to 3 per comment
        if ($this->parentCommentId) {
            $parentComment = Comment::find($this->parentCommentId);
            if ($parentComment && $parentComment->replies()->count() >= 3) {
                session()->flash('error', 'Maximum 3 replies allowed per comment.');
                return;
            }
        }

        // Validate the content based on rules
        $this->validate();

        try {
            $comment = Comment::create([
                'content' => $this->content,
                'post_id' => $this->postId,
                'parent_comment_id' => $this->parentCommentId,
                'depth' => $this->depth,
            ]);

            // Reset input fields after successful save
            $this->reset(['content', 'parentCommentId', 'depth', 'replyingTo']);

            // Success message flash
            session()->flash('success', 'Your comment has been posted successfully.');

            // Notify frontend or other components
            $this->dispatch('commentAdded', $comment);
            if ($comment->parent_comment_id) {
                $this->dispatch('replyAdded', $comment->parent_comment_id);
            }
        
        } catch (Exception $e) {
            session()->flash('error', 'Failed to add comment: ' . $e->getMessage());
        }
    }

    public function render()
    {
        $post = Post::find($this->postId);
        if (!$post) {
            throw new Exception('Post not found');
        }
        $this->maxDepth = Comment::MAX_DEPTH;
        return view('livewire.comments.comment-form', [
            'post' => $post,
            'maxDepth' => $this->maxDepth,
            'replyingTo' => $this->replyingTo,
        ]);
    }
}
