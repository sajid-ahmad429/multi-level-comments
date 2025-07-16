<article class="bg-gray-50 rounded-2xl p-6 shadow ring-1 ring-gray-100 mb-4">
    <header class="flex items-center gap-4 mb-4">
        <img src="https://ui-avatars.com/api/?name={{ urlencode($author->name ?? 'Anonymous') }}"
             class="h-12 w-12 rounded-full" />
        <div>
            <h3 class="font-semibold">{{ $author->name ?? 'Anonymous' }}</h3>
            <small class="text-gray-500">{{ $comment->created_at->diffForHumans() }}</small>
        </div>
    </header>

    <p class="text-gray-700 mb-3">{{ $comment->content }}</p>

    <div class="flex space-x-2">
        <button wire:click="toggleReplies" class="text-sm text-indigo-600 hover:underline">
            {{ $showReplies ? 'Hide Replies' : 'View Replies' }}
        </button>
        <button wire:click="toggleReplyForm" class="text-sm text-indigo-600 hover:underline">Reply</button>
    </div>

    @if($showReplyForm)
        <div class="mt-4">
            @livewire('comments.comment-form', [
                'postId' => $post->id,
                'parentCommentId' => $comment->id
            ], key('reply-form-'.$comment->id))
        </div>
    @endif

    @if($showReplies)
        <div class="mt-4 space-y-4 pl-4 border-l border-indigo-200">
            @foreach($replies as $reply)
                @livewire('comments.comment-item', ['comment' => $reply, 'post' => $post], key('comment-'.$reply->id))
            @endforeach
        </div>
    @endif
</article>
