<div>
    @if($comments->isEmpty())
        <p class="text-gray-600 italic text-center">No comments yet. Be the first to comment!</p>
    @else
        <div class="space-y-10">
            @foreach($comments as $comment)
                {{-- Comment Card --}}
                <article class="bg-gray-50 rounded-2xl p-6 shadow-sm ring-1 ring-gray-100 transition duration-300">
                    <header class="flex items-center gap-4 mb-4">
                        <img
                            src="https://ui-avatars.com/api/?name={{ urlencode($comment->user->name ?? 'Anonymous') }}"
                            alt="{{ $comment->user->name ?? 'Anonymous' }}"
                            class="h-12 w-12 rounded-full object-cover ring-2 ring-indigo-400"
                        />
                        <div>
                            <h3 class="text-lg font-semibold text-gray-900">
                                {{ $comment->user->name ?? 'Anonymous' }}
                            </h3>
                            <time class="text-xs text-gray-400" datetime="{{ $comment->created_at->toIso8601String() }}">
                                {{ $comment->created_at->diffForHumans() }}
                            </time>
                        </div>
                    </header>

                    <div class="text-gray-800 leading-relaxed whitespace-pre-wrap mb-4">
                        {{ $comment->content }}
                    </div>

                    @if($comment->replies->isNotEmpty())
                        <section class="pl-8 border-l-2 border-indigo-300 space-y-6">
                            @foreach($comment->replies as $reply)
                                <article class="bg-white rounded-xl p-4 shadow ring-1 ring-gray-100">
                                    <header class="flex items-center gap-3 mb-2">
                                        <img
                                            src="https://ui-avatars.com/api/?name={{ urlencode($reply->user->name ?? 'Anonymous') }}"
                                            alt="{{ $reply->user->name ?? 'Anonymous' }}"
                                            class="h-8 w-8 rounded-full object-cover ring-1 ring-indigo-300"
                                        />
                                        <div>
                                            <h4 class="text-sm font-semibold text-gray-900">
                                                {{ $reply->user->name ?? 'Anonymous' }}
                                            </h4>
                                            <time class="text-xs text-gray-400" datetime="{{ $reply->created_at->toIso8601String() }}">
                                                {{ $reply->created_at->diffForHumans() }}
                                            </time>
                                        </div>
                                    </header>
                                    <p class="text-gray-700 text-sm whitespace-pre-wrap">{{ $reply->content }}</p>
                                </article>
                            @endforeach
                        </section>
                    @endif
                </article>
            @endforeach
        </div>
    @endif
</div>