<article 
    tabindex="0" 
    aria-label="Comment by {{ $comment->user->name ?? 'Anonymous' }}"
    class="bg-gray-50 rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-300"
    x-data="{ openReplyForm: false }">

    {{-- Comment header --}}
    <header class="flex items-center gap-4 mb-4">
        <img 
            src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'Anonymous') }}&background=7c3aed&color=fff&size=128"
            alt="{{ $comment->user->name ?? 'Anonymous' }}"
            class="h-12 w-12 rounded-full object-cover ring-2 ring-indigo-400" />
        
        <div>
            <h3 class="text-lg font-semibold text-gray-900">
                {{ $comment->user->name ?? 'Anonymous' }}
            </h3>
            <time 
                class="text-xs text-gray-400" 
                datetime="{{ $comment->created_at->toIso8601String() }}"
                title="{{ $comment->created_at->toDayDateTimeString() }}">
                {{ $comment->created_at->diffForHumans() }}
            </time>
        </div>

        <button 
            @click="openReplyForm = !openReplyForm"
            aria-label="Reply to comment by {{ $comment->user->name ?? 'Anonymous' }}"
            class="ml-auto px-3 py-1.5 text-indigo-600 hover:text-indigo-800 font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3M16 14h5l-1.405-1.405" />
            </svg>
            Reply
        </button>
    </header>

    {{-- Comment content --}}
    <div class="text-gray-800 leading-relaxed whitespace-pre-wrap mb-6">
        {{ $comment->content }}
    </div>

    {{-- Reply Form for this comment --}}
    <div x-show="openReplyForm" x-transition class="mt-3">
        <livewire:comments.comment-form 
            :postId="$comment->post_id"
            :parentCommentId="$comment->id"
            :key="'reply-form-comment-'.$comment->id.'-'.now()"
        />
    </div>

    {{-- Replies --}}
    @if($replies && count($replies))
    <section 
        aria-label="Replies to comment by {{ $comment->user->name ?? 'Anonymous' }}"
        class="pl-16 space-y-8 border-l-4 border-indigo-300 mt-6">
        
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