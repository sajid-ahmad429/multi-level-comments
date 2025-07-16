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
        $this->validate();

        try {
            $comment = Comment::create([
                'content' => $this->content,
                'post_id' => $this->postId,
                'parent_comment_id' => $this->parentCommentId,
                'depth' => $this->depth,
            ]);

            $this->reset(['content', 'parentCommentId', 'depth', 'replyingTo']);
            $this->emit('commentAdded', $comment);
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
