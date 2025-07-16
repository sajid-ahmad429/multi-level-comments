<article 
    tabindex="0" 
    aria-label="Comment by {{ $comment->user->name ?? 'Anonymous' }}"
    class="bg-gray-50 rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-400 transition duration-300">
    
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
            aria-label="Reply to comment by {{ $comment->user->name ?? 'Anonymous' }}"
            class="ml-auto px-3 py-1.5 text-indigo-600 hover:text-indigo-800 font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-400 transition flex items-center gap-1">
            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3M16 14h5l-1.405-1.405" />
            </svg>
            Reply
        </button>
    </header>

    <div class="text-gray-800 leading-relaxed whitespace-pre-wrap mb-6">
        {{ $comment->content }}
    </div>

    @if($comment->replies->isNotEmpty())
    <section 
        aria-label="Replies to comment by {{ $comment->user->name ?? 'Anonymous' }}"
        class="pl-16 space-y-8 border-l-4 border-indigo-300">
        
        @foreach($comment->replies as $reply)
        <article 
            tabindex="0" 
            aria-label="Reply by {{ $reply->user->name ?? 'Anonymous' }}"
            class="bg-white rounded-xl p-4 shadow ring-1 ring-gray-100 focus:outline-none focus:ring-2 focus:ring-indigo-300 transition duration-300">
            
            <header class="flex items-center gap-3 mb-3">
                <img 
                    src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'Anonymous') }}&background=818cf8&color=fff&size=64"
                    alt="{{ $reply->user->name ?? 'Anonymous' }}"
                    class="h-9 w-9 rounded-full object-cover ring-1 ring-indigo-300" />
                
                <div>
                    <h4 class="text-sm font-semibold text-gray-900">
                        {{ $reply->user->name ?? 'Anonymous' }}
                    </h4>
                    <time 
                        class="text-xs text-gray-400"
                        datetime="{{ $reply->created_at->toIso8601String() }}"
                        title="{{ $reply->created_at->toDayDateTimeString() }}">
                        {{ $reply->created_at->diffForHumans() }}
                    </time>
                </div>

                <button 
                    aria-label="Reply to reply by {{ $reply->user->name ?? 'Anonymous' }}"
                    class="ml-auto px-2 py-0.5 text-indigo-600 hover:text-indigo-800 font-semibold rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-300 transition flex items-center gap-1 text-xs">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round" d="M3 10h11M9 21V3M16 14h5l-1.405-1.405" />
                    </svg>
                    Reply
                </button>
            </header>
            
            <p class="text-gray-700 text-sm leading-relaxed whitespace-pre-wrap">
                {{ $reply->content }}
            </p>
        </article>
        @endforeach

        {{-- Optional: Load more replies button --}}
        {{--
        <button
            class="mt-4 text-indigo-600 hover:text-indigo-800 font-semibold text-sm focus:outline-none focus:ring-2 focus:ring-indigo-300 transition">
            Load more replies
        </button>
        --}}
    </section>
    @endif
</article>
