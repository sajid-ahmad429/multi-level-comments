<article
    tabindex="0"
    aria-label="Comment by {{ $author->name ?? 'Anonymous' }}"
    class="bg-white border border-gray-200 rounded-xl p-5 hover:border-indigo-300 focus-within:border-indigo-400 transition-all duration-200 ease-in-out mb-6"
    x-data="{ 
        openReplyForm: @entangle('showReplyForm').defer, 
        openReplies: @entangle('showReplies').defer 
    }"
>
    <!-- Comment header -->
    <header class="flex items-start gap-4 mb-3">
        <img
            src="https://ui-avatars.com/api/?name={{ urlencode($author->name ?? 'Anonymous') }}&background=6366f1&color=fff&size=128"
            alt="{{ $author->name ?? 'Anonymous' }}"
            class="h-10 w-10 rounded-full object-cover flex-shrink-0"
        />
        <div class="flex-grow">
            <h3 class="text-base font-semibold text-gray-900">
                {{ $author->name ?? 'Anonymous' }}
            </h3>
            <time
                class="text-xs text-gray-500"
                datetime="{{ $comment->created_at->toIso8601String() }}"
                title="{{ $comment->created_at->toDayDateTimeString() }}">
                {{ $comment->created_at->diffForHumans() }}
            </time>
        </div>
        <div class="flex gap-2">
            <button
                @click="openReplies = !openReplies"
                class="text-sm text-gray-600 hover:text-indigo-600 focus:outline-none"
                x-text="openReplies ? 'Hide Replies' : 'Show Replies'"
            ></button>
            <button
                @click="openReplyForm = !openReplyForm"
                class="text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none"
            >
                Reply
            </button>
        </div>
    </header>

    <!-- Comment content -->
    <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-wrap mb-4">
        {{ $comment->content }}
    </div>

    <!-- Reply form -->
    <div
        x-show="openReplyForm"
        x-transition
        class="mt-3"
    >
        <div class="bg-gray-50 border border-gray-200 rounded p-4">
            <livewire:comments.comment-form
                :postId="$comment->post_id"
                :parentCommentId="$comment->id"
                :key="'reply-form-comment-'.$comment->id.'-'.now()"
                wire:submit.prevent="handleReplySubmitted"
            />
        </div>
    </div>

    <!-- Replies -->
    <section
        x-show="openReplies"
        x-transition
        aria-label="Replies to comment by {{ $author->name ?? 'Anonymous' }}"
        class="mt-5 space-y-4 border-l border-gray-200 pl-5"
    >
        @forelse($replies as $reply)
            <livewire:comments.comment-item
                :comment="$reply"
                :post="$post"
                :key="'comment-item-'.$reply->id"
            />
        @empty
            <p class="text-xs text-gray-500">No replies yet.</p>
        @endforelse
    </section>
</article>