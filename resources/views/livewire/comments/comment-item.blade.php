<article
    tabindex="0"
    aria-label="Comment by {{ $author->name ?? 'Anonymous' }}"
    class="bg-white border border-gray-200 shadow-sm rounded-2xl p-6 hover:shadow-md focus-within:shadow-md transition-all duration-300 ease-in-out mb-6"
    x-data="{ 
        openReplyForm: @entangle('showReplyForm'), 
        openReplies: @entangle('showReplies') 
    }"
>
    <!-- Comment header -->
    <header class="flex items-start gap-4 mb-4">
        <img
            src="https://ui-avatars.com/api/?name={{ urlencode($author->name ?? 'Anonymous') }}&background=6366f1&color=fff&size=128"
            alt="{{ $author->name ?? 'Anonymous' }}"
            class="h-10 w-10 rounded-full object-cover flex-shrink-0 ring-2 ring-indigo-500/20"
        />
        <div class="flex-grow">
            <h3 class="text-sm font-semibold text-gray-900">
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
                class="inline-flex items-center gap-1 text-sm text-gray-500 hover:text-indigo-600 focus:outline-none transition cursor-pointer hover:bg-indigo-50 rounded px-1"
            >
                <svg x-show="!openReplies" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
                <svg x-show="openReplies" xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7"/>
                </svg>
                <span x-text="openReplies ? 'Hide Replies' : 'Show Replies'"></span>
            </button>
            <button
                @click="openReplyForm = !openReplyForm"
                class="inline-flex items-center gap-1 text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none transition cursor-pointer hover:bg-indigo-50 rounded px-1"
            >
                <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 10h4l3 10 4-18 3 8h4" />
                </svg>
                Reply
            </button>
        </div>
    </header>

    <!-- Comment content -->
    <div class="text-gray-700 text-[15px] leading-relaxed whitespace-pre-wrap mb-4">
        {{ $comment->content }}
    </div>

    <!-- Reply form -->
    <div
        x-show="openReplyForm"
        x-transition
        class="mt-3"
    >
        <div class="bg-gray-50 border border-gray-200 rounded-lg p-4">
            <livewire:comments.comment-form
                :postId="$comment->post_id"
                :parentCommentId="$comment->id"
                :key="'reply-form-comment-'.$comment->id.'-'.now()"
            />
        </div>
    </div>

    <!-- Replies -->
    <section
        x-show="openReplies"
        x-transition
        aria-label="Replies to comment by {{ $author->name ?? 'Anonymous' }}"
        class="mt-5 space-y-4 border-l-2 border-gray-100 pl-5"
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
