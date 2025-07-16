<article
    tabindex="0"
    aria-label="Comment by {{ $comment->user->name ?? 'Anonymous' }}"
    class="bg-white border border-gray-200 rounded-xl p-5 hover:border-indigo-300 focus-within:border-indigo-400 transition-all duration-200 ease-in-out mb-6"
    x-data="{ openReplyForm: false }"
>
    <!-- Comment header -->
    <header class="flex items-start gap-4 mb-3">
        <img
            src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'Anonymous') }}&background=6366f1&color=fff&size=128"
            alt="{{ $comment->user->name ?? 'Anonymous' }}"
            class="h-10 w-10 rounded-full object-cover flex-shrink-0"
        />
        <div class="flex-grow">
            <h3 class="text-base font-semibold text-gray-900">
                {{ $comment->user->name ?? 'Anonymous' }}
            </h3>
            <time
                class="text-xs text-gray-500"
                datetime="{{ $comment->created_at->toIso8601String() }}"
                title="{{ $comment->created_at->toDayDateTimeString() }}">
                {{ $comment->created_at->diffForHumans() }}
            </time>
        </div>
        <button
            @click="openReplyForm = !openReplyForm"
            aria-label="Reply to comment by {{ $comment->user->name ?? 'Anonymous' }}"
            class="text-sm text-indigo-600 hover:text-indigo-800 focus:outline-none"
        >
            Reply
        </button>
    </header>

    <!-- Comment content -->
    <div class="text-gray-800 text-sm leading-relaxed whitespace-pre-wrap mb-4">
        {{ $comment->content }}
    </div>

    <!-- Reply form -->
    <div
        x-show="openReplyForm"
        x-transition:enter="transition ease-out duration-200"
        x-transition:enter-start="opacity-0 translate-y-1"
        x-transition:enter-end="opacity-100 translate-y-0"
        x-transition:leave="transition ease-in duration-150"
        x-transition:leave-start="opacity-100 translate-y-0"
        x-transition:leave-end="opacity-0 translate-y-1"
        class="mt-3"
    >
        <div class="bg-gray-50 border border-gray-200 rounded p-4">
            <livewire:comments.comment-form
                :postId="$comment->post_id"
                :parentCommentId="$comment->id"
                :key="'reply-form-comment-'.$comment->id.'-'.now()"
            />
        </div>
    </div>

    <!-- Replies -->
    @if($replies && count($replies))
    <section
        aria-label="Replies to comment by {{ $comment->user->name ?? 'Anonymous' }}"
        class="mt-5 space-y-4 border-l border-gray-200 pl-5"
    >
        @foreach($replies as $reply)
            <livewire:comments.comment-item
                :comment="$reply"
                :post="$post"
                :key="'comment-item-'.$reply->id"
            />
        @endforeach
    </section>
    @endif
</article>
