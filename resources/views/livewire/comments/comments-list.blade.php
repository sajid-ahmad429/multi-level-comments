<div>
    @if($comments->isEmpty())
        <p class="text-gray-600 italic text-center">No comments yet. Be the first to comment!</p>
    @else
        <div class="space-y-10">
            @foreach($comments as $comment)
                {{-- Comment Card --}}
                @livewire('comments.comment-item', ['comment' => $comment], key($comment->id))
            @endforeach
        </div>
    @endif
</div>