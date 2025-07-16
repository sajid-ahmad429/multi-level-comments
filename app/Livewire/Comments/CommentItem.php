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
        $randomNames = [
            'Amit Sharma',
            'Priya Patel',
            'Rajesh Kumar',
            'Sneha Gupta',
            'Vikram Singh',
            'Neha Reddy',
            'Rahul Verma',
            'Anjali Nair',
            'Arjun Das',
            'Kiran Mehta',
            'Sunil Joshi',
            'Pooja Sinha',
            'Manish Bhatia',
            'Divya Agarwal',
            'Suresh Pillai',
            'Ritu Chauhan',
            'Deepak Yadav',
            'Meera Iyer',
            'Ravi Deshmukh',
            'Komal Thakur',
            'Abhishek Mishra',
            'Shweta Rao',
            'Gaurav Saxena',
            'Kavita Kapoor',
            'Harsh Vardhan',
            'Nikita Malhotra',
            'Aditya Tripathi',
            'Preeti Kaur',
            'Sanjay Shetty',
            'Isha Dubey',
            'Anil Menon',
            'Tanya Bhatt',
            'Kunal Chawla',
            'Bhavna Joshi',
            'Yash Jain',
            'Pallavi Shah',
            'Rohit Bansal',
            'Sheetal Kulkarni',
            'Ajay Chauhan',
            'Simran Gill',
            'Vikas Ahuja',
            'Aarti Puri',
            'Tarun Kapoor',
            'Nidhi Sharma',
            'Nilesh Shinde',
            'Rekha Yadav',
            'Sameer Sood',
            'Madhuri Rani',
            'Prakash Nayak',
            'Lavanya Reddy'
        ];
        
        return view('livewire.comments.comment-item', [
            'comment' => $this->comment,
            'replies' => $this->replies,
            'author' => $this->comment->user 
                ?? (object)['name' => $randomNames[array_rand($randomNames)]],
        ]);
    }

}
